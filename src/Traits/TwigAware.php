<?php

declare(strict_types = 1);

namespace Onyx\Traits;

use Symfony\Component\HttpFoundation\Response;
use Onyx\Services\CQS\QueryResult;
use Puzzle\Pieces\PathManipulation;

trait TwigAware
{
    use PathManipulation;

    private
        $twig;

    public function setTwig(\Twig_Environment $twig): self
    {
        $this->twig = $twig;

        return $this;
    }

    private function computeTemplatePath(string $template): string
    {
        if(defined('self::TPL_DIR'))
        {
            $template = $this->enforceEndingSlash(self::TPL_DIR) . $this->removeLeadingSlash($template);
        }

        return $template;
    }


    private function render(string $template, array $context = array()): Response
    {
        $template = $this->computeTemplatePath($template);

        return new Response(
            $this->twig->render($template, $context)
        );
    }

    private function renderResult(string $template, QueryResult $result): Response
    {
        $template = $this->computeTemplatePath($template);

        return new Response(
            $this->twig->render($template, [
                'result' => $result,
            ])
        );
    }
}
