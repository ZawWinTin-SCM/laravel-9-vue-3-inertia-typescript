<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 20) as $index) {
            Product::factory()->create();
        }
    }
}
