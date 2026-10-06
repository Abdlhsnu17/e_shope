<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique();
            $table->string('nisn')->nullable();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('jenis_kelamin')->default('L');
            $table->string('kelas')->nullable();
            $table->string('jurusan')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('telepon_wali')->nullable();
            $table->string('status')->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
