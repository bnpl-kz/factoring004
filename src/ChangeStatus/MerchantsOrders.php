<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\ChangeStatus;

use BnplPartners\Factoring004\ArrayInterface;

class MerchantsOrders implements ArrayInterface
{
    private string $merchantId;

    /**
     * @var \BnplPartners\Factoring004\ChangeStatus\AbstractMerchantOrder[]
     */
    private array $orders;

    /**
     * @param \BnplPartners\Factoring004\ChangeStatus\AbstractMerchantOrder[] $orders
     */
    public function __construct(string $merchantId, array $orders)
    {
        $this->merchantId = $merchantId;
        $this->orders = $orders;
    }

    /**
     * @param array<string, mixed> $merchantsOrders
     * @psalm-param array{merchantId: string, orders: array{orderId: string, status: string, amount?: int}[]} $merchantsOrders
     */
    public static function createFromArray(array $merchantsOrders): MerchantsOrders
    {
        return new self(
            $merchantsOrders['merchantId'],
            array_map(function (array $order) {
                return array_key_exists('amount', $order)
                    ? ReturnOrder::createFromArray($order)
                    : DeliveryOrder::createFromArray($order);
            }, $merchantsOrders['orders'])
        );
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    /**
     * @return \BnplPartners\Factoring004\ChangeStatus\AbstractMerchantOrder[]
     */
    public function getOrders(): array
    {
        return $this->orders;
    }

    /**
     * @psalm-return array{merchantId: string, orders: array{orderId: string, status: string, amount?: int}[]}
     */
    public function toArray(): array
    {
        return [
            'merchantId' => $this->getMerchantId(),
            'orders' => array_map(fn(AbstractMerchantOrder $order) => $order->toArray(), $this->getOrders()),
        ];
    }
}