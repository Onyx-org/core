<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandTrackers;

use Onyx\Services\CQS\CommandTracker;

class Synchronous implements CommandTracker
{
    private
        $trackings;

    public function __construct()
    {
        $this->trackings = [];
    }

    public function track(string $trackingId, $data): void
    {
        if(array_key_exists($trackingId, $this->trackings))
        {
            throw new \LogicException(sprintf('Tracking id "%s" already exists in command tracker', $trackingId));
        }

        $this->trackings[$trackingId] = $data;
    }

    public function retrieveTrackedData(string $trackingId)
    {
        if(! array_key_exists($trackingId, $this->trackings))
        {
            throw new \RuntimeException(sprintf('Tracking id "%s" not found in command tracker', $trackingId));
        }

        return $this->trackings[$trackingId];
    }
}
