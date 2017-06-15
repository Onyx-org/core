<?php

namespace Onyx\Console\Commands;

use Silex\Route;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteLister extends Command
{
    private
        $routeRetriever;

    public function __construct(Routes\Retriever $routeRetriever)
    {
        parent::__construct();

        $this->routeRetriever = $routeRetriever;
    }

    protected function configure()
    {
        $this->setName('routes:list')
            ->setDescription('List all HTTP routes.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $routes = $this->routeRetriever->retrieveRoutes();

        $output->writeln(sprintf('%d routes', count($routes)));

        $table = new Table($output);
        $table->setStyle('borderless');
        $table->setHeaders(array(
            'schemes',
            'methods',
            'path',
            'requirements',
        ));

        foreach($routes as $route)
        {
            $table->addRow([
                implode('|', $route->getMethods()),
                implode(', ', $route->getSchemes()),
                $route->getPath(),
                $this->renderRequirements($route),
            ]);
        }

        $table->render();
    }

    private function renderRequirements(Route $route): string
    {
        if(count($route->getRequirements()) === 0)
        {
            return 'None';
        }

        $requirements = [];

        foreach($route->getRequirements() as $variableName => $pattern)
        {
            $requirements[] = implode(' => ', [$variableName, $pattern]);
        }

        return implode(PHP_EOL, $requirements);
    }
}
