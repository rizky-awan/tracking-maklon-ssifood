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
        Schema::create('client_masters', function (Blueprint $table) {
             $table->id();

            $table->string('brand_name');
            $table->string('client_name');
            $table->string('product_type');
            $table->string('variant');

            $table->string('category');
            $table->string('payment_status');

            $table->string('pic')->nullable();
            $table->string('design_from')->nullable();

            // payment dates
            $table->date('sample_payment_date')->nullable();
            $table->date('lab_legal_dp_date')->nullable();
            $table->date('dp_50_date')->nullable();
            $table->date('full_payment_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_masters');
    }
};
