<?php

namespace Front\Main;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\Di\Container as Di;

use Model\Catalog\Product;
use Model\Catalog\Group;
use Model\Page;

use Components\SettingWidget\Widget as Setting;

class Controller extends Response
{
    private $di;
    private $layout;
    private const NEWS_ID = "e813e4e7e7d947c5b0c14b75d82fa6a4";
    private const STOCKS_ID = "ab333c698371edf1e25d6d8a410f14ee";
    private const NOVELTY_ID = "f578a942a0e6460daa8e139c05a6e208";
    private const POPULAR_ID = "e79aeab994d47a21eb8b2ed2477bfe74";

    public function __construct()
    {
        $this->di = Di::instance();
        $this->layout = $this->di->get('layout');
    }

    public function getRoutes()
    {
        $group = Router::group();

        $group->addGet('/', [$this, 'getContent']);

        return $group;
    }

    public function getContent()
    {

        $new = Product::byTags(['Новинки'])->getAll();

        $news = Page::find(['cid' => self::NEWS_ID])
            ->limit(3)
            ->order('`date` DESC')
            ->getAll();

        $stocks = Page::find(['cid' => self::STOCKS_ID])
            ->limit(2)
            ->order('`date` DESC')
            ->getAll();

        $novelty = Page::find(['id' => self::NOVELTY_ID])
                ->get();

        $popular = Page::find(['id' => self::POPULAR_ID])
                ->get();

        $sliders = [
            (Object)[ 'id' => 'novelty',
              'link' => Router::byName('api:pages:v1:subpages')->getURL(['id' => self::NOVELTY_ID]),
              'title' => $novelty->title,
              'description' => $novelty->announce
            ],
            (Object)[ 'id' => 'popular',
              'link' => Router::byName('api:pages:v1:subpages')->getURL(['id' => self::POPULAR_ID]),
              'title' => $popular->title,
              'description' => $popular->announce
            ],
        ];

        $data = [ 
            'new' => $new,
            'news' => $news,
            'stocks' => $stocks,
            'sliders' => $sliders,
            'numbers' => Setting::get('Телефоны') ?? []
        ];

        $html = $this->render(__DIR__ . '/temp/default.tpl', $data);

        $this->layout
            ->setSrc('index')
            ->setContent($html);
    }
}
