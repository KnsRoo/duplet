<?php

namespace Components\SettingWidget;

use Model\Setting;

class Widget {

    public static function get($name){

        $prop = Setting::find([ 'name' => $name ])->get();

        if($prop->isNew() || !$prop->content)
            return '';

        $content = json_decode($prop->content, true);
 
        return $content['value'] ?? '';

    }

}

?>
