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
    Schema::create('presensi_kelas', function (Blueprint $table) {
      $table->id();
      $table->foreignId('pertemuan_id')->constrained('pertemuan_kelas')->cascadeOnDelete();
      $table->foreignId('peserta_id')->constrained('peserta')->cascadeOnDelete();
      $table->boolean('hadir')->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('presensi_kelas');
  }
};
