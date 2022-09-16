<?php

namespace BnplPartners\Factoring004\Applications;

use PHPUnit\Framework\TestCase;

class ApplicationPartialReturnTest extends TestCase
{

    public function testGetAmountAr(): void
    {
        $merchantId = new ApplicationPartialReturn(6000,'1', '1');
        $this->assertEquals(6000, $merchantId->getAmountAr());
    }

    public function testGetMerchantId(): void
    {
        $merchantId = new ApplicationPartialReturn(6000,'1', '1');
        $this->assertEquals('1', $merchantId->getMerchantId());
    }

    public function testGetMerchantOrderId(): void
    {
        $merchantId = new ApplicationPartialReturn(6000,'1', '1');
        $this->assertEquals('1', $merchantId->getMerchantOrderId());
    }

    public function testToArray(): void
    {
        $order = new ApplicationPartialReturn(6000,'1', '1');
        $expected = [
            'amountAR' => 6000,
        ];

        $this->assertEquals($expected, $order->toArray());
    }
}
