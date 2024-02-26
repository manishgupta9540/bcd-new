<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class KeyAccountManager extends Model
{
     /*** The attributes that are mass assignable.** @var array*/

     protected $table = 'key_account_managers';
     
     protected $fillable = ['customer_id', 'business_id', 'user_id','status','is_primary']; 

}
