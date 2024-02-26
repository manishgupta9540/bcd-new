@extends('layouts.guest')
<style>
   
.btn-accept {
    font-weight: 400;
    color: #ec7070;
    background-color: transparent;
}
.btn-accept:hover {
    color: #fd5b5b;
    text-decoration: underline;
    background-color: transparent;
    border-color: transparent;
}
</style>
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
         <!-- ============Breadcrumb ============= -->
   <div class="row">
      <div class="col-sm-11 col-10">
          <ul class="breadcrumb">
          <li>
          <a href="{{ url('/verify/home') }}">Dashboard</a>
          </li>
          <li>Covid-19 Certificate</li>
          </ul>
      </div>
      <!-- ============Back Button ============= -->
      <div class="col-sm-1 back-arrow col-2">
          <div class="text-right">
          <a href="{{ url()->previous() }}"> <i class="fas fa-arrow-circle-left fa-2x"></i></a>
          </div>
      </div>
  </div>   
      <div class="row">
         <div class="col-md-12">
            <div class="card text-left">
               <div class="card-body">
                  <div class="row">
                     @if ($message = Session::get('success'))
                     <div class="col-md-12">
                        <div class="alert alert-success">
                           <strong>{{ $message }}</strong> 
                        </div>
                     </div>
                     @endif
                     <div class="col-md-8">
                        <h4 class="card-title mt-2 mb-1"> Verifications </h4>
                        <p> Available ID Checks</p>
                     </div>
                     <div class="col-md-4">
                        <div class="btn-group" style="float:right">
                        </div>
                     </div>
                  </div>
                  <!-- row start -->
                  <div class="row">
                     <div class="col-md-12">
                        <div class="table-responsive">
                           <p class="text-danger text-center error-data"></p>
                           <table class="table table-bordered id-checks">
                              <thead>
                                 <tr>
                                    {{-- <th scope="col">#</th> --}}
                                    <th scope="col"> Check </th>
                                    <th scope="col"> Mobile Number </th>
                                    <th scope="col"> Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if( count($services) > 0)
                                    @foreach($services as $item)
                                        <tr class="">
                                            {{-- <th scope="row">{{ $item->id }}</th> --}}
                                            <td> <b> {{ $item->name }} </b>&nbsp;&nbsp; 
                                            <br>
                                            <small class="text-muted">  </small>
                                            </td>
                                            <td>
                                            <input type="text" name="" class="form-control IdNumber" placeholder="Enter The Mobile No."> 
                                            <span class="error" style="font-size:12px;color:red"></span>
                                            </td>
                                            <td>
                                            <button type="button" class="btn btn-sm btn-info checkButton" id="{{ $item->type_name }}" data-service="{{base64_encode($item->id)}}" check-type="aadhar" ><i class="fa fa-hand-point-right"></i> Go</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                 @else
                                    <tr>
                                        <td scope="row" colspan="7">
                                        <h3 class="text-center">No record!</h3>
                                        </td>
                                    </tr>
                                 @endif
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>

                  <!-- ./row end -->

                   <!-- row report -->
                   <div class="row reportBox reportBoxCovid19 pt-3 d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="covid19ReportExport" target="_blank" href="" download="">Download PDF</a></p>
                     <div class="table-responsive">
                        
                        <div class="col-md-10">
                            <img style='height:45px; object-fit:contain; width:150px;' src="{{url('/').'/admin/images/BCD-Logo3.png'}}" alt=''>
                        </div>
                        <h3 class="text-center"> <b>Covid-19 Certificate Verification</b></h3>

                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">Initiated Date</th>
                                    <th scope="col"> Completed Date </th>
                                    <th scope="col"> Insufficiency Raise Date </th>
                                    <th scope="col"> Insufficiency Cleared Date</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row"><span class="initiated_dt">{{ date('d-M-Y')}}</span></td>
                                    <td><span class="completed_dt">{{ date('d-M-Y')}}</span></td>
                                    <td>NA</td>
                                    <td>NA</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- id detail -->
                           <h3 class="text-center"><b>Covid 19 Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td width="50%">Reference ID</td> <td width="50%" class="c_reference_id"></td> </tr>
                                 <tr> <td>Reference Validity</td> <td class='phone_validity'>Valid <img style='width:30px; margin-bottom: 5px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='phone_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td>
                                       Covid 19 Verification Completed <br>
                                       {{-- Phone number exist <span class="t_phone_number"></span> <br> --}}
                                       Mobile Number : <span class="c_mobile_number"></span> <br>
                                       Reference ID : <span class="c_reference_id"></span> <br>
                                 </td> 
                              </tr>
                              </tbody>
                           </table>
                           <!-- d detail -->
                        </div>
                     </div>
                  </div>
                  <!-- ./report row end -->
               </div>
            </div>
         </div>
      </div>
   </div>
   
  

{{-- Modal for otp verification  --}}
<div class="modal"  id="send_otp_covid">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Covid 19 OTP Verification</h4>
            <button type="button" class="close btn_disable" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{ url('/verify/idCovid19Check/covid19') }}" id="covid19_check">
         @csrf
            <input type="hidden" name="otp_id" id="otp_id">
            <input type="hidden" name="txnId" id="txnId">
            <input type="hidden" name="ser_id" id="ser_id" class="ser_id">
            <div class="modal-body">
            <div class="form-group">
               <label for="label_name"> Mobile Number  </label>
               <input type="text" id="mob_c" class="form-control mob_c" placeholder="Enter Phone number" readonly/>
               {{-- <p style="margin-bottom: 2px;" class="text-danger" id="error-mob"></p>  --}}
            </div>
               <div class="form-group">
                  {{-- <label for="label_name"> OTP </label>
                  <input type="text" id="otp" name="otp" class="form-control otp" placeholder="Enter OTP"/>
                  <p style="margin-bottom: 2px;" class="text-danger error-container error-otp" id="error-otp"></p>  --}}
                  <div class="row justify-content-center align-items-center">
                     <div class="col-sm-5 text-center">
                         <label for="label_name"> OTP </label>
                     </div>
                 </div>
                 <div class="row justify-content-center align-items-center">
                     <div class="col-sm-8 text-center">
                         <input name="otp[]" class="digit text-center otp" type="text" id="first_otp" size="1" maxlength="1" tabindex="0" >
                         <input name="otp[]" class="digit text-center otp" type="text" id="second_otp" size="1" maxlength="1" tabindex="1">
                         <input name="otp[]" class="digit text-center otp" type="text" id="third_otp" size="1" maxlength="1"  tabindex="2">
                         <input name="otp[]" class="digit text-center otp" type="text" id="fourth_otp" size="1" maxlength="1" tabindex="3">
                         <input name="otp[]" class="digit text-center otp" type="text" id="fifth_otp" size="1" maxlength="1"  tabindex="4">
                         <input name="otp[]" class="digit text-center otp" type="text" id="sixth_otp" size="1" maxlength="1" tabindex="5">
                     </div>
                 </div>
                 <div class="row justify-content-center align-items-center">
                     <div class="col-sm-6 text-center">
                         <p style="margin-bottom: 2px;" class="text-danger error-container error-otp pt-2" id="error-otp"></p>
                         {{-- <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-wrong_otp"> </p>   --}}
                         <p style="margin-bottom: 2px;" class="text-danger error-container error-all" id="error-all"> </p> 
                     </div>
                 </div>
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info btn_disable submit" >Submit </button>
               <button type="button" class="btn btn-danger btn_disable" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

{{-- Modal for Covid Reference check --}}
<div class="modal" id="covid19_ref">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Covid 19 Verification</h4>
            <button type="button" class="close btn_disable" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{ url('/verify/idCovid19Check/covid19ref') }}" id="covid19_ref_check">
         @csrf
            <input type="hidden" name="mob_c" class="mob_c">
            <input type="hidden" name="otp_id" id="otp_id" class="otp_id">
            <input type="hidden" name="ser_id" id="ser_id" class="ser_id">
            <div class="modal-body"> 
               <div class="form-group">
                  <label for="label_name"> Reference ID </label>
                  <input type="text" id="reference_id" name="reference_id" class="form-control reference_id" placeholder="Enter Reference ID" autocomplete="off"/>
                  <p style="margin-bottom: 2px;" class="text-danger error-container error-reference_id" id="error-reference_id"></p> 
               </div>
            </div>
            <p style="margin-bottom: 2px;" class="text-danger error-container error-all" id="error-all"> </p>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info submit btn_disable">Submit </button>
               <button type="button" class="btn btn-danger btn_disable" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>


   <!-- Footer Start -->
   <div class="flex-grow-1 "></div>
</div>

<script>
$(document).ready(function() {

    
      // Covid 19 Certifcate
      $('#covid_19_certificate').click(function(){
            //reset error data 
            $(".error-data, span.error").html("");
            $('.reportBox').addClass('d-none');
            $('.reportBox').removeClass('d-block');
            var currentBtn = $(this); 
            //disable button
            $(this).prop("disabled", true);
            var checkType = $(this).attr("check-type");

            $('#covid19_check')[0].reset();
            $('#covid19_ref_check')[0].reset();
            $('.error-container').html('');
            $('.otp').removeClass('border-danger');
            $('form-control').removeClass('is-invalid');
            $('.btn_disable').attr('disabled',false);
            // add spinner to button
            $(this).html(
            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
            );

            var inputData = $(currentBtn).parent('td').prev('td').find('input').val();
            var finalInputID = inputData.trim();
            var service_id=$(this).attr('data-service');
            // alert(service_id);
            if(inputData !="" && finalInputID.length == 10){
               //
               $.ajax({
                  url:"{{ url('/verify/idCheck/covid19') }}",
                  method:"GET",
                  data:{'id_number':inputData,'service_id':service_id},      
                  success:function(data)
                  {
                     window.setTimeout(function(){
                        $(currentBtn).prop("disabled", false);
                        $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                     },1000);
                     if(data.fail)
                     {
                        $(currentBtn).parent('td').prev('td').find('span').html(data.message);
                        $(currentBtn).attr("check-type",checkType);
                        $(currentBtn).prop("disabled", false);
                        $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                     }
                     if(data.fail == false)
                     {
                        $('#mob_c').val(finalInputID);
                        $('#txnId').val(data.txnId);
                        $('#otp_id').val(data.otp_id);
                        $('.ser_id').val(service_id);
                        //notify
                        $('#send_otp_covid').modal({
                           backdrop: 'static',
                           keyboard: false
                        });

                        $(currentBtn).attr("check-type",checkType);
                        $(currentBtn).prop("disabled", false);
                        $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');

                     }
                  },
                  error : function(data)
                  {
                     console.log(data);
                  }
               });

            }else{

                  $(currentBtn).parent('td').prev('td').find('span').html("Please enter the valid Phone number!");
               
                  setTimeout(function(){ 
                     $(currentBtn).attr("check-type",checkType);
                     $(currentBtn).prop("disabled", false);
                     $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                  }, 1000);
            }

      });

      $(document).on('submit','form#covid19_check',function(event){

         $('.reportBox').addClass('d-none');
         $('.reportBox').removeClass('d-block');
         $("#overlay").fadeIn(300);　
         event.preventDefault();
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var $btn = $(this);
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
         $('.error-container').html('');
         $('.otp').removeClass('border-danger');
         $('form-control').removeClass('border-danger');
         $('.btn_disable').attr('disabled',true);
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
            success: function (data) {
               console.log(data);
               // $('.error-container').html('');
               window.setTimeout(function(){
                  $('.btn_disable').attr('disabled',false);
                  $('.submit').html('Submit');
               },2000);
               if (data.fail && data.error_type == 'validation') {
                     //$("#overlay").fadeOut(300);
                     for (control in data.errors) {
                        $('.' + control).addClass('border-danger');
                        $('.error-' + control).html(data.errors[control]);
                     }
               } 
               if (data.fail && data.error == 'yes') {
                  
                  $('.error-all').html(data.message);
               }
               if (data.fail == false) {
                  // $('#advance_check').modal('hide');
                  // console.log(data);

                  $('#send_otp_covid').modal('hide');

                  $('.otp_id').val(data.id);
                  $('.ser_id').val(data.service_id);
                  $('.mob_c').val(data.mobile_no);
                  $('#covid19_ref').modal({
                     backdrop: 'static',
                     keyboard: false
                  });

               }
            },
            error : function(data)
            {
               console.log(data);
            }
         });
         return false;
      });

      $(document).on('submit','form#covid19_ref_check',function(event){

         $('.reportBox').addClass('d-none');
         $('.reportBox').removeClass('d-block');
         $("#overlay").fadeIn(300);　
         event.preventDefault();
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var $btn = $(this);
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
         $('.error-container').html('');
         $('form-control').removeClass('border-danger');
         $('.btn_disable').attr('disabled',true);
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
            success: function (data) {
               console.log(data);
               $('.error-container').html('');
               window.setTimeout(function(){
                  $('.btn_disable').attr('disabled',false);
                  $('.submit').html('Submit');
               },2000);
               if (data.fail && data.error_type == 'validation') {
                     //$("#overlay").fadeOut(300);
                     for (control in data.errors) {
                        $('input[name=' + control + ']').addClass('border-danger');
                        $('.error-' + control).html(data.errors[control]);
                     }
               } 
               if (data.fail && data.error == 'yes') {
                  
                  $('.error-all').html(data.message);
               }
               if (data.fail == false) {
                  $('#covid19_ref').modal('hide');
                  // console.log(data);
                  toastr.success("Verification Done");
                  $('.reportBoxCovid19').removeClass('d-none');
                  $('.reportBoxCovid19').addClass('d-block');
                  
                  //set the output data
                  $('.c_mobile_number').html("<strong>"+data.data.mobile_no+"</strong>"); 
                  $('.c_reference_id').html("<strong>"+data.data.reference_id+"</strong>"); 

                  $('#covid19ReportExport').attr('href',data.url);

                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }
            },
            error : function(data)
            {
               console.log(data);
            }
         });
         return false;
      });

});

function OTPInput() {
      const inputs = document.querySelectorAll('.otp');
      // alert(inputs.length);
      for (let i = 0; i < inputs.length; i++) 
      { 
         inputs[i].addEventListener('keyup', function(event) 
         { 
            if (event.key==="Backspace" ) 
            { 
                  inputs[i].value='' ; 
                  if (i !==0) inputs[i - 1].focus();
                  
            } 
            else { 
                  if (i===inputs.length - 1 && inputs[i].value !=='' ) 
                  { return true; } 
                  else if (event.keyCode> 47 && event.keyCode < 58) 
                  { 
                     inputs[i].value=event.key; 
                     if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); 
                     
                  } 
                  else if (event.keyCode> 95 && event.keyCode < 106) 
                  { 
                     inputs[i].value=event.key; 
                     if (i !==inputs.length - 1) 
                     inputs[i + 1].focus(); event.preventDefault(); 
                     
                  }
            } 
            
         }); 
         
      } 
      
} 
OTPInput(); 

</script>

@endsection
