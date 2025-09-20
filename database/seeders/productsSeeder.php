<?php

namespace Database\Seeders;

use App\Models\product_dc;
use App\Models\product_units;
use App\Models\products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class productsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => "Egg for Eating","pprice" => 15, "sprice" => 20, 'for_production' => 'No', 'status' => 'Active'],
            ['name' => "Egg for Production","pprice" => 15, "sprice" => 20, 'for_production' => 'Yes', 'status' => 'Active'],
           
        ];
        products::insert($data);

        $data1 = [
            ['product_id' => 1, 'name' => 'Pieces', 'value' => 1],
            ['product_id' => 1, 'name' => 'Dzn', 'value' => 12],
            ['product_id' => 1, 'name' => 'Tray', 'value' => 30],
            ['product_id' => 1, 'name' => 'Carton', 'value' => 100],
            ['product_id' => 2, 'name' => 'Pieces', 'value' => 1],
            ['product_id' => 2, 'name' => 'Dzn', 'value' => 12],
            ['product_id' => 2, 'name' => 'Tray', 'value' => 30],
            ['product_id' => 2, 'name' => 'Carton', 'value' => 100],
           
        ];

        product_units::insert($data1);

    }
}
