<?php

declare(strict_types = 1);

namespace Onyx\Webpack\Manifest;

use Onyx\Webpack\Manifest;

class Memory implements Manifest
{
    private
        $files,
        $chunk;

    public function __construct(array $files, ?string $chunk)
    {
        $this->files = $files;
        $this->chunk = $chunk;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getFile(string $name): ?string
    {
        if(! isset($this->files[$name]))
        {
            return null;
        }

        return $this->files[$name];
    }

    public function getChunkManifest(): ?string
    {
        return $this->chunk;
    }
}
