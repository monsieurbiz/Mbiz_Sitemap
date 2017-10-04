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
 * Sitemap Model
 *
 * @package Mbiz_Sitemap
 */
class Mbiz_Sitemap_Model_Sitemap
{

    /**
     * List of urls of the sitemap
     *
     * @var array
     */
    protected $_urls = [];

    /**
     * Location's URL
     *
     * @var string
     */
    protected $_locationUrl;

    /**
     * Generate the sitemap with everything in it
     * <p>According to Google, it has to contains less than 50k URLs
     * and be less than 10Mo before gzip compression.</p>
     *
     * @param string $filename Filename (from root) of the sitemap
     * @param string $locationUrl Public URL of the futur sitemap
     *
     * @return bool Success of generation (TRUE) or failure (FALSE)
     */
    public function generate($filename, $locationUrl)
    {
        $this->_locationUrl = $locationUrl;

        // File's content
        $layoutXml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">%s</urlset>";
        $urlXml = "<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%s</priority></url>";

        $urlset = '';
        foreach ($this->_urls as $url) {
            $urlset .= sprintf(
                $urlXml,
                $url['loc'],
                $url['lastmod'],
                $url['changefreq'],
                $url['priority']
            );
        }

        // Create the sitemap
        if (!$fp = fopen($filename, 'w')) {
            $generated = false;
        } else {
            $writed = fwrite($fp, sprintf($layoutXml, $urlset)) !== false;
            $closed = fclose($fp);
            $generated = $writed && $closed;
        }

        return $generated;
    }

    /**
     * Add an URL to the sitemap
     *
     * @param string $url The public URL
     * @param string $lastUpdated The last updated string
     * @param string $freq Update frequency
     * @param float $priority The url's priority
     *
     * @return Mbiz_Sitemap_Model_Sitemap
     */
    public function addUrl($url, $lastUpdated = 'now', $freq = 'daily', $priority = 0.8)
    {
        $this->_urls[] = [
            'loc' => $url,
            'lastmod' => $lastUpdated,
            'changefreq' => $freq,
            'priority' => $priority,
        ];
        return $this;
    }

    /**
     * Retrieve the location's URL
     *
     * @return string
     *
     * @throws Exception
     */
    public function getLocation()
    {
        if (!is_string($this->_locationUrl)) {
            throw new \Exception('Please generate the sitemap before trying to get its URL ;).');
        }
        return $this->_locationUrl;
    }

}