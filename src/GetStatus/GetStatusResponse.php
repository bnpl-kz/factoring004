<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\GetStatus;

class GetStatusResponse
{
    private string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function create(array $response): GetStatusResponse
    {
        return new self($response['status'] ?? "");
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}