@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">SLA / Create </h3>
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
             <li>
               <a href="{{ url('/sla') }}">SLA</a>
             </li>
             <li>Create New</li>
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
         <div class=" text-left">
            <div class="">
               <div class="col-md-12 content-container">
                  <!-- left-sidebar -->
                  @include('admin.settings.left-sidebar') 
                  <!-- start right sec -->
                  <div class="col-sm-12 content-wrapper">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 ">
                              
                                <!-- row -->
                                 <div class="row">
                                    <div class="col-md-12">
                                       <h4 class="card-title mb-1 mt-3">Craete a new SLA </h4>
                                       <p class="pb-border"> Create the SLA with service </p>
                                    </div>
                                    <div class="col-md-12">
                                    <form method="post" action="{{ route('/settings/sla/save') }}">
                                        @csrf
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Select a Customer <span class="text-danger">*</span></label>
                                                <select class="form-control " type="text" name="customer" >
                                                <option value=""> -Select- </option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id}}">{{ $customer->first_name.' '.$customer->last_name }}</option>
                                                @endforeach
                                                </select>
                                                @if ($errors->has('customer'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('customer') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Select services <span class="text-danger">*</span></label>
                                                <select class="form-control " type="text" name="service" >
                                                <option value=""> -Select- </option>
                                                    @foreach($services as $service)
                                                    <option value="{{ $service->id}}">{{ $service->name }}</option>
                                                    @endforeach
                                                </select>
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
                                                <label>SLA Name <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="name" >
                                                @if ($errors->has('name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('name') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label> SLA Code <span class="text-danger">*</span></label>
                                                <input class="form-control" type="code" name="code" >
                                                @if ($errors->has('code'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('code') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>
                                       
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <input type="submit" class="btn btn-primary mt-3" value="Submit">
                                             </div>
                                          </div>
                                         
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
