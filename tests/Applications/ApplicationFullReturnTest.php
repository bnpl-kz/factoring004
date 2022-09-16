<?php

namespace BnplPartners\Factoring004\Applications;

use PHPUnit\Framework\TestCase;

class ApplicationFullReturnTest extends TestCase
{
    public function testGetMerchantId(): void
    {
        $merchantId = new ApplicationFullReturn('1', '1');
        $this->assertEquals('1', $merchantId->getMerchantId());
    }

    public function testGetMerchantOrderId(): void
    {
        $merchantId = new ApplicationFullReturn('1', '1');
        $this->assertEquals('1', $merchantId->getMerchantOrderId());
    }

    public function testToArray(): void
    {
        $order = new ApplicationFullReturn('1', '1');
        $expected = [];

        $this->assertEquals($expected, $order->toArray());
    }
}
