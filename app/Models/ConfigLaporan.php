<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigLaporan extends Model
{
  protected $table = 'config_laporan';
  protected $fillable = ['group', 'data'];

  protected function casts(): array
  {
    return [
      'data' => 'array'
    ];
  }
}
