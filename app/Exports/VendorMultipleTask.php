<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class VendorMultipleTask implements WithMultipleSheets
{

    protected $from_date;
    protected $to_date;
    protected $export_type;
    protected $task_id;
    protected $service_id;
    // protected $no_of_verifications;

    function __construct($from_date, $to_date,$service_id,$task_id,$export_type) {
        $this->from_date        = $from_date;
        $this->to_date          = $to_date;
        $this->service_id         = $service_id;
        $this->task_id     = $task_id;
        $this->export_type      = $export_type;
        
        // $this->no_of_verifications = $no_of_verifications;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->service_id as $key => $value) {

            $sheets[] = new VendorTaskExport( $this->from_date,$this->to_date ,$this->export_type ,$this->task_id,$value);
        } 
            // $sheets[] = new InvoicesPerMonthSheet($this->year, $month);
        
        return $sheets;
    }
}
