<?php

declare(strict_types = 1);

namespace Onyx\Traits;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

trait UrlGeneratorAware
{
    private
        $urlGenerator;

    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator): self
    {
        $this->urlGenerator = $urlGenerator;

        return $this;
    }

    private function redirect(string $route, array $parameters = array()): RedirectResponse
    {
        return new RedirectResponse(
            $this->path($route, $parameters)
        );
    }

    private function path(string $route, $parameters = array()): string
    {
        return $this->urlGenerator->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    private function url(string $route, $parameters = array()): string
    {
        return $this->urlGenerator->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
