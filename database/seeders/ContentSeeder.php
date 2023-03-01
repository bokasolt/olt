<?php

namespace Database\Seeders;

use App\Models\Content;
use Faker\Provider\Address;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        try {
            Content::create([
                'path' => 'about',
                'title' => 'About',
                'menu_order' => 1,
                'body' => $faker->text(3000)
            ]);
            Content::create([
                'path' => 'faq',
                'title' => 'FAQ',
                'menu_order' => 1,
                'body' => $faker->text(3000)
            ]);
            Content::create([
                'path' => 'support',
                'title' => 'Support',
                'menu_order' => 1,
                'body' => $faker->text(3000)
            ]);
            $terms = Content::create([
                'path' => 'terms',
                'title' => 'Terms & Conditions',
                'body' => $faker->text(3000)
            ]);
            $terms->system = 1;
            $terms->update();
        } catch (\Exception $e) {
        }
    }
}
