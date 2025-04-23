<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('peserta_tes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('tes_id')->constrained('tes')->cascadeOnDelete();
      $table->foreignId('peserta_id')->constrained('peserta')->cascadeOnDelete();
      $table->foreignId('ruangan_id')->nullable()->constrained('ruangan')->cascadeOnDelete();
      $table->boolean('hadir')->default(false);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('peserta_tes');
  }
};
