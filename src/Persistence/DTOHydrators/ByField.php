<?php

declare(strict_types = 1);

namespace Onyx\Persistence\DTOHydrators;

use Onyx\Persistence\DTOHydrator;
use Onyx\Persistence\DataTransferObject;
use Onyx\Persistence\Field;
use Onyx\Persistence\Exceptions\InvalidDataException;
use Onyx\Persistence\ValueTransformer;

class ByField implements DTOHydrator
{
    private
        $fields,
        $enableExceptions,
        $valueTransformers;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
        $this->enableExceptions = true;
        $this->valueTransformers = array();
    }

    public function enableExceptions(): self
    {
        $this->enableExceptions = true;

        return $this;
    }

    public function disableExceptions(): self
    {
        $this->enableExceptions = false;

        return $this;
    }

    public function addValueTransformer(ValueTransformer $valueTransformer): self
    {
        $this->valueTransformers[] = $valueTransformer;

        return $this;
    }

    public function hydrate(DataTransferObject $dto, array $dataset): ?DataTransferObject
    {
        try
        {
            foreach ($this->fields as $fieldName => $field)
            {
                if($field instanceof Field)
                {
                    $this->hydrateField($dto, $dataset, $fieldName, $field);
                }
            }
        }
        catch (\Exception $e)
        {
            if($this->enableExceptions)
            {
                throw $e;
            }

            return null;
        }

        return $dto;
    }

    private function hydrateField(DataTransferObject $dto, array $dataset, string $fieldName, Field $field): void
    {
        if(! property_exists($dto, $fieldName))
        {
            throw new InvalidDataException(sprintf(
                'Field "%s" does not exist in DataTransferObject class "%s".',
                $fieldName,
                get_class($dto)
            ));
        }

        $value = $this->findValue($field->getNamePath(), $dataset);
        $value = $field->convert($value);

        $dto->$fieldName = $this->transformValue($field, $value);
    }

    private function transformValue(Field $field, $value)
    {
        foreach($this->valueTransformers as $valueTransformer)
        {
            $value = $valueTransformer->convert($field, $value);
        }

        return $value;
    }

    private function findValue(array $fieldPath, array $dataset)
    {
        $key = array_shift($fieldPath);
        if(! array_key_exists($key, $dataset))
        {
            throw new InvalidDataException(sprintf(
                'Key "%s" does not exists in dataset',
                $key
            ));
        }

        if(! empty($fieldPath))
        {
            return $this->findValue($fieldPath, $dataset[$key]);
        }

        return $dataset[$key];
    }
}
