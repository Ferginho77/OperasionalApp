<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('BackupSession', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('AbsensiId');
        $table->unsignedBigInteger('BackupOperatorId');
        $table->time('JamMulai')->nullable();
        $table->time('JamSelesai')->nullable();
        $table->decimal('TotalizerAwal', 10, 2)->nullable();
        $table->decimal('TotalizerAkhir', 10, 2)->nullable();
        $table->timestamps();

        $table->foreign('AbsensiId')->references('id')->on('absensi')->onDelete('cascade');
        $table->foreign('BackupOperatorId')->references('id')->on('karyawan');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('BackupSession');
    }
};
