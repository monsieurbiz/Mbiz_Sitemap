# Mbiz_Sitemap

This extension simplifies the sitemap.xml generation.

## How it works

It is very simple.

Every 30 minutes, according to the cron setup in `app/etc/config.xml` the sitemaps will be generated.

By default there is not sitemap defined. You can add sitemaps by observing the event `mbiz_sitemap_generate_sitemap` and add a sitemap to the index in your code.

### Example

This is an observer of the event `mbiz_sitemap_generate_sitemap`.

```php
class Acme_Demo_Model_Observer
{
    public function generateSitemaps(Varien_Event_Observer $observer)
    {
        // Fill the sitemap
        $sitemap = Mage::getModel('mbiz_sitemap/sitemap');
        $collection = Mage::getResourceModel('acme_demo/article_collection');
        foreach ($collection as $article) {
            $sitemap->addUrl(
                $article->getUrl(), // URL
                date('c', strtotime($article->getUpdatedAt())), // Last Updated
                'monthly', // Frequency
                0.8 // Priority
            );
        }
      
        // Generate the XML file
        $sitemap->generate(
            Mage::getBaseDir() . DS . 'sitemaps' . DS . 'articles.xml', // The XML file
          	Mage::getUrl('', [ // The URL
            	'_direct' => 'sitemaps/articles.xml',
            	'_type' => Mage_Core_Model_Store::URL_TYPE_DIRECT_LINK,
        	])
        );
        $observer->getIndex()->addSitemap($sitemap);
    }
}
```



## Troubleshooting

### Where is the sitemap index?

You can find the index there: `/sitemap-index-CODE.xml` where `CODE` is the store's code. By default it is `/sitemap-index-default.xml`.

For now the module generates only for the store with `store_id=1`.

### How can I change the schedule?

You can change the schedule by updating the configuration with your own module. By now it is not possible to change it using the admin panel.

### Where do I need to generate the sitemaps?

You choose where to generate the sitemap in your observer.

By default the module creates the directory `/sitemaps`. So you can generate them in it.

But if you want to generate your sitemaps somewhere else you can, it will work well.

### Can I generate the sitemaps myself?

Yes, just run this code:

```php
Mage::getSingleton('mbiz_sitemap/cron')->generateSitemaps();
```

## License

See [LICENSE](https://raw.githubusercontent.com/monsieurbiz/Mbiz_TrackingTags/master/LICENSE).

## Maintainer

This module is maintained by [Monsieur Biz](https://monsieurbiz.com).