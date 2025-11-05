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
        Schema::create('skus', function (Blueprint $table) {
            $table->id();
            $table->string('sku_code', 100)->unique();
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->string('category', 100)->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->string('unit_measure', 50)->nullable(); // mg, ml, g, l, units
            $table->string('manufacturer')->nullable();
            $table->string('product_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Additional custom fields
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('sku_code');
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skus');
    }
};
