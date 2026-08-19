<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->string('nama_lengkap', 100);
            $table->string('email', 100)->unique();
            $table->string('telepon', 20)->nullable();
            $table->enum('role', ['Super Admin', 'Admin', 'Manager', 'Operasional'])->default('Operasional');
            $table->string('foto_profile', 255)->nullable();
            $table->datetime('last_login')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
