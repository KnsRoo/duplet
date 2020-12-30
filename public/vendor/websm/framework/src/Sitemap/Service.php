<?php

namespace Websm\Framework\Sitemap;

/**
 * Service 
 *
 * Сервис для объединения генераторов карты сайта.
 * 
 */
class Service {

    private $generators = [];
    /* for returning different types - look _Sitemap */
    /* private $returnType = 'XML'; */

    /**
     * addGenerator 
     *
     * Добавляет генератор карты сайта.
     * 
     * @param SitemapGeneratorInterface $generator 
     * @access public
     * @return Service
     */
    public function addGenerator(SitemapGeneratorInterface $generator) {

        $this->generators[] = $generator;

        return $this;

    }

    /**
     * getResult 
     *
     * Вернет карту сайта в виде строки.
     * 
     * @access public
     * @return String
     *
     * @code
     *
     * $sitemap = new Sitemap\Service;
     * $sitemap->addGenerator(PagesSitemapGenerator($domain));
     *
     * echo $sitemap->getResult(); // String
     *
     * @endcode
     */
    public function getResult() {

        $generators = $this->generators;

        $ret = '';
        foreach ($generators as $generator) {

            $ret .= $generator->generateAsXML();

        }

        return $ret;

    }

}
