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

    private $logfile = 'error.log';

    public function errorLog($error, $data){
        $text = '';
        $now = date("Y-m-d h:i:s");
        switch ($error){
            case 1: $text = " ".$data['id']." ".$data['title']." WARNING! No Image founded for query ".$data['subject']; break;
            case 2: $text = " ".$data['id']." ".$data['title']." WARNING! No Attributes founded in ".$data['subject']." Try next image"; break;
            case 3: $text = " ".$data['id']." ".$data['title']." WARNING! No href founded in ".$data['subject']." Try next image"; break;
            case 4: $text = " ".$data['id']." ".$data['title']." WARNING! Image has unsupported format ".$data['subject']." Try next image"; break;
            case 5: $text = " ".$data['id']." ".$data['title']." ERROR! Error for adding to SQlite Db Image ".$data['subject']; break;
            case 6: $text = " ERROR! ".$data['id']." Error executing query ".$data['stm']." Details: ".$data['stm_error']; break;
            case 7: $text = " ".$data['id']." ".$data['title']." WARNING! Request failed for URL ".$data['subject']." Try next image"; break;
        }
        $text = $now.$text."\n";
        file_put_contents(__DIR__.'/'.$this->logfile, $text, FILE_APPEND);
    }

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

        if (!$result){
            $this->errorLog(6, [
                                'subject' => $stm,
                                'stm_error' => $stm->errorInfo()
                                ]);
            die(var_dump($stm->errorInfo()));
        } else {
            //unlink($file);
        }
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

    public function findElement($string, $offset = 0){ 
        $finded = mb_strpos($string,'<a class="thumb"', $offset);
        if ($finded === false){
            return false;
        }
        $newhtml = mb_substr($string, $finded);
        $finded = mb_strpos($newhtml,"</a>");
        $element = mb_substr($newhtml, 0, $finded+4); 
        return [$finded+4+$offset, $element];
}

    public function findImage($product){
        $productName = $product['title'];
        //$productName = str_replace(' ','+',$productName);
        $productName = mb_ereg_replace('\\\"','',$productName);
        $newhtml = file_get_contents('https://www.bing.com/images/search?sp=-1&pq=%u0442%u0438%u0440%u043e%u043a%u0441%u0438%u043d&sc=8-8&cvid=D92F74779459403799EA96C297AC6C2C&tsc=ImageBasicHover&q='.urlencode($productName).'&qft=+filterui:imagesize-large&form=IRFLTR&first=1');
        #$newhtml = file_get_contents('https://www.bing.com/images/search?q='.urlencode($productName).'&form=QBLH&sp=-1&pq=%D1%82%D0%B8%D1%80%D0%BE%D0%BA%D1%81%D0%B8%D0%BD&sc=8-8&qs=n&cvid=D92F74779459403799EA96C297AC6C2C&first=1&tsc=ImageBasicHover');
        $noresults = mb_strpos($newhtml, 'не найдены.');

        if ($noresults != false){
            $this->errorLog(1, ['id' => $product['id'],
                                'title' => $product['title'],
                                'subject' => $productName
                                ]);
            return;
        }

        // $finded = mb_strpos($newhtml,'<a class="thumb"');
        
        // $newhtml = mb_substr($newhtml, $finded);
        

        // $finded = mb_strpos($newhtml,"</a>");
        // $newhtml = mb_substr($newhtml, 0, $finded+4);

        $offset = 0;
        $img = null;
        $ext = null;

        while (true){
            $element = $this->findElement($newhtml,$offset);

            //$file = file_put_contents(__DIR__.'/file.html',$element);

            if (!$element) {
                $this->errorLog(1,['id' => $product['id'],
                                   'title' => $product['title'],
                                   'subject' => $productName
                                   ]);
                break;
            }

            $xml = simplexml_load_string($element[1]);
            $json = json_encode($xml);
            $json = (array)json_decode($json);

            if (!$json['@attributes']){
                $this->errorLog(2,['id' => $product['id'],
                                   'title' => $product['title'],
                                   'subject' => $xml
                                   ]);
                $offset += $element[0];
                continue;
            }
            if (!$json['href']){ 
                $offset += $element[0];
                $this->errorLog(3,['id' => $product['id'],
                                   'title' => $product['title'],
                                   'subject' => $xml
                                   ]);
                continue; 
            }

            $img = file_get_contents($json['href']);
            if (!$img) {
                $offset += $element[0];
                $this->errorLog(7,['id' => $product['id'],
                                   'title' => $product['title'],
                                   'subject' => $json['href']
                                   ]);
                continue; 
            }
            $ext = NULL;

            if (strpos($json['href'],'.JPG')){
                $ext = ".jpg";
            } 
            else if (strpos($json['href'],'.PNG')){
                $ext = ".png";
            }
            else if (strpos($json['href'],'.JPEG')){
                $ext = ".jpeg";
            }
            else if (strpos($json['href'],'.jpg')){
                $ext = ".jpg";
            }  
            else if (strpos($json['href'],'.png')){
                $ext = ".png";
            }
            else if (strpos($json['href'],'.jpeg')){
                $ext = ".jpeg";
            }

            if (!$ext){
                $this->errorLog(4,['id' => $product['id'],
                                   'title' => $product['title'],
                                   'subject' => $json['href']
                                   ]);
                continue;       
            }

            break;
        }

        $newPathToPicture = self::DUPLET_DATA_PATH . "/" .md5(uniqid()).$ext;
        
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
            $this->errorLog(5,['id' => $product['id'],
                               'title' => $product['title'],
                               'subject' => $newPathToPicture
                               ]);
            unlink($newPathToPicture);
            return;
        }

        $this->addRowToQuery($product['id'], $pictureId);
    }

    public function parse()
    {
        unlink(__DIR__.'/'.$this->logfile);

        $sqlQuery = 'SELECT `id`,`title` FROM `catalog_product` WHERE `picture` IS NULL LIMIT 10';

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
