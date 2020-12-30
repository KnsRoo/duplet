<?php

namespace Websm\Framework\Sitemap;

interface SitemapGeneratorInterface {

    /**
     * generateAsXML
     *
     * Генерирует sitemap в xml-формате
     * (sitemap.xml)
     * @access public
     * @return String;
     */
    public function generateAsXML();

}
