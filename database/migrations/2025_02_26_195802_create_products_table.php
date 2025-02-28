<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('sku')->unique();
            $table->string('image')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('featured')->default(false);
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->json('specifications')->nullable();
            $table->decimal('average_rating', 3, 2)->nullable();
            $table->boolean('on_sale')->default(false);
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->date('new_until')->nullable();
            $table->timestamps();

            // Indexes for product filtering
            $table->index('active');
            $table->index('category_id');
            $table->index('brand_id');
            $table->index('price');
            $table->index('on_sale');
            $table->index('featured');
            $table->index('average_rating');

            // Compound indexes for common filter combinations
            $table->index(['active', 'category_id']);
            $table->index(['active', 'price']);
            $table->index(['active', 'on_sale']);
            $table->index(['category_id', 'price']);
            $table->index(['brand_id', 'price']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
