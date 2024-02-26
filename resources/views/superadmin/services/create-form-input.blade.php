@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Verifications/ {{ $service->name}} </h3>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         
         <!-- start right sec -->
         <div class="col-md-12 content-wrapper" style=" ">
            <div class="formCover" style="height: 100vh; background:#fff;">
               <!-- section -->
               <section>
                  <div class="col-sm-12 ">
                     
                        <!-- row -->
                        <div class="row">
                           <div class="col-md-6">
                              <h4 class="card-title mb-1 mt-3">{{ $service->name}}  </h4>
                              <p class="pb-border"> Form control overview   </p>
                           </div>
                           <div class="col-md-6 text-right">
                              <a href="{{ url('/sla/create') }}" class="mt-3 btn btn-sm btn-primary">Form Control</a></a>
                           </div>
                           <!--  -->
                           <div class="col-md-6">
                              <h4 class="card-title mb-1 mt-3"> Type - Multiple </h4>
                              <p class="pb-border"> Form control overview   </p>
                           </div>
                           <!--  -->
                           <div class="col-md-12">

                           <table class="table">
                           <thead class="thead-light">
                                 <tr>
                                    <th> Section Name </th>
                                    <th> Status </th>
                                    <th> Action </th>
                                 </tr>

                           </thead>
                           <tbody>
                           <tr>
                                 <td> Residential Address </td>
                                 <td> Action </td>
                           </tr>
                           <tr>
                                 <td> Current Address </td>
                                 <td> Action </td>
                           </tr>
                           </tbody>
                        </table>

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
