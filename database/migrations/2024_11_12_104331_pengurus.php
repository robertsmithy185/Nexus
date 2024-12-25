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
        Schema::create('pengurus', function (Blueprint $table) {
            $table->string('nim_pengurus')->primary(); // Menjadikan nim_pengurus sebagai primary key
            $table->string('name'); // Kolom Nama Pengurus
            $table->string('password'); // Kolom Password
            $table->string('kode_ukm'); // Kolom Kode UKM
            $table->enum('role', ['mahasiswa', 'pengurus', 'admin']); // Role pengguna
            $table->timestamps();
            
            // Foreign key nim_pengurus ke nim mahasiswa
            $table->foreign('nim_pengurus')->references('nim')->on('mahasiswa')
                ->onDelete('cascade');

            // Foreign key kode_ukm ke kode ukm
            $table->foreign('kode_ukm')->references('kode')->on('ukm')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengurus');
    }
};

