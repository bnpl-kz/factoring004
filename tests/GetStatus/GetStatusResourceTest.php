<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\GetStatus;

use BnplPartners\Factoring004\Transport\Response;
use BnplPartners\Factoring004\Transport\TransportInterface;
use PHPUnit\Framework\TestCase;

class GetStatusResourceTest extends TestCase
{

    public function testGetStatusByPreappID()
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('GET', '/bnpl/preapp/aaaaaaaa-0000-1111-2222-bbbbbbbbbbbb/status', [], [])
            ->willReturn(new Response(200, [], ['status' => 'received']));

        $resource = new GetStatusResource($transport, 'http://example.com');
        $response = $resource->getStatusByPreappID('aaaaaaaa-0000-1111-2222-bbbbbbbbbbbb');
        $expected = GetStatusResponse::create(['status' => 'received']);

        $this->assertEquals($expected, $response);
    }

    public function testGetStatusByOrderID()
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('GET', '/bnpl/bill/aaaaaaaa-0000-1111-2222-bbbbbbbbbbbb/status', [], [])
            ->willReturn(new Response(200, [], ['status' => 'received']));

        $resource = new GetStatusResource($transport, 'http://example.com');
        $response = $resource->getStatusByOrderID('aaaaaaaa-0000-1111-2222-bbbbbbbbbbbb');
        $expected = GetStatusResponse::create(['status' => 'received']);

        $this->assertEquals($expected, $response);
    }
}