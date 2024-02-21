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
            $table->string('nama');
            $table->string('nisn');
            $table->enum('kelas', ['XII PPLG 1', 'XII PPLG 2', 'XII PPLG 3']);
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
            $table->unsignedBigInteger('group_id')->nullable(); // Menggunakan tipe data yang sesuai
            $table->foreign('group_id')->references('id')->on('group')->onDelete('cascade');
            $table->unsignedBigInteger('pembimbing_id')->nullable();
            $table->foreign('pembimbing_id')->references('id')->on('pembimbing')->onDelete('set null');
            $table->timestamps();

            // Menambahkan indeks untuk kolom user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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