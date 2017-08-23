<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandTrackers;

use Onyx\Services\CQS\CommandTracker;

class InMemory implements CommandTracker
{
    public
        $data;

    public function __construct(iterable $data = [])
    {
        $this->data = $data;
    }

    public function track(string $trackingId, $data): void
    {
        $this->data[$trackingId] = $data;
    }

    public function retrieveTrackedData(string $trackingId)
    {
        return $this->data[$trackingId];
    }
}
