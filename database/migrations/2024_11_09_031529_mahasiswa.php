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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim')->primary(); // Menjadikan 'nim' sebagai primary key
            $table->string('name');
            $table->string('prodi');
            $table->string('password'); // Password yang sudah di-hash
            $table->enum('role', ['mahasiswa', 'pengurus', 'admin']); // Role pengguna
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
