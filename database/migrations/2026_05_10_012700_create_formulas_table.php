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
        Schema::create('formulas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_master_id')
                ->constrained()
                ->cascadeOnDelete();

            // INFO (denormalized)
            $table->string('brand_name')->nullable();
            $table->string('client_name')->nullable();
            $table->string('product_type')->nullable();
            $table->string('variant')->nullable();

            // progress
            $table->string('formula_progress')->nullable();
            $table->string('availability')->nullable();
            $table->string('status')->nullable();
            $table->string('cpb_status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulas');
    }
};
