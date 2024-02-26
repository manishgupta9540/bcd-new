<?php
namespace App;

use App\Models\Admin\RoleMaster;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles; 
    
    /*** The attributes that are mass assignable.** @var array*/

    // protected $fillable = ['name','phone', 'business_id', 'email', 'password','user_type','customer_id','company_logo','first_name','last_name','father_name','parent_id','country_id','client_emp_code','entity_code','gender','dob','role','email_verification_token' ,'email_verification_sent_at','status','created_by','is_deleted','deleted_at','deleted_by','phone_code','phone_iso']; 

     /*** The attributes that are not mass assignable.** @var array*/

     protected $guarded = [];

    /*** The attributes that should be hidden for arrays.** @var array*/

    protected $hidden = ['password', 'remember_token', ]; 

    /*** The attributes that should be cast to native types.** @var array*/

    protected $casts = ['email_verified_at' => 'datetime', ];

   

    /**
     * Role
     * */ 
    public function role()
    {
        return $this->belongsTo(RoleMaster::class,'id');
    }

}

