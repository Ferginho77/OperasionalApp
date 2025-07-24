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
        Schema::create('jadwaloperator', function (Blueprint $table) {
                $table->id();
               $table->unsignedBigInteger('KaryawanId');
                $table->foreign('KaryawanId')->references('id')->on('karyawan')->onDelete('cascade');
                $table->date('Tanggal');
                $table->enum('Shift', ['Pagi', 'Sore', 'Malam']); // Shift tambahan
                $table->string('NomorSPBU'); // referensi SPBU
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwaloperator');
    }
};
