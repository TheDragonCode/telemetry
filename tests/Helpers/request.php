<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;

function makeRequest(array $headers = [], array $server = []): Request
{
    $serverFromHeaders = [];

    foreach ($headers as $key => $value) {
        $serverKey = 'HTTP_' . str_replace('-', '_', strtoupper($key));

        $serverFromHeaders[$serverKey] = $value;
    }

    $server = array_merge($serverFromHeaders, $server);

    return Request::create('/', 'GET', [], [], [], $server);
}
