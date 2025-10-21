<?php

declare(strict_types=1);

use DragonCode\Telemetry\TelemetryHeader;
use DragonCode\Telemetry\TelemetryRequest;

it('sets and gets user id with string and int, falling back to existing header or 0', function () {
    $header = new TelemetryHeader;

    // 1) Explicit string
    $request   = makeRequest();
    $telemetry = new TelemetryRequest($request, $header);
    $telemetry->userId('42');
    expect($request->headers->get($header->userId))->toBe('42')
        ->and($telemetry->getUserId())->toBe('42');

    // 2) Explicit int should be cast to string
    $request   = makeRequest();
    $telemetry = new TelemetryRequest($request, $header);
    $telemetry->userId(7);
    expect($request->headers->get($header->userId))->toBe('7');

    // 3) Fallback to existing header when null
    $request   = makeRequest([$header->userId => '555']);
    $telemetry = new TelemetryRequest($request, $header);
    $telemetry->userId(null);
    expect($request->headers->get($header->userId))->toBe('555')
        ->and($telemetry->getUserId())->toBe('555');

    // 4) getUserId() returns '0' when nothing present
    $request   = makeRequest();
    $telemetry = new TelemetryRequest($request, $header);
    expect($telemetry->getUserId())->toBe('0');
});
