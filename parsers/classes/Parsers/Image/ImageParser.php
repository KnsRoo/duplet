<?php

namespace Parsers\Image;

use SQLite3;

use Parsers\ProgressBar;
use Parsers\Connection;

class ImageParser
{
    const DUPLET_DATA_PATH = '/usr/local/www/sites/duplet.shop/public/data';

    private $ffarmPdo = null;
    private $imagesPdo = null;
    private $imagesDataPath = null;
    private $ffarmProducts = [];
    private $otherDbProducts = [];

    private $batchSize= 1000;
    private $batch = 0;
    private $filePrefix = 0;

    public function __construct()
    {
        $this->imagesDataPath = '/usr/local/www/sites/duplet.shop/public/data';

        $connection = new Connection();
        $this->Pdo = $connection->getPdo();
    }

    private function saveProductsImages($file)
    {
        $sqlStart = "INSERT INTO `catalog_product` (`id`, `picture`) VALUES ";
        $sql = rtrim(file_get_contents($file), ",");
        $sqlEnd =" ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `picture` = VALUES(`picture`);"; 

        $stm = $this->Pdo->prepare($sqlStart . $sql . $sqlEnd);
        $result = $stm->execute();

        if (!$result)
            die(var_dump($stm->errorInfo()));

        unlink($file);
    }

    private function addRowToQuery(string $productId, string $pictureName)
    {
        $params = [];
        $params['id'] = (int)$productId;
        $params['picture'] = "'" . $pictureName . "'";

        $sql = "(".implode(', ', $params)."),";
        file_put_contents(__DIR__.'/sql_images_'.$this->filePrefix.'.txt', $sql ,FILE_APPEND);
        if ($this->batch > $this->batchSize){
            $this->filePrefix++;
            $this->batch = 0;
        }
        $this->batch++;
    }

    private function copyImage(array $product, string $pictureName)
    {

        $productName = $product['title'];
        $pathToPicture = glob($this->imagesDataPath . '/' . $pictureName . '*');

        if (empty($pathToPicture))
            return null;

        $pathToPicture = $pathToPicture[0];

        $pictureBaseName = basename($pathToPicture);
        $newPathToPicture = self::DUPLET_DATA_PATH . '/' . $pictureBaseName;

        if (!copy($pathToPicture, $newPathToPicture)) {
            echo "Не удалось добавить картинку для продукта $productName";
            die();
        }

        $db = new SQLite3(self::DUPLET_DATA_PATH . '/' . 'data.db');

        $pictureId = pathinfo($newPathToPicture)['filename'];
        $pictureSize = filesize($newPathToPicture);

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $pictureType = $finfo->file($newPathToPicture);

        $pictureExt = pathinfo($newPathToPicture)['extension'];
        $date = date('U');

        if (!$db->exec("
            INSERT OR REPLACE INTO file(id, cid, title, size, type, ext, date)
            VALUES ('$pictureId', '02448a40417695cf6d42cd872eae7203', '$productName', $pictureSize, '$pictureType', '$pictureExt', $date);
        ")) {
            echo "Не удалось добавить картинку для продукта $productName";
            die();
        }

        $this->addRowToQuery($product['id'], $pictureId);
    }

    public function findImage($product){
        $productName = $product['title'];
        $productName=str_replace(' ','+',$productName);
        $newhtml = file_get_contents('https://www.bing.com/images/search?q='.urlencode($productName).'&form=QBLH&sp=-1&pq=%D1%82%D0%B8%D1%80%D0%BE%D0%BA%D1%81%D0%B8%D0%BD&sc=8-8&qs=n&cvid=D92F74779459403799EA96C297AC6C2C&first=1&tsc=ImageBasicHover');
        $finded = mb_strpos($newhtml,'<a class="thumb"');
        
        $newhtml = mb_substr($newhtml, $finded);
        

        $finded = mb_strpos($newhtml,"</a>");
        $newhtml = mb_substr($newhtml, 0, $finded+4);

        $file = file_put_contents(__DIR__.'/file.html',$newhtml);

        $xml = simplexml_load_string($newhtml);
        $json = json_encode($xml);
        $json = (array)json_decode($json);
        $json = (array)$json['@attributes'];

        if (!$json['href']) return;

        $img = file_get_contents($json['href']);
        $ext = ".jpg";
        if (strpos($json['href'],'.png')){
            $ext = ".png";
        }

        $newPathToPicture = __DIR__."/../../../../public/data/".md5(uniqid()).$ext;
        
        file_put_contents($newPathToPicture, $img);

        $db = new SQLite3(self::DUPLET_DATA_PATH . '/' . 'data.db');

        $newpathToPicture = glob($newPathToPicture. '*');

        $pictureId = pathinfo($newPathToPicture)['filename'];
        $pictureSize = filesize($newPathToPicture);

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $pictureType = $finfo->file($newPathToPicture);

        $pictureExt = pathinfo($newPathToPicture)['extension'];
        $date = date('U');

        if (!$db->exec("
            INSERT OR REPLACE INTO file(id, cid, title, size, type, ext, date)
            VALUES ('$pictureId', '02448a40417695cf6d42cd872eae7203', '$productName', $pictureSize, '$pictureType', '$pictureExt', $date);
        ")) {
            echo "Не удалось добавить картинку для продукта $productName";
            die();
        }

        $this->addRowToQuery($product['id'], $pictureId);
    }

    public function parse()
    {
        $sqlQuery = 'SELECT `id`,`title` FROM `catalog_product` WHERE `picture` IS NULL';

        $stm = $this->Pdo->prepare($sqlQuery);
        $result = $stm->execute();

        if (!$result)
            die(var_dump($stm->errorInfo()));

        $products = $stm->fetchAll();
        $this->Products = $products;

        $productsCount = count($products);

        $progressBar = new ProgressBar($productsCount, 'images');

        foreach ($products as $product) {
            $this->findImage($product);

            $progressBar->makeStep();
        }

        $files = glob(__DIR__.'/sql_images_*.txt');

        foreach ($files as $file) {
             $this->saveProductsImages($file);
        }

        $progressBar->close();
    }
}
