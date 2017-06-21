<?php

declare(strict_types = 1);

namespace Onyx\Services;

interface Trackable
{
    public function getTrackingId(): string;
}
