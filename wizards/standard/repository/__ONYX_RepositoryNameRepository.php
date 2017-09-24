<?php

declare(strict_types = 1);

namespace __ONYX_Namespace\Persistence;

use __ONYX_Namespace\Domain;
use __ONYX_Namespace\Persistence\DataTransferObjects as DTO;

interface __ONYX_RepositoryNameRepository
{
    public function find(string $uuid): ?Domain\__ONYX_RepositoryName;

    public function save(DTO\__ONYX_RepositoryName $dto): void;
}
