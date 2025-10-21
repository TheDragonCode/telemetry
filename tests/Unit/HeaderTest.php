<?php

declare(strict_types=1);

use DragonCode\Telemetry\TelemetryHeader;

it('uses default header names', function () {
    $header = new TelemetryHeader;

    expect($header->userId)->toBe('X-Telemetry-User-Id')
        ->and($header->ip)->toBe('X-Telemetry-Ip')
        ->and($header->trackId)->toBe('X-Telemetry-Track-Id');
});

it('accepts custom header names', function () {
    $header = new TelemetryHeader(
        userId : 'Some-User-Id',
        ip     : 'Some-IP',
        trackId: 'Some-Track-Id',
    );

    expect($header->userId)->toBe('Some-User-Id')
        ->and($header->ip)->toBe('Some-IP')
        ->and($header->trackId)->toBe('Some-Track-Id');
});
