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
        Schema::create('bo_m_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')->constrained('bo_m_s')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials');
            $table->integer('quantity');
            $table->string('unit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bo_m_components');
    }
};
