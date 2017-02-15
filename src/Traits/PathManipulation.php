<?php

declare(strict_types = 1);

namespace Onyx\Traits;

trait PathManipulation
{
    private function enforceLeadingSlash(string $path): string
    {
        return DIRECTORY_SEPARATOR . $this->removeLeadingSlash($path);
    }

    private function removeLeadingSlash(string $path): string
    {
        return ltrim($path, DIRECTORY_SEPARATOR);
    }

    private function enforceEndingSlash(string $path): string
    {
        return $this->removeEndingSlash($path) . DIRECTORY_SEPARATOR;
    }

    private function removeEndingSlash(string $path): string
    {
        return rtrim($path, DIRECTORY_SEPARATOR);
    }

    private function removeWrappingSlashes(string $path): string
    {
        return trim($path, DIRECTORY_SEPARATOR);
    }
}
