<?php

namespace App\Console\Commands;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap';

    /**
     * Execute the console command.
     */


    public function handle()
    {
        $sitemap = Sitemap::create();

        /* // الصفحات الثابتة
         $staticPages = [
             '/',
             '/about',
             '/contact',
             '/categories',
             // أضف بقية الصفحات هنا
         ];

         foreach ($staticPages as $page) {

             $sitemap->add(
                 Url::create($page)
                     ->setLastModificationDate(now())
                     ->setPriority(0.9)
                     ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
             );
         }*/
        $domain = 'https://web.ali-pasha.com';

        ##################################################################
        ####################  Products #######################################
        #################################################################
        Product::product()->active()->upTo20()->latest()->chunk(200, function ($products) use ($sitemap, $domain) {
            foreach ($products as $product) {
                $lastMod = $product->updated_at ?? now();
                if ($product->getImageSiteMap()) {
                    $url = Url::create("{$domain}/posts/{$product->id}")
                        ->setLastModificationDate($lastMod)
                        ->addImage($product->getImageSiteMap(),"{$product->name}")
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);
                } else {
                    $url = Url::create("{$domain}/posts/{$product->id}")
                        ->setLastModificationDate($lastMod)
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);
                }
                $sitemap->add($url);
            }
        });

        // حفظ الملف في المسار العام
        $sitemap->writeToFile(public_path('products.xml'));

        ##################################################################
        ####################  JOBS #######################################
        #################################################################
      /*  $sitemap = Sitemap::create();
        Product::job()->active()->upTo20()->where('end_date', '>=', now())->latest()->chunk(200, function ($products) use ($sitemap, $domain) {

            foreach ($products as $product) {
                $lastMod = $product->updated_at ?? now();
                $url = Url::create("{$domain}/jobs/{$product->id}")
                    ->setLastModificationDate($lastMod)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);
                $sitemap->add($url);
            }
        });
        $sitemap->writeToFile(public_path('jobs.xml'));*/
        ##################################################################
        ####################  tenders #######################################
        #################################################################
        $sitemap = Sitemap::create();
        Product::service()->active()->latest()->chunk(200, function ($products) use ($sitemap, $domain) {

            foreach ($products as $product) {
                $lastMod = $product->updated_at ?? now();
                $url = Url::create("{$domain}/services/{$product->id}")
                    ->setLastModificationDate($lastMod)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);
                $sitemap->add($url);
            }
        });
        $sitemap->writeToFile(public_path('services.xml'));
        $this->info('Sitemap generated successfully!');
    }

}
