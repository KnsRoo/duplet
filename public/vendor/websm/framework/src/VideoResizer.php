<?php

namespace Websm\Framework;

class VideoResizer
{
    const QUALITY_480 = ['640x480', '2500k'];
    const QUALITY_360 = ['480x360', '1000k'];

    protected $programm = '/usr/local/bin/ffmpeg';
    protected $vCodec = 'libx264';
    protected $vBitrate = '800k';
    protected $vBufSize = '1200k';
    protected $aBitrate = '384k';
    protected $aTrack = '0';
    protected $debug = false;
    protected $scaleVideo = "-vf scale=\"'w=if(gt(a,16/9),640,-2):h=if(gt(a,16/9),-2,360)'\"";

    /**
     * getResizeCommand 
     *
     * Возвращает готовую команду для конвертирования видео.
     * 
     * @param string $in Имя входного файла.
     * @param string $out Имя файла на выходе.
     * @access public
     * @return string
     */
    public function getResizeCommand($in = 'in.mp4', $out = 'out.mp4')
    {
        $command = $this->programm;
        $command .= ' -y';
        $command .= ' -i ' . escapeshellarg($in);
        $command .= ' -map 0:v:0';
        $command .= ' -map 0:a:' . $this->aTrack;
        $command .= ' -vcodec ' . escapeshellarg($this->vCodec);
        $command .= ' -vsync 1 -async 1';
        $command .= ' -color_primaries 1 -color_trc 1 -colorspace 1';
        $command .= ' ' . $this->scaleVideo;
        $command .= ' -crf 20';
        $command .= ' -preset medium -profile:v high -level 3.1';
        $command .= ' -maxrate ' . $this->vBitrate . ' -bufsize ' . $this->vBufSize;
        $command .= ' -acodec aac -ac 2 -ab ' . $this->aBitrate;
        $command .= ' -f mp4 -movflags +faststart';
        $command .= ' ' . escapeshellarg($out);

        return $command;
    }

    /**
     * setProgramm 
     *
     * Установит путь до исполняемого файла.
     * 
     * @param string $path 
     * @access public
     * @return Object
     */
    public function setProgramm($path = '/usr/local/bin/ffmpeg')
    {
        if (!file_exists($path) || !is_file($path)) {
            throw new \Exception('Programm not found.');
        }

        $this->programm = $path;
        return $this;
    }

    /**
     * setQuality 
     *
     * Устанавливает качество видео на выходе.
     * 
     * @param mixed $size Размер видео на выходе.
     * @param string $bitrate Битрейт видео на выходе.
     * @access public
     * @return Object
     *
     * @code
     *
     * $resizer = new VideoResizer;
     * $resizer->setQuality('1280x720', '5000k');
     *
     * // или
     *
     * $resizer->setQuality(VideoResizer::QUALITY_720);
     *
     * @endcode
     */
    public function setQuality($size)
    {
        if (is_array($size)) {
            // http://uppod.ru/talk_11087  $scale примеры 
            if ($size == self::QUALITY_360) {
                $this->scaleVideo = "-vf scale=\"'w=if(gt(a,16/9),640,-2):h=if(gt(a,16/9),-2,360)'\"";
                $this->vBufSize = '1200k';
                $this->vBitrate = '800k';
                $this->aBitrate = '128k';
            }
            if ($size == self::QUALITY_480) {
                $this->scaleVideo = "-vf scale=\"'w=if(gt(a,16/9),854,-2):h=if(gt(a,16/9),-2,480)'\"";
                $this->vBufSize = '4200k';
                $this->vBitrate = '1200k';
                $this->aBitrate = '128k';
            }
            return $this;
        } else {
            throw new \Exception('$size is not correct.');
        }
    }

    /**
     * setVCodec 
     *
     * Установить кодек для конвертирования видео.
     * 
     * @param string $codec 
     * @access public
     * @return Object
     */
    public function setVCodec($codec = 'libx264')
    {
        $this->vCodec = $codec;
        return $this;
    }


    /**
     * setABitrate 
     *
     * Установит битрейт аудио на выходе.
     * 
     * @param string $bitrate 
     * @access public
     * @return Object
     */
    public function setABitrate($bitrate = '384k')
    {
        if (!preg_match('/^\d+[km]?$/', $bitrate)) {
            throw new \Exception('$bitrate is not correct.');
        }

        $this->aBitrate = $bitrate;
        return $this;
    }

    /**
     * setVBitrate 
     *
     * Установит битрейт видео на выходе.
     * 
     * @param string $bitrate 
     * @access public
     * @return Object 
     */
    public function setVBitrate($bitrate = '5000k')
    {
        if (!preg_match('/^\d+[km]?$/', $bitrate)) {
            throw new \Exception('$bitrate is not correct.');
        }

        $this->vBitrate = $bitrate;
        return $this;
    }

    /**
     * resize 
     *
     * Запускает процесс конвертации видео.
     * 
     * @param string $in Имя входного файла.
     * @param string $out Имя файла на выходе.
     * @access public
     * @return Boolean
     */
    public function resize($in, $out)
    {
        $status = false;
        $message = [];
        $command = $this->getResizeCommand($in, $out);

        exec($command, $message, $status);

        if (!$status) {
            return true;
        } else {
            return false;
        }
    }

    public function getPreviewCommand($in = 'input', $out = 'output', $size = null)
    {
        $command = $this->programm;
        $command .= ' -i ' . escapeshellarg($in);
        $command .= ' -an -ss 00:00:03 -r 1 -vframes 1';
        if ($size) $command .= ' -s ' . escapeshellarg($size);
        $command .= ' -y ' . escapeshellarg($out);

        return $command;
    }

    public function preview($in, $out, $size = null)
    {
        $status = false;
        $message = [];
        $command = $this->getPreviewCommand($in, $out, $size);

        exec($command, $message, $status);

        if (!$status) {
            if (class_exists('Imagick')) ImgResize::resize($out, $out);
            return true;
        } else {
            return false;
        }
    }
}
