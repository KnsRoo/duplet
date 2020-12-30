<?php

namespace Components\Sliders\Products;

use Websm\Framework\Response;
use Model\Catalog\Product;

class Widget extends Response
{
    private $pathToTpl;
    private $tag;
    private $titleSlider;
    private $classCss;

    public function __construct(string $tag, string $titleSlider, string $classCss, string $template = 'default')
    {
        $this->pathToTpl = __DIR__.'/temp/'.$template.'.tpl';
        $this->tag = $tag;
        $this->titleSlider = $titleSlider;
        $this->classCss = $classCss;
    }

    public function __toString()
    {
        return $this->getHtml();
    }

    private function getHtml()
    {
        $products = Product::ByTags([$this->tag])
            ->andWhere(['visible' => true ])
            ->getAll();
            
        if(empty($products))
            return '';

        $data = [
            'products' => $products,
            'title' => $this->titleSlider,
            'classCss' => $this->classCss,
        ];

        return $this->render($this->pathToTpl, $data);
    }
}
