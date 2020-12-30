<?php

namespace Core\Misc\Pager;

use Websm\Framework\Db\Qb;
use Websm\Framework\Response;

class Pager extends Response{

    protected $_active = 6; /**< Активная. */
    protected $_pages = null; /**< Хранит общее количество страниц. */
    protected $_cache = ''; /**< Для кеширования. */
    protected $_href = ''; /**< Для кеширования. */

    protected $_templates = [
        'layout' => __DIR__.'/temp/layout.tpl',
        'item'   => __DIR__.'/temp/item.tpl',
    ];

    public static function init(){ return new self; }

    /**
     * @brief Устанавливает номер активной страницы.
     * @param $active Номер типа int.
     * @return Pager
     */

    public function active($active = 1){

        if(!is_int($active))
            throw new \Exception('$active type not integer.');

        $this->_active = (int)$active;
        return $this;

    }

    /**
     * @brief Устанавливает количество страниц на основе запроса к базе данных.
     * @param $qb Обьект Qb (QueryBuilder).
     * @param $items Количество элементов на странице.
     * @param $active Задаёт смещение выборки страниц
     * @return Pager
     */

    public function pagesQb(Qb $qb, $items = 20, $active = 1){


        if(!is_int($items) && $items)
            throw new \Exception('$items type not integer or $items <= 0.');

        $active = $active ?: 1;

        $count = $qb->count();
        $this->_pages = ceil($count/$items);

        $this->_active = $active;
        $qb->limit([($active - 1) * $items, $items]);

        return $this;

    }

    /**
     * @brief Устанавливает количество страниц.
     * @param $pages
     * @return Pager
     */

    public function pages($pages){

        $this->_pages = (int)$pages;
        return $this;

    }

    /**
     * @brief Устанавливает ссылку.
     * @param $href Ссылка содержащая шаблон для замены ":page".
     * @return Pager
     *
     * Пример использования:
     * @code
     *  $pager = Pager::init()
     *      ->href('/catalog-45/page-number-:page');
     *  // :page будет заменено на номер страницы.
     * @endcode
     */

    public function href($href = ''){

        $this->_href = $href;
        return $this;

    }


    /**
     * @brief Генерирует массив страниц.
     * @return Array
     */

    public function getRawPager(){

        if($this->_pages <= 1)return [];
        $tmp = range(1, $this->_pages);
        $out = [];

        if($this->_pages > 6){

            $this->_active <= 4 && $out = array_slice($tmp, 0, 4);

            $this->_active >= ($this->_pages - 3) &&
                $out = array_slice($tmp, $this->_pages - 4, 4);

            ($this->_active > 3 && $this->_active < ($this->_pages - 2)) &&
                $out = array_slice($tmp, $this->_active - 2, 3);

        }
        else $out = $tmp;
        return $out;

    }

    protected function getHref($page = 1){

        return preg_replace('/\:page/i', $page, $this->_href);

    }

    /**
     * @brief Создаёт постраничку по заданому шаблону
     * @param $pages Массив страниц
     * @param $template Массив кастомных шаблонов
     * @param $arrow (1/0) выводит стрелки
     * @return String
     * */

    public function getPager(Array $pages, Array $template = [], $arrow = 1) {

        $template = array_merge($this->_templates, $template);
        $items = '';

        if(!$pages) return;

        if($arrow)
            $items .= $this->render($template['item'], [
                'href'   => $this->getHref(($this->_active > 1 ? $this->_active - 1 : 1)),
                'symbol' => '&#9668',
                'class'  => '']
            );

        if($this->_active > 3 && $this->_pages > 6) {
            $items .= $this->render($template['item'], ['href' => $this->getHref(1), 'symbol' => 1, 'class' => '']);
            $items .= $this->render($template['item'], ['href' => '#', 'symbol' => '...', 'class' => 'dots']);
        }

        foreach($pages as &$item)
            $items .= $this->render(
                $template['item'], [
                'href'   => $this->getHref($item),
                'symbol' => $item,
                'class'  => $item == $this->_active ? ' active' : '']
            );

        if($this->_active < $this->_pages - 2 && $this->_pages > 6) {
            $items .= $this->render($template['item'], ['href' => '#', 'symbol' => '...', 'class' => 'dots']);
            $items .= $this->render($template['item'], ['href' => $this->getHref($this->_pages), 'symbol' => $this->_pages, 'class' => '']);
        }

        if($arrow)
            $items .= $this->render($template['item'], [
                'href'   => $this->getHref(($this->_active < $this->_pages ? $this->_active + 1 : $this->_pages )),
                'symbol' => '&#9658',
                'class'  => '']
            );

        return $this->render($template['layout'], ['items' => $items]);

    }

    /**
     * @brief Вернёт ранее сгенерированную верстку.
     * @return HTML
     */

    public function getCached(){ return $this->_cache; }

    /**
     * @brief Вернёт сгенерированную верстку.
     * @return HTML
     *
     * @code
        $pager = Pager::init()
            ->pagesQb($qb, 1, $currentPage)
            ->href('/page-num-:page')
            ->get($template)
     * @endcode
     */

    public function get(Array $template = [], $arrow = true){
        $this->_cache = $this->getPager($this->getRawPager(), $template, $arrow);
        return $this->_cache;
    }

}
