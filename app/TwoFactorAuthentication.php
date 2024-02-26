<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwoFactorAuthentication extends Model
{
    //
    protected $table = '2_factor_authentications';

    protected $guarded = [];
}
