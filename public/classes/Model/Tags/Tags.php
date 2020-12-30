<?php

namespace Model\Tags;

use Websm\Framework\Db\ActiveRecord;

class Tags extends ActiveRecord {

    public static $table = 'tags';

    public $id = null;
    public $title = '';
    public $static = false;

    public function getRules() {
        return [
            [ 'id', 'required' ],
            [ 'title', 'pass' ],
        ];
    }

    public static function addTags(Array $tags) {

        foreach ($tags as $tag) {

            $tag = trim(preg_replace('/\:/', '', $tag));

            if (!$tag) continue;

            if (self::find(['title' => $tag])->exists()) 
                continue;

            $ar = new self('create');

            $ar->id = md5(uniqid());
            $ar->title = $tag;

            $ar->save();

        }

    }

    public static function deleteTags(Array $tags) {

        return self::query()
            ->delete()
            ->where(['id' => $tags])
            ->execute();

    }

}
