<?php

namespace Onyx\Persistence\Fields;

require_once __DIR__ . '/AbstractFieldTestCase.php';

class IntegerTest extends AbstractFieldTestCase
{
    public function providerTestConvert()
    {
        return array(
            'Integer : null' => array(
                $this->prepareIntegerField(),
                null,
                null
            ),
            'Integer simple : -582' => array(
                $this->prepareIntegerField(),
                -582,
                -582
            ),
            'Integer unsigned : 0' => array(
                $this->prepareUnsignedField(),
                0,
                0
            ),
            'Integer unsigned : 325' => array(
                $this->prepareUnsignedField(),
                325,
                325
            ),
            'Integer [min : 212] : 212' => array(
                $this->prepareIntegerField(212),
                212,
                212
            ),
            'Integer [min : -500]: 10' => array(
                $this->prepareIntegerField(-500),
                10,
                10
            ),
            'Integer [max : 0]: 0' => array(
                $this->prepareIntegerField(null, 0),
                0,
                0
            ),
            'Integer [max : 2388]: -515' => array(
                $this->prepareIntegerField(null, 2388),
                -515,
                -515
            ),
            'Integer simple : "-582"' => array(
                $this->prepareIntegerField(),
                '-582',
                -582
            ),
            'Integer unsigned : "0"' => array(
                $this->prepareUnsignedField(),
                '0',
                0
            ),
            'Integer unsigned : "325"' => array(
                $this->prepareUnsignedField(),
                '325',
                325
            ),
            'Integer [min : 212] : "212"' => array(
                $this->prepareIntegerField(212),
                '212',
                212
            ),
            'Integer [min : -500]: "10"' => array(
                $this->prepareIntegerField(-500),
                '10',
                10
            ),
            'Integer [max : 0]: "0"' => array(
                $this->prepareIntegerField(null, 0),
                '0',
                0
            ),
            'Integer [max : 2388]: "-515"' => array(
                $this->prepareIntegerField(null, 2388),
                '-515',
                -515
            ),
        );
    }

    public function providerTestConvertWithExceptions()
    {
        return array(
            'Integer : "true"' => array(
                $this->prepareIntegerField(),
                "true"
            ),
            'Integer : array(1)' => array(
                $this->prepareIntegerField(),
                [1]
            ),
            'Integer unsigned : -1' => array(
                $this->prepareUnsignedField(),
                -1
            ),
            'Integer unsigned : "-350"' => array(
                $this->prepareUnsignedField(),
                "-350"
            ),
            'Integer [min 10]: 9' => array(
                $this->prepareIntegerField(10),
                9
            ),
            'Integer [min 186]: "25"' => array(
                $this->prepareIntegerField(186),
                "25"
            ),
            'Integer [max 50]: 51' => array(
                $this->prepareIntegerField(null, 50),
                51
            ),
            'Integer [max 98174]: 99146' => array(
                $this->prepareIntegerField(null, 98174),
                99146
            )
        );
    }

    private function prepareIntegerField($min = null, $max = null, $isUnsigned = false)
    {
        $field = new  Integer();

        if($isUnsigned)
        {
            $field = new UnsignedInteger();
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
        return $this->prepareIntegerField($min, $max, true);
    }
}
