@extends('layouts.client')
<style>
    .disabled-link{
      pointer-events: none;
    }
  </style>
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
            <div class="main-content">				
            <!-- ============Breadcrumb ============= -->
            <div class="row">
                    <div class="col-sm-11">
                        <ul class="breadcrumb">
                        <li><a href="{{ url('/my/home') }}">Dashboard</a></li>
                        <li><a href="{{ url('/my/candidates') }}">Candidate</a></li>
                        <li>Create new</li>
                        </ul>
                    </div>
                    <!-- ============Back Button ============= -->
                    <div class="col-sm-1 back-arrow">
                        <div class="text-right">
                        <a href="{{ url('/my/candidates') }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                        </div>
                    </div>
            </div>
            <!-- ./breadbrum -->
            <div class="row">
			<div class="card text-left">
               <div class="card-body" style="">
               
               <div class="col-md-8 offset-md-2">
               <form class="mt-2" method="post" id="addCandidateForm" action="{{ url('/my/candidates/store') }}">
                @csrf
			   <div class="row">
            
                @if ($message = Session::get('error'))
                <div class="col-md-12">   
                    <div class="alert alert-danger">
                    <strong>{{ $message }}</strong> 
                    </div>
                </div>
                @endif

			    <div class="col-md-10">
	              <h4 class="card-title mb-1" style="border-bottom:1px solid #ddd;">Add a new candidate </h4> 
				    <p class="mt-1"> Fill the required details </p>			
				</div>
				
			   <div class="col-md-10">		
                        @php
                            $business_id = Auth::user()->id;

                            $req = 0;

                            if($business_id==2155)
                            {
                                $req=1;
                            }
                        @endphp
                        <!-- select a SLA of customer  -->
                        <div class="sla_row">
                            {{-- <label for="name">SLA Type <span class="text-danger">*</span></label> 
                            <br>
                            <label class="radio-inline pr-2">
                                <input type="radio" class="sla_type" name="sla_type" value="package" data-id="{{Auth::user()->business_id}}"> Package 
                            </label> 
                            <label class="radio-inline">
                                <input type="radio" class="sla_type" name="sla_type" value="variable" data-id="{{Auth::user()->business_id}}"> Variable SLA 
                            </label>
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla_type"></p>   --}}

                            <div class="form-group"> 
                                <label for="service">Select a SLA <span class="text-danger">*</span></label> 
                                <select class="form-control slaList" name="sla"> 
                                    <option value="">-Select-</option> 
                                    @if( count($slas) > 0 ) 
                                        @foreach($slas as $sla) 
                                            <option value="{{ $sla->id }}" >{{ ucfirst($sla->title) }}</option> 
                                        @endforeach 
                                    @endif 
                                </select> 
                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla"></p> 
                            </div> 
                            <div class="form-group SLAResult"> 
                            </div> 
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
                        </div>

                        {{-- <div class="sla_type_result">

                        </div> --}}
                        
                        {{-- <div class="form-group">
                            <label for="service">Select a SLA <span class="text-danger">*</span></label>
                            <select class="form-control slaList" name="sla">
                                <option value="">-Select-</option>
                                @if( count($slas) > 0 )
                                    @foreach($slas as $sla)
                                    <option value="{{ $sla->id }}" >{{ ucfirst($sla->title) }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla"></p>
                        </div>
                        
                        <div class="form-group SLAResult">
                        
                        </div>
                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p> --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">Emp Code </label>
                                    <input type="text" name="client_emp_code" class="form-control" placeholder="Emp code" value="{{ old('client_emp_code') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">Entity Code</label>
                                    <input type="text" name="entity_code" class="form-control" placeholder="Entity code" value="{{ old('entity_code') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter first name" value="{{ old('first_name') }}">
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-first_name"></p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control" id="middle_name" placeholder="Enter middle name" value="{{ old('first_name') }}">
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-middle_name"></p>
                                </div> 
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Last Name @if($req==1) <span class="text-danger">*</span> @endif</label>
                                    <input type="text" name="last_name" class="form-control last_name" id="last_name"  placeholder="Enter last name" value="{{ old('last_name') }}">
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-last_name"></p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="father_name">Father Name @if($req==0) <span class="text-danger">*</span> @endif </label>
                                    <input type="text" name="father_name" class="form-control"  placeholder="Enter father name" value="{{ old('father_name') }}">
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-father_name"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Aadhar Number </label></label>
                                    <input type="text" name="aadhar" class="form-control aadhar"  placeholder="Enter Aadhar Number" value="{{ old('aadhar') }}">
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-aadhar"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="name">DOB @if($req==0) <span class="text-danger">*</span> @endif </label>
                                <input type="text" name="dob" class="form-control  dob commonDatepicker"  placeholder="" value="{{ old('dob') }}">
                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dob"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="name">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control " >
                                    <option value="">-Select-</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gender"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone <span class="text-danger">*</span></label>
                                    <input type="hidden"  id="code" name ="primary_phone_code" value="91">
                                    <input type="hidden"  id="iso" name ="primary_phone_iso" value="in" >
                                    <input type="tel" name ="phone" id="phone1" class="number_only form-control" style='display:block' value="{{ old('phone') }}">
                                    <small class="text-muted"></small>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-phone"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="lbl_email" for="email">Email</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" value="{{ old('email') }}">
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>  
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jaf">Select a JAF Filling Access <span class="text-danger">*</span></label>
                                    <select class="form-control jaf" name="jaf" id="jaf_reset">
                                        <option value="">-Select-</option>
                                        <option value="customer">{{Helper::parent_company_name($parent_id)}}</option>
                                        <option value="coc">{{Helper::company_name($business_id)}}</option>
                                        <option value="candidate">Candidate</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-jaf"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group jaf_file">
                                    <label for="label_name"> JAF Details:  <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,svg,pdf,csv,xlsx,zip,docs are accepted "></i>   </label>
                                    <input type="file" name="jaf_details[]" multiple id="jaf_details" accept=".jpg,.jpeg,.png,.pdf,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.zip,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="form-control jaf_details">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-jaf_details"></p>  
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter password"   value="{{ old('password') }}">
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-password"></p>  
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm-password">Confirm password</label>
                                    <input type="password" name="confirm-password" class="form-control" id="confirm-password" placeholder="Enter confirm password"  value="{{ old('password') }}">
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-password"></p>  
                                </div>
                            </div>
                        </div> --}}
                       
                        <p class="text-danger jaf_note d-none">Note:- System will send the JAF link to the Candidate's email with login credentials.</p>
                        {{-- <div class="form-group mt-2">
                            <div class='form-check form-check-inline'><label class='form-check-label' for=''>Send JAF Link</label></div>  
                            <!-- Rounded switch -->
                            <label class="switch">
                                <input type="checkbox" name="is_send_jaf_link"><span class="slider round"></span>
                            </label>
                        </div> --}}
                        
                        <div class="form-group mt-2">
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-user"></p>            
                            <button type="submit" class="btn btn-info submit">Submit</button>
				        </div>	
                    </div>
                <!--  -->
                </form>
               </div>
            </div>
				
            </div>
            
        </div>

<script>
    
    $(function(){
        $('.switch').on('change.bootstrapSwitch', function(e) {
        console.log(e.target.checked);
    });

    // $(document).on('change','.sla_type',function(){

    //     var type=$(this).val();
    //     var cust_id=$(this).attr('data-id');
    //     $('.sla_type_result').html("");
    //     if(type=='package')
    //     {
    //         $('.sla_type_result').html('<div class="form-group"> <label for="service">Select a SLA <span class="text-danger">*</span></label> <select class="form-control slaList" name="sla"> <option value="">-Select-</option> @if( count($slas) > 0 ) @foreach($slas as $sla) <option value="{{ $sla->id }}" >{{ ucfirst($sla->title) }}</option> @endforeach @endif </select> <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla"></p> </div> <div class="form-group SLAResult"> </div> <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>');
    //     }
    //     else if(type=='variable')
    //     {
    //         $('.sla_type_result').html('<div class="row"> <div class="col-sm-6"> <div class="form-group"> <label class="pb-1" for="name">Days Type <span class="text-danger">*</span></label> <br> <label class="radio-inline pr-2"> <input type="radio" class="days_type" name="days_type" value="working"> Working Days </label> <label class="radio-inline"> <input type="radio" class="days_type" name="days_type" value="calender" > Calender Days </label> <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-days_type"></p> </div> </div> </div><input type="hidden" name="sla" class="sla" id="sla" value="{{$variable->id}}"><div class="form-group error-control SLAResult"> @foreach ($services as $service) <div class="form-check form-check-inline"> <input class="form-check-input variable_services_list" type="checkbox" name="services[]" value="{{ $service->id}}" data-string="{{ $service->name  }}" data-type="{{ $service->is_multiple_type }}" id="inlineCheckbox-{{ $service->id}}" data-verify={{$service->verification_type}}> <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label> </div> @endforeach <p style="margin-top:2px; margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p></div> <div class="service_result" style="border: 1px solid #ddd; padding:10px;margin-bottom:15px;"> <div class="row"> <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Configure Number of Verifications Need on each service</div> </div> </div>');
    //     }

        // });
    $(document).on('change', '.jaf', function (event) {
    
        var jaf_value = $('.jaf option:selected').val();

        if (jaf_value == 'candidate') {
            // $(".single").removeClass('d-none');
            $(".jaf_file").hide();
            // $(".single").show();

            $('.jaf_note').removeClass('d-none');
            
        }
        else {
            // $(".multiple").removeClass('d-none');
            // $(".single").hide();
            $(".jaf_file").show();
            $('.jaf_note').addClass('d-none');
        }

    
    
    });
    //on select sla item
    $(document).on('change','.slaList',function(e) {
        e.preventDefault();
        $(".SLAResult").html("");
        var sla_id = $('.slaList option:selected').val();
        $.ajax({ 
            type:"POST",
            url: "{{ url('/my/customer/mixSla/serviceItems') }}",
            data: {"_token": "{{ csrf_token() }}",'sla_id':sla_id},      
            success: function (response) {
                // console.log(response);
                if(response.success==true  ) {   
                    $.each(response.data, function (i, item) {
                        
                    if(item.checked_atatus){$(".SLAResult").append("<div class='form-check form-check-inline disabled-link'><input class='form-check-input services_list' type='checkbox' checked name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' data-type='' readonly><label class='form-check-label' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
                    }else{
                        $(".SLAResult").append("<div class='form-check form-check-inline disabled-link'><input class='form-check-input services_list' type='checkbox' name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' data-type='' readonly><label class='form-check-label' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
                    }

                    });

                    var company_name = response.company_name;

                    $('.SLAResult').append('<p class="text-danger">Note:- If You Want to Add More Checks, Please Contact to '+company_name+' !!</p>');
                }
                //show the form validates error
                if(response.success==false ) {                              
                    for (control in response.errors) {   
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

    $(document).on('change','#jaf_reset',function(event){
        var _this=$(this);

        $('.lbl_email').html('Email');

        $('.jaf_note').addClass('d-none');

        if(_this.val().toLowerCase()=='candidate')
        {
            $('.lbl_email').html('Email <span class="text-danger">*</span>');

            $('.jaf_note').removeClass('d-none');
        }
        

    });

    // $(document).on('change','.variable_services_list',function() {
    //   if(this.checked)
    //   {
    //      var id =  $(this).attr("value");
    //      var text =  $(this).attr("data-string");
    //      var verify =$(this).attr("data-verify");
    //      var tat = 1;
    //      if(text.toLowerCase()=='Address'.toLowerCase())
    //     {
    //         tat=7;
    //     }
    //     else if(text.toLowerCase()=='Employment'.toLowerCase())
    //     {
    //         tat=5;
    //     }
    //     else if(text.toLowerCase()=='Educational'.toLowerCase())
    //     {
    //         tat=7;
    //     }
    //     else if(text.toLowerCase()=='Criminal'.toLowerCase())
    //     {
    //         tat=3;
    //     }
    //     else if(text.toLowerCase()=='Judicial'.toLowerCase())
    //     {
    //         tat=2;
    //     }
    //     else if(text.toLowerCase()=='Reference'.toLowerCase())
    //     {
    //         tat=2;
    //     }
    //     else if(text.toLowerCase()=='Covid-19 Certificate'.toLowerCase())
    //     {
    //         tat=5;
    //     }

    //     if(verify.toLowerCase()=="Auto".toLowerCase())
    //         $(".service_result").append("<p class='pb-border row-"+id+"'></p><div class='row row-"+id+" mt-2' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-2'><input class='form-control' type='text' name='service_unit-"+id+"' value='1' readonly><p style='margin-top:2px; margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='tat-"+id+"' value='"+tat+"' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-"+id+"'></p></div><div class='col-sm-2'><label>Incentive TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='incentive-"+id+"' value='1'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-"+id+"'></p></div></div><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-2'></div><div class='col-sm-3 pt-2 text-right'><label>Penalty TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='penalty-"+id+"' value='"+tat+"'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-"+id+"'></p></div></div>");
    //     else
    //         $(".service_result").append("<p class='pb-border row-"+id+"'></p><div class='row row-"+id+" mt-2' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-2'><input class='form-control' type='text' name='service_unit-"+id+"' value='1' ><p style='margin-top:2px; margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='tat-"+id+"' value='"+tat+"' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-"+id+"'></p></div><div class='col-sm-2'><label>Incentive TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='incentive-"+id+"' value='1'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-"+id+"'></p></div></div><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-2'></div><div class='col-sm-3 pt-2 text-right'><label>Penalty TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='penalty-"+id+"' value='"+tat+"'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-"+id+"'></p></div></div>");
    //   }
    //   else
    //   {
    //      var id =  $(this).attr("value");
    //      $("div#row-"+id).remove();
    //      $("p.row-"+id).remove();
    //   }
   
    // });

});

</script>

<script>
$(function(){

// $('.btn').on('click', function() {
//     var $this = $(this);
//     var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
//     if ($(this).html() !== loadingText) {
//       $this.data('original-text', $(this).html());
//       $this.html(loadingText);
//     }
//     setTimeout(function() {
//       $this.html($this.data('original-text'));
//     }, 5000);
// });

//    $('#createCandidateBtn').click(function(e) {
//         e.preventDefault();
//         $("#addCandidateForm").submit();
//     });

   $(document).on('submit', 'form#addCandidateForm', function (event) {
        event.preventDefault();
        //clearing the error msg
        $('p.error_container').html("");

        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        $('.submit').attr('disabled',true);
        $('.form-control').attr('readonly',true);
        $('.form-control').addClass('disabled-link');
        $('.error-control').addClass('disabled-link');
        if ($('.submit').html() !== loadingText) {
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
                        $('.form-control').attr('readonly',false);
                        $('.form-control').removeClass('disabled-link');
                        $('.error-control').removeClass('disabled-link');
                        $('.submit').html('Submit');
                    },2000);
                // console.log(response);
                if(response.success==true  ) {          
                    //notify
                toastr.success("Candidate has been created successfully");
                    // redirect to google after 5 seconds
                    window.setTimeout(function() {
                        window.location = "{{ url('/')}}"+"/my/candidates/";
                    }, 2000);
                
                }
                //show the form validates error
                if(response.success==false ) {   
                    var i = 0;                           
                    for (control in response.errors) {   
                        $('#error-' + control).html(response.errors[control]);
                        if(i==0)
                        {
                            $('select[name='+control+']').focus();
                            $('input[name='+control+']').focus(); 
                            $('textarea[name='+control+']').focus();
                        }
                        i++;  
                    }
                }
            },
            error: function (response) {
                console.log(response);
            }
        });
        return false;
   });
});

</script>

@endsection