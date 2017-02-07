<?php

namespace Onyx\Persistence\ValueTransformers;

use Onyx\Persistence\Field;

interface ValueTransformer
{
    public function convert(Field $field, $value);
}