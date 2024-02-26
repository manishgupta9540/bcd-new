<?php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;

class VendorTask extends Model
{
    protected $guarded = [];

    public function getRaiseInsuffAttribute()
    {
        
        $status = '';
        $vendor_insuff = Helper::checkVendorInsuff($this->candidate_id,$this->service_id,$this->no_of_verification);
       //dd($vendor_insuff);
        
        // if($vendor_insuff->status == 'cleared')
        // {
        //     $status;
        // }
        // elseif($vendor_insuff->status == 'raise')
        // {
        //     $status;
        // }
        // return $status;
        
        
        return $vendor_insuff;
    }

    public function getInOutStatusAttribute()
    {
        $in_out_tat = Helper::checkVendorTaskTat($this->id);

        return $in_out_tat;
    }

}
