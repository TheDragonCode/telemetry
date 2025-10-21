<?php

declare(strict_types=1);

use DragonCode\Telemetry\TelemetryHeader;
use DragonCode\Telemetry\TelemetryRequest;

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
