@extends('layouts.guest')
@section('content')
<!-- ============ Search UI Start ============= -->
<style type="text/css">
   .selectoption {
   margin-top: -23px;
   margin-left: -16px;
   }
   .row.requestrow12 {
   margin-top: -18px;
   }
   .servicesfield {
   background-color: #f7f9fd;
   padding-left: 34px;
   padding-right: 34px;
   padding-bottom: 30px;
   padding-top: 30px;
   }
   input[type=text], select, textarea {
   width: 100%;
   padding: 12px;
   border: 1px solid #ccc;
   border-radius: 4px;
   resize: vertical;
   }
   label {
   padding: 12px 12px 12px 0;
   display: inline-block;
   }
   input[type=submit] {
   background-color: #04AA6D;
   color: white;
   padding: 12px 20px;
   border: none;
   border-radius: 4px;
   cursor: pointer;
   float: right;
   }
   input[type=submit]:hover {
   background-color: #45a049;
   }
   .container {
   border-radius: 5px;
   background-color: #f2f2f2;
   padding: 20px;
   }
   .col-25 {
   float: left;
   width: 25%;
   margin-top: 6px;
   }
   .col-75 {
   float: left;
   width: 75%;
   margin-top: 6px;
   }
   /* Clear floats after the columns */
   .row:after {
   content: "";
   display: table;
   clear: both;
   }
   /* Responsive layout - when the screen is less than 600px wide, make the two columns stack on top of each other instead of next to each other */
   @media screen and (max-width: 600px) {
   .col-25, .col-75, input[type=submit] {
   width: 100%;
   margin-top: 0;
   }
   }
   .form-group label {
   font-size: 16px;
   font-weight: 600;
   color: #663399;
   margin-bottom: 4px;
   }
   .serviceverify {
   margin-left: 17px;
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
   span.selectservices {
   margin-left: 7px;
   font-size: 15px;
   }
</style>
<!-- ============ Search UI End ============= -->
<!-- =============== Left side End ================-->
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11 col-8">
             <ul class="breadcrumb">
             <li><a href="{{ url('/guest/home') }}">Dashboard</a></li>
             <li><a href="{{ url('/guest/candidates') }}">Candidate</a></li>
             <li>Verification</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow col-4">
             <div class="text-right">
             <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div>
      <div class="row">
         <div class="card newrequestcard">
            <div class="card-body verify">
               <h3 class="card-title mb-3 verifying1" style="font-size: 19px"> Candidate Details </h3>
               <div class="row requestrow1">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Name :</label>
                        <input type="text" class="form-control" value="{{$candidate->name}}" readonly>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Email :</label>
                        <input type="email" class="form-control" value="{{$candidate->email}}" readonly>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Contact Number :</label>
                        <input type="text" class="form-control" value="{{$candidate->phone}}" readonly>
                     </div>
                  </div>
               </div>
               <hr>
               <h3 class="card-title mb-3 verifying1" style="font-size: 19px;margin-top:18px"> Start Verification </h3>
               <p style="font-size: 14px;margin-top: -14px;">Fill the required details</p>
               @if($guest_v!=NULL)
                  <form class="mt-2" method="post" id="verificationForm" action="{{ url('/guest/candidates/verification/store') }}" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" name="candidate_id" value="{{base64_encode($candidate->id)}}">
                     <div class="row requestrow12">
                        <div class="col-md-12 ">
                           <label style="font-size: 19px;">Select Services</label><br>
                           <div class="selectoption">
                              @foreach($services as $service)
                                 <?php $check_service=Helper::get_verification_service($guest_v->id,$service->id);?>
                                 <label class="checkbox-inline serviceverify">
                                    <input type="checkbox" class="services_list" name="services[]" value="{{ $service->id}}" data-string="{{ $service->name  }}" id="inlineCheckbox-{{ $service->id}}" data-verify="{{$service->verification_type}}" @if($check_service!=NULL) checked @endif><span class="selectservices">{{ $service->name  }}</span>
                                 </label>
                              @endforeach
                           </div>
                           <p style="margin-bottom: 10px;" class="text-danger error_container" id="error-services"></p>
                        </div>
                     </div>
                     <div class="servicesfield">
                        @foreach($services as $service)
                           <?php $guest_service=Helper::get_verification_service($guest_v->id,$service->id);?>
                           @if($guest_service!=NULL)
                              <?php 
                                    $service_number_array=json_decode($guest_service->service_number,true);
                                    $keys=array_keys($service_number_array);
                                    $values=array_values($service_number_array);
                              ?>
                              @if($service->name=='Bank Verification' || $service->name=='Passport' )
                                 <?php 
                                    $k_arr=explode('_',$keys[0]);
                                    $keys1=implode(' ',$k_arr);

                                    $k_arr1=explode('_',$keys[1]);
                                    $keys2=implode(' ',$k_arr1);
                                 ?>
                                 <div class="row" id='row-{{$guest_service->service_id}}'>
                                    <div class="col-sm-3">
                                       <label for="acard">{{ucwords($keys1)}}</label>
                                    </div>
                                    <div class="col-sm-3">
                                       <input type="text" id="service_unit-{{$guest_service->service_id}}" name='service_unit-{{$guest_service->service_id}}' value="{{$values[0]}}">
                                       <p style='margin-top:2px;margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-{{$guest_service->service_id}}'></p>
                                    </div>
                                    <div class="col-sm-3">
                                       <label for="acard">{{ucwords($keys2)}}</label>
                                    </div>
                                    <div class="col-sm-3">
                                       <input type="text" id="notes-{{$guest_service->service_id}}" name='notes-{{$guest_service->service_id}}' placeholder='{{$service->name}}' value="{{$values[1]}}">
                                       <p style='margin-top:2px;margin-bottom: 2px;' class='text-danger error_container' id='error-notes-{{$guest_service->service_id}}'></p>
                                    </div>
                                 </div>
                              @else
                                 <div class="row" id='row-{{$guest_service->service_id}}'>
                                    <?php 
                                       $k_arr=explode('_',$keys[0]);
                                       $keys=implode(' ',$k_arr);
                                    ?>
                                    <div class="col-sm-3">
                                       <label for="acard">{{ucwords($keys)}}</label>
                                    </div>
                                    <div class="col-sm-9">
                                       <input type="text" id="service_unit-{{$guest_service->service_id}}" name='service_unit-{{$guest_service->service_id}}' value="{{$values[0]}}">
                                    </div>
                                 </div>
                              @endif
                           @endif
                        @endforeach
                     </div>
                     <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary submit" style="width: 20%;padding: 14px;margin: 18px 0px;font-size:16px;">Next</button>
                     </div>
                  </form>
               @else
               <form class="mt-2" method="post" id="verificationForm" action="{{ url('/guest/candidates/verification/store') }}" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="candidate_id" value="{{base64_encode($candidate->id)}}">
                  <div class="row requestrow12">
                     <div class="col-md-12 ">
                        <label style="font-size: 19px;">Select Services</label><br>
                        <div class="selectoption">
                           @foreach($services as $service)
                              <label class="checkbox-inline serviceverify">
                                 <input type="checkbox" class="services_list" name="services[]" value="{{ $service->id}}" data-string="{{ $service->name  }}" id="inlineCheckbox-{{ $service->id}}" data-verify="{{$service->verification_type}}" ><span class="selectservices">{{ $service->name  }}</span>
                              </label>
                           @endforeach
                        </div>
                        <p style="margin-bottom: 10px;" class="text-danger error_container" id="error-services"></p>
                     </div>
                  </div>
                  <div class="servicesfield">
                     
                  </div>
                  <div class="col-md-12 text-center">
                     <button type="submit" class="btn btn-primary submit" style="width: 20%;padding: 14px;margin: 18px 0px;font-size:16px;">Next</button>
                  </div>
               </form>
               @endif
            </div>
         </div>
      </div>
   </div>
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
</div>
</div>
<script>
   // alert("hi");
   $(document).ready(function(){

       $(".services_list").change(function() {
         //  alert('hi');
           if(this.checked)
           {
               var id =  $(this).attr("value");
               var text =  $(this).attr("data-string");
               var verify =$(this).attr("data-verify");
               
               if(text=='Bank Verification')
                   $(".servicesfield").append("<div class='row' id='row-"+id+"'> <div class='col-sm-3'><label>"+'Account Number'+"</label></div> <div class='col-sm-3'><input class='' type='text' name='service_unit-"+id+"' ><p style='margin-top:2px;margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div> <div class='col-sm-3'><label>"+'Ifsc Code'+"</label></div> <div class='col-sm-3'><input class='' type='text' name='notes-"+id+"' placeholder='IFSC Code' ><p style='margin-top:2px;margin-bottom: 2px;' class='text-danger error_container' id='error-notes-"+id+"'></p></div></div>");
               else if(text=='Passport')
                   $(".servicesfield").append("<div class='row' id='row-"+id+"'><div class='col-sm-3'><label>"+'File Number'+"</label></div> <div class='col-sm-3'> <input class='' type='text' name='service_unit-"+id+"' ><p style='margin-top:2px;margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div> <div class='col-sm-3'><label>"+'Dob'+"</label></div> <div class='col-sm-3'><input class=' commonDatepicker' type='text' name='notes-"+id+"' placeholder='' ><p style='margin-top:2px;margin-bottom: 2px;' class='text-danger error_container' id='error-notes-"+id+"'></p> </div></div>");
               else if(text=='Driving')
                   $(".servicesfield").append("<div class='row' id='row-"+id+"'><div class='col-sm-3'> <label>"+'DL'+' '+'Number'+"</label></div> <div class='col-sm-9'><input class='' type='text' name='service_unit-"+id+"' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div></div>");
               // else if(text=='Telecom')
               //     $(".servicesfield").append("<div class='row' id='row-"+id+"'><div class='col-sm-6'><label>"+'Mobile'+' '+'Number'+"</label></div><div class='col-sm-6'><input class='' type='text' name='service_unit-"+id+"' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></div></div>");
               else
                   $(".servicesfield").append("<div class='row' id='row-"+id+"'> <div class='col-sm-3'> <label for='acard'>"+text+' '+'Number'+"</label> </div> <div class='col-sm-9'> <input type='text' id='service_unit-"+id+"' name='service_unit-"+id+"'> <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'> </p></div> </div>");
           }
           else
           {
               var id =  $(this).attr("value");
               $("div#row-"+id).remove();
           }
       
       });
       // $('.submit').on('click', function() {
       //         var $this = $(this);
       //         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
       //         if ($(this).html() !== loadingText) {
       //         $this.data('original-text', $(this).html());
       //         $this.html(loadingText);
       //         }
       //         setTimeout(function() {
       //         $this.html($this.data('original-text'));
       //         }, 3000);
       // });

       $(document).on('submit', 'form#verificationForm', function (event) {
           event.preventDefault();
           //clearing the error msg
           $('p.error_container').html("");
           // $('.form-control').removeClass('border-danger');
           var form = $(this);
           var data = new FormData($(this)[0]);
           var url = form.attr("action");
           var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
           $('.submit').attr('disabled',true);
           if($('.submit').html!=loadingText)
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
                       $('.submit').html('Next');
                   },2000);

                   console.log(response);
                   if(response.success==true) {          
                       // window.location = "{{ url('/')}}"+"/sla/?created=true";
                       toastr.success('Form Submitted Successfully');
                       // var order_id=response.order_id;
                       var candidate_id=response.candidate_id;
                       window.setTimeout(function(){
                           window.location="{{url('/guest/')}}"+"/candidates/verification/checkout/"+candidate_id;
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
               error: function (xhr, textStatus, errorThrown) {
                   // alert("Error: " + errorThrown);
               }
           });
           return false;
       });
   });
       
</script>   
@endsection