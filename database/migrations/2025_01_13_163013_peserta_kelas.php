<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('peserta_kelas', function (Blueprint $table) {
      $table->id();
      $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
      $table->foreignId('peserta_id')->constrained('peserta')->cascadeOnDelete();
      $table->boolean('aktif')->default(true);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('peserta_kelas');
  }
};
