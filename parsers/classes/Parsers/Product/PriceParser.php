<?php

namespace Parsers\Product;

use SimpleXMLElement;

class PriceParser
{
    private $xmlWithPrices;
    private $xmlWithPriceTypes;
    private $data = [];

    public function __construct(SimpleXMLElement $xmlWithPrices)
    {
        $this->xmlWithPrices = $xmlWithPrices;
        //$this->xmlWithPriceTypes = $xmlWithPriceTypes;
    }

    public function parse()
    {

        //$typesOfPrices = $this->getTypesOfPrices();

        //var_dump($this->xmlWithPrices);

        $products = $this->xmlWithPrices->ЦеныТоваровРозничные;

        $total = $products->count();

         foreach ($products->ЦенаТовара as $product) {
             //var_dump($product);
             $productId = $product->Ид->__toString();
             $priceForUnit = $product->ЦенаЗаЕдиницу->__toString();
             $this->data[$productId] = $priceForUnit;
        //     foreach ($product->Цены->Цена as $price) {
        //         $representation = $price->Представление->__toString();
        //         $typeOfPriceId = $price->ИдТипаЦены->__toString();
        //         // $typeOfPrice = $typesOfPrices[$typeOfPriceId];
        //         

        //         $valuta = $price->Валюта->__toString();
        //         // $unit = $price->Единица->__toString();
        //         $coefficient = $price->Коэффициента->__toString();

        //         $prices[] = [
        //             'Представление' => $representation,
        //             //'ТипЦены' => $typeOfPrice,
        //             'ЦенаЗаЕдиницу' => $priceForUnit,
        //             'Валюта' => $valuta,
        //             // 'Единица' => $unit,
        //             'Коэффициент' => $coefficient,
        //         ];
        //     }
        //     $this->data[$productId] = $prices;
        // }
        }
        //var_dump($this->data);
    }

    private function getTypesOfPrices()
    {
        $typesOfPrices = [];
        foreach ($this->xmlWithPriceTypes->Классификатор->ТипыЦен->ТипЦены as $typeOfPrice) {
            $productId = $typeOfPrice->Ид->__toString();
            $name = $typeOfPrice->Наименование->__toString();
            $valuta = $typeOfPrice->Валюта->__toString();
            //$tax = $this->getTax($typeOfPrice->Налог);

            $typesOfPrices[$productId] = [
                'Наименование' => $name,
                'Валюта' => $valuta,
                //'Налог' => $tax,
            ];
        }

        return $typesOfPrices;
    }

    private function getTax(SimpleXMLElement $xml)
    {
        $name = $xml->Наименование->__toString();
        $inSumm = $xml->УчтеноВСумме->__toString();
        return [
            'Наименование' => $name,
            'УчтеноВСумме' => $inSumm,
        ];
    }

    public function get(string $productId)
    {
        if (isset($this->data[$productId])) {
            return $this->data[$productId];
        }
        return false;
    }
}
