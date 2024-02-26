<?php


namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

trait CommonTrait
{
    static function workingDays($start_date,$tatwDays,$inc_tatwDays)
    {
        // using + weekdays excludes weekends
        $arr=[];
        $tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$tatwDays} weekdays"));

        $inc_tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$inc_tatwDays} weekdays"));

        $arr=['tat_date'=>$tat_new_date,'inc_tat_date'=>$inc_tat_new_date];

        return $arr;
        
    }

    static function calenderDays($start_date,$holidays,$tatwDays,$inc_tatwDays)
    {
        // using + weekdays excludes weekends
        // $arr=[];
        $tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$tatwDays} weekdays"));

        $inc_tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$inc_tatwDays} weekdays"));

        foreach($holidays as $holiday)
        {
        
            $holiday_ts = strtotime($holiday->date);

            // if holiday falls between start date and new date, then account for it
            if ($holiday_ts >= strtotime($start_date) && $holiday_ts <= strtotime($tat_new_date)) {

                // check if the holiday falls on a working day
                $h = date('w', $holiday_ts);
                    if ($h != 0 && $h != 6 ) {
                    // holiday falls on a working day, add an extra working day
                    $tat_new_date = date('Y-m-d', strtotime("{$tat_new_date} + 1 weekdays"));
                }
            }

            // if holiday falls between start date and new date, then account for it
            if ($holiday_ts >= strtotime($start_date) && $holiday_ts <= strtotime($inc_tat_new_date)) {

                // check if the holiday falls on a working day
                $h = date('w', $holiday_ts);
                    if ($h != 0 && $h != 6 ) {
                    // holiday falls on a working day, add an extra working day
                    $inc_tat_new_date = date('Y-m-d', strtotime("{$inc_tat_new_date} + 1 weekdays"));
                }
            }
        }

        return array('tat_date'=>$tat_new_date,'inc_tat_date'=>$inc_tat_new_date);
    }

    static function round_up ( $value, $precision )
    {
        $pow = pow ( 10, $precision ); 
        return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow;
    }
}
