<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('npsn')->nullable();
            $table->string('jenjang')->nullable();
            $table->string('akreditasi')->nullable();
            $table->string('kepala_sekolah')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('sejarah')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_profiles');
    }
};
