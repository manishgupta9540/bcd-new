<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\InsuffdataExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\S3ConfigTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
class InsuffController extends Controller
{
    //
    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id = Auth::user()->business_id;
        $rows=10;

        $raised_insuff=DB::table('jaf_form_data as jf')
                        ->select('jf.*',DB::raw('group_concat(DISTINCT jf.service_id) as services'),DB::raw('group_concat(DISTINCT jf.id) as jaf_id'),'u.display_id','u.email','u.phone','u.phone_code','u.phone_iso')
                        ->join('candidate_reinitiates as u','jf.candidate_id','=','u.id')
                        ->join('verification_insufficiency as v','v.jaf_form_data_id','=','jf.id')
                        ->join('job_items as j','jf.job_item_id','=','j.id')
                        ->where(['u.is_deleted' =>'0','j.jaf_status'=>'filled','u.business_id'=>$business_id])
                        ->whereIn('v.status',['raised','removed']);
                        if(is_numeric($request->get('candidate_id'))){
                            $raised_insuff->where('u.id',$request->get('candidate_id'));
                        }
                        // if($request->get('from_date') !=""){
                        //     $raised_insuff->whereDate('u.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                        //   }
                        //   if($request->get('to_date') !=""){
                        //     $raised_insuff->whereDate('u.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                        //   }
                        if($request->get('email')){
                            $raised_insuff->where('u.email',$request->get('email'));
                        }
                        if($request->get('mob')){
                        $raised_insuff->where('u.phone',$request->get('mob'));
                        }
                        if($request->get('ref')){
                        $raised_insuff->where('u.display_id',$request->get('ref'));
                        }
                        if($request->get('rows')!='') {
                            $rows = $request->get('rows');
                        }

         $raised_insuff = $raised_insuff->orderBy('jf.updated_at','desc')->groupBy('jf.candidate_id')->paginate($rows);

         $candidates = DB::table('candidate_reinitiates')->where(['user_type'=>'candidate','business_id'=>$business_id])->get();

         if($request->ajax())
            return view('clients.insuff.ajax',compact('raised_insuff','candidates'));
         else
            return view('clients.insuff.index',compact('raised_insuff','candidates'));
    }

      /**
     * set the session data
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function setSessionData(Request $request)
    {   
        //clear session data 
        Session()->forget('customer_id');
        Session()->forget('candidate_id');
        Session()->forget('to_date');
        Session()->forget('from_date');
        Session()->forget('check_id');

        Session()->forget('type');

        Session()->forget('export_service_id');
        Session()->forget('export_candidate_id');

        // Session()->forget('jaf_id');
        // Session()->forget('service_id');

        if( is_numeric($request->get('customer_id')) ){             
            session()->put('customer_id', $request->get('customer_id'));
        }
        if( is_numeric($request->get('candidate_id')) ){             
          session()->put('candidate_id', $request->get('candidate_id'));
        }
        // both date is selected 
        if($request->get('report_date') !="" && $request->get('to_date') !=""){
            session()->put('report_from_date', $request->get('report_date'));
            session()->put('report_to_date', $request->get('to_date'));
        }
        else
        {
          if($request->get('from_date') !=""){
            session()->put('from_date', $request->get('from_date'));
          }
          if($request->get('to_date') !=""){
            session()->put('to_date', $request->get('to_date'));
          }
        }
        //
        if($request->get('check_id') !=""){
          session()->put('check_id', $request->get('check_id'));
        }
        if($request->get('type') !=""){
            session()->put('type', $request->get('type'));
        }

        // if($request->get('jaf_id')!="")
        // {
        //   session()->put('jaf_id', $request->get('jaf_id'));
        // }

        // if($request->get('service_id')!="")
        // {
        //   session()->put('service_id', $request->get('service_id'));
        // }
        
        if($request->get('export_service_id'))
        {
            session()->put('export_service_id', $request->get('export_service_id'));
        }

        if($request->get('export_candidate_id'))
        {
            session()->put('export_candidate_id', $request->get('export_candidate_id'));
        }

        echo '1';
    }

    public function insuff_detail(Request $request)
    {
        $candidate_id=base64_decode($request->candidate_id);
        $service_id=base64_decode($request->service_id);
        $jaf_id=base64_decode($request->jaf_id);
        $service_name=$request->service_name;

        $form='';
        $service_name = '';
        $type=$request->type;
        if($type=='raised')
        {
            
            $ver_insuff=DB::table('verification_insufficiency as vi')
                            ->select('vi.*','s.name as service_name','s.verification_type','u.name as candidate_name','u.display_id')
                            ->join('candidate_reinitiates as u','u.id','=','vi.candidate_id')
                            ->join('services as s','s.id','=','vi.service_id')
                            ->where(['vi.jaf_form_data_id'=>$jaf_id,'vi.service_id'=>$service_id,'vi.status'=>'raised'])
                            ->orderBy('vi.id','desc')
                            ->first();
            if($ver_insuff->notes==NULL)
                $comments='N/A';
            else
                $comments=$ver_insuff->notes;

            if(stripos($ver_insuff->verification_type,'Manual')!==false)
            {
                $service_name = $ver_insuff->service_name.' - '.$ver_insuff->item_number;
            }
            else
            {
                $service_name = $ver_insuff->service_name;
            }

            $form.='<div class="form-group">
                        <label><strong>Candidate Name:</strong> '.$ver_insuff->candidate_name.' ('.$ver_insuff->display_id.')</label>
                    </div>';

            $form.='<div class="form-group">
                        <label for="label_name"> <strong>Comments:</strong> <span id="comments">'.$comments.'</span></label>
                    </div>';
            $insuff_attach=DB::table('insufficiency_attachments')->where(['jaf_form_data_id'=>$jaf_id,'service_id'=>$service_id,'status'=>'raise'])->get();
            if(count($insuff_attach)>0)
            {
                $s3_config = S3ConfigTrait::s3Config();
                $path=url('/').'/uploads/raise-insuff/';
                $form.='<div class="form-group">
                        <label><strong>Attachments: </strong></label>
                        <div class="row">';
                foreach($insuff_attach as $insuff)
                {
                    $img='';
                    if(stripos($insuff->file_platform,'s3')!==false)
                    {
                        $filePath = 'uploads/raise-insuff/';

                        $disk = Storage::disk('s3');

                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                            'Key'                        => $filePath.$insuff->file_name,
                            'ResponseContentDisposition' => 'attachment;'//for download
                        ]);

                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                        $file = (string)$req->getUri();
                    }
                    else
                    {
                        $file=$path.$insuff->file_name;
                    }

                    if(stripos($insuff->file_name, 'pdf')!==false) {
                        $img='<img src="'.url("/")."/admin/images/icon_pdf.png".'" title="'.$insuff->file_name.'" alt="preview" style="height:100px;"/>';
                    }
                    else
                    {
                        $img='<img src="'.$file.'" title="'.$insuff->file_name.'" alt="preview" style="height:100px;"/>';
                    }

                    $form.='<div class="col-4">
                            <div class="image-area" style="width:110px;">
                                '.$img.'
                            </div>
                            </div>';
                } 
                $form.='</div>
                        </div>';
            }

            $insuff_by = 'N/A';
            if($ver_insuff->updated_by!=NULL)
            {
                $user=DB::table('users')->where(['id'=>$ver_insuff->updated_by])->first();
                if($user!=NULL)
                    $insuff_by= $user->name;
            }
            else
            {
                $user=DB::table('users')->where(['id'=>$ver_insuff->created_by])->first();

                if($user!=NULL)
                    $insuff_by= $user->name;
            }

            if($ver_insuff->updated_at!=NULL)
            {
                $insuff_date=date('d-m-Y h:i A',strtotime($ver_insuff->updated_at));
            }
            else
            {
                $insuff_date=date('d-m-Y h:i A',strtotime($ver_insuff->created_at));
            }

            $form.='<div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label><strong>Raised By : </strong><span>'.$insuff_by.' </span</label>
                            </div>
                            <div class="col-6">
                                <label><strong>Raised Date & Time : </strong><span>'.$insuff_date.' </span</label>
                            </div>
                        </div>
                    </div>';
            
            $insuff_logs = DB::table('insufficiency_logs as i')
                            ->select('i.*','s.name as service_name','s.verification_type')
                            ->join('services as s','s.id','=','i.service_id')
                            ->where(['i.jaf_form_data_id'=>$jaf_id])
                            ->whereIn('i.status',['raised','failed'])
                            ->orderBy('i.id','desc')
                            ->get();

            if(count($insuff_logs)>0)
            {
                $form.='<h5 class="pt-2">Insufficieny Log Details</h5>
                        <p class="pb-border"></p>';

                $count = count($insuff_logs);

                $form.='<div class="insuff-data">';
                foreach($insuff_logs as $key => $insuff)
                {
                    $insuff_comment = 'N/A';
                    $form.='<div class="row">';

                    if($insuff->notes!=NULL)
                    {
                        $insuff_comment=$insuff->notes;
                    }

                    $insuff_by = 'N/A';

                    $user=DB::table('users')->where(['id'=>$insuff->created_by])->first();

                    if($user!=NULL)
                        $insuff_by= $user->name;

                    $form.='<div class="col-12">
                                <div class="form-group">
                                    <label for="label_name"> <strong>Comments:</strong> <span id="comments">'.$insuff_comment.'</span></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><strong>Raised By : </strong><span>'.$insuff_by.' </span</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><strong>Raised Date & Time : </strong><span>'.date('d-m-Y h:i A',strtotime($insuff->created_at)).' </span</label>
                                </div>
                            </div>';

                    $form.='</div>';

                    if($count!=++$key)
                    {
                        $form.='<p class="pb-border"></p>';
                    }
                }

                $form.='</div>';

            }

            return response()->json(
                [
                    'form'=> $form,
                    'service_name' => $service_name
                ]);

        }
        else if($type=='removed')
        {
            $ver_insuff=DB::table('verification_insufficiency as vi')
                        ->select('vi.*','s.name as service_name','s.verification_type','u.name as candidate_name','u.display_id')
                        ->join('candidate_reinitiates as u','u.id','=','vi.candidate_id')
                        ->join('services as s','s.id','=','vi.service_id')
                        ->where(['vi.jaf_form_data_id'=>$jaf_id,'vi.service_id'=>$service_id,'vi.status'=>'removed'])
                        ->orderBy('vi.id','desc')
                        ->first();
            if($ver_insuff->notes==NULL)
                $comments='N/A';
            else
                $comments=$ver_insuff->notes;

            $form.='<div class="form-group">
                        <label><strong>Candidate Name:</strong> '.$ver_insuff->candidate_name.' ('.$ver_insuff->display_id.')</label>
                    </div>';

            $form.='<div class="form-group">
                        <label for="label_name"> <strong>Comments: </strong><span id="comments">'.$comments.'</span></label>
                    </div>';
            $insuff_attach=DB::table('insufficiency_attachments')->where(['jaf_form_data_id'=>$jaf_id,'service_id'=>$service_id,'status'=>'removed'])->get();
            if(count($insuff_attach)>0)
            {
                $s3_config = S3ConfigTrait::s3Config();
                $path=url('/').'/uploads/clear-insuff/';
                $form.='<div class="form-group">
                        <label><strong>Attachments: </strong></label>
                        <div class="row">';
                foreach($insuff_attach as $insuff)
                {
                    $img='';
                    if(stripos($insuff->file_platform,'s3')!==false)
                    {
                        $filePath = 'uploads/clear-insuff/';

                        $disk = Storage::disk('s3');

                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                            'Key'                        => $filePath.$insuff->file_name,
                            'ResponseContentDisposition' => 'attachment;'//for download
                        ]);

                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                        $file = (string)$req->getUri();
                    }
                    else
                    {
                        $file=$path.$insuff->file_name;
                    }
                    
                    if(stripos($insuff->file_name, 'pdf')!==false) {
                        $img='<img src="'.url("/")."/admin/images/icon_pdf.png".'" title="'.$insuff->file_name.'" alt="preview" style="height:100px;"/>';
                    }
                    else
                    {
                        $img='<img src="'.$file.'" title="'.$insuff->file_name.'" alt="preview" style="height:100px;"/>';
                    }
                    
                    $form.='<div class="col-4">
                            <div class="image-area" style="width:110px;">
                                '.$img.'
                            </div>
                            </div>';
                }
                $form.='</div>
                        </div>';
            }

            if($ver_insuff->updated_by!=NULL)
            {
                $user=DB::table('users')->where(['id'=>$ver_insuff->updated_by])->first();
                $insuff_by= $user->name;
            }
            else
            {
                $user=DB::table('users')->where(['id'=>$ver_insuff->created_by])->first();
                $insuff_by= $user->name;
            }

            if($ver_insuff->updated_at!=NULL)
            {
                $insuff_date=date('d-m-Y h:i A',strtotime($ver_insuff->updated_at));
            }
            else
            {
                $insuff_date=date('d-m-Y h:i A',strtotime($ver_insuff->created_at));
            }

            $form.='<div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label><strong> Cleared By : </strong><span>'.$insuff_by.' </span</label>
                            </div>
                            <div class="col-6">
                                <label><strong> Cleared Date & Time : </strong><span>'.$insuff_date.' </span</label>
                            </div>
                        </div>
                    </div>';

            if(stripos($ver_insuff->verification_type,'Manual')!==false)
            {
                $service_name = $ver_insuff->service_name.' - '.$ver_insuff->item_number;
            }
            else
            {
                $service_name = $ver_insuff->service_name;
            }

            $insuff_logs = DB::table('insufficiency_logs as i')
                            ->select('i.*','s.name as service_name','s.verification_type')
                            ->join('services as s','s.id','=','i.service_id')
                            ->where(['i.jaf_form_data_id'=>$jaf_id])
                            ->whereIn('i.status',['removed'])
                            ->orderBy('i.id','desc')
                            ->get();

            if(count($insuff_logs)>0)
            {
                $form.='<h5 class="pt-2">Insufficieny Log Details</h5>
                        <p class="pb-border"></p>';

                $count = count($insuff_logs);

                $form.='<div class="insuff-data">';
                foreach($insuff_logs as $key => $insuff)
                {
                    $insuff_comment = 'N/A';
                    $form.='<div class="row">';

                    if($insuff->notes!=NULL)
                    {
                        $insuff_comment=$insuff->notes;
                    }

                    $insuff_by = 'N/A';

                    $user=DB::table('users')->where(['id'=>$insuff->created_by])->first();

                    if($user!=NULL)
                        $insuff_by= $user->name;

                    $form.='<div class="col-12">
                                <div class="form-group">
                                    <label for="label_name"> <strong>Comments:</strong> <span id="comments">'.$insuff_comment.'</span></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><strong>Cleared By : </strong><span>'.$insuff_by.' </span</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><strong>Cleared Date & Time : </strong><span>'.date('d-m-Y h:i A',strtotime($insuff->created_at)).' </span</label>
                                </div>
                            </div>';

                    $form.='</div>';

                    if($count!=++$key)
                    {
                        $form.='<p class="pb-border"></p>';
                    }
                }

                $form.='</div>';

            }

            return response()->json(
                [
                    'form'=> $form,
                    'service_name' => $service_name
                ]);
        }

    }

    /**
     * Export Excel of candidate's JAF data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function export(Request $request) 
    {
      $from_date = $to_date= $customer_id=$business_id = $check_id = $type = $status= "";
      $business_id = Auth::user()->business_id;

      $candidate_id=[];
        
        // dd($request->session()->get('export_candidate_id'));

        if( $request->session()->has('from_date') && $request->session()->has('to_date'))
        {  
          $from_date     =  $request->session()->get('from_date');
          $to_date       =  $request->session()->get('to_date');
        }
        else
        {
          if($request->session()->has('from_date'))
          {
            $from_date      =  $request->session()->get('from_date');
          }
        }
        //
        if($request->session()->has('customer_id'))
        {  
          $customer_id      =  $request->session()->get('customer_id');
        }
        
        if($request->session()->has('export_candidate_id'))
        {  
            $candidate_id   =  $request->session()->get('export_candidate_id');
            rsort($candidate_id);
        }

        if($request->session()->has('type'))
        {  
          $type  =  $request->session()->get('type');
        }

        if($request->session()->has('status'))
        {  
          $status  =  $request->session()->get('status');
        }

       
          $i=0;
        
          $verification_insufficiency=DB::table('verification_insufficiency as vs')
                            ->select('vs.*')
                            ->join('jaf_form_data as jf','jf.id','=','vs.jaf_form_data_id')
                            ->whereIn('vs.candidate_id',$candidate_id)
                            ->where(['vs.status'=>'raised','jf.is_insufficiency'=>1])
                            ->get();
        

        if(count($verification_insufficiency)>0)
        {
          if(stripos($type,'csv')!==false)
            return Excel::download(new InsuffdataExport($from_date, $to_date, $customer_id,$candidate_id, $business_id,$type,$status), 'candidates-insuff-data.csv');
          else
            return Excel::download(new InsuffdataExport($from_date, $to_date, $customer_id,$candidate_id, $business_id,$type,$status), 'candidates-insuff-data.xlsx');
        }
        else
        {
           return 'No Data Found';
        }
        
        
    }
}
