<?php

namespace App\Imports;

use App\Models\Admin\CandidateReinitiate;
use Maatwebsite\Excel\Concerns\ToModel;


class FrnidImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new CandidateReinitiate([
            'frnid' => $value[0],
            'name'  => $value[1],
            'display_id'   =>$value[2],
        ]);
    }
}
