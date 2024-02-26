@extends('layouts.admin')
@section('content')
<style type="text/css">
   ul,li
   {
     list-style-type: none;
   }
   .disabled-link{
      pointer-events: none;
   }
   </style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>
                 <a href="{{ url('/admin/vendor') }}">Vendors</a>
             </li>
             <li>Profile</li>
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
                  @include('admin.vendors.left-sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 ">
                              
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-12">
                                       <h4 class="card-title mb-1 mt-3">Profile Information <small class="text-muted"> ( {{ Helper::user_name($profile->user_id)  }} - {{ $profile->company_name!=null?$profile->company_name: 'Individual' }}) </small> </h4> 
                                       <p class="pb-border"> Your Vendor account info  </p>
                                    </div>
                                    <div class="col-md-12">

                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>First name <span class="text-danger">*</span></label>
                                                <input class="form-control " type="text" name="first_name" value="{{ $profile->first_name }}" readonly>
                                                @if ($errors->has('first_name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('first_name') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Last name</label>
                                                <input class="form-control number_only" type="text" name="pincode" value="{{ $profile->last_name }}" readonly>
                                                @if ($errors->has('last_name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('last_name') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>
                                       
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Phone <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" id="phone1" name="phone" value="{{ $profile->phone }}" readonly>
                                                @if ($errors->has('phone'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('phone') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label> Email <span class="text-danger">*</span></label>
                                                <input class="form-control" type="email" name="email" value="{{ $profile->email }}" readonly>
                                                @if ($errors->has('email'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('email') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>
                                       
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                
                                             </div>
                                          </div>
                                         
                                       </div>

                                    </div>
                                 </div>
                                 
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
