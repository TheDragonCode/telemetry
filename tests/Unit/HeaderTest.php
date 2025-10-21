<?php

declare(strict_types=1);

use DragonCode\Telemetry\TelemetryHeader;

it('uses default header names', function () {
    $header = new TelemetryHeader;

    expect($header->userId)->toBe('X-Telemetry-User-Id')
        ->and($header->ip)->toBe('X-Telemetry-Ip')
        ->and($header->traceId)->toBe('X-Telemetry-Trace-Id');
});

it('accepts custom header names', function () {
    $header = new TelemetryHeader(
        userId : 'Some-User-Id',
        ip     : 'Some-IP',
        traceId: 'Some-Trace-Id',
    );

    expect($header->userId)->toBe('Some-User-Id')
        ->and($header->ip)->toBe('Some-IP')
        ->and($header->traceId)->toBe('Some-Trace-Id');
});
