<?php

declare(strict_types=1);

use DragonCode\Telemetry\TelemetryHeader;
use DragonCode\Telemetry\TelemetryRequest;
use Ramsey\Uuid\Uuid;

it('gets or sets track id, generating a UUID v4 when absent', function () {
    $header = new TelemetryHeader;

    // 1) If header exists, return it
    $request   = makeRequest([$header->trackId => 'track-123']);
    $telemetry = new TelemetryRequest($request, $header);
    expect($telemetry->getTrackId())->toBe('track-123');

    // 2) When absent, generate UUID v4
    $request   = makeRequest();
    $telemetry = new TelemetryRequest($request, $header);
    $generated = $telemetry->getTrackId();
    expect(Uuid::isValid($generated))->toBeTrue()
        ->and(Uuid::fromString($generated)->getVersion())->toBe(4);

    // 3) trackId() without param sets header using getTrackId()
    $telemetry->trackId();
    expect($request->headers->has($header->trackId))->toBeTrue()
        ->and(Uuid::isValid($request->headers->get($header->trackId)))->toBeTrue();

    // 4) trackId() with explicit value sets header
    $telemetry->trackId('manual-id');
    expect($request->headers->get($header->trackId))->toBe('manual-id');
});
