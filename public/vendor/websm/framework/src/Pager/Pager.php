<?php

namespace Websm\Framework\Pager;

use Websm\Framework\Pager\Exceptions\InvalidArgumentException;

/**
 * Pager
 *
 * @uses PagerInterface
 * @author Igor Bykov <con29rus@live>
 */
class Pager implements PagerInterface {

    private $countItems = 1;
    private $currentPage = 1;
    private $countOnPage = 10;

    /**
     * Constructor
     *
     * @param mixed $countItems Количество элементов
     * @param int $currentPage Номер текущей страницы.
     * @param int $countOnPage Количество элементов на странице.
     * @access public
     *
     * @code
     *
     * $pager = new Pager(50, 3, 5);
     *
     * $pager->genPages(); // [2, 3, 4]
     * $pager->genPages(5); // [1, 2, 3, 4, 5]
     * $pager->genPages(7); // [1, 2, 3, 4, 5, 6, 7]
     *
     * // chunk example
     *
     * $items = range(1, 50); // [1, 2, 3, ... 50]
     * $pager = new Pager(50, 3, 5);
     * $pager->chunkItems($items); // [11, 12, 13, 14, 15]
     *
     * @endcode
     */
    public function __construct(
        $countItems,
        $currentPage = 1,
        $countOnPage = 10
    ) {

        $this->countItems = $countItems;
        $this->countOnPage = $countOnPage;

        $pages = $this->getCountPages();

        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $pages) $currentPage = $pages;

        $this->currentPage = $currentPage;

    }

    public function getCountPages() {

        $count = ceil($this->countItems / $this->countOnPage);
        return $count > 0 ? $count : 1;

    }

    public function getCurrentPage() {

        return $this->currentPage;

    }

    public function chunkItems(Array $items) {

        $chunked = array_chunk($items, $this->countOnPage);
        $index = $this->currentPage - 1;
        return isset($chunked[$index]) ? $chunked[$index] : [];

    }

    public function getNextPage() {

        $page = $this->currentPage + 1;
        $pages = $this->getCountPages();

        return $page > $pages ? $pages : $page;

    }

    public function getPervPage() {

        $page = $this->currentPage - 1;
        return $page <= 1 ? 1 : $page;

    }

    public function genPages($width = 3) {

        if (!($width % 2)) 
            throw new InvalidArgumentException('Width is even');

        $currentPage = $this->currentPage;
        $pages = $this->getCountPages();
        $tail = (int)floor($width / 2);

        $start = $currentPage - $tail;
        $end = $currentPage + $tail;

        if ($width >= $pages) return range(1, $pages);

        if ($start < 1) {

            $end = $end + (1 - $start);
            $start = 1;

        } elseif ($end > $pages) {

            $start = $start - ($end - $pages);
            $end = $pages;

        }

        return range($start, $end);

    }

}
