<?php

namespace BnplPartners\Factoring004\Applications;

use PHPUnit\Framework\TestCase;

class ApplicationResponseTest extends TestCase
{
    public function testToString(): void
    {
        $response = new ApplicationResponse('OK');
        $this->assertEquals('OK', (string) $response);
    }

    public function testJsonSerialize(): void
    {
        $response = new ApplicationResponse('OK');
        $this->assertEquals('"OK"', json_encode($response));
    }
}
