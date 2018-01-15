<?php

declare(strict_types = 1);

namespace Onyx\Webpack\Manifest;

use PHPUnit\Framework\TestCase;

class MemoryTest extends TestCase
{
    private
        $files,
        $chunk;

    protected function setUp()
    {
        $this->files = array(
            'image.jpg' => 'assets/image.jpg',
            'audio.mp3' => 'assets/audio.mp3',
            'video.mp4' => 'assets/audio.mp4',
        );

        $this->chunk = "Chunk content";
    }

    public function testGetFiles()
    {
        $manifest = new Memory($this->files, $this->chunk);

        $this->assertSame($this->files, $manifest->getFiles());
    }

    public function testGetFile()
    {
        $manifest = new Memory($this->files, $this->chunk);

        $this->assertSame('assets/image.jpg', $manifest->getFile('image.jpg'));
        $this->assertNull($manifest->getFile('unknownFile.pdf'));
    }

    public function testGetChunkManifest()
    {
        $manifest = new Memory($this->files, $this->chunk);

        $this->assertSame($this->chunk, $manifest->getChunkManifest());
    }
}
