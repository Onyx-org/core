<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\HandlerProviders;

use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Onyx\Services\CQS\Queries\NullQuery;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryResult;
use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\Command;
use Onyx\Services\CQS\Commands\NullCommand;

class MultipleHandlerProviderTest extends TestCase
{
    private
        $emptyProvider,
        $provider;

    protected function setUp()
    {
        $containerQ1 = new Container();
        $containerQ2 = new Container([
            NullQuery::class => $this->nullQueryHandler(),
        ]);

        $containerC1 = new Container();
        $containerC2 = new Container([
            NullCommand::class => $this->nullCommandHandler(),
        ]);

        $this->provider = new MultipleHandlerProvider([
            new PimpleClassBased($containerQ1),
            new PimpleClassBased($containerC1),
            new PimpleClassBased($containerC2),
            new PimpleClassBased($containerQ2),
        ]);

        $this->emptyProvider = new MultipleHandlerProvider([
            new PimpleClassBased($containerQ1),
            new PimpleClassBased($containerC1),
        ]);
    }

    private function nullQueryHandler(): QueryHandler
    {
        return new class implements QueryHandler {
            public function accept(Query $query): bool {}
            public function handle(Query $query): QueryResult {}
        };
    }

    private function nullCommandHandler(): CommandHandler
    {
        return new class implements CommandHandler {
            public function accept(Command $command): bool {}
            public function handle(Command $command): void {}
        };
    }

    public function testFindQueryHandlerFor()
    {
        $handler = $this->provider->findQueryHandlerFor(new NullQuery());

        $this->assertEquals($this->nullQueryHandler(), $handler);
    }

    public function testFindCommandHandlerFor()
    {
        $handler = $this->provider->findCommandHandlerFor(new NullCommand());

        $this->assertEquals($this->nullCommandHandler(), $handler);
    }

    /**
     * @expectedException \LogicException
     */
    public function testQueryHandlerNotFound()
    {
        $this->emptyProvider->findQueryHandlerFor(new NullQuery());
    }

    /**
     * @expectedException \LogicException
     */
    public function testCommandHandlerNotFound()
    {
        $this->emptyProvider->findCommandHandlerFor(new NullCommand());
    }
}
