<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pendaftaran')->unique();
            $table->string('nama_lengkap');
            $table->string('nisn')->nullable();
            $table->string('jenis_kelamin')->default('L');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('jurusan_pilihan')->nullable();
            $table->string('email');
            $table->string('telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('telepon_wali')->nullable();
            $table->string('status')->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
