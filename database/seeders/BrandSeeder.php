<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [

            // Clothing brands
            ['name' => 'Nike', 'featured' => true, 'logo' => 'brands/nike.png'],
            ['name' => 'Adidas', 'featured' => true, 'logo' => 'brands/adidas.png'],
            ['name' => 'Zara', 'featured' => true, 'logo' => 'brands/zara.png'],
            ['name' => 'H&M', 'featured' => true, 'logo' => 'brands/h&m.png'],
            ['name' => 'Levi\'s', 'featured' => false, 'logo' => 'brands/levis.png'],
            ['name' => 'Gap', 'featured' => false, 'logo' => 'brands/gap.png'],
            ['name' => 'Calvin Klein', 'featured' => false, 'logo' => 'brands/calvinklein.png'],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'logo' =>  $brand['logo'],
                'featured' => $brand['featured'],
            ]);
        }

        $this->command->info('Brands seeded!');
    }
}
