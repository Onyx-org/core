<?php

namespace Onyx\Persistence;

use Onyx\Persistence\Field;

interface ValueTransformer
{
    public function convert(Field $field, $value);
}