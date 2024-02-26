<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillingItem extends Model
{
    //
    public function billing()
    {
        return $this->belongsTo('App\Billing','billing_id');
    }
}
