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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('SpbuId'); // Tambahkan SpbuId
            $table->unsignedBigInteger('NozzelId');
            $table->unsignedBigInteger('PulauId');
            $table->unsignedBigInteger('ProdukId');
            $table->decimal('TelerAwal', 15, 2)->default(0);
            $table->decimal('TelerAkhir', 15, 2)->default(0);
            $table->decimal('Jumlah', 15, 2)->default(0);
            $table->decimal('JumlahRupiah', 18, 2)->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('SpbuId')->references('id')->on('spbu')->onDelete('cascade');
            $table->foreign('NozzelId')->references('id')->on('nozle')->onDelete('cascade');
            $table->foreign('PulauId')->references('id')->on('pulau')->onDelete('cascade');
            $table->foreign('ProdukId')->references('id')->on('produk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
