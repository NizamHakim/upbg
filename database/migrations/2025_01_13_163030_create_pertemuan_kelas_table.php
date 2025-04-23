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
    Schema::create('pertemuan_kelas', function (Blueprint $table) {
      $table->id();
      $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
      $table->foreignId('ruangan_id')->constrained('ruangan')->cascadeOnDelete();
      $table->foreignId('pengajar_id')->nullable()->constrained('users')->nullOnDelete();
      $table->integer('pertemuan_ke');
      $table->date('tanggal');
      $table->time('waktu_mulai');
      $table->time('waktu_selesai');
      $table->boolean('terlaksana')->default(false);
      $table->text('topik')->nullable();
      $table->text('catatan')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('pertemuan_kelas');
  }
};
