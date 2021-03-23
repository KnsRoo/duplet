<?php

namespace Components\Footer;

use Websm\Framework\Response;

use Components\SettingWidget\Widget as Setting;
use Components\Menu\Widget as Menu;

class Widget extends Response
{
    
    private $pathToTpl;

    public function __construct(string $template = 'default')
    {
        $this->pathToTpl = __DIR__.'/temp/'.$template.'.tpl';
    }

    public function __toString()
    {
        return $this->getHtml();
    }

    private function getHtml()
    {
        $social['vkontakte'] = Setting::get('vkontakte') ?? '';
        $social['twitter'] = Setting::get('twitter') ?? '';
        $social['facebook'] = Setting::get('facebook') ?? '';
        $social['instagram'] = Setting::get('instagram') ?? '';
        $social['Одноклассники'] = Setting::get('Одноклассники') ?? '';

        $options['copyright'] = Setting::get('Copyright') ?? '';
        $options['Почты'] = Setting::get('Почты') ?? [];
        $options['Телефоны'] = Setting::get('Телефоны') ?? [];
        $options['Адрес'] = Setting::get('Адрес') ?? '';

        $data = [
            'options' => $options,
            'social' => $social,
            'menu' => new Menu('footer')
        ];

        return $this->render($this->pathToTpl, $data);
    }
}
