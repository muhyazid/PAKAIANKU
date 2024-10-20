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
        Schema::create('manufacturing_order_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manufacturing_order_id')->constrained('manufacturing_orders')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials');
            $table->integer('to_consume');  // Jumlah yang harus dikonsumsi
            $table->integer('consumed')->default(0); // Jumlah yang telah dikonsumsi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturing_order_materials');
    }
};
