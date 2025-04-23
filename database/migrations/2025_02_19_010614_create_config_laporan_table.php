<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('config_laporan', function (Blueprint $table) {
      $table->id();
      $table->string('group');
      $table->json('data');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('config_laporan');
  }
};
