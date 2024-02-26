<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    protected $guarded = [];

    //protected $appends = ['is_insuff'];

    public function getInsuffAttribute()
    {
        $jaf_insuff = Helper::checkJafInsuff($this->candidate_id,$this->service_id,$this->number_of_verifications);

        return $jaf_insuff!=NULL ? 1 : 0;
    }

    public function getInOutStatusAttribute()
    {
        // 0 for no result, 1 for in TAT, 2 for Out TAT

        $in_out_tat = Helper::checkTaskTat($this->id);

        return $in_out_tat;
    }

    
}
