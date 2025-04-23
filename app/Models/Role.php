<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  protected $fillable = ['name'];

  //# Casting and Accessor
  public function givePermissionTo(array $permissions)
  {
    $this->permissions()->sync(array_map(fn($permission) => $permission->id, $permissions));
  }

  public function hasPermissionTo($permission): bool
  {
    return $this->permissions->contains('name', $permission);
  }

  //? Relationship
  public function permissions()
  {
    return $this->belongsToMany(Permission::class, 'role_has_permissions');
  }

  public function users()
  {
    return $this->belongsToMany(User::class, 'role_user');
  }
}
