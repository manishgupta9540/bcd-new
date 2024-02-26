@php
$DELETE_ACCESS    = false;
$DOWNLOAD_ACCESS   = false;
$VIEW_ACCESS   = false;
$JAF_ACCESS =false;
$HOLD_ACCESS = false;
$RESUME_ACCESS = false;
$RESEND_ACCESS = false;
$VIEW_ACCESS   = Helper::can_access('View Candidate profile','/my');//passing action title and route group name
$JAF_ACCESS   = Helper::can_access('JAF Link','/my');//passing action title and route group name
$DELETE_ACCESS   = Helper::can_access('Delete Candidate','/my');//passing action title and route group name
$DOWNLOAD_ACCESS   = Helper::can_access('JAF Download','/my');//passing action title and route group name
$HOLD_ACCESS   = Helper::can_access('Hold Candidate','/my');//passing action title and route group name
$RESUME_ACCESS   = Helper::can_access('Resume Candidate','/my');//passing action title and route group name
$RESEND_ACCESS   = Helper::can_access('Resend JAF to Candidate','/my');//passing action title and route group name
@endphp 
{{-- @if($LIST_ACCESS) --}}
        <div class="row">
            <div class="col-md-12"> 
               
                 <div class="table-responsive">
                
                    <table class="table table-bordered  table-hover candidatesTable">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Emp. Code</th>
                                <th scope="col" width="15%">Phone Number</th>
                                <th scope="col">Email</th>
                                <th scope="col">SLA</th>
                                <th scope="col">JAF Status</th>
                                <th scope="col">Created at</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="candidateList"> 
                        @if( count($items) > 0 )
                            @foreach($items as $item)
                            <?php $hold = Helper::check_jaf_hold($item->candidate_id) ?>
                            <tr data-id="{{ base64_encode($item->candidate_id) }}">
                        
                                <td>
                                   @if($hold)
                                        <div><a class="d-none" href="{{url('/my/candidates/jaf-info',['id'=>base64_encode($item->id)])}}" class="btn-link" data-candidate="{{ base64_encode($item->candidate_id) }}"> {{ $item->name }} </a></div>
                                        <div data-candidate_id="{{ base64_encode($item->candidate_id) }}">{{ $item->name }}</div>
                                   @else
                                        <div><a href="{{url('/my/candidates/jaf-info',['id'=>base64_encode($item->id)])}}" class="btn-link" data-candidate="{{ base64_encode($item->candidate_id) }}"> {{ $item->name }} </a></div>
                                        <div class="d-none" data-candidate_id="{{ base64_encode($item->candidate_id) }}">{{ $item->name }}</div>
                                   @endif
                                    <small class="text-muted">Ref. No.: {{ $item->display_id }}</small>
                                </td>

                                <td>{{ $item->client_emp_code }}</td>
                                <td>{{ "+".$item->phone_code."-".str_replace(' ','',$item->phone) }}</td>
                                <td>{{ $item->email }}</td>
                                
                                <td>
                                    {{ Helper::get_sla_name($item->sla_id)}} <br>
                                    <?php $tat=  Helper::get_sla_tat($item->sla_id);?>
                                    <small class=""><span class="text-danger"> TAT -</span> {{$tat['client_tat']}}</small>
                                </td>
                                <td>
                                    @if($item->jaf_status == 'pending' || $item->jaf_status == 'draft')
                                    
                                        @if ($item->jaf_send_to== 'coc')
                                            @if ($item->jaf_status == 'pending')
                                            <span class="badge badge-danger">Not Filled</span><br>
                                            @else
                                            <span class="badge badge-danger">Draft</span><br>
                                            @endif
                                            <?php 
                                                $type = Auth::user()->user_type; 
                                            ?>
                                            @if ($VIEW_ACCESS)
                                                @if ($hold)
                                                    <span data-can_id="{{ base64_encode($item->candidate_id)}}">
                                                        @if(Auth::user()->business_id==$hold->hold_by)
                                                            On Hold 
                                                        @else
                                                            <?php $business_id=Helper::get_business_id($hold->hold_by) ?>
                                                            On Hold By {{Helper::user_name($hold->hold_by)}} @if($business_id!=NULL)({{Helper::company_name($business_id)}}) @endif
                                                        @endif
                                                    </span>
                                                    <a href="{{ url('my/candidates/jaf-fill',['case_id'=>  base64_encode($item->job_item_id),'id' =>  base64_encode($item->candidate_id) ]) }}" style='font-size:12px;' data-cand_id="{{ base64_encode($item->candidate_id)}}" class="bnt-link jaf d-none">JAF Link</a>
                                                @else
                                                    <a href="{{ url('my/candidates/jaf-fill',['case_id'=>  base64_encode($item->job_item_id),'id' =>  base64_encode($item->candidate_id) ]) }}" style='font-size:12px;' data-cand_id="{{ base64_encode($item->candidate_id)}}" class="bnt-link jaf">JAF Link</a>
                                                    <span class="d-none" data-can_id="{{ base64_encode($item->candidate_id)}}" >On Hold</span>
                                                @endif

                                            @else
                                            
                                                <span>you have not permission for JAF Filling</span>
                                            @endif
                                    
                                            {{-- <a href="{{ url('my/candidates/jaf-fill',['case_id'=>  base64_encode($item->job_item_id) ]) }}" style='font-size:12px;' class="bnt-link">JAF Link</a> --}}

                                        @endif
                                        @if ($item->jaf_send_to== 'customer' || $item->jaf_send_to== 'Customer' )
                                            @if ($item->jaf_status == 'pending')
                                            <span class="badge badge-danger">Not Filled</span><br>
                                            @else
                                            <span class="badge badge-danger">Draft</span><br>
                                            @endif
                                                
                                            @if ($VIEW_ACCESS)
                                                @if ($hold)
                                                    <span data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span>
                                                    <span class="d-none" style='font-size:12px;' data-cand_id="{{ base64_encode($item->candidate_id)}}">JAF Sent to Customer</span>
                                                @else
                                                    <span style='font-size:12px;' data-cand_id="{{ base64_encode($item->candidate_id)}}">JAF Sent to Customer</span>
                                                    <span class="d-none" data-can_id="{{ base64_encode($item->candidate_id)}}" >On Hold</span>
                                                @endif

                                            @else
                                            
                                                <span>you have not permission for JAF Filling</span>
                                            @endif
                                        @endif
                                        @if ($item->jaf_send_to== 'candidate' || $item->jaf_send_to== 'Candidate')
                                            @if ($item->jaf_status == 'pending')
                                            <span class="badge badge-danger">Not Filled</span><br>
                                            @else
                                            <span class="badge badge-danger">Draft</span><br>
                                            @endif
                                                {{-- <span>JAF send to Candidate</span> --}}
                                            @if ($VIEW_ACCESS)
                                                @if ($hold)
                                                    <span data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span>
                                                    <span class="d-none" style='font-size:12px;' data-cand_id="{{ base64_encode($item->candidate_id)}}">JAF Sent to Candidate</span>
                                                    @if($RESEND_ACCESS)
                                                        <div><a href="javascript:;" style="font-size: 12px;;" id="resendMail{{base64_encode($item->candidate_id)}}" class="btn-link resendMail resendMail{{base64_encode($item->candidate_id)}} d-none" data-id="{{base64_encode($item->candidate_id)}}" data-resend="{{base64_encode($item->candidate_id)}}"><i class="far fa-envelope"></i> Re-send Mail</a></div>    
                                                    @endif
                                                @else
                                                    <span style='font-size:12px;' data-cand_id="{{ base64_encode($item->candidate_id)}}">JAF Sent to Candidate</span>
                                                    <span class="d-none" data-can_id="{{ base64_encode($item->candidate_id)}}" >On Hold</span>
                                                    @if($RESEND_ACCESS)
                                                        <div><a href="javascript:;" style="font-size: 12px;;" id="resendMail{{base64_encode($item->candidate_id)}}" class="btn-link resendMail resendMail{{base64_encode($item->candidate_id)}}" data-id="{{base64_encode($item->candidate_id)}}" data-resend_m="{{base64_encode($item->candidate_id)}}"><i class="far fa-envelope"></i> Re-send Mail</a></div>
                                                    @endif
                                                @endif
                                            @else
                                                <span>you have not permission for JAF Filling</span>
                                            @endif
                                        @endif
                                    @endif

                                    @if($item->jaf_status == 'filled' )
                                        <span class="badge badge-success" style="font-size: 12px;">  Completed </span><br>
                                        
                                        @if ($hold)
                                            <span data-can_id="{{ base64_encode($item->candidate_id)}}">On Hold</span>
                                            @if ($DOWNLOAD_ACCESS) 
                                                <a href="{{ url('/my/jaf-download',['id'=>base64_encode($item->candidate_id)]) }}" data-cand_id="{{ base64_encode($item->candidate_id)}}" style="font-size: 12px;" class="btn-link d-none">Download JAF </a> 
                                            @endif
                                        @else
                                            @if ($DOWNLOAD_ACCESS) 
                                                <a href="{{ url('/my/jaf-download',['id'=>base64_encode($item->candidate_id)]) }}" data-cand_id="{{ base64_encode($item->candidate_id)}}" style="font-size: 12px;" class="btn-link">Download JAF </a> 
                                            @endif
                                                <span class="d-none" data-can_id="{{ base64_encode($item->candidate_id)}}" >On Hold</span>
                                        @endif
                                         
                                    @endif
                                </td>
                                <td>
                                    {{ date('d-m-Y',strtotime($item->created_at)) }}</td>
                                <td>
                                 @if ($VIEW_ACCESS)
                                     
                                    @if($hold)
                                        <a class="d-none" href="{{ url('/my/candidates/show',['id'=>base64_encode($item->id)]) }}" data-candidate="{{ base64_encode($item->candidate_id) }}">
                                            <button class="btn btn-sm btn-info" type="button"><i class="far fa-eye"></i> View</button>
                                        </a>
                                    @else
                                        <a href="{{ url('/my/candidates/show',['id'=>base64_encode($item->id)]) }}" data-candidate="{{ base64_encode($item->candidate_id) }}">
                                            <button class="btn btn-sm btn-info" type="button"> <i class="far fa-eye"></i> View</button>
                                        </a>
                                    @endif
                                    <?php $report_status = Helper::get_report_status($item->candidate_id); ?>
                                    @if ($DELETE_ACCESS)
                                        {{-- @if ($report_status==NULL || $report_status['status']=='incomplete' || $report_status['status']=='interim') --}}
                                            <button class="btn btn-danger btn-sm deleteRow" type="button" data-id="{{ base64_encode($item->candidate_id) }}" > <i class='fa fa-trash'></i> Delete</button>
                                        {{-- @endif --}}
                                    @endif
                                    
                                    @if($item->jaf_status == 'pending' || $item->jaf_status == 'draft')
                                        {{-- @if ($item->jaf_send_to=='coc' || $item->jaf_send_to=='COC') --}}
                                            @if ($hold)
                                                @if($RESUME_ACCESS)
                                                    <button class="btn btn-success btn-md resume " type="button" data-candidate_id="{{ base64_encode($item->candidate_id) }}" data-business_id="{{ base64_encode($item->business_id) }}"><i class="fas fa-play-circle"> Resume</i></button> 
                                                @endif
                                                @if($HOLD_ACCESS)
                                                    <button class="btn btn-warning btn-md hold d-none" type="button" data-candidate="{{ base64_encode($item->candidate_id) }}" data-business="{{ base64_encode($item->business_id) }}"><i class="fas fa-pause-circle"> Hold</i></button>
                                                @endif
                                            @else
                                                @if($RESUME_ACCESS)
                                                    <button class="btn btn-success btn-md resume d-none" type="button" data-candidate_id="{{ base64_encode($item->candidate_id) }}" data-business_id="{{ base64_encode($item->business_id) }}"><i class="fas fa-play-circle"> Resume</i></button>
                                                @endif
                                                @if($HOLD_ACCESS)
                                                    <button class="btn btn-warning btn-md hold" type="button" data-candidate="{{ base64_encode($item->candidate_id) }}" data-business="{{ base64_encode($item->business_id) }}"><i class="fas fa-pause-circle"> Hold</i></button>
                                                @endif
                                            @endif
                                        {{-- @endif --}}

                                    @else
                                        <?php $report_status = Helper::get_report_status($item->candidate_id); ?>
                                        @if ($report_status==NULL || $report_status['status']=='incomplete' || $report_status['status']=='interim')
                                            @if ($hold)
                                                <button class="btn btn-success btn-md resume " type="button" data-candidate_id="{{ base64_encode($item->candidate_id) }}" data-business_id="{{ base64_encode($item->business_id) }}"><i class="fas fa-play-circle"> Resume</i></button>
                                                <button class="btn btn-warning btn-md hold d-none" type="button" data-candidate="{{ base64_encode($item->candidate_id) }}" data-business="{{ base64_encode($item->business_id) }}"><i class="fas fa-pause-circle"> Hold</i></button>
                                            @else
                                                <button class="btn btn-warning btn-md hold" type="button" data-candidate="{{ base64_encode($item->candidate_id) }}" data-business="{{ base64_encode($item->business_id) }}"><i class="fas fa-pause-circle"> Hold</i></button>
                                                <button class="btn btn-success btn-md resume d-none" type="button" data-candidate_id="{{ base64_encode($item->candidate_id) }}" data-business_id="{{ base64_encode($item->business_id) }}"><i class="fas fa-play-circle"> Resume</i></button>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                                   
                                </td>
                            </tr>
                             @endforeach
                             @else
                             <tr class="text-center">
                                <td scope="row" colspan="8"><h3 class="text-center">No record!</h3></td>
                             </tr>
                          @endif
                        
                        </tbody>
                    </table>
                   
                </div>
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
            {{-- @else
                                   
            <span><h3 class="text-center">You have no access to View Candidate Lists </h3></span>
         
          @endif --}}