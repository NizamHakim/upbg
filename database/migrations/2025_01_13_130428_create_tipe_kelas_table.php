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
    Schema::create('tipe_kelas', function (Blueprint $table) {
      $table->id();
      $table->string('nama');
      $table->string('kode');
      $table->foreignId('kategori_id')->nullable()->constrained('kategori_kelas')->nullOnDelete();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tipe_kelas');
  }
};
