<?php

namespace Websm\Framework;

use Imagick;
use Exception;

class ImgResize {

    public static function resize(
        $in, $out, $width = null, $height = null,
        $bestFit = true, $cropZoom = false, $fill = 'white',
        $blur = 0.9, $filterType = Imagick::FILTER_POINT
    ){

        if(!is_string($in) || !$in)
            throw new Exception('$in is not string or $in is empty string.');

        if(!is_string($out) || !$out)
            throw new Exception('$out is not string or $out is empty string.');

        $imagick = new Imagick();
        $imagick->readImage($in);

        if(is_null($width))
            $width = $imagick->getImageWidth();

        if(is_null($height))
            $height = $imagick->getImageHeight();

        $imagick->resizeImage($width, $height, $filterType, $blur, $bestFit);

        $cropWidth = $imagick->getImageWidth();
        $cropHeight = $imagick->getImageHeight();

        if(preg_match('/\.(jpg|jpeg)$/i',$out)) {

            $imagick->setImageCompression(Imagick::COMPRESSION_ZIP);
            $imagick->setImageCompressionQuality(75);

            $bg = new Imagick();
            $bg->newImage($cropWidth, $cropHeight, $fill);
            $bg->compositeImage($imagick, Imagick::COMPOSITE_DEFAULT,0,0);
            $imagick = $bg;

        }

        if ($cropZoom) {

            $newWidth = $cropWidth / 2;
            $newHeight = $cropHeight / 2;

            $imagick->cropimage(
                $newWidth,
                $newHeight,
                ($cropWidth - $newWidth) / 2,
                ($cropHeight - $newHeight) / 2
            );

            $imagick->scaleimage(
                $imagick->getImageWidth() * 4,
                $imagick->getImageHeight() * 4
            );

        }

        $imagick->stripImage();
        $imagick->writeImage($out);
        $imagick->clear();
        $imagick->destroy();

    }

}
