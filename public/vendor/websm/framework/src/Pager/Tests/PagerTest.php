<?php

include_once __DIR__.'/../../init.php';

use PHPUnit\Framework\TestCase;

use Websm\Framework\Pager\Pager;

class PagerTest extends TestCase {

    public function testCreatePager() {

        try {

            new Pager(50, 3, 10);
            $this->assertTrue(true);

        } catch (Exception $e) {

            $this->assertTrue(false);

        }


    }

    public function testChunkItems() {

        $items = range(1, 50);
        $pager = new Pager(count($items), 3, 5);
        $chunked = $pager->chunkItems($items);

        $this->assertEquals($chunked, [11, 12, 13, 14, 15]);

        $pager = new Pager(count($items), 5, 5);
        $chunked = $pager->chunkItems($items);

        $this->assertEquals($chunked, [21, 22, 23, 24, 25]);

    }

    public function testGenPages() {

        $pager = new Pager(50, 3, 5);

        $this->assertEquals($pager->genPages(), [2, 3, 4]);
        $this->assertEquals($pager->genPages(5), [1, 2, 3, 4, 5]);
        $this->assertEquals($pager->genPages(7), [1, 2, 3, 4, 5, 6, 7]);

        $pager = new Pager(50, 3, 10);

        $this->assertEquals($pager->genPages(7), [1, 2, 3, 4, 5]);

        try {

            $pager->genPages(4);
            $this->assertTrue(false);

        } catch (Exception $e) {

            $this->assertTrue(true);

        }

    }

    public function testGetCountPages() {

        $pager = new Pager(50, 3, 10);

        $this->assertEquals($pager->getCountPages(), 5);

        $pager = new Pager(100, 3, 10);

        $this->assertEquals($pager->getCountPages(), 10);

    }

    public function testGetCurrentPage() {

        $pager = new Pager(50, 3);

        $this->assertEquals($pager->getCurentPage(), 3);

    }

    public function testGetNextAndPervPages() {

        $pager = new Pager(50, 3);

        $this->assertEquals($pager->getPervPage(), 2);
        $this->assertEquals($pager->getNextPage(), 4);

    }

}
