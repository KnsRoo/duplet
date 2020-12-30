<?php

namespace Core\Misc;

class Cyr2Lat {

    private $str = ''; /**< Строка для конвертирования.*/

    private static $translit = [
        'а'=>'a', 'А'=>'A', 'б'=>'b', 'Б'=>'B', 'в'=>'v', 'В'=>'V', 'г'=>'g', 'Г'=>'G',
        'д'=>'d', 'Д'=>'D', 'е'=>'e', 'Е'=>'E', 'ё'=>'yo','Ё'=>'Yo','ж'=>'j', 'Ж'=>'J',
        'з'=>'z', 'З'=>'Z', 'и'=>'i', 'И'=>'I', 'й'=>'y', 'Й'=>'Y', 'к'=>'k', 'К'=>'K',
        'л'=>'l', 'Л'=>'L', 'м'=>'m', 'М'=>'M', 'н'=>'n', 'Н'=>'N', 'о'=>'o', 'О'=>'O',
        'п'=>'p', 'П'=>'P', 'р'=>'r', 'Р'=>'R', 'с'=>'s', 'С'=>'S', 'т'=>'t', 'Т'=>'T',
        'у'=>'u', 'У'=>'U', 'ф'=>'f', 'Ф'=>'F', 'х'=>'h', 'Х'=>'H', 'ц'=>'c', 'Ц'=>'C', 
        'ч'=>'ch','Ч'=>'Ch','ш'=>'sh','Ш'=>'Sh','щ'=>'sh','Щ'=>'Sh','ъ'=>'',  'ы'=>'i', 
        'ь'=>'', 'Ь'=>'', 'э'=>'e', 'Э'=>'E', 'ю'=>'yu','Ю'=>'Yu','я'=>'ya','Я'=>'Ya',
    ];

    /**
     * @brief Констркктор.
     * @param $str Строка для конвертирования.
     */

    public function __construct($str = '') { $this->str = $str; }

    /**
     * @brief Возвращает сконвертированную строку.
     * @return String
     * @see get;
     */

    public function __toString() {

        return $this->get();

    }

    /**
     * @brief Возвращает сконвертированную строку.
     * @return String
     *
     * Пример использования в виде объекта:
     * @code
     *  $cyr2lat = new Cyr2Lat('Привет мир!');
     *  $string = $cyr2lat->get();
     * @endcode
     */

    public function get() {

        return self::conv($this->str);

    }

    /**
     * @brief Конвертирует строку.
     * @param $str Строка для конвертирования.
     * @return String
     *
     * @code
     *  $string = Cyr2Lat::conv('Привет мир!');
     * @endcode
     */

    public static function conv($str = '') {

        return strtr($str, self::$translit);

    }

}
