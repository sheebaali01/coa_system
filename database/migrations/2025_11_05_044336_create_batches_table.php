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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->integer('sku_id');
            $table->string('batch_number', 100)->unique();
            $table->integer('total_vials')->unsigned();
            $table->date('manufacture_date');
            $table->date('expiry_date');
            $table->string('coa_document')->nullable(); // Path to COA PDF
            $table->json('lab_results')->nullable(); // Purity, concentration, etc.
            $table->enum('status', ['active', 'expired', 'recalled', 'pending'])->default('active');
            // $table->text('notes')->nullable();
            // $table->string('storage_conditions')->nullable();
            // $table->decimal('batch_size', 10, 2)->nullable(); // Total quantity
            $table->string('lot_number')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('batch_number');
            $table->index('status');
            $table->index(['manufacture_date', 'expiry_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
