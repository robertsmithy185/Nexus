<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaUkmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota_ukm', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('nim', 20); // Foreign key dari tabel mahasiswa
            $table->string('kode_ukm', 10); // Foreign key dari tabel ukm
            $table->timestamps();
            
            // Definisi foreign key
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('kode_ukm')->references('kode')->on('ukm')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggota_ukm');
    }
}
