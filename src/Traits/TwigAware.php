<?php

namespace Onyx\Traits;

use Symfony\Component\HttpFoundation\Response;

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
}
