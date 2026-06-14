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
        Schema::create('designs', function (Blueprint $table) {
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
            $table->boolean('design_option')->default(false);
            $table->boolean('create_mockup')->default(false);
            $table->boolean('review_client')->default(false);

            $table->string('design_1st_packaging')->nullable();
            $table->string('design_2nd_packaging')->nullable();

            $table->string('regulator_status')->nullable();
            $table->string('status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};
