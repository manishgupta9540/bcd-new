@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">				
    <div class="row">

        <div class="page-header ">

        <div class=" align-items-center">
            <div class="col">
                <h3 class="page-title">Settings / General</h3>                               
            </div>
        </div>
        </div>
    </div>
<div class="row">
		    <div class=" text-left">
            <div class="">

            <div class="col-md-12 content-container">
             
            <!-- left-sidebar -->
            @include('settings.left-sidebar') 
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
                      <div style="border:1px solid #ddd; width:180px; height:100px; margin-top:15px;" id="fileResult">

                      @if($company->company_logo !=null)
                        <div class=''>
                          <img src= "{{ url('/').'/uploads/company-logo/'.$company->company_logo }}"   alt='Preview'>
                          <a class='remove-image' href='javascript:;' style='display: inline;'>&#215;</a>
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

<style type="text/css">
.files input {
    outline: 2px dashed #92b0b3;
    outline-offset: -10px;
    -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
    transition: outline-offset .15s ease-in-out, background-color .15s linear;
    padding: 120px 0px 85px 35%;
    text-align: center !important;
    margin: 0;
    width: 100% !important;
}
.files input:focus{     outline: 2px dashed #92b0b3;  outline-offset: -10px;
    -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
    transition: outline-offset .15s ease-in-out, background-color .15s linear; border:1px solid #92b0b3;
 }
.files{ position:relative}
.files:after {  pointer-events: none;
    position: absolute;
    top: 60px;
    left: 0;
    width: 50px;
    right: 0;
    height: 56px;
    content: "";
    background-image: url(https://image.flaticon.com/icons/png/128/109/109612.png);
    display: block;
    margin: 0 auto;
    background-size: 100%;
    background-repeat: no-repeat;
}
.color input{ background-color:#f1f1f1;}
.files:before {
    position: absolute;
    bottom: 10px;
    left: 0;  pointer-events: none;
    width: 100%;
    right: 0;
    height: 57px;
    content: " or drag it here. ";
    display: block;
    margin: 0 auto;
    color: #2ea591;
    font-weight: 600;
    text-transform: capitalize;
    text-align: center;
}
/*  */
.content-container .aside-nav {
    width: 280px;
    position: absolute;
    top: 0;
    bottom: 0;
    border-right: 1px solid hsla(0,0%,64%,.2);
    background-color: #f8f9fa;
    padding-top: 0px;
    padding-bottom: 10px;
    z-index: 4;
    min-height: 100vh;
}
.content-container .aside-nav ul {
    overflow-y: auto;
    height: 90%;
    list-style: none;
    margin: 0;
    padding: 0;
}
.content-container .aside-nav a {
    padding: 15px;
    display: block;
    color: #000311;
}
.content-container .aside-nav .active a {
    background: #fff;
    border-top: 1px solid hsla(0,0%,64%,.2);
    border-bottom: 1px solid hsla(0,0%,64%,.2);
}
.content-container .aside-nav .active i {
    float: right;
    font-size: 18px;
}
.content-container .content-wrapper {
    padding: 25px 25px 25px 15px;
    margin-left: 295px;
    margin-top: 1px;
    background-color: #fff;
}
</style>

@endsection