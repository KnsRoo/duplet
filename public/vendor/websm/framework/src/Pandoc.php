<?php

namespace Websm\Framework;

use Websm\Framework\Exceptions\BaseException;

class Pandoc {

    private $command = '%s --from=%s --to=%s --output=/dev/stdout';
    private $bin;

    /**
     * __construct 
     * 
     * @param string $bin Путь к исполняемому файлу pandoc
     * @access public
     * @return void
     */
    public function __construct($bin = '/usr/local/bin/pandoc') {

        if (!file_exists($bin))
            throw new BaseException('pandoc not found.');

        $this->bin = $bin;

    }

    /**
     * convert
     *
     * Конвертирует стрку одного типа данных в другой.
     *
     * @param string $content Входная строка.
     * @param string $from Тип данных входной строки.
     * @param string $to Желаемый тип данных на выходе.
     * @access public
     * @return string
     *
     * @code
     *
     * $pandoc = new Pandoc;
     * $buffer = $pandoc->convert('<h1>Hello World!!!</h1>', 'html', 'docx');
     *
     * file_put_contents('hello_world.docx', $buffer);
     *
     * @endcode
     */
    public function convert($content, $from, $to) {

        $command = sprintf(
            $this->command,
            escapeshellcmd($this->bin),
            escapeshellarg($from),
            escapeshellarg($to)
        );

        $desc = [
            ['pipe', 'r'],
            ['pipe', 'w'],
            ['pipe', 'w'],
        ];

        $proc = proc_open($command, $desc, $pipes, '/tmp');

        fwrite($pipes[0], $content);
        fclose($pipes[0]);

        $out = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $err = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $status = proc_close($proc);

        if ($status !== 0)
            throw new BaseException("Execution error.\n${err}");

        return $out;

    }

}
