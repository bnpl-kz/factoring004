<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\GetStatus;

use PHPUnit\Framework\TestCase;

class GetStatusResponseTest extends TestCase
{
    public function testCreate()
    {
        $expected = new GetStatusResponse('received');
        $actual = GetStatusResponse::create(['status' => 'received']);
        $this->assertEquals($expected, $actual);
    }
}