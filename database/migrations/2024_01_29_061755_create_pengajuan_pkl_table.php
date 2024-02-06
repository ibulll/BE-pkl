<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanPklTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_pkl', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nis');
            $table->string('cv');
            $table->string('portofolio');
            $table->string('email');
            $table->text('alamat');
            $table->binary('file_cv')->nullable();
            $table->binary('file_portofolio')->nullable();
            $table->enum('status', ['Diperiksa', 'Diproses', 'Diterima', 'Ditolak'])
            ->default('Diperiksa');
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
        Schema::dropIfExists('pengajuan_pkl');
    }
}
