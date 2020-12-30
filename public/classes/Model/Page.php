<?php

namespace Model;

use Back\Files\Config as FilesConfig;

use Websm\Framework\Db\ActiveRecord;
use Websm\Framework\Path\PathProviderInterface;

use DateTime;

use Model\FileMan\File;
use Websm\Framework\StringF;

class Page extends ActiveRecord implements PathProviderInterface
{
    use Tags\TagsTrait;

    public static $table = 'page';

    public $title = '';
    public $core_page = null;
    public $static = 0;
    public $announce = null;
    public $picture = null;
    public $icon = null;
    public $date = '';
    public $visible = 0;
    public $id;
    public $cid;
    public $text = '';
    public $keywords = '';
    public $sort = 0;
    public $hash = '';
    public $chpu = '';
    public $props;

    public function getRules()
    {
        return [
            [
                ['id', 'cid'], 'match', 'exp' => '/^(\w+)?$/',
                'message' => 'Не коректный идентификатор.'
            ],
            ['sort', 'int'],
            ['title', 'stripTags'],
            [
                'title', 'length', 'on' => 'create', 'min' => 2,
                'message' => 'Для созания раздела, название должно быть более 2х символов'
            ],
            [
                'title', 'length', 'on' => 'update', 'min' => 2,
                'message' => 'Для изменения раздела, название должно быть более 2х символов'
            ],
            ['core_page', 'native'],
            /* ['chpu', 'chpuFormat'], */
            [
                'chpu', 'length', 'min' => 1,
                'message' => 'Ссылка не может быть пустой.'
            ],
            [['hash', 'static'], 'pass'],
            ['text', 'pass', 'on' => ['update' ,'create']],
            ['announce', 'native', 'on' => 'update'],
            ['announce', 'stripTags'],
            [
                'announce', 'length', 'max' => 300,
                'message' => 'Текст анонса не должен превышать 300 символов.'
            ],
            ['announce', 'pass', 'on' => 'update'],
            ['keywords', 'stripTags'],
            ['sort', 'int', 'on' => 'sort', 'message' => 'Значение должно быть целым положительным числом'],
            ['sort', 'limit', 'on' => 'sort', 'min' => 0, 'message' => 'Значение должно быть целым положительным числом'],
            ['visible', 'int', 'on' => 'visibility'],
            ['visible', 'limit', 'on' => 'visibility', 'min' => 0, 'max' => 1],
            ['picture', 'native'],
            ['icon', 'native'],
            ['date', 'date', 'from' => 'd.m.Y', 'to' => 'Y-m-d', 'on' => 'update'],
            ['date', 'pass', 'on' => 'sort'],
            ['tags', 'serializeTagsValidator'],
            ['props', 'pass']
        ];
    }

    public function chpuFormat($field, $params = [])
    {
        $this->$field = trim($this->$field, '/');
    }

    /**
     * belongs Проверит принадлежность к разделу.
     * 
     * @param mixed $id 
     * @access public
     * @return bool
     */
    public function belongs($id = null)
    {
        if ($id == $this->cid) return true;

        $page = $this->getParent();

        if (!$page) return false;
        else return $page->belongs($id);
    }

    public function getDate($format = 'd.m.Y')
    {
        return (new DateTime($this->date))
            ->format($format);
    }

    public function getExtraPropertiesArray()
    {
        $res = json_decode($this->props, true);
        return $res ?: [];
    }
    public function getFormatedDate($format = '%d.%m.%Y', $locale = null)
    {
        $current = setlocale(LC_TIME, null);
        setlocale(LC_TIME, $locale);

        $date = strftime($format, $this->getDate('U'));

        setlocale(LC_TIME, $current);

        return $date;
    }

    public function getAnnounceText($min = 200)
    {
        if ($this->announce) return $this->announce;
        else return $this->getFormatedText($min);
    }

    public function getFormatedText($min = 200)
    {
        return StringF::cutToADot($this->text, 200);
    }

    public function getPicture($res = '700x700')
    {
        $picture = null;
        $file = File::find(['id' => $this->picture])->get();
        if ($file->isPicture())
            $picture = $file->getPicture($res);

        $prefix = FilesConfig::PREFIX_PATH . '/';

        return $picture ? $prefix . $picture : null;
    }

    public function getImageStyle($res = '700x700')
    {
        $picture = $this->getPicture($res);
        $style = $picture ? 'style="background-image:url(\'' . $picture . '\');"' : '';

        return $style;
    }

    public function getIcon()
    {
        $icon = null;
        $file = File::find(['id' => $this->icon])->get();

        if (!$file->isVPicture()) return null;

        $icon = $file->getName();
        $prefix = FilesConfig::PREFIX_PATH . '/';

        return $icon ? $prefix . $icon : null;
    }

    public function getParent()
    {
        if (!$this->cid) return false;
        return self::find(['id' => $this->cid])->get();
    }

    public function getPreviewFile()
    {
        $picture = null;
        $file = \Model\FileMan\File::find(['id' => $this->picture])->get();

        if ($file->isPicture() || $file->isVideo()) return $file;
        else return false;
    }

    public function subPages()
    {
        return Page::find(['cid' => $this->id])
            ->order('sort');
    }

    public function getSubPages()
    {
        return Page::find(['cid' => $this->id])
            ->order('sort')
            ->genAll();
    }

    public function getVisibleSubPages()
    {
        return Page::find(['cid' => $this->id])
            ->andWhere(['visible' => 1])
            ->order('sort')
            ->genAll();
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getRef()
    {
        return $this->chpu;
    }

    public function isRoot()
    {
        return $this->cid === null;
    }
}
