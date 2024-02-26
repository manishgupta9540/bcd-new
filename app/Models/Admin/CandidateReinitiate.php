<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class CandidateReinitiate extends Authenticatable
{
    use Notifiable;
    use HasRoles; 

    protected $guard = 'candidate';
    //
    protected $guarded = [];

    protected $table = 'candidate_reinitiates';
}
