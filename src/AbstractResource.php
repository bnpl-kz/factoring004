<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004;

use BnplPartners\Factoring004\Auth\AuthenticationInterface;
use BnplPartners\Factoring004\Auth\NoAuth;
use BnplPartners\Factoring004\Transport\ResponseInterface;
use BnplPartners\Factoring004\Transport\TransportInterface;
use InvalidArgumentException;

abstract class AbstractResource
{
    protected const DEFAULT_HEADERS = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    protected TransportInterface $transport;
    protected string $baseUri;
    protected AuthenticationInterface $authentication;

    public function __construct(
        TransportInterface $transport,
        string $baseUri,
        ?AuthenticationInterface $authentication = null
    ) {
        if (!filter_var($baseUri, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Base URI cannot be empty');
        }

        $this->transport = $transport;
        $this->baseUri = $baseUri;
        $this->authentication = $authentication ?? new NoAuth();
    }

    /**
     * @param string $path
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     *
     * @return \BnplPartners\Factoring004\Transport\ResponseInterface
     *
     * @throws \BnplPartners\Factoring004\Exception\DataSerializationException
     * @throws \BnplPartners\Factoring004\Exception\NetworkException
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    protected function postRequest(string $path, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->request('POST', $path, $data, $headers);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     *
     * @return \BnplPartners\Factoring004\Transport\ResponseInterface
     *
     * @throws \BnplPartners\Factoring004\Exception\DataSerializationException
     * @throws \BnplPartners\Factoring004\Exception\NetworkException
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    protected function request(string $method, string $path, array $data = [], array $headers = []): ResponseInterface
    {
        $this->transport->setBaseUri($this->baseUri);
        $this->transport->setAuthentication($this->authentication);
        $this->transport->setHeaders(static::DEFAULT_HEADERS);

        return $this->transport->request($method, $path, $data, $headers);
    }
}
