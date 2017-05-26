<?php

declare(strict_types = 1);

namespace Onyx\Services;

use PHPUnit\Framework\TestCase;

class ServiceNameComputerTest extends TestCase
{
    /**
     * @dataProvider providerTestCompute
     */
    public function testCompute(string $expectedServiceName, string $classNamespace, string $namespaceSeparator)
    {
        $serviceNameComputer = new ServiceNameComputer($namespaceSeparator);

        $serviceName = $serviceNameComputer->compute($classNamespace);

        $this->assertSame($expectedServiceName, $serviceName);
    }

    public function providerTestCompute()
    {
        return [
            'simple separator with simple namespace' => [
                'expected service name' => 'unicorn',
                'class namespace' => 'Pony\Burger\Unicorn',
                'namespace separator' => 'Burger',
            ],
            'more complex separator with longer namespace' => [
                'expected service name' => 'page_pagequery',
                'class namespace' => 'Onyx\Domain\Queries\Page\PageQuery',
                'namespace separator' => 'Domain\Queries',
            ],
        ];
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testComputeException()
    {
        $classNamespace = 'Onyx\Domain\Queries\Page\PageQuery';

        $serviceNameComputer = new ServiceNameComputer('Pony\Burger');

        $serviceName = $serviceNameComputer->compute($classNamespace);
    }
}
