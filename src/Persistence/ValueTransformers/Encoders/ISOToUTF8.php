<?php

namespace Onyx\Persistence\ValueTransformers\Encoders;

use Onyx\Persistence\ValueTransformers\ValueTransformer;
use Onyx\Persistence\Field;

class ISOToUTF8 implements ValueTransformer
{
    private
        $acceptTypes;

    public function __construct()
    {
        $this->acceptTypes = array(
            FieldTypes::STRING,
            FieldTypes::RAW,
        );
    }

    public function convert(Field $field, $value)
    {
        if(in_array($field->getType(), $this->acceptTypes))
        {
            return utf8_encode($value);
        }

        return $value;
    }
}