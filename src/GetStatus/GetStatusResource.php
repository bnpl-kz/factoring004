<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\GetStatus;

use BnplPartners\Factoring004\AbstractResource;
use BnplPartners\Factoring004\Exception\AuthenticationException;
use BnplPartners\Factoring004\Exception\DataSerializationException;
use BnplPartners\Factoring004\Exception\EndpointUnavailableException;
use BnplPartners\Factoring004\Exception\ErrorResponseException;
use BnplPartners\Factoring004\Exception\NetworkException;
use BnplPartners\Factoring004\Exception\TransportException;
use BnplPartners\Factoring004\Exception\UnexpectedResponseException;
use BnplPartners\Factoring004\Response\ErrorResponse;
use BnplPartners\Factoring004\Transport\ResponseInterface;

class GetStatusResource extends AbstractResource
{
    private string $byPreappPath = '/bnpl/preapp/%s/status';
    private string $byBillNumberPath = '/bnpl/bill/%s/status';

    /**
     * @throws ErrorResponseException
     * @throws NetworkException
     * @throws EndpointUnavailableException
     * @throws DataSerializationException
     * @throws UnexpectedResponseException
     * @throws TransportException
     * @throws AuthenticationException
     */
    private function getStatus(string $path): GetStatusResponse
    {
        $response = $this->request('GET', $path);

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return GetStatusResponse::create($response->getBody());
        }

        $this->handleClientError($response);

        throw new EndpointUnavailableException($response);
    }

    /**
     * @throws ErrorResponseException
     * @throws NetworkException
     * @throws DataSerializationException
     * @throws UnexpectedResponseException
     * @throws EndpointUnavailableException
     * @throws AuthenticationException
     * @throws TransportException
     */
    public function getStatusByOrderID(string $orderID): GetStatusResponse
    {
        $path = sprintf($this->byBillNumberPath, $orderID);
        return $this->getStatus($path);
    }

    /**
     * @throws ErrorResponseException
     * @throws NetworkException
     * @throws EndpointUnavailableException
     * @throws DataSerializationException
     * @throws UnexpectedResponseException
     * @throws TransportException
     * @throws AuthenticationException
     */
    public function getStatusByPreappID(string $preappID): GetStatusResponse
    {
        $path = sprintf($this->byPreappPath, $preappID);
        return $this->getStatus($path);
    }

    /**
     * @throws AuthenticationException
     * @throws ErrorResponseException
     * @throws UnexpectedResponseException
     */
    private function handleClientError(ResponseInterface $response): void
    {
        if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 500) {
            $data = $response->getBody();

            if (isset($data['error']) && is_array($data['error'])) {
                $data = $data['error'];
            }

            if (isset($data['fault']) && is_array($data['fault'])) {
                $data = $data['fault'];
            }

            if (empty($data['code'])) {
                throw new UnexpectedResponseException($response, $data['message'] ?? 'Unexpected response schema');
            }

            if ($response->getStatusCode() === 401) {
                throw new AuthenticationException('', $data['message'] ?? '', $data['code']);
            }

            /** @psalm-suppress ArgumentTypeCoercion */
            throw new ErrorResponseException(ErrorResponse::createFromArray($data));
        }
    }

}