<?php

namespace Core\Misc\MiniPager;

use Websm\Framework\Db\Qb;
use Websm\Framework\Response;
use Websm\Framework\Router\Pattern;;

class MiniPager extends Response {
    
    protected $onclick = "";

    public static function init(){ return new self; }


    public static function makePager(
        $itemsCount,
        $itemsCountOnPage,
        $activePage = 1,
        $onclick = "",
        $uri = "") {


        $pagesCount = round($itemsCount / $itemsCountOnPage);

        
        $data['pagesCount'] = $pagesCount;
        $data['activePage'] = $activePage;

        $nextPage = 1;
        $previousPage= 1; 

        if($activePage == $pagesCount) {

            $nextPage = $pagesCount;
            $previousPage = $nextPage - 1;
        }
        else
        {
            if($activePage > 1) {
                $nextPage = $activePage + 1;
                $previousPage = $activePage - 1; 
            }
            elseif($activePage == 1) {
                $nextPage = $activePage + 1;
            }
        }

        $nextPage = preg_replace("/:page/", $nextPage, $onclick);
        $previousPage = preg_replace("/:page/", $previousPage, $onclick);

        $data['nextPage'] = $nextPage . ';';
        $data['previousPage'] = $previousPage . ';';
        $date['uri'] = $uri;
         
        $responce = new Response;
        return $responce->render(__DIR__.'/temp/pager.tpl', $data);
    }
}
