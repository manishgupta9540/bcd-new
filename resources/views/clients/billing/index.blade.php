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

         <!-- ============Breadcrumb ============= -->
   <div class="row">
      <div class="col-sm-11">
         <ul class="breadcrumb">
         <li>
         <a href="{{ url('/my/home') }}">Dashboard</a>
         </li>
         <li>Billing</li>
         </ul>
      </div>
      <!-- ============Back Button ============= -->
      <div class="col-sm-1 back-arrow">
         <div class="text-right">
            <a href="{{ url()->previous() }}"> <i class="fas fa-arrow-circle-left fa-2x"></i></a>
         </div>
      </div>
   </div>   
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Accounts/Billing </h3>
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
                        <section>
                           <div class="col-sm-12 ">
                              
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">Billing </h4>
                                       <p class="pb-border"> Your billing overview </p>
                                    </div>
                                    <div class="col-md-6 pt-3">
                                       <div class="btn-group" style="float:right">     
                                          <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="search-drop-field pb-3" id="search-drop">
                                    <div class="row">
                                        <div class="col-12">           
                                            <div class="btn-group" style="float:right;font-size:24px;">   
                                                <a href="#" class="filter_close text-danger"><i class="far fa-times-circle"></i></a>        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 form-group mb-1">
                                            <label> From date </label>
                                            <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
                                        </div>
                                        <div class="col-md-4 form-group mb-1">
                                            <label> To date </label>
                                            <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
                                        </div>
                                        <div class="col-md-4 form-group mb-1">
                                            <label>Status</label>
                                            <select class="form-control status" name="status">
                                                <option value="">--Select--</option>
                                                <option value="draft">Draft Invoice</option>
                                                <option value="under_review">Under Review</option>
                                                <option value="completed">Approved</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-info search filterBtn" style="width:15%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                    </div>
                                </div>
                                 <div id="candidatesResult">
                                    @include('clients.billing.ajax')
                                 </div>
                                 <!-- ./billing detail -->
                                 
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
            <h4 class="modal-title">Cancel Request</h4>
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
      $(".select").select2();
      $('.filter0search').click(function(){
               $('.search-drop-field').toggle();
      });
      $('.filter_close').click(function(){
         $('.search-drop-field').toggle();
      });
      var uriNum = location.hash;
      pageNumber = uriNum.replace("#", "");
      // alert(pageNumber);
      getData(pageNumber);

      $(document).on('click','.filterBtn', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
      });

      $(document).on('change','.status, .from_date, .to_date', function (e){    
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
      var status      =    $('.status').val();      

            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

            $.ajax(
            {
               url: '?page=' + page+'&customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&status='+status,
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
      var status      =    $('.status').val(); 
      
         $.ajax(
         {
               url: "{{ url('/') }}"+'/my/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&status='+status,
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
