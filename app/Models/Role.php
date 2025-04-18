<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }
}
