<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\Applications;

use JsonSerializable;

class ApplicationResponse implements JsonSerializable
{
    private string $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function __toString(): string
    {
        return $this->data;
    }

    public function jsonSerialize(): string
    {
        return $this->data;
    }
}