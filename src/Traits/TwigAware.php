<?php

declare(strict_types = 1);

namespace Onyx\Traits;

use Symfony\Component\HttpFoundation\Response;
use Onyx\Services\CQS\QueryResult;

trait TwigAware
{
    private
        $twig;

    public function setTwig(\Twig_Environment $twig): self
    {
        $this->twig = $twig;

        return $this;
    }

    private function render($template, array $context = array()): Response
    {
        return new Response(
            $this->twig->render($template, $context)
        );
    }

    private function renderResult($template, QueryResult $result): Response
    {
        return new Response(
            $this->twig->render($template, [
                'result' => $result,
            ])
        );
    }
}
