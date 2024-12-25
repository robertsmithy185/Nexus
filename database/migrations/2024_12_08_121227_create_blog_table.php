<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('nim_pengurus'); // Foreign key
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('gambar')->nullable(); // URL atau path gambar
            $table->timestamps(); // Created_at dan updated_at

            // Definisi foreign key
            $table->foreign('nim_pengurus')
                ->references('nim_pengurus')
                ->on('pengurus')
                ->onDelete('cascade'); // Hapus blog jika pengurus dihapus
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog');
    }
}
