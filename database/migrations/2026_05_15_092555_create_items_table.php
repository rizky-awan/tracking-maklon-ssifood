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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->string('code') ->nullable();
            $table->string('name');

            $table->enum('type', ['raw_material', 'packaging']);

            $table->string('unit');
            $table->integer('min_stock')->default(0);

            $table->boolean('track_expiry')->default(false);
            $table->string('producer')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
