<?php

include __DIR__ . '/../ArrayHelper.php';

use PHPUnit\Framework\TestCase;

use Websm\Framework\Types\ArrayHelper;

final class ArrayHelperTest extends TestCase {

    public function testCreateArray() {

        try {

            $array = new ArrayHelper(['one', 'two', 'three']);
            $array = new ArrayHelper();

        } catch (Exception $e) {

            $this->assertTrue(false);

        }

        try {

            $array = new ArrayHelper('123');

        } catch (Exception $e) {

            $this->assertTrue(true);

        }

    }

    public function testCount() {

        $array = new ArrayHelper;

        $array->push('one');
        $array->push('two');
        $array->push('three');

        $this->assertEquals(count($array), 3);
        $this->assertEquals($array->count(), 3);

    }

    public function testPushAndPopAndShift() {

        $array = new ArrayHelper;

        $this->assertEquals(count($array), 0);

        $array->push('val1');

        $array[] = 'val2';
        $array[] = 'val3';

        $this->assertEquals(count($array), 3);

        $val = $array->pop();

        $this->assertEquals(count($array), 2);
        $this->assertEquals($val, 'val3');

        $val = $array->shift();

        $this->assertEquals(count($array), 1);
        $this->assertEquals($val, 'val1');

    }

    public function testIsEmpty() {

        $array = new ArrayHelper;

        $this->assertTrue($array->isEmpty());
        $this->assertFalse(empty($array));

    }

    public function testIssetAndOffsetExists() {

        $array = new ArrayHelper(['one', 'two' => 2]);

        $this->assertTrue(isset($array[0]));
        $this->assertTrue(isset($array['two']));
        $this->assertFalse(isset($array['three']));

        $this->assertTrue($array->offsetExists(0));
        $this->assertTrue($array->offsetExists('two'));
        $this->assertFalse($array->offsetExists('three'));

    }

    public function testUnsetAndOffsetUnset() {

        $array = new ArrayHelper(['one', 'two' => 2]);

        unset($array[0]);

        $this->assertEquals($array->toArray(), ['two' => 2]);

        $array->offsetUnset('two');

        $this->assertEquals($array->toArray(), []);

    }

    public function testGetValueByKey() {

        $array = new ArrayHelper(['one', 'two' => 2]);

        $this->assertEquals($array['two'], 2);

        $this->assertEquals($array->two, 2);

        $this->assertEquals($array->offsetGet('two'), 2);

    }

    public function testGetNotEmpty() {

        $array = new ArrayHelper([null, 0, '', false, 'test', null, 1]);

        $this->assertEquals($array->getNotEmpty(), 'test');

    }

    public function testArrayIterate() {

        $original = [1, 2, 'three', 'four'];
        $helper = new ArrayHelper($original);

        foreach ($helper as $key => $value) {

            $this->assertEquals($original[$key], $value);

        }

    }

    public function testCollectValByKey() {

        $array = new ArrayHelper([
            ['test-key' => 1, 2, 3],
            ['test-key' => 2, 4, 5],
            ['test-key' => 3],
            ['test-key' => 4, 'any-key' => 6],
        ]);

        $collected = $array->collectValByKey('test-key');

        $this->assertEquals($collected->toArray(), [1 ,2 ,3, 4]);

    }

    public function testChunkTest() {

        $array = new ArrayHelper([1, 2, 3]);

        $chunked = $array->chunk(2);

        $equals = new ArrayHelper([
            new ArrayHelper([1, 2]),
            new ArrayHelper([3])
        ]);

        $this->assertEquals($chunked, $equals);

        $chunked = $array->chunk(3);

        $this->assertNotEquals($chunked, $equals);

    }

}


