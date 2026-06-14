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
        Schema::create('productions', function (Blueprint $table) {
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
            $table->date('dp_date')->nullable();

            $table->boolean('balanced')->default(false);
            $table->date('balanced_date')->nullable();

            $table->boolean('mixing')->default(false);
            $table->date('mixing_date')->nullable();

            $table->boolean('filling')->default(false);
            $table->date('filling_date')->nullable();

            $table->boolean('packing')->default(false);
            $table->date('packing_date')->nullable();

            $table->date('estimasi_ready')->nullable();

            $table->boolean('sending')->default(false);
            $table->date('sending_date')->nullable();

            $table->boolean('client_receive')->default(false);

            $table->string('status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
