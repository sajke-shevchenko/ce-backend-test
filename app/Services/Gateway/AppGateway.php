<?php

namespace App\Services\Gateway;

use App\Data\Merchant\MerchantData;
use App\Exceptions\Gateway\GatewayRequestException;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Generator\RandomBytesGenerator;

class AppGateway extends GatewayAbstract
{
    protected function getMerchant(): MerchantData
    {
        return MerchantData::from([
            'id' => config('gateway.app.id'),
            'key' => config('gateway.app.key'),
            'callback_url' => config('gateway.app.callback_url'),
            'limit' => config('gateway.app.limit'),
        ]);
    }

    protected function getGatewayData(Payment $payment): array
    {
        $randomizer = new RandomBytesGenerator();

        $gatewayData = [
            'amount' => $payment->amount,
            'amountPaid' => $payment->amount_paid,
            'status' => $payment->status,
            'project' => $this->merchantData->id,
            'invoice' => $payment->id,
            'rand' => $randomizer->generate(8)
        ];

        $gatewayData['sign'] = $this->getSign($gatewayData);

        return $gatewayData;
    }

    protected function getSign(array $signData): string
    {
        ksort($signData);

        return hash("md5", implode('.', $signData) . $this->merchantData->key);
    }

    /**
     * @throws GatewayRequestException
     */
    public function sendRequest(Payment $payment): void
    {
        $gatewayData = $this->getGatewayData($payment);

        $response = Http::withHeaders([
            'Accept' => 'multipart/form-data',
            'Authorization' => $gatewayData['sign']
        ])->post($this->merchantData->callbackUrl, [
            'amount' => $gatewayData['amount'],
            'amount_paid' => $gatewayData['amountPaid'],
            'status' => $gatewayData['status'],
            'project' => $gatewayData['project'],
            'invoice' => $gatewayData['invoice'],
            'rand' => $gatewayData['rand'],
        ]);

        if ($response->status() !== 200) {
            throw new GatewayRequestException($response->status());
        }
    }
}