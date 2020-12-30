<?php

namespace Components\Pager;

use Websm\Framework\Response;
use Websm\Framework\Db\Qb;
use Websm\Framework\Router\Router;

class Widget {

    const TPLS = __DIR__.'/temp/';

    private $pageNumberParameter;
    private $pagerHtml;
    private $items;

    private $errors = [];

    public function __construct($qb, $countOnPage = 10, $page, $range = 5, $pageNumberParameter = 'page'){

        $this->pageNumberParameter = $pageNumberParameter;

        if(($range % 2) == 0 ){
            ++$range;
        }

        //Количество элементов
        $itemsCount = $qb->count();
        //Количество страниц
        $pages = ceil($itemsCount / $countOnPage);

        //Если запрашиваемая страница больше количества страниц, то = последней возможной странице
        if($page > $pages)
            $page = $pages;

        //Если меньше, то равна 1
        if($page < 1)
            $page = 1;

        //Альтернатива предыдущим двум условиям
        /* $page = $page > $pages ? $pages : $page < 1 ? 1 : $page; */

        $this->items = $qb->limit([(($page - 1) * $countOnPage ), $countOnPage])
             ->getAll();

        if($itemsCount <= $countOnPage){
            $this->pagerHtml = '';
        }
        else{
            $this->renderPager($page, $pages, $range);
        }

    }

    private function renderPager($page, $pages, $range){

        $pagesArr = [$page];

        $halfRange = (($range - 1) / 2);

        for($i = 1; $i <= $halfRange; $i++){
            $pagesArr[] = $page-$i;
            $pagesArr[] = $page+$i;
        }

        sort($pagesArr);

        $delCount = 0;

        foreach($pagesArr as $key => $value){
            if($value < 2){
                unset($pagesArr[$key]);
                ++$delCount;
            }
        }

        sort($pagesArr);

        $maxCount = isset($pagesArr[count($pagesArr) - 1]) ? $pagesArr[count($pagesArr) - 1] : 1;

        for($delCount; $delCount > 0; $delCount--){
            if(($maxCount + $delCount) < ($pages - 2))
                $pagesArr[] = $maxCount + $delCount;
        }

        foreach($pagesArr as $key => $value){
            if( $value > ($pages - 1) ){
                unset($pagesArr[$key]);
                ++$delCount;
            }
        }

        sort($pagesArr);

        $minCount = isset($pagesArr[0]) ? $pagesArr[0] : 1;

        for($delCount; $delCount > 0; $delCount--){
            if(($minCount - $delCount) > 1 )
                $pagesArr[] = $minCount - $delCount;
        }

        sort($pagesArr);

        $minPage = isset($pagesArr[0]) ? $pagesArr[0] : 1;
        $maxPage = isset($pagesArr[count($pagesArr) - 1]) ? $pagesArr[count($pagesArr) - 1] : 1;

        $content = '';

        $res = new Response;
        $router = Router::instance();
        $path = $router->getAbsolutePath();
        /* $rawAction = $path."?".$this->pageNumberParameter."="; */
        $rawAction = $path."?";
        foreach ($_GET as $key => $value) {
            if ($key !== $this->pageNumberParameter) {
                $rawAction .= $key."=".urlencode($value)."&";
            }
        }
        $rawAction .= $this->pageNumberParameter."=";

        if($page > 1){
            $content .= $res->render(self::TPLS.'previous.tpl', [ 'action' => $rawAction.($page-1) ]);
        }

        if($page == 1){
            $content .= $res->render(self::TPLS.'active.tpl', [ 'page' => 1 ]);
        }
        else{
            $content .= $res->render(self::TPLS.'inactive.tpl', [ 'action' => $rawAction."1", 'page' => 1 ]);
        }

        if($minPage > 2){
            if($minPage == 3){
                $content .= $res->render(self::TPLS.'inactive.tpl', [ 'action' => $rawAction."2", 'page' => 2 ]);
            }
            else{
                $content .= $res->render(self::TPLS.'dots.tpl');
            }
        }

        foreach($pagesArr as $p){
            if($p == $page){
                $content .= $res->render(self::TPLS.'active.tpl', [ 'page' => $p ]);
            }
            else{
                $content .= $res->render(self::TPLS.'inactive.tpl', [ 'page' => $p, 'action' => $rawAction.$p ]);
            }
        }

         if($maxPage < ($pages-1) ){
            if($maxPage == $pages-2){
                $content .= $res->render(self::TPLS.'inactive.tpl', [ 'action' => $rawAction.($pages-1), 'page' => ($pages-1) ]);
            }
            else{
                $content .= $res->render(self::TPLS.'dots.tpl');
            }
        }

        if($pages > 1){
            if($page == $pages){
                $content .= $res->render(self::TPLS.'active.tpl', [ 'page' => $pages ]);
            }
            else{
                $content .= $res->render(self::TPLS.'inactive.tpl', [ 'action' => $rawAction.$pages, 'page' => $pages ]);
            }
        }

        if($page < $pages){
            $content .= $res->render(self::TPLS.'next.tpl', [ 'action' => $rawAction.($page+1) ]);
        }

        $this->pagerHtml = $res->render(self::TPLS.'content.tpl', [ 'content' => $content ]);
    }

    public function getItems(){
        return $this->items;
    }

    public function getHtml(){
        return $this->pagerHtml;
    }

}

?>
