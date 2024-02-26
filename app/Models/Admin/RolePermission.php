<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    
    public function action()
    {
        return $this->belongsToMany(ActionMaster::class,'permission_id');
    }
}
