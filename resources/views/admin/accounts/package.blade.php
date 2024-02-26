@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Account / Package </h3>
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
            <li>
                <a href="{{ url('/settings/general') }}">Accounts</a>
            </li>
            <li>Package</li>
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
         
               <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('admin.accounts.left-sidebar') 
                  <!-- start right sec -->
                  </div>
                  <div class="col-md-9 content-wrapper">
                     <div class="formCover" style="height: 100vh;">
                       
                           <div class="col-sm-12 ">
                              
                            <!-- row -->
                            <div class="container mt-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="subscription text-left">
                                            <h5>Subscription</h5>
                                            <p class="pb-border"></p>
                                        </div>
                                        <div class="plan p-3 bg-white"><span>Your plan</span>
                                            <div class="d-flex justify-content-between align-items-baseline align-content-center mt-2">
                                                <h5>{{ $package->name }}</h5><button class="btn btn-primary btn-sm px-3 py-2" type="button">Upgrade plan</button>
                                            </div>
                                            <div><span class="progress-info">307/500 MAUs used</span>
                                                <div class="progress">
                                                    <div class="progress-bar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 70%;"></div>
                                                </div><span class="progress-info">MAU reset on Dec 20, 2020</span>
                                            </div>
                                        </div>
                                        <div class="plan p-3 bg-white mt-2"><span class="d-block">End subscription</span><span class="access-data d-block mb-4">Upon cancelling you will lose accesss to customer data in BCD</span><button class="btn btn-danger btn-sm px-4" type="button">Cancel your subscription</button></div>
                                    </div>
                                </div>
                            </div>
                            <!-- ./business detail -->
                                 
                          
                     </div>
                  </div>
                  <!-- end right sec -->
               </div>
            </div>
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
   
   
   });
                     
</script>  
@endsection
