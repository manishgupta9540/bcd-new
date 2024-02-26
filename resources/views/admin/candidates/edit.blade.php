@extends('layouts.admin')
@section('content')
<style>
    .disabled-link{
      pointer-events: none;
    }
  </style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
    <!-- ============ Body content start ============= -->
    <div class="main-content">				
        <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ url('/candidates') }}">Candidate</a></li>
                <li>Edit</li>
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
            <div class="card text-left">
                <div class="card-body" style="">
                
                    <div class="col-md-8 offset-md-2">
                        <form class="mt-2" method="post" id="editCandidateForm" action="{{ route('/candidates/update',['id'=>base64_encode($user->id)]) }}">
                            @csrf
                            <input type="hidden" name="customer" value="{{base64_encode($user->business_id)}}">
                            <input type="hidden" id="days_type" name="days_type" value="{{$case_info->job_days_type}}">
                            <div class="row">
                                @if ($message = Session::get('error'))
                                <div class="col-md-12">   
                                    <div class="alert alert-danger">
                                    <strong>{{ $message }}</strong> 
                                    </div>
                                </div>
                                @endif

                                <div class="col-md-10">
                                <h4 class="card-title mb-1" style="border-bottom:1px solid #ddd;">Edit the candidate info : <b>{{ $user->first_name.' '.$user->middle_name.' '.$user->last_name }}</b></h4> 
                                    <p class="mt-1"> Fill the required details </p>			
                                </div>
                                
                                <div class="col-md-10">		

                                    <!-- select a customer  -->
                                        <div class="form-group">
                                            <label for="service">Customer: <b>{{ $case_info->company_name }} </b></label>
                                        </div>
                                        <!-- select a SLA of customer  -->
                                        <?php 
                                            $job_items=Helper::get_job_items($user->id,$user->business_id);
                                            // dd($job_items);
                                        ?>
                                        @if($job_items->jaf_status=='filled' || $job_items->jaf_status=='draft')
                                            <div class="form-group">
                                                <label for="service">SLA : <b>{{ $case_info->title }} </b> </label>
                                            
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla"></p>
                                            </div>
                                            
                                            <div class="form-group SLAResult">
                                            
                                            </div>

                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>

                                        @else

                                            <div class="sla_row">
                                                <label for="name">SLA Type 
                                                    <span class="text-danger">*</span>
                                                </label> <br>
                                                <label class="radio-inline error-control pr-2">
                                                    <input type="radio" class="sla_type" name="sla_type" value="package" data-id="{{$user->business_id}}" @if($case_info->sla_type=='package') checked data-status="1" @else data-status="0" @endif > Package 
                                                </label> 
                                                <label class="radio-inline error-control"> 
                                                    <input type="radio" class="sla_type" name="sla_type" value="variable" data-id="{{$user->business_id}}" @if($case_info->sla_type=='variable') checked data-status="1" @else data-status="0" @endif> Variable SLA 
                                                </label>
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla_type"></p>
                                            </div>
                                            
                                            @if($case_info->sla_type=='package')
                                                <div class="sla_type_result">
                                                    <?php $customer_sla=Helper::get_customer_sla($user->business_id); ?>
                                                    <div class="form-group"> 
                                                        <label for="service">
                                                            Select a SLA <span class="text-danger">*</span>
                                                        </label> 
                                                        <select class="form-control slaList" name="sla" id="sla"> 
                                                            <option value="">-Select-</option>
                                                            @if($customer_sla!=NULL && count($customer_sla)>0)
                                                                @foreach ($customer_sla as $sla)
                                                                    <option @if($sla->id==$job_items->sla_id) selected @endif value="{{$sla->id}}">{{$sla->title}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select> 
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla"></p> 
                                                    </div> 
                                                    <div class="form-group SLAResult">
                                                        @foreach($services as $service)
                                                            <div class='form-check form-check-inline disabled-link'>
                                                                <input class='form-check-input error-control services_list' type='checkbox' name='services[]' value='{{$service->id}}' id='{{$service->id}}' data-type='' readonly {{in_array($service->id, $selected_services_id) ? 'checked' : '' }}>
                                                                <label class='form-check-label error-control' for='{{$service->id}}'>{{$service->name}}</label>
                                                            </div>
                                                        @endforeach
                                                    </div> 
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
                                                </div>
                                                <div class="service_result" style="border: 1px solid #ddd; padding:10px;margin-bottom:15px;"> 
                                                    <div class="row"> 
                                                        <div class="col-sm-12 mt-1 mb-2">
                                                            <span style="color:#dd2e2e">Configure Number of Verifications Need on each check item</span>
                                                            <span style="float: right;">
                                                               <span class="pr-2"> Total Checks:- <span class="total_checks">{{$total_checks}}</span></span>
                                                               <span class="total_p @if(stripos($case_info->job_price_type,'package')!==false) d-none @endif"> Total Price:- <i class='fas fa-rupee-sign'></i> <span class="total_check_price">{{$total_check_price}}</span></span>
                                                            </span>
                                                        </div> 
                                                    </div>
                                                   
                                                    @foreach($sla_items as $item)
                                                        <p class='pb-border row-{{$item->service_id}}' id='row-{{$item->service_id}}'></p>
                                                        <div class='row mt-2' id='row-{{$item->service_id}}'>
                                                            <div class='col-sm-2'>
                                                                <label>{{$item->service_name}}</label>
                                                            </div>
                                                            @php 
                                                                $newvalueverification =  $item->number_of_verifications - $item->old_number_of_verifications;
                                                            @endphp
                                                            <div class='col-sm-2'>
                                                                <input class='form-control no_of_check' type='text' name='service_unit-{{$item->service_id}}' value='{{$item->number_of_verifications}}' readonly @if($item->verification_type=='Auto' || $item->verification_type=='auto') readonly @endif>
                                                                @if ($newvalueverification < 0)
                                                                    <p>Excluded : {{$newvalueverification}} </p>
                                                                @elseif($newvalueverification > 0)
                                                                    <p>Included : {{$newvalueverification}} </p>
                                                                @endif
                                                                
                                                                <p style='margin-top:2px; margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-{{$item->service_id}}'></p>
                                                            </div>
                                                            <div class='col-sm-1'>
                                                                <label>TAT</label>
                                                            </div>
                                                            <div class='col-sm-2'>
                                                                <input class='form-control' type='text' name='tat-{{$item->service_id}}' value='{{$item->check_tat}}' placeholder='TAT' >
                                                                <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-{{$item->service_id}}'></p>
                                                            </div>
                                                            <div class='col-sm-2'>
                                                                <label>Incentive TAT</label>
                                                            </div>
                                                            <div class='col-sm-3'>
                                                                <input class='form-control' type='text' name='incentive-{{$item->service_id}}' value='{{$item->incentive_tat}}'>
                                                                <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-{{$item->service_id}}'></p>
                                                            </div>
                                                        </div>
                                                        <div class='row mt-2' id='row-{{$item->service_id}}'>
                                                            <div class='col-sm-2'></div>
                                                            <div class='col-sm-3 pt-2 text-right'>
                                                                <label>Penalty TAT</label>
                                                            </div>
                                                            <div class='col-sm-2'>
                                                                <input class='form-control' type='text' name='penalty-{{$item->service_id}}' value='{{$item->penalty_tat}}'>
                                                                <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-{{$item->service_id}}'></p>
                                                            </div>
                                                            <div class='col-sm-2 price_row @if(stripos($case_info->job_price_type,'package')!==false) d-none @endif pt-2'>
                                                                <label>Price (<small class='text-muted'>Per Item <i class='fas fa-rupee-sign'></i></small>)</label>
                                                            </div>
                                                            <div class='col-sm-3 price_row @if(stripos($case_info->job_price_type,'package')!==false) d-none @endif'>
                                                                <input class='form-control check_price' type='text' name='price-{{$item->service_id}}' value='{{$item->price}}' @if(stripos($case_info->job_price_type,'package')!==false) readonly @endif>
                                                                <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-{{$item->service_id}}'></p>
                                                            </div>
                                                        </div>
                                                    @endforeach 
                                                </div> 

                                            @elseif($case_info->sla_type=='variable')
                                                <div class="sla_type_result">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="pb-1" for="name">Price Type <span class="text-danger">*</span></label> <br>
                                                                <label class="radio-inline pr-2">
                                                                <input type="radio" class="price_type" name="price_type" value="package" @if(stripos($case_info->job_price_type,'package')!==false) checked data-status="1" @else data-status="0" @endif> Package-Wise </label> 
                                                                <label class="radio-inline"> 
                                                                    <input type="radio" class="price_type" name="price_type" value="check" @if(stripos($case_info->job_price_type,'check')!==false) checked data-status="1" @else data-status="0" @endif> Check-Wise 
                                                                </label>
                                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-price_type"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="price_result @if(stripos($case_info->job_price_type,'package')!==false) mb-2 @endif" @if(stripos($case_info->job_price_type,'package')!==false) style="border:1px solid #ddd;padding:10px;width:50%" @endif>
                                                        @if(stripos($case_info->job_price_type,'package')!==false)
                                                           <div class="row">
                                                                <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Package Wise Price</div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>Price <span class="text-danger">*</span> (<small class="text-muted"><i class="fas fa-rupee-sign"></i></small>)</label>
                                                                        <input class="form-control" type="text" name="price" value="{{$case_info->job_package_price}}">
                                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-price"></p>
                                                                    </div>
                                                                </div>
                                                           </div> 
                                                        @endif
                                                    </div>
                                                    <input type="hidden" name="sla" class="sla" id="sla" value="{{$variable->id}}">
                                                    <div class="form-group SLAResult"> 
                                                        @foreach ($services as $service) 
                                                            <div class="form-check form-check-inline error-control"> 
                                                                <input class="form-check-input variable_services_list" type="checkbox" name="services[]" value="{{$service->id}}" data-string="{{$service->name}}" data-type="{{ $service->is_multiple_type }}" id="inlineCheckbox-{{ $service->id}}" data-verify={{$service->verification_type}} {{in_array($service->id, $selected_services_id) ? 'checked' : '' }}> 
                                                                <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label> 
                                                            </div> 
                                                        @endforeach 
                                                        <p style="margin-top:2px; margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
                                                    </div> 
                                                    
                                                    <div class="service_result" style="border: 1px solid #ddd; padding:10px;margin-bottom:15px;"> 
                                                        <div class="row"> 
                                                            <div class="col-sm-12 mt-1 mb-2">
                                                                <span style="color:#dd2e2e">Configure Number of Verifications Need on each check item</span>
                                                                <span style="float: right;">
                                                                   <span class="pr-2"> Total Checks:- <span class="total_checks">{{$total_checks}}</span></span>
                                                                   <span class="total_p @if(stripos($case_info->job_price_type,'package')!==false) d-none @endif"> Total Price:- <i class='fas fa-rupee-sign'></i> <span class="total_check_price">{{$total_check_price}}</span></span>
                                                                </span>
                                                            </div> 
                                                        </div>
                                                        @foreach($sla_items as $item)
                                                            <p class='pb-border row-{{$item->service_id}}' id='row-{{$item->service_id}}'></p>
                                                            <div class='row mt-2' id='row-{{$item->service_id}}'>
                                                                <div class='col-sm-2'>
                                                                    <label>{{$item->service_name}}</label>
                                                                </div>
                                                                <div class='col-sm-2'>
                                                                    <input class='form-control no_of_check' type='text' name='service_unit-{{$item->service_id}}' value='{{$item->number_of_verifications}}' @if($item->verification_type=='Auto' || $item->verification_type=='auto') readonly @endif>
                                                                    <p style='margin-top:2px; margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-{{$item->service_id}}'></p>
                                                                </div>
                                                                <div class='col-sm-1'>
                                                                    <label>TAT</label>
                                                                </div>
                                                                <div class='col-sm-2'>
                                                                    <input class='form-control' type='text' name='tat-{{$item->service_id}}' value='{{$item->check_tat}}' placeholder='TAT' >
                                                                    <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-{{$item->service_id}}'></p>
                                                                </div>
                                                                <div class='col-sm-2'>
                                                                    <label>Incentive TAT</label>
                                                                </div>
                                                                <div class='col-sm-3'>
                                                                    <input class='form-control' type='text' name='incentive-{{$item->service_id}}' value='{{$item->incentive_tat}}'>
                                                                    <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-{{$item->service_id}}'></p>
                                                                </div>
                                                            </div>
                                                            <div class='row mt-2' id='row-{{$item->service_id}}'>
                                                                <div class='col-sm-2'></div>
                                                                <div class='col-sm-3 pt-2 text-right'>
                                                                    <label>Penalty TAT</label>
                                                                </div>
                                                                <div class='col-sm-2'>
                                                                    <input class='form-control' type='text' name='penalty-{{$item->service_id}}' value='{{$item->penalty_tat}}'>
                                                                    <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-{{$item->service_id}}'></p>
                                                                </div>
                                                                <div class='col-sm-2 price_row @if(stripos($case_info->job_price_type,'package')!==false) d-none @endif pt-2'>
                                                                    <label>Price (<small class='text-muted'>Per Item <i class='fas fa-rupee-sign'></i></small>)</label>
                                                                </div>
                                                                <div class='col-sm-3 price_row @if(stripos($case_info->job_price_type,'package')!==false) d-none @endif'>
                                                                    <input class='form-control check_price' type='text' name='price-{{$item->service_id}}' value='{{$item->price}}' @if(stripos($case_info->job_price_type,'package')!==false) readonly @endif>
                                                                    <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-{{$item->service_id}}'></p>
                                                                </div>
                                                            </div>
                                                        @endforeach 
                                                    </div> 

                                                </div>
                                            @endif

                                        @endif
                
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="first_name">FRNID </label>
                                                    <input type="text" name="frnid" class="form-control" placeholder="FRNID" value="{{ $user->frnid }}">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="first_name">Client Emp Code </label>
                                                    <input type="text" name="client_emp_code" class="form-control" placeholder="Client emp code" value="{{ $user->client_emp_code }}">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="first_name">Entity Code</label>
                                                    <input type="text" name="entity_code" class="form-control" placeholder="Entity code" value="{{ $user->entity_code }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter first name" value="{{ $user->first_name }}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-first_name"></p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="first_name">Middle Name</label>
                                                    <input type="text" name="middle_name" class="form-control" id="middle_name" placeholder="Enter middle name" value="{{ $user->middle_name }}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-middle_name"></p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="name">Last Name </label>
                                                    <input type="text" name="last_name" class="form-control" id="last_name"  placeholder="Enter last name" value="{{ $user->last_name }}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-last_name"></p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="name">Father Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="father_name" class="form-control"  placeholder="Enter father name" value="{{ $user->father_name }}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-father_name"></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label for="name">DOB <span class="text-danger">*</span></label>
                                                <input type="text" name="dob" class="form-control commonDatepicker"  placeholder="" value="{{ date('d-m-Y', strtotime($user->dob)) }}">
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dob"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label for="name">Gender <span class="text-danger">*</span></label>
                                                <select name="gender" class="form-control " >
                                                    <option value="">-Select-</option>
                                                    <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                    <option value="Other"  {{ $user->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gender"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Phone <span class="text-danger">*</span></label>
                                                    <input type="hidden"  id="code" name ="primary_phone_code" value="91" >
                                                    <input type="hidden"  id="iso" name ="primary_phone_iso" value="in" >
                                                    <input type="tel" name ="phone" id="phone1" class=" form-control" style='display:block' value="{{ $user->phone }}">
                                                    <small class="text-muted"></small>
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-phone"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" value="{{ $user->email }}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="alternative_email">Alternative Email</label>
                                                    <input type="email" name="alternative_email" class="form-control" id="alternative_email" placeholder="Enter Alternative Email" value="{{ $user->alternative_email }}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-alternative_email"></p>  
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group mt-2">   
                                            <input type="hidden" name="user_id" value="{{ base64_encode($user->id) }}">         
                                            <button type="submit" class="btn btn-info update">Update</button>
                                        </div>	
                                </div>
                            </div>
                                <!--  -->
                        </form>
                    </div>
                </div>
                
            </div><!-- Footer Start -->
        </div>
        <div class="flex-grow-1"></div>
    </div>
</div>

<script>
    
    $(function(){

        // $('.btn').on('click', function() {
        //     var $this = $(this);
        //     var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        //     if ($(this).html() !== loadingText) {
        //     $this.data('original-text', $(this).html());
        //     $this.html(loadingText);
        //     }
        //     setTimeout(function() {
        //     $this.html($this.data('original-text'));
        //     }, 2000);
        // });


        $('.switch').on('change.bootstrapSwitch', function(e) {
            console.log(e.target.checked);
        });

    $('.customer').prop('selectedIndex',0);

    // $(document).on('change','.customer',function(e) {
    //     e.preventDefault();
    //     $('.slaList').empty();
    //     $('.slaList').append("<option value=''>-Select-</option>");
    //     $(".SLAResult").html("");

    //     var customer = $('.customer option:selected').val();
    //     $.ajax({
    //         type:"POST",
    //         url: "{{ url('/customers/sla/getlist') }}",
    //         data: {"_token": "{{ csrf_token() }}",'customer_id':customer},      
    //         success: function (response) {
    //             console.log(response);
    //             if(response.success==true  ) {   
    //                 $.each(response.data, function (i, item) {
    //                     $(".slaList").append("<option value='"+item.id+"'>" + item.title + "</option>");
    //                 });
    //             }
    //             //show the form validates error
    //             if(response.success==false ) {                              
    //                 for (control in response.errors) {   
    //                     $('#error-' + control).html(response.errors[control]);
    //                 }
    //             }
    //         },
    //         error: function (xhr, textStatus, errorThrown) {
    //             // alert("Error: " + errorThrown);
    //         }
    //     });
    //     return false;
        
    // });

    //on select sla item
    // $(document).on('change','.slaList',function(e) {
    //     e.preventDefault();
    //     $(".SLAResult").html("");
    //     var sla_id = $('.slaList option:selected').val();
    //     $.ajax({
    //     type:"POST",
    //     url: "{{ url('/customer/mixSla/serviceItems') }}",
    //     data: {"_token": "{{ csrf_token() }}",'sla_id':sla_id},      
    //     success: function (response) {
    //         console.log(response);
    //         if(response.success==true  ) {   
    //             $.each(response.data, function (i, item) {
                    
    //               if(item.checked_atatus){
    //                 $(".SLAResult").append("<div class='form-check form-check-inline'><input class='form-check-input services_list' type='checkbox' checked name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' data-type=''><label class='form-check-label' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
    //               }else{
    //                 $(".SLAResult").append("<div class='form-check form-check-inline'><input class='form-check-input services_list' type='checkbox' name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' data-type=''><label class='form-check-label' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
    //               }

    //             });
    //         }
    //         //show the form validates error
    //         if(response.success==false ) {                              
    //             for (control in response.errors) {   
    //                 $('#error-' + control).html(response.errors[control]);
    //             }
    //         }
    //     },
    //     error: function (xhr, textStatus, errorThrown) {
    //         // alert("Error: " + errorThrown);
    //     }
    //     });
    //     return false;
    // });

    $(document).on('change','.sla_type',function(){

        var type=$(this).val();
        var cust_id=$(this).attr('data-id');
        var status=$(this).attr('data-status');
        $('.sla_type_result').html("");
        // alert(type);
        if(type=='package')
        {
            if(status==0)
            {
                $('#days_type').val('{{$case_info->cust_days_type}}');
                $('.sla_type_result').html(`<div class="form-group"> <label for="service">Select a SLA <span class="text-danger">*</span></label> <select class="form-control slaList" name="sla" id="sla"> <option value="">-Select-</option> </select> <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla"></p> </div> <div class="form-group SLAResult" > </div> <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>`);
                $('.slaList').empty();
                $('.slaList').append("<option value=''>-Select-</option>");
                $(".SLAResult").html("");

                $.ajax({
                    type:"POST",
                    url: "{{ url('/customers/sla/getlist') }}",
                    data: {"_token": "{{ csrf_token() }}",'customer_id':cust_id},      
                    success: function (response) {
                        console.log(response);
                        if(response.success==true  ) {   
                            $.each(response.data, function (i, item) {
                                $(".slaList").append("<option value='"+item.id+"'>" + item.title + "</option>");
                            });
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
                // return false;
            }
            else
            {
                $('#days_type').val('{{$case_info->job_days_type}}');
                $('.sla_type_result').html(
                    `<?php $customer_sla=Helper::get_customer_sla($user->business_id); ?>
                    <div class="form-group"> 
                        <label for="service">
                            Select a SLA <span class="text-danger">*</span>
                        </label> 
                        <select class="form-control slaList" name="sla" id="sla"> 
                            <option value="">-Select-</option>
                            @if($customer_sla!=NULL && count($customer_sla)>0)
                                @foreach ($customer_sla as $sla)
                                    <option @if($sla->id==$job_items->sla_id) selected @endif value="{{$sla->id}}">{{$sla->title}}</option>
                                @endforeach
                            @endif
                        </select> 
                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla"></p> 
                    </div> 
                    <div class="form-group SLAResult">
                        @foreach($services as $service)
                            <div class='form-check form-check-inline disabled-link'>
                                <input class='form-check-input error-control services_list' type='checkbox' name='services[]' value='{{$service->id}}' id='{{$service->id}}' data-type='' readonly {{in_array($service->id, $selected_services_id) ? 'checked' : '' }}>
                                <label class='form-check-label error-control' for='{{$service->id}}'>{{$service->name}}</label>
                            </div>
                        @endforeach
                    </div> 
                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>`
                    );
            }

        }
        else if(type == 'variable')
        {
            if(status==1)
            {
                $('#days_type').val('{{$case_info->job_days_type}}');
            }
            else
            {
                $('#days_type').val('{{$case_info->job_days_type}}'); 
            }

            $('.sla_type_result').html(`
            <input type="hidden" name="sla" class="sla" id="sla" value="{{$variable->id}}">
            @if($case_info->sla_type=='variable')
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="pb-1" for="name">Price Type <span class="text-danger">*</span></label> <br>
                            <label class="radio-inline pr-2">
                            <input type="radio" class="price_type" name="price_type" value="package" @if(stripos($case_info->job_price_type,'package')!==false) checked data-status="1" @else data-status="0" @endif> Package-Wise </label> 
                            <label class="radio-inline"> 
                                <input type="radio" class="price_type" name="price_type" value="check" @if(stripos($case_info->job_price_type,'check')!==false) checked data-status="1" @else data-status="0" @endif> Check-Wise 
                            </label>
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-price_type"></p>
                        </div>
                    </div>
                </div>
                <div class="price_result @if(stripos($case_info->job_price_type,'package')!==false) mb-2 @endif" @if(stripos($case_info->job_price_type,'package')!==false) style="border:1px solid #ddd;padding:10px;width:50%" @endif>
                    @if(stripos($case_info->job_price_type,'package')!==false)
                        <div class="row">
                            <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Package Wise Price</div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Price <span class="text-danger">*</span> (<small class="text-muted"><i class="fas fa-rupee-sign"></i></small>)</label>
                                    <input class="form-control" type="text" name="price" value="{{$case_info->job_package_price}}">
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-price"></p>
                                </div>
                            </div>
                        </div> 
                    @endif
                </div>
                <div class="form-group SLAResult error-control"> 
                    @foreach ($services as $service) 
                        <div class="form-check form-check-inline"> 
                            <input class="form-check-input variable_services_list" type="checkbox" name="services[]" value="{{$service->id}}" data-string="{{$service->name}}" data-type="{{ $service->is_multiple_type }}" id="inlineCheckbox-{{ $service->id}}" data-verify={{$service->verification_type}} {{in_array($service->id, $selected_services_id) ? 'checked' : '' }}> 
                            <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label> 
                        </div> 
                    @endforeach 
                    <p style="margin-top:2px; margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
                </div> 
                <div class="service_result" style="border: 1px solid #ddd; padding:10px;margin-bottom:15px;"> 
                    <div class="row"> 
                        <div class="col-sm-12 mt-1 mb-2">
                            <span style="color:#dd2e2e">Configure Number of Verifications Need on each check item</span>
                            <span style="float: right;">
                                <span class="pr-2"> Total Checks:- <span class="total_checks">{{$total_checks}}</span></span>
                                <span class="total_p @if(stripos($case_info->job_price_type,'package')!==false) d-none @endif"> Total Price:- <i class='fas fa-rupee-sign'></i> <span class="total_check_price">{{$total_check_price}}</span></span>
                            </span>
                        </div> 
                    </div>
                    @foreach($sla_items as $item)
                        <p class='pb-border row-{{$item->service_id}}' id='row-{{$item->service_id}}'></p>
                        <div class='row mt-2' id='row-{{$item->service_id}}'>
                            <div class='col-sm-2'>
                                <label>{{$item->service_name}}</label>
                            </div>
                            <div class='col-sm-2'>
                                <input class='form-control' type='text' name='service_unit-{{$item->service_id}}' value='{{$item->number_of_verifications}}' @if($item->verification_type=='Auto' || $item->verification_type=='auto') readonly @endif>
                                <p style='margin-top:2px; margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-{{$item->service_id}}'></p>
                            </div>
                            <div class='col-sm-1'>
                                <label>TAT</label>
                            </div>
                            <div class='col-sm-2'>
                                <input class='form-control' type='text' name='tat-{{$item->service_id}}' value='{{$item->check_tat}}' placeholder='TAT' >
                                <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-{{$item->service_id}}'></p>
                            </div>
                            <div class='col-sm-2'>
                                <label>Incentive TAT</label>
                            </div>
                            <div class='col-sm-3'>
                                <input class='form-control' type='text' name='incentive-{{$item->service_id}}' value='{{$item->incentive_tat}}'>
                                <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-{{$item->service_id}}'></p>
                            </div>
                        </div>
                        <div class='row mt-2' id='row-{{$item->service_id}}'>
                            <div class='col-sm-2'></div>
                            <div class='col-sm-3 pt-2 text-right'>
                                <label>Penalty TAT</label>
                            </div>
                            <div class='col-sm-2'>
                                <input class='form-control' type='text' name='penalty-{{$item->service_id}}' value='{{$item->penalty_tat}}'>
                                <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-{{$item->service_id}}'></p>
                            </div>
                            <div class='col-sm-2 price_row @if(stripos($case_info->job_price_type,'package')!==false) d-none @endif pt-2'>
                                <label>Price (<small class='text-muted'>Per Item <i class='fas fa-rupee-sign'></i></small>)</label>
                            </div>
                            <div class='col-sm-3 price_row @if(stripos($case_info->job_price_type,'package')!==false) d-none @endif'>
                                <input class='form-control check_price' type='text' name='price-{{$item->service_id}}' value='{{$item->price}}' @if(stripos($case_info->job_price_type,'package')!==false) readonly @endif>
                                <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-{{$item->service_id}}'></p>
                            </div>
                        </div>
                    @endforeach 
                </div>
            @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="pb-1" for="name">Price Type <span class="text-danger">*</span></label> <br>
                            <label class="radio-inline pr-2">
                            <input type="radio" class="price_type" name="price_type" value="package"> Package-Wise </label> 
                            <label class="radio-inline"> 
                                <input type="radio" class="price_type" name="price_type" value="check" checked> Check-Wise 
                            </label>
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-price_type"></p>
                        </div>
                    </div>
                </div>
                <div class="price_result">
                                       
                </div>
                <div class="form-group SLAResult error-control"> 
                    @foreach ($services as $service) 
                    <div class="form-check form-check-inline"> 
                    <input class="form-check-input variable_services_list" type="checkbox" name="services[]" value="{{ $service->id}}" data-string="{{ $service->name  }}" data-type="{{ $service->is_multiple_type }}" id="inlineCheckbox-{{ $service->id}}" data-verify={{$service->verification_type}}> 
                    <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label> 
                    </div> 
                    @endforeach 
                    <p style="margin-top:2px; margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
                </div> 
                @if($case_info->sla_type=='variable')
                <div class="service_result" style="border: 1px solid #ddd; padding:10px;margin-bottom:15px;"> 
                    <div class="row"> 
                        <div class="col-sm-12 mt-1 mb-2">
                            <span style="color:#dd2e2e">Configure Number of Verifications Need on each check item</span>
                            <span style="float: right;">
                                <span class="pr-2"> Total Checks:- <span class="total_checks">0</span></span>
                                <span class="total_p"> Total Price:- <i class='fas fa-rupee-sign'></i> <span class="total_check_price">0.00</span></span>
                            </span>
                        </div> 
                    </div> 
                </div>  
                @endif 
            @endif`
            );
        }


    });

    //on select sla item
    $(document).on('change','.slaList',function(e) {
        e.preventDefault();
        $(".SLAResult").html("");
        var sla_id = $('.slaList option:selected').val();
        // alert(sla_id);
        $.ajax({ 
            type:"POST",
            url: "{{ url('/customer/mixSla/serviceItems') }}",
            data: {"_token": "{{ csrf_token() }}",'sla_id':sla_id},      
            success: function (response) {
                console.log(response);
                if(response.success==true  ) {   
                    $.each(response.data, function (i, item) {
                        
                    if(item.checked_atatus){$(".SLAResult").append("<div class='form-check form-check-inline disabled-link'><input class='form-check-input error-control services_list' type='checkbox' checked name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' data-type='' readonly><label class='form-check-label error-control' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
                    }else{
                        $(".SLAResult").append("<div class='form-check form-check-inline disabled-link'><input class='form-check-input error-control services_list' type='checkbox' name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' data-type='' readonly><label class='form-check-label error-control' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
                    }

                    });
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


    $(document).on('change','.variable_services_list',function() {

        var total_price = 0;

        var total_check = 0;

        if(this.checked)
        {
            var id =  $(this).attr("value");
            var text =  $(this).attr("data-string");
            var verify =$(this).attr("data-verify");

            var tat = 1;

            var readonly = '';

            var display_none = '';

            var price_type = $('.price_type:checked').val();

            if(price_type.toLowerCase()=='package'.toLowerCase())
            {
                readonly = 'readonly';

                display_none = 'd-none';
            }

            if(text.toLowerCase()=='Address'.toLowerCase())
            {
                tat=7;
            }
            else if(text.toLowerCase()=='Employment'.toLowerCase())
            {
                tat=5;
            }
            else if(text.toLowerCase()=='Educational'.toLowerCase())
            {
                tat=7;
            }
            else if(text.toLowerCase()=='Criminal'.toLowerCase())
            {
                tat=3;
            }
            else if(text.toLowerCase()=='Judicial'.toLowerCase())
            {
                tat=2;
            }
            else if(text.toLowerCase()=='Reference'.toLowerCase())
            {
                tat=2;
            }
            else if(text.toLowerCase()=='Covid-19 Certificate'.toLowerCase())
            {
                tat=5;
            }

            if(verify.toLowerCase()=="Auto".toLowerCase())
                $(".service_result").append("<p class='pb-border row-"+id+"'></p><div class='row row-"+id+" mt-2' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-2'><input class='form-control no_of_check' type='text' name='service_unit-"+id+"' value='1' readonly><p style='margin-top:2px; margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='tat-"+id+"' value='"+tat+"' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-"+id+"'></p></div><div class='col-sm-2'><label>Incentive TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='incentive-"+id+"' value='1'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-"+id+"'></p></div></div><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-2'></div><div class='col-sm-3 pt-2 text-right'><label>Penalty TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='penalty-"+id+"' value='"+tat+"'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-"+id+"'></p></div><div class='col-sm-2 price_row "+display_none+" pt-2'><label>Price (<small class='text-muted'>Per Item <i class='fas fa-rupee-sign'></i></small>)</label></div><div class='col-sm-3 price_row "+display_none+"'><input class='form-control check_price' type='text' name='price-"+id+"' value='0' "+readonly+"><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-"+id+"'></p></div></div>");
            else
                $(".service_result").append("<p class='pb-border row-"+id+"'></p><div class='row row-"+id+" mt-2' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-2'><input class='form-control no_of_check' type='text' name='service_unit-"+id+"' value='1' ><p style='margin-top:2px; margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='tat-"+id+"' value='"+tat+"' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-"+id+"'></p></div><div class='col-sm-2'><label>Incentive TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='incentive-"+id+"' value='1'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-"+id+"'></p></div></div><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-2'></div><div class='col-sm-3 pt-2 text-right'><label>Penalty TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='penalty-"+id+"' value='"+tat+"'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-"+id+"'></p></div><div class='col-sm-2 price_row "+display_none+" pt-2'><label>Price (<small class='text-muted'>Per Item <i class='fas fa-rupee-sign'></i></small>)</label></div><div class='col-sm-3 price_row "+display_none+"'><input class='form-control check_price' type='text' name='price-"+id+"' value='0' "+readonly+"><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-"+id+"'></p></div></div>");
        }
        else
        {
            var id =  $(this).attr("value");
            $("div#row-"+id).remove();
            $("p.row-"+id).remove();
        }

        $('.check_price').each(function () {
            if(!isNaN(parseFloat($(this).val())))
            {
                total_price = total_price + parseFloat($(this).val());
            }
        });

        $('.total_check_price').html(total_price.toFixed(2));

        $('.no_of_check').each(function(){
            var is_int = Number.isInteger(parseInt($(this).val()));
            if(is_int)
            {
                total_check = total_check + parseInt($(this).val());
            }
        });

        $('.total_checks').html(total_check);
   
    });

    $(document).on('change','.price_type',function(){
         if(this.checked)
         {
            
            $('.price_result').html('');
            $('.price_result').removeClass('mb-2');
            $('.price_result').removeAttr('style');

            var price_type = $('.price_type:checked').val();
            
            if(price_type.toLowerCase()=='package'.toLowerCase())
            {
               var status = $(this).attr('data-status');
               var price = '0';
               $('.price_result').addClass('mb-2');
               $('.price_result').css({'border': '1px solid #ddd','padding':'10px','width':'50%'});
               if(status==1)
               {
                   price = '{{$case_info->job_package_price}}';
               }
               $('.price_result').html("<div class='row'> <div class='col-sm-12 mt-1 mb-2' style='color:#dd2e2e'>Package-Wise Price</div> <div class='col-sm-6'> <div class='form-group'> <label>Price <span class='text-danger'>*</span> (<small class='text-muted'><i class='fas fa-rupee-sign'></i></small>)</label> <input class='form-control' type='text' name='price' value='"+price+"'> <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price'></p> </div> </div> </div>");

               $('.check_price').attr('readonly',true);

               $('.price_row').addClass('d-none');

               $('.total_p').addClass('d-none');

            }
            else
            {
                $('.check_price').attr('readonly',false);

                $('.price_row').removeClass('d-none');

                $('.total_p').removeClass('d-none');
            }
            
         }
         else
         {
            alert('Select One price type');
         } 
    });

    $(document).on('change keyup','.check_price',function(){

        var total_price = 0;

        $('.check_price').each(function () {
            if(!isNaN(parseFloat($(this).val())))
            {
                total_price = total_price + parseFloat($(this).val());
            }
        });

        $('.total_check_price').html(total_price.toFixed(2));
    });

    $(document).on('change keyup','.no_of_check',function(){

        var total_check = 0;
        $('.no_of_check').each(function(){
            var is_int = Number.isInteger(parseInt($(this).val()));
            if(is_int)
            {
                total_check = total_check + parseInt($(this).val());
            }
        });

        $('.total_checks').html(total_check);
    });

});

</script>

<script>
$(function(){

//    $('#createCandidateBtn').click(function(e) {
//         e.preventDefault();
//         $("#editCandidateForm").submit();
//     });

   $(document).on('submit', 'form#editCandidateForm', function (event) {
        event.preventDefault();
        //clearing the error msg
        $('p.error_container').html("");

        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        $('.update').attr('disabled',true);
        $('.form-control').attr('readonly',true);
        // $('.form-control').addClass('disabled-link');
        $('.error-control').addClass('disabled-link');
        if ($('.update').html() !== loadingText) {
              $('.update').html(loadingText);
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
                    $('.update').attr('disabled',false);
                    $('.form-control').attr('readonly',false);
                    // $('.form-control').removeClass('disabled-link');
                    $('.error-control').removeClass('disabled-link');
                    $('.update').html('Update');
                },2000);
                console.log(response);
                if(response.success==true  ) {          
                
                    //notify
                toastr.success("Candidate updated successfully");
                    // redirect to google after 5 seconds
                    window.setTimeout(function() {
                        window.location = "{{ url('/')}}"+"/candidates/";
                    }, 2000);
                
                }
                //show the form validates error
                if(response.success==false ) {    
                    var i=0;                          
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
            error: function (xhr, textStatus, errorThrown) {
                // alert("Error: " + errorThrown);
            }
        });
        return false;
   });
});

</script>

@endsection