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
    Schema::create('ruangan_tes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('tes_id')->constrained('tes')->cascadeOnDelete();
      $table->foreignId('ruangan_id')->constrained('ruangan')->cascadeOnDelete();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('ruangan_tes');
  }
};
