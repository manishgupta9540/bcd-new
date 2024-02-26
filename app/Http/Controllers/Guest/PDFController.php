<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Imagick;
use App\Traits\S3ConfigTrait;

class PDFController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', '0');
        ini_set("pcre.backtrack_limit", "80000000");
    }


    public function previewReport(Request $request)
    {
        $parent_id = Auth::user()->parent_id;
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;
        
        $gcs_id=base64_decode($request->report_id);
        // dd($gcs_id);
        $guest_cart_service=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id])->first();
    
        if($guest_cart_service->file_name!=NULL && File::exists(public_path()."/guest/reports/pdf/".$guest_cart_service->file_name))
        {
            $file = public_path()."/guest/reports/pdf/".$guest_cart_service->file_name;
            $headers = array('Content-Type: application/pdf');
            //return response()->download($file, $guest_cart_service->file_name,$headers);
            return response()->json([
                'success' => true,
                'url' => url('/').'/guest/reports/pdf/'.$guest_cart_service->file_name
              ]);

        }
        else
        {
            // generating pdf report
            $data = NULL;
            $service = DB::table('services')->where(['id'=>$guest_cart_service->service_id])->first();
            $path=public_path().'/guest/reports/pdf/';
            $file_name = NULL;
            $arr_data = [];

            if(!File::exists($path))
            {
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            if(stripos($service->name,'Aadhar')!==false)
            {

                $file_name='aadhar-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $arr_data = $service_data_array['check'];
        
                $aadhar_number = $arr_data['Aadhar Number'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('aadhar_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {
                        $data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$aadhar_number])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.aadhar', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.aadhar', compact('aadhar_number'))
                            ->save($path.$file_name);

                }

            }
            else if(stripos($service->type_name,'pan')!==false)
            {
                $file_name='pan-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $pan_number=$data['PAN Number'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('pan_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.pan', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.pan', compact('pan_number'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->type_name,'voter_id')!==false)
            {
                $file_name='voter_id-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $voter_id_number=$data['Voter ID Number'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('voter_id_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voter_id_number])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.voter-id', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.voter-id', compact('voter_id_number'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->type_name,'rc')!==false)
            {
                $file_name='rc-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $rc_number=$data['RC Number'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('rc_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.rc', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.rc', compact('rc_number'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->type_name,'passport')!==false)
            {
                $file_name='passport-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $file_number=$data['File Number'];

                $dob=$data['Date of Birth'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('passport_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$file_number,'dob'=>$dob])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.passport', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.passport', compact('file_number','dob'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->name,'Driving')!==false)
            {
                $file_name='dl-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $dl_number=$data['DL Number'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('dl_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$dl_number])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.dl', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.dl', compact('dl_number'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->name,'Bank Verification')!==false)
            {
                $file_name='bank-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $account_number=$data['Account Number'];

                $ifsc_code=$data['IFSC Code'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('bank_account_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_number])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.bank-verification', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.bank-verification', compact('account_number','ifsc_code'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->type_name,'e_court')!==false)
            {
                $file_name='e_court-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $name=$data['Name'];

                $father_name=$data['Father Name'];

                $address=$data['Address'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('e_court_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('e_court_check_masters')->select('*')->where(['name'=>$name,'father_name'=>$father_name,'address'=>$address])->latest()->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.e_court', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.e_court', compact('name','father_name','address'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->type_name,'upi')!==false)
            {
                $file_name='upi-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $upi_id=$data['UPI ID'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('upi_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('upi_check_masters')->select('*')->where(['upi_id'=>$upi_id])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.upi', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.upi', compact('upi_id'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->type_name,'cin')!==false)
            {
                $file_name='cin-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $cin=$data['CIN Number'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('cin_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('cin_check_masters')->select('*')->where(['cin_number'=>$cin])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.cin', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.cin', compact('cin'))
                            ->save($path.$file_name);

                }
            }

            DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                'file_name' => $file_name
            ]);

             // generating the service_wise zip

             $guest_cart=DB::table('guest_instant_carts')
             ->where(['giv_m_id'=>$guest_cart_service->giv_m_id])
             ->orderBy('service_id','asc')
             ->get();

             $zip_c = new \ZipArchive();

            foreach($guest_cart as $gc)
            {
                $guest_c_s=DB::table('guest_instant_cart_services')
                                ->where(['giv_c_id'=>$gc->id])
                                ->get();

                $zipname="";
                $path_zip = "";
                $services=DB::table('services')->where('id',$gc->service_id)->first();

                if($services->name=='Aadhar')
                {
                    $zipname = 'aadhar-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/aadhar/';
                }
                else if(stripos($services->type_name,'pan')!==false)
                {
                    $zipname = 'pan-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/pan/';
                }
                else if($services->name=='Voter ID')
                {
                    $zipname = 'voter_id-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/voterid/';
                }
                else if($services->name=='RC')
                {
                    $zipname = 'rc-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/rc/';
                }
                else if($services->name=='Passport')
                {
                    $zipname = 'passport-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/passport/';
                }
                else if($services->name=='Driving')
                {
                    $zipname = 'driving-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/driving/';
                }
                else if($services->name=='Bank Verification')
                {
                    $zipname = 'bank-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/bank/';
                }
                else if(stripos($services->name,'E-Court')!==false)
                {
                    $zipname = 'e_court-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/e-court/';
                }
                else if(stripos($services->type_name,'upi')!==false)
                {
                    $zipname = 'upi-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/upi/';
                }
                else if(stripos($services->type_name,'cin')!==false)
                {
                    $zipname = 'cin-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/cin/';
                }

                if(!File::exists($path_zip))
                {
                    File::makeDirectory($path_zip, $mode = 0777, true, true);
                }

                
                $zip_c->open($path_zip.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                foreach($guest_c_s as $gcs)
                {
                    $path_r = public_path()."/guest/reports/pdf/";
                    
                    $zip_c->addFile($path_r.$gcs->file_name, '/reports/'.basename($path_r.$gcs->file_name));  
                }

                $zip_c->close();

                DB::table('guest_instant_carts')->where(['id'=>$gc->id])->update([
                    'zip_name' => $zipname!=""?$zipname:NULL,
                ]);

            }

            //generating the master zip

            $zipname1="";
            $path_m=''; 
            $zipname1 = 'reports-'.date('Ymdhis').'.zip';
            $zip1 = new \ZipArchive();      
            $zip1->open(public_path().'/guest/reports/zip/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            if(!File::exists(public_path().'/guest/reports/zip/'))
            {
                File::makeDirectory(public_path().'/guest/reports/zip/', $mode = 0777, true, true);
            }

            $guest_cart=DB::table('guest_instant_carts')
                            ->where(['giv_m_id'=>$guest_cart_service->giv_m_id])
                            ->orderBy('service_id','asc')
                            ->get();

            foreach($guest_cart as $gc)
            {
                $services=DB::table('services')->where('id',$gc->service_id)->first();
                if($services->name=='Aadhar')
                {
                    $path_m = public_path().'/guest/reports/zip/aadhar/';
                }
                else if($gc->service_id==3)
                {
                    $path_m = public_path().'/guest/reports/zip/pan/';
                }
                else if($services->name=='Voter ID')
                {
                    $path_m = public_path().'/guest/reports/zip/voterid/';
                }
                else if($services->name=='RC')
                {
                    $path_m = public_path().'/guest/reports/zip/rc/';
                }
                else if($services->name=='Passport')
                {
                    $path_m = public_path().'/guest/reports/zip/passport/';
                }
                else if($services->name=='Driving')
                {
                    $path_m = public_path().'/guest/reports/zip/driving/';
                }
                else if($services->name=='Bank Verification')
                {
                    $path_m = public_path().'/guest/reports/zip/bank/';
                }
                else if(stripos($services->name,'E-Court')!==false)
                {
                    $path_m = public_path().'/guest/reports/zip/e-court/';
                }
                else if(stripos($services->type_name,'upi')!==false)
                {
                    $path_m = public_path().'/guest/reports/zip/upi/';
                }
                else if(stripos($services->type_name,'cin')!==false)
                {
                    $path_m = public_path().'/guest/reports/zip/cin/';
                }

                if(!File::exists($path_m))
                {
                    File::makeDirectory($path_m, $mode = 0777, true, true);
                }

                $zip1->addFile($path_m.$gc->zip_name, '/reports/'.basename($path_m.$gc->zip_name));  
            }

            $zip1->close();

            DB::table('guest_instant_masters')->where(['id'=> $guest_cart_service->giv_m_id])->update([
                'zip_name' => $zipname1!=""?$zipname1:NULL,
            ]);

            $guest_cart_service=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id])->first();

            $file = public_path()."/guest/reports/pdf/".$guest_cart_service->file_name;
            $headers = array('Content-Type: application/pdf');
            return response()->download($file, $guest_cart_service->file_name,$headers);

            // return response()->json([
            //     'success' => true,
            //     'url' => url('/').'/guest/reports/pdf/'.$file
            //   ]);

        }
    }
}
