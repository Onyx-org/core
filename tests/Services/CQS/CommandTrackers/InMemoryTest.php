<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandTrackers;

use PHPUnit\Framework\TestCase;

class InMemoryTest extends TestCase
{
    private
        $tracker,
        $data;

    protected function setUp()
    {
        $this->data = [
            'speed' => 'rene',
            'rene' => 'la taupe',
        ];
        $this->tracker = new InMemory($this->data);
    }

    public function testRetrieveData()
    {
        $retrievedData = $this->tracker->retrieveTrackedData('speed');
        $expectedData = $this->data['speed'];

        $this->assertEquals($expectedData, $retrievedData);
    }

    public function testTrackAlreadyTrackedData()
    {
        $this->assertEquals('la taupe', $this->tracker->retrieveTrackedData('rene'));

        $this->tracker->track('rene', 'taupe');

        $this->assertEquals('taupe', $this->tracker->retrieveTrackedData('rene'));
    }
}
