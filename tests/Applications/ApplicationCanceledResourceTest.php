<?php

namespace BnplPartners\Factoring004\Applications;

use BnplPartners\Factoring004\AbstractResourceTest;
use BnplPartners\Factoring004\Transport\Response;
use BnplPartners\Factoring004\Transport\TransportInterface;
use Psr\Http\Client\ClientInterface;

class ApplicationCanceledResourceTest extends AbstractResourceTest
{
    /**
     * @throws \BnplPartners\Factoring004\Exception\PackageException
     */
    public function testFullReturn(): void
    {
        $orders = new ApplicationCanceled('1','1');

        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('PUT', '/accountingservice/1.0/applications/'.$orders->getMerchantId().'/'
                .$orders->getMerchantOrderId().'/canceled', [], [])
            ->willReturn(new Response(200, [], 'OK'));

        $resource = new ApplicationResource($transport, static::BASE_URI);
        $response = $resource->canceled($orders);
        $expected = new ApplicationResponse('OK');

        $this->assertEquals($expected, $response);
    }


    protected function callResourceMethod(ClientInterface $client): void
    {
        $resource = new ApplicationResource($this->createTransport($client), static::BASE_URI);
        $resource->canceled(
            new ApplicationCanceled('1','1')
        );
    }
}
