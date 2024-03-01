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
            $table->unsignedBigInteger('user_id'); // Menambahkan kolom user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('cv')->nullable();
            $table->string('portofolio')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->string('email_perusahaan')->nullable();
            $table->string('alamat_perusahaan')->nullable();
            $table->unsignedBigInteger('perusahaan_id')->nullable();
            $table->foreign('perusahaan_id')->references('id')->on('perusahaan');
            $table->binary('file_cv')->nullable();
            $table->binary('file_portofolio')->nullable();
            $table->enum('status', ['Diperiksa', 'Diproses', 'Diterima', 'Ditolak']);
            $table->string('group_id')->nullable();
            $table->unsignedBigInteger('pembimbing_id_1')->nullable();
            $table->foreign('pembimbing_id_1')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('pembimbing_id_2')->nullable();
            $table->foreign('pembimbing_id_2')->references('id')->on('users')->onDelete('set null');
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
