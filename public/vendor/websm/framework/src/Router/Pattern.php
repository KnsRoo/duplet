<?php

namespace Websm\Framework\Router;

class Pattern {

    protected $pattern = '/';
    protected $regExp = '//';
    protected $matches = [];

    protected $options = [
        'sensitive' => false,
        'strict'    => false,
        'end'       => true,
    ];

    protected $expForKeys = [];

    public function __construct($pattern = '/', Array $options = []) {

        if(!is_string($pattern))
            throw new \Exception('$pattern is not string.');

        $this->pattern = $pattern;
        $this->setOptions($options);

    }

    public function __toString() {

        return $this->toRegExp();

    }

    public function setOptions(Array $options = []) {

        $this->options = array_merge($this->options, $options);
        return $this;

    }

    public function setExpForKeys(Array $data) {

        $this->expForKeys = $data;
        $this->toRegExp(false);
        return $this;

    }

    protected function clearSlashes(&$pattern) {

        $pattern = preg_replace('/\/+/', '/', $pattern);

    }

    protected function normalize(&$pattern) {

        $pattern = preg_replace('/\\\*\//m', '\/', $pattern);

    }

    protected function setEnding(&$pattern) {

        if(!$this->options['strict']){

            $pattern = preg_replace('/^(.*?)\\\*\/*$/', '$1(?:\/(?=$))?', $pattern);

        }

        if(!$this->options['end']) {

            $pattern = preg_replace('/^(.*?[^\/])$/', '$1(?=\/|$)', $pattern);

        }
        else $pattern .= '$';

    }

    protected function expByKey($key) {

        $exp = &$this->expForKeys[$key];
        return $exp ?: '[^/]+?';

    }

    protected function replaceKeys(&$pattern) {

        $handler = function($matches){

            $exp = $this->expByKey($matches['key']);

            switch($matches['mod']) {

                case '?':

                    return '(?:'.$matches['slash'].'(?<'.$matches['key'].'>'.$exp.'))?';

                case '+':

                    return $matches['slash'].'(?<'.$matches['key'].'>'.$exp.'(?:/'.$exp.')*)';


                case '*':

                    return '(?:'.$matches['slash'].'(?<'.$matches['key'].'>'.$exp.'(?:/'.$exp.')*))?';


                default:

                    return $matches['slash'].'(?<'.$matches['key'].'>'.$exp.')';


            }

        };

        $pattern = preg_replace_callback('/(?<slash>\/)?\:(?<key>\w+)(?<mod>[\+\*\?]?)/iu', $handler, $pattern);

    }

    public function toRegExp($cached = true) {

        if($cached && $this->regExp != '//') return $this->regExp;

        extract($this->options, EXTR_SKIP);

        $pattern = $this->pattern;

        $this->clearSlashes($pattern);

        $this->replaceKeys($pattern);

        $this->setEnding($pattern);

        $this->normalize($pattern);

        $sensitive = $sensitive ? '' : 'i';

        $pattern = '/^'.$pattern.'()/'.$sensitive;

        $this->regExp = $pattern;

        return $pattern;

    }

    public function match($path = '') {

        if(!is_string($path))
            throw new \Exception('$path is not string.');

        if(preg_match($this->toRegExp(), $path, $this->matches))
            return $this->matches;

        else return false;

    }

    public function toPath(Array $replaces = [], $pretty = false) {

        $pattern = $this->pattern;
        $replaces = array_merge($this->matches, $replaces);

        foreach ($replaces as $key => $value){

            $pattern = preg_replace('/\:'.$key.'/ui', $value, $pattern);

        }

        $pattern = preg_replace('/[^\w\/\-\ ]/iu', '', $pattern);

        if (!$pretty) {

            $tmp = array_map('urlencode', explode('/', $pattern));
            $pattern = implode('/', $tmp);

        }

        return $pattern;

    }

}
