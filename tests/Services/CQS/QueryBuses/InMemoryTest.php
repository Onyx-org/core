<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\QueryBuses;

use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryHandlers\ClosureBased;
use PHPUnit\Framework\TestCase;
use Onyx\Services\CQS\QueryResults\NullQueryResult;

class InMemoryTest extends TestCase
{
    private
        $query1,
        $query2;

    protected function setUp()
    {
        $this->query1 = new class implements Query{};
        $this->query2 = new class implements Query{};
    }

    public function testSend()
    {
        $bus = new InMemory();
        $this->assertCount(0, $bus->getSentQueries());
        $this->assertSame(null, $bus->getLastSentQuery());

        $bus->send($this->query1);
        $this->assertCount(1, $bus->getSentQueries());
        $this->assertSame($this->query1, $bus->getLastSentQuery());

        $bus->send($this->query1);
        $this->assertCount(2, $bus->getSentQueries());
        $this->assertSame($this->query1, $bus->getLastSentQuery());

        $bus->send($this->query2);
        $this->assertCount(3, $bus->getSentQueries());
        $this->assertSame($this->query2, $bus->getLastSentQuery());
    }

    public function testUniqueHandler()
    {
        $query = new class implements Query{ public $theQueryHasBeenHandled; };

        $bus = new InMemory(new ClosureBased(function(Query $query) {
            $query->theQueryHasBeenHandled = true;

            return new NullQueryResult();
        }));
        $bus->send($query);

        $this->assertTrue($query->theQueryHasBeenHandled);
    }
}
