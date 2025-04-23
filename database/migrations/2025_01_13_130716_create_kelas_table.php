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
    Schema::create('kelas', function (Blueprint $table) {
      $table->id();
      $table->string('kode')->unique();
      $table->foreignId('program_id')->nullable()->constrained('program_kelas')->cascadeOnDelete();
      $table->foreignId('tipe_id')->nullable()->constrained('tipe_kelas')->cascadeOnDelete();
      $table->foreignId('level_id')->nullable()->constrained('level_kelas')->cascadeOnDelete();
      $table->foreignId('ruangan_id')->constrained('ruangan')->cascadeOnDelete();
      $table->string('nomor');
      $table->integer('banyak_pertemuan');
      $table->date('tanggal_mulai');
      $table->string('group_link')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('kelas');
  }
};
