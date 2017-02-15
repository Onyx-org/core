<?php

namespace Onyx\Persistence\Fields;

require_once __DIR__ . '/AbstractFieldTestCase.php';

class NotNullableTest extends AbstractFieldTestCase
{
    public function providerTestConvert()
    {
        $notNullableStringField = new NotNullable(new StringField());
        $notNullableIntegerField = new NotNullable(new Integer());
        $notNullableRawField = new NotNullable(new Raw());

        $emptyAsNullBooleanField = new NotNullable(new Boolean());
        $emptyAsNullBooleanField->emptyAsNull();

        return array(
            'Empty OK' => array($notNullableStringField, "", ""),
            'String OK' => array($notNullableStringField, "testString", "testString"),
            'Integer OK' => array($notNullableIntegerField, 1, 1),
            'Raw OK' => array($notNullableRawField, [], []),
            'Boolean empty as null OK' => array($emptyAsNullBooleanField, true, true)
        );
    }

    public function providerTestConvertWithExceptions()
    {
        $notNullableRawField = new NotNullable(new Raw());

        $emptyAsNullBooleanField = new NotNullable(new Boolean());
        $emptyAsNullBooleanField->emptyAsNull();

        return array(
            'Null' => array($notNullableRawField, null),
            'Empty KO' => array($emptyAsNullBooleanField, "")
        );
    }
}
