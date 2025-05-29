<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->belongsToMany(\App\Models\Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
