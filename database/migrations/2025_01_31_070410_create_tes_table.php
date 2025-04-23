<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('tes', function (Blueprint $table) {
      $table->id();
      $table->string('kode')->unique();
      $table->foreignId('tipe_id')->constrained('tipe_tes')->cascadeOnDelete();
      $table->string('nama');
      $table->date('tanggal');
      $table->time('waktu_mulai');
      $table->time('waktu_selesai');
      $table->boolean('terlaksana')->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tes');
  }
};
