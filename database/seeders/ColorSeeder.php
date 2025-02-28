<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Black', 'value' => '#000000'],
            ['name' => 'White', 'value' => '#FFFFFF'],
            ['name' => 'Red', 'value' => '#FF0000'],
            ['name' => 'Green', 'value' => '#008000'],
            ['name' => 'Blue', 'value' => '#0000FF'],
            ['name' => 'Yellow', 'value' => '#FFFF00'],
            ['name' => 'Purple', 'value' => '#800080'],
            ['name' => 'Orange', 'value' => '#FFA500'],
            ['name' => 'Pink', 'value' => '#FFC0CB'],
            ['name' => 'Grey', 'value' => '#808080'],
            ['name' => 'Brown', 'value' => '#A52A2A'],
            ['name' => 'Navy', 'value' => '#000080'],
            ['name' => 'Teal', 'value' => '#008080'],
            ['name' => 'Gold', 'value' => '#FFD700'],
            ['name' => 'Silver', 'value' => '#C0C0C0'],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }

        $this->command->info('Colors seeded!');
    }
}
