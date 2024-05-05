<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tenants';

    public function users() {
        return $this->hasMany(User::class);
    }

    public function locations() {
        return $this->hasMany(Location::class);
    }

    public function events() {
        return $this->hasMany(Event::class);
    }

    public function permissionroles() {
        return $this->hasMany(PermissionRole::class);
    }
}
