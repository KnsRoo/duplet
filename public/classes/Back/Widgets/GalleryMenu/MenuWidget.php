<?php

namespace Back\Widgets\GalleryMenu;

class MenuWidget {

    private static $title = '';

    public static function setContent($str) {

        self::$title = $str;

    }

    public static function getHtml() {

        return self::$title;

    }

}
