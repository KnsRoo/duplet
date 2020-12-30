<?php

include __DIR__.'/../EnumAbstract.php';
include __DIR__.'/../../Exceptions/BaseException.php';
include __DIR__.'/../../Exceptions/NotFoundException.php';
include __DIR__.'/../../Exceptions/InvalidArgumentException.php';

use PHPUnit\Framework\TestCase;

use Websm\Framework\Types\EnumAbstract;
use Websm\Framework\Exceptions\NotFoundException;
use Websm\Framework\Exceptions\InvalidArgumentException;

class NumbersEnum extends EnumAbstract {

    const ONE = 1;
    const TWO = 2;
    const THREE = 3;

}

final class EnumAbstractTest extends TestCase {

    public function testCreateEnum() {

        try {

            $enum = new NumbersEnum('TWO');
            $this->assertEquals(2, $enum->get());

        } catch (Exception $e) {

            $this->assertTrue(false);

        }

    }

    public function testFailCreateEnumValueOf() {

        $this->expectException(NotFoundException::class);
        $enum = NumbersEnum::valueOf(6);

    }

    public function testCreateEnumValueOf() {

        try {

            $enum = NumbersEnum::valueOf(2);
            $this->assertEquals(2, $enum->get());

        } catch (Exception $e) {

            $this->assertTrue(false);

        }

    }

    public function testFailCreateEnum() {

        $this->expectException(NotFoundException::class);
        new NumbersEnum('SIX');

    }

    public function testGetValue() {

        $enum = new NumbersEnum('TWO');
        $this->assertEquals(2, $enum->get());

    }

    public function testToString() {

        $enum = new NumbersEnum('TWO');
        $this->assertTrue('2' === $enum->__toString());

    }

    public function testFailToString() {

        $enum = new NumbersEnum('TWO');
        $this->assertFalse(2 === $enum->__toString());

    }

}

