<?php

namespace Components\Sliders\Main;

use Websm\Framework\Response;
use Model\Page;
use Components\SettingWidget\Widget as Setting;

class Widget extends Response
{
    private const MAIN_PAGE_ID = '5bf02b5320894e2d45e2101aa6b946c2';
    private $pathToTpl;

    public function __construct(string $template = 'default')
    {
        $this->pathToTpl = __DIR__ . '/temp/' . $template . '.tpl';
    }

    public function __toString()
    {
        return $this->getHtml();
    }

    private function getHtml()
    {
        $sliderPages = Page::find(['cid' => self::MAIN_PAGE_ID])
            ->andWhere(['visible' => true])
            ->order('sort')
            ->getAll();

        $countSlider = count($sliderPages);

        $social['vk'] = Setting::get('vk') ?? '#';
        $social['instagram'] = Setting::get('instagram') ?? '#';

        $data = [
            'sliders' => $sliderPages,
            'social' => $social,
            'countSlider' => $countSlider
        ];

        return $this->render($this->pathToTpl, $data);
    }
}
