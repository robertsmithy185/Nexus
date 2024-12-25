<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informasi', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('nim_pengurus'); // Foreign Key
            $table->string('judul'); // Judul Informasi
            $table->text('informasi'); // Isi Informasi
            $table->timestamps(); // created_at and updated_at

            // Menambahkan Foreign Key Constraint
            $table->foreign('nim_pengurus')
                ->references('nim_pengurus')
                ->on('pengurus')
                ->onDelete('cascade') // Hapus informasi jika pengurus dihapus
                ->onUpdate('cascade'); // Perbarui informasi jika nim_pengurus berubah
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('informasi');
    }
}
