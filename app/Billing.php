<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    //
    public function billingitems()
    {
        return $this->hasMany('App\BillingItem','billing_id');
    }
}
