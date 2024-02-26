@php
$SUMMARY_REQUEST_ACCESS    = false;
$PREVIEW_ACCESS   = false;
$APPROVAL_ACTION_REQUEST_ACCESS    = false;
$ACTION_REQUEST_ACCESS   = false;
$APPROVE_REQUEST_ACCESS   = false;
$DOWNLOAD_ACCESS   = false;
$SUMMARY_REQUEST_ACCESS    = Helper::can_access('Billing Summary Details','/my');
$PREVIEW_ACCESS   = Helper::can_access('Preview Billing','/my');
$APPROVAL_ACTION_REQUEST_ACCESS    = Helper::can_access('Billing Approval Action','/my');
$ACTION_REQUEST_ACCESS = Helper::can_access('Action Details','/my');
$APPROVE_REQUEST_ACCESS = Helper::can_access('Billing Approval Status','/my');
$DOWNLOAD_ACCESS = Helper::can_access('Download Invoice','/my');


// $REPORT_ACCESS   = false;
// $VIEW_ACCESS   = false;SLA
@endphp 
<div class="row">
    <div class="col-md-12">
        {{-- <div class="table-responsive"> --}}
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        {{-- <th>#</th> --}}
                        <th scope="col" style="position:sticky; top:60px">Bill To</th>
                        <th scope="col" style="position:sticky; top:60px">Duration</th>
                        <th scope="col" style="position:sticky; top:60px">Description</th>
                        <th width="15%" scope="col" style="position:sticky; top:60px">Amount</th>
                        <th scope="col" style="position:sticky; top:60px">Status</th>
                        <th width="15%" scope="col" style="position:sticky; top:60px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- <tr>
                        <td>1-Jan-2021 to 15-Jan-2021</td>
                        <td>Total Checks: 29 </td>
                        <td>INR 9999.00</td>
                        <td> <a href="" class="btn-link"> Download Invoice </a> 
                        <a href="" class="btn-link "> View </a> 
                        </td>
                    </tr>     
                    <tr>
                    <td>16-Jan-2021 to 31-Jan-2021</td>
                        <td>Total Checks: 20 </td>
                        <td>INR 999.00</td>
                        <td> 
                            <a href="" class="btn-link"> Download Invoice </a> 
                            <a href="" class="btn-link "> View </a> 
                        </td>
                    </tr>            --}}

                    @if(count($billings)>0)
                        @foreach ($billings as $key => $item)
                        <?php
                            $billing_approval = NULL;
                            $billing_actions = [];
                            $billing_approval=Helper::billingApproval($item->id);
                            $billing_actions= Helper::get_billing_action($item->id);
                        ?>
                        <tr>
                        {{-- <td>{{$key + 1}}</td> --}}
                        <td>
                            {{$item->company_name}} - {{$item->name}}<br>
                            <small class="text-muted">Invoice No.:- <b>{{$item->invoice_id }} </b></small>
                        </td>
                        <td>({{date('d M',strtotime($item->start_date))}} - {{date('d M',strtotime($item->end_date))}}) {{date('Y',strtotime($item->start_date))}}</td>
                        <td class="text-center">{{$item->description!=NULL?$item->description:'-'}}</td>
                        <td><i class="fas fa-rupee-sign"></i> {{$item->total_amount}}</td>
                        <td>
                            @if(stripos($item->status,'under_review')!==false)
                                <span class="badge badge-warning" style="font-size: 12px;">Under Review</span>
                            @else
                                <span class="badge badge-success" style="font-size: 12px;">Approved</span>
                            @endif
                        </td>
                        <td> 
                          
                           
                           
                                
                           
                            
                                
                            
                            
                                
                           
                            @if ($DOWNLOAD_ACCESS)
                                <span><a href="{{url('/my/billing/details/downloadPDF',['id'=>base64_encode($item->id)])}}" target="_blank" class="btn btn-outline-info" title="Download Invoice"> <i class="fas fa-download"></i> </a></span>
                            @endif
                            @if ($PREVIEW_ACCESS)
                                <span><button class="btn btn-outline-info invoicePreviewBox" data-id="{{base64_encode($item->id)}}" title="Preview Invoice" type="button"> <i class="fab fa-product-hunt"></i> </button></span>
                            @endif
                            @if ($SUMMARY_REQUEST_ACCESS)
                                <a href="{{url('/my/billing/details',['id'=>base64_encode($item->id)])}}" class=" btn btn-outline-dark" title="Billing Summary"><i class="far fa-eye"></i></a>
                            @endif
                            {{-- @if(stripos($item->status,'completed')!==false) --}}
                            {{-- @endif --}}
                            @if(stripos($item->status,'under_review')!==false)
                                @if($billing_approval!=NULL)
                                    <?php 
                                        $cancel_by = 0;
                                        $cancel_by = Auth::user()->parent_id;
                                        if($billing_approval->request_cancel_by!=NULL)
                                        {
                                            $user=Helper::user_details($billing_approval->request_cancel_by);
                                            // $user=DB::table('users')->where(['id'=>$billing_approval->request_cancel_by])->first();
                                            $cancel_by=$user->business_id;
                                        }

                                    ?>
                                    @if($billing_approval->request_cancel_by==NULL && $billing_approval->request_approve_by_coc_id==NULL)
                                        <div class="pt-1">
                                            @if ($APPROVAL_ACTION_REQUEST_ACCESS)
                                                <span><button class="btn btn-outline-success approveRequest" data-id="{{base64_encode($billing_approval->id)}}" data-type="{{base64_encode('approve')}}" title="Approve Request"><i class="far fa-check-circle"></i></button></span>
                                                <span><button class="btn btn-outline-danger cancelRequest" data-id="{{base64_encode($billing_approval->id)}}" data-type="{{base64_encode('cancel')}}" title="Return Review"><i class="far fa-times-circle"></i></button></span>
                                            @endif
                                            @if(count($billing_actions)>0)
                                                @if ($ACTION_REQUEST_ACCESS)
                                                    <span><button class="btn btn-outline-dark actionDetails" data-id="{{base64_encode($item->id)}}" title="Action Details"><i class="fab fa-autoprefixer"></i></button></span>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="pt-1">
                                            <?php $user_d=Helper::user_details($billing_approval->request_sent_by);?>
                                            <a href="javascript:;" class="send_request" data-id="{{base64_encode($billing_approval->id)}}" title="Send Request Details"><span class="badge badge-info">Send Request by Customer : <br><strong>{{Helper::company_name($user_d->business_id)}}</strong></span></a>
                                        </div>
                                    @elseif($billing_approval->request_approve_by_coc_id!=NULL)
                                        @if(count($billing_actions)>0)
                                            @if ($ACTION_REQUEST_ACCESS)
                                                    <span><button class="btn btn-outline-dark actionDetails" data-id="{{base64_encode($item->id)}}" title="Action Details"><i class="fab fa-autoprefixer"></i></button></span>
                                            @endif                                        
                                        @endif
                                        <div class="pt-1">
                                            @if ($APPROVE_REQUEST_ACCESS)
                                                <?php $user_d=Helper::user_details($billing_approval->request_approve_by_coc_id); ?>
                                                <a href="javascript:;" class="app_coc_details" data-id="{{base64_encode($billing_approval->id)}}" data-type="{{base64_encode('approve')}}" title="Approve Request Details"><span class="badge badge-success">Approve Request Sent to Customer : <br><strong>{{Helper::company_name($user_d->parent_id)}}</strong></span></a>
                                            @endif
                                        </div>
                                    @elseif($billing_approval->request_cancel_by!=NULL)
                                        @if(count($billing_actions)>0)
                                            @if ($ACTION_REQUEST_ACCESS)
                                                <span><button class="btn btn-outline-dark actionDetails" data-id="{{base64_encode($item->id)}}" title="Action Details"><i class="fab fa-autoprefixer"></i></button></span>
                                            @endif                                        
                                        @endif
                                        <?php $user_d=Helper::user_details($billing_approval->request_cancel_by); ?>
                                        @if ($APPROVE_REQUEST_ACCESS)
                                            @if(stripos($user_d->user_type,'customer')!==false)
                                                <div class="pt-1">
                                                    <a href="javascript:;" class="cancel_details" data-id="{{base64_encode($billing_approval->id)}}" data-type="{{base64_encode('cancel')}}" title="Return Review Details"><span class="badge badge-custom">Return Review by Customer : <br><strong>{{Helper::company_name($user_d->business_id)}}</strong></span></a>
                                                </div>
                                            @else
                                                <div class="pt-1">
                                                    <a href="javascript:;" class="cancel_details" data-id="{{base64_encode($billing_approval->id)}}" data-type="{{base64_encode('cancel')}}" title="Return Review Details"><span class="badge badge-custom">Return Review to Customer : <br><strong>{{Helper::company_name($user_d->parent_id)}}</span></a>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            @elseif (stripos($item->status,'completed')!==false)
                                @if($billing_approval!=NULL)
                                    @if ($ACTION_REQUEST_ACCESS)
                                        @if(count($billing_actions)>0)
                                            <span><button class="btn btn-outline-dark actionDetails" data-id="{{base64_encode($item->id)}}" title="Action Details"><i class="fab fa-autoprefixer"></i></button></span>
                                        @endif
                                    @endif 
                                    @if($billing_approval->request_approve_by_cust_id!=NULL)
                                        <?php $user_d=Helper::user_details($billing_approval->request_approve_by_cust_id); ?>
                                        @if ($APPROVE_REQUEST_ACCESS)
                                            <div class="pt-1">
                                                <a href="javascript:;" class="app_cust_details" data-id="{{base64_encode($billing_approval->id)}}" title="Approve Request Details"><span class="badge badge-success">Approve By Customer : <br><strong>{{Helper::company_name($user_d->business_id)}}</strong></span></a>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </td>
                        </tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                        <td colspan="6">No data found</td>
                        </tr>
                    @endif
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
      <div class="paging_simple_numbers">
          {!! $billings->render() !!}
      </div>
    </div>
 </div>