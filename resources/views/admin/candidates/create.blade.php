@extends('layouts.admin')
<style>
    .disabled-link{
      pointer-events: none;
    }
  </style>
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
    <!-- ============ Body content start ============= -->
    <div class="main-content">				
        <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ url('/candidates') }}">Candidate</a></li>
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
            <div class="card text-left">
                
                <div class="card-body" style="">
                    <div class="col-12">
                        <section>
                            @include('admin.candidates.create.menu')
                        </section>
                        <marquee width="60%" direction="left" onmouseover="this.stop();" onmouseout="this.start();" height="50px">
                            Note<span class="text-danger">*</span>:- Click to download Excel format to create multiple candidate at a time. <a href="{{ env('EXCEL_PATH') }}" ><i class="far fa-hand-point-right"></i> Excel <i class="far fa-hand-point-left"></i></a> || <a href="{{ env('CSV_PATH') }}" ><i class="far fa-hand-point-right"></i> CSV <i class="far fa-hand-point-left"></i></a>
                        </marquee>
                    </div>
                    <div class="col-md-8 offset-md-2">
                        <form class="mt-2" method="post" id="addCandidateForm" action="{{ url('/candidates/store') }}" enctype="multipart/form-data">
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
                                
                                        <!-- select a customer  -->
                                        <div class="form-group">
                                            <label for="service">Select a Customer <span class="text-danger">*</span></label>
                                            <select class="form-control customer" name="customer" id="customer">
                                                <option value="">-Select-</option>
                                                @if( count($customers) > 0 )
                                                    @foreach($customers as $item)
                                                    <option value="{{ $item->id }}">{{ ucfirst($item->company_name).' - '.$item->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-customer"></p>
                                        </div>

                                        <div class="sla_row">
                                            
                                        </div>

                                        <div class="sla_type_result">

                                        </div>

                                        <!-- select a SLA of customer  -->
                                        <div class="row pt-2">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="name">Form Type <span class="text-danger">*</span></label>
                                                    <select name="form_type" class="form-control form_type" >
                                                        <option value="">-Select-</option>
                                                        <option value="single">Single</option>
                                                        <option value="multiple">Multiple</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="multiple d-none">
                                            <div class="form-group">
                                                <label for="service">Select a file</label>
                                                <input class="form-control file" type="file" id="csv_file" name="excelFile"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-file"></p>
                                                {{-- @if ($errors->has('csv_file'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('csv_file') }}
                                                    </div>
                                                @endif --}}
                                                </div>
                                                <button class="btn btn-info import" type="button" >Import User Data</button>

                                            
                                        </div>
                                        <div class="single d-none">
                                                <div class="row">
                                                
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="first_name">FRNID</label>
                                                            <input type="text" name="frnid" class="form-control" placeholder="FRNID" value="{{ old('frnid') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="first_name">Client Emp Code </label>
                                                            <input type="text" name="client_emp_code" class="form-control" placeholder="Client emp code" value="{{ old('client_emp_code') }}">
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
                                                            <label for="first_name">Middle Name</label>
                                                            <input type="text" name="middle_name" class="form-control" id="middle_name" placeholder="Enter middle name" value="{{ old('first_name') }}">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-middle_name"></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="name">Last Name </label>
                                                            <input type="text" name="last_name" class="form-control" id="last_name"  placeholder="Enter last name" value="{{ old('last_name') }}">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-last_name"></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="name">Father Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="father_name" class="form-control"  placeholder="Enter father name" value="{{ old('father_name') }}">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-father_name"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="name">Aadhar Number </label>
                                                            <input type="text" name="aadhar" class="form-control aadhar"  placeholder="Enter Aadhar Number" value="{{ old('aadhar') }}">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-aadhar"></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="name">Case Received Date <span class="text-danger">*</span></label>
                                                            <input type="text" name="case_received_date" class="form-control case_received_date commonDatepicker"  placeholder="" value="{{ old('case_received_date') }}" autocomplete="off">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-case_received_date"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                        <label for="name">DOB <span class="text-danger">*</span></label>
                                                        <input type="text" name="dob" class="form-control dob commonDatepicker"  placeholder="" value="{{ old('dob') }}" autocomplete="off">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dob"></p>
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
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Phone <span class="text-danger">*</span></label>
                                                            <input type="hidden" id="code" name ="primary_phone_code" value="91" >
                                                            <input type="hidden" id="iso" name ="primary_phone_iso" value="in" >
                                                            <input type="tel" name ="phone" id="phone1" class="number_only form-control" style='display:block' value="{{ old('phone') }}">
                                                            <small class="text-muted"></small>
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-phone"></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email" class="lbl_email">Email</label>
                                                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" value="{{ old('email') }}">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>  
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="alternative_email" class="lbl_alternative_email">Alternative Email</label>
                                                            <input type="email" name="alternative_email" class="form-control" id="alternative_email" placeholder="Enter Alternative Email" value="{{ old('email') }}">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-alternative_email"></p>  
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
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mt-4">
                                                            <label class="">
                                                                <input type="checkbox" name="assign_to" class="assign_to" id="assign_to" checked>
                                                            </label>
                                                            <div class='form-check form-check-inline '><label class='form-check-label' for='assign_to'>Self Assign(JAF Form)</label></div>  
                                                            <!-- Rounded switch -->
                                                        
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group jaf_div">
                                                            <label for="jaf">Select a JAF Filling Access <span class="text-danger jaf_req d-none">*</span></label>
                                                            <select class="form-control jaf" name="jaf" id="jaf_reset">
                                                                <option value="">-Select-</option>
                                                                <option value="customer" selected>Customer</option>
                                                                <option value="coc">COC</option>
                                                                <option value="candidate">Candidate</option>
                                                            </select>
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-jaf"></p>
                                                        </div>
                                                        <p class="text-danger jaf_note d-none">Note:- System will send the JAF link to the Candidate's email with login credentials.</p>
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
                                            
                                                <div class="form-group mt-2">    
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-user"></p>        
                                                        <button type="submit" class="btn btn-info submit">Submit</button>
                                                </div>
                                                
                                                {{-- <div class="form-group mt-2">
                                                    <div class='form-check form-check-inline'><label class='form-check-label' for=''>Send JAF Link</label></div>  
                                                    <!-- Rounded switch -->
                                                    <label class="switch">
                                                        <input type="checkbox" name="is_send_jaf_link"><span class="slider round"></span>
                                                    </label>
                                                </div>
                                                --}}
                                        </div>
                                            
                                    </div>
                                    <!--  -->
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
        </div>
        {{-- Modal for excel verification    --}}

        <div class="modal"  id="excel_data">
            <div class="modal-dialog" style="max-width: 96%;">
            <div class="modal-content" style=" max-width: 80%;">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Excel data Preview</h4>
                    {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
                </div>
                <p style="margin-left: 20px;">Note:- If any data will incorrect in any row then those candidate will not be created by the System.</p>

                <!-- Modal body -->
                <form method="post" action="{{ url('/candidates/multiple') }}" id="excel_form">
                @csrf
                <input type="hidden" name="customer_id" id="customer_id">
                <input type="hidden" name="sla_id"  id="sla_id">
                <input type="hidden" name="sla_type" id='sla_type'>
                <input type="hidden" name="unique_id"  id="unique_id" >
                <input type="hidden" name="service_id"  id="service_id">
                <input type="hidden" name="service_units" id="service_units">
                <input type="hidden" name="tats" id='tats'>
                <input type="hidden" name="incentives"  id="incentives" >
                <input type="hidden" name="penalties"  id="penalties">
                <input type="hidden" name="check_prices" id="check_prices">
                <input type="hidden" name="days_types" id="days_types">
                <input type="hidden" name="price_types" id="price_types">
                <input type="hidden" name="package_price" id="package_price">
                    <div class="modal-body ">
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive"> 
                                {{-- @if($VIEW_CUSTOMER_ACCESS) --}}
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                {{-- <th scope="col">#</th> --}}
                                                <th scope="col">Client emp code</th>
                                                <th scope="col">Entity code </th>
                                                <th scope="col">First Name</th>
                                                <th scope="col">Middle Name</th>
                                                <th scope="col">Last Name</th>
                                                <th scope="col">Father Name</th>
                                                <th scope="col">Aadhar Number</th>
                                                <th scope="col">DOB</th>
                                                <th scope="col">Gender</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Phone</th>
                                                <th scope="col">JAF Filling Access</th>
                                                <th scope="col">FRNID</th>
                                                <th scope="col">Recruiter</th>
                                                <th scope="col">Source</th>
                                                <th scope="col">Cost Code</th>
                                                <th scope="col">Grade</th>
                                                {{-- <th scope="col">Action</th> --}}

                                            </tr>
                                        </thead> 
                                        <tbody id="dummy_data">
                                            {{-- @foreach ($excel_dummy as $dummy)
                                                
                                        
                                                <tr>
                                                    <td>{{$dummy->client_emp_code}}</td>
                                                    <td>{{$dummy->entity_code}}</td>
                                                    <td>{{$dummy->first_name}}</td>
                                                    <td>{{$dummy->middle_name}}</td>
                                                    <td>{{$dummy->last_name}}</td>
                                                    <td>{{$dummy->father_name}}</td>
                                                    <td>{{$dummy->aadhar_number}}</td>
                                                    <td>{{$dummy->dob}}</td>
                                                    <td>{{$dummy->gender}}</td>
                                                    <td>{{$dummy->phone}}</td>
                                                    <td>{{$dummy->email}}</td>
                                                    <td>{{$dummy->jaf_filling_access}}</td>
                                                </tr>
                                            @endforeach --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        
                        <button type="submit" class="btn btn-info mutiple_submit" >Submit </button>
                        <button type="button" class="btn btn-danger mutiple_close" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
        
    </div>
</div>

<script>
    
$(function(){

    

    $('.switch').on('change.bootstrapSwitch', function(e) {
        console.log(e.target.checked);
    });

    $('.customer').prop('selectedIndex',0);

    $(document).on('change','.customer',function(e) {
        e.preventDefault();
        // $('.slaList').empty();
        // $('.slaList').append("<option value=''>-Select-</option>");
        // $(".SLAResult").html("");

        var customer = $('.customer option:selected').val();
        $('.sla_row').html("");
        $('.sla_type_result').html("");
        // alert(customer);
        if(customer!='')
        {
            $('.sla_row').html('<label for="name">SLA Type <span class="text-danger">*</span></label> <br><label class="radio-inline error-control pr-2"><input type="radio" class="sla_type" name="sla_type" value="package" data-id="'+customer+'"> Package </label> <label class="radio-inline error-control"> <input type="radio" class="sla_type" name="sla_type" value="variable" data-id="'+customer+'"> Variable SLA </label><p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla_type"></p>');
        }
        
        // $.ajax({
        //     type:"POST",
        //     url: "{{ url('/customers/sla/getlist') }}",
        //     data: {"_token": "{{ csrf_token() }}",'customer_id':customer},      
        //     success: function (response) {
        //         console.log(response);
        //         if(response.success==true  ) {   
        //             $.each(response.data, function (i, item) {
        //                 $(".slaList").append("<option value='"+item.id+"'>" + item.title + "</option>");
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
        // });
        // return false;
        
    });

    $(document).on('change','.sla_type',function(){
    
        var type=$(this).val();
        var cust_id=$(this).attr('data-id');
        $('.sla_type_result').html("");
        // alert(type);
        if(type=='package')
        {
            $('.sla_type_result').html('<div class="form-group"> <label for="service">Select a SLA <span class="text-danger">*</span></label> <select class="form-control slaList" name="sla" id="sla"> <option value="">-Select-</option> </select> <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla"></p> </div> <div class="form-group SLAResult" > </div> <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>');
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
        else if(type =='variable')
        {
            $('.sla_type_result').html(`<div class="row"> <div class="col-sm-6"> <div class="form-group"> <label class="pb-1" for="name">Days Type <span class="text-danger">*</span></label> <br> <label class="radio-inline error-control pr-2"> <input type="radio" class="days_type" name="days_type" value="working"> Working Days </label> <label class="radio-inline error-control"> <input type="radio" class="days_type" name="days_type" value="calender" > Calender Days </label> <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-days_type"></p> </div>
                                        </div> 
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
                                       </div> </div>
                                       <div class="price_result">
                                       
                                       </div>
                                       <input type="hidden" name="sla" class="sla" id="sla" value="{{$variable->id}}">
                                       <div class="form-group SLAResult"> 
                                            @foreach ($services as $service) 
                                                <div class="form-check form-check-inline error-control"> 
                                                    <input class="form-check-input variable_services_list" type="checkbox" name="services[]" value="{{ $service->id}}" data-string="{{ $service->name  }}" data-type="{{ $service->is_multiple_type }}" id="inlineCheckbox-{{ $service->id}}" data-verify={{$service->verification_type}}> 
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
                                                        <span class="pr-2"> Total Checks:- <span class="total_checks">0</span></span>
                                                        <span class="total_p"> Total Price:- <i class='fas fa-rupee-sign'></i> <span class="total_check_price">0.00</span></span>
                                                    </span>
                                                </div> 
                                            </div> 
                                        </div>`);
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

                    if(response.sla.price_type == 'check'){
                        $(".SLAResult").append(`<div class='service_result' style='border: 1px solid #ddd; padding:10px;margin-bottom:15px;'>
                            <div class='row'>
                                <div class='col-sm-12 mt-1 mb-2'>
                                    <span style='color:#dd2e2e'>Configure Number of Verifications Need on each check item</span>
                                    <span style='float: right;'><span class='pr-2'> Total Checks:- <span class='total_checks'>${response.total_checks}</span></span>
                                    <span class='total_p'> Total Price:- <i class='fas fa-rupee-sign'></i><span class='total_check_price'>${response.total_check_price}</span></span>
                                    </span>
                                </div>
                                </div>`); 

                           
                           
                            $.each(response.sladata, function (j, items) {
                                var ronly = '';
                                if(items.verification_type=='Auto')
                                {
                                    ronly = 'readonly';
                                }
                                $(".SLAResult").append('<p class="pb-border row-"'+items.service_id+'"></p>\
                                                <div class="row mt-2" id="'+items.service_id+'">\
                                                    <div class="col-sm-2">\
                                                        <label>'+items.service_name+'</label>\
                                                    </div>\
                                                    <div class="col-sm-2">\
                                                        <input class="form-control no_of_check countvalue" data-v="'+items.number_of_verifications+'" id="countvalue" type="number" min="1" name="service_unit-'+items.sla_item_id+'" value="'+items.number_of_verifications+'"'+ronly+'>\
                                                         <h6 class="includes d-none" style="margin-top: 5px;"> Excluded <span class="service_value_include"></span></h6>\
                                                         <h6 class="excludes d-none" style="margin-top: 5px;">Included <span class="service_value_exclude"></span></h6>\
                                                        <p style="margin-top:2px; margin-bottom: 2px;" class="text-danger error_container" id="error-service_unit-'+items.sla_item_id+'"></p>\
                                                    </div>\
                                                    <div class="col-sm-1"><label>TAT</label></div>\
                                                    <div class="col-sm-2">\
                                                        <input class="form-control" type="text" name="tat-'+items.sla_item_id+'" value="'+items.check_tat+'" placeholder="TAT" readonly>\
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tat-'+items.sla_item_id+'"></p>\
                                                    </div>\
                                                    <div class="col-sm-2"><label>Incentive TAT</label>\</div>\
                                                    <div class="col-sm-3">\
                                                        <input class="form-control" type="text" name="incentive-'+items.sla_item_id+'" value="'+items.incentive_tat+'" readonly>\
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-incentive-'+items.sla_item_id+'"></p>\
                                                    </div>\
                                                </div>\
                                                    <div class="row mt-2 row-27" id="row-27">\
                                                        <div class="col-sm-2"></div>\
                                                        <div class="col-sm-3 pt-2 text-right"><label>Penalty TAT</label>\
                                                    </div>\
                                                    <div class="col-sm-2">\
                                                        <input class="form-control" type="text" name="penalty-'+items.sla_item_id+'" value="'+items.penalty_tat+'" readonly>\
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-penalty-'+items.sla_item_id+'"></p>\
                                                    </div>\
                                                    <div class="col-sm-2 price_row  pt-2"><label>Price (<small class="text-muted">Per Item <i class="fas fa-rupee-sign"></i></small>)</label>\
                                                    </div><div class="col-sm-3 price_row"><input class="form-control check_price" type="text" name="price-'+items.sla_item_id+'" value="'+items.price+'" readonly>\
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-price-'+items.sla_item_id+'"></p>\
                                                    </div>');
                                     
                            });  
                            $(".SLAResult").append(`</div>`);
                        }
                        
                                  
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
            $(".service_result").append("<p class='pb-border row-"+id+"'></p><div class='row row-"+id+" mt-2' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-2'><input class='form-control no_of_check' type='text' name='service_unit-"+id+"' value='1' readonly><p style='margin-top:2px; margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='tat-"+id+"' value='"+tat+"' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-"+id+"'></p></div><div class='col-sm-2'><label>Incentive TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='incentive-"+id+"' value='1'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-"+id+"'></p></div></div><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-2'></div><div class='col-sm-3 pt-2 text-right'><label>Penalty TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='penalty-"+id+"' value='"+tat+"'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-"+id+"'></p></div><div class='col-sm-2 price_row "+display_none+" pt-2'><label>Price (<small class='text-muted'>Per Item <i class='fas fa-rupee-sign'></i></small>)</label></div><div class='col-sm-3 price_row'><input class='form-control check_price' type='text' name='price-"+id+"' value='0' "+readonly+"><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-"+id+"'></p></div></div>");
        else
            $(".service_result").append("<p class='pb-border row-"+id+"'></p><div class='row row-"+id+" mt-2' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-2'><input class='form-control no_of_check' type='text' name='service_unit-"+id+"' value='1' ><p style='margin-top:2px; margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='tat-"+id+"' value='"+tat+"' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-"+id+"'></p></div><div class='col-sm-2'><label>Incentive TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='incentive-"+id+"' value='1'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-"+id+"'></p></div></div><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-2'></div><div class='col-sm-3 pt-2 text-right'><label>Penalty TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='penalty-"+id+"' value='"+tat+"'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-"+id+"'></p></div><div class='col-sm-2 price_row "+display_none+" pt-2'><label>Price (<small class='text-muted'>Per Item <i class='fas fa-rupee-sign'></i></small>)</label></div><div class='col-sm-3 price_row'><input class='form-control check_price' type='text' name='price-"+id+"' value='0' "+readonly+"><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-"+id+"'></p></div></div>");
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
           $('.price_result').addClass('mb-2');
           $('.price_result').css({'border': '1px solid #ddd','padding':'10px','width':'50%'});
           $('.price_result').html(`<div class="row">
                                      <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Package-Wise Price</div>
                                      <div class="col-sm-6">
                                         <div class="form-group">
                                            <label>Price <span class="text-danger">*</span> (<small class="text-muted"><i class="fas fa-rupee-sign"></i></small>)</label>
                                            <input class="form-control" type="text" name="price" value="0">
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-price"></p>
                                         </div>
                                      </div> 
                                   </div>`);

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


    $(document).on('change keyup','.countvalue',function(){
        var _this = $(this);
        var includecount =  $(this).attr('data-v');
       
        var addvalue = _this.val();
        var excludevalue = includecount - addvalue;
       
        if(excludevalue < 0)
        {
            var exclude =  Math.abs(excludevalue);
            
            _this.parent().find(".includes").addClass("d-none");

            _this.parent().find(".excludes").removeClass("d-none");
            _this.parent().find('.service_value_exclude').html(exclude);
        }
        else if(excludevalue > 0)
        {
            var included = excludevalue;
           
            _this.parent().find(".excludes").addClass("d-none");

            _this.parent().find(".includes").removeClass("d-none");
            _this.parent().find('.service_value_include').html(included);
        }
     
    });
});

</script>

<script>
$(function(){

        // $('.submit').on('click', function() {
        //     var $this = $(this);
        //     var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        //     if ($(this).html() !== loadingText) {
        //       $this.data('original-text', $(this).html());
        //       $this.html(loadingText);
        //     }
        //     setTimeout(function() {
        //       $this.html($this.data('original-text'));
        //     }, 10000);
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
        $('.close').attr('disabled',true);
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
                        $('.close').attr('disabled',false);
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


 $(document).on('change','.form_type',function() {

        var form_value = $('.form_type option:selected').val();
        if (form_value == 'single') {
            $(".single").removeClass('d-none');
            $(".multiple").hide();
            $(".single").show();
            
        }
        else if (form_value == 'multiple') {
            $(".multiple").removeClass('d-none');
            $(".single").hide();
            $(".multiple").show();
        }
        else
        {
            $(".single").removeClass('d-none');
            $(".multiple").hide();
            $(".single").hide();
            $(".single").removeClass('d-none');
        }
        // alert(form_value);
 });

</script>
<script>
    
    $('.import').on('click', function() {
        var $this = $(this);
        // alert($this.data('file'));
        // var data = ;
        // console.log(data);
        var types = [];
        $("input[name='services[]']:checked").each(function() {
            
            types.push($(this).val());
        });
        var sla_type="";
    
        sla_type=  $("input[name='sla_type']:checked").val() ;
        var  package_price=  $("input[name='price']").val() ;
                
        var price_type = $('.price_type:checked').val()!=undefined ? $('.price_type:checked').val() : '';
        var days_type = $('.days_type:checked').val()!=undefined ? $('.days_type:checked').val() : '';
        //    alert(days_type);
        var service_unit=[]; 
        var tat=[];
        var incentive=[];
        var penalty=[];
        var prices=[];
            types.forEach(function(e,f){
                service_unit.push($("input[name='service_unit-"+e+"']").val());
                tat.push($("input[name='tat-"+e+"']").val());
                incentive.push($("input[name='incentive-"+e+"']").val());
                penalty.push($("input[name='penalty-"+e+"']").val());
                prices.push($("input[name='price-"+e+"']").val());
                // console.log(f);
            });
        var import_file =$('#csv_file')[0].files[0];
        // if (import_file==undefined) {
        //      import_file = null;
        // } 
                // console.log(service_unit);
            // return false;

            // var service =JSON.stringify(types);

        var formData = new FormData();
        
    
        formData.append('customer',$('#customer').val());
        formData.append('sla',$('#sla').val());
        formData.append('sla_type',sla_type);
        formData.append('services',Array.from(types));
        formData.append('service_unit',service_unit);
        formData.append('tat',tat);
        formData.append('incentive',incentive);
        formData.append('penalty',penalty);
        formData.append('prices',prices);
        formData.append('file',import_file);
        formData.append('days_type',days_type);
        formData.append('price_type',price_type);


        $('#customer_id').val($('#customer').val());
        $('#sla_id').val($('#sla').val());
        $('#sla_type').val(sla_type);
        $('#service_units').val(service_unit);
        $('#service_id').val(types);
        $('#tats').val(tat);
        $('#incentives').val(incentive);
        $('#penalties').val(penalty);
        $('#check_prices').val(prices);
        $('#days_types').val(days_type);
        $('#price_types').val(price_type);
        $('#package_price').val(package_price);


        // console.log(formData);
        // console.log($('form#addCandidateForm').serialize());
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        if ($(this).html() !== loadingText) {
        $this.data('original-text', $(this).html());
        $this.html(loadingText);
        }
        setTimeout(function() {
        $this.html($this.data('original-text'));
        }, 5000);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.error_container').html('');
        $.ajax({
            type: 'POST',
            url:"{{ url('/candidates/importExcel') }}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (data) {
                // console.log(data.success);
                $('.error-container').html('');
                // if (data.fail && data.error == '') {
                //     //    console.log(data.success);
                //     $('.error').html(data.message);
                //     }
                
                
                if (data.success == true ) {
                    $('#unique_id').val(data.unique_excel_id);
                    $("#dummy_data").html(data.excel);
                    $("#excel_data").modal("show");
                    
                    
                    // window.location.href='{{ Config::get('app.admin_url')}}/candidates';
                }
                //show the form validates error
                if(data.success==false ) {                              
                    for (control in data.errors) {   
                        $('#error-' + control).html(data.errors[control]);
                    }
                }
            },
            error: function(data){
                console.log(data);
            } 
        
            
        });

    });

    $(document).on('submit', 'form#excel_form', function (event) {
        event.preventDefault();
        //clearing the error msg
        $('p.error_container').html("");
        $('.form-control').removeClass('border-danger');
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            $('.mutiple_submit').attr('disabled',true);
            $('.mutiple_close').attr('disabled',true);
            $('.form-control').attr('readonly',true);
            $('.form-control').addClass('disabled-link');
            $('.error-control').addClass('disabled-link');
            if ($('.mutiple_submit').html() !== loadingText) {
                $('.mutiple_submit').html(loadingText);
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
                    $('.mutiple_submit').attr('disabled',false);
                    $('.mutiple_close').attr('disabled',false);
                    $('.form-control').attr('readonly',false);
                    $('.form-control').removeClass('disabled-link');
                    $('.error-control').removeClass('disabled-link');
                    $('.mutiple_submit').html('Submit');
                    $('.mutiple_close').html('Close');
                },2000);

                console.log(response);
                if(response.fail==false) {          
                    // window.location = "{{ url('/')}}"+"/sla/?created=true";
                    toastr.success('All candidates have been created successfully.');
                    window.setTimeout(function(){
                        window.location = "{{ url('/')}}"+"/candidates/";
                    },2000);
                }
                //show the form validates error
                if(response.success==true ) {                              
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

    $(document).on('change', '.jaf', function (event) {
   
        var jaf_value = $('.jaf option:selected').val();

        if (jaf_value == 'candidate') {
            // $(".single").removeClass('d-none');
            $(".jaf_file").hide();
            // $(".single").show();

            $('.jaf_note').removeClass('d-none');
            
        }
        // else if(jaf_value == 'customer')
        // {
        //     // $("#assign_to").prop("checked", true);
        //     $(".jaf_file").show();
        // }
        else {
            // $(".multiple").removeClass('d-none');
        // $(".single").hide();
        $(".jaf_file").show();
        $('.jaf_note').addClass('d-none');
        }

        if(jaf_value!='customer')
        {
            $("#assign_to").prop("checked", false);
        }
       
    });

    $(document).on('click', '.assign_to', function (event) {
   
        var assign_to = $("input[name='assign_to']:checked").val();
        //    alert(assign_to);
        if (assign_to == 'on') {  
            // $(".single").removeClass('d-none');
            // $('#jaf_reset').val('');
            // $(".jaf_div").hide();

            $("#jaf_reset").val("customer").change();
            $('.jaf_req').addClass('d-none');
            //    window.reset('.jaf_div');
            // $(".single").show();
            
        }
        else {
            // $(".multiple").removeClass('d-none');     
        // $(".single").hide();
        // $('#jaf_reset').val('');

        $('.jaf_req').removeClass('d-none');
        
        // $(".jaf_div").show();
        
      }
  
    });

    $(document).on('change','#jaf_reset',function(event){
        var _this=$(this);

        $('.lbl_email').html('Email');

        if(_this.val().toLowerCase()=='candidate')
        {
            $('.lbl_email').html('Email <span class="text-danger">*</span>');
        }
    });

</script>

@endsection