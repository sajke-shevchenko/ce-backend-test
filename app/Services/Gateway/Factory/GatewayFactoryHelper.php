<?php

namespace App\Services\Gateway\Factory;

use App\Services\Gateway\GatewayAbstract;

class GatewayFactoryHelper
{
    public static function getGatewayFactory(string $gatewayType): GatewayAbstract
    {
        return match ($gatewayType) {
            'app' => AppGatewayFactory::createGateway(),
            'merchant' => MerchantGatewayFactory::createGateway(),
        };
    }
}