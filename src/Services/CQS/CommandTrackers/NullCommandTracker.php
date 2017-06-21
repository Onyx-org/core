<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandTrackers;

use Onyx\Services\CQS\CommandTracker;

class NullCommandTracker implements CommandTracker
{
    public function track(string $trackingId, $data): void
    {
    }

    public function retrieveTrackedData(string $trackingId)
    {
    }
}
