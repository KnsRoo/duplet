<?php

namespace Model\Tags;

trait TagsTrait {

    public $tags = [];

    public function getTags() {

        $tags = $this->tags ?: [];

        if (is_string($tags)) {

            $tags = trim($this->tags, ':');
            if(!$tags) return [];

            $tags = explode(':', $tags);
            return $tags;

        }

        return $tags;

    }

    public function serializeTagsValidator($field, $params) {

        if (is_array($this->tags)) {

            $this->tags = array_map(function ($tag) {

                return preg_replace('/\:/', '', $tag);

            }, $this->tags);

            $tags = implode(':', $this->tags);
            $this->tags = ':'.$tags.':';

        }

    }

    public static function byTags(Array $tags) {

        $tags = Tags::find(['title' => $tags])
            ->genAll();

        $query = self::find('NOT 1');

        $i = 1;
        foreach ($tags as $tag) {

            $tag = $tag->title;
            $key = ':tag_'.$i;
            $tag = '%:'.$tag.':%';

            $query->orWhere('`tags` LIKE '.$key, [$key => $tag]);
            $i++;

        }

        return $query;

    }

    public static function hasNoTags(Array $tags)
    {
        $tags = Tags::find(['title' => $tags])
            ->genAll();

        $query = self::find('NOT 1');

        $i = 1;
        foreach ($tags as $tag) {

            $tag = $tag->title;
            $key = ':tag_'.$i;
            $tag = '%:'.$tag.':%';

            $query->orWhere('`tags` NOT LIKE '.$key, [$key => $tag]);
            $i++;

        }

        return $query;
    }

    public static function getByTags(Array $tags) {

        return self::byTags()->genAll();

    }

    public function hasTag($tag) {

        $tags = $this->getTags();

        return in_array($tag, $tags);

    }

    public function addTags(Array $tags) {

        Tags::addTags($tags);

        $activeTags = $this->getTags();

        foreach ($tags as $tag) {

            $tag = trim($tag);

            if(!$tag) continue;

            if (in_array($tag, $activeTags))
                continue;

            $activeTags[] = $tag;

        }

        $this->tags = $activeTags;

        return $this;

    }

    public function removeTags(Array $tags) {

        $activeTags = $this->getTags();
        $newTags = [];

        foreach($activeTags as $tag) {

            if(!in_array($tag, $tags))
                $newTags[] = $tag;

        }

        $this->tags = $newTags;

        return $this;

    }

    public function getAllTags() {

        return Tags::find()
            ->order('title ASC')
            ->genAll();

    }

    public function setTags(Array $tags) {

        $this->tags = $tags;
        return $this;

    }

    public function clearTags() {

        $this->tags = [];
        return $this;

    }

}
