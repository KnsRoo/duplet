<?php

namespace Front\Sitemap;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\Di;
use Websm\Framework\Cache\FileCache;

class Sitemap extends Response
{
    private $domain;
    private $protocol = 'https';
    private $rootUrl = '';

    public function __construct()
    {
        $this->domain = (isset($_SERVER['SERVER_NAME']))
            ? $this->protocol.'://'.$_SERVER['SERVER_NAME']
            : null;
    }

    public function getRoutes()
    {
        $group = Router::group();

        $group->addAll('/', [$this, 'getSitemap'])
            ->setName('getSitemapXML');

        return $group;
    }

    public function getSitemap() 
    {
        $pool = new FileCache('cache/');

        $item = $pool->getItem('sitemap');

        if($item->isHit())
        {
            $xml = $item->get();
        }
        else
        {
            $urls = $this->getURLs();
            $xml = '';

            foreach($urls as $url)
            {
                $xml .= $this->render(__DIR__ . '/tpl/item.tpl', ['url' => $url]); 
            }

            $xml = $this->render(__DIR__ . '/tpl/main.tpl', ['body' => $xml]);

            $item->set($xml);
            $item->expiresAfter(60);
            $pool->save($item);
        }

        $res = new Response;

        header("Content-type:application/xml");

        $res->send($xml);
    }

    public function getChpu() {

        $items = [];

        $pages = \Model\Page::find(['visible' => 1])
            ->columns('chpu')
            ->getAll();

        $products = \Model\Catalog\Product::find(['visible' => true])
            ->columns('chpu')
            ->getAll();

        foreach($pages as $item)
        {
            $items[] = $this->getURL($item->chpu);
        }

        foreach($products as $item)
        {
            $items[] = $this->getURL('/catalog/'.$item->chpu);
        }
        return $items;
    }

    public function getURL($url = null)
    {
        if(!$this->domain)
            throw new Exception('Domain is undefined.');

        $loc = $this->domain.$this->rootUrl;

        if ($url)
            $loc .= $url; 

        return $loc;
    }

    public function getURLs()
    {
        $urls = []; 
        $urls[] = $this->getURL();

        $urls = array_merge($urls, $this->getChpu());

        return $urls;
    }
}
