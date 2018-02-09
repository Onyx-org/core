<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandTrackers;

use Onyx\Services\CQS\CommandTracker;

/**
 * @deprecated Use Synchronous implementation instead
 */
class InMemory implements CommandTracker
{
    private
        $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function track(string $trackingId, $data): void
    {
        $this->data[$trackingId] = $data;
    }

    public function retrieveTrackedData(string $trackingId)
    {
        if (! array_key_exists($trackingId, $this->data))
        {
            throw new \RuntimeException(sprintf('Tracking id "%s" not found in command tracker', $trackingId));
        }

        return $this->data[$trackingId];
    }
}
