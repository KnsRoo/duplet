<?php
// this class is my modification of pager range supplier
namespace Core\Pager;

/**
 * MyPager
 *
 * @uses PagerInterface
 * @author Dmitry Marov <d.marov94@gmail.com>
 */
class MyPager implements PagerInterface {

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
    public function __construct($countItems, $currentPage = 1, $countOnPage = 10) {

        $this->countItems = max($countItems,1);
        $this->countOnPage = $countOnPage;

        $pages = $this->getCountPages();

        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $pages) $currentPage = $pages;

        $this->currentPage = $currentPage;

    }

    public function getCountPages() {

        return ceil($this->countItems / $this->countOnPage);

    }

    public function getCurrentPage() {

        return $this->currentPage;

    }

    public function chunkItems(Array $items) {

        $index = $this->currentPage - 1;
        return array_slice($items, $index * $this->countOnPage, $this->countOnPage);

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

    public function genPages($gap = 1) {

        $currentPage = $this->currentPage;
        $pages = $this->getCountPages();

        $start = $this->currentPage - $gap;
        $end = $this->currentPage + $gap;

        if ($start < 1) {

            $end += (1 - $start);
            $start = 1;
            $end = min($end,$pages);

        } else if ($end > $pages) {

            $start -= ($end - $pages);
            $end = $pages;
            $start = max($start,1);

        }

        return range($start, $end);

    }

    function getFirstPage() {

        return 1;

    }

    function getlastPage() {
    
        return $this->getCountPages();

    }
    
}
