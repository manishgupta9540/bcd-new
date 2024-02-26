@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">				
    {{-- <div class="row">

        <div class="page-header ">

        <div class=" align-items-center">
            <div class="col">
                <h3 class="page-title">Settings / General</h3>                               
            </div>
        </div>
        </div>
    </div> --}}
    <div class="row">
      <div class="col-sm-11">
          <ul class="breadcrumb">
              <li>
                  <a href="{{ url('/app/home') }}">Dashboard</a>
              </li>
              <li>Settings</li>
              <li>General</li>
          </ul>
      </div>
<div class="row">
		    <div class=" text-left">
            <div class="">

            <div class="col-md-12 content-container">
             
            <!-- left-sidebar -->
            @include('superadmin.settings.left-sidebar') 
            <!-- ./  -->
                
        <!-- start right sec -->
        <div class="col-sm-12 content-wrapper">
            
          <div class="formCover">
          
           <section>
            <div class="row">
              <div class="col-sm-2 ">
                <h3>Logo </h3>
                <p class="text-muted">Your Company Logo (it will be used in various places - Reports, APP etc.)</p>
              </div>
              <div class="col-sm-10 ">

                <!-- row -->
                <div class="row">
                    <div class="col-sm-12 ">
                    <a class="btn-link" id="clickSelectFile" style="color: #0056b3; font-size: 16px; " href="javascript:;"><i class="fa fa-plus"></i> Add file</a>
                        <input type="file" id="fileupload" name="file" style="display:none"/> 
                    </div>
                </div>
                <!-- ./row -->
                <!-- row -->
                <div class="row">
                    <div class="col-sm-12 logoPreview " style="min-height: 120px;">
                      <div style="border:1px solid #ddd; width:220px; padding:20px; height:100px; margin-top:15px; text-align:center" id="fileResult">

                      @if($company->company_logo !=null)
                        <div class='image-area'>
                          <img src= "{{ url('/').'/uploads/company-logo/'.$company->company_logo }}"   alt='Preview'>
                          
                          <input type='hidden' name='fileID' value="">
                        </div>
                        @else

                      @endif
                    </div>

                    </div>
                </div>
                <!-- ./row -->

              </div>
            </div>
            </section>
            <!-- ./section -->

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
          url: "{{ url('/app/company/upload/logo') }}",
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

<style type="text/css">

</style>

@endsection