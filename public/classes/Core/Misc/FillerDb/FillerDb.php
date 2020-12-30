<?php

declare(strict_types=1);

namespace Core\Misc\FillerDb;

use Faker\Factory;
use Model\Catalog\Product;
use Model\Page;
use Websm\Framework\Chpu;
use Websm\Framework\Db\ActiveRecord;

class FillerDb
{
    private $faker;

    public function __construct(string $locale = 'ru_RU')
    {
        $this->faker = Factory::create($locale);
    }

    public function fillTablePage(int $count = 4, string $cid = null, bool $isCreateSubPage = true, int $nesting = 1)
    {
        for ($i=0; $i < $count; $i++) { 
            $faker = $this->faker;
            $modelPage = new Page();
            
            $modelPage->scenario('create');
            $modelPage->id = md5(uniqid());        
            $modelPage->title = $faker->text(20);
            $modelPage->announce = $faker->text(10);
            $modelPage->date = $faker->dateTimeBetween('-1 years', 'now');
            $modelPage->text = $faker->realText();
            $modelPage->cid = $cid;
            $modelPage->hash = md5($modelPage->title);
            //$modelPage->picture = $faker->imageUrl();
            Chpu::inject($modelPage);
            $modelPage->chpu = '/' . $modelPage->chpu;

            if(!$modelPage->save()){
                var_dump($modelPage->_error, $modelPage);
                die();
            }
            $modelPage->chpu = Chpu::inject($modelPage);
            if ($cid == null && $isCreateSubPage && $nesting != 0) {
                $this->fillTablePage(4, $modelPage->id, $isCreateSubPage, $nesting);
            }

            //echo "<img src='$modelPage->picture'/>";
        }
    }

    public function fillTableProduct(int $count = 4, string $cid = null, bool $isCreateSubPage = true, int $nesting = 1)
    {
        for ($i=0; $i < $count; $i++) { 
            $faker = $this->faker;
            $modelProduct = new Product();
            
            $modelProduct->scenario('create');
            $modelProduct->id = md5(uniqid());        
            $modelProduct->title = $faker->text(20);
            $modelProduct->announce = $faker->text(10);
            $modelProduct->date = $faker->dateTimeBetween('-1 years', 'now');
            $modelProduct->text = $faker->realText();
            $modelProduct->cid = $cid;
            $modelProduct->hash = md5($modelProduct->title);
            //$modelProduct->picture = $faker->imageUrl();
            Chpu::inject($modelProduct);
            $modelProduct->chpu = '/' . $modelProduct->chpu;

            if(!$modelProduct->save()){
                var_dump($modelProduct->_error, $modelProduct);
                die();
            }
            $modelProduct->chpu = Chpu::inject($modelProduct);
            if ($cid == null && $isCreateSubPage && $nesting != 0) {
                $this->fillTablePage(4, $modelProduct->id, $isCreateSubPage, $nesting);
            }

            //echo "<img src='$modelProduct->picture'/>";
        }
    }
}