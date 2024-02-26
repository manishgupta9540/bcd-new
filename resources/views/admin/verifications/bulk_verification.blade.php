@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
    <div class="main-content">
        <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                    <li>
                    <a href="{{ url('/home') }}">Dashboard</a>
                    </li>
                    <li>Instant Verification</li>
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
                               @include('admin.verifications.menu')
                            </div>

                            @if ($message = Session::get('success'))
                                <div class="col-md-12">
                                    <div class="alert alert-success">
                                        <strong>{{ $message }}</strong> 
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-8">
                                <h4 class="card-title mt-2 mb-1"> Verifications </h4>
                                <p> Bulk ID Checks</p>
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
                                           {{-- <p style="margin-bottom: 2px;" class="text-danger error-container " id="error-mandatory"></p>  --}}
                                        {{-- </div> --}}
                                        {{-- <button class="btn btn-secondary btn-lg dropdown-toggle" id="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  Actions  </button> --}}
                                            {{-- <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#"> Else Here</a></div>  --}}
                                        <!-- <a class="btn btn-success btn-lg" href="{!! url('/app/customers/create') !!} " > Add New </a>-->
                                </div>
                            </div>
                        </div>
                        <form class="mt-1" method="post" enctype="multipart/form-data" action="{{ url('/bulkVerifications/importExcel') }}" id="addCustomerFrm">
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
                                    <div class="multiple d-none ">
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
                                            <button class="btn btn-info import" type="button" >Import Verification Data</button>
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
<!-- Script -->
<script type="text/javascript">
    $(document).ready(function(){
        //Download format of Checks
        $(document).on('change', '.verification', function (e) {

            e.preventDefault();  //stop the browser from following
            var _this =$(this);
            var id=$('.verification').val();
            var service_name = $(".verification option:selected").attr('data-name');
            
            //console.log(id);
            //    alert(service_name);

            if (id=='2') {
                window.location.href = '/verification_format/Aadhaar.xlsx';
            } 
            else if(id=='3') {
                window.location.href = '/verification_format/PAN.xlsx';
            }
            else if(id=='4') {
                window.location.href = '/verification_format/Voter ID.xlsx';
            }
            else if(id=='7') {
                window.location.href = '/verification_format/RC.xlsx';
            }
            else if(id=='8') {
                window.location.href = '/verification_format/Passport.xlsx';
            }
            else if(id=='9') {
                window.location.href = '/verification_format/DL1.xlsx';
            }
            else if(id=='12') {
                window.location.href = '/verification_format/Bank Verification.xlsx';
            }
            else if(id=='14') {
                window.location.href = '/verification_format/GSTIN.xlsx';
            }
            else if(service_name.toLowerCase()=='upi verification')
            {
                window.location.href = '/verification_format/UPI.xlsx';
            }
            else if(service_name.toLowerCase()=='cin verification')
            {
                window.location.href = '/verification_format/CIN.xlsx';
            }
            else if(service_name.toLowerCase()=='court check v1')
            {
                window.location.href = '/verification_format/court_check_v1.xlsx';
            }
           
        });
        //hide and show import data
        $(document).on('change', '.services', function (e) {
            e.preventDefault();  //stop the browser from following
            var _current =$(this);
            var id=$('.services').val();
            if (id =='') {
                $(".multiple").addClass('d-none');
                // $(".multiple").hide();
            }
            else {
                $(".multiple").removeClass('d-none');
                // $(".multiple").show();
            }
        });
    });
    //Import Services Excel
    $('.import').on('click', function() {
        var form = $(this);
        var data = new FormData();
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
            url: "{{ url('/bulkVerifications/importExcel') }}",
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {
            
                console.log(response.fail);
                window.setTimeout(function(){
                     $(form).attr('disabled',false);
                     $(form).html('Import Verification Data');
                  },2000);
                if(response.fail == false)
                  {
                      window.open(response.zip);
                      $('#addCustomerFrm')[0].reset();
                      $(".multiple").addClass('d-none');
                  }

                  if(response.fail==true && response.error=='empty_file')
                  {
                    $('#error-csv_file').html('Please select a file!');
                  }
                  else if(response.fail == true)
                  {
                     //notify
                    toastr.error("Something went wrong!,please upload the valid details ");
                      $('#addCustomerFrm')[0].reset();
                      $(".multiple").addClass('d-none');
                  }
                  
            },
            error: function(data){
            console.log(data);
        } 
        });
        // return false;

    });
</script>
@endsection