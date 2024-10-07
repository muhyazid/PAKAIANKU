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
        Schema::table('materials', function (Blueprint $table) {
            /// Hapus foreign key constraint
            $table->dropForeign(['produk_id']); 
            // Hapus kolom 'produk_id'
            $table->dropColumn('produk_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
           // Tambahkan kembali kolom 'produk_id' jika ingin rollback
            $table->unsignedBigInteger('produk_id')->nullable();

            // Tambahkan kembali foreign key constraint jika ingin rollback
            $table->foreign('produk_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
};
