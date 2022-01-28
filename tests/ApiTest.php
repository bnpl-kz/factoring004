<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004;

use BnplPartners\Factoring004\PreApp\PreAppResource;
use BnplPartners\Factoring004\Transport\TransportInterface;
use InvalidArgumentException;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    private const BASE_URI = 'http://example.com';

    private TransportInterface $transport;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transport = $this->createStub(TransportInterface::class);
    }

    public function testCreate(): void
    {
        $expected = new Api($this->transport, static::BASE_URI);
        $actual = Api::create($this->transport, static::BASE_URI);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @testWith [""]
     *           ["http"]
     *           ["https"]
     *           ["http:"]
     *           ["https:"]
     *           ["http://"]
     *           ["https://"]
     *           ["example"]
     *           ["/path"]
     */
    public function testCreateWithEmptyBaseUri(string $baseUri): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Api($this->transport, $baseUri);
    }

    public function testPreApps(): void
    {
        $api = new Api($this->transport, static::BASE_URI);

        $this->assertInstanceOf(PreAppResource::class, $api->preApps);
        $this->assertSame($api->preApps, $api->preApps);
    }

    public function testGetUnexpectedProperty(): void
    {
        $api = new Api($this->transport, static::BASE_URI);

        $this->expectException(OutOfBoundsException::class);

        $this->assertNull($api->test);
    }
}
