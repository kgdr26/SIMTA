<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisSidangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_sidang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_BeritaAcara');
            $table->unsignedBigInteger('id_InformasiSidang');
            $table->string('Nama_sidang');
            $table->softDeletes()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenis_sidang');
    }
}
