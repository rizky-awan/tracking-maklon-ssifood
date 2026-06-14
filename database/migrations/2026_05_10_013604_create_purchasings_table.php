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
        Schema::create('purchasings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_master_id')
                ->constrained()
                ->cascadeOnDelete();

            // INFO
            $table->string('brand_name')->nullable();
            $table->string('client_name')->nullable();
            $table->string('product_type')->nullable();
            $table->string('variant')->nullable();

            // PROCESS
            $table->string('raw_material')->nullable();
            $table->string('price_1st_packaging')->nullable();
            $table->string('price_2nd_packaging')->nullable();

            $table->boolean('dummy_1')->default(false);
            $table->boolean('dummy_2')->default(false);

            $table->string('approve_dummy_1')->nullable();
            $table->string('approve_dummy_2')->nullable();

            $table->string('final_design')->nullable();
            $table->string('po_status')->nullable();
            $table->string('printing_approve')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchasings');
    }
};
