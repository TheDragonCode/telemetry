<?php

declare(strict_types=1);

use DragonCode\Telemetry\TelemetryHeader;
use DragonCode\Telemetry\TelemetryRequest;

it('returns the same Request instance via getRequest()', function () {
    $header    = new TelemetryHeader;
    $request   = makeRequest();
    $telemetry = new TelemetryRequest($request, $header);

    expect($telemetry->getRequest())->toBe($request);
});
