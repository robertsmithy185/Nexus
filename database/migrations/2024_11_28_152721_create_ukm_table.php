<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function p(): void
    {
        Schema::create('ukm', function (Blueprint $table) {
            $table->string('kode')->primary(); // Menjadikan 'kode' sebagai primary key
            $table->string('name');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->string('link_pendaftaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ukm');
    }
};
