<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            // Clothing sizes
            ['name' => 'X-Small', 'value' => 'XS', 'type' => 'clothing'],
            ['name' => 'Small', 'value' => 'S', 'type' => 'clothing'],
            ['name' => 'Medium', 'value' => 'M', 'type' => 'clothing'],
            ['name' => 'Large', 'value' => 'L', 'type' => 'clothing'],
            ['name' => 'X-Large', 'value' => 'XL', 'type' => 'clothing'],
            ['name' => '2X-Large', 'value' => '2XL', 'type' => 'clothing'],
        ];

        foreach ($sizes as $size) {
            Size::create($size);
        }

        $this->command->info('Sizes seeded!');
    }
}
