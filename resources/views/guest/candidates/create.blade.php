@extends('layouts.guest')
@section('content')
<style type="text/css">
   .form-group label {
   font-size: 16px;
   font-weight: 600;
   color: #663399;
   margin-bottom: 4px;
   }
   .form-control {
   border: initial;
   outline: initial !important;
   background: #fff;
   border: 1px solid #ced4da;
   color: #47404f;
   }
   .col-md-6 {
   margin-top: 10px;
   }
   .form-control:focus {
   color: #665c70;
   background-color: #fff;
   border-color: #ced4da;
   outline: 0;
   box-shadow: 0 0 0 0.2rem rgb(255 255 255);
   }
</style>
<!-- =============== Left side End ================-->
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li><a href="{{ url('/guest/home') }}">Dashboard</a></li>
             <li><a href="{{ url('/guest/candidates') }}">Candidate</a></li>
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
         <div class="card newrequestcard">
            <div class="card-body verify">
               <img src="{{asset('guest/images/newrequest.jpg')}}">
               <h3 class="card-title mb-3 verifying1"> Who are you verifying? </h3>
               <p style="font-size: 16px">Enter their information so we can verify their employment. BCD can verify any employee.</p>
               <form class="mt-2" method="post" id="addCandidateForm" action="{{ url('/guest/candidates/store') }}" enctype="multipart/form-data">
                  @csrf
                  <div class="row requestrow">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="first_name">First Name <span class="text-danger">*</span></label>
                           <input type="text" class="form-control first_name" name="first_name" id="first_name" placeholder="Enter their first name">
                           <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-first_name"></p>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="last_name">Middle Name </label>
                           <input type="text" class="form-control middle_name" name="middle_name" id="middle_name" placeholder="Enter their middle name">
                           <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-middle_name"></p>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="last_name">Last Name </label>
                           <input type="text" class="form-control last_name" name="last_name" id="last_name" placeholder="Enter their last name">
                           <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-last_name"></p>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="father_name">Father Name <span class="text-danger">*</span></label>
                           <input type="text" class="form-control father_name" name="father_name" id="father_name" placeholder="Enter their father's name">
                           <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-father_name"></p>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="aadhar">Aadhar Number </label>
                           <input type="text" class="form-control aadhar" id="aadhar" placeholder="Enter Aadhar Number">
                           <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-aadhar"></p>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="name">Gender <span class="text-danger">*</span></label>
                           <select name="gender" class="form-control gender" >
                                 <option value="">-Select-</option>
                                 <option value="Male">Male</option>
                                 <option value="Female">Female</option>
                                 <option value="Other">Other</option>
                           </select>
                           <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gender"></p>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone <span class="text-danger">*</span></label><br>
                            <input type="hidden"  id="code" name ="primary_phone_code" value="91" >
                            <input type="hidden"  id="iso" name ="primary_phone_iso" value="in" >
                            <input type="tel" name ="phone" id="phone1" class="number_only form-control phone" style='display:block;' value="{{ old('phone') }}">
                            <small class="text-muted"></small>
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-phone"></p>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="email">Contact Email </label>
                           <input type="email" class="form-control email" name="email" id="email" placeholder="Please enter their best contact email">
                           <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>  
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                           <p style="font-size: 12px">Providing their date of birth will potentially enable us to process the request significantly faster.</p>
                           <input type="date" class="form-control dob" name="dob" id="dob">
                           <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dob"></p>
                        </div>
                     </div>
                     <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-success submit" style="width: 20%;padding: 14px;margin: 18px 0px;font-size:16px;">Save</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
</div>
</div><!-- ============ Search UI Start ============= -->
<!-- ============ Search UI End ============= -->
<script>
   $(document).ready(function(){
      $('#phone1').parent().css({'width':'100%'});

      $(document).on('submit', 'form#addCandidateForm', function (event) {
            event.preventDefault();
            //clearing the error msg
            $('p.error_container').html("");
            $('.form-control').removeClass('border-danger');
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
            $('.submit').attr('disabled',true);
            if($('.submit').html()!=loadingText)
            {
               $('.submit').html(loadingText);
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
                        $('.submit').attr('disabled',false);
                        $('.submit').html('Save');
                    },3000);

                    console.log(response);
                    if(response.success==true) {          
                        // window.location = "{{ url('/')}}"+"/sla/?created=true";
                        var candidate_id=response.candidate_id;
                        toastr.success('Candidate Created Successfully');
                        window.setTimeout(function(){
                           //  window.location = "{{ url('/guest/')}}"+"/candidates/";
                           window.location = "{{ url('/guest/')}}"+"/candidates/verification/"+candidate_id;
                        },2000);
                    }
                    //show the form validates error
                    if(response.success==false ) {                              
                        for (control in response.errors) {  
                            $('.'+control).addClass('border-danger'); 
                            $('#error-' + control).html(response.errors[control]);
                        }
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });
            return false;
      });

   });
    
</script>
@endsection