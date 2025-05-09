<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('roles', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->timestamps();
    });

    Schema::create('permissions', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->timestamps();
    });

    Schema::create('role_has_permissions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
      $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('role_has_permissions');
    Schema::dropIfExists('permissions');
    Schema::dropIfExists('roles');
  }
};
