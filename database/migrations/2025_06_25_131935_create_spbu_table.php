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
        Schema::create('spbu', function (Blueprint $table) {
            $table->id();
            $table->string("NamaSPBU");
            $table->string("NomorSPBU")->unique();
            $table->string("Alamat");
            $table->foreignId('UserId')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spbu');
    }
};
