<?php

declare(strict_types=1);

namespace ItalyStrap\StorageTests;

trait CommonStoreMultipleTestsTrait
{
    /**
     * @test
     */
    public function getDefaultValueIfKeyDoesNotExists(): void
    {
        $this->assertSame(
            'default',
            $this->makeInstance()->get('key', 'default'),
            'Should return default value if key does not exists'
        );
    }

    /**
     * @test
     */
    public function getZeroWhenValueIsZero(): void
    {
        $sut = $this->makeInstance();
        $sut->set('key', 0);
        $this->assertSame(0, $sut->get('key'), 'Should return zero when value is zero');
    }

    /**
     * @test
     * @dataProvider iterableValueForSetMultipleProvider
     */
    public function setMultiple(iterable $values)
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setMultiple($values), '');
        $this->assertSame('value1', $sut->get('key1'), 'Should return value1');
        $this->assertSame('value2', $sut->get('key2'), 'Should return value2');
    }

    abstract protected function prepareSetMultipleReturnFalse(): void;

    /**
     * @test
     */
    public function setMultipleReturnFalse(): void
    {
        $this->prepareSetMultipleReturnFalse();
        $sut = $this->makeInstance();
        $this->assertFalse($sut->set('', 'value1'), 'Should return false if key is empty');
        $this->assertFalse($sut->setMultiple([
            '' => 'value1',
        ]), 'Should return false if key is empty');
    }

    /**
     * @test
     * @dataProvider iterableValuesForGetMultipleAndUpdateMultipleProvider
     */
    public function getMultiple(iterable $keys, iterable $expected = [])
    {
        $sut = $this->makeInstance();
        $sut->set('key1', 'value1');
        $sut->set('key2', 'value2');
        $actual =  $sut->getMultiple($keys);

        $count = 0;
        foreach ($actual as $key => $value) {
            $count++;
            $this->assertSame($expected[$key], $value, 'Should return value1 and value2');
        }
        $this->assertSame(2, $count, 'Should return 2');
    }

    /**
     * @test
     */
    public function getMultipleReturnDefaultValue(): void
    {
        $sut = $this->makeInstance();
        $sut->set('key2', 'value2');
        $actual =  $sut->getMultiple(['key1', 'key3'], 'default');

        $count = 0;
        foreach ($actual as $value) {
            $count++;
            $this->assertSame('default', $value, 'Should return default value');
        }
        $this->assertSame(2, $count, 'Should return 2');
    }

    /**
     * @test
     * @dataProvider iterableValuesForGetMultipleAndUpdateMultipleProvider
     */
    public function deleteMultiple(iterable $keys, iterable $expected = [])
    {
        $sut = $this->makeInstance();
        $sut->set('key1', 'value1');
        $sut->set('key2', 'value2');
        $this->assertSame('value1', $sut->get('key1'), 'Should return value1');
        $this->assertSame('value2', $sut->get('key2'), 'Should return value2');
        $this->assertTrue($sut->deleteMultiple($keys), 'Should return true');
        $this->assertNull($sut->get('key1'), 'Should return null');
        $this->assertNull($sut->get('key2'), 'Should return null');
    }

    /**
     * @test
     */
    public function deleteMultipleReturnTrueWithNotExistentValue()
    {
        $this->assertNull($this->makeInstance()->get('key1'));
        $this->assertNull($this->makeInstance()->get('key3'));
        $this->assertTrue($this->makeInstance()->deleteMultiple(['key1', 'key3']), 'Should return true');
    }

    /**
     * @test
     */
    public function deleteNotExistingValue()
    {
        $this->assertTrue($this->makeInstance()->delete('key1'), 'Should return true');
    }

    /**
     * @test
     */
    public function deleteFromEmptyKeyShouldReturnFalse()
    {
        $this->assertFalse($this->makeInstance()->delete(''), 'Should return false');
        $this->assertFalse($this->makeInstance()->deleteMultiple(['']), 'Should return false');
    }

    public static function iterableValueForSetMultipleProvider(): iterable
    {
        yield 'Array' => [
            ['key1' => 'value1', 'key2' => 'value2'],
        ];

        yield 'Traversable' => [
            new \ArrayIterator(['key1' => 'value1', 'key2' => 'value2']),
        ];

        yield 'Generator' => [
            (function () {
                yield 'key1' => 'value1';
                yield 'key2' => 'value2';
            })(),
        ];

        yield 'ArrayObject' => [
            new \ArrayObject(['key1' => 'value1', 'key2' => 'value2']),
        ];
    }

    public static function iterableValuesForGetMultipleAndUpdateMultipleProvider(): iterable
    {
        yield 'Array' => [
            ['key1', 'key2'],
            ['key1' => 'value1', 'key2' => 'value2'],
        ];

        yield 'Traversable' => [
            new \ArrayIterator(['key1', 'key2']),
            ['key1' => 'value1', 'key2' => 'value2'],
        ];

        yield 'Generator' => [
            (function () {
                yield 'key1';
                yield 'key2';
            })(),
            ['key1' => 'value1', 'key2' => 'value2'],
        ];

        yield 'ArrayObject' => [
            new \ArrayObject(['key1', 'key2']),
            ['key1' => 'value1', 'key2' => 'value2'],
        ];
    }
}
