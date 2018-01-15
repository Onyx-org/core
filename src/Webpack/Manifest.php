<?php

namespace Onyx\Webpack;

interface Manifest
{
    public function getFiles(): array;

    public function getFile(string $name): ?string;

    public function getChunkManifest(): ?string;
}
