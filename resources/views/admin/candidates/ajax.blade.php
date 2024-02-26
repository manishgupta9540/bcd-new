<div class="row">
<div class="col-md-12">
    {{-- <div class="table-responsive"> --}}
                <table class="table table-bordered  candidatesTable " >
                    <thead>
                        <tr>
                            <th scope="col" style="position:sticky; top:60px"><input type="checkbox" name='showhide' onchange="checkAll(this)"></th>
                            <th scope="col" style="position:sticky; top:60px">Name</th>
                            <th scope="col" style="position:sticky; top:60px">Contact Info</th>
                            {{-- <th scope="col" style="position:sticky; top:60px" width="12%">Phone</th> --}}
                            <th scope="col" style="position:sticky; top:60px">SLA</th>
                            <th scope="col" style="position:sticky; top:60px">JAF Status</th>
                            <th scope="col" style="position:sticky; top:60px">Status</th>
                            <th scope="col" style="position:sticky; top:60px">Created at</th>
                            <th scope="col" style="position:sticky; top:60px">Action</th>
                        </tr>
                    </thead>  
                    <tbody class="candidateList">
                        {{-- Assign value and call helper permission access condition --}}
                        @php
                            // $ADD_ACCESS    = false;
                            $EDIT_ACCESS   = false;
                            $VIEW_ACCESS   = false;
                            $JAF_ACCESS =false;
                            $DELETE_ACCESS = false;
                            $JAF_FILLED_ACCESS =false;
                            $DOWNLOAD_PDF_ACCESS = false;
                            $GENERATE_REPORT_ACCESS = false;
                            $RESEND_ACCESS = false;
                            $HOLD_ACCESS = false;
                            // dd($ADD_ACCESS);
                            $EDIT_ACCESS    = Helper::can_access('Edit Candidates','');//passing action title and route group name
                            $VIEW_ACCESS   = Helper::can_access('View Candidate profile','');//passing action title and route group name
                            $JAF_ACCESS   = Helper::can_access('JAF Link','');//passing action title and route group name
                            $JAF_FILLED_ACCESS   = Helper::can_access('JAF Filled','');//passing action title and route group name
                            $DOWNLOAD_PDF_ACCESS = Helper::can_access('Download Reports ','');//passing action title and route group name
                            $DELETE_ACCESS = Helper::can_access('Delete Candidates','');
                            $GENERATE_REPORT_ACCESS = Helper::can_access('Generate Candidate Reports','');//passing action title and route group name
                            $RESEND_ACCESS = Helper::can_access('Resend Mail','');
                            $HOLD_ACCESS = Helper::can_access('Hold and Resume Candidates','');//passing action title and route group name
                        @endphp 

                        {{-- @if ($users->user_type == 'customer' ) --}}
                            @if( count($items) > 0 )
                           
                                
                                {{-- @endif --}}
                                @foreach($items as $item)
                                <?php $hold = Helper::check_jaf_hold($item->candidate_id) ?>
                                    <tr data-id="{{ base64_encode($item->candidate_id) }}" candidate-d_id="{{ base64_encode($item->candidate_id) }}">
                                        <th scope="row"><input class="priority" type="checkbox"  name="priority[]" value="{{ $item->id }}" onchange='checkChange();'></th>
                                        <td>
                                        @if($item->priority == 'normal')
                                          <i class="fa fa-circle normal"></i> 
                                        @elseif($item->priority == 'high')
                                            <i class="fa fa-circle high"></i>
                                        @else
                                            <i class="fa fa-circle low"></i>
                                        @endif
                                        {{ $item->name }}<br>
                                            <small class="text-muted">Customer: <b>{{Helper::company_name($item->business_id)}}</b></small><br>
                                            <small class="text-muted">Ref. No. <b>{{$item->display_id }}</b></small>

                                        </td>
                                        <td>
                                            {{-- {{ $item->email!=''?$item->email:"" }} --}}
                                            {{ $item->email }} <br>
                                            {{ $item->phone!=null?'+'.$item->phone_code."-".str_replace(' ','',$item->phone):"--" }}
                                        </td>
                                        {{-- <td></td> --}}
                                        <td>
                                          <small>  {{ Helper::get_sla_name($item->sla_id)}} </small><br>
                                          @php 
                                                $tat=  Helper::get_sla_tat($item->sla_id);
                                            
                                                $tat_overdue=0;
                                                if($item->filled_at!=NULL)
                                                {
                                                    $tat_overdue=Helper::tat_overdue($item->filled_at,$tat['tat']);
                                                }
                                            @endphp
                                            <small > <span class="text-info"> Internal TAT-</span> {{$tat['tat']}} </small><br>
                                            <small class=""><span class="text-danger">Client TAT -</span> {{$tat['client_tat']}}</small>
                                                @if($item->jaf_status=='filled')
                                                    <?php $report_status = Helper::get_report_status($item->candidate_id); 
                                                    
                                                    // dd($report_status);?>
                                                   
                                                    {{-- @if ($report_status) --}}
                                                        @if($report_status==NULL || $report_status['status']=='incomplete' )
                                                            @if($tat_overdue > 0)
                                                                @if($item->is_tat_ignore==1)
                                                                    <small><span class="text-info"> <button class="btn btn-link ignor_tat_lnk" c_name="{{$item->name}}" tat_notes="{{$item->tat_notes}}" tat_days="{{$item->tat_ignore_days}}" type="button" style="padding-left:0px;"> TAT Ignored</button></span></small>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    {{-- @endif --}}
                                                @endif
                                        </td>
                                        <td>
                                            @php
                                                $user_type= Auth::user()->user_type;
                                                if ($user_type=='user') {
                                                    
                                                    $cam = Helper::get_is_cam($item->business_id);

                                                }
                                            @endphp
                                            @if ($user_type == 'customer' || $cam!==null)
                                                
                                            
                                                @if($item->jaf_status == 'pending' || $item->jaf_status == 'draft')
                                                    @if ($item->jaf_send_to== 'customer' || $item->jaf_send_to== 'Customer')
                                                        @if ($item->jaf_status == 'pending')
                                                            <span class="badge badge-danger">Not Filled</span><br>
                                                        @else
                                                            <span class="badge badge-danger">Draft</span><br>
                                                        @endif
                                                        @if($JAF_ACCESS)
                                                            @if ($hold)
                                                                <span data-can_id="{{ base64_encode($item->candidate_id)}}">
                                                                    @if(Auth::user()->business_id==$hold->hold_by)
                                                                        On Hold 
                                                                    @else
                                                                        <?php $business_id=Helper::get_business_id($hold->hold_by) ?>
                                                                        On Hold By {{Helper::user_name($hold->hold_by)}} @if($business_id!=NULL)({{Helper::company_name($business_id)}}) @endif
                                                                    @endif
                                                                </span>
                                                                    {{-- <span data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span> --}}
                                                                <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($item->job_item_id),'id' =>  base64_encode($item->candidate_id)])}}"  style='font-size:10px;'  data-cand_id="{{ base64_encode($item->candidate_id)}}" class="bnt-link jaf d-none">JAF Link</a>
                                                            @else
                                                                <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($item->job_item_id),'id' =>  base64_encode($item->candidate_id)])}}"  style='font-size:10px;'  data-cand_id="{{ base64_encode($item->candidate_id)}}" class="bnt-link jaf">JAF Link</a>
                                                                <span class="d-none" data-can_id="{{ base64_encode($item->candidate_id)}}" >On Hold</span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                    @if ($item->jaf_send_to== 'candidate' || $item->jaf_send_to== 'Candidate')
                                                        @if ($item->jaf_status == 'pending')
                                                            <span class="badge badge-danger">Not Filled</span><br>
                                                        @else
                                                            <span class="badge badge-danger">Draft</span><br>
                                                        @endif
                                                        @if($hold)
                                                            <span data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span>
                                                            <span style="font-size: 10px;" class="d-none" data-cand_id="{{ base64_encode($item->candidate_id)}}">JAF Sent to Candidate</span>
                                                            <div><a href="javascript:;" style="font-size: 10px;" id="resendMail{{base64_encode($item->candidate_id)}}" class="btn-link resendMail resendMail{{base64_encode($item->candidate_id)}} d-none" data-id="{{base64_encode($item->candidate_id)}}" data-resend="{{base64_encode($item->candidate_id)}}"><i class="far fa-envelope"></i> Re-send Mail</a></div>
                                                        @else
                                                            <span class="d-none" data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span>
                                                            <span style="font-size: 10px;" class="" data-cand_id="{{ base64_encode($item->candidate_id)}}">JAF Sent to Candidate</span>
                                                            <div><a href="javascript:;" style="font-size: 10px;" id="resendMail{{base64_encode($item->candidate_id)}}" class="btn-link resendMail resendMail{{base64_encode($item->candidate_id)}}" data-id="{{base64_encode($item->candidate_id)}}" data-resend_m="{{base64_encode($item->candidate_id)}}"><i class="far fa-envelope"></i> Re-send Mail</a></div>
                                                        @endif
                                                    @endif
                                                    @if ($item->jaf_send_to== 'coc' || $item->jaf_send_to== 'COC')
                                                        @if ($item->jaf_status == 'pending')
                                                            <span class="badge badge-danger" >Not Filled</span><br> 
                                                        @else
                                                            <span class="badge badge-danger" >Draft</span><br>
                                                        @endif
                                                        @if($hold)
                                                           <small> <span data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span></small>
                                                            <small><span style="font-size: 10px;" class="d-none" data-cand_id="{{ base64_encode($item->candidate_id)}}">JAF Sent to Customer: <b>{{Helper::company_name($item->business_id)}}</span></small>
                                                        @else
                                                           <small> <span style="font-size: 10px;" data-cand_id="{{ base64_encode($item->candidate_id)}}">JAF Sent to Customer: <b>{{Helper::company_name($item->business_id)}}</span></small>
                                                           <small> <span class="d-none" data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span></small>
                                                        @endif
                                                    @endif
                                                        {{-- <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($item->job_item_id) ]) }}" style='font-size:14px;' class="bnt-link">JAF Link</a> --}}
                                                @endif

                                                @if($item->jaf_status == 'filled')
                                                    {{-- For JAF filled access --}}
                                                    @if ($JAF_FILLED_ACCESS)
                                                        @if($hold) 
                                                           <small> <span data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span>  </small>
                                                            <small> <span class="badge badge-success d-none" data-cand_id="{{ base64_encode($item->candidate_id)}}"> <a style="color:#fff;" href="{{ url('/candidates/jaf-info',['id'=> base64_encode($item->candidate_id)]) }}"> JAF Filled </a></span></small><br>
                                                        @else
                                                            <small>  <span class="d-none" data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span></small>
                                                            <small> <span class="badge badge-success" data-cand_id="{{ base64_encode($item->candidate_id)}}"> <a style="color:#fff;" href="{{ url('/candidates/jaf-info',['id'=> base64_encode($item->candidate_id)]) }}">JAF Filled </a></span></small><br>
                                                        @endif
                                                    @endif
                                                    <!-- get report status -->
                                                    <?php
                                                        $report_status = Helper::get_report_status($item->candidate_id); 
                                                    
                                                        $ready_report = Helper::get_ready_report($item->candidate_id);
                                                    ?>
                                                    @if($report_status != NULL && ($report_status['status'] =='completed' || $report_status['status'] =='interim') ) 
                                                        @if ($DOWNLOAD_PDF_ACCESS)
                                                            <a href="javascript:;" style="font-size: 10px;" class="btn-link reportExportBox" data-id="{{  base64_encode($report_status['id']) }}" > PDF Report</a>

                                                        @endif
                                                    @else 
                                                        @if ($GENERATE_REPORT_ACCESS)
                                                            @if($hold)
                                                                @if($ready_report== 1)
                                                                    <a style='font-size:10px;' class="btn-lnk send_report_otp cursor-pointer d-none" data-id={{ base64_encode($item->candidate_id) }} data-cand_id="{{ base64_encode($item->candidate_id)}}">Generate Report</a>  <br>
                                                                @endif
                                                                {{-- <span data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span> --}}
                                                                {{-- <a href="{{ url('candidate/report-generate',['id'=>  base64_encode($item->candidate_id) ]) }}" style='font-size:14px;' class="bnt-link">Generate Report</a>   --}}
                                                                {{-- <small class="text-muted">Ref. No. <b>{{ $item->display_id }}</b></small> --}}
                                                                <a href="{{ url('/jaf-download',['id'=>base64_encode($item->candidate_id)]) }}" style="font-size: 10px;" class="btn-lnk d-none" data-cand_id="{{ base64_encode($item->candidate_id)}}">Download JAF </a> <br>
                                                                @if($tat_overdue > 0)
                                                                    <span class="text-danger d-none" data-cand_id="{{ base64_encode($item->candidate_id)}}"><small>Overdue TAT - {{$tat_overdue}} {{$tat_overdue>1? 'Days':'Day'}}</small></span><br>
                                                                @endif
                                                            @else
                                                                {{-- <span class="d-none" data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span> --}}
                                                                <a style='font-size:10px;' class="btn-lnk send_report_otp cursor-pointer" data-id={{ base64_encode($item->candidate_id) }} data-cand_id="{{ base64_encode($item->candidate_id)}}">Generate Report</a>  <br>
                                                                {{-- <a href="{{ url('candidate/report-generate',['id'=>  base64_encode($item->candidate_id) ]) }}" style='font-size:14px;' class="bnt-link">Generate Report</a>   --}}
                                                                {{-- <small class="text-muted">Ref. No. <b>{{ $item->display_id }}</b></small> --}}
                                                                <a href="{{ url('/jaf-download',['id'=>base64_encode($item->candidate_id)]) }}" style="font-size: 10px;" class="btn-lnk" data-cand_id="{{ base64_encode($item->candidate_id)}}">Download JAF </a> <br>
                                                                @if($tat_overdue > 0)
                                                                    <span class="text-danger" data-cand_id="{{ base64_encode($item->candidate_id)}}"><small>Overdue TAT - {{$tat_overdue}} {{$tat_overdue>1? 'Days':'Day'}}</small></span><br>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            
                                            @else
                                                 @if($item->jaf_status == 'pending' || $item->jaf_status == 'draft' || $item->jaf_status == 'filled')
                                                    @if($item->jaf_status == 'pending' || $item->jaf_status == 'draft')
                                                        @if ($item->jaf_status == 'pending')
                                                            <span class="badge badge-danger">Not Filled</span><br> 
                                                        @elseif($item->jaf_status == 'draft')
                                                            <span class="badge badge-danger">Draft</span><br>
                                                        @endif

                                                        @if ($item->jaf_send_to== 'candidate' || $item->jaf_send_to== 'Candidate')
                                                            @if($hold)
                                                                <span data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span>
                                                                <span style="font-size: 10px;" class="d-none" data-cand_id="{{ base64_encode($item->candidate_id)}}">JAF Sent to Candidate</span>
                                                                 @if($RESEND_ACCESS)
                                                                    <div><a href="javascript:;" style="font-size: 10px;" id="resendMail{{base64_encode($item->candidate_id)}}" class="btn-link resendMail resendMail{{base64_encode($item->candidate_id)}} d-none" data-id="{{base64_encode($item->candidate_id)}}" data-resend="{{base64_encode($item->candidate_id)}}"><i class="far fa-envelope"></i> Re-send Mail</a></div>
                                                                @endif
                                                            @else
                                                                <span class="d-none" data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span>
                                                                <span style="font-size: 10px;" class="" data-cand_id="{{ base64_encode($item->candidate_id)}}">JAF Sent to Candidate</span>
                                                               @if($RESEND_ACCESS)
                                                                    <div><a href="javascript:;" style="font-size: 10px;" id="resendMail{{base64_encode($item->candidate_id)}}" class="btn-link resendMail resendMail{{base64_encode($item->candidate_id)}}" data-id="{{base64_encode($item->candidate_id)}}" data-resend_m="{{base64_encode($item->candidate_id)}}"><i class="far fa-envelope"></i> Re-send Mail</a></div>
                                                                @endif
                                                            @endif
                                                        @endif
                                                        @if ($item->jaf_send_to== 'coc' || $item->jaf_send_to== 'COC')
                                                            @if($hold)
                                                                <small> <span data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span></small>
                                                                <small><span style="font-size: 10px;" class="d-none" data-cand_id="{{ base64_encode($item->candidate_id)}}">JAF Sent to Customer: <b>{{Helper::company_name($item->business_id)}}</span></small>
                                                            @else
                                                                <small> <span style="font-size: 10px;" data-cand_id="{{ base64_encode($item->candidate_id)}}">JAF Sent to Customer: <b>{{Helper::company_name($item->business_id)}}</span></small>
                                                                <small> <span class="d-none" data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span></small>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <span class="badge badge-success">JAF Filled</span>
                                                        @if ($JAF_FILLED_ACCESS)
                                                            @if($hold) 
                                                                <small> <span data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span>  </small>
                                                            @else
                                                                <small> <span class="d-none" data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span></small>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif 
                                        </td>
                                        <td>
                                            @php
                                                $ready_to_report = Helper::get_ready_report($item->candidate_id);
                                            @endphp
                                            {!! Helper::get_jaf_auto_check_api_status($item->candidate_id) !!} <br>
                                            @if($ready_to_report== 1)
                                                @if($report_status != NULL && ($report_status['status'] =='completed' || $report_status['status'] =='interim') ) 
                                                    <small class='text-success'> Report Generated</small>
                                                @else
                                                    <small class='text-success'> Ready to Report </small>
                                                @endif
                                               
                                            @endif
                                                
                                            {{-- @else
                                                <small class='text-warning'> Not Ready to Report </small>
                                            @endif --}}
                                        </td>
                                        <td>{{ date('d-m-Y',strtotime($item->created_at)) }}</td>
                                        <td>
                                            {{-- check permission access condition  --}}

                                            @if($VIEW_ACCESS)
                                                @if ($hold)
                                                    <a class="d-none" href="{{ route('/candidates/show',['id'=>base64_encode($item->id)]) }}" data-candidate="{{ base64_encode($item->candidate_id) }}">
                                                        <button class="btn btn-primary btn-sm mb-1" type="button" style="width: 40%;"> <i class='fa fa-eye'></i> View</button>
                                                    </a>
                                                @else
                                                    <a href="{{ route('/candidates/show',['id'=>base64_encode($item->id)]) }}"  data-candidate="{{ base64_encode($item->candidate_id) }}">
                                                        <button class="btn btn-primary btn-sm mb-1" type="button" style="width: 40%;"> <i class='fa fa-eye'></i> View</button>
                                                    </a>
                                                @endif
                                            @endif
                                            @if($EDIT_ACCESS)
                                                <a href="{{ route('/candidates/edit',['id'=>base64_encode($item->id)]) }}">
                                                    <button class="btn btn-info btn-sm mb-1" type="button" style="width: 40%;"> <i class='fa fa-edit'></i> Edit</button>
                                                </a>
                                            @endif
                                            {{-- @if($item->jaf_status == 'pending' || $item->jaf_status == 'draft') --}}
                                                {{-- @if ($DELETE_ACCESS)
                                                        <button class="btn btn-danger btn-sm deleteRow" type="button" data-id="{{ base64_encode($item->candidate_id) }}"> <i class='fa fa-trash'></i> Delete</button>
                                                @endif --}}
                                            
                                            {{-- @endif --}}
                                            @if ($DELETE_ACCESS)
                                                <button class="btn btn-danger btn-sm deleteRow mb-1" type="button" style="width: 40%;" data-id="{{ base64_encode($item->candidate_id) }}" > <i class='fa fa-trash'></i> Delete</button>
                                            @endif
                                          <?php $report_status = Helper::get_report_status($item->candidate_id); ?>
                                          
                                          @if ($report_status==NULL || $report_status['status']=='incomplete' || $report_status['status']=='interim')
                                            @if ($HOLD_ACCESS)
                                                @if ($hold)
                                                    <button class="btn btn-success btn-md resume mb-1 " type="button" style="width: 40%;"  data-candidate_id="{{ base64_encode($item->candidate_id) }}" data-business_id="{{ base64_encode($item->business_id) }}"><i class="fas fa-play-circle"> Resume</i></button>
                                                    <button class="btn btn-warning btn-md hold d-none mb-1" type="button" style="width: 40%;"  data-candidate="{{ base64_encode($item->candidate_id) }}" data-business="{{ base64_encode($item->business_id) }}"><i class="fas fa-pause-circle"> Hold</i></button>
                                                @else
                                                    <button class="btn btn-warning btn-md hold mb-1" type="button" style="width: 40%;"  data-candidate="{{ base64_encode($item->candidate_id) }}" data-business="{{ base64_encode($item->business_id) }}"><i class="fas fa-pause-circle"> Hold</i></button>
                                                    <button class="btn btn-success btn-md resume d-none mb-1" type="button" style="width: 40%;" data-candidate_id="{{ base64_encode($item->candidate_id) }}" data-business_id="{{ base64_encode($item->business_id) }}"><i class="fas fa-play-circle"> Resume</i></button>
                                                @endif
                                            @endif
                                          @endif
                                            @if($item->jaf_status=='filled')
                                                <?php $report_status = Helper::get_report_status($item->candidate_id); ?>
                                                @if ($report_status==NULL || $report_status['status']=='incomplete')
                                                    {{-- @if($report_status['status']=='incomplete') --}}
                                                        @if($tat_overdue > 0)
                                                            @if($item->is_tat_ignore==0)
                                                                <button class="btn btn-dark btn-sm ignore_tat mb-1" style="width: 40%;" candidate-id={{base64_encode($item->candidate_id)}} type="button" > Ignore TAT</button>
                                                            @endif
                                                        @endif
                                                    {{-- @endif --}}
                                                @endif
                                            @endif
                                            
                                            @if($item->jaf_status=='filled')
                                                <button class="btn btn-info btn-sm mb-1 send-link" type="button" candidate-id={{base64_encode($item->candidate_id)}} style="width: 40%;">Send Link</button>
                                            @endif
                                            
                                            </td>
                                        
                                    </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td scope="row" colspan="9"><h3 class="text-center">No record!</h3></td>
                                </tr>
                            @endif 
                       
                {{-- @endif --}}
                    </tbody>
                </table>
                {{-- </div> --}}
             </div>
          </div>

            <div class="row">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info" role="status" aria-live="polite"></div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class=" paging_simple_numbers" >            
                        {!! $items->render() !!}
                    </div>
                </div>
		    </div>
        
       {{-- Modal for otp verification    --}}

    <div class="modal" id="send_otp">
        <div class="modal-dialog">
           <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                 <h4 class="modal-title">OTP Verification to Generate Report</h4>
                 {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
              </div>
              <!-- Modal body -->
              <form method="post" action="{{url('/candidates/verfiy_otp')}}" id="verify_otp">
              @csrf
                <input type="hidden" name="can_id" id="can_id">
                 <div class="modal-body">
                    <div class="form-group">
                        <label for="label_name"> <strong> Candidate Name : </strong> <span class="c_name"></span> </label>
                    </div>
                    <div class="form-group pb-3">
                        <label for="label_name"> <strong> Reference No. : </strong> <span class="c_ref_no"></span> </label>
                    </div>
                    <div class="form-group">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-sm-5 text-center">
                                <label for="label_name"> OTP </label>
                            </div>
                        </div>
                        <div class="row justify-content-center align-items-center">
                            <div class="col-sm-6 text-center">
                                <input name="otp[]" class="digit text-center otp" type="text" id="first_otp" size="1" maxlength="1" tabindex="0" >
                                <input name="otp[]" class="digit text-center otp" type="text" id="second_otp" size="1" maxlength="1" tabindex="1">
                                <input name="otp[]" class="digit text-center otp" type="text" id="third_otp" size="1" maxlength="1"  tabindex="2">
                                <input name="otp[]" class="digit text-center otp" type="text" id="fourth_otp" size="1" maxlength="1" tabindex="3">
                            </div>
                        </div>
                        <div class="row justify-content-center align-items-center">
                            <div class="col-sm-6 text-center">
                                <p style="margin-bottom: 2px;" class="text-danger error-container pt-2" id="error-otp"></p> 
                                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                            </div>
                        </div>
                    </div>
                    {{-- <div class="form-group">
                          <label for="label_name"> OTP </label>
                          <input type="text" id="otp" name="otp" class="form-control otp" placeholder="Enter OTP"/>
                          <p style="margin-bottom: 2px;" class="text-danger" id="error-otp"></p> 
                    </div> --}}
                 </div>
                 <!-- Modal footer -->
                 <div class="modal-footer">
                    <button type="submit" class="btn btn-info otp_submit btn_otp">Submit </button>
                    <button type="button" class="btn btn-danger btn_otp" id="otp_close" data-dismiss="modal">Close</button>
                 </div>
              </form>
           </div>
        </div>
     </div>
     
       <!-- Footer Start -->
       <div class="flex-grow-1"></div>
       
    </div>

{{-- Ingnore Tat  Model --}}
    <div class="modal" id="tat_ignore_modal">
        <div class="modal-dialog">
           <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                 <h4 class="modal-title">Ignore TAT</h4>
                 {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
              </div>
              <!-- Modal body -->
              <form method="post" action="{{url('/candidates/ignore_tat')}}" id="tat_ignore">
              @csrf
                <input type="hidden" name="candi_id" id="candi_id">
                 <div class="modal-body">
                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                    <div class="form-group">
                        <label for="label_name"> No of Days: <span class="text-danger">*</span></label>
                        <input type="number" id="days" name="days" min="0" max="10" class="form-control days" placeholder="ex : 7"/>
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-days"></p> 
                    </div>
                    <div class="form-group">
                          <label for="label_name"> Notes: <span class="text-danger">*</span></label>
                          {{-- <input type="text" id="otp " name="otp" class="form-control otp" placeholder="Enter OTP"/> --}}
                          <textarea name="notes" class="form-control notes" placeholder=""></textarea>
                          <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-notes"></p> 
                    </div>
                 </div>
                 <!-- Modal footer -->
                 <div class="modal-footer">
                    <button type="submit" class="btn btn-info tat_ignore_submit btn-disable">Submit </button>
                    <button type="button" class="btn btn-danger btn-disable" id="tat_ignore_close" data-dismiss="modal">Close</button>
                 </div>
              </form>
           </div>
        </div>
     </div>
     
       <!-- Footer Start -->
       <div class="flex-grow-1"></div>
       
    </div>
    {{-- Ingnore Tat Show Details Model --}}
    <div class="modal" id="ignore_tat_details">
        <div class="modal-dialog">
           <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                 <h4 class="modal-title" id="can_name"></h4>
                 {{-- <button type="button" class="close" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>&times;</small></button> --}}
              </div>
              <!-- Modal body -->
              {{-- <form method="post" action="{{url('/candidates/ignore_tat')}}" id="tat_ignore">
              @csrf --}}
                {{-- <input type="hidden" name="candi_id" id="candi_id"> --}}
                 <div class="modal-body">
                 {{-- <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p>  --}}
                    <div class="form-group">
                        <label for="label_name"> No of Days: </label>
                        {{-- <input type="text" id="otp " name="otp" class="form-control otp" placeholder="Enter OTP"/> --}}
                        <span id="day" class="text-muted day"></span>
                        {{-- <p style="margin-bottom: 2px;" class="text-danger" id="error-notes"></p>  --}}
                    </div>
                    <div class="form-group">
                          <label for="label_name"> Notes: </label>
                          {{-- <input type="text" id="otp " name="otp" class="form-control otp" placeholder="Enter OTP"/> --}}
                          <textarea name="note" class="form-control note" placeholder="" readonly></textarea>
                          {{-- <p style="margin-bottom: 2px;" class="text-danger" id="error-notes"></p>  --}}
                    </div>
                 </div>
                 <!-- Modal footer -->
                 <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                 </div>
              {{-- </form> --}}
           </div>
        </div>
     </div>
     
       <!-- Footer Start -->
       <div class="flex-grow-1"></div>
       
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.send_report_otp').click(function(){
                var _this=$(this);
                var id=$(this).attr('data-id');
                $('#can_id').val(id);
                var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
                _this.addClass('disabled-link');
                if (_this.html() !== loadingText) {
                    _this.html(loadingText);
                }
                $.ajax({
                    url:"{{ route('/candidates/send_otp') }}",
                    method:"POST",
                    data:{"_token": "{{ csrf_token() }}",'_id':id},      
                    success:function(data)
                    {
                        window.setTimeout(function(){
                            _this.removeClass('disabled-link');
                            _this.html('Generate Report');
                        },2000);
                        console.log(data);
                        if(data.fail == false)
                        {
                            $('#verify_otp')[0].reset();
                            $('.otp').removeClass('border-danger');
                            $('.error-container').html('');
                            $('.c_name').html(data.data.name);
                            $('.c_ref_no').html(data.data.ref_no);
                            //notify
                            $('#send_otp').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            // console.log(data.id);
                        }
                        else
                        {
                            alert('not working');
                        }
                    },
                    error : function(data){
                        console.log(data);
                    }
                });
                        
            });

            // $('.otp_submit').on('click', function() {
            //     $('#otp_close').prop('disabled',true);
           
            //     var $this = $(this);
            //     var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            //     if ($(this).html() !== loadingText) {
            //     $this.data('original-text', $(this).html());
            //     $this.html(loadingText);
            //     // $this.prop('disabled',true);
            //     }
            //     setTimeout(function() {
            //     $this.html($this.data('original-text'));
            //     $this.prop('disabled',false);
            //     }, 5000);
            // });

                // $('#erifyotpBtn').click(function(e) {
                //     e.preventDefault();
                //     $("#verify_otp").submit();
                // });
    
            $(document).on('submit', 'form#verify_otp', function (event) {
    
                $("#overlay").fadeIn(300);　
                event.preventDefault();
                var form = $(this);
                var data = new FormData($(this)[0]);
                var url = form.attr("action");
                var $btn = $(this);
                $('.btn_otp').attr('disabled',true);
                $('.otp').removeClass('border-danger');
                var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
                if($('.otp_submit').html()!=loadingText)
                {
                    $('.otp_submit').html(loadingText);
                }
                $('.error-container').html('');
                $.ajax({
                    type: form.attr('method'),
                    url: url,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        // console.log(data);
                        // $('.error-container').html('');
                        window.setTimeout(function(){
                            $('.btn_otp').attr('disabled',false);
                            $('.otp_submit').html('Submit');
                        },2000);
                        if (data.fail && data.error_type == 'validation') {
                                
                                //$("#overlay").fadeOut(300);
                                for (control in data.errors) {
                                    $('.' + control).addClass('border-danger');
                                    $('#error-' + control).html(data.errors[control]);
                                }
                        } 
                        if (data.fail && data.error == 'yes') {
                            
                            $('#error-all').html(data.message);
                        }
                        if (data.fail == false) {
                            // $('#send_otp').modal('hide');
                            // alert(data.id);
                            var candidate_id=data.id;
                            // alert('abd');
                            window.location="{{ url('/') }}"+"/candidate/report-generate/"+candidate_id;
                            
                            // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                            //  location.reload(); 
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                    // error: function (xhr, textStatus, errorThrown) {
                    //     console.log("Error: " + errorThrown);
                    //     // alert("Error: " + errorThrown);
    
                    // }
                });
                event.stopImmediatePropagation();
                return false;
    
            });
           
            $(document).on('click', '.ignore_tat', function (event) {
    
                var candidate_id = $(this).attr('candidate-id');
                $('#tat_ignore')[0].reset();
                $('#candi_id').val(candidate_id);
                $('.error-container').html('');
                $('#tat_ignore_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                // tat_ignore_submit
            });
            // $('.tat_ignore_submit').on('click', function() {
            //     $('#tat_ignore_close').prop('disabled',true);
           
            //     var $this = $(this);
            //     var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            //     if ($(this).html() !== loadingText) {
            //     $this.data('original-text', $(this).html());
            //     $this.html(loadingText);
            //     // $this.prop('disabled',true);
            //     }
            //     setTimeout(function() {
            //     $this.html($this.data('original-text'));
            //     $this.prop('disabled',false);
            //     }, 5000);
            // });

            // $('#tatIgnoreBtn').click(function(e) {
            //     e.preventDefault();
            //     $("#tat_ignore").submit();
            // });
            $(document).on('submit', 'form#tat_ignore', function (event) {
    
                    $("#overlay").fadeIn(300);　
                    event.preventDefault();
                    var form = $(this);
                    var data = new FormData($(this)[0]);
                    var url = form.attr("action");
                    var $btn = $(this);
                    var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
                    $('.btn-disable').attr('disabled',true);
                    if($('.tat_ignore_submit').html()!==loadingText)
                    {
                        $('.tat_ignore_submit').html(loadingText);
                    }

                    $.ajax({
                        type: form.attr('method'),
                        url: url,
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            // console.log(data);
                            $('.error-container').html('');
                            window.setTimeout(function(){
                                $('.btn-disable').attr('disabled',false);
                                $('.tat_ignore_submit').html('Submit');
                            },2000);
                            if (data.fail && data.error_type == 'validation') {
                                    
                                    //$("#overlay").fadeOut(300);
                                    for (control in data.errors) {
                                        $('textarea[notes=' + control + ']').addClass('is-invalid');
                                        $('input[days=' + control + ']').addClass('is-invalid');
                                        $('#error-' + control).html(data.errors[control]);
                                    }
                            } 
                            if (data.fail && data.error == 'yes') {
                                
                                $('#error-all').html(data.message);
                            }
                            if (data.fail == false) {
                                // $('#send_otp').modal('hide');
                                // alert(data.id);

                                // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                                //  location.reload(); 
                                toastr.success('TAT Ignored Successfully');
                                window.setTimeout(function(){
                                    location.reload(); 
                                },2000);
                            }
                        },
                        error: function (data) {
                            
                            console.log(data);

                        }
                        // error: function (xhr, textStatus, errorThrown) {
                            
                        //     alert("Error: " + errorThrown);

                        // }
                    });
                    return false;

            });

            $(document).on('click', '.ignor_tat_lnk', function (event) {
    
                var tat_notes = $(this).attr('tat_notes');
                var candidate_name=$(this).attr('c_name');
                var tat_days=$(this).attr('tat_days');
                $('#can_name').html('TAT Ignored ('+ candidate_name + ')');
                $('.note').val(tat_notes);
                $('.day').html(tat_days);
                $('#ignore_tat_details').modal({
                    backdrop: 'static',
                    keyboard: false
                });


            });

            // $(".otp").keyup(function(event){
            //     if ($(this).next('.otp').length > 0){
            //         $(this).next('.otp')[0].focus();
            //     }else{
            //         if ($(this).parent().next().find('.otp').length > 0){
            //             $(this).parent().next().find('.otp')[0].focus();
            //         }
            //     }
            // });

        });


        // Select all check
    function checkAll(e) {
        var checkboxes = document.getElementsByClassName('priority');
        
        if (e.checked) {
            for (var i = 0; i < checkboxes.length; i++) { 
            checkboxes[i].checked = true;
            }
        } else {
            for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = false;
            }
        }
    }
    function checkChange(){

        var totalCheckbox = document.querySelectorAll('.priority').length;
        var totalChecked = document.querySelectorAll('.priority:checked').length;

        // When total options equals to total checked option
        if(totalCheckbox == totalChecked) {
            document.getElementsByName("showhide")[0].checked=true;
        } else {
            document.getElementsByName("showhide")[0].checked=false;
        }
    }

    function OTPInput() {
        const inputs = document.querySelectorAll('.otp');
        // alert(inputs.length);
        for (let i = 0; i < inputs.length; i++) 
        { 
            inputs[i].addEventListener('keyup', function(event) 
            { 
                if (event.key==="Backspace" ) 
                { 
                    inputs[i].value='' ; 
                    if (i !==0) inputs[i - 1].focus();
                    
                } 
                else { 
                    if (i===inputs.length - 1 && inputs[i].value !=='' ) 
                    { return true; } 
                    else if (event.keyCode> 47 && event.keyCode < 58) 
                    { 
                        inputs[i].value=event.key; 
                        if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); 
                        
                    } 
                    else if (event.keyCode> 95 && event.keyCode < 106) 
                    { 
                        inputs[i].value=event.key; 
                        if (i !==inputs.length - 1) 
                        inputs[i + 1].focus(); event.preventDefault(); 
                        
                    }
                } 
                
            }); 
            
        } 
        
    } 
    OTPInput(); 
    // end All Check 

    </script>
        