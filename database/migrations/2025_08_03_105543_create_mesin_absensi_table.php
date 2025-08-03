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
        Schema::create('MesinAbsen', function (Blueprint $table) {
           $table->id();
            $table->string('id_mesin')->unique(); // ID unik untuk setiap SPBU
            $table->string('ip_address'); // IP address mesin
            $table->integer('port')->default(4370);
            $table->string('lokasi')->nullable(); // Contoh: "SPBU Cilegon"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('MesinAbsen');
    }
};
