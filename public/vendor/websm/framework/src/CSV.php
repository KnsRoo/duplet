<?php

namespace Websm\Framework;

class CSV implements \Iterator {

    private $rows = [];
    private $delimiter = ';';
    private $position = 0;

    public function __construct() { $this->position = 0; }

    public static function parse($file, $delimiter = ';') {

        ini_set('auto_detect_line_endings', true);

        $csv = new self;
        $csv->delimiter = $delimiter;
        $handle = fopen($file, 'r');

        while ($row = fgetcsv($handle, 0, $delimiter))
            $csv->rows[] = $row;

        fclose($handle);
        return $csv;

    }

    public function __toString() { return $this->toString(); }

    public function setDelimiter($delimiter) {

        $this->delimiter = $delimiter;
        return $this;

    }

    public function getRow($numRow = 0) { return $this->rows[$numRow]; }

    public function addRow() { $this->rows[] = func_get_args(); }

    public function save($file) {

        $handle = fopen($file, 'w');

        if (!$handle) throw new \Exception('Opening $file error.');

        foreach($this->rows as $row)
            fputcsv($handle, $row, $this->delimiter);

        fclose($handle);

    }

    public function toString() {

        ob_start();
        $this->out();
        return ob_get_clean();

    }

    public function out() { $this->save('php://output'); }

    public function rewind() { $this->position = 0; }

    public function current() { return $this->rows[$this->position]; }

    public function key() { return $this->position; }

    public function next() { ++$this->position; }

    public function valid() { return isset($this->rows[$this->position]); }

}
