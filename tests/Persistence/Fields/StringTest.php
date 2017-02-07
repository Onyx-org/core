<?php

namespace Onyx\Persistence\Fields;

require_once __DIR__ . '/AbstractFieldTestCase.php';

class StringTest extends AbstractFieldTestCase
{
    public function providerTestConvert()
    {
        $simpleString = new StringField();
        $minString = (new StringField())->minSize(5);
        $maxString = (new StringField())->maxSize(10);
        $sameMinMaxString = (new StringField())->minSize(5)->maxSize(5);
        $minMaxString = (new StringField())->minSize(5)->maxSize(10);

        return array(
            'Null' => array($simpleString, null, null),
            'String' => array($simpleString, 'testString', 'testString'),
            'Min size exact' => array($minString, '12345', '12345'),
            'Min size more' => array($minString, '123456', '123456'),
            'Max size exact' => array($maxString, '1234567890', '1234567890'),
            'Min size less' => array($maxString, 'test', 'test'),
            'Same min and max' => array($sameMinMaxString, 'test5', 'test5'),
            'Min and max - equal to min' => array($minMaxString, 'test5', 'test5'),
            'Min and max - equal to max' => array($minMaxString, 'test567890', 'test567890'),
            'Min and max - between' => array($minMaxString, 'test567', 'test567')
        );
    }

    public function providerTestConvertWithExceptions()
    {
        $simpleString = new StringField();
        $minString = (new StringField())->minSize(5);
        $maxString = (new StringField())->maxSize(10);
        $sameMinMaxString = (new StringField())->minSize(5)->maxSize(5);
        $minMaxString = (new StringField())->minSize(5)->maxSize(10);

        return array(
            'Invalid type - array' => array($simpleString, ['test']),
            'Invalid type - int' => array($simpleString, 7337),
            'Invalid type - bool' => array($simpleString, true),
            'Min size KO' => array($minString, '1234'),
            'Min size empty' => array($minString, ''),
            'Max size KO' => array($maxString, '12345678901'),
            'Same min and max - lower' => array($sameMinMaxString, 'test'),
            'Same min and max - greater' => array($sameMinMaxString, 'test56'),
            'Min and max - lower' => array($minMaxString, 'test'),
            'Min and max - greater' => array($minMaxString, 'test5678901')
        );
    }
}
