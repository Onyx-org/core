<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\QueryHandlers;

use PHPUnit\Framework\TestCase;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\Queries\NullQuery;
use Onyx\Services\CQS\QueryResults\NullQueryResult;

class ClosureBasedTest extends TestCase
{
    /**
     * @dataProvider providerTestAccept
     */
    public function testAccept(Query $query)
    {
        $handler = new ClosureBased(function ($query) {});

        $this->assertTrue($handler->accept($query));
    }

    public function providerTestAccept()
    {
        return [
            [new NullQuery()],
            [new class implements Query {}],
        ];
    }

    public function testSend()
    {
        $result = new NullQueryResult();

        $handler = new ClosureBased(function ($query) use($result) {
            return $result;
        });

        $this->assertSame($result, $handler->handle(new NullQuery()));
    }
}
