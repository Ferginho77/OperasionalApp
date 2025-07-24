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
    Schema::create('absensi', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('KaryawanId');
        $table->date('Tanggal');
        $table->time('JamMasuk')->nullable();
        $table->time('JamIstirahatMulai')->nullable();
        $table->time('JamIstirahatKembali')->nullable();
        $table->time('JamPulang')->nullable();
        
        $table->unsignedBigInteger('NozleId')->nullable();
        $table->unsignedBigInteger('ProdukId')->nullable();
        $table->string('Pulau')->nullable();
        $table->decimal('TotalizerAwal', 10, 2)->nullable();
        $table->decimal('TotalizerAkhir', 10, 2)->nullable();

        $table->timestamps();

        $table->foreign('KaryawanId')->references('id')->on('karyawan')->onDelete('cascade');
        $table->foreign('NozleId')->references('id')->on('nozle')->onDelete('set null');
        $table->foreign('ProdukId')->references('id')->on('produk')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
