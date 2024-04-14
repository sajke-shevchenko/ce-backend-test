<?php

namespace App\Http\Requests;

use App\Data\Payment\PaymentData;
use App\Enums\GatewayEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'currency_id' => 'required|integer|exists:currencies,id',
            'amount' => 'required|numeric|min:1',
            'amount_paid' => 'required|numeric|min:1',
            'status' => 'required|string|in:' . implode(",", PaymentStatusEnum::getAvailableStatuses()),
            'gateway_type' => 'required|in:' . implode(",", GatewayEnum::getAvailableGateways()),
        ];
    }

    public function getPaymentData(): PaymentData
    {
        return PaymentData::from([
            'amount' => $this->get('amount'),
            'amountPaid' => $this->get('amount_paid'),
            'status' => $this->get('status'),
        ]);
    }
}