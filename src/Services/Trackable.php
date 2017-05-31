<?php

declare(strict_types = 1);

namespace Onyx\Services;

use Ramsey\Uuid\Uuid;

abstract class Trackable implements TrackableObject
{
    private
        $trackingId;

    public function __construct()
    {
        $this->trackingId = (Uuid::uuid4())->toString();
    }

    public function getTrackingId()
    {
        return $this->trackingId;
    }
}