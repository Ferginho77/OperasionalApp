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
        Schema::create('Tangki', function (Blueprint $table) {
            $table->id();
            $table->string('Produk')->nullable(); // Nomor unik untuk setiap tangki
            $table->string('Ukuran')->nullable(); // Nomor unik untuk setiap tangki
            $table->string('JumlahDispenser')->nullable(); // Nomor unik untuk setiap tangki
            $table->string('Pulau')->nullable(); // Nomor unik untuk setiap tangki
            $table->foreignId('SpbuId')->references('id')->on('spbu')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Tangki');
    }
};
