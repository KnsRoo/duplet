<?php

namespace Websm\Framework\PDF;

class PhantomPDF {

    private $command = '%s %s %s %s';
    private $phantom;
    private $jsScript = __DIR__ . '/../Assets/phantom.js';
    private $from;
    private $isTemp = false;

    /**
     * __construct 
     * 
     * @param string $bin Путь к исполняемому файлу phantomjs
     * @access public
     * @return void
     */
    public function __construct($phantom = '/usr/local/bin/phantomjs') {

        if (!is_string($phantom))
            throw new \Exception('Type not string.');

        if (!file_exists($phantom))
            throw new \Exception('PhantmoJs not found.');

        if (!file_exists($this->jsScript))
            throw new \Exception('JsScript not found.');

        $this->phantom = $phantom;

    }

    /**
     * from
     *
     * Конвертирует стрку одного типа данных в другой.
     *
     * @param string $content Входная строка/Путь до файла.
     * @access public
     * @return string
     *
     * @code
     *
     * $pdf = new PhantomPDF;
     * $pdf->from('<h1>Hello World!!!</h1>');
     *
     * @endcode
     */
    public function from($value) {

        if (!is_string($value))
            throw new \Exception('Type not string.');

        if (is_file($value) && file_exists($value)) {
            $this->from = $value;
        } else {
            $this->from = sys_get_temp_dir() . 'pandoc.html';
            file_put_contents($this->from, $value);
            $this->isTemp = true;
        }

    }

    public function render() {

        if (!$this->phantom || !$this->from || !$this->jsScript)
            throw new \Exception('Some property not set.');

        $this->to = '/tmp/' . uniqid() . '.pdf';

        $command = sprintf(
            $this->command,
            escapeshellarg($this->phantom),
            escapeshellarg($this->jsScript),
            escapeshellarg($this->from),
            escapeshellarg($this->to)
        );

        exec($command);
        return file_get_contents($this->to);

    }

    public function __destruct() {

		@unlink($this->to);
        if ($this->isTemp)
            @unlink($this->from);
    }

}
