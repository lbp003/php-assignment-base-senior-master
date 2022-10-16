<?php

namespace Database\Seeders;

use App\Models\RepairShop;
use Illuminate\Database\Seeder;

class RepairShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RepairShop::factory()
            ->count(10)
            ->create();
    }
}
