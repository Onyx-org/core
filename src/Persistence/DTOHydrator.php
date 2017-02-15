<?php

namespace Onyx\Persistence;

interface DTOHydrator
{
    public function enableExceptions(): DTOHydrator;

    public function disableExceptions(): DTOHydrator;

    public function hydrate(DataTransferObject $dto, array $dataset): ?DataTransferObject;
}
