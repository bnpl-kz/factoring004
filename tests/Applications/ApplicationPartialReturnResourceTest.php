<?php

namespace BnplPartners\Factoring004\Applications;

use BnplPartners\Factoring004\AbstractResourceTest;
use BnplPartners\Factoring004\Transport\Response;
use BnplPartners\Factoring004\Transport\TransportInterface;
use Psr\Http\Client\ClientInterface;

class ApplicationPartialReturnResourceTest extends AbstractResourceTest
{
    /**
     * @throws \BnplPartners\Factoring004\Exception\PackageException
     */
    public function testPartialReturn(): void
    {
        $orders = new ApplicationPartialReturn(6000,'1','1');

        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('PUT', '/accountingservice/1.0/applications/'.$orders->getMerchantId().'/'
                .$orders->getMerchantOrderId().'/part-return', $orders->toArray(), [])
            ->willReturn(new Response(200, [], 'OK'));

        $resource = new ApplicationResource($transport, static::BASE_URI);
        $response = $resource->partialReturn($orders);
        $expected = new ApplicationResponse('OK');

        $this->assertEquals($expected, $response);
    }


    protected function callResourceMethod(ClientInterface $client): void
    {
        $resource = new ApplicationResource($this->createTransport($client), static::BASE_URI);
        $resource->partialReturn(
            new ApplicationPartialReturn(6000,'1','1')
        );
    }
}
