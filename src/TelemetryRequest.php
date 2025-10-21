<?php

declare(strict_types=1);

namespace DragonCode\Telemetry;

use Ramsey\Uuid\UuidFactory;
use Symfony\Component\HttpFoundation\Request;

use function is_int;

class TelemetryRequest
{
    public function __construct(
        protected Request $request,
        protected TelemetryHeader $header,
    ) {}

    public function userId(int|string|null $id): static
    {
        $id ??= $this->getUserId();

        $this->set($this->header->userId, $id);

        return $this;
    }

    public function getUserId(): string
    {
        if ($id = $this->get($this->header->userId)) {
            return $id;
        }

        return '0';
    }

    public function ip(?string $ip = null): static
    {
        $ip ??= $this->getIp();

        $this->set($this->header->ip, $ip);

        return $this;
    }

    public function getIp(): string
    {
        if ($ip = $this->get($this->header->ip)) {
            return $ip;
        }

        return $this->get('HTTP_X_REAL_IP') ?: $this->request->getClientIp();
    }

    public function trackId(?string $id = null): static
    {
        $id ??= $this->getTrackId();

        $this->set($this->header->trackId, $id);

        return $this;
    }

    public function getTrackId(): string
    {
        if ($id = $this->get($this->header->trackId)) {
            return $id;
        }

        return (new UuidFactory)->uuid4()->toString();
    }

    public function getRequest(): Request
    {

        return $this->request;
    }

    protected function set(string $key, array|int|string|null $value): static
    {
        if (is_int($value)) {
            $value = (string) $value;
        }

        $this->request->headers->set($key, $value);

        return $this;
    }

    protected function get(string $key): array|string|null
    {
        return $this->request->headers->get($key);
    }
}
