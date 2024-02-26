@extends('layouts.admin')
@section('content')
<style>
   .action-data
   {
      max-height: 300px;
      overflow-x: hidden;
      overflow-y: scroll;
   }

   .sweet-alert button.cancel {
        background: #DD6B55 !important;
    }

    #preview{
        /* overflow-x: hidden; */
        /* overflow-y: hidden; */
        z-index: 999;
        padding-top: 0px;
        /* margin:auto; */
    }
    #preview .modal-dialog.modal-lg{
  max-width: 90% !important;
  width: 100%;
  padding: 0px;
  left: 3.5%;
}

#preview .modal-content {
  margin: auto;
  display: block;
  width: 100%;
  max-width: 1270px;
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
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>Billing</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow">
             <div class="text-right">
             <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div>
      <div class="row">
       
            
               {{-- <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('admin.accounts.left-sidebar') 
               </div> --}}
                  <!-- start right sec -->
                  <div class="col-md-12 content-wrapper" style="background:#fff">
                    <div class="col-md-12 text-center pt-3">
                        <h2 class="text-center font-weight-bolder mb-0">[Confidential]</h2>   
            
                    </div>
                     <div class="formCover " style="height: 100vh;">
                        <!-- section -->
                        <section>
                           @include('admin.billing.menu')
                           <div class="col-sm-12">
                              
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">Billing  </h4>
                                       <p class="pb-border"> Billing overview/history  </p>
                                    </div>
                                    {{-- <div class="col-md-6 text-right">
                                       <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a>
                                    </div> --}}
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
                                        <div class="col-md-3 form-group mb-1">
                                            <label> From date </label>
                                            <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
                                        </div>
                                        <div class="col-md-3 form-group mb-1">
                                            <label> To date </label>
                                            <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
                                        </div>
                                        <div class="col-md-3 form-group mb-1">
                                            <label>Status</label>
                                            <select class="form-control status" name="status">
                                                <option value="">--Select--</option>
                                                <option value="draft">Draft Invoice</option>
                                                <option value="under_review">Under Review</option>
                                                <option value="completed">Approved</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group mb-1 level_selector">
                                          <label>Customer Name</label><br>
                                          <select class="form-control customer_list select " name="customer" id="customer">
                                              <option> All </option>
                                              @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}"> {{ $customer->company_name.' - '.$customer->name}} </option>
                                              @endforeach
                                          </select>
                                          {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-danger resetBtn" style="width:15%;padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i>  Reset </button>
                                        <button class="btn btn-info search filterBtn" style="width:15%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 form-group mt-1">
                                        <a class="btn-link" id="download_bulk_invoice" href="javascript:;"> <i class="far fa-file-pdf"></i> Download Bulk Invoice</a> 
                                        <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p>
                                    </div>
                                    <div class="col-md-9 form-group mt-1 text-right">
                                        <a class="btn-link" id="download_sample_invoice" target="__blank" href="{{url('/billing/sample')}}"> <i class="far fa-file-pdf"></i> Download Sample Invoice</a> 
                                        <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p>
                                    </div>
                                </div>
                                <div id="candidatesResult">
                                    @include('admin.billing.ajax')
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
            <h4 class="modal-title">Send Request</h4>
            <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/billing/send_request')}}" id="send_request_frm" enctype="multipart/form-data">
         @csrf
           <input type="hidden" name="id" class="id" id="id">
            <div class="modal-body">
               <div class="row">
                  <div class="col-12">
                     <div class="form-group">
                        <label for="label_name"> Customer : </label>
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
                           <label for="label_name"> Comments: <span class="text-danger">*</span></label>
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
               <button type="submit" class="btn btn-info btn-disable submit_btn">Submit </button>
               <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

<div class="modal" id="send_req_details_modal">
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
                        <label for="label_name"> Sent To: </label>
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
         <form method="post" action="{{url('/billing/cancel_request')}}" id="cancel_request_frm" enctype="multipart/form-data">
         @csrf
           <input type="hidden" name="id" class="id" id="id">
            <div class="modal-body">
               <div class="row">
                  <div class="col-12">
                     <div class="form-group">
                        <label for="label_name"> Customer: </label>
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
                           <label for="label_name"> Comments: <span class="text-danger">*</span></label>
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
               <button type="submit" class="btn btn-info btn-disable submit_btn">Submit </button>
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
         <form method="post" action="{{url('/billing/status')}}" id="approve_request_frm" enctype="multipart/form-data">
         @csrf
           <input type="hidden" name="id" class="id" id="id">
            <div class="modal-body">
               <div class="row">
                  <div class="col-12">
                     <div class="form-group">
                        <label for="label_name"> Customer Name: </label>
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
                           <label for="label_name"> Comments: <span class="text-danger">*</span></label>
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
               <button type="submit" class="btn btn-info btn-disable submit_btn">Submit </button>
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

<div class="modal" id="action_details_modal">
   <div class="modal-dialog modal-lg">
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
                           <label for="label_name"> Customer: </label>
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
    <div class="modal-dialog modal-lg">
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
      $(document).on('click','#clickSelectFile',function(){ 
      
         $('#fileupload').trigger('click');
         
      });
      
      $(document).on('click','.remove-image',function(){ 
         
         $('#fileupload').val("");
         $(this).parent('.image-area').detach();
      
      });
      
      $(document).on('change','#fileupload',function(e){ 
         // alert('test');
         //show process 
         // $("").html("Uploading...");
         $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 
      
         var fd = new FormData();
         var inputFile = $('#fileupload')[0].files[0];
         fd.append('file',inputFile);
         fd.append('_token', '{{csrf_token()}}');
         //
         
            $.ajax({
                     type: 'POST',
                     url: "{{ url('/company/upload/logo') }}",
                     data: fd,
                     processData: false,
                     contentType: false,
                     success: function(data) {
                        console.log(data);
                        if (data.fail == false) {
                        
                        //reset data
                        $('#fileupload').val("");
                        $("#fileUploadProcess").html("");
                        //append result
                        $("#fileResult").html("<div class='image-area'><img src='"+data.filePrev+"'  alt='Preview'><a class='remove-image' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.file_id+"'></div>");
            
                        } else {
            
                        $("#fileUploadProcess").html("");
                        alert("please upload valida file! allowed file type , Image, PDF, Doc, Xls and txt ");
                        console.log("file error!");
                        
                        }
                     },
                     error: function(error) {
                        console.log(error);
                        // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
                     }
            }); 
               return false;
            
      });

      //when click on complete button
      $(document).on('click','.complete',function(){

            var id = $(this).attr('data-id');

            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#approve_request_modal').modal({
               backdrop: 'static',
               keyboard: false
            });

            $.ajax({
               type:'GET',
               url: "{{ url('/')}}"+"/billing/status",
               data: {'id':id},        
               success: function (data) {        
                  $("#approve_request_frm")[0].reset();
                  if(data !='null')
                  { 
                     // alert(data.result.additional_charge_notes);
                     //check if primary data 
                     $('.id').val(id);
                     $('.cust_name').html(data.result.company_name+' - '+data.result.name);
                     $('.inv_no').html(data.result.invoice_id);
                     $('.duration').html(data.duration);
                     $('.comments').html('');
                  }
               },
               error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
               }
            });
      });

      $(document).on('submit', 'form#approve_request_frm', function (event) {
            $("#overlay").fadeIn(300);　
            event.preventDefault();
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var $btn = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
            $('.error-container').html('');
            $('.form-control').removeClass('is-invalid');
            $('.btn-disable').attr('disabled',true);
            if ($('.submit_btn').html() !== loadingText) {
                $('.submit_btn').html(loadingText);
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
                     window.setTimeout(function(){
                        $('.btn-disable').attr('disabled',false);
                        $('.submit_btn').html('Submit');
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
                        toastr.success("Request Approved Successfully");
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

            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#approve_req_details_modal').modal({
               backdrop: 'static',
               keyboard: false
            });

            $.ajax({
               type:'GET',
               url: "{{ url('/')}}"+"/billing/status",
               data: {'id':id},        
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
                     $('.comments').html(data.result1.comments!=null ? data.result1.comments : 'N/A');
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
            url: "{{ url('/')}}"+"/billing/completedetails",
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
                  $('.comments').html(data.result1.comments!=null ? data.result1.comments : 'N/A');
               }
            },
            error: function (xhr, textStatus, errorThrown) {
                  // alert("Error: " + errorThrown);
            }
         });
      });

      //when click on Send Request button
      $(document).on('click', '.sendRequest', function (event) {
            var id = $(this).attr('data-id');
            
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#send_request_modal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $.ajax({
                type: 'GET',
                url: "{{ url('/billing/send_request') }}",
                data: {'id':id},        
                success: function (data) {
                  //   console.log(data);
                    $("#send_request_frm")[0].reset();
                    if(data !='null')
                    { 
                        // alert(data.result.additional_charge_notes);
                        //check if primary data 
                        $('.id').val(id);
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

      });

      $(document).on('submit', 'form#send_request_frm', function (event) {
         $("#overlay").fadeIn(300);　
            event.preventDefault();
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var $btn = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
            $('.error-container').html('');
            $('.form-control').removeClass('is-invalid');
            $('.btn-disable').attr('disabled',true);
            if ($('.submit_btn').html() !== loadingText) {
                $('.submit_btn').html(loadingText);
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
                     window.setTimeout(function(){
                        $('.btn-disable').attr('disabled',false);
                        $('.submit_btn').html('Submit');
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
                        toastr.success("Request Sent Successfully");
                        window.setTimeout(function(){
                            location.reload();
                        },2000);
                        
                    }
                     
               },
               error: function (xhr, textStatus, errorThrown) {
                    //  alert("Error: " + errorThrown);
               }
            });

        

      });

      $(document).on('click','.send_details',function(){

            var id = $(this).attr('data-id');
            
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#send_req_details_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
         
            $.ajax({
               type:'GET',
               url: "{{ url('/')}}"+"/billing/send_request",
               data: {'id':id},        
               success: function (data) {        
               
                  if(data !='null')
                  { 
                     // alert(data.result.additional_charge_notes);
                     //check if primary data 
                     $('.id').val(id);
                     $('.cust_name').html(data.result.company_name);
                     $('.inv_no').html(data.result.invoice_id);
                     $('.duration').html(data.duration);
                     $('.attach-data').html(data.form);
                     $('.comments').html(data.result1.comments!=null ? data.result1.comments : 'N/A');
                  }
               },
               error: function (xhr, textStatus, errorThrown) {
                     alert("Error: " + errorThrown);
               }
            });
      });

      //when click on Cancel Request
      $(document).on('click', '.cancelRequest', function (event) {
         
            var id = $(this).attr('data-id');
            
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#cancel_request_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
         // if(confirm("Are you sure want to Cancel the request ?")){
            $.ajax({
               type:'GET',
               url: "{{ url('/')}}"+"/billing/cancel_request",
               data: {'id':id},        
               success: function (data) {        
               // console.log(response);
               
                     // if (response.status=='ok') { 

                     //    toastr.success("Request Cancel Successfully");
                     //    window.setTimeout(function(){
                     //       location.reload();
                     //    },2000);
                     // } 
                    $("#cancel_request_frm")[0].reset();
                    if(data !='null')
                    { 
                        // alert(data.result.additional_charge_notes);
                        //check if primary data 
                        $('.id').val(id);
                        $('.cust_name').html(data.result.company_name);
                        $('.inv_no').html(data.result.invoice_id);
                        $('.duration').html(data.duration);
                        $('.comments').html('');
                        
                    }
               },
               error: function (xhr, textStatus, errorThrown) {
                     alert("Error: " + errorThrown);
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
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
            $('.error-container').html('');
            $('.form-control').removeClass('is-invalid');
            $('.btn-disable').attr('disabled',true);
            if ($('.submit_btn').html() !== loadingText) {
                $('.submit_btn').html(loadingText);
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
                     window.setTimeout(function(){
                        $('.btn-disable').attr('disabled',false);
                        $('.submit_btn').html('Submit');
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
                    //  alert("Error: " + errorThrown);
               }
            });

        

      });

      $(document).on('click','.cancel_details',function(){

            var id = $(this).attr('data-id');
            
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#cancel_req_details_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
         
            $.ajax({
               type:'GET',
               url: "{{ url('/')}}"+"/billing/cancel_request",
               data: {'id':id},        
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
                     $('.comments').html(data.result1.comments!=null ? data.result1.comments : 'N/A');
                  }
               },
               error: function (xhr, textStatus, errorThrown) {
                     alert("Error: " + errorThrown);
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
            url: "{{ url('/')}}"+"/billing/actiondetails",
            data: {"_token": "{{ csrf_token() }}",'id':id},        
            success: function (data) {        
            
               if(data !='null')
               { 
                  // alert(data.result.additional_charge_notes);
                  //check if primary data 
                  $('.id').val(id);
                  $('.cust_name').html(data.result.company_name);
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

      $(document).on('click','#download_bulk_invoice',function(){
        var _this=$(this);
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
        $('p.load_container').html("");

        var bulk_bill_id=[];
        
       
            var bulk_id=document.querySelectorAll('.bulk_id:checked').length;

            if(bulk_id<=0)
            {
                swal({
                  title: "Please Select the Bill Check First !!",
                  text: '',
                  type: 'warning',
                  buttons: true,
                  dangerMode: true,
                  confirmButtonColor:'#003473'
               });
               
            }
            else
            {
                var j=0;
                $('.bulk_id:checked').each(function () {
                    bulk_bill_id[j++] = $(this).val();
                });

                _this.addClass('disabled-link');
                $('#loading').html(loadingText);
                var user_id     =    $(".customer_list").val();                
                var from_date   =    $(".from_date").val(); 
                var to_date     =    $(".to_date").val();  

                $.ajax(
                {
                    
                    url: "{{ url('/') }}"+'/users/setData',
                    type: "get",
                    data: {'customer_id':user_id,'from_date':from_date,'to_date':to_date,'bulk_bill_id':bulk_bill_id},
                    datatype: "html",

                })
                .done(function(data)
                {
                    window.setTimeout(function(){
                        _this.removeClass('disabled-link');
                        $('#loading').html("");
                        // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                    },2000);
                    
                    console.log(data);
                    var path = "{{ route('/bulk-bill-export')}}";
                    window.open(path);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError)
                {
                    //alert('No response from server');
                });

            }
            


      });

      $(document).on('click','.mailInvoice',function(){
            var _this =$(this);
            var id=$(this).attr('data-id');

            swal({
               // icon: "warning",
               type: "warning",
               title: "Are You Sure Want to Send the Mail Invoice ?",
               text: "",
               dangerMode: true,
               showCancelButton: true,
               confirmButtonColor: "#007358",
               confirmButtonText: "YES",
               cancelButtonText: "CANCEL",
               closeOnConfirm: false,
               closeOnCancel: false
               },
               function(e){
                  if(e==true)
                  {
                    var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Sending...';
                    _this.attr('disabled',true);
                    if (_this.html() !== loadingText) {
                        _this.html(loadingText);
                    }
                    $.ajax({
                        type:'POST',
                        url: "{{route('/billing/mailInvoice')}}",
                        data: {"_token": "{{ csrf_token() }}",'id':id},        
                        success: function (response) {        
                        // console.log(response);
                            window.setTimeout(function(){
                                _this.attr('disabled',false);
                                _this.html('<i class="far fa-envelope"></i>');
                            },2000);
                            if (response.status=='ok') {            
                                var name=response.name;
                                toastr.success("Mail Sent Succesfully to "+name);
                            } 
                            else {
                                toastr.error("Something Went Wrong !");
                            }
                        },
                        error: function (response) {
                        //    console.log(response);
                        }
                        // error: function (xhr, textStatus, errorThrown) {
                        //     alert("Error: " + errorThrown);
                        // }
                    });
                    swal.close();
                  }
                  else
                  {
                    swal.close();
                  }
               }
            );
            

      });

      // Preview Invoice
      $(document).on('click','.invoicePreviewBox',function(){
         // alert('ads');
         var id = $(this).attr('data-id');
         $('#preview_pdf').attr('src',"{{ url('/') }}"+"/billing/details/preview/"+id);
         // document.getElementById('preview_pdf').src="{{ url('/my/') }}"+"/billing/details/preview/"+id;
      
         $('#preview').modal({
                backdrop: 'static',
                keyboard: false
            });
      });

        $(document).on('click', '.resetBtn' ,function(){

            $("input[type=text], textarea").val("");
            //   $('.customer_list').val('');
            //    $('.candidate').val('');
            //    $('.user_list').val('');
            $('#customer').val(null).trigger('change');
            $('.status').val(null).trigger('change');
            // $('#user').val(null).trigger('change');
            
            var uriNum = location.hash;
            pageNumber = uriNum.replace("#","");
            // alert(pageNumber);
            getData(pageNumber);
        });
   
   });

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

    $(document).on('change','.customer_list, .from_date, .to_date,.status', function (e){    
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
                url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&status='+status,
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

    function checkAll(e) {
        var checkboxes = document.getElementsByClassName('bulk_id');
        
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

        var totalCheckbox = document.querySelectorAll('.bulk_id').length;
        var totalChecked = document.querySelectorAll('.bulk_id:checked').length;

        // When total options equals to total checked option
        if(totalCheckbox == totalChecked) {
            document.getElementsByName("showhide")[0].checked=true;
        } else {
            document.getElementsByName("showhide")[0].checked=false;
        }
    }
   
                     
</script>  
@endsection
