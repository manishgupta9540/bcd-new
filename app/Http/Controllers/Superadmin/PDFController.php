<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use DB;

class PDFController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function PDFgenerate($id)
    {
        $sla_data = DB::table('customer_sla')->where(['id'=>$id])->first();
        
        $sla_service_items = DB::table('customer_sla_items as cs')
                                ->select('cs.id','cs.service_id')
                                ->where(['cs.sla_id'=>$id])
                                ->get();

        $pdf = PDF::loadView('superadmin.settings.sla.pdf-jaf', compact('sla_service_items'));
  
        return $pdf->download('jaf.pdf');
    }
}