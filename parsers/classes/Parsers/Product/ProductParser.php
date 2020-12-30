<?php

namespace Parsers\Product;

use SimpleXMLElement;

use Websm\Framework\Chpu;
use Core\Misc\NewChpu;

use Model\Catalog\Product;

use Exceptions\FileNotFoundException;
use Exceptions\InvalidFileException;
use Exceptions\MainException;

use Parsers\Connection;

class ProductParser
{
    private const ALLOWED_MIME_TYPE = 'text/xml';

    private $xmlWithCategories;
    private $xmlWithPrice;
    private $xmlWithPriceTypes;
    private $priceParser;
    private $productsIds = [];
    private $duplicateIds = [];
    private $sort = [];

    private $errors = [];

    public function __construct(string $pathToCategoriesXml, string $pathToPriceXml)
    {
        try {
            $this->checkFile($pathToCategoriesXml);
            $this->checkFile($pathToPriceXml);
            // $this->checkFile($pathToPriceTypesXml);

            $categoriesFile = file_get_contents($pathToCategoriesXml);
            $this->xmlWithCategories = new SimpleXMLElement($categoriesFile);

             $priceFile = file_get_contents($pathToPriceXml);
             $this->xmlWithPrice = new SimpleXMLElement($priceFile);

            // $priceTypesFile = file_get_contents($pathToPriceTypesXml);
            // $this->xmlWithPriceTypes = new SimpleXMLElement($priceTypesFile);

            $connection = new Connection();
            $this->pdo = $connection->getPdo();
        } catch (FileNotFoundException $e) {
            $e->printError();
        } catch (InvalidFileException $e) {
            $e->printError();
        } catch (\Exception $e) {
            print_r("Error:\n");
            print_r($e->getMessage()."\n");
            print_r("Trace:\n");
            print_r($e->getTraceAsString());
        }
    }

    private function checkFile(string $path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException(
                'File on path "'.$path.'" not found'
            );
        }
    }

    private function getProductsArr()
    {
        return $this->xmlWithCategories
            ->Товары;

    }

    private function getMainPrice(array $prices)
    {
        $result = false;
        foreach ($prices as $price) {
            //if (isset($price['ТипЦены']['Наименование']) &&
               // $price['ТипЦены']['Наименование'] === 'Розничная') {
            if (isset($price['ЦенаЗаЕдиницу'])) {
                $result = preg_replace('/,/', '.', preg_replace('/[^0-9,]/', '', $price['ЦенаЗаЕдиницу']));
            }
        }
        return $result;
    }


    private function getDataFromXmlProduct(SimpleXMLElement $product)
    {
        $rawId = $product->Ид->__toString();
        $productId = md5($rawId);
        $code = $product->Штрихкод->__toString();
        // $vendorCode = $product->Артикул->__toString();
        $name = $product->Наименование->__toString();
        $name = preg_replace('/(\v|\t|\n|\r|\f|\b)+/u', '', $name);
        $name = str_replace(array("'", '"', '`'), array("\'", '\"', '\`'), $name);
        //$baseUnit = $product->БазоваяЕдиница->__toString();
        $baseUnit = '';
        // $baseUnits = $this->xmlWithPriceTypes->Классификатор->ЕдиницыИзмерения;
        // foreach($baseUnits->ЕдиницаИзмерения as $unit) {
        //    if ($unit->Ид->__toString() === $product->БазоваяЕдиница->__toString())
        //        $baseUnit = $unit->НаименованиеКраткое->__toString();
        // }
        // $fullTitle = $product->ПолноеНаименование->__toString();
        // $fullTitle = '';
        // foreach($product->ЗначенияРеквизитов->ЗначениеРеквизита as $requisit) {
        //     if ($requisit->Наименование->__toString() === 'Полное наименование')
        //         $fullTitle = $requisit->Значение->__toString();
        // }
        $rawGroupId = $product->Группы->Ид->__toString();
        $groupId = md5($rawGroupId);
        $rawImage = $product->Картинка->__toString();
        $image = $rawImage ? md5(
            str_replace('import_files/', '', $rawImage)
        ) : null;
        $mainPrice = $this->priceParser->get($rawId);
         // $prices = $this->priceParser
         //     ->get($rawId);

         // if (gettype($prices) === 'boolean')
         //    $mainPrice = 0.00;
         // else
         //     $mainPrice = $this->prices[]

        return [
            'Штрихкод' => $code,
            // 'Артикул' => $vendorCode,
            'Наименование' => $name,
            'БазоваяЕдиница' => $baseUnit,
            // 'ПолноеНаименование' => $fullTitle,
            'Группа' => $rawGroupId,
            'productId' => $productId,
            'groupId' => $groupId,
            'image' => $image,
            'mainPrice' => $mainPrice,
        ];
    }

    private function arrToProps(array $array)
    {
        $newArr = [];
        foreach ($array as $key => $value) {
            $key = preg_replace('/(\v|\s|\t|\n|\r|\f|\b)+/u', '', $key);
            $value = preg_replace('/(\v|\s|\t|\n|\r|\f|\b)+/u', '', $value);
            $key = str_replace(array("'", '"'), array("\'", '\"'), $key);
            $value = str_replace(array("'", '"'), array("\'", '\"'), $value);

            if (gettype($value) === 'string') {
                $newArr[$key] = [
                    'type' => 'string',
                    'value' => $value,
                ];
                continue;
            }
            $newArr[$key] = [
                    'type' => 'json',
                    'value' => $value,
                ];
        }

        $newArr = json_encode($newArr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $newArr = str_replace('\\\\', '\\', $newArr);
        return $newArr;
    }

    private function getProductSort(string $groupId)
    {
        if (!isset($this->sort[$groupId])) {
            $maxSortProduct = Product::find([ 'cid' => $groupId ])
                ->order('`sort` DESC')
                ->get();
            if ($maxSortProduct->isNew()) {
                $this->sort[$groupId] = 1;
                return 1;
            }
            $this->sort[$groupId] = $maxSortProduct->sort;
        }

        ++$this->sort[$groupId];
        return $this->sort[$groupId];
    }

    private function parseOne(SimpleXMLElement $product)
    {
        $data = $this->getDataFromXmlProduct($product);

         $product = Product::find([ 'id' => $data[ 'productId' ] ])
             ->get();

         if (!$product->isNew())
             $this->duplicateIds[] = "'" . $product->id . "'";

         $product->scenario('update');
         
         if ($product->isNew()) {
             $product->scenario('create');
             $product->id = $data['productId'];
             $product->sort = $this->getProductSort($data['groupId']);
             $product->cid = $data['groupId'];
         }
        unset($data['productId']);

        $product->code = $data['Штрихкод'];
        unset($data['Штрихкод']);

        $product->title = $data['Наименование'];
        unset($data['Наименование']);

        $product->hash = md5($product->id);

        if (!$product->picture) {
                $product->picture = $data['image'];
        }
        unset($data['image']);

        $product->date = time();

        if ($data['mainPrice'] === false) {
             $this->errors[$product->id] = [
                 'title' => $product->title,
                 'message' => 'Unable to take main price for this product',
             ];
             return;
        }

        $product->price = floatval($data['mainPrice']);
         unset($data['mainPrice']);

        if (!$product->price) {
             $product->visible = false;
        }

        $product->props = $this->arrToProps($data);

        Chpu::inject($product, [ 'cid' => 'category' ]);

        $param = [];
        $param['id'] = $product->id === null ? 'null' : "'" . $product->id . "'";
        $param['cid'] = $product->cid === null ? 'null' : "'" . $product->cid . "'";
        $param['code'] = $product->code === '' ? 'null' : "'" . $product->code . "'";
        $param['title'] = $product->title === '' ? 'null' : "'" . $product->title . "'";
        $param['meta_title'] = $product->meta_title === '' ? 'null' : "'" . $product->meta_title . "'";
        $param['keywords'] = $product->keywords === '' ? 'null' : "'" . $product->keywords . "'";
        $param['price'] = $product->price;
        $param['hash'] = "'" . $product->hash . "'";
        $param['preview'] = $product->preview === '' ? 'null' : "'" . $product->preview . "'";
        $param['about'] = $product->about === '' ? 'null' : "'" . $product->about . "'";
        $param['picture'] = $product->picture ?? 'null';
        $param['visible'] = $product->visible ? 1 : 0;
        $param['sort'] = $product->sort ?? 'null';
        $param['chpu'] = $product->chpu === '' ? 'null' : "'" . $product->chpu . "'";
        $param['props'] = $product->props === '' ? 'null' : "'" . $product->props . "'";

        $sql = "(".implode(', ', $param)."),";
        file_put_contents(__DIR__.'/sql_products.txt', $sql ,FILE_APPEND);

        if (!$product->save()) {
            $this->errors[$product->id] = [
                'title' => $product->title,
                'message' => 'Unable to save product',
                'ModelErrors' => $product->getErrors(),
            ];
        } else {
            if (($key = array_search($product->id, $this->productsIds)) !== false) {
                unset($this->productsIds[$key]);
            }
        }
    }

    private function getProductsIds()
    {
        $products = Product::find()
            ->columns('id')
            ->genAll();

        $idsArr = [];

        foreach ($products as $product) {
            $idsArr[] = $product->id;
        }

        return $idsArr;
    }

    private function clearDuplicatesProducts()
    {
        /*foreach ($this->duplicateIds as $productId) {
            Product::find([ 'id' => $productId ])
                ->get()
                ->delete();
        }*/


        $sql = "DELETE FROM `catalog_product` WHERE id IN (" . implode(', ', $this->duplicateIds) . ");";

        $stm = $this->pdo->prepare($sql);
        $result = $stm->execute();
        if(!$result){
            die(var_dump($stm->errorInfo()));
        }
    }

    public function parse()
    {
        $this->productsIds = $this->getProductsIds();
        $this->priceParser = new PriceParser($this->xmlWithPrice);
        $this->priceParser
             ->parse();

        $productsArr = $this->getProductsArr();

        // $progressBarTotal = $productsArr->count();
        // $progressBar = new ProgressBar($progressBarTotal, 'Product parser');

        foreach ($productsArr->Товар as $product) {
            $this->parseOne($product);
            //$progressBar->makeStep();
        }

        if ($this->duplicateIds)
           $this->clearDuplicatesProducts();

         $sql_start = "INSERT INTO `catalog_product` (`id`, `cid`, `code`, `title`, `meta_title`, `keywords`, `price`, `hash`, `preview`, `about`, `picture`, `visible`, `sort`, `chpu`, `props`) VALUES ";
         $sql = rtrim(file_get_contents(__DIR__.'/sql_products.txt'), ",") . ';';
         $stm = $this->pdo->prepare($sql_start.$sql);

         $result = $stm->execute();
         if(!$result){
             die(var_dump($stm->errorInfo()));
         }

         if ($this->errors) {
             print_r("\r ERR \n");
             print_r($this->errors);
        }
         unlink(__DIR__.'/sql_products.txt');

        // $progressBar->close($this->errors);

        /* $productSort = new ProductsSort(); */
        /* $productSort->sort(); */
    }
}
