<?php

namespace App\Services\Gateway;

use App\Data\Merchant\MerchantData;
use App\Models\Payment;

abstract class GatewayAbstract
{
    /**
     * Merchant credentials.
     *
     * @var MerchantData
     */
    protected MerchantData $merchantData;

    public function __construct()
    {
        $this->merchantData = $this->getMerchant();
    }

    /**
     * Generate and get sign by given data.
     *
     * @param array $signData
     *
     * @return string
     */
    abstract protected function getSign(array $signData): string;

    /**
     * Get gateway data.
     *
     * @param Payment $payment
     *
     * @return array
     */
    abstract protected function getGatewayData(Payment $payment): array;

    /**
     * Get merchant credentials.
     *
     * @return MerchantData
     */
    abstract protected function getMerchant(): MerchantData;

    /**
     * Send request to the merchant callback url.
     *
     * @param Payment $payment
     *
     * @return void
     */
    abstract public function sendRequest(Payment $payment): void;

    /**
     * Get limit (in merchant currency).
     *
     * @return int
     */
    public function getLimit(): int
    {
        return $this->merchantData->limit;
    }

    /**
     * Get merchant id.
     *
     * @return int
     */
    public function getMerchantId(): int
    {
        return $this->merchantData->id;
    }
}
