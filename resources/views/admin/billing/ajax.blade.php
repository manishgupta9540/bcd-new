@php
$SEND_REQUEST_ACCESS    = false;
$VIEW_ACCESS   = false;

$CANCEL_REQUEST_ACCESS   = false;
$APPROVE_REQUEST_ACCESS   = false;
$DOWNLOAD_ACCESS   = false;
$SEND_REQUEST_ACCESS    = Helper::can_access('Send Billing Request','');
$VIEW_ACCESS   = Helper::can_access('Billing View','');

$CANCEL_REQUEST_ACCESS = Helper::can_access('Cancel Billing Request','');
$APPROVE_REQUEST_ACCESS = Helper::can_access('Complete Billing','');
$DOWNLOAD_ACCESS = Helper::can_access('Download Billing','');


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
                        <th scope="col" style="position:sticky; top:60px"><input type="checkbox" name='showhide' onchange="checkAll(this)"></th>
                        <th scope="col" style="position:sticky; top:60px">Customer</th>
                        <th scope="col" style="position:sticky; top:60px">Duration</th>
                        <th scope="col" style="position:sticky; top:60px">Description</th>
                        <th scope="col" style="position:sticky; top:60px" width="10%">Tax</th>
                        <th scope="col" style="position:sticky; top:60px" width="10%">Amount</th>
                        <th scope="col" style="position:sticky; top:60px" width="10%">Status</th>
                        <th scope="col" style="position:sticky; top:60px" width="15%">Action</th>
                    </tr>
                </thead>
                <tbody class="">
                    @if(count($billings)>0)
                        @foreach ($billings as $key => $item) 
                        {{-- Get the Billing Approval Data --}}
                        <?php
                            $billing_approval = NULL;
                            $billing_actions = [];
                            $billing_approval=Helper::billingApproval($item->id);
                            $billing_actions= Helper::get_billing_action($item->id);
                        ?>
                        <tr>
                        {{-- <td>{{$key + 1}}</td> --}}
                        <th scope="row"><input class="bulk_id" type="checkbox"  name="bulk_id[]" value="{{ $item->id }}" onchange='checkChange();'></th>
                        <td>
                            {{$item->company_name}} - {{$item->name}}<br>
                            <small class="text-muted">Invoice No.:- <b>{{$item->invoice_id }} </b></small>
                        </td>
                        <td>({{date('d M',strtotime($item->start_date))}} - {{date('d M',strtotime($item->end_date))}}) {{date('Y',strtotime($item->start_date))}}</td>
                        <td class="text-center">{{$item->description!=NULL?$item->description:'-'}}</td>
                        <td>{{$item->tax}} %</td>
                        <td><i class="fas fa-rupee-sign"></i> {{$item->total_amount}}</td>
                        <td> 
                            @if(stripos($item->status,'draft')!==false)
                                <span class="badge badge-danger" style="font-size: 12px;">{{ucwords($item->status)}} Invoice</span>
                            @elseif(stripos($item->status,'under_review')!==false)
                                <span class="badge badge-warning" style="font-size: 12px;">Under Review</span>
                            @else
                                <span class="badge badge-success" style="font-size: 12px;">Approved</span>
                            @endif
                        </td>
                        <td>
                            {{-- @if(stripos($item->status,'completed')!==false) --}}
                                @if ($DOWNLOAD_ACCESS)
                                    <span><a href="{{url('/billing/details/downloadPDF',['id'=>base64_encode($item->id)])}}" target="_blank" class="btn btn-outline-info downloadInvoice" data-id="{{$item->id}}" title="Download Invoice"> <i class="fas fa-download"></i> </a></span>   
                                @endif
                                <span><button class="btn btn-outline-info invoicePreviewBox" data-id="{{base64_encode($item->id)}}" title="Preview Invoice" type="button"> <i class="fab fa-product-hunt"></i> </button></span>
                            {{-- @endif --}}
                            <span><button class="btn btn-outline-dark mailInvoice" data-id="{{base64_encode($item->id)}}" title="Send Mail Invoice"><i class="far fa-envelope"></i></button></span>
                            @if ($VIEW_ACCESS)
                            <span><a href="{{url('/billing/details',['id'=>base64_encode($item->id)])}}" class=" btn btn-outline-dark" title="Billing Summary Details"><i class="far fa-eye"></i></a></span>
                            @endif
                            @if(stripos($item->status,'draft')!==false)
                                @if ($SEND_REQUEST_ACCESS)
                                    <span><button class="btn btn-outline-dark sendRequest" data-id="{{base64_encode($item->id)}}" title="Send approval request to customer"><i class="fas fa-paper-plane"></i></button></span>
                                @endif
                            @elseif (stripos($item->status,'under_review')!==false)
                                @if($billing_approval!=NULL)
                                    @if($billing_approval->request_approve_by_coc_id!=NULL)
                                        @if ($APPROVE_REQUEST_ACCESS)
                                            <span><button class="btn btn-outline-success complete" data-id="{{base64_encode($item->id)}}" title="Complete"><i class="far fa-check-circle"></i></button></span>
                                        @endif
                                        @if(count($billing_actions)>0)
                                            <span><button class="btn btn-outline-dark actionDetails" data-id="{{base64_encode($item->id)}}" title="Action Details"><i class="fab fa-autoprefixer"></i></button></span>
                                        @endif
                                        <?php $user=Helper::user_details($billing_approval->request_approve_by_coc_id); ?>
                                        <div class="pt-1">
                                            <a href="javascript:;" class="app_coc_details" data-id="{{base64_encode($item->id)}}" title="Approve Request Details"> <span class="badge badge-success">Approved Request Sent By Customer : <br><strong>{{$user!=NULL ? Helper::company_name($user->business_id):''}}</strong> </span></a>
                                        </div>
                                    @elseif($billing_approval->request_cancel_by!=NULL)
                                        @if ($SEND_REQUEST_ACCESS)
                                            <span><button class="btn btn-outline-dark sendRequest" data-id="{{base64_encode($item->id)}}" title="Send Request"><i class="fas fa-paper-plane"></i></button></span>
                                        @endif

                                        @if(count($billing_actions)>0)
                                            <span><button class="btn btn-outline-dark actionDetails" data-id="{{base64_encode($item->id)}}" title="Action Details"><i class="fab fa-autoprefixer"></i></button></span>
                                        @endif
                                        <?php $user_d=Helper::user_details($billing_approval->request_cancel_by); ?>
                                        @if(stripos($user_d->user_type,'customer')!==false)
                                            <div class="pt-1">
                                                <a href="javascript:;" class="cancel_details" data-id="{{base64_encode($item->id)}}" title="Return Review Details"><span class="badge badge-custom">Return Review by Customer : <br><strong>{{Helper::company_name($user_d->business_id)}}</strong> </span></a>
                                            </div>
                                        @else
                                            <div class="pt-1">
                                                <a href="javascript:;" class="cancel_details" data-id="{{base64_encode($item->id)}}" title="Return Review Details"><span class="badge badge-custom">Return Review by Customer : <br><strong>{{Helper::company_name($user_d->business_id)}}</strong></span></a>
                                            </div>
                                        @endif
                                    @else
                                        @if ($APPROVE_REQUEST_ACCESS)
                                            <span><button class="btn btn-outline-success complete" data-id="{{base64_encode($item->id)}}" title="Complete"><i class="far fa-check-circle"></i></button></span>
                                        @endif
                                        @if ($CANCEL_REQUEST_ACCESS)
                                            <span><button class="btn btn-outline-danger cancelRequest" data-id="{{base64_encode($item->id)}}" title="Return Review"><i class="far fa-times-circle"></i></button></span>
                                        @endif

                                        @if(count($billing_actions)>0)
                                            <span><button class="btn btn-outline-dark actionDetails" data-id="{{base64_encode($item->id)}}" title="Action Details"><i class="fab fa-autoprefixer"></i></button></span>
                                        @endif
                                        <div class="pt-1">
                                            <a href="javascript:;" class="send_details" data-id="{{base64_encode($item->id)}}" title="Send Request Details"><span class="badge badge-info">Request Has Been Sent to Customer : <br><strong>{{$item->company_name}}</strong></span></a>
                                        </div>
                                    @endif
                                @endif
                            @elseif (stripos($item->status,'completed')!==false)
                                        @php
                                          $quickbook =  Helper::quickbook_customer($item->business_id);
                                        @endphp
                                @if ($quickbook!=null)
                                <a href="{{ url('/quickbook/customers/invoice',['id'=>base64_encode($item->id)]) }}"><button class="btn btn-outline-dark " title="Quicksbook Invoice">Qb</button></a>
                                    
                                @endif
                                @if($billing_approval!=NULL)
                                    @if(count($billing_actions)>0)
                                        <span><button class="btn btn-outline-dark actionDetails" data-id="{{base64_encode($item->id)}}" title="Action Details"><i class="fab fa-autoprefixer"></i></button></span>
                                    @endif
                                    @if($billing_approval->request_approve_by_cust_id!=NULL)
                                        <?php $user_d=Helper::user_details($billing_approval->request_approve_by_cust_id); ?>
                                        <div class="pt-1">
                                            <a href="javascript:;" class="app_cust_details" data-id="{{base64_encode($item->id)}}" title="Approve Request Details"><span class="badge badge-success">Approved By Customer : <br><strong>{{Helper::company_name($user_d->business_id)}}</strong></span></a>
                                        </div>
                                    @endif
                                @endif
                            @endif
                        </td> 
                        </tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                        <td colspan="8">No data found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        {{-- </div> --}}
    </div>
    {{-- <div class="flex-grow-1"></div> --}}
</div>
 <div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite"></div>
    </div>
    <div class="col-sm-12 col-md-7">
      <div class=" paging_simple_numbers" >            
          {!! $billings->render() !!}
      </div>
    </div>
 </div>
 