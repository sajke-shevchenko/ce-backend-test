<?php

namespace App\Services\Gateway\Factory;

use App\Services\Gateway\GatewayAbstract;
use App\Services\Gateway\MerchantGateway;

class MerchantGatewayFactory
{
    public static function createGateway(): GatewayAbstract
    {
        return new MerchantGateway();
    }
}