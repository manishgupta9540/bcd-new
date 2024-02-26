@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
    <div class="main-content">
        <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ url('/candidates') }}">Candidate Bulk</a></li>
                    <li>Create New</li>
                </ul>
            </div>
            <!-- ============Back Button ============= -->
            <div class="col-sm-1 back-arrow">
                <div class="text-right">
                <a href="{{url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card text-left"> 
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                {{-- @include('admin.verifications.menu') --}}
                                @include('admin.candidates.create.menu')
                                {{-- <ul class="nav nav-tabs nav-tabs-bottom">
                                    <li class="nav-item"><a href="{{ route('/idChecks') }}" class="nav-link ">Instant ID Checks</a></li>
                                    <li class="nav-item"><a href="{{ route('/bulkVerifications') }}" class="nav-link ">Instant Bulk Verifications</a></li>
                                    <li class="nav-item"><a href="{{ route('/verifications') }}" class="nav-link">Manual Verifications</a></li>
                                    <li class="nav-item"><a href="" class="nav-link active">Bulk Criminal Verification</a></li>
                                </ul> --}}
                            </div>

                            @if ($message = Session::get('success'))
                                <div class="col-md-12">
                                    <div class="alert alert-success">
                                        <strong>{{ $message }}</strong> 
                                    </div>
                                </div>
                            @endif
                                <div class="col-md-8">
                                    <h4 class="card-title mt-2 mb-1"> Manual Verification </h4>
                                    <p> Bulk Manual Checks</p>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" style="float:right">
                                        {{-- <div class="form-group"> --}}
                                        <label for="label_name"> Select to Download Verification format </label>
                                        <select class="form-control verification" name="verification">
                                            <option value=""> -Select- </option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}" data-name="{{$service->name}}"> {{ $service->name }} </option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                </div>
                           
                        </div>
                        <form class="mt-1" method="post" enctype="multipart/form-data" action="" id="addCustomerFrm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" >
                                        {{-- <div class="form-group"> --}}
                                        <label for="label_name"> Services </label>
                                        <select class="form-control services" name="services">
                                            <option value=""> -Select- </option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}"> {{ $service->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="multiple  ">
                                        <div class="form-group">
                                            <label for="service">Select a file</label>
                                            <input class="form-control file" type="file" id="csv_file" name="excelFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-csv_file"></p>
                                            @if ($errors->has('csv_file'))
                                            <div class="error text-danger">
                                                {{ $errors->first('csv_file') }}
                                            </div>
                                            @endif
                                        </div>
                                        <span class="text-danger">Note:- The number of checks should not be more than 5.</span><br>
                                        <button class="btn btn-info import" type="button">Import Data</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal"  id="excel_data">
    <div class="modal-dialog" style="max-width: 96%;">
    <div class="modal-content" style=" max-width: 80%;">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Excel data Preview</h4>
            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
        </div>
        <p style="margin-left: 20px; color: red;">Note:- If any data will incorrect in any row then those candidate will not be created by the System.</p>

        <!-- Modal body -->
        <form method="post" action="{{ url('/criminal/multiple') }}" id="excel_form">
        @csrf
            <input type="hidden" name="unique_id"  id="unique_id" >
            <input type="hidden" name="service_id"  id="service_id" >
            <div class="modal-body ">
                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" style="max-height:300px; overflow-y:scroll"> 
                        {{-- @if($VIEW_CUSTOMER_ACCESS) --}}
                                    <table class="table table-bordered" id="dummy_data">
                                        {{-- <thead>
                                            <tr>
                                               
                                                <th scope="col">Client ID</th>
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
                                                <th scope="col">No. of checks</th>
                                                <th scope="col">Price <small>(price is in Rupee) </small></th>
                                                <th scope="col">Address-1</th>
                                                <th scope="col">Address Type-1</th>
                                                <th scope="col">Address-2</th>
                                                <th scope="col">Address Type-2</th>
                                                <th scope="col">Address-3</th>
                                                <th scope="col">Address Type-3</th>
                                                <th scope="col">Address-4</th>
                                                <th scope="col">Address Type-4</th>
                                                <th scope="col">Address-5</th>
                                                <th scope="col">Address Type-5</th>
                                            </tr>
                                        </thead>  --}}
                                        
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
<!-- Script -->
<script type="text/javascript">
$(document).ready(function(){
    $(document).on('change', '.verification', function (e) {
        e.preventDefault();  //stop the browser from following
        var _this =$(this);
        var id=$('.verification').val();
        var service_name = $(".verification option:selected").attr('data-name');

        console.log(id);
        //  alert(service_name);

        if (id=='11') {
            window.location.href = '/excel/education_excel_format -1.xlsx';
        } 
        else if(id=='15') {
            window.location.href = '/excel/criminal_excel_format.xlsx';
        }
    });
    // 
    $(document).on('focusout','.exceldata', function() {
        var _this =$(this);
       var current= $(this).text();
       var id = $(this).closest("td").find("input[type=hidden]").val();
       var name =$(this).closest("td").attr("data-value");
   
        console.log(name);
        $.ajax({
            type: 'GET',
            url: "{{url('/')}}"+'/criminal/update/?id='+id+'&field_value='+current+'&field_name='+name,
            // data:  {'id': id,'field_name':current},
        
            success: function (response) {
                if (response.fail == false ) {
                    console.log('pahuch gya');
                    _this.closest("td").find("span").removeClass('text-danger');
                    _this.closest("td").find("span").prop('contenteditable', 'false');
                }
                if(response.fail==true && response.error=='required' ) {                              
                    // for (control in response.errors) {   
                        _this.closest("td").find("span").html('Required');
                    // }
                    
                }
                //show the form validates error
                if(response.fail==true && response.error=='unique' || response.fail==true ) {                              
                    // for (control in response.errors) {   
                    //     $('#error-' + control).html(response.errors[control]);
                    // }
                }
                  
            },
            // error: function(data){
            // console.log(data);
            // } 
        });
        // alert('test');

    });


    //Import Services Excel
    $('.import').on('click', function() {
        var form = $(this);
        var data = new FormData();
        var service=$('.services').val();
        // alert(service);
        data.append('service_id',$('.services option:selected').val());
        data.append('file', $('#csv_file')[0].files[0]);
        var url = form.attr("action");
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            $('#error-csv_file').html('');
        // if ($(this).html() !== loadingText) {
        //     form.data('original-text', $(this).html());
        //     form.html(loadingText);
        // }
        $(form).attr('disabled',true);
         if($(form).html()!=loadingText)
         {
            $(form).html(loadingText);
         }
        // setTimeout(function() {
        // form.html(this.data('original-text'));
        // }, 5000);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: "{{ url('/bulk/criminal/importExcel') }}",
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {
            
                console.log(response.fail);
                // $('.error-container').html('');
                window.setTimeout(function(){
                    $(form).attr('disabled',false);
                    $(form).html('Import Verification Data');
                  },2000);
                 
                if (response.fail == false ) {
                    var service=$('.services').val();
                    $('#service_id').val(service);
                    // console.log(service);
                    $('#unique_id').val(response.unique_excel_id);
                    $("#dummy_data").html(response.excel);
                    $("#excel_data").modal("show");
                    $('#excel_data table tr td .exceldata').first().focus();
                    
                    // $( ".exceldata" ).focus();
                    
                    // window.location.href='{{ Config::get('app.admin_url')}}/candidates';
                }
                if(response.fail==true && response.error=='wrong' ) {                              
                    // for (control in response.errors) {   
                    //     $('#error-' + control).html(response.errors[control]);
                    // }
                    $('#error-csv_file').html('Please select a correct file !');
                }
                if(response.fail==true && response.error=='empty_file' ) {                              
                    // for (control in response.errors) {   
                    //     $('#error-' + control).html(response.errors[control]);
                    // }
                    $('#error-csv_file').html('Please select a file!');
                }
                //show the form validates error
                if(response.fail==true ) {                              
                    for (control in response.errors) {   
                        $('#error-' + control).html(response.errors[control]);
                    }
                }
                  
            },
            error: function(data){
            console.log(data);
            } 
        });
        // return false;

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
                    toastr.success('All correct candidates have been created successfully.');
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
 });
</script>
@endsection