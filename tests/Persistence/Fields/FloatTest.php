<?php

namespace Onyx\Persistence\Fields;

require_once __DIR__ . '/AbstractFieldTestCase.php';

class FloatTest extends AbstractFieldTestCase
{
    public function providerTestConvert()
    {
        return array(
            'Float : null' => array(
                $this->prepareFloatField(),
                null,
                null
            ),
            'Float simple : -582.0' => array(
                $this->prepareFloatField(),
                -582.0,
                -582.0
            ),
            'Float unsigned : 0.0' => array(
                $this->prepareUnsignedField(),
                0.0,
                0.0
            ),
            'Float unsigned : 325.10' => array(
                $this->prepareUnsignedField(),
                325.10,
                325.10
            ),
            'Float [min : 10.05] : 10.05' => array(
                $this->prepareFloatField(10.05),
                10.05,
                10.05
            ),
            'Float [min : -1.10]: 1.10' => array(
                $this->prepareFloatField(-1.10),
                1.10,
                1.10
            ),
            'Float [max : 0.0]: 0.0' => array(
                $this->prepareFloatField(null, 0.0),
                0.0,
                0.0
            ),
            'Float [max : 12.50]: -515.05' => array(
                $this->prepareFloatField(null, 12.50),
                -515.05,
                -515.05
            ),
            'Float simple : "-582.12"' => array(
                $this->prepareFloatField(),
                '-582.12',
                -582.12
            ),
            'Float unsigned : "0.1"' => array(
                $this->prepareUnsignedField(),
                '0.1',
                0.1
            ),
            'Float unsigned : "325.5"' => array(
                $this->prepareUnsignedField(),
                '325.5',
                325.5
            ),
            'Float [min : 212.10] : "212.10"' => array(
                $this->prepareFloatField(212.10),
                '212.10',
                212.10
            ),
            'Float [min : -500.05]: "10.05"' => array(
                $this->prepareFloatField(-500.05),
                '10.05',
                10.05
            ),
            'Float [max : 0.1]: "0.1"' => array(
                $this->prepareFloatField(null, 0.1),
                '0.1',
                0.1
            ),
            'Float [max : 2388.55]: "-515.20"' => array(
                $this->prepareFloatField(null, 2388.55),
                '-515.20',
                -515.20
            ),
            'Float : 1' => array(
                $this->prepareFloatField(),
                1,
                1.0
            ),
            'Float : "1"' => array(
                $this->prepareFloatField(),
                "1",
                1.0
            ),
        );
    }

    public function providerTestConvertWithExceptions()
    {
        return array(
            'Float : "true"' => array(
                $this->prepareFloatField(),
                "true"
            ),
            'Float : true' => array(
                $this->prepareFloatField(),
                true
            ),
            'Float : Closure()' => array(
                $this->prepareFloatField(),
                function(){}
            ),
            'Float : array(1)' => array(
                $this->prepareFloatField(),
                [1]
            ),
            'Float unsigned : -1.1' => array(
                $this->prepareUnsignedField(),
                -1.1
            ),
            'Float unsigned : "-350.50"' => array(
                $this->prepareUnsignedField(),
                "-350.50"
            ),
            'Float [min 10.50]: 10.47' => array(
                $this->prepareFloatField(10.50),
                10.47
            ),
            'Float [min 186.12]: "25.02"' => array(
                $this->prepareFloatField(186.12),
                "25.02"
            ),
            'Float [max 50.1]: 51.2' => array(
                $this->prepareFloatField(null, 50.1),
                51.2
            )
        );
    }

    private function prepareFloatField($min = null, $max = null, $isUnsigned = false)
    {
        $field = new FloatField();

        if($isUnsigned)
        {
            $field = new UnsignedFloatField();
        }

        if(isset($min))
        {
            $field = $field->setMin($min);
        }

        if(isset($max))
        {
            $field = $field->setMax($max);
        }

        return $field;
    }

    private function prepareUnsignedField($min = null, $max = null)
    {
        return $this->prepareFloatField($min, $max, true);
    }
}
