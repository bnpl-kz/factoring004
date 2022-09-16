<?php

namespace BnplPartners\Factoring004\Applications;

use BnplPartners\Factoring004\AbstractResourceTest;
use BnplPartners\Factoring004\Transport\Response;
use BnplPartners\Factoring004\Transport\TransportInterface;
use Psr\Http\Client\ClientInterface;

class ApplicationDeliveredResourceTest extends AbstractResourceTest
{
    /**
     * @throws \BnplPartners\Factoring004\Exception\PackageException
     */
    public function testDelivered(): void
    {
        $orders = new ApplicationDelivered('1','1');

        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('PUT', '/accountingservice/1.0/applications/'.$orders->getMerchantId().'/'
                .$orders->getMerchantOrderId().'/delivered', [], [])
            ->willReturn(new Response(200, [], 'OK'));

        $resource = new ApplicationResource($transport, static::BASE_URI);
        $response = $resource->delivered($orders);
        $expected = new ApplicationResponse('OK');

        $this->assertEquals($expected, $response);
    }


    protected function callResourceMethod(ClientInterface $client): void
    {
        $resource = new ApplicationResource($this->createTransport($client), static::BASE_URI);
        $resource->delivered(
            new ApplicationDelivered('1','1')
        );
    }
}
