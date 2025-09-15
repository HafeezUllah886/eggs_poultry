<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OilProducts;

class oilProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            [ 'code' => '1234',
            'name' => 'Organic SL',
            'ltr' => '4 Ltr',
            'grade' => '20W-50',
            'packing' => 'CTN',
            'pprice' => '5600',],
            [ 'code' => '4567',
            'name' => 'Moxie SN',
            'ltr' => '1 Ltr',
            'grade' => '10W-40',
            'packing' => 'CTN',
            'pprice' => '1800',],
        ];
      
        OilProducts::insert($data);
    }
}
