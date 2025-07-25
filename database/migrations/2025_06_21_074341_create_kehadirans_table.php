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
    Schema::create('kehadiran', function (Blueprint $table) {
        $table->id();
        $table->foreignId('KaryawanId')->constrained('karyawan')->onDelete('cascade');
        $table->timestamp('WaktuMasuk')->nullable();
        $table->timestamp('WaktuPulang')->nullable();
        $table->unsignedBigInteger('SpbuId')->nullable();
        $table->timestamps();

        $table->foreign('SpbuId')->references('id')->on('spbu')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran');
    }
};
