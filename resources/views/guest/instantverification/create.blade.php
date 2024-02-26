@extends('layouts.guest')
@section('content')
<style type="text/css">
   .form-group label {
   font-size: 14px;
   font-weight: 600;
   color: #002e62;
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
   .default-card
   {
    
    box-shadow: 0px 0px 10px #ccc;
    padding: 10px;
    border-radius: 10px;
    margin: 15px;

   }
   .disabled-link
   {
       pointer-events: none;
   }
   /* h4.title1 {
    margin-top: -24px;
} */
.sweet-alert button.cancel {
        background: #DD6B55 !important;
    }
    



    @media (max-width: 576px){
.newrequestcard .submit {
    margin: 13px 0px !important;
    font-size: 14px !important;
    padding: 8px 5px !important;
    width: 30% !important;
    float: right !important;
}
}
</style>
<!-- =============== Left side End ================-->
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11 col-10">
             <ul class="breadcrumb">
                <li>
                    <a href="{{ url('/verify/home') }}">Dashboard</a>
                </li>
                <li><a href="{{url('/verify/instant_verification')}}">Verification</a></li>
                <li>Details</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow col-2">
             <div class="text-right">
             <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div>
      <div class="row">
         <div class="card newrequestcard">
            <div class="card-body verify">
               <form class="mt-2" method="post" id="addCartDetailsForm" action="{{ url('/verify/instant_verification/services/store') }}" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="guest_master_id" id="guest_master_id" value="{{Crypt::encryptString($guest_master_id)}}">
                  @foreach ($guest_cart as $key => $gc)
                      <?php $service_name=Helper::get_service_name($gc->service_id);?>
                      <div class="card">
                        <h3 class="card-header">{{stripos($service_name,'Driving')!==false ? 'Driving License' : $service_name}} - {{$gc->number_of_verification}}</h3>
                        <?php
                                $guest_cart_services=Helper::get_instant_cart_service($guest_master_id,$gc->id,$gc->service_id);
                        ?>
                        <div class="card-body">
                            @foreach($guest_cart_services as $g_key => $gcs)
                                <?php 
                                    $service_name=Helper::get_service_name($gcs->service_id);
                                ?>
                                @if($gcs->service_data!=NULL)
                                    <?php 
                                        $service_data_array=json_decode($gcs->service_data,true); 
                                        // dd($service_data_array);
                                    ?>
                                    <div class="row default-card">
                                        @foreach ($service_data_array as $service_key => $service_value)
                                            <?php $i=0; ?>
                                            @if(stripos($service_key,'check')!==false)
                                                <div class="col-md-6 col-6">
                                                    {{-- @if(stripos($service_key,'candidate')!==false)
                                                        <h3>Candidate Info ({{$g_key + 1}})</h3>
                                                    @else --}}
                                                    {{-- @if(stripos($service_key,'check')!==false) --}}
                                                        <h4 class="title1">Check's Input</h4>
                                                        {{-- <p class="pb-border"></p> --}}
                                                    {{-- @endif --}}
                                                    {{-- @endif --}}
                                                </div>

                                                <div class="col-md-6 text-right float-right check-input1 col-6">
                                                    <a href="javascript:;" class="text-danger delete_btn" data-id="{{base64_encode($gcs->id)}}" style="font-size: 24px;">
                                                        <i class="far fa-times-circle"></i>
                                                    </a>
                                                </div>
                                                <div class="col-12">
                                                    <p class="pb-border"></p>
                                                </div>
                                                @foreach ($service_value as $key => $value)
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{$key}} : @if(!(stripos($key,'Middle Name')!==false || stripos($key,'Email')!==false || stripos($key,'Last Name')!==false)) <span class="text-danger">*</span> @endif</label>

                                                            {{-- @if(stripos($service_key,'candidate')!==false)
                                                                <input type="hidden" name="common_label-{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}" value="{{$key}}">
                                                                @if(stripos($key,'Gender')!==false)
                                                                    <select class="form-control" name="common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}">
                                                                        <option value="">-Select-</option>
                                                                        <option @if($value=='Male') selected @endif value="Male">Male</option>
                                                                        <option @if($value=='Female') selected @endif value="Female">Female</option>
                                                                        <option @if($value=='Other') selected @endif value="Other">Other</option>
                                                                    </select>
                                                                    <p style="margin-bottom: 2px;font-size: 16px;" class="text-danger error_container" id="error-common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}"></p>
                                                                @elseif(stripos($key,'Phone')!==false)
                                                                    <input type="hidden"  id="code" name ="primary_phone_code" value="91" >
                                                                    <input type="hidden"  id="iso" name ="primary_phone_iso" value="in" >
                                                                    <input type="tel" name="common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}" class="number_only form-control" style='display:block;' value="{{$value}}" >
                                                                    <p style="margin-bottom: 2px;font-size: 16px;" class="text-danger error_container" id="error-common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}"></p>
                                                                @elseif(stripos($key,'Date of Birth')!==false)
                                                                    <input type="date" class="form-control dob" name="common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}" id="dob" value="{{date('Y-m-d',strtotime($value))}}">
                                                                    @if(stripos($service_key,'candidate')!==false)
                                                                        <p style="margin-bottom: 2px;font-size: 16px;" class="text-danger error_container" id="error-common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}"></p>
                                                                    @endif
                                                                @elseif (stripos($key,'Email')!==false)
                                                                    <input type="email" class="form-control email" name="common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}" id="email" value="{{$value}}">
                                                                    <p style="margin-bottom: 2px;font-size: 16px;" class="text-danger error_container" id="error-common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}"></p>
                                                                @else
                                                                    <input type="text" class="form-control" name="common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}" value="{{$value}}">
                                                                    <p style="margin-bottom: 2px;font-size: 16px;" class="text-danger error_container" id="error-common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}"></p>
                                                                @endif
                                                            @else --}}
                                                                <input type="hidden" name="check_label-{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}" value="{{$key}}">
                                                                @if(stripos($key,'Date of Birth')!==false) 
                                                                    <input type="date" class="form-control dob" name="check_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}" id="dob" value="{{date('Y-m-d',strtotime($value))}}">
                                                                @else
                                                                    <input type="text" class="form-control" name="check_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}" value="{{$value}}">
                                                                @endif
                                                                <p style="margin-bottom: 2px;font-size: 13px;" class="text-danger error_container" id="error-check_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}"></p>
                                                            {{-- @endif --}}
                                                        </div>
                                                    </div>
                                                    <?php $i++; ?>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="row default-card">
                                        {{-- <div class="col-md-12">
                                            <h3>Candidate Info ({{$g_key + 1}})</h3>
                                            <p class="pb-border"></p>
                                        </div> --}}
                                        {{-- <?php 
                                            $guest_common_inputs=Helper::get_guest_common_form_inputs();
                                            $i=0;
                                        ?> --}}
                                        {{-- @foreach ($guest_common_inputs as $input)
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{$input->label_name}} : @if(!(stripos($input->label_name,'Middle Name')!==false || stripos($input->label_name,'Email')!==false || stripos($input->label_name,'Last Name')!==false)) <span class="text-danger">*</span> @endif </label>
                                                    <input type="hidden" name="common_label-{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}" value="{{$input->label_name}}">
                                                    @if(stripos($input->label_name,'Gender')!==false)
                                                        <select class="form-control" name="common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}">
                                                            <option value="">-Select-</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                    @elseif(stripos($input->label_name,'Phone')!==false)
                                                        <input type="hidden"  id="code" name ="primary_phone_code" value="91" >
                                                        <input type="hidden"  id="iso" name ="primary_phone_iso" value="in" >
                                                        <input type="tel" name="common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}" class="number_only form-control" style='display:block;' value="">
                                                    @elseif(stripos($input->label_name,'Date of Birth')!==false)
                                                        <input type="date" class="form-control dob" name="common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}" id="dob">
                                                    @elseif (stripos($input->label_name,'Email')!==false)
                                                        <input type="email" class="form-control email" name="common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}" id="email">
                                                    @else
                                                        <input type="text" class="form-control" name="common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}">
                                                    @endif
                                                    <p style="margin-bottom: 2px;font-size: 16px;" class="text-danger error_container" id="error-common_{{$gcs->id.'-'.$gcs->service_id.'-'.$i}}"></p>
                                                </div>
                                            </div> 
                                            <?php $i++; ?>                                      
                                        @endforeach --}}
                                        <div class="col-md-6 col-6">
                                            <h4 class="title1">Check's Input</h4>
                                        </div>
                                        <div class="col-md-6 text-right float-right check-input1 col-6">
                                            <a href="javascript:;" class="text-danger delete_btn" data-id="{{base64_encode($gcs->id)}}" style="font-size: 24px;">
                                                <i class="far fa-times-circle"></i>
                                            </a>
                                        </div>

                                        <div class="col-12">
                                            <p class="pb-border"></p>
                                        </div>
                                    
                                        <?php 
                                            $guest_service_inputs=Helper::get_guest_service_form_inputs($gcs->service_id);
                                            $j=0;
                                            //dd($guest_service_inputs);
                                        ?>
                                        @foreach ($guest_service_inputs as $input)
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{$input->label_name}} : <span class="text-danger">*</span></label>
                                                    <input type="hidden" name="check_label-{{$gcs->id.'-'.$gcs->service_id.'-'.$j}}" value="{{$input->label_name}}">
                                                    @if(stripos($input->label_name,'Date of Birth')!==false)
                                                        <input type="date" name="check_{{$gcs->id.'-'.$gcs->service_id.'-'.$j}}" class="form-control dob" id="dob">
                                                    @else
                                                        <input type="text" class="form-control" name="check_{{$gcs->id.'-'.$gcs->service_id.'-'.$j}}">
                                                    @endif
                                                    <p style="margin-bottom: 2px;font-size: 13px;" class="text-danger error_container" id="error-check_{{$gcs->id.'-'.$gcs->service_id.'-'.$j}}"></p>
                                                </div>
                                            </div> 
                                            <?php $j++; ?>                                      
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>
                      </div>
                  @endforeach
                  <div class="row">
                     <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-info submit" style="width: 20%;padding: 14px;margin: 18px 0px;font-size:16px;">Next</button>
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
       $('.phone').parent().css({'width':'100%'});

       $(document).on('click','.delete_btn',function(){
            var _this=$(this);
            // var result=confirm("Are You Sure You Want to Delete?");
            var id = $(this).data('id');
            // if(result){
            //     _this.addClass('disabled-link');
            //     $.ajax({
            //         type: "POST",
            //         dataType: "json",
            //         url: '{{url('/verify/instant_verification/services/delete_by_check')}}',
            //         data: {"_token": "{{ csrf_token() }}",'id': id},
            //         success: function(data){
            //             console.log(data);
            //             window.setTimeout(function(){
            //             _this.removeClass('disabled-link');
            //             },2000);
                        
            //             if(data.success==true)
            //             {
            //                 toastr.success('Record Deleted Successfully');
            //                 if(data.db==false)
            //                 {
            //                     window.setTimeout(function(){
            //                         window.location.reload();
            //                     },2000);
            //                 }
            //                 else if(data.db==true)
            //                 { 
            //                     window.setTimeout(function(){
            //                         window.location="{{url('/verify/')}}"+"/instant_verification";
            //                     },2000);
            //                 }
            //             }
            //         }
            //     });
            // }
            // else{
            //     return false;
            // }

            swal({
                  // icon: "warning",
                  type: "warning",
                  title: "Are You Sure You Want to Delete?",
                  text: "",
                  dangerMode: true,
                  showCancelButton: true,
                  confirmButtonColor: "#007358",
                  confirmButtonText: "YES",
                  cancelButtonText: "CANCEL",
                  closeOnConfirm: false,
                  closeOnCancel: false
                  },
                  function(e){
                     if(e==true)
                     {
                        _this.addClass('disabled-link');
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: '{{url('/verify/instant_verification/services/delete_by_check')}}',
                            data: {"_token": "{{ csrf_token() }}",'id': id},
                            success: function(data){
                                console.log(data);
                                window.setTimeout(function(){
                                _this.removeClass('disabled-link');
                                },2000);
                                
                                if(data.success==true)
                                {
                                    toastr.success('Record Deleted Successfully');
                                    if(data.db==false)
                                    {
                                        window.setTimeout(function(){
                                            window.location.reload();
                                        },2000);
                                    }
                                    else if(data.db==true)
                                    { 
                                        window.setTimeout(function(){
                                            window.location="{{url('/verify/')}}"+"/instant_verification";
                                        },2000);
                                    }
                                }
                            }
                        });
                        swal.close();
                     }
                     else
                     {
                         swal.close();
                     }
                  }
            );
       });

       $(document).on('submit', 'form#addCartDetailsForm', function (event) {
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
                       var guest_master_id=response.guest_master_id;
                       window.setTimeout(function(){
                           window.location="{{url('/verify/')}}"+"/instant_verification/checkout/"+guest_master_id;
                       },2000);
                   }
                   //show the form validates error
                   if(response.success==false ) {                              
                       for (control in response.errors) {  
                           // $('.'+control).addClass('border-danger'); 
                           $('#error-' + control).html(response.errors[control]);
                           $('input[name='+control+']').focus();
                           $('select[name='+control+']').focus();
                       }
                   }
               },
               error: function (response) {
                   console.log(response);
               }
            //    error: function (xhr, textStatus, errorThrown) {
            //        console.log(errorThrown);
            //    }
           });
           return false;
       });

   });

   $(".phone").intlTelInput({
          initialCountry: "in",
          separateDialCode: true,
        //   preferredCountries: ["ae", "in"],
         onlyCountries: ["in"],
          geoIpLookup: function (callback) {
              $.get('https://ipinfo.io', function () {
              }, "jsonp").always(function (resp) {
                  var countryCode = (resp && resp.country) ? resp.country : "";
                  callback(countryCode);
              });
          },
          utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"
   });

      /* ADD A MASK IN PHONE1 INPUT (when document ready and when changing flag) FOR A BETTER USER EXPERIENCE */

      var mask1 = $(".phone").attr('placeholder').replace(/[0-9]/g, 0);

      $(document).ready(function () {
          $('.phone').mask(mask1)
      });

      //
      $(".phone").on("countrychange", function (e, countryData) {
          $(".phone").val('');
          var mask1 = $(".phone").attr('placeholder').replace(/[0-9]/g, 0);
          $('.phone').mask(mask1);
          $('#code').val($(".phone").intlTelInput("getSelectedCountryData").dialCode);
          $('#iso').val($(".phone").intlTelInput("getSelectedCountryData").iso2);
      });
    
</script>
@endsection