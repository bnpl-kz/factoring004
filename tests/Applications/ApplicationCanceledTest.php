<?php

namespace BnplPartners\Factoring004\Applications;

use PHPUnit\Framework\TestCase;

class ApplicationCanceledTest extends TestCase
{

    public function testGetMerchantId(): void
    {
        $merchantId = new ApplicationCanceled('1', '1');
        $this->assertEquals('1', $merchantId->getMerchantId());
    }

    public function testGetMerchantOrderId(): void
    {
        $merchantId = new ApplicationCanceled('1', '1');
        $this->assertEquals('1', $merchantId->getMerchantOrderId());
    }

    public function testToArray(): void
    {
        $order = new ApplicationCanceled('1', '1');
        $expected = [];

        $this->assertEquals($expected, $order->toArray());
    }
}
