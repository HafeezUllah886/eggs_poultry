<?php

namespace Database\Seeders;

use App\Models\auctions;
use App\Models\category;
use App\Models\makers;
use Illuminate\Database\Seeder;

class auctionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => "Uss Yokohama"],
        ];
        auctions::insert($data);


        $data1 = [
            ['name' => "Suzuki"],
            ['name' => "Toyota"],
            ['name' => "Honda"],
            ['name' => "Nissan"],
            ['name' => "Mitsubishi"],
        ];
        makers::insert($data1);

        $data2 = [
            ['name' => "Car"],
            ['name' => "Bike"],
        
        ];
        category::insert($data2);
    }
}
