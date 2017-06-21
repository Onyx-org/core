<?php

declare(strict_types = 1);

namespace __ONYX_Namespace\Persistence\Repositories;

use __ONYX_Namespace\Persistence\__ONYX_RepositoryNameRepository;
use __ONYX_Namespace\Domain as Domain;
use __ONYX_Namespace\Persistence\DataTransferObjects as DTO;
use Doctrine\DBAL\Connection;
use Onyx\Persistence\DTOHydrators\ByField;
use Onyx\Persistence\Fields;
// use __ONYX_Namespace\Peristence\PonyRepository;

class __ONYX_RepositoryName implements __ONYX_RepositoryNameRepository
{
    private const TABLE_NAME = '__ONYX_RepositoryName';

    private
        // $ponyRepository,
        $db;

    public function __construct(Connection $db/*, PonyRepository $ponyRepository */)
    {
        $this->db = $db;
        // $this->ponyRepository = $ponyRepository;
    }

    public function find(string $uuid): ?Domain\__ONYX_RepositoryName
    {
        $statement = $this->db->executeQuery(
            sprintf("SELECT * FROM %s WHERE uuid = ?", self::TABLE_NAME),
            [$uuid]
        );

        $row = $statement->fetch();

        if($row === false)
        {
            return null;
        }

        return $this->buildDomainObject($row);
    }

    public function save(DTO\__ONYX_RepositoryName $dto): void
    {
        // FIXME TODO handle unique violation errors

        $data = [
            'uuid' => $dto->uuid,
        ];

        $types = [
            \PDO::PARAM_STR,
        ];

        /*
          TODO handle "upsert" on your own

        $this->db->insert(self::TABLE_NAME, $data, $types);

        $this->db->update(self::TABLE_NAME, $data, ['uuid' => $dto->uuid], $types);

        //*/
    }

    private function buildDomainObject(array $row): Domain\__ONYX_RepositoryName
    {
        $dto = $this->buildDTOObject($row);

        /*
         Insert lazy loading here

         Example :

        $dto->set('pony', function() use($dto) {
            return $this->ponyRepository->find($dto->ponyUuid);
        });

        //*/

        return new Domain\__ONYX_RepositoryName($dto);
    }

    private function buildDTOObject(array $row): DTO\__ONYX_RepositoryName
    {
        $fields = [
            'uuid' => new Fields\NotNullable(new Fields\StringField('uuid')),
        ];

        $hydrator = new ByField($fields);
        $dto = $hydrator->hydrate(new DTO\__ONYX_RepositoryName(), $row);

        return $dto;
    }
}
