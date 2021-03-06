<?php

declare(strict_types = 1);

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;
use Onyx\Persistence\Exceptions\InvalidDataException;
use Onyx\Persistence\FieldTypes;

class DateTime extends Raw implements Field
{
    const
        MYSQL_FORMAT = 'Y-m-d H:i:s';

    private
        $dateFormat;

    public function __construct($namePath, string $dateFormat)
    {
        parent::__construct($namePath);

        $this->dateFormat = $dateFormat;
    }

    public function convert($value)
    {
        if($value === null)
        {
            return null;
        }

        if($value instanceof \DateTime)
        {
            return $value;
        }

        $date = $this->tryConvert($value);

        return $date;
    }

    private function tryConvert($value): ?\DateTime
    {
        $date = null;
        $exception = null;

        try
        {
            if(is_string($value))
            {
                $date = \DateTime::createFromFormat($this->dateFormat, $value);
                $date = $this->checkStrictDateValidity($value, $date);
            }
        }
        catch(\Throwable $e)
        {
            $exception = $e;
        }

        if(! $date instanceof \DateTime)
        {
            $this->throwException($value, $exception);
        }

        return $date;
    }

    private function checkStrictDateValidity($value, $date): ?\DateTime
    {
        if(! $date instanceof \DateTime || $date->format($this->dateFormat) !== $value)
        {
            $date = null;
        }

        return $date;
    }

    private function throwException($value, ?\Throwable $exception = null): void
    {
        $printValue = "";
        if(is_string($value) || is_numeric($value))
        {
            $printValue = ' = ' . (string)$value;
        }

        $message = sprintf(
                'Value %s %s is not a valid date for format : "%s".',
                $this->getPrintableNamePath(),
                $printValue,
                $this->dateFormat
        );

        $code = 13;
        if($exception !== null)
        {
            $code = $exception->getCode();
        }

        throw new InvalidDataException($message, $code, $exception);
    }

    public function getType(): int
    {
        return FieldTypes::DATETIME;
    }
}
