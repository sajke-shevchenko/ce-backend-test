<?php

namespace App\Enums;

class PaymentStatusEnum
{
    public const NEW = 'new';
    public const CREATED = 'created';
    public const IN_PROGRESS = 'inprogress';
    public const PENDING = 'pending';
    public const COMPLETED = 'completed';
    public const PAID = 'paid';
    public const EXPIRED = 'expired';
    public const REJECTED = 'rejected';

    public static function getAvailableStatuses(): array
    {
        return [
            self::NEW,
            self::CREATED,
            self::IN_PROGRESS,
            self::PENDING,
            self::COMPLETED,
            self::PAID,
            self::EXPIRED,
            self::REJECTED,
        ];
    }
}