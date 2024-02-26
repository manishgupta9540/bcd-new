<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = []; 
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contacts';
}
