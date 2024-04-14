<?php

namespace App\Data\Merchant;

use Spatie\LaravelData\Data;

class MerchantData extends Data
{
    public int $id;
    public string $key;
    public int $limit;
    public string $callbackUrl;
}