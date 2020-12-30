<?php

namespace Front\Layout;

use Websm\Framework\Response;
use Websm\Framework\Di\Container as Di;
use Websm\Framework\AssetsLoader;

use Components\Menu\Widget as Menu;
use Components\Header\Widget as Header;
use Components\Footer\Widget as Footer;
use Components\SettingWidget\Widget as Setting;
use Components\Seo\Seo;

class Layout
{
    private $content;
    private $template = __DIR__.'/temp/default.tpl';

    public function __construct() 
    {
        $this->di = Di::instance();
        $this->assetsLoader = $this->di->get('assetsLoader');
    }

    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }

    public function setTemplate(string $template)
    {
        $this->template = __DIR__.'/temp/'.$template.'.tpl';
        return $this;
    }

    public function setSrc(...$srcNames)
    {
        $this->css = $this->assetsLoader->getInLineCss(...$srcNames);
        $this->js = $this->assetsLoader->getInLineJs(...$srcNames);
        return $this;
    }

    public function renderPage()
    {
        $resp = new Response;

        $social['vkontakte'] = Setting::get('vkontakte') ?? '';
        $social['twitter'] = Setting::get('twitter') ?? '';
        $social['facebook'] = Setting::get('facebook') ?? '';
        $social['instagram'] = Setting::get('instagram') ?? '';
        $social['Одноклассники'] = Setting::get('Одноклассники') ?? '';

        $options['copyright'] = Setting::get('copyright') ?? '';
        $options['Почты'] = Setting::get('Почты') ?? [];
        $options['Телефоны'] = Setting::get('Телефоны') ?? [];
        $options['Адрес'] = Setting::get('Адрес') ?? '';

        $data = [
            'seo' => new Seo(),
            'css' => $this->css ?? '',
            'js' => $this->js ?? '',
            'options' => $options,
            'social' => $social,
            'content' => $this->content,
            'header' => new Header(),
            'footer' => new Footer(),
        ];

        $html = $resp->render($this->template, $data);
        die($html);
    }
}
