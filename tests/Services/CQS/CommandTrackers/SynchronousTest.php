<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandTrackers;

use PHPUnit\Framework\TestCase;

class SynchronousTest extends TestCase
{
    public function testRetrieveTrackedData()
    {
        $tracker = new Synchronous();

        $trackingId = 'pony-burger';

        $data = new class {};

        $tracker->track($trackingId, $data);

        $this->assertSame($data, $tracker->retrieveTrackedData($trackingId));
    }

    /**
     * @expectedException \LogicException
     */
    public function testTrackSameIdMoreThanOnce()
    {
        $tracker = new Synchronous();

        $tracker->track('pony-burger', []);
        $tracker->track('pony-burger', []);
    }

    /**
     * @expectedException \Exception
     */
    public function testRetrieveUnknownTrackedData()
    {
        $tracker = new Synchronous();

        $tracker->retrieveTrackedData('pony-burger');
    }
}
