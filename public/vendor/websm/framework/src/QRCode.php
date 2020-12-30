<?php

namespace Websm\Framework;

class QRCode {

    private $bin;
    private $tmpIn;
    private $tmpOut;
    private $errorCorector = 'L';
    private $version = 1;
    private $dpi = 72;
    private $command = '%s -l %s -v %d -d %d -o %s';

    /**
     * __construct
     *
     * @param string $bin Путь к исполняемому файлу qrencode
     * @access public
     */
    public function __construct($bin = '/usr/local/bin/qrencode') {

        if(!file_exists($bin))
            throw new BaseException('qrgencode not found.');

        $this->bin = $bin;
        $this->tmpIn = tempnam(sys_get_temp_dir(), 'qrencode');
        $this->tmpOut = tempnam(sys_get_temp_dir(), 'qrencode');

    }

    /**
     * __destruct
     *
     */
    public function __destruct() {

        @unlink($this->tmpIn);
        @unlink($this->tmpOut);

    }

    /**
     * SetVersion
     * Set version of QrCode
     * @param int $version
     */
    public function setVersion($version = 1) {

        if(!is_numeric($version))
            throw new BaseException('version must be number.');

        $this->version = $version;

    }

    /**
     * SetDpi
     * Set dpi for QrCode
     * @param int $dpi
     */
    public function setDpi($dpi = 72) {

        if(!is_numeric($dpi))
            throw new BaseException('dpi must be number.');

        $this->dpi = $dpi;

    }

    /**
     * SetErrorCorrector
     * Specify error correction level from L (lowest) to H (highest).
     * @param string $errorCorector
     */
    public function setErrorCorrector($errorCorector = 'L') {

        $optionsErrorCorrector = [ 'L', 'M', 'Q', 'H' ];

        $errorCorector = strtoupper($errorCorector);

        if(!in_array($errorCorector, $optionsErrorCorrector))
            throw new BaseException('Wrong param ' . L . ' for error correctot it can accept L, M, Q, H');

        $this->errorCorector = $errorCorector;

    }

    /**
     * generateQr
     *
     * Read string from  file encode in base64
     * If $readFromFile is true it will be read from file
     *
     * @param string $from
     * @param bool $readFromFile
     * @return string
     */
    public function generateQr($from, $readFromFile = false) {

        $command = $this->command;

        $out = $this->tmpOut;

        if($readFromFile && !file_exists($from))
            throw new BaseException('file ' . $from . ' does not exists.');
        else if($readFromFile && file_exists($from))
            $command .= ' -r %s ';
        else
            $command .= ' %s ';

        $command = sprintf(
            $command,
            escapeshellcmd($this->bin),
            $this->errorCorector,
            $this->version,
            $this->dpi,
            escapeshellarg($out . '.png'),
            escapeshellarg($from)
        );

        exec($command);

        return base64_encode(file_get_contents($out . '.png'));

    }

}
