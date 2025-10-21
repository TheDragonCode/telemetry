<?php

declare(strict_types=1);

use DragonCode\Telemetry\TelemetryHeader;
use DragonCode\Telemetry\TelemetryRequest;
use Symfony\Component\HttpFoundation\Request;

it('sets header from callback when header is absent and casts ints to strings', function () {
    $headerName = 'Some-Header';

    $request   = makeRequest();
    $header    = new TelemetryHeader;
    $telemetry = new TelemetryRequest($request, $header);

    $telemetry->custom($headerName, function (Request $req) {
        expect($req)->toBeInstanceOf(Request::class);

        return 1234; // will be cast to string by TelemetryRequest::set
    });

    expect($request->headers->get($headerName))->toBe('1234');
});

it('preserves existing header and does not call the callback when header is present', function () {
    $headerName = 'Some-Header';

    $request = makeRequest([$headerName => 'qwerty']);

    $called    = false;
    $header    = new TelemetryHeader;
    $telemetry = new TelemetryRequest($request, $header);

    $telemetry->custom($headerName, function () use (&$called) {
        $called = true; // must remain false if existing header is used

        return 'should-not-be-used';
    });

    expect($called)->toBeFalse()
        ->and($request->headers->get($headerName))->toBe('qwerty');
});
