<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Domain;

class DomainSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();
        Domain::truncate();
        for ($i = 0; $i < 100; $i++) {
            Domain::create([
                'domain' => strtolower($faker->name) . '.com',
            ]);
        }
    }
}
