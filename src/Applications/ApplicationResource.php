<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\Applications;

use BnplPartners\Factoring004\AbstractResource;
use BnplPartners\Factoring004\Exception\AuthenticationException;
use BnplPartners\Factoring004\Exception\EndpointUnavailableException;
use BnplPartners\Factoring004\Exception\ErrorResponseException;
use BnplPartners\Factoring004\Exception\UnexpectedResponseException;
use BnplPartners\Factoring004\Response\ErrorResponse;
use BnplPartners\Factoring004\Transport\ResponseInterface;

class ApplicationResource extends AbstractResource
{

    /**
     * @throws \BnplPartners\Factoring004\Exception\NetworkException
     * @throws EndpointUnavailableException
     * @throws \BnplPartners\Factoring004\Exception\DataSerializationException
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    public function partialReturn(ApplicationPartialReturn $applicationPartialReturn): ApplicationResponse
    {
        $response = $this->request(
            'PUT',
            '/accountingservice/1.0/applications/'.
            $applicationPartialReturn->getMerchantId().'/'.
            $applicationPartialReturn->getMerchantOrderId().'/part-return',
            $applicationPartialReturn->toArray()
        );

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return new ApplicationResponse($response->getBody());
        }

        $this->handleClientError($response);

        throw new EndpointUnavailableException($response);
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\NetworkException
     * @throws EndpointUnavailableException
     * @throws \BnplPartners\Factoring004\Exception\DataSerializationException
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    public function fullReturn(ApplicationFullReturn $applicationFullReturn): ApplicationResponse
    {
        $response = $this->request(
            'PUT',
            '/accountingservice/1.0/applications/'.
            $applicationFullReturn->getMerchantId().'/'.
            $applicationFullReturn->getMerchantOrderId().'/return',
        );

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return new ApplicationResponse($response->getBody());
        }

        $this->handleClientError($response);

        throw new EndpointUnavailableException($response);
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\NetworkException
     * @throws EndpointUnavailableException
     * @throws \BnplPartners\Factoring004\Exception\DataSerializationException
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    public function delivered(ApplicationDelivered $applicationDelivered): ApplicationResponse
    {
        $response = $this->request(
            'PUT',
            '/accountingservice/1.0/applications/'.
            $applicationDelivered->getMerchantId().'/'.
            $applicationDelivered->getMerchantOrderId().'/delivered',
        );

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return new ApplicationResponse($response->getBody());
        }

        $this->handleClientError($response);

        throw new EndpointUnavailableException($response);
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\NetworkException
     * @throws EndpointUnavailableException
     * @throws \BnplPartners\Factoring004\Exception\DataSerializationException
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    public function canceled(ApplicationCanceled $applicationCanceled): ApplicationResponse
    {
        $response = $this->request(
            'PUT',
            '/accountingservice/1.0/applications/'.
            $applicationCanceled->getMerchantId().'/'.
            $applicationCanceled->getMerchantOrderId().'/canceled',
        );

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return new ApplicationResponse($response->getBody());
        }

        $this->handleClientError($response);

        throw new EndpointUnavailableException($response);
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\AuthenticationException
     * @throws \BnplPartners\Factoring004\Exception\ErrorResponseException
     * @throws \BnplPartners\Factoring004\Exception\UnexpectedResponseException
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

            $code = (int) $data['code'];

            if (in_array($code, static::AUTH_ERROR_CODES, true)) {
                throw new AuthenticationException($data['description'] ?? '', $data['message'] ?? '', $code);
            }

            /** @psalm-suppress ArgumentTypeCoercion */
            throw new ErrorResponseException(ErrorResponse::createFromArray($data));
        }
    }
}