@extends('layouts.client')
@section('content')
<style>
    .action-data
    {
       max-height: 300px;
       overflow-x: hidden;
       overflow-y: scroll;
    }
 </style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Account / Billing </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/my/home') }}">Dashboard</a>
             </li>
             <li>
                <a href="{{ url('/my/billing') }}">Billing</a>
            </li>
             <li>Summary</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow">
             <div class="text-right">
             <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div>

     {{-- <div class="row">
        <div class="page-header ">
           <div class=" align-items-center">
              <div class="col">
                 <h3 class="page-title">Accounts/Billing/Summary </h3>
              </div>
           </div>
        </div>
     </div> --}}
      <div class="row">
         
            
               {{-- <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('clients.accounts.sidebar') 
               </div> --}}
                  <!-- start right sec -->
                  <div class="col-md-12 content-wrapper" style="background:#fff">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <?php
                            $billing_approval = NULL;
                            $billing_actions = [];
                            $billing_approval=Helper::billingApproval($billing->id);
                            $billing_actions= Helper::get_billing_action($billing->id);
                            $billing_discount = Helper::billing_discount($billing->id);
                        ?>
                        <section>
                           <div class="col-sm-12 ">
                              
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-12">
                                       <h4 class="card-title mb-1 mt-3">Billing Summary</h4>
                                       <p class="pb-border"></p>
                                    </div>
                                    {{-- <div class="col-md-6 text-right">
                                       <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a>
                                    </div> --}}
                                    {{-- <div class="col-md-6 pt-3">
                                       <div class="btn-group" style="float:right">     
                                          <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>
                                       </div>
                                    </div> --}}
                                 </div>
                                 {{-- <div class="search-drop-field pb-3" id="search-drop">
                                    <div class="row">
                                        <div class="col-md-3 form-group mb-1">
                                            <label> From date </label>
                                            <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
                                        </div>
                                        <div class="col-md-3 form-group mb-1">
                                            <label> To date </label>
                                            <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
                                        </div>
                                        <div class="col-md-3 form-group mb-1 level_selector">
                                          <label>Check Name</label><br>
                                          <select class="form-control customer_list select " name="customer" id="customer">
                                              <option> All </option>
                                              @if(count($services)>0)
                                                @foreach ($services as $item)
                                                <option value="{{ $item->id }}"> {{$item->name}} </option>
                                                @endforeach
                                              @endif
                                          </select>
                                      </div>
                                        <div class="col-md-2">
                                        <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                          <label style="font-size: 16px;"> Bill To : <strong>{{Helper::company_name($billing->business_id)}} ({{Helper::user_name($billing->business_id)}})</strong></label>
                                          {{-- <label style="font-size: 16px;"> <b> </b></label> --}}
                                          {{-- <input type="hidden" name="customer" value="{{ $sla->business_id }}"> --}}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <div class="form-group">
                                          <label style="font-size: 16px;"> Duration : <strong>({{date('d M',strtotime($billing->start_date))}} - {{date('d M',strtotime($billing->end_date))}}) {{date('Y',strtotime($billing->start_date))}}</strong></label>
                                          {{-- <label style="font-size: 16px;"> <b> </b></label> --}}
                                          {{-- <input type="hidden" name="customer" value="{{ $sla->business_id }}"> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                          <label style="font-size: 16px;"> Status : @if(stripos($billing->status,'draft')!==false) <span class="badge badge-danger" style="font-size: 12px;">Draft Invoice</span> @elseif(stripos($billing->status,'under_review')!==false) <span class="badge badge-warning" style="font-size: 12px;">Under Review</span> @else <span class="badge badge-success" style="font-size: 12px;">Approved</span> @endif</label>
                                        </div>
                                        <div class="form-group">
                                            <span class="text-muted" style="font-size: 16px;"> 
                                               
                                                Action : 
                                                <span><a href="{{url('/my/billing/details/downloadPDF',['id'=>base64_encode($billing->id)])}}" target="_blank" class="btn btn-outline-info" title="Download Invoice"> <i class="fas fa-download"></i> </a></span>

                                                <span><button class="btn btn-outline-info invoicePreviewBox" data-id="{{base64_encode($billing->id)}}" title="Preview Invoice" type="button"> <i class="fab fa-product-hunt"></i> </button></span>
                                                
                                                @if(stripos($billing->status,'under_review')!==false)
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
                                                        @if($billing_approval->request_cancel_by==NULL &&  $billing_approval->request_approve_by_coc_id==NULL)
                                                            <button class="btn btn-outline-success approveRequest" data-id="{{base64_encode($billing_approval->id)}}" data-type="{{base64_encode('approve')}}" title="Approve Request"><i class="far fa-check-circle"></i></button>
                                                            <button class="btn btn-outline-danger cancelRequest" data-id="{{base64_encode($billing_approval->id)}}" data-type="{{base64_encode('cancel')}}" title="Return Review"><i class="far fa-times-circle"></i></button>
                                                            @if(count($billing_actions)>0)
                                                                <span><button class="btn btn-outline-dark actionDetails" data-id="{{base64_encode($billing->id)}}" title="Action Details"><i class="fab fa-autoprefixer"></i></button></span>
                                                            @endif
                                                            <div class="pt-1">
                                                                <?php $user_d=Helper::user_details($billing_approval->request_sent_by);?>
                                                                <a href="javascript:;" class="send_request" data-id="{{base64_encode($billing_approval->id)}}" title="Send Request Details"><span class="badge badge-info">Send Request by Customer : <br><strong>{{Helper::company_name($user_d->business_id)}}</strong></span></a>
                                                            </div>
                                                        @elseif($billing_approval->request_approve_by_coc_id!=NULL)
                                                            @if(count($billing_actions)>0)
                                                                <span><button class="btn btn-outline-dark actionDetails" data-id="{{base64_encode($billing->id)}}" title="Action Details"><i class="fab fa-autoprefixer"></i></button></span>
                                                            @endif
                                                            <?php $user_d=Helper::user_details($billing_approval->request_approve_by_coc_id); ?>
                                                            <a href="javascript:;" class="app_coc_details" data-id="{{base64_encode($billing_approval->id)}}" data-type="{{base64_encode('approve')}}" title="Approve Request Details"><span class="badge badge-success">Approve Request Sent to Customer : <br><strong>{{Helper::company_name($user_d->parent_id)}}</strong></span></a>
                                                        @elseif($billing_approval->request_cancel_by!=NULL)
                                                            @if(count($billing_actions)>0)
                                                                <span><button class="btn btn-outline-dark actionDetails" data-id="{{base64_encode($billing->id)}}" title="Action Details"><i class="fab fa-autoprefixer"></i></button></span><br>
                                                                <div class="pt-1">
                                                            @endif
                                                            <?php $user_d=Helper::user_details($billing_approval->request_cancel_by); ?>
                                                            @if(stripos($user_d->user_type,'customer')!==false)
                                                                <a href="javascript:;" class="cancel_details" data-id="{{base64_encode($billing_approval->id)}}" data-type="{{base64_encode('cancel')}}" title="Return Review Details"><span class="badge badge-custom">Return Review by Customer: <br><strong>{{Helper::company_name($user_d->business_id)}}</strong></span></a>
                                                            @else
                                                                <a href="javascript:;" class="cancel_details" data-id="{{base64_encode($billing_approval->id)}}" data-type="{{base64_encode('cancel')}}" title="Return Review Details"><span class="badge badge-custom">Return Review to Customer: <br><strong>{{Helper::company_name($user_d->parent_id)}}</strong></span></a>
                                                            @endif

                                                            @if(count($billing_actions)>0)
                                                                </div>
                                                            @endif

                                                        @endif
                                                    @endif
                                                @else
                                                    
                                                    @if($billing_approval!=NULL)
                                                        @if(count($billing_actions)>0)
                                                            <span><button class="btn btn-outline-dark actionDetails" data-id="{{base64_encode($billing->id)}}" title="Action Details"><i class="fab fa-autoprefixer"></i></button></span>
                                                        @endif
                                                        @if($billing_approval->request_approve_by_cust_id!=NULL)
                                                            <?php $user_d=Helper::user_details($billing_approval->request_approve_by_cust_id); ?>
                                                            <div class="pt-1">
                                                                <a href="javascript:;" class="app_cust_details" data-id="{{base64_encode($billing_approval->id)}}" title="Approve Request Details"><span class="badge badge-success">Approve By Customer : <br><strong>{{Helper::company_name($user_d->business_id)}}</strong></span></a>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <div class="form-group">
                                            <label style="font-size: 16px;"> Subtotal : <strong><i class="fas fa-rupee-sign"></i> {{$billing->sub_total}} </strong></label>
                                        </div>
                                        @if($billing_discount!=NULL)
                                            <div class="form-group">
                                                <?php
                                                    $content = '';

                                                    $discount_type = '';

                                                    $discount = '';

                                                    if(stripos($billing_discount->discount_type,'flat')!==false)
                                                    {
                                                        $discount_type = 'Fixed Amount';

                                                        $discount = '₹ '.$billing_discount->discount;
                                                    }
                                                    else if(stripos($billing_discount->discount_type,'percentage')!==false)
                                                    {
                                                        $discount_type = 'Percentage';

                                                        $discount = $billing_discount->discount.'%';
                                                    }

                                                    $content = 'Discount Reference : '.ucwords($billing_discount->discount_ref).' - Wise, Discount Type : '.$discount_type.', Discount : '.$discount;
                                                ?>
                                                @if(stripos($billing_discount->discount_type,'flat')!==false)
                                                    <label style="font-size: 16px;"> Discount <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="{{$content}}"></i> : <strong><i class="fas fa-rupee-sign"></i> {{$billing_discount->discount_amt}}</strong></label>
                                                @elseif(stripos($billing_discount->discount_type,'percentage')!==false)
                                                    <label style="font-size: 16px;"> Discount <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="{{$content}}"></i> : <strong><i class="fas fa-rupee-sign"></i> {{$billing_discount->discount_amt}}</strong></label>
                                                @else
                                                    <label style="font-size: 16px;"> Discount : <strong><i class="fas fa-rupee-sign"></i> 0.00</strong></label>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label style="font-size: 16px;"> Tax <small>({{$billing->tax}}%)</small>: <strong><i class="fas fa-rupee-sign"></i> {{$billing->tax_amount}}</strong></label>
                                            </div>
                                            <div class="form-group">
                                                <label style="font-size: 16px;"> Total Amount : <i class="fas fa-rupee-sign"></i> <strong>{{$billing->total_amount}} </strong></label>
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <label style="font-size: 16px;"> Discount : <strong><i class="fas fa-rupee-sign"></i> 0.00</strong></label>
                                            </div>
                                            <div class="form-group">
                                                <label style="font-size: 16px;"> Tax <small>({{$billing->tax}}%)</small>: <strong><i class="fas fa-rupee-sign"></i> {{$billing->tax_amount}}</strong></label>
                                            </div>
                                            <div class="form-group">
                                                <label style="font-size: 16px;"> Total Amount : <i class="fas fa-rupee-sign"></i> <strong>{{$billing->total_amount}} </strong></label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div id="candidatesResult">
                                    @include('clients.billing.billing_details_ajax')
                                </div>
                                 <!-- ./business detail -->
                                 
                           </div>
                        </section>
                        <!-- ./section -->
                        <!--  -->
                        <!-- ./section -->
                     </div>
                  </div>
                  <!-- end right sec -->
      </div>
   </div>
</div>
<div class="modal" id="send_request_modal">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Send Request Details</h4>
             <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
            <input type="hidden" name="id" class="id" id="id">
             <div class="modal-body">
                <div class="row">
                   <div class="col-12">
                      <div class="form-group">
                         <label for="label_name"> Sent By: </label>
                         <span class="cust_name"></span>
                     </div>
                   </div>
                   <div class="col-12">
                      <div class="form-group">
                         <label for="label_name"> Invoice No: </label>
                         <span class="inv_no"></span>
                     </div>
                   </div>
                   <div class="col-12">
                      <div class="duration">
                         
                      </div>
                   </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Comments: </label>
                            <span class="comments"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="attach-data">
                        </div>
                    </div>
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
             </div>
       </div>
    </div>
</div>

<div class="modal" id="cancel_request_modal">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Return Review</h4>
             <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('/my/billing/approve_status')}}" id="cancel_request_frm" enctype="multipart/form-data">
          @csrf
            <input type="hidden" name="id" class="id" id="id">
            <input type="hidden" name="type" class="type" id="type">
             <div class="modal-body">
                <div class="row">
                   <div class="col-12">
                      <div class="form-group">
                         <label for="label_name"> Bill To: </label>
                         <span class="cust_name"></span>
                     </div>
                   </div>
                   <div class="col-12">
                      <div class="form-group">
                         <label for="label_name"> Invoice No: </label>
                         <span class="inv_no"></span>
                     </div>
                   </div>
                   <div class="col-12">
                      <div class="duration">
                         
                      </div>
                   </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Comments </label>
                            <textarea id="comment" name="comments" class="form-control comments" placeholder=""></textarea>
                            {{-- <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/> --}}
                            <p style="margin-bottom: 2px;" class="text-danger error-container error-comments" id="error-comments"></p> 
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Attachments: <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,pdf are accepted"></i></label>
                            <input type="file" name="attachment[]" id="attachment" multiple class="form-control attachment">
                            <p style="margin-bottom: 2px;" class="text-danger error-container error-attachment" id="error-attachment"></p>  
                        </div>
                    </div>
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-disable">Submit </button>
                <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
             </div>
          </form>
       </div>
    </div>
</div>
 
 <div class="modal" id="cancel_req_details_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Return Review Details</h4>
                <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
            </div>
            <!-- Modal body -->
            <input type="hidden" name="id" class="id" id="id">
            <input type="hidden" name="type" class="type" id="type">
                <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Return Review By: </label>
                            <span class="cust_name"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Invoice No: </label>
                            <span class="inv_no"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="duration">
                            
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Comments: </label>
                            <span class="comments"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="attach-data">
                        </div>
                    </div>
                </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
 </div>

 <div class="modal" id="approve_request_modal">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Approve Request</h4>
             <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('/my/billing/approve_status')}}" id="approve_request_frm" enctype="multipart/form-data">
          @csrf
            <input type="hidden" name="id" class="id" id="id">
            <input type="hidden" name="type" class="type" id="type">
             <div class="modal-body">
                <div class="row">
                   <div class="col-12">
                      <div class="form-group">
                         <label for="label_name"> Bill To: </label>
                         <span class="cust_name"></span>
                     </div>
                   </div>
                   <div class="col-12">
                      <div class="form-group">
                         <label for="label_name"> Invoice No: </label>
                         <span class="inv_no"></span>
                     </div>
                   </div>
                   <div class="col-12">
                      <div class="duration">
                         
                      </div>
                   </div>
                   <div class="col-12">
                      <div class="form-group">
                         <label for="label_name"> Rating: </label><br>
                         <fieldset class="rate">
                            <input type="radio" id="rating10" name="rating" value="5" /><label class="stars_1" for="rating10" title="5 stars"></label>
                            <input type="radio" id="rating9" name="rating" value="4.5" /><label class="half stars_1" for="rating9" title="4 1/2 stars"></label>
                            <input type="radio" id="rating8" name="rating" value="4" /><label class="stars_1" for="rating8" title="4 stars"></label>
                            <input type="radio" id="rating7" name="rating" value="3.5" /><label class="half stars_1" for="rating7" title="3 1/2 stars"></label>
                            <input type="radio" id="rating6" name="rating" value="3" /><label  class="stars_1" for="rating6" title="3 stars"></label>
                            <input type="radio" id="rating5" name="rating" value="2.5" /><label class="half stars_1" for="rating5" title="2 1/2 stars"></label>
                            <input type="radio" id="rating4" name="rating" value="2" /><label class="stars_1" for="rating4" title="2 stars"></label>
                            <input type="radio" id="rating3" name="rating" value="1.5" /><label class="half stars_1" for="rating3" title="1 1/2 stars"></label>
                            <input type="radio" id="rating2" name="rating" value="1" /><label class="stars_1" for="rating2" title="1 star"></label>
                            <input type="radio" id="rating1" name="rating" value=".5" /><label class="half stars_1" for="rating1" title="1/2 star"></label>
                        </fieldset>
                        <p style="margin-bottom: 2px;" class="text-danger error-container error-rating" id="error-rating"></p>  
                      </div>
                   </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Comments: </label>
                            <textarea id="comment" name="comments" class="form-control comments" placeholder=""></textarea>
                            {{-- <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/> --}}
                            <p style="margin-bottom: 2px;" class="text-danger error-container error-comments" id="error-comments"></p> 
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Attachments: <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,pdf are accepted"></i></label>
                            <input type="file" name="attachment[]" id="attachment" multiple class="form-control attachment">
                            <p style="margin-bottom: 2px;" class="text-danger error-container error-attachment" id="error-attachment"></p>  
                        </div>
                    </div>
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-disable">Submit </button>
                <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
             </div>
          </form>
       </div>
    </div>
 </div>

 <div class="modal" id="approve_req_details_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Approve Request Details</h4>
                <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
            </div>
            <!-- Modal body -->
            <input type="hidden" name="id" class="id" id="id">
            <input type="hidden" name="type" class="type" id="type">
                <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Approve By: </label>
                            <span class="cust_name"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Invoice No: </label>
                            <span class="inv_no"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="duration">
                            
                        </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label for="label_name"> Rating: </label>
                        <span class="stars"></span>
                      </div>
                  </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Comments: </label>
                            <span class="comments"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="attach-data">
                        </div>
                    </div>
                </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
 </div>

 <div class="modal" id="app_cust_req_details_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Approve Request Details</h4>
                <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
            </div>
            <!-- Modal body -->
            <input type="hidden" name="id" class="id" id="id">
                <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Company Name: </label>
                            <span class="cust_name"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Invoice No: </label>
                            <span class="inv_no"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="duration">
                            
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Comments: </label>
                            <span class="comments"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="attach-data">
                        </div>
                    </div>
                </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
 </div>

 <div class="modal fade" id="action_details_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Action Details</h4>
                <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
            </div>
            <!-- Modal body -->
            <input type="hidden" name="id" class="id" id="id">
                <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Bill To: </label>
                            <span class="cust_name"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Invoice No: </label>
                            <span class="inv_no"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="duration">
                            
                        </div>
                    </div>
                    <div class="col-12 pt-2">
                        <h5 class="text-muted">Action Details:-</h5>
                        <p class="pb-border"></p>
                        <div class="action-data">
 
                        </div>
                    </div>
                </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
 </div>

 <div class="modal fade" id="preview">
    <div class="modal-dialog modal-lg" style="max-width: 90% !important;">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Invoice Preview</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          
             <div class="modal-body">
             <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                <iframe 
                    src="" 
                    style="width:100%; height:500px;" 
                    frameborder="0" id="preview_pdf">
                </iframe>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="button" class="btn btn-danger back" data-dismiss="modal">Close</button>
             </div>
       </div>
    </div>
 </div>

@stack('scripts')
<script type="text/javascript">
   //
   $(document).ready(function() {
   //
//    $(document).on('click','#clickSelectFile',function(){ 
   
//        $('#fileupload').trigger('click');
       
//    });
   
//    $(document).on('click','.remove-image',function(){ 
       
//        $('#fileupload').val("");
//        $(this).parent('.image-area').detach();
   
//    });
   
//    $(document).on('change','#fileupload',function(e){ 
//       // alert('test');
//       //show process 
//       // $("").html("Uploading...");
//       $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 
   
//       var fd = new FormData();
//       var inputFile = $('#fileupload')[0].files[0];
//       fd.append('file',inputFile);
//       fd.append('_token', '{{csrf_token()}}');
//       //
      
//       $.ajax({
//                type: 'POST',
//                url: "{{ url('/company/upload/logo') }}",
//                data: fd,
//                processData: false,
//                contentType: false,
//                success: function(data) {
//                   console.log(data);
//                   if (data.fail == false) {
                  
//                   //reset data
//                   $('#fileupload').val("");
//                   $("#fileUploadProcess").html("");
//                   //append result
//                   $("#fileResult").html("<div class='image-area'><img src='"+data.filePrev+"'  alt='Preview'><a class='remove-image' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.file_id+"'></div>");
      
//                   } else {
      
//                   $("#fileUploadProcess").html("");
//                   alert("please upload valida file! allowed file type , Image, PDF, Doc, Xls and txt ");
//                   console.log("file error!");
                  
//                   }
//                },
//                error: function(error) {
//                   console.log(error);
//                   // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
//                }
//             }); 
//          return false;
      
//       });

    $(".select").select2();
    $('.filter0search').click(function(){
            $('.search-drop-field').toggle();
    });

    var uriNum = location.hash;
    pageNumber = uriNum.replace("#", "");
    // alert(pageNumber);
    getData(pageNumber);

   $(document).on('change','.from_date',function() {

      var from = $('.from_date').datepicker('getDate');
      var to_date   = $('.to_date').datepicker('getDate');

      if($('.to_date').val() !=""){
      if (from > to_date) {
      alert ("Please select appropriate date range!");
      $('.from_date').val("");
      $('.to_date').val("");

      }
      }

   });
   //
   $(document).on('change','.to_date',function() {

      var to_date = $('.to_date').datepicker('getDate');
      var from   = $('.from_date').datepicker('getDate');
         if($('.from_date').val() !=""){
         if (from > to_date) {
         alert ("Please select appropriate date range!");
         $('.from_date').val("");
         $('.to_date').val("");
         
         }
         }

   });

   $(document).on('click','.filterBtn', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
    });

    $(document).on('change','.customer_list, .from_date, .to_date', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
    });

    $(document).on('click', '.pagination a,.searchBtn',function(event){
        //loader
        $("#overlay").fadeIn(300);　
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        event.preventDefault();
        var myurl = $(this).attr('href');
        var page  = $(this).attr('href').split('page=')[1];
        getData(page);
    });

        // when click on send request button

        $(document).on('click','.send_request',function(){
            var id = $(this).attr('data-id');
                $('.form-control').removeClass('is-invalid');
                $('.error-container').html('');
                $('.btn-disable').attr('disabled',false);
                $('#send_request_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $.ajax({
                    type: 'POST',
                    url: "{{ url('/my/billing/send_request') }}",
                    data: {"_token": "{{ csrf_token() }}",'id':id},        
                    success: function (data) {
                        //   console.log(data);
                        if(data !='null')
                        { 
                            // alert(data.result.additional_charge_notes);
                            //check if primary data 
                            $('.id').val(id);
                            $('.cust_name').html(data.result.company_name);
                            $('.inv_no').html(data.result.invoice_id);
                            $('.duration').html(data.duration);
                            $('.attach-data').html(data.form);
                            $('.comments').html(data.result.comments!=null ? data.result.comments : 'N/A');
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        // alert("Error: " + errorThrown);
                    }
                });
        });

        //when click on Approve Request button
        $(document).on('click', '.approveRequest', function (event) {
         
         var id = $(this).attr('data-id');
         var type = $(this).attr('data-type');
         //  alert(user_id);
         // if(confirm("Are you sure want to approve the request ?")){
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#approve_request_modal').modal({
               backdrop: 'static',
               keyboard: false
            });

            $.ajax({
               type:'GET',
               url: "{{ url('/my/')}}"+"/billing/approve_status",
               data: {'id':id,'type':type},        
               success: function (data) {        
               
               
                     // if (response.status=='ok') { 
                     //    if(response.success)
                     //    {
                     //       if(response.type=='approve')
                     //          toastr.success("Request Sent Successfully");
                     //    }
                     //    else
                     //    {
                     //       toastr.success("Billing Status has already Completed !!");
                     //    }
                     //    window.setTimeout(function(){
                     //       location.reload();
                     //    },2000);
                     // } 
                     $("#approve_request_frm")[0].reset();
                     if(data !='null')
                     { 
                        // alert(data.result.additional_charge_notes);
                        //check if primary data 
                        $('.id').val(id);
                        $('.type').val(type);
                        $('.cust_name').html(data.result.company_name);
                        $('.inv_no').html(data.result.invoice_id);
                        $('.duration').html(data.duration);
                        $('.comments').html('');
                     }

               },
               error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
               }
            });

         // }
         // return false;

        });

        $(document).on('submit', 'form#approve_request_frm', function (event) {
                $("#overlay").fadeIn(300);　
                event.preventDefault();
                var form = $(this);
                var data = new FormData($(this)[0]);
                var url = form.attr("action");
                var $btn = $(this);
                $('.error-container').html('');
                $('.form-control').removeClass('is-invalid');
                $('.btn-disable').attr('disabled',true);
                $.ajax({
                type: form.attr('method'),
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,        
                success: function (data) {        
                        // console.log(data);
                        window.setTimeout(function(){
                            $('.btn-disable').attr('disabled',false);
                        },2000);
                        if (data.fail && data.error_type == 'validation') {
                                //$("#overlay").fadeOut(300);
                                for (control in data.errors) {
                                    $('input[name='+control+']').addClass('is-invalid');
                                    $('textarea[name='+control+']').addClass('is-invalid');
                                    $('.error-' + control).html(data.errors[control]);
                                }
                        } 
                        if (data.fail && data.error == 'yes') {
                            
                            $('#error-all').html(data.message);
                        }
                        if (data.fail == false) {
                            toastr.success("Request Approval Sent Successfully");
                            window.setTimeout(function(){
                                location.reload();
                            },2000);
                            
                        }
                        
                },
                error: function (xhr, textStatus, errorThrown) {
                        // alert("Error: " + errorThrown);
                }
                });
        });

        $(document).on('click','.app_coc_details',function(){

            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');

            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#approve_req_details_modal').modal({
            backdrop: 'static',
            keyboard: false
            });

            $.ajax({
                type:'GET',
                url: "{{ url('/my/')}}"+"/billing/approve_status",
                data: {'id':id,'type':type},        
                success: function (data) {        
                
                    if(data !='null')
                    { 
                        // alert(data.result.additional_charge_notes);
                        //check if primary data 
                        $('.id').val(id);
                        $('.stars').css({'font-size':'16px'});
                        $('.stars').html(data.stars);
                        $('.cust_name').html(data.cust_name);
                        $('.inv_no').html(data.result.invoice_id);
                        $('.duration').html(data.duration);
                        $('.attach-data').html(data.form);
                        $('.comments').html(data.result.comments!=null ? data.result.comments : 'N/A');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                        // alert("Error: " + errorThrown);
                }
            });
        });

        $(document).on('click','.app_cust_details',function(){

                var id = $(this).attr('data-id');

                $('.form-control').removeClass('is-invalid');
                $('.error-container').html('');
                $('.btn-disable').attr('disabled',false);
                $('#app_cust_req_details_modal').modal({
                backdrop: 'static',
                keyboard: false
                });

                $.ajax({
                    type:'POST',
                    url: "{{ url('/my/')}}"+"/billing/completedetails",
                    data: {"_token": "{{ csrf_token() }}",'id':id},        
                    success: function (data) {        
                    
                        if(data !='null')
                        { 
                            // alert(data.result.additional_charge_notes);
                            //check if primary data 
                            $('.id').val(id);
                            $('.cust_name').html(data.result.company_name+' - '+data.result.name);
                            $('.inv_no').html(data.result.invoice_id);
                            $('.duration').html(data.duration);
                            $('.attach-data').html(data.form);
                            $('.comments').html(data.result.comments!=null ? data.result.comments : 'N/A');
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                            // alert("Error: " + errorThrown);
                    }
                });
        });

        //when click on Cancel Request
        $(document).on('click', '.cancelRequest', function (event) {
         
            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');

            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#cancel_request_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
            //  alert(user_id);
            // if(confirm("Are you sure want to Cancel the request ?")){
            $.ajax({
                type:'GET',
                url: "{{ url('/my/')}}"+"/billing/approve_status",
                data: {'id':id,'type':type},        
                success: function (data) {        
                // console.log(data);
                
                    // if (response.status=='ok') { 
                    //    if(response.success)
                    //    {
                    //       if(response.type=='cancel')
                    //          toastr.success("Request Cancel Successfully");
                    //    }
                    //    else
                    //    {
                    //       toastr.success("Billing Approval has already Completed !!");
                    //    }
                    //    window.setTimeout(function(){
                    //       location.reload();
                    //    },2000);
                    // } 
                    $("#cancel_request_frm")[0].reset();
                    if(data !='null')
                    {   
                            //check if primary data 
                            $('.id').val(id);
                            $('.type').val(type);
                            $('.cust_name').html(data.result.company_name);
                            $('.inv_no').html(data.result.invoice_id);
                            $('.duration').html(data.duration);
                            $('.comments').html('');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });

            // }
            // return false;

        });

        $(document).on('submit', 'form#cancel_request_frm', function (event) {
                $("#overlay").fadeIn(300);　
                event.preventDefault();
                var form = $(this);
                var data = new FormData($(this)[0]);
                var url = form.attr("action");
                var $btn = $(this);
                $('.error-container').html('');
                $('.form-control').removeClass('is-invalid');
                $('.btn-disable').attr('disabled',true);
                $.ajax({
                    type: form.attr('method'),
                    url: url,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,        
                    success: function (data) {        
                        // console.log(data);
                        window.setTimeout(function(){
                            $('.btn-disable').attr('disabled',false);
                        },2000);
                        if (data.fail && data.error_type == 'validation') {
                                //$("#overlay").fadeOut(300);
                                for (control in data.errors) {
                                    $('input[name='+control+']').addClass('is-invalid');
                                    $('textarea[name='+control+']').addClass('is-invalid');
                                    $('.error-' + control).html(data.errors[control]);
                                }
                        } 
                        if (data.fail && data.error == 'yes') {
                            
                            $('#error-all').html(data.message);
                        }
                        if (data.fail == false) {
                            toastr.success("Request Cancel Successfully");
                            window.setTimeout(function(){
                                location.reload();
                            },2000);
                            
                        }
                        
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        // alert("Error: " + errorThrown);
                    }
                });

            

        });

        $(document).on('click','.cancel_details',function(){

            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');

            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#cancel_req_details_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        
            $.ajax({
                type:'GET',
                url: "{{ url('/my/')}}"+"/billing/approve_status",
                data: {'id':id,'type':type},        
                success: function (data) {        
                
                if(data !='null')
                { 
                    // alert(data.result.additional_charge_notes);
                    //check if primary data 
                    $('.id').val(id);
                    $('.cust_name').html(data.cust_name);
                    $('.inv_no').html(data.result.invoice_id);
                    $('.duration').html(data.duration);
                    $('.attach-data').html(data.form);
                    $('.comments').html(data.result.comments!=null ? data.result.comments : 'N/A');
                }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });
        });

        //when click on action details
        $(document).on('click','.actionDetails',function(){

            var id = $(this).attr('data-id');

            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#action_details_modal').modal({
            backdrop: 'static',
            keyboard: false
            });

            $.ajax({
            type:'POST',
            url: "{{ url('/my/')}}"+"/billing/actiondetails",
            data: {"_token": "{{ csrf_token() }}",'id':id},        
            success: function (data) {        
            
                if(data !='null')
                { 
                    // alert(data.result.additional_charge_notes);
                    //check if primary data 
                    $('.id').val(id);
                    $('.cust_name').html(data.result.company_name+' ('+data.result.name+')');
                    $('.inv_no').html(data.result.invoice_id);
                    $('.duration').html(data.duration);
                    $('.action-data').html(data.form);
                    $('.comments').html('');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
            }
            });
        });

        // Preview Invoice
        $(document).on('click','.invoicePreviewBox',function(){
            // alert('ads');
            var id = $(this).attr('data-id');
            $('#preview_pdf').attr('src',"{{ url('/my/') }}"+"/billing/details/preview/"+id);
            // document.getElementById('preview_pdf').src="{{ url('/my/') }}"+"/billing/details/preview/"+id;
        
            $('#preview').modal({
                    backdrop: 'static',
                    keyboard: false
                });
        });


});
    function getData(page){
        //set data
        var user_id     =    $(".customer_list").val();                

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();      

            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

            $.ajax(
            {
                url: '?page=' + page+'&customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date,
                type: "get",
                datatype: "html",
            })
            .done(function(data)
            {
                $("#candidatesResult").empty().html(data);
                $("#overlay").fadeOut(300);
                //debug to check page number
                location.hash = page;
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                alert('No response from server');

            });

    }

    function setData(){

        var user_id     =    $(".customer_list").val();                
      //   var check       =    $(".check option:selected").val();

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();    
        
            $.ajax(
            {
                url: "{{ url('/') }}"+'/my/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date,
                type: "get",
                datatype: "html",
            })
            .done(function(data)
            {
            console.log(data);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                //alert('No response from server');
            });

    }
   
                     
</script>  
@endsection
