<?php

declare(strict_types=1);

use DragonCode\Telemetry\TelemetryHeader;
use DragonCode\Telemetry\TelemetryRequest;
use Ramsey\Uuid\Uuid;

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

it('gets ip with correct precedence and sets header via ip()', function () {
    $header = new TelemetryHeader;

    // 1) If telemetry header exists, it wins
    $request   = makeRequest([$header->ip => '203.0.113.10']);
    $telemetry = new TelemetryRequest($request, $header);
    expect($telemetry->getIp())->toBe('203.0.113.10');

    // 2) Else HTTP_X_REAL_IP (non-standard header name checked by the class)
    $request = makeRequest();
    $request->headers->set('HTTP_X_REAL_IP', '198.51.100.20');
    $telemetry = new TelemetryRequest($request, $header);
    expect($telemetry->getIp())->toBe('198.51.100.20');

    // 3) Else client ip (REMOTE_ADDR)
    $request   = makeRequest([], ['REMOTE_ADDR' => '192.0.2.30']);
    $telemetry = new TelemetryRequest($request, $header);
    expect($telemetry->getIp())->toBe('192.0.2.30');

    // 4) ip() without argument sets header from getIp()
    $telemetry->ip();
    expect($request->headers->get($header->ip))->toBe('192.0.2.30');

    // 5) ip() with value overrides and sets header
    $telemetry->ip('10.0.0.1');
    expect($request->headers->get($header->ip))->toBe('10.0.0.1');
});

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

it('returns the same Request instance via getRequest()', function () {
    $header    = new TelemetryHeader;
    $request   = makeRequest();
    $telemetry = new TelemetryRequest($request, $header);

    expect($telemetry->getRequest())->toBe($request);
});
