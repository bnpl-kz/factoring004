<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\Applications;

use BnplPartners\Factoring004\ArrayInterface;

class ApplicationPartialReturn implements ArrayInterface
{

    private int $amountAr;
    private string $merchantId;
    private string $merchantOrderId;

    public function __construct(int $amountAr, string $merchantId, string $merchantOrderId)
    {
        $this->amountAr = $amountAr;
        $this->merchantId = $merchantId;
        $this->merchantOrderId = $merchantOrderId;
    }

    public function getAmountAr(): int
    {
        return $this->amountAr;
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
        return [
            'amountAR' => $this->getAmountAr(),
        ];
    }
}