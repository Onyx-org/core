<?php

declare(strict_types = 1);

namespace Onyx\Services;

interface TrackableObject
{
    public function getTrackingId(): string;
}