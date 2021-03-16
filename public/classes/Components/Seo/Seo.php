<?php

namespace Components\Seo;

use Websm\Framework\Response;

class Seo {

    private static $title = 'Оружейный магазин "Дуплет"';
    private static $keywords = 'Дуплет';
    private static $description = 'Оружейный магазин "Дуплет"';
    private static $meta_title = 'Оружейный магазин "Дуплет"';

    public function __toString()
    {
        return $this->getHtml();
    }

    public static function setContent($title = '', $keywords = '', $description = '', $meta_title = '')
    {
        self::$title = htmlspecialchars(strip_tags($title));
        self::$keywords = htmlspecialchars(strip_tags($keywords));
        self::$description = htmlspecialchars(strip_tags($description));
        self::$meta_title = htmlspecialchars(strip_tags($meta_title? $meta_title:$title));

    }

    private function getHtml() {
        
        $res = new Response;

        $data = [
            'title' => self::$title,
            'keywords' => self::$keywords,
            'description' => self::$description,
            'meta_title' => self::$meta_title,
        ];

        $html = $res->render(__DIR__.'/temp/default.tpl', $data);
        return $html; 
    }

}
