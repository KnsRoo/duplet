<?php

namespace Components\Menu;

use Websm\Framework\Response;
use Model\Page;

class Widget extends Response
{
    
    private $pathToTpl;
    private $header = '63ea991a7a1b415191a2dea45d6ce3b6';
    private $footer = '4874f8b326965cd3d4ebe99314d875d4';
    private $exceptionsHeader = [''];
    private $exceptionsFooter = [''];

    public function __construct(string $template = 'default')
    {
        $this->pathToTpl = __DIR__.'/temp/'.$template.'.tpl';
    }

    public function __toString()
    {
        return $this->getHeaderHtml();
    }

    private function getHeaderHtml()
    {
        $exceptionPages = $this->getExceptionPagesString();

        $pages = Page::find(['cid' => $this->header ])
            ->andWhere([ 'visible' => true ])
            ->andWhere('id NOT IN (' . $exceptionPages .')')
            ->order('sort')
            ->getAll();

        $data = [
            'pages' => $pages,
        ];
        
        return $this->render($this->pathToTpl, $data);
    }


    public function getFooterHtml()
    {
        $exceptionPages = $this->getExceptionPagesString($this->exceptionsFooter);

        $pages = Page::find([ 'cid' => $this->footer ])
            ->andWhere([ 'visible' => true ])
            ->andWhere('id NOT IN (' . $exceptionPages .')')
            ->order('sort')
            ->getAll();

        $data = [
            'pages' => $pages,
        ];
        
        return $this->render($this->pathToTpl, $data);
    }

    private function getExceptionPagesString($exceptionPagesIds = null) 
    {
        $exceptionPagesIds = $exceptionPagesIds ? $exceptionPagesIds : $this->exceptionsHeader;
        $exceptionPagesIdsString = '';
        foreach ($exceptionPagesIds as $exceptionPageId)
            $exceptionPagesIdsString .= '"' . $exceptionPageId . '",';
        $exceptionPagesIdsString = substr($exceptionPagesIdsString, 0, -1);


        return $exceptionPagesIdsString;
    }
}
