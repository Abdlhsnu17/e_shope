<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Index untuk kolom yang dipakai menyaring dan mengurut daftar.
 *
 * Sebelumnya hanya ada unique index pada NIS/NIP/nomor/slug, sehingga setiap
 * penyaringan status, kategori, atau kelas — dan setiap pengurutan menurut waktu
 * pada halaman berindeks — memaksa pemindaian seluruh tabel. Index gabungan
 * disusun mengikuti urutan pemakaian: kolom penyaring lebih dahulu, kolom
 * pengurut sesudahnya, agar satu index melayani filter dan urutan sekaligus.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'registrations_status_created_at_index');
            $table->index('created_at', 'registrations_created_at_index');
            $table->index('email', 'registrations_email_index');
            $table->index('nisn', 'registrations_nisn_index');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->index(['is_published', 'published_at'], 'announcements_published_index');
            $table->index(['kategori', 'is_published', 'published_at'], 'announcements_kategori_published_index');
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->index(['kategori', 'created_at'], 'galleries_kategori_created_at_index');
            $table->index('created_at', 'galleries_created_at_index');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->index(['status', 'nama'], 'students_status_nama_index');
            $table->index('kelas', 'students_kelas_index');
            $table->index('nisn', 'students_nisn_index');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->index(['status', 'nama'], 'teachers_status_nama_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index(['is_active', 'name'], 'users_is_active_name_index');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropIndex('registrations_status_created_at_index');
            $table->dropIndex('registrations_created_at_index');
            $table->dropIndex('registrations_email_index');
            $table->dropIndex('registrations_nisn_index');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndex('announcements_published_index');
            $table->dropIndex('announcements_kategori_published_index');
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->dropIndex('galleries_kategori_created_at_index');
            $table->dropIndex('galleries_created_at_index');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_status_nama_index');
            $table->dropIndex('students_kelas_index');
            $table->dropIndex('students_nisn_index');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropIndex('teachers_status_nama_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_is_active_name_index');
        });
    }
};
