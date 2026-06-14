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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();

            $table->enum('type', ['IN', 'OUT']); // 🔥 wajib

            $table->integer('qty');

            $table->date('date');

            $table->string('source')->nullable(); 
            // contoh: purchase, production, adjustment

            $table->string('reference_type')->nullable(); 
            // contoh: WORK_ORDER

            $table->unsignedBigInteger('reference_id')->nullable(); 
            // id WO

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
