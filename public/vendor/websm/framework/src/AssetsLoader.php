<?php

namespace Websm\Framework;

use Websm\Framework\Exceptions\NotFoundException;

class AssetsLoader
{
    private $store;
    private $pathToAssets;

    /**
     * Инцилизирует класс
     * 
     * @param string $pathToAssets путь до файла assets.json
     * @return void
     */

    public function __construct(string $pathToAssets = 'assets/assets.json')
    {
        $this->pathToAssets = $pathToAssets;
        $this->init();;
    }

    private function init()
    {
        if (file_exists($this->pathToAssets)) {
            $file = file_get_contents($this->pathToAssets);
            $this->store = json_decode($file, true);
        } else {
            throw new NotFoundException("$this->pathToAssets not found", 404);
        }
    }
    /**
     * Возращет строку подключения CSS
     *
     * @param [array|string] ...$pagesName Имена страниц
     * @return string
     */
    public function getInlineCss(...$pageNames): string
    {
        $result = [];
        foreach ($pageNames as $pageName) {
            # code...
            if (isset($this->store[$pageName]['css'])) {
                $jsonCss = $this->store[$pageName]['css'];
                $arrCss = is_array($jsonCss) ? $jsonCss : [$jsonCss];

                $result[] = $this->wrapCss($arrCss);
            }
        }
        return implode("\n", $result);
    }
    /**
     * Возращет строку подключения JS
     *
     * @param [array|string] ...$pagesName Имена страниц
     * @return string
     */
    public function getInlineJs(...$pagesName): string
    {
        $result = [];
        foreach ($pagesName as $pageName) {
            if (isset($this->store[$pageName]['js'])) {
                $jsonJs = $this->store[$pageName]['js'];
                $arrJs = is_array($jsonJs) ? $jsonJs : [$jsonJs];

                $result[] = $this->wrapJs($arrJs);
            }
        }

        return implode("\n", $result);
    }
    /**
     * Создаёт тэг <script> с заданным url
     *
     * @param array $arrayPathToJs
     * @return string
     */
    private function wrapJs(array $arrayPathToJs): string
    {
        $result = [];
        foreach ($arrayPathToJs as $pathToJs) {
            $result[] =  '<script src="' . $pathToJs . '"></script>';
        }

        return implode("\n", $result);
    }
    /**
     * Создаёт тэг <style> с заданным url
     *
     * @param array $arrayPathToJs
     * @return string
     */
    private function wrapCss(array $arrayPathToCss): string
    {
        $result = [];
        foreach ($arrayPathToCss as $pathToCss) {
            $result[] = '<link rel="stylesheet" href="' . $pathToCss . '"/>';
        }

        return implode("\n", $result);
    }
}