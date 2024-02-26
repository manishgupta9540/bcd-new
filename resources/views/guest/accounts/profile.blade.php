@extends('layouts.guest')
@section('content')


{{-- <div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content"> --}}
      {{-- <div class="row">
         <div class="col-sm-11 col-10">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/verify/home') }}">Dashboard</a>
             </li>
             <li>
               <a href="{{ url('/verify/profile') }}">Accounts</a>
             </li>
             <li>Profile</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow col-2">
             <div class="text-right">
             <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div> --}}
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Account / Profile </h3>
               </div>
            </div>
         </div>
      </div> --}}
      {{-- <div class="row"> --}}
           
               <div class="col-md-3 content-container mt-5">
                  <!-- left-sidebar -->
                  @include('guest.accounts.left-sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-lg-8 col-sm-12 content-wrapper">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-10 offset-md-1">
                              
                                 <!-- row -->
                                 <div class="row profile-block">
                                    <div class="col-md-6">
                                       <h2 class="card-title mb-1 mt-5">Profile Information</h2>
                                       <p class="pb-border"> Your primary account info  </p>
                                    </div>
                                    {{-- <div class="col-md-6">
                                       <div class="btn-group mb-1 mt-3" style="float:right">     
                                          <button type="button" class="btn btn-lg btn-outline-danger deleteAccountBtn"> <i class="fas fa-user-times"></i> Delete Account</button>
                                       </div>
                                    </div> --}}
                                    @if ($message = Session::get('success'))
                                       <div class="col-md-12">   
                                          <div class="alert alert-success">
                                          <strong>{{ $message }}</strong> 
                                          </div>
                                       </div>
                                    @endif
                                    <div class="col-md-12">
                                     <form class="mt-2" method="post" enctype="multipart/form-data" action="{{ url('/verify/profile/update') }}">
                                       @csrf
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>First Name <span class="text-danger">*</span></label>
                                                <input class="form-control " type="text" name="first_name" value="{{ $profile->first_name }}" placeholder="Enter First Name" autocomplete="off">
                                                @if ($errors->has('first_name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('first_name') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Last Name </label>
                                                <input class="form-control " type="text" name="last_name" value="{{ $profile->last_name }}" placeholder="Enter Last Name" autocomplete="off">
                                                @if ($errors->has('last_name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('last_name') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Phone  <span class="text-danger">*</span></label><br>
                                                <input class="form-control" type="text" id="phone1" name="phone" value="{{ $profile->phone }}" autocomplete="off">
                                                @if ($errors->has('phone'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('phone') }}
                                                   </div>
                                                @endif
                                             </div>
                                          </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label> Email</label>
                                                   <input class="form-control" type="email" name="email" value="{{ $profile->email }}" readonly>
                                                   @if ($errors->has('email'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('email') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                            
                                       </div>
                                       <?php $guest_business=Helper::user_businesses(Auth::user()->business_id);?>
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label> Company Name</label>
                                                   <input class="form-control" type="text" name="company_name" value="{{$guest_business!=NULL? $guest_business->company_name : '' }}" placeholder="Enter Company Name"  autocomplete="off">
                                                   @if ($errors->has('company_name'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('company_name') }}
                                                   </div>
                                                   @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label> Job Title</label>
                                                   <input class="form-control" type="text" name="job_title" value="{{$guest_business!=NULL? $guest_business->job_title : '' }}" placeholder="Enter Your Job Title" autocomplete="off">
                                                   @if ($errors->has('job_title'))
                                                      <div class="error text-danger">
                                                         {{ $errors->first('job_title') }}
                                                      </div>
                                                   @endif
                                             </div>
                                          </div>
                                       </div>
                                       <div class="updatebtndiv">
                                          <button type="submit" class="btn btn-md btn-info updatebtn">Update</button>
                                      </div>
                                     </form>
                                    </div>
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
               {{-- </div>
            </div>
         </div> --}}
      {{-- </div> --}}
   {{-- </div>
</div> --}}
@stack('scripts')
<script>
   $(document).ready(function(){
      $('#phone1').parent().css({'width':'100%'});

      $(document).on('click', '.deleteAccountBtn', function (event) {
         var _this = $(this);
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
         swal({
            // icon: "warning",
            type: "warning",
            title: "Are You Sure Want To Delete Your Account?",
            text: "While confirming this status, Please make sure about Verification, Order & Reports!",
            dangerMode: true,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "YES",
            cancelButtonText: "CANCEL",
            closeOnConfirm: false,
            closeOnCancel: false
            },
            function(e){
               if(e==true)
               {
                  _this.attr('disabled',true);
                  if (_this.html() !== loadingText) {
                     _this.html(loadingText);
                  }

                  $.ajax({
                     type:'POST',
                     url: "{{ url('/verify/')}}"+"/delete-account",
                     data: {"_token": "{{ csrf_token() }}"},        
                     success: function (response) {   

                           window.setTimeout(function(){
                              _this.attr('disabled',false);
                              _this.html('<i class="fas fa-user-times"></i> Delete Account');
                           },2000);

                           if (response.success) { 
                              toastr.success("User Account Deleted Successfully");

                              window.setTimeout(function(){
                                 location.reload();
                              },2000);
                           } 
                           else if(response.success==false){
                              toastr.error(response.message);
                           }
                           else
                           {
                              toastr.error("Something Went Wrong !!");
                           }
                     },
                     error: function (xhr, textStatus, errorThrown) {
                           // alert("Error: " + errorThrown);
                     }
                  });
                  swal.close();
               }
               else
               {
                  swal.close();
               }
            });

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
