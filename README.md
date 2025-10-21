# 🪢 Telemetry

![the dragon code telemetry](https://preview.dragon-code.pro/the%20dragon%20code/telemetry.svg?brand=php&mode=auto)

[![Stable Version][badge_stable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![License][badge_license]][link_license]

End-to-end telemetry of inter-service communication.

## Installation

You can install the **Telemetry** package via [Composer](https://getcomposer.org):

```Bash
composer require dragon-code/telemetry
```

## Basic Usage

### Using Default Header Names

```php
use DragonCode\Telemetry\TelemetryHeader;
use DragonCode\Telemetry\TelemetryRequest;
use Symfony\Component\HttpFoundation\Request;

/** @var Request $request */
$request = /* ... */;

$telemetry = new TelemetryRequest($request, new TelemetryHeader);

function telemetry(Request $request, ?int $userId = null): Request
{
    return (new TelemetryRequest($request, new TelemetryHeader))
        ->userId($userId)
        ->ip()
        ->trackId()
        ->getRequest();
}

// Для первого вызова
telemetry($request, $user->id);

// Для последующих сервисов
telemetry($request);
```

### Custom Header Names

```php
use DragonCode\Telemetry\TelemetryHeader;

return new TelemetryHeader(
    userId: 'Some-User-Id',
    ip: 'Some-IP',
    trackId: 'Some-Track-Id',
);
```

## License

This package is licensed under the [MIT License](LICENSE).


[badge_downloads]:      https://img.shields.io/packagist/dt/dragon-code/telemetry.svg?style=flat-square

[badge_license]:        https://img.shields.io/packagist/l/dragon-code/telemetry.svg?style=flat-square

[badge_stable]:         https://img.shields.io/github/v/release/TheDragonCode/telemetry?label=packagist&style=flat-square

[link_license]:         LICENSE

[link_packagist]:       https://packagist.org/packages/dragon-code/telemetry
