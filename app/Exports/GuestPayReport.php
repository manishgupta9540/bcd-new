<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use DB;

class GuestPayReport implements FromCollection, WithMapping,WithHeadings,ShouldAutoSize,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = DB::table('guest_instant_masters')->where('is_payment_done',1)->get();

        return $query;
    }

    public function map($query): array
    {
        $data = [
            $query->name,
            $query->email,
            $query->contactNumber,
            $query->order_id,
            $query->transaction_id,
            $query->payment_id,
            $query->total_price,
            $query->created_at,
            $query->updated_at,
        ];

        return $data;
    }

    public function headings(): array
    {
        $columns = ['Name','Email','Contact Number','Order ID','Transaction ID','Payment ID','Total Price','Created At','Updated At'];

        return $columns;
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }
}
