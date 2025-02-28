<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalRecords = env('SEED_PRODUCTS_COUNT', 1000);
        Schema::disableForeignKeyConstraints();
        // Load all categories, brands, colors, and sizes (to avoid repeated queries)
        $categories = Category::all()->keyBy('id')->all();
        $brands = Brand::all()->keyBy('id')->all();
        $colors = Color::all()->keyBy('id')->all();

        // Group sizes by type for easier assignment
        $sizesByType = [];
        foreach (Size::all() as $size) {
            if (!isset($sizesByType[$size->type])) {
                $sizesByType[$size->type] = [];
            }
            $sizesByType[$size->type][] = $size;
        }

        // Define product type configurations
        $productTypes = [
            'Men\'s Clothing' => [
                'brands' => [1, 2, 3, 4],
                'price_range' => [19, 129],
                'has_variants' => true,
                'variant_types' => ['color', 'size'],
                'color_ids' => [1, 2, 3, 5, 6, 10, 12],
                'size_type' => 'clothing',
                'image_prefix' => 'clothing/mens',
                'description_template' => 'This {brand} {name} is perfect for any occasion. Made from high-quality materials, it offers both comfort and style. Available in {color} and multiple sizes.',
                'specs' => [
                    'Material' => ['Cotton', 'Polyester', 'Cotton Blend', 'Wool', 'Denim'],
                    'Fit' => ['Regular', 'Slim', 'Relaxed', 'Athletic'],
                    'Occasion' => ['Casual', 'Formal', 'Sports', 'Everyday']
                ]
            ],
            'Women\'s Clothing' => [
                'brands' => [1, 2, 5, 6, 7],
                'price_range' => [24, 149],
                'has_variants' => true,
                'variant_types' => ['color', 'size'],
                'color_ids' => [1, 2, 3, 5, 6, 8, 9, 10],
                'size_type' => 'clothing',
                'image_prefix' => 'clothing/womens',
                'description_template' => 'This stylish {brand} {name} combines fashion and comfort. The {material} fabric ensures durability while the {color} color adds a touch of elegance to your wardrobe.',
                'specs' => [
                    'Material' => ['Cotton', 'Polyester', 'Rayon', 'Silk', 'Linen'],
                    'Style' => ['Casual', 'Formal', 'Bohemian', 'Classic', 'Trendy'],
                    'Season' => ['All Season', 'Summer', 'Winter', 'Spring/Fall']
                ]
            ],
        ];

        $batchSize = min(5000, $totalRecords);
        $totalBatches = ceil($totalRecords / $batchSize);

        $productColorRelations = [];
        $productSizeRelations = [];
        $productVariants = [];
        $productImages = [];
        $variantColorRelations = []; // New array for variant_color relations
        $variantSizeRelations = []; // New array for variant_size relations

        $categoryNameToId = [];
        foreach ($categories as $category) {
            $categoryNameToId[$category->name] = $category->id;
        }

        // Product seeding loop
        for ($batch = 1; $batch <= $totalBatches; $batch++) {
            $this->command->info("Processing batch {$batch} of {$totalBatches}...");

            // Begin transaction for this batch
            DB::beginTransaction();

            try {
                $products = [];
                $currentBatchSize = min($batchSize, $totalRecords - (($batch - 1) * $batchSize));
                $variantId = 1; // Starting ID for variants (will be incremented)

                for ($i = 0; $i < $currentBatchSize; $i++) {
                    // Generate a unique product ID for this batch
                    $productId = (($batch - 1) * $batchSize) + $i + 1;

                    // Select a random subcategory
                    $subcategoryName = array_rand($productTypes);
                    $subcategoryId = $categoryNameToId[$subcategoryName];
                    $typeConfig = $productTypes[$subcategoryName];

                    // Select a random brand for this category
                    $brandId = $typeConfig['brands'][array_rand($typeConfig['brands'])];
                    $brandName = $brands[$brandId]->name;

                    // Generate product name
                    $productName = $this->generateProductName($brandName, $subcategoryName);

                    // Generate price within the category's range
                    $price = fake()->randomFloat(2, $typeConfig['price_range'][0], $typeConfig['price_range'][1]);

                    // Apply retail pricing strategy (e.g., $99.99 instead of $100)
                    $price = $this->retailPrice($price);

                    // Some products are on sale
                    $onSale = fake()->boolean(20); // 20% chance
                    $salePrice = $onSale ? round($price * (1 - (fake()->randomElement([10, 15, 20, 25, 30]) / 100)), 2) : null;

                    // Generate SKU
                    $sku = strtoupper(substr($brandName, 0, 3)) . '-' . strtoupper(substr($subcategoryName, 0, 3)) . '-' . $productId;

                    // Generate specifications from the template
                    $specs = [];
                    foreach ($typeConfig['specs'] as $specName => $specValues) {
                        $specs[$specName] = fake()->randomElement($specValues);
                    }

                    // Prepare description with placeholders filled in
                    $description = $typeConfig['description_template'];
                    $replacements = [
                        '{brand}' => $brandName,
                        '{name}' => $productName,
                        '{color}' => $colors[$typeConfig['color_ids'][0]]->name, // Default color
                    ];

                    // Add spec-specific replacements
                    foreach ($specs as $key => $value) {
                        $description = str_replace('{' . strtolower(str_replace(' ', '_', $key)) . '}', $value, $description);
                    }

                    $description = strtr($description, $replacements);

                    // Product main image
                    $imageNumber = fake()->numberBetween(1, 4); // Assuming 4 image variations per product type
                    $image = $typeConfig['image_prefix'] . '/product-' . $imageNumber . '.jpg';

                    // Add to products array
                    $products[] = [
                        'name' => $productName,
                        'slug' => Str::slug($productName . '-' . $productId),
                        'description' => $description,
                        'price' => $price,
                        'sale_price' => $salePrice,
                        'stock' => fake()->numberBetween(0, 1000),
                        'sku' => $sku,
                        'image' => $image,
                        'active' => fake()->boolean(90), // 90% active
                        'featured' => fake()->boolean(10), // 10% featured
                        'category_id' => $subcategoryId,
                        'brand_id' => $brandId,
                        'specifications' => json_encode($specs),
                        'average_rating' => fake()->optional(0.7)->randomFloat(1, 1, 5), // 70% have ratings
                        'on_sale' => $onSale,
                        'discount_percentage' => $onSale ? fake()->randomElement([10, 15, 20, 25, 30]) : null,
                        'new_until' => fake()->boolean(30) ? fake()->dateTimeBetween('+1 month', '+3 months')->format('Y-m-d') : null,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ];

                    // Track relationships for this product
                    // Assign colors
                    if (!empty($typeConfig['color_ids'])) {
                        $colorCount = fake()->numberBetween(1, min(4, count($typeConfig['color_ids'])));
                        $productColors = fake()->randomElements($typeConfig['color_ids'], $colorCount);

                        foreach ($productColors as $colorId) {
                            $productColorRelations[] = [
                                'product_id' => $productId,
                                'color_id' => $colorId,
                                'created_at' => now()->format('Y-m-d H:i:s'),
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                            ];
                        }
                    }

                    // Assign sizes if applicable
                    $selectedSizes = [];
                    if (!empty($typeConfig['size_type']) && isset($sizesByType[$typeConfig['size_type']])) {
                        $sizesForType = $sizesByType[$typeConfig['size_type']];
                        $sizeCount = fake()->numberBetween(1, min(4, count($sizesForType)));
                        $selectedSizes = fake()->randomElements($sizesForType, $sizeCount);

                        foreach ($selectedSizes as $size) {
                            $productSizeRelations[] = [
                                'product_id' => $productId,
                                'size_id' => $size->id,
                                'created_at' => now()->format('Y-m-d H:i:s'),
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                            ];
                        }
                    }

                    // Create variants if applicable
                    if ($typeConfig['has_variants'] && !empty($productColors)) {
                        // Generate variants based on configuration
                        if (in_array('color', $typeConfig['variant_types']) && in_array('size', $typeConfig['variant_types'])) {
                            // Create color + size variants
                            foreach ($productColors as $colorId) {
                                foreach ($selectedSizes as $size) {
                                    // Create variant and get its index
                                    $newVariantId = $this->addVariantWithId(
                                        $productVariants,
                                        $variantId,
                                        $productId,
                                        $sku,
                                        $colorId,
                                        $size->id,
                                        $price,
                                        $salePrice
                                    );

                                    // Add color relationship for this variant
                                    $variantColorRelations[] = [
                                        'product_variant_id' => $newVariantId,
                                        'color_id' => $colorId,
                                        'created_at' => now()->format('Y-m-d H:i:s'),
                                        'updated_at' => now()->format('Y-m-d H:i:s'),
                                    ];

                                    // Add size relationship for this variant
                                    $variantSizeRelations[] = [
                                        'product_variant_id' => $newVariantId,
                                        'size_id' => $size->id,
                                        'created_at' => now()->format('Y-m-d H:i:s'),
                                        'updated_at' => now()->format('Y-m-d H:i:s'),
                                    ];

                                    $variantId++;
                                }
                            }
                        } elseif (in_array('color', $typeConfig['variant_types']) && in_array('storage', $typeConfig['variant_types'])) {
                            // Create color + storage variants
                            foreach ($productColors as $colorId) {
                                foreach ($selectedSizes as $size) {
                                    // Create variant and get its index
                                    $newVariantId = $this->addVariantWithId(
                                        $productVariants,
                                        $variantId,
                                        $productId,
                                        $sku,
                                        $colorId,
                                        $size->id,
                                        $price,
                                        $salePrice
                                    );

                                    // Add color relationship for this variant
                                    $variantColorRelations[] = [
                                        'product_variant_id' => $newVariantId,
                                        'color_id' => $colorId,
                                        'created_at' => now()->format('Y-m-d H:i:s'),
                                        'updated_at' => now()->format('Y-m-d H:i:s'),
                                    ];

                                    // Add size relationship for this variant
                                    $variantSizeRelations[] = [
                                        'product_variant_id' => $newVariantId,
                                        'size_id' => $size->id,
                                        'created_at' => now()->format('Y-m-d H:i:s'),
                                        'updated_at' => now()->format('Y-m-d H:i:s'),
                                    ];

                                    $variantId++;
                                }
                            }
                        } elseif (in_array('color', $typeConfig['variant_types'])) {
                            // Create color-only variants
                            foreach ($productColors as $colorId) {
                                // Create variant and get its index
                                $newVariantId = $this->addVariantWithId(
                                    $productVariants,
                                    $variantId,
                                    $productId,
                                    $sku,
                                    $colorId,
                                    null,
                                    $price,
                                    $salePrice
                                );

                                // Add color relationship for this variant
                                $variantColorRelations[] = [
                                    'product_variant_id' => $newVariantId,
                                    'color_id' => $colorId,
                                    'created_at' => now()->format('Y-m-d H:i:s'),
                                    'updated_at' => now()->format('Y-m-d H:i:s'),
                                ];

                                $variantId++;
                            }
                        }
                    }

                    // Add additional product images
                    $additionalImageCount = fake()->numberBetween(0, 4);
                    for ($j = 0; $j < $additionalImageCount; $j++) {
                        $imgNumber = fake()->numberBetween(1, 4);
                        $productImages[] = [
                            'product_id' => $productId,
                            'image' => $typeConfig['image_prefix'] . '/product-' . $imgNumber . '-alt' . '.jpg',
                            'position' => $j + 1,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ];
                    }
                }

                // Insert products in chunks
                foreach (array_chunk($products, 1000) as $chunk) {
                    Product::insert($chunk);
                }

                // Insert product-color relationships in chunks
                if (!empty($productColorRelations)) {
                    foreach (array_chunk($productColorRelations, 1000) as $chunk) {
                        DB::table('product_color')->insert($chunk);
                    }
                    $productColorRelations = []; // Free memory
                }

                // Insert product-size relationships in chunks
                if (!empty($productSizeRelations)) {
                    foreach (array_chunk($productSizeRelations, 1000) as $chunk) {
                        DB::table('product_size')->insert($chunk);
                    }
                    $productSizeRelations = []; // Free memory
                }

                // Insert product variants in chunks
                if (!empty($productVariants)) {
                    foreach (array_chunk($productVariants, 1000) as $chunk) {
                        DB::table('product_variants')->insert($chunk);
                    }
                    $productVariants = []; // Free memory
                }

                // Insert variant-color relationships in chunks
                if (!empty($variantColorRelations)) {
                    foreach (array_chunk($variantColorRelations, 1000) as $chunk) {
                        DB::table('variant_color')->insert($chunk);
                    }
                    $variantColorRelations = []; // Free memory
                }

                // Insert variant-size relationships in chunks
                if (!empty($variantSizeRelations)) {
                    foreach (array_chunk($variantSizeRelations, 1000) as $chunk) {
                        DB::table('variant_size')->insert($chunk);
                    }
                    $variantSizeRelations = []; // Free memory
                }

                // Insert product images in chunks
                if (!empty($productImages)) {
                    foreach (array_chunk($productImages, 1000) as $chunk) {
                        DB::table('product_images')->insert($chunk);
                    }
                    $productImages = []; // Free memory
                }

                // Commit this batch
                DB::commit();

                $this->command->info("Inserted " . ($batch * $batchSize) . " of {$totalRecords} products");

                // Garbage collection
                if ($batch % 5 === 0) {
                    $this->command->info("Running garbage collection...");
                    gc_collect_cycles();
                }
            } catch (\Exception $e) {
                // Rollback on error
                DB::rollBack();
                $this->command->error("Error in batch {$batch}: " . $e->getMessage());
                throw $e;
            }
        }

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        // Update auto-increment value
        DB::statement("ALTER TABLE products AUTO_INCREMENT = " . ($totalRecords + 1));

        // Update MySQL statistics
        $this->command->info('Analyzing table to update statistics...');
        DB::statement("ANALYZE TABLE products");

        $this->command->info('Product seeding completed!');
    }

    protected function generateProductName($brand, $category)
    {
        $names = [];

        switch ($category) {
            case 'Men\'s Clothing':
            case 'Women\'s Clothing':
                $types = [
                    'Men\'s Clothing' => ['T-Shirt', 'Shirt', 'Jeans', 'Pants', 'Hoodie', 'Sweater', 'Jacket', 'Coat'],
                    'Women\'s Clothing' => ['Dress', 'Blouse', 'Shirt', 'Jeans', 'Skirt', 'Sweater', 'Jacket', 'Leggings']
                ];
                $styles = ['Classic', 'Modern', 'Slim Fit', 'Relaxed', 'Athletic', 'Vintage', 'Casual', 'Formal'];

                $names = [fake()->randomElement($styles) . ' ' . fake()->randomElement($types[$category])];
                break;

            default:
                $names = ['Product ' . fake()->word . ' ' . fake()->numberBetween(100, 999)];
        }

        return fake()->randomElement($names);
    }

    /**
     * Round price to common retail price points (e.g., $99.99 instead of $100)
     */
    protected function retailPrice($price)
    {
        // Round to .99 or .95 endings
        $wholeNumber = floor($price);
        $decimal = $price - $wholeNumber;

        if ($decimal < 0.2) {
            return $wholeNumber - 0.01;
        } elseif ($decimal >= 0.2 && $decimal < 0.7) {
            return $wholeNumber + 0.49;
        } else {
            return $wholeNumber + 0.99;
        }
    }

    /**
     * Add a product variant to the variants array
     */
    protected function addVariant(&$variants, $productId, $baseSku, $colorId, $sizeId, $basePrice, $baseSalePrice)
    {
        $variantSku = $baseSku;

        if ($colorId) {
            $variantSku .= '-C' . $colorId;
        }

        if ($sizeId) {
            $variantSku .= '-S' . $sizeId;
        }

        // Variants may have slight price variations
        $priceMultiplier = fake()->randomElement([0.95, 1.0, 1.0, 1.0, 1.1, 1.2]);
        $price = round($basePrice * $priceMultiplier, 2);
        $salePrice = $baseSalePrice ? round($baseSalePrice * $priceMultiplier, 2) : null;

        $variant = [
            'product_id' => $productId,
            'sku' => $variantSku,
            'price' => $price,
            'sale_price' => $salePrice,
            'stock' => fake()->numberBetween(0, 100),
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ];

        $variants[] = $variant;

        return count($variants) - 1; // Return the index of the added variant
    }

    /**
     * Add a product variant to the variants array with a specific ID
     *
     * @return int The variant ID
     */
    protected function addVariantWithId(&$variants, $variantId, $productId, $baseSku, $colorId, $sizeId, $basePrice, $baseSalePrice)
    {
        $variantSku = $baseSku;

        if ($colorId) {
            $variantSku .= '-C' . $colorId;
        }

        if ($sizeId) {
            $variantSku .= '-S' . $sizeId;
        }

        // Variants may have slight price variations
        $priceMultiplier = fake()->randomElement([0.95, 1.0, 1.0, 1.0, 1.1, 1.2]);
        $price = round($basePrice * $priceMultiplier, 2);
        $salePrice = $baseSalePrice ? round($baseSalePrice * $priceMultiplier, 2) : null;

        $variant = [
            'id' => $variantId,
            'product_id' => $productId,
            'sku' => $variantSku,
            'price' => $price,
            'sale_price' => $salePrice,
            'stock' => fake()->numberBetween(0, 100),
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ];

        $variants[] = $variant;

        return $variantId; // Return the variant ID
    }
}
