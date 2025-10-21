<?php

declare(strict_types=1);

namespace DragonCode\Telemetry;

readonly class TelemetryHeader
{
    public function __construct(
        public string $userId = 'X-Telemetry-User-Id',
        public string $ip = 'X-Telemetry-Ip',
        public string $trackId = 'X-Telemetry-Track-Id',
    ) {}
}
