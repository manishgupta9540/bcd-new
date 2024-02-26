@extends('layouts.guest')
@section('content')
<style>
    .disabled-link{
        pointer-events: none;
    }
</style>
{{-- <div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/verify/home') }}">Dashboard</a>
             </li>
             <li>
               <a href="{{ url('/verify/profile') }}">Accounts</a>
             </li>
             <li>Settings</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow">
             <div class="text-right">
             <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div>
      
      <div class="row"> --}}
           
               <div class="col-md-3 content-container mt-5">
                  <!-- left-sidebar -->
                  @include('guest.accounts.left-sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-8 content-wrapper">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <div class="col-sm-10 offset-md-1">
                           <div class="row profile-block">
                               <div class="col-sm-4">
                                    <h3>Purge Data </h3>
                                    <p class="text-muted">Purge your data (To get notification about Data purging)</p>
                               </div>
                               <div class="col-md-8">
                                   @if(Auth::user()->is_purged==0)
                                        <form method="post" action="{{url('/verify/settings/purge-data')}}" id="purge_frm">
                                            @csrf
                                            <div class="row pb-2">
                                                <div class="col-md-12">
                                                    <div class="form-check form-check-inline error-control">
                                                        <input class="form-check-input purge_check" type="checkbox" name="purge_check" id="purge_check">
                                                        <label class="form-check-label" for="purge_check">Do You Want To Purge Your Data</label>
                                                    </div><br>
                                                    <span style="" class="text-danger error_container" id="error-purge_check"></span>
                                                </div>
                                            </div>
                                            <div class="row pb-2">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                    <label>Purge Data <small>(in days)</small> <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control error-control purge_data" name="purge_data" placeholder="Ex:- 5" autocomplete="off" readonly>
                                                    <span style="" class="text-danger error_container" id="error-purge_data"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row pb-2 pBtn">
                                                
                                            </div>
                                        </form>
                                   @else
                                        <form method="post" action="{{url('/verify/settings/purge-data')}}" id="purge_frm">
                                            @csrf
                                            <div class="row pb-2">
                                                <div class="col-md-12">
                                                    <div class="form-check form-check-inline error-control">
                                                        <input class="form-check-input purge_check" type="checkbox" name="purge_check" id="purge_check" checked>
                                                        <label class="form-check-label" for="purge_check">Do You Want To Purge Your Data</label>
                                                    </div><br>
                                                    <span style="" class="text-danger error_container" id="error-purge_check"></span>
                                                </div>
                                            </div>
                                            <div class="row pb-2">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label>Purge Data <small>(in days)</small> <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control error-control purge_data" name="purge_data" value="{{Auth::user()->purge_days}}" placeholder="Ex:- 5" autocomplete="off">
                                                    <span style="" class="text-danger error_container" id="error-purge_data"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row pb-2">
                                                <div class="col-sm-6">
                                                    <div class="form-group text-right">
                                                        <button type="submit" class="btn btn-info p_save updatebtn">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                   @endif
                               </div>
                           </div>
                           
                        </div>
                        <!-- ./section -->
                        <!--  -->
                        <!-- ./section -->
                     </div>
                  </div>
                  <!-- end right sec -->
             {{--  </div>
             </div>
         </div>
      </div>
   </div>
</div> --}}
@stack('scripts')
<script>
    $(document).ready(function(){
		$('.purge_check').change(function(){
			var _this = $(this);
			var status=_this.prop('checked') ==  true ? 1 : 0
			
			if(status==1)
			{
				$('.purge_data').attr('readonly',false);
                $('.pBtn').html(`<div class="col-sm-6">
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-info p_save updatebtn">Save</button>
                                    </div>
                                </div>`);
			}
			else
			{
				$('.purge_data').attr('readonly',true);
				// $('.purge_data').val('');
                $('.pBtn').html('');
                $('.error_container').html('');
			}
		});

        $(document).on('submit', 'form#purge_frm', function (event) {
           event.preventDefault();
           //clearing the error msg
           $('p.error_container').html("");
           // $('.form-control').removeClass('border-danger');
           var form = $(this);
           var data = new FormData($(this)[0]);
           var url = form.attr("action");
           var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
           $('.p_save').attr('disabled',true);
           if($('.p_save').html!=loadingText)
           {
               $('.p_save').html(loadingText);
           }
           $.ajax({
               type: form.attr('method'),
               url: url,
               data: data,
               cache: false,
               contentType: false,
               processData: false,      
               success: function (response) {
                   window.setTimeout(function(){
                       $('.p_save').attr('disabled',false);
                       $('.p_save').html('Save');
                   },2000);

                   console.log(response);
                   if(response.success==true) {          
                       // window.location = "{{ url('/')}}"+"/sla/?created=true";
                       toastr.success('Record Updated Successfully');
                       // var order_id=response.order_id;
                       window.setTimeout(function(){
                           location.reload();
                       },2000);
                   }
                   //show the form validates error
                   if(response.success==false ) {                              
                       for (control in response.errors) {  
                           // $('.'+control).addClass('border-danger'); 
                           $('#error-' + control).html(response.errors[control]);
                       }
                   }
               },
               error: function (response) {
                   console.log(response);
               }
            //    error: function (xhr, textStatus, errorThrown) {
            //        console.log(errorThrown);
            //    }
           });
           return false;
        });
	});
</script>

{{--<script type="text/javascript">
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
   
   
   });
                     
</script>   --}}
@endsection
