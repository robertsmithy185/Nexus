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
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->string('foto_profil')->nullable()->after('password'); 
            // 'nullable()' agar kolom bisa bernilai NULL
            // 'after()' untuk menentukan posisi kolom baru setelah kolom 'password'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
        });
    }
};
