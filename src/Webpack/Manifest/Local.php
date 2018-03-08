<?php

declare(strict_types = 1);

namespace Onyx\Webpack\Manifest;

use Psr\Log\LoggerInterface;
use Onyx\Webpack\Manifest;
use Puzzle\Pieces\Json;

class Local implements Manifest
{
    private
        $files,
        $chunkManifest;

    public function __construct(string $manifestPath, string $chunkManifestPath, LoggerInterface $logger)
    {
        // Must be set first
        $this->logger = $logger;

        $this->files = $this->setFiles($manifestPath);
        $this->chunkManifest = $this->loadFileContent($chunkManifestPath);
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
        return $this->chunkManifest;
    }

    private function setFiles(string $path): array
    {
        $json = $this->loadFileContent($path);

        if($json === '' || $json === null)
        {
            $this->logger->error('Empty or no Webpack manifest.');

            return [];
        }

        return $this->decode($json);
    }

    private function decode(string $json): array
    {
        $data = Json::decode($json, true);

        if(!is_array($data))
        {
            $this->logger->error('Expected an array from Webpack manifest.');

            return [];
        }

        return $data;
    }

    private function loadFileContent(string $path): ?string
    {
        if(!is_file($path))
        {
            return null;
        }

        $content = file_get_contents($path);
        if($content === false)
        {
            $this->logger->error(sprintf('Something went wrong while trying to read the file %s', $path));

            return null;
        }

        return $content;
    }
}
