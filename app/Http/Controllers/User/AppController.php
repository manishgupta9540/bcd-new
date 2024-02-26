<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDF;
use Mpdf\Mpdf;
use mikehaertl\pdftk\Pdf as PDFTK;
use Illuminate\Support\Facades\Auth;
use Mail;
use ZipArchive;


class AppController extends Controller
{
    //
    public function __construct()
    {
        ini_set('max_execution_time', '0');
    }
    
    public function downloadReportZip(Request $request)
    {
        $zip_id=base64_decode($request->zip_id);

        $zip_log=DB::table('zip_logs')->where(['id'=>$zip_id])->first();

        $today_timestamp=strtotime(date('Y-m-d h:i A'));

        $zip_date = date('Y-m-d h:i A',strtotime($zip_log->created_at.'+1 day'));

        $zip_timestamp = strtotime($zip_date);

        // dd($zip_date);

        if($today_timestamp > $zip_timestamp)
        {
            return redirect()->route('/user/error-404-file');
        }

        if($zip_log->zip_name!=NULL)
        {
            $file = public_path()."/zip/".$zip_log->zip_name;
            $headers = array('Content-Type: application/zip');
            return response()->download($file, $zip_log->zip_name,$headers);
        }
    }

    public function errorFile()
    {
        return view('main-web.error-404-file');
    }

    public function downloadguestReportZip(Request $request)
    {
        $zip_id=base64_decode($request->zip_id);

        $guest_v=DB::table('guest_verifications')->where(['id'=>$zip_id])->first();
        if($guest_v->zip_name!=NULL)
        {
            $file = public_path()."/guest/reports/zip/".$guest_v->zip_name;
            $headers = array('Content-Type: application/zip');
            return response()->download($file, $guest_v->zip_name,$headers);
        }
    }


    public function downloadguestInstantReportCheckPdf(Request $request)
    {
        $gcs_id=base64_decode($request->reports_id);
        $guest_cart_service=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id])->first();
        $users = DB::table('users')->where('business_id',$guest_cart_service->user_id)->first();
        $guest_user=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id])->first();
        //dd($users);
      
        // if($guest_cart_service->file_name!=NULL && File::exists(public_path()."/guest/reports/pdf/".$guest_cart_service->file_name))
        // {
        //     $file = public_path()."/guest/reports/pdf/".$guest_cart_service->file_name;
            
        //     $headers = array('Content-Type: application/pdf');

        //     $pdf = new PDFTK($file,[
        //         'command' => '/snap/pdftk/current/usr/bin/pdftk',
        //         'useExec' => true,
        //     ]);
            
        //     $password = mt_rand(100000, 999999);
        //     $password = str_pad($password, 6, 0, STR_PAD_LEFT);
        //     $userPassword = $password.'a';
           
        //     $result = $pdf->allow('AllFeatures')      // Change permissions        
        //                     ->setPassword($password)
        //                     ->setUserPassword($userPassword)          
        //                     ->passwordEncryption(128)   
        //                     ->saveAs($file);
        //     //dd($result);
        //     if ($result === false) {
        //         $error = $pdf->getError(); 
        //        // dd($error);
        //     }

        //     $name = $users->name;
        //     $email = $users->email;
        //     $key = $password;
        
        //     $msg = "Pdf password mail";
           
        //     $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'guest_user'=> $guest_user,'key'=>$key);
          
        //     Mail::send(['html'=>'mails.order-report-pdf-password'], $data, function($message) use($email,$name) {
        //         $message->to($email, $name)->subject
        //         ('myBCD System - Pdf Password Generated');
        //         $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
        //     });

        //     return response()->download($file, $guest_cart_service->file_name,$headers);
        // }
        // else
        // {
            //dd($users);
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

                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                                
                        $password = mt_rand(100000, 999999);
                        $password = str_pad($password, 6, 0, STR_PAD_LEFT);
                        $userPassword = $password.'a';
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)
                                        ->setUserPassword($userPassword)          
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        //dd($result);
                        if ($result === false) {
                            $error = $pdf->getError(); 
                            // dd($error);
                        }    

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
                        
                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                                
                        $password = mt_rand(100000, 999999);
                        $password = str_pad($password, 6, 0, STR_PAD_LEFT);
                        $userPassword = $password.'a';
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)
                                        ->setUserPassword($userPassword)          
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        //dd($result);
                        if ($result === false) {
                            $error = $pdf->getError(); 
                            // dd($error);
                        }    

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

                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                                
                        $password = mt_rand(100000, 999999);
                        $password = str_pad($password, 6, 0, STR_PAD_LEFT);
                        $userPassword = $password.'a';
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)
                                        ->setUserPassword($userPassword)          
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        //dd($result);
                        if ($result === false) {
                            $error = $pdf->getError(); 
                            // dd($error);
                        }    

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

                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                                
                        $password = mt_rand(100000, 999999);
                        $password = str_pad($password, 6, 0, STR_PAD_LEFT);
                        $userPassword = $password.'a';
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)
                                        ->setUserPassword($userPassword)          
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        //dd($result);
                        if ($result === false) {
                            $error = $pdf->getError(); 
                            // dd($error);
                        }    

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

                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                                
                        $password = mt_rand(100000, 999999);
                        $password = str_pad($password, 6, 0, STR_PAD_LEFT);
                        $userPassword = $password.'a';
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)
                                        ->setUserPassword($userPassword)          
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        //dd($result);
                        if ($result === false) {
                            $error = $pdf->getError(); 
                            // dd($error);
                        }    

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

                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                                
                        $password = mt_rand(100000, 999999);
                        $password = str_pad($password, 6, 0, STR_PAD_LEFT);
                        $userPassword = $password.'a';
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)
                                        ->setUserPassword($userPassword)          
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        //dd($result);
                        if ($result === false) {
                            $error = $pdf->getError(); 
                            // dd($error);
                        }    

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

                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                                
                        $password = mt_rand(100000, 999999);
                        $password = str_pad($password, 6, 0, STR_PAD_LEFT);
                        $userPassword = $password.'a';
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)
                                        ->setUserPassword($userPassword)          
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        //dd($result);
                        if ($result === false) {
                            $error = $pdf->getError(); 
                            // dd($error);
                        }    

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

                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                                
                        $password = mt_rand(100000, 999999);
                        $password = str_pad($password, 6, 0, STR_PAD_LEFT);
                        $userPassword = $password.'a';
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)
                                        ->setUserPassword($userPassword)          
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        //dd($result);
                        if ($result === false) {
                            $error = $pdf->getError(); 
                            // dd($error);
                        }    

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

                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                                
                        $password = mt_rand(100000, 999999);
                        $password = str_pad($password, 6, 0, STR_PAD_LEFT);
                        $userPassword = $password.'a';
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)
                                        ->setUserPassword($userPassword)          
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        //dd($result);
                        if ($result === false) {
                            $error = $pdf->getError(); 
                            // dd($error);
                        }    

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
                        
                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                                
                        $password = mt_rand(100000, 999999);
                        $password = str_pad($password, 6, 0, STR_PAD_LEFT);
                        $userPassword = $password.'a';
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)
                                        ->setUserPassword($userPassword)          
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        //dd($result);
                        if ($result === false) {
                            $error = $pdf->getError(); 
                            // dd($error);
                        }    

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

            $name = $users->name;
            $email = $users->email;
            $key = $password;
        
            $msg = "Pdf password mail";
            
            $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'guest_user'=> $guest_user,'key'=>$key);
            
            Mail::send(['html'=>'mails.order-report-pdf-password'], $data, function($message) use($email,$name) {
                $message->to($email, $name)->subject
                ('myBCD System - Pdf Password Generated');
                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
            });
            
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

        //}
    }

    public function downloadguestInstantReportZip(Request $request)
    {
        $zip_id=base64_decode($request->zip_id);

        $guest_master=DB::table('guest_instant_masters')->where(['id'=>$zip_id])->first();

        $users = DB::table('users')->where('business_id',$guest_master->user_id)->first();
        $guest_user=DB::table('guest_instant_masters')->where(['id'=>$zip_id])->first();
    
        // if($guest_master->zip_name!=NULL && File::exists(public_path()."/guest/reports/zip/".$guest_master->zip_name))
        // {
        //     $file = public_path()."/guest/reports/zip/".$guest_master->zip_name;

        //     $headers = array('Content-Type: application/zip');
        //     $zip = '';
        //     return response()->download($file, $guest_master->zip_name,$headers);
        // }
        // else
        // {

            $guest_cart_services=DB::table('guest_instant_cart_services')
                                            ->where(['giv_m_id'=>$guest_master->id])
                                            ->orderBy('service_id','asc')
                                            ->get();
            $password = mt_rand(100000,999999);
            $password = str_pad($password, 6, 0, STR_PAD_LEFT);
            $userPassword = $password.'a';
            // generate pdf report
            foreach($guest_cart_services as $gcs)
            {
                $data = NULL;
                $service = DB::table('services')->where(['id'=>$gcs->service_id])->first();
                $path=public_path().'/guest/reports/pdf/';
                $file_name = NULL;
                $arr_data = [];

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                
                if(stripos($service->name,'Aadhar')!==false)
                {

                    $file_name='aadhar-'.$gcs->id.date('Ymdhis').".pdf";

                    $service_data_array=json_decode($gcs->service_data,true);

                    $arr_data = $service_data_array['check'];
            
                    $aadhar_number = $arr_data['Aadhar Number'];

                    if(stripos($gcs->status,'success')!==false)
                    {
                        if($gcs->check_master_id!=NULL)
                        {
                            $data = DB::table('aadhar_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                        }
                        else
                        {

                            $data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$aadhar_number])->first();
                        }

                        $master_data = $data;
                       
                        $pdf = PDF::loadView('guest.instantverification.pdf.aadhar', compact('master_data'))
                        ->save($path.$file_name);

                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                       // dd($pdf);
                      
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)  
                                        ->setUserPassword($userPassword)       
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        //dd($result);
                        if ($result === false) {
                            $error = $pdf->getError(); 
                        }

                        DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
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
                    $file_name='pan-'.$gcs->id.date('Ymdhis').".pdf";

                    $service_data_array=json_decode($gcs->service_data,true);

                    $data = $service_data_array['check'];

                    $pan_number=$data['PAN Number'];

                    if(stripos($gcs->status,'success')!==false)
                    {
                        if($gcs->check_master_id!=NULL)
                        {
                            $data = DB::table('pan_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                        }
                        else
                        {

                            $data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
                        }

                        $master_data = $data;

                        $pdf = PDF::loadView('guest.instantverification.pdf.pan', compact('master_data'))
                            ->save($path.$file_name); 
                        
                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)  
                                        ->setUserPassword($userPassword)       
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        
                        if ($result === false) {
                            $error = $pdf->getError(); 
                        }

                        DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
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
                    $file_name='voter_id-'.$gcs->id.date('Ymdhis').".pdf";

                    $service_data_array=json_decode($gcs->service_data,true);

                    $data = $service_data_array['check'];

                    $voter_id_number=$data['Voter ID Number'];

                    if(stripos($gcs->status,'success')!==false)
                    {
                        if($gcs->check_master_id!=NULL)
                        {
                            $data = DB::table('voter_id_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                        }
                        else
                        {

                            $data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voter_id_number])->first();
                        }

                        $master_data = $data;

                        $pdf = PDF::loadView('guest.instantverification.pdf.voter-id', compact('master_data'))
                            ->save($path.$file_name); 
                        
                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)  
                                        ->setUserPassword($userPassword)       
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        
                        if ($result === false) {
                            $error = $pdf->getError(); 
                        }
    

                        DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
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
                    $file_name='rc-'.$gcs->id.date('Ymdhis').".pdf";

                    $service_data_array=json_decode($gcs->service_data,true);

                    $data = $service_data_array['check'];

                    $rc_number=$data['RC Number'];

                    if(stripos($gcs->status,'success')!==false)
                    {
                        if($gcs->check_master_id!=NULL)
                        {
                            $data = DB::table('rc_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                        }
                        else
                        {

                            $data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();
                        }

                        $master_data = $data;

                        $pdf = PDF::loadView('guest.instantverification.pdf.rc', compact('master_data'))
                            ->save($path.$file_name);
                            
                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)  
                                        ->setUserPassword($userPassword)       
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        
                        if ($result === false) {
                            $error = $pdf->getError(); 
                        }
        

                        DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
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
                    $file_name='passport-'.$gcs->id.date('Ymdhis').".pdf";

                    $service_data_array=json_decode($gcs->service_data,true);

                    $data = $service_data_array['check'];

                    $file_number=$data['File Number'];

                    $dob=$data['Date of Birth'];

                    if(stripos($gcs->status,'success')!==false)
                    {
                        if($gcs->check_master_id!=NULL)
                        {
                            $data = DB::table('passport_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                        }
                        else
                        {
                            $data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$file_number,'dob'=>$dob])->first();
                        }

                        $master_data = $data;

                        $pdf = PDF::loadView('guest.instantverification.pdf.passport', compact('master_data'))
                            ->save($path.$file_name); 
                           
                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)  
                                        ->setUserPassword($userPassword)       
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        
                        if ($result === false) {
                            $error = $pdf->getError(); 
                        }
        

                        DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
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
                    $file_name='dl-'.$gcs->id.date('Ymdhis').".pdf";

                    $service_data_array=json_decode($gcs->service_data,true);

                    $data = $service_data_array['check'];

                    $dl_number=$data['DL Number'];

                    if(stripos($gcs->status,'success')!==false)
                    {
                        if($gcs->check_master_id!=NULL)
                        {
                            $data = DB::table('dl_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                        }
                        else
                        {

                            $data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$dl_number])->first();
                        }

                        $master_data = $data;

                        $pdf = PDF::loadView('guest.instantverification.pdf.dl', compact('master_data'))
                            ->save($path.$file_name); 

                        $pdf = new PDFTK($path.$file_name,[
                            'command' => '/snap/pdftk/current/usr/bin/pdftk',
                            'useExec' => true,
                        ]);
                        
                        $result = $pdf->allow('AllFeatures')      // Change permissions        
                                        ->setPassword($password)  
                                        ->setUserPassword($userPassword)       
                                        ->passwordEncryption(128)   
                                        ->saveAs($path.$file_name);
                        
                        if ($result === false) {
                            $error = $pdf->getError(); 
                        }
    
                        DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
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
                    $file_name='bank-'.$gcs->id.date('Ymdhis').".pdf";

                    $service_data_array=json_decode($gcs->service_data,true);

                    $data = $service_data_array['check'];

                    $account_number=$data['Account Number'];

                    $ifsc_code=$data['IFSC Code'];

                    if(stripos($gcs->status,'success')!==false)
                    {
                        if($gcs->check_master_id!=NULL)
                        {
                            $data = DB::table('bank_account_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                        }
                        else
                        {
                            $data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_number])->first();
                        }

                        $master_data = $data;

                        $pdf = PDF::loadView('guest.instantverification.pdf.bank', compact('master_data'))
                            ->save($path.$file_name); 
                        
                            $pdf = new PDFTK($path.$file_name,[
                                'command' => '/snap/pdftk/current/usr/bin/pdftk',
                                'useExec' => true,
                            ]);
                            
                            $result = $pdf->allow('AllFeatures')      // Change permissions        
                                            ->setPassword($password)  
                                            ->setUserPassword($userPassword)       
                                            ->passwordEncryption(128)   
                                            ->saveAs($path.$file_name);
                            
                            if ($result === false) {
                                $error = $pdf->getError(); 
                            }
    

                        DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                            'check_master_id' => $master_data->id
                        ]);
                    }
                    else
                    {
                        $pdf = PDF::loadView('guest.instantverification.pdf.failed.bank', compact('account_number','ifsc_code'))
                                ->save($path.$file_name);

                    }
                }
                else if(stripos($service->type_name,'e_court')!==false)
                {
                    $file_name='e_court-'.$gcs->id.date('Ymdhis').".pdf";

                    $service_data_array=json_decode($gcs->service_data,true);

                    $data = $service_data_array['check'];

                    $name=$data['Name'];

                    $father_name=$data['Father Name'];

                    $address=$data['Address'];

                    if(stripos($gcs->status,'success')!==false)
                    {
                        if($gcs->check_master_id!=NULL)
                        {
                            $data = DB::table('e_court_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                        }
                        else
                        {

                            $data = DB::table('e_court_check_masters')->select('*')->where(['name'=>$name,'father_name'=>$father_name,'address'=>$address])->latest()->first();
                        }

                        $master_data = $data;

                        $pdf = PDF::loadView('guest.instantverification.pdf.e_court', compact('master_data'))
                            ->save($path.$file_name); 

                            $pdf = new PDFTK($path.$file_name,[
                                'command' => '/snap/pdftk/current/usr/bin/pdftk',
                                'useExec' => true,
                            ]);
                            
                            $result = $pdf->allow('AllFeatures')      // Change permissions        
                                            ->setPassword($password)  
                                            ->setUserPassword($userPassword)       
                                            ->passwordEncryption(128)   
                                            ->saveAs($path.$file_name);
                            
                            if ($result === false) {
                                $error = $pdf->getError(); 
                            }
    

                        DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
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
                    $file_name='upi-'.$gcs->id.date('Ymdhis').".pdf";

                    $service_data_array=json_decode($gcs->service_data,true);

                    $data = $service_data_array['check'];

                    $upi_id=$data['UPI ID'];

                    if(stripos($gcs->status,'success')!==false)
                    {
                        if($gcs->check_master_id!=NULL)
                        {
                            $data = DB::table('upi_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                        }
                        else
                        {

                            $data = DB::table('upi_check_masters')->select('*')->where(['upi_id'=>$upi_id])->first();
                        }

                        $master_data = $data;

                        $pdf = PDF::loadView('guest.instantverification.pdf.upi', compact('master_data'))
                            ->save($path.$file_name); 

                            $pdf = new PDFTK($path.$file_name,[
                                'command' => '/snap/pdftk/current/usr/bin/pdftk',
                                'useExec' => true,
                            ]);
                            
                            $result = $pdf->allow('AllFeatures')      // Change permissions        
                                            ->setPassword($password)  
                                            ->setUserPassword($userPassword)       
                                            ->passwordEncryption(128)   
                                            ->saveAs($path.$file_name);
                            
                            if ($result === false) {
                                $error = $pdf->getError(); 
                            }
    

                        DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
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
                    $file_name='cin-'.$gcs->id.date('Ymdhis').".pdf";

                    $service_data_array=json_decode($gcs->service_data,true);

                    $data = $service_data_array['check'];

                    $cin=$data['CIN Number'];

                    if(stripos($gcs->status,'success')!==false)
                    {
                        if($gcs->check_master_id!=NULL)
                        {
                            $data = DB::table('cin_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                        }
                        else
                        {

                            $data = DB::table('cin_check_masters')->select('*')->where(['cin_number'=>$cin])->latest()->first();
                        }

                        $master_data = $data;

                        $pdf = PDF::loadView('guest.instantverification.pdf.cin', compact('master_data'))
                            ->save($path.$file_name); 

                            $pdf = new PDFTK($path.$file_name,[
                                'command' => '/snap/pdftk/current/usr/bin/pdftk',
                                'useExec' => true,
                            ]);
                            
                            $result = $pdf->allow('AllFeatures')      // Change permissions        
                                            ->setPassword($password)  
                                            ->setUserPassword($userPassword)       
                                            ->passwordEncryption(128)   
                                            ->saveAs($path.$file_name);
                            
                            if ($result === false) {
                                $error = $pdf->getError(); 
                            }
    
                        DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                            'check_master_id' => $master_data->id
                        ]);
                    }
                    else
                    {
                        $pdf = PDF::loadView('guest.instantverification.pdf.failed.cin', compact('cin'))
                                ->save($path.$file_name);

                    }
                }

                DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                    'file_name' => $file_name
                ]);
            }

            // generating the service_wise zip
            $guest_cart=DB::table('guest_instant_carts')
                                ->where(['giv_m_id'=>$guest_master->id])
                                ->orderBy('service_id','asc')
                                ->get();       

            foreach($guest_cart as $gc)
            {
                $guest_cart_services=DB::table('guest_instant_cart_services')
                                    ->where(['giv_c_id'=>$gc->id])
                                    ->get();

                $zipname="";
                $services=DB::table('services')->where('id',$gc->service_id)->first();

                if($services->name=='Aadhar')
                {
                    $zipname = 'aadhar-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/aadhar/';
                }
                else if($gc->service_id==3)
                {
                    $zipname = 'pan-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/pan/';
                }
                else if($services->name=='Voter ID')
                {
                    $zipname = 'voter_id-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/voterid/';
                }
                else if($services->name=='RC')
                {
                    $zipname = 'rc-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/rc/';
                }
                else if($services->name=='Passport')
                {
                    $zipname = 'passport-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/passport/';
                }
                else if($services->name=='Driving')
                {
                    $zipname = 'driving-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/driving/';
                }
                else if($services->name=='Bank Verification')
                {
                    $zipname = 'bank-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/bank/';
                }
                else if(stripos($services->name,'E-Court')!==false)
                {
                    $zipname = 'e_court-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/e-court/';
                }
                else if(stripos($services->type_name,'upi')!==false)
                {
                    $zipname = 'upi-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/upi/';
                }
                else if(stripos($services->type_name,'cin')!==false)
                {
                    $zipname = 'cin-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/cin/';
                }

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                $zip = new \ZipArchive();
                $zip->open($path.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

               
                foreach($guest_cart_services as $gcs)
                {   
                    $path = public_path()."/guest/reports/pdf/".$gcs->file_name;
                    $zip->addFile($path, '/reports/'.basename($path));
                }
               
                $zip->close();
               
                DB::table('guest_instant_carts')->where(['id'=>$gc->id])->update([
                    'zip_name' => $zipname!=""?$zipname:NULL,
                    
                ]);

            }

           

            //generating the master zip

            $zipname="";
            $path=''; 
            $zipname = 'reports-'.date('Ymdhis').'.zip';
            $zip = new \ZipArchive();      
            $zip->open(public_path().'/guest/reports/zip/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            
            
            $guest_cart=DB::table('guest_instant_carts')
                                ->where(['giv_m_id'=>$guest_master->id])
                                ->orderBy('service_id','asc')
                                ->get();

            foreach($guest_cart as $gc)
            {
                $services=DB::table('services')->where('id',$gc->service_id)->first();
                if($services->name=='Aadhar')
                {
                    $path = public_path().'/guest/reports/zip/aadhar/';
                }
                else if($gc->service_id==3)
                {
                    $path = public_path().'/guest/reports/zip/pan/';
                }
                else if($services->name=='Voter ID')
                {
                    $path = public_path().'/guest/reports/zip/voterid/';
                }
                else if($services->name=='RC')
                {
                    $path = public_path().'/guest/reports/zip/rc/';
                }
                else if($services->name=='Passport')
                {
                    $path = public_path().'/guest/reports/zip/passport/';
                }
                else if($services->name=='Driving')
                {
                    $path = public_path().'/guest/reports/zip/driving/';
                }
                else if($services->name=='Bank Verification')
                {
                    $path = public_path().'/guest/reports/zip/bank/';
                }
                else if(stripos($services->name,'E-Court')!==false)
                {
                    $path = public_path().'/guest/reports/zip/e-court/';
                }
                else if(stripos($services->type_name,'upi')!==false)
                {
                    $path = public_path().'/guest/reports/zip/upi/';
                }
                else if(stripos($services->type_name,'cin')!==false)
                {
                    $path = public_path().'/guest/reports/zip/cin/';
                }

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                $zip->addFile($path.$gc->zip_name, '/reports/'.basename($path.$gc->zip_name)); 
               
               // $zip->setEncryptionName('/reports/'.basename($path.$gc->zip_name), ZipArchive::EM_AES_256);
            }

            // $password = mt_rand(100000,999999);
            // $key = str_pad($password, 6, 0, STR_PAD_LEFT);
            // $zip->setPassword($key);
            $zip->close();

            DB::table('guest_instant_masters')->where(['id'=> $guest_master->id])->update([
                'zip_name' => $zipname!=""?$zipname:NULL,
            ]);

            $guest_master = DB::table('guest_instant_masters')->where(['id'=>$zip_id])->first();

            $file = public_path()."/guest/reports/zip/".$guest_master->zip_name;

            $headers = array('Content-Type: application/zip');
           
            $name = $users->name;
            $email = $users->email;

            $key = $password;
        
            $msg = "ZIP password mail";
           
            $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'guest_user'=> $guest_user,'key'=>$key);
          
            Mail::send(['html'=>'mails.order-report-zip-password'], $data, function($message) use($email,$name) {
                $message->to($email, $name)->subject
                ('myBCD System - ZIP Password Generated');
                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
            });

            return response()->download($file, $guest_master->zip_name,$headers);

           
        // }
    }

    public function downloadInsuffReport(Request $request)
    {
        $id=base64_decode($request->id);

        $insuff_log=DB::table('notification_logs')->where(['id'=>$id])->first();
        // dd($zip_date);
       
        if($insuff_log->file_name!=NULL)
        {
            $file = public_path()."/uploads/insuff-notify/".$insuff_log->file_name;
            $headers = array('Content-Type: application/pdf');
            return response()->download($file, $insuff_log->file_name,$headers);
        }
    }

    public function downloadBillingInvoice(Request $request)
    {
        
        $billing_id=base64_decode($request->id);

        $bill=DB::table('billings')->where('id',$billing_id)->first();

        $file_name='';

        $business_id=$bill->parent_id;

        $customers=DB::table('users as u')
                ->select('u.*','ub.company_name','ub.gst_number','ub.tin_number','ub.address_line1 as business_address','ub.city_name','ub.state_name','ub.country_name','ub.zipcode','ub.phone as business_phone','ub.website','ub.email as business_email')
                ->join('user_businesses as ub','ub.business_id','=','u.id')
                ->where('u.id',$business_id)
                ->first();


        // $items=DB::table('billing_items as bi')
        //     ->select('bi.*','s.verification_type')
        //     ->join('services as s','s.id','=','bi.service_id')
        //     ->where(['bi.billing_id'=>$billing_id])
        //     ->orderBy('bi.service_id','asc')
        //     ->get();

        $billing_detail_candidate=DB::table('billing_items as bi')
        ->DISTINCT('bi.candidate_id')
        ->select('bi.candidate_id','bi.business_id',DB::raw('sum(bi.quantity) as total_quantity'),DB::raw('sum(bi.additional_charges) as total_additional_charges'),DB::raw('sum(bi.final_total_check_price) as total_check_price'))
        ->groupBy('bi.candidate_id')
        ->where(['bi.billing_id'=>$billing_id])
        ->whereNotNull('bi.candidate_id');

        $billing_detail_candidate=$billing_detail_candidate->get();

        $billing_detail_api = DB::table('billing_items as bi')
        ->select('bi.business_id',DB::raw('sum(bi.quantity) as total_quantity'),DB::raw('sum(bi.additional_charges) as total_additional_charges'),DB::raw('sum(bi.final_total_check_price) as total_check_price'))
        ->groupBy('bi.candidate_id')
        ->where(['bi.billing_id'=>$billing_id])
        ->whereNull('bi.candidate_id');

        $billing_detail_api=$billing_detail_api->get();

        $pdf = PDF::loadView('admin.billing.pdf.invoice', compact('billing_detail_candidate','billing_detail_api','bill','customers') );

        $file_name=$bill->invoice_id.date('Ymdhis').'.pdf';

        // return $pdf->stream($file_name);

        return $pdf->download($file_name);
    }

    public function shortenLink(Request $request)
    {
        $code = $request->code;

        $find = DB::table('short_links')->where('code',$code)->latest()->first();

        return redirect($find->link);
    }

    public function downloadReportShare(Request $request)
    {
        $id=base64_decode($request->id);

        $report_log=DB::table('notification_logs')->where(['id'=>$id])->first();
        // dd($zip_date);
       
        if($report_log->file_name!=NULL)
        {
            $file = public_path().'/uploads/report-share-data/'.$report_log->business_id.'/'.$report_log->file_name;
            $headers = array('Content-Type: application/zip');
            return response()->download($file, $report_log->file_name,$headers);
        }
    }


    public function downloadProgressExcel(Request $request)
    {
        $id = base64_decode($request->id);

        $progress_log=DB::table('notification_logs')->where(['id'=>$id])->first();

        if($progress_log!=NULL && $progress_log->file_name!=NULL)
        {
            $file = public_path().'/uploads/progress-export/'.$progress_log->file_name;
            $headers = array('Content-Type: application/vnd.ms-excel');
            return response()->download($file, $progress_log->file_name,$headers);
        }
    }
}
