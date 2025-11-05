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
        Schema::create('vials', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_id');
            $table->string('vial_number', 50); // e.g., "VIAL #1", "V-001"
            $table->string('unique_code', 255)->unique(); // Human-readable unique code
            $table->text('qr_code',500); // QR code data (URL)
            $table->string('qr_code_image')->nullable(); // Path to QR code image
            $table->boolean('is_scanned')->default(false);
            $table->timestamp('first_scan_at')->nullable();
            $table->integer('scan_count')->unsigned()->default(0);
            $table->enum('status', ['available', 'used', 'damaged', 'recalled', 'pending'])->default('available');
            $table->decimal('volume', 8, 2)->nullable(); // Volume in ml
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('is_scanned');
            $table->unique(['batch_id', 'vial_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vials');
    }
};
