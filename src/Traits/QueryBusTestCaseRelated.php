<?php

declare(strict_types = 1);

namespace Onyx\Traits;

use Onyx\Services\CQS\QueryBuses\InMemory;

trait QueryBusTestCaseRelated
{
    private
        $queryBus;

    private function initializeQueryBusForTest(): void
    {
        $this->queryBus = new InMemory();
    }

    private function assertQueriesHaveBeenSent(array $expectedQueryTypes): void
    {
        foreach($expectedQueryTypes as $queryType)
        {
            $this->assertQueryHasBeenSent($queryType);
        }
    }

    private function assertQueryHasBeenSent(string $queryType): void
    {
        foreach($this->queryBus->getSentQueries() as $query)
        {
            if($query instanceof $queryType)
            {
                $this->addToAssertionCount(1);

                return;
            }
        }

        $this->fail(sprintf(
            "Failed to assert that query of type %s has been sent",
            $queryType
        ));
    }
}
