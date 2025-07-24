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
       Schema::create('nozle', function (Blueprint $table) {
            $table->id();
            $table->string('NamaNozle');
            $table->unsignedBigInteger('PulauId');
            $table->unsignedBigInteger('SpbuId');
            $table->timestamps();

            $table->foreign('PulauId')->references('id')->on('pulau')->onDelete('cascade');
            $table->foreign('SpbuId')->references('id')->on('spbu')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nozles');
    }
};
