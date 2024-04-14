<?php

namespace App\Services\Gateway;

use App\Data\Merchant\MerchantData;
use App\Exceptions\Gateway\GatewayRequestException;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class MerchantGateway extends GatewayAbstract
{
    protected function getMerchant(): MerchantData
    {
        return MerchantData::from([
            'id' => config('gateway.merchant.id'),
            'key' => config('gateway.merchant.key'),
            'callback_url' => config('gateway.merchant.callback_url'),
            'limit' => config('gateway.merchant.limit'),
        ]);
    }

    protected function getGatewayData(Payment $payment): array
    {
        $gatewayData = [
            'amount' => $payment->amount,
            'amountPaid' => $payment->amount_paid,
            'status' => $payment->status,
            'timestamp' => now()->timestamp,
            'merchantId' => $this->merchantData->id,
            'paymentId' => $payment->id,
        ];

        $gatewayData['sign'] = $this->getSign($gatewayData);
        $gatewayData['callbackUrl'] = $this->merchantData->callbackUrl;

        return $gatewayData;
    }

    protected function getSign(array $signData): string
    {
        ksort($signData);

        return hash("sha256", implode(':', $signData) . $this->merchantData->key);
    }

    /**
     * @throws GatewayRequestException
     */
    public function sendRequest(Payment $payment): void
    {
        $gatewayData = $this->getGatewayData($payment);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post($this->merchantData->callbackUrl, [
            'amount' => $gatewayData['amount'],
            'amount_paid' => $gatewayData['amountPaid'],
            'status' => $gatewayData['status'],
            'merchant_id' => $gatewayData['merchantId'],
            'payment_id' => $gatewayData['paymentId'],
            'timestamp' => $gatewayData['timestamp'],
            'sign' => $gatewayData['sign'],
        ]);

        if ($response->status() !== 200) {
            throw new GatewayRequestException($response->status());
        }
    }
}
