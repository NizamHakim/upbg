<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('jadwal_kelas', function (Blueprint $table) {
      $table->id();
      $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
      $table->integer('hari');
      $table->time('waktu_mulai');
      $table->time('waktu_selesai');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('jadwal_kelas');
  }
};
