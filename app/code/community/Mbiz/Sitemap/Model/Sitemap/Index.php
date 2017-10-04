<?php
/**
 * This file is part of Mbiz_Sitemap for Magento.
 *
 * @license See LICENSE file distributed with this source code.
 * @author Jacques Bodin-Hullin <@jacquesbh> <j.bodinhullin@monsieurbiz.com>
 * @category Mbiz
 * @package Mbiz_Sitemap
 * @copyright Copyright (c) 2017 Monsieur Biz (http://monsieurbiz.com/)
 */

/**
 * Sitemap_Index Model
 *
 * @package Mbiz_Sitemap
 */
class Mbiz_Sitemap_Model_Sitemap_Index
{

    /**
     * List of sitemaps
     *
     * @var array
     */
    protected $_sitemaps = [];

    /**
     * Add a sitemap to the index
     *
     * @param Mbiz_Sitemap_Model_Sitemap $sitemap Sitemap to add
     *
     * @return Mbiz_Sitemap_Model_Sitemap_Index
     */
    public function addSitemap(Mbiz_Sitemap_Model_Sitemap $sitemap)
    {
        $this->_sitemaps[] = $sitemap;
        return $this;
    }

    /**
     * Generate the Sitemap index
     *
     * @param string $filename Filename (from root dir) of the sitemap to generate
     *
     * @return bool Success (TRUE) or failure (FALSE)
     */
    public function generate($filename)
    {
        // File's content
        $layoutXml
            = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
%s
</sitemapindex>
XML;
        $sitemapXml
            = <<<XML
<sitemap>
    <loc>%s</loc>
    <lastmod>%s</lastmod>
</sitemap>
XML;

        $sitemapindex = '';
        foreach ($this->_sitemaps as $sitemap) {
            $sitemapindex .= sprintf(
                $sitemapXml,
                $sitemap->getLocation(),
                Mage::getSingleton('core/date')->gmtDate('c')
            );
        }

        // Create the sitemap index
        if (!$fp = fopen($filename, 'w')) {
            $generated = false;
        } else {
            $writed = fwrite($fp, sprintf($layoutXml, $sitemapindex)) !== false;
            $closed = fclose($fp);
            $generated = $writed && $closed;
        }

        return $generated;
    }

}