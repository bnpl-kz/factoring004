<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\Otp;

use BnplPartners\Factoring004\AbstractResourceTest;
use BnplPartners\Factoring004\Transport\Response;
use BnplPartners\Factoring004\Transport\TransportInterface;
use GuzzleHttp\ClientInterface;

class SendOtpResourceTest extends AbstractResourceTest
{
    /**
     * @throws \BnplPartners\Factoring004\Exception\PackageException
     */
    public function testSendOtp(): void
    {
        $otp = new SendOtp('1', '100', 6000);

        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('POST', '/accounting/v1/sendOtp', $otp->toArray(), [])
            ->willReturn(new Response(200, [], ['msg' => 'OK']));

        $resource = new OtpResource($transport, static::BASE_URI);
        $response = $resource->sendOtp($otp);

        $this->assertEquals(new DtoOtp('OK'), $response);
    }

    protected function callResourceMethod(ClientInterface $client): void
    {
        $resource = new OtpResource($this->createTransport($client), static::BASE_URI);
        $resource->sendOtp(new SendOtp('1', '100', 6000));
    }
}
