<?php

namespace Onyx\Persistence\Fields;

require_once __DIR__ . '/AbstractFieldTestCase.php';

class ArrayFieldTest extends AbstractFieldTestCase
{
    public function providerTestConvert()
    {
        $simpleArrayField = new ArrayField();
        $integerArrayField = new ArrayField('test', new  IntegerField());

        return array(
            'Null' => array($simpleArrayField, null, null),
            'Null Integer Array' => array($integerArrayField, null, null),
            'Empty Array' => array($simpleArrayField, [], []),
            'Integer empty Array' => array($integerArrayField, [], []),
            'Mixed Array' => array($simpleArrayField, ["a", 1, 5.2, [1, 2]], ["a", 1, 5.2, [1, 2]]),
            'Mixed Array with keys' => array
            (
                $simpleArrayField, ["field1" => 1, "field2" => ["sfield1" => 8.3]],
                ["field1" => 1, "field2" => ["sfield1" => 8.3]]
            ),
            'Integer Array' => array($integerArrayField, [1, 2, 3, 10], [1, 2, 3, 10]),
            'Integer as String Array' => array($integerArrayField, ["1", "2", "3", "10"], [1, 2, 3, 10])
        );
    }

    public function providerTestConvertWithExceptions()
    {
        $simpleArrayField = new ArrayField();
        $integerArrayField = new ArrayField('test', new  IntegerField());

        return array(
            'Empty string' => array($simpleArrayField, ""),
            'String' => array($simpleArrayField, "test"),
            'Integer for Integer Array' => array($integerArrayField, 666),
            'Bad item type array' => array($integerArrayField, [1, 2, "toto"])
        );
    }
}
