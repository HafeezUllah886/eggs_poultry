<?php

namespace Database\Seeders;

use App\Models\accounts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class accountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        accounts::create([
            'title'     => "Cash Account",
            'type'      => "Cash",
            'category'  => "Business",
            'currency'  => "PKR",
            'status'    => "Active",
            'branch_id' => 1,
        ]);

        accounts::create([
            'title'     => "Walk-in Supplier",
            'category'  => "Supplier",
            'currency'  => "PKR",
            'status'    => "Active",
            'branch_id' => 1,
        ]);

        accounts::create([
            'title'     => "Walk-in Customer",
            'category'  => "Customer",
            'currency'  => "PKR",
            'status'    => "Active",
            'branch_id' => 1,
        ]);

        accounts::create([
            'title'     => "Test Transporter",
            'category'  => "Transporter",
            'currency'  => "PKR",
            'status'    => "Active",
            'branch_id' => 1,
        ]);

    }
}
