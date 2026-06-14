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
        Schema::create('legals', function (Blueprint $table) {
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
            $table->string('contract_kirim')->nullable();
            $table->string('contract_terima')->nullable();
            $table->string('lab_test')->nullable();
            $table->string('ingredients')->nullable();
            $table->string('nutrition_fact')->nullable();
            $table->string('checking_label')->nullable();
            $table->string('status_legal')->nullable();
            $table->string('bpom')->nullable();
            $table->string('barcode')->nullable();
            $table->string('status_label')->nullable();
            $table->string('print1')->nullable();
            $table->string('print2')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legals');
    }
};
