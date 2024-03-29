<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\Otp;

use BnplPartners\Factoring004\AbstractResourceTest;
use BnplPartners\Factoring004\Transport\Response;
use BnplPartners\Factoring004\Transport\TransportInterface;
use GuzzleHttp\ClientInterface;

class CheckOtpResourceTest extends AbstractResourceTest
{
    /**
     * @throws \BnplPartners\Factoring004\Exception\PackageException
     */
    public function testCheckOtp(): void
    {
        $otp = new CheckOtp('1', '100', 'test', 6000);

        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('POST', '/accounting/v1/checkOtp', $otp->toArray(), [])
            ->willReturn(new Response(200, [], ['msg' => 'OK']));

        $resource = new OtpResource($transport, static::BASE_URI);
        $response = $resource->checkOtp($otp);

        $this->assertEquals(new DtoOtp('OK'), $response);
    }

    protected function callResourceMethod(ClientInterface $client): void
    {
        $resource = new OtpResource($this->createTransport($client), static::BASE_URI);
        $resource->checkOtp(new CheckOtp('1', '100', 'test', 6000));
    }
}
