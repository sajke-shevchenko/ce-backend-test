<?php

namespace App\Data\Payment;

use Spatie\LaravelData\Data;

class PaymentData extends Data
{
    public string $status;
    public float|null $amount;
    public float|null $amountPaid;
}