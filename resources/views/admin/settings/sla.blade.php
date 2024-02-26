@extends('layouts.admin')
@section('content')
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
                 <a href="{{ url('/settings/general') }}">Accounts</a>
             </li>
             <li>SLA</li>
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
                  <h3 class="page-title">Settings / SLA </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         
            
            <div class="col-md-3 content-container">
            <!-- left-sidebar -->
                @include('admin.settings.left-sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style=" background:#fff;">
                     <div class="formCover" style="height: 100vh; ;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 ">
                              
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">SLA  </h4>
                                       <p class="pb-border"> Your customer's SLA   </p>
                                    </div>
                                    <div class="col-md-6 text-right">
                                       <a href="{{ url('/settings/sla/create') }}" class="mt-3 btn btn-sm btn-primary"> <i class="fa fa-plus"></i> Create new</a>
                                    </div>

                                    <div class="col-md-12">

                                    <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col" style="position:sticky; top:60px">Name</th>
                                            <th scope="col" style="position:sticky; top:60px">Comapny</th>
                                            <th scope="col" style="position:sticky; top:60px">Services</th>
                                            <th scope="col" style="position:sticky; top:60px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($sla as $item)
                                        <tr>
                                            <td>{{ $item->title }}</td>
                                            <td> {{ $item->first_name.' '.$item->last_name }}</td>
                                            <td>Address verifications </td>
                                            <td> <a href="" class="btn-link"> Edit </a> </td>
                                        </tr>
                                        @endforeach      
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
