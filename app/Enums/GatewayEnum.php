<?php

namespace App\Enums;

class GatewayEnum
{
    public const APP = 'app';
    public const MERCHANT = 'merchant';

    public static function getAvailableGateways(): array
    {
        return [
            self::APP,
            self::MERCHANT,
        ];
    }
}