<?php

namespace Websm\Framework\Validation\Rules;

class Phone extends AbstractRule {

    const PATTERN = '/^(8|\+?7)?\s?\(?(\d{3})\)?\s?(\d{3})\-?(\d{2})\-?(\d{2})$/';

    public function check($key, &$data = []) { return true; }

    public static function inlineCheck($data) {

        if (preg_match(self::PATTERN, $data, $out)) {
            return implode(array_slice($out, 2), '');
        }
        else return false;

    }

}
