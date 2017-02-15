<?php

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;

class RawTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider providerTestPrintableNamePath
     */
    public function testPrintableNamePath($fieldPath, $expected)
    {
        $field = new RawField($fieldPath);

        $value = $field->getPrintableNamePath();

        $this->assertSame($expected, $value);
    }

    public function providerTestPrintableNamePath()
    {
        return array(
            'SimplePath' => array('SimplePath', '[SimplePath]'),
            'Array size 1' => array(['OneField'], '[OneField]'),
            'Array size 2' => array(['field1', 'field2'], '[field1][field2]'),
            'Array size 3' => array(['field1', 'field2', 'field3'], '[field1][field2][field3]'),
            'Array size 4' => array(['field1', 'field2', 'field3', 'field4'], '[field1][field2][field3][field4]')
        );
    }

    /**
     * @dataProvider providerTestConvert
     */
    public function testConvert(Field $field, $value, $expected)
    {
        $convertedValue = $field->convert($value);
        $this->assertSame($expected, $convertedValue);
    }

    public function providerTestConvert()
    {
        $stringPathRawField = new RawField('SimplePath');
        $arrayPathRawField = new RawField(array('field1', 'field2', 'field3'));

        return array(
            'Null' => array($stringPathRawField, null, null),
            'Array' => array($stringPathRawField, [], []),
            'Float' => array($arrayPathRawField, 5.3, 5.3),
        );
    }
}
