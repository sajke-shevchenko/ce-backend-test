<?php

namespace App\Services\Gateway\Factory;

use App\Services\Gateway\AppGateway;
use App\Services\Gateway\GatewayAbstract;

class AppGatewayFactory
{
    public static function createGateway(): GatewayAbstract
    {
        return new AppGateway();
    }
}