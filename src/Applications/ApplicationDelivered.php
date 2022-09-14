<?php

namespace BnplPartners\Factoring004\Applications;

use BnplPartners\Factoring004\ArrayInterface;

class ApplicationDelivered implements ArrayInterface
{
    private string $merchantId;
    private string $merchantOrderId;

    public function __construct(string $merchantId, string $merchantOrderId)
    {
        $this->merchantId = $merchantId;
        $this->merchantOrderId = $merchantOrderId;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getMerchantOrderId(): string
    {
        return $this->merchantOrderId;
    }

    public function toArray(): array
    {
        return [];
    }
}