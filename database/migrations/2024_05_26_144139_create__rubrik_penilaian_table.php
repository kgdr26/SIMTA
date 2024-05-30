<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRubrikPenilaianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rubrik_penilaian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dosen');
            $table->decimal('pembimbing1_nilaiSidang', 5, 2);
            $table->decimal('pembimbing1_nilaiBimbingan', 5, 2);
            $table->decimal('pembimbing2_nilaiSidang', 5, 2);
            $table->decimal('pembimbing2_nilaiBimbingan', 5, 2);
            $table->decimal('nilai_penguji1', 5, 2);
            $table->decimal('nilai_penguji2', 5, 2);
            $table->decimal('nilai_penguji3', 5, 2);
            $table->decimal('bobot', 5, 2);
            $table->decimal('nilaiDGNBobot', 5, 2);
            $table->decimal('totalNilaiAkhir', 5, 2);
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
        Schema::dropIfExists('rubrik_penilaian');
    }
}
