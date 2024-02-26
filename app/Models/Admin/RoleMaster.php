<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class RoleMaster extends Model
{
    public function permission()
    {
        return $this->hasOne(RolePermission::class,'role_id','id');
    }
}