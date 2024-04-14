<?php

namespace App\Services\Payment;

use App\Data\Payment\PaymentData;
use App\Exceptions\Gateway\PaymentLimitException;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class PaymentService
{
    public function update(Payment $payment, PaymentData $paymentData, User|Authenticatable|null $user): void
    {
        if (!$user || $payment->user_id !== $user->id) {
            throw new AccessDeniedException();
        }

        $payment->status = $paymentData->status;

        if ($payment->isDirty()) {
            $payment->save();
        }
    }

    /**
     * @throws PaymentLimitException
     */
    public function create(PaymentData $paymentData, User $user, int $merchantId, int $limit): void
    {
        $this->checkLimit($paymentData, $user, $merchantId, $limit);
    }

    /**
     * @throws PaymentLimitException
     */
    private function checkLimit(PaymentData $paymentData, User $user, int $merchantId, int $limit): void
    {
        $paymentsSum = Payment::query()->where('user_id', '=', $user->id)
            ->whereDate('created_at', '>=', today())
            ->where('merchant_id', '=', $merchantId)
            ->sum('amount_paid');

        if ($paymentsSum > $limit || ($paymentsSum + $paymentData->amountPaid) > $limit) {
            throw new PaymentLimitException();
        }
    }
}