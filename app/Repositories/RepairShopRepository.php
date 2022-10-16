<?php

namespace App\Repositories;

use App\Models\RepairShop;

class RepairShopRepository
{
    public function getAllRepairShopsLatLongs(): array
    {
        return RepairShop::get(['id', 'name', 'latitude', 'longitude'])
                ->toArray();
    }
}
