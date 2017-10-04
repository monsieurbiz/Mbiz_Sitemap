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
 * Cron Model
 *
 * @package Mbiz_Sitemap
 */
class Mbiz_Sitemap_Model_Cron
{

    /**
     * Generate sitemaps and the index
     */
    public function generateSitemaps()
    {
        // Start emulation
        /* @var $emulator Mage_Core_Model_App_Emulation */
        $emulator = Mage::getModel('core/app_emulation');
        $initial = $emulator->startEnvironmentEmulation(Mage_Core_Model_App::DISTRO_STORE_ID);

        // Create sitemaps directory
        (new Varien_Io_File)
            ->setAllowCreateFolders(true)
            ->createDestinationDir(Mage::getBaseDir() . DS . 'sitemaps')
        ;

        // The index
        /* @var $sitemapIndex Mbiz_Sitemap_Model_Sitemap_Index */
        $sitemapIndex = Mage::getModel('mbiz_sitemap/sitemap_index');

        // Generate sitemaps
        Mage::dispatchEvent('mbiz_sitemap_generate_sitemap', [
            'index' => $sitemapIndex,
        ]);

        // Generate the index
        $filename = Mage::getBaseDir() . DS . 'sitemap-index-' . Mage::app()->getStore()->getCode() . '.xml';
        $sitemapIndex->generate($filename);

        // Stop emulation
        $emulator->stopEnvironmentEmulation($initial);
    }

}