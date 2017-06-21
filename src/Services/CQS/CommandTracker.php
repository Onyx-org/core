<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS;

interface CommandTracker
{
    public function track(string $trackingId, $data): void;

    public function retrieveTrackedData(string $trackingId);
}
