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

        // المنتجات
        Product::product()->chunk(200, function ($products) use ($sitemap) {
            $lastMod = $product->updated_at ?? now();
            foreach ($products as $product) {
                $url = Url::create("/posts/{$product->id}")
                    ->setLastModificationDate($lastMod)
                    ->addImage($product->getImage('images'))
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);
                $sitemap->add($url);
            }
        });

        // حفظ الملف في المسار العام
        $sitemap->writeToFile(public_path('products.xml'));
        $sitemap = Sitemap::create();
        Product::jobs()->chunk(200, function ($products) use ($sitemap) {
            $lastMod = $product->updated_at ?? now();
            foreach ($products as $product) {
                $url = Url::create("/jobs/{$product->id}")
                    ->setLastModificationDate($lastMod)
                    /*->addImage($product->getImage('images'))*/
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);
                $sitemap->add($url);
            }
        });
        $sitemap->writeToFile(public_path('jobs.xml'));
        $this->info('Sitemap generated successfully!');
    }

}
