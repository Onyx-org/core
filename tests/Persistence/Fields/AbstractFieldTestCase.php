<?php

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;

abstract class AbstractFieldTestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider providerTestConvert
     */
    public function testConvert(Field $field, $value, $expected)
    {
        $convertedValue = $field->convert($value);
        $this->assertSame($expected, $convertedValue);
    }

    /**
     * @dataProvider providerTestConvertWithExceptions
     * @expectedException \Onyx\Persistence\Exceptions\InvalidDataException
     */
    public function testConvertWithExceptions(Field $field, $value)
    {
        $field->convert($value);
    }

    abstract public function providerTestConvert();

    abstract public function providerTestConvertWithExceptions();
}
