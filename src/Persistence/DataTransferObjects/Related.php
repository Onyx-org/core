<?php

declare(strict_types = 1);

namespace Onyx\Persistence\DataTransferObjects;

use Onyx\Persistence\DataTransferObject;

abstract class Related implements DataTransferObject
{
    private
        $lazyLoadedAttributes;

    public function __construct(array $attributeNames)
    {
        $this->lazyLoadedAttributes = array_flip($attributeNames);
    }

    public function load(string $attributeName)
    {
        $this->checkAttributeExists($attributeName);

        $attribute = $this->lazyLoadedAttributes[$attributeName];

        if($attribute instanceof \Closure)
        {
            $attribute = $attribute();
            $this->lazyLoadedAttributes[$attributeName] = $attribute;
        }

        return $attribute;
    }

    public function set(string $attributeName, $data): void
    {
        $this->checkAttributeExists($attributeName);

        $this->lazyLoadedAttributes[$attributeName] = $data;
    }

    private function exists(string $attributeName): bool
    {
        return in_array($attributeName, array_keys($this->lazyLoadedAttributes));
    }

    private function checkAttributeExists(string $attributeName): void
    {
        if($this->exists($attributeName) === false)
        {
            throw new \InvalidArgumentException("$attributeName does not exist");
        }
    }
}
