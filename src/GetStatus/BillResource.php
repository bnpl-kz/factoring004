<?php

namespace BnplPartners\Factoring004\GetStatus;

use BnplPartners\Factoring004\AbstractResource;
use BnplPartners\Factoring004\Exception\AuthenticationException;
use BnplPartners\Factoring004\Exception\DataSerializationException;
use BnplPartners\Factoring004\Exception\EndpointUnavailableException;
use BnplPartners\Factoring004\Exception\ErrorResponseException;
use BnplPartners\Factoring004\Exception\NetworkException;
use BnplPartners\Factoring004\Exception\TransportException;
use BnplPartners\Factoring004\Exception\UnexpectedResponseException;
use BnplPartners\Factoring004\Transport\ResponseInterface;

class BillResource extends AbstractResource
{

    /**
     * @param string $orderID
     * @throws ErrorResponseException
     * @throws NetworkException
     * @throws EndpointUnavailableException
     * @throws UnexpectedResponseException
     * @throws DataSerializationException
     * @throws AuthenticationException
     * @throws TransportException
     * @return StatusResponse
     */
    public function getStatus($orderID)
    {
        $response = $this->request('GET', sprintf('/bnpl/bill/%s/status', $orderID));

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return StatusResponse::create($response->getBody());
        }

        $this->handleClientError($response);

        throw new EndpointUnavailableException($response);
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\AuthenticationException
     * @throws \BnplPartners\Factoring004\Exception\ErrorResponseException
     * @throws \BnplPartners\Factoring004\Exception\UnexpectedResponseException
     * @return void
     */
    private function handleClientError(ResponseInterface $response)
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
                throw new UnexpectedResponseException($response, isset($data['message']) ? $data['message'] : 'Unexpected response schema');
            }

            $code = (int) $data['code'];

            if (in_array($code, static::AUTH_ERROR_CODES, true)) {
                throw new AuthenticationException(isset($data['description']) ? $data['description'] : '', isset($data['message']) ? $data['message'] : '', $code);
            }

            /** @psalm-suppress ArgumentTypeCoercion */
            throw new ErrorResponseException(\BnplPartners\Factoring004\Response\ErrorResponse::createFromArray($data));
        }
    }
}