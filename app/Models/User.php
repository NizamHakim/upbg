<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  protected $fillable = [
    'name',
    'nickname',
    'nik',
    'email',
    'password',
    'phone_number',
    'profile_picture',
    'current_role_id',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  //# Casting and Accessor
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  protected function profilePicture(): Attribute
  {
    return Attribute::make(
      get: function (mixed $value, array $attributes) {
        return $value ? asset('storage/' . $value) : 'https://eu.ui-avatars.com/api/?bold=true&color=0866b7&name=' . urlencode($attributes['name']);
      },
    );
  }

  //# Casting and Accessor
  public function textFormat(string $format)
  {
    switch ($format) {
      case 'option':
        return "{$this->name} ({$this->nik})";
      default:
        return $this->nama;
    }
  }

  public function scopeRole(Builder $query, string $role)
  {
    return $query->whereHas('roles', fn($query) => $query->where('name', $role));
  }

  public function assignRole($roles): void
  {
    $this->roles()->sync($roles);
  }

  //? Relationship
  public function roles()
  {
    return $this->belongsToMany(Role::class, 'role_user')->orderByPivot('role_id')->withTimestamps();
  }

  public function currentRole()
  {
    return $this->belongsTo(Role::class, 'current_role_id');
  }

  public function pengajarKelas()
  {
    return $this->belongsToMany(Kelas::class, 'pengajar_kelas', 'user_id', 'kelas_id')->withTimestamps();
  }

  public function pengajarPertemuan()
  {
    return $this->hasMany(PertemuanKelas::class, 'pengajar_id');
  }

  public function pengawasTes()
  {
    return $this->belongsToMany(Tes::class, 'pengawas_tes', 'user_id', 'tes_id')->withTimestamps();
  }
}
