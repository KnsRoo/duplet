<?php

namespace Parsers\Product;

use SimpleXMLElement;

use Websm\Framework\Chpu;
use Core\Misc\NewChpu;

use Model\Catalog\Product;

use Exceptions\FileNotFoundException;
use Exceptions\InvalidFileException;
use Exceptions\MainException;

use ProgressBar\ProgressBar;

use Parsers\Connection;

class RemainsParser
{
    private const ALLOWED_MIME_TYPE = 'text/xml';

    private $xmlWithRemains;

    private $errors = [];

    public function __construct(string $pathToRemainsXml)
    {
        try {
            $this->checkFile($pathToRemainsXml);

            $remainsFile = file_get_contents($pathToRemainsXml);
            $this->xmlWithRemains = new SimpleXMLElement($remainsFile);

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
        return $this->xmlWithRemains
            ->Товары;

    }

    private function parseOne(SimpleXMLElement $product)
    {

        $id = md5($product->Ид);
        $remains = [];

        foreach($product->Остатки as $remain){
            $key =  $remain->Остаток->НаименованиеСклада->__toString();
            $value = $remain->Остаток->Остаток->__toString();
            $remains[$key] = $value;
        }

        $product = Product::find(['id' => $id])->get();

        if ($product->isNew()){
            print_r("Product not found ".$product->id);
            return;
        }

        $props = json_decode($product->props);
        $props->Остатки = [ "type" => "json", "value" => $remains ];

        $product->props = json_encode($props);
        if (!$product->save()){
            print_r("error saving product ".$product->title);
        }
    }

    public function parse()
    {
        $productsArr = $this->getProductsArr();


        $progressBarTotal = $productsArr->count();
        $progressBar = new ProgressBar($progressBarTotal, 'Remains parser');

        foreach ($productsArr->Товар as $product) {
            $this->parseOne($product);
            $progressBar->makeStep();
        }
        $progressBar->close();
    }
}
