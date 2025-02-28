<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [

            [
                'name' => 'Clothing',
                'children' => [
                    ['name' => 'Men\'s Clothing'],
                    ['name' => 'Women\'s Clothing'],
                ]
            ],


        ];

        $faker = fake();
        foreach ($categories as $index => $categoryData) {
            $parentCategory = Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'position' => $index,
                'active' => true,
            ]);

            // Insert subcategories
            foreach ($categoryData['children'] as $childIndex => $childData) {
                Category::create([
                    'name' => $childData['name'],
                    'slug' => Str::slug($childData['name']),
                    'parent_id' => $parentCategory->id,
                    'position' => $childIndex,
                    'active' => true,
                ]);
            }
        }

        $this->command->info('Categories seeded!');
    }
}
