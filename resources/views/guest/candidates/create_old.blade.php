@extends('layouts.guest')
@section('content')
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
            <div class="card text-left">
                <div  class="card-body" style="">
                    <div class="col-md-8 offset-md-2">
                        <form class="mt-2" method="post" id="addCandidateForm" action="{{ url('/guest/candidates/store') }}" enctype="multipart/form-data">
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
                                        <div class="form-group">
                                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" class="form-control first_name" id="first_name" placeholder="Enter first name" value="{{ old('first_name') }}">
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-first_name"></p>
                                        </div>
                
                                        <div class="form-group">
                                            <label for="first_name">Middle Name</label>
                                            <input type="text" name="middle_name" class="form-control" id="middle_name" placeholder="Enter middle name" value="{{ old('first_name') }}">
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-middle_name"></p>
                                        </div>
                
                                        <div class="form-group">
                                            <label for="name">Last Name </label>
                                            <input type="text" name="last_name" class="form-control last_name" id="last_name"  placeholder="Enter last name" value="{{ old('last_name') }}">
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-last_name"></p>
                                        </div>
                
                                        <div class="form-group">
                                            <label for="name">Father Name <span class="text-danger">*</span></label>
                                            <input type="text" name="father_name" class="form-control father_name"  placeholder="Enter father name" value="{{ old('father_name') }}">
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-father_name"></p>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Aadhar Number </label>
                                            <input type="text" name="aadhar" class="form-control aadhar"  placeholder="Enter Aadhar Number" value="{{ old('aadhar') }}">
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-aadhar"></p>
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
                                                    <input type="hidden"  id="code" name ="primary_phone_code" value=" 91 " >
                                                    <input type="hidden"  id="iso" name ="primary_phone_iso" value=" in " >
                                                    <input type="tel" name ="phone" id="phone1" class="number_only form-control phone" style='display:block' value="{{ old('phone') }}">
                                                    <small class="text-muted"></small>
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-phone"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" name="email" class="form-control email" id="email" placeholder="Enter email" value="{{ old('email') }}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>  
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="form-group mt-2">    
                                             <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-user"></p>        
                                             <button type="submit" class="btn btn-primary submit">Submit</button>
                                        </div>
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
<script>
    // alert("hi");
    $(document).ready(function(){
        $('.submit').on('click', function() {
                var $this = $(this);
                var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
                if ($(this).html() !== loadingText) {
                $this.data('original-text', $(this).html());
                $this.html(loadingText);
                }
                setTimeout(function() {
                $this.html($this.data('original-text'));
                }, 3000);
        });

        $(document).on('submit', 'form#addCandidateForm', function (event) {
            event.preventDefault();
            //clearing the error msg
            $('p.error_container').html("");
            $('.form-control').removeClass('border-danger');
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            $('.submit').attr('disabled',true);
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
                    },3000);

                    console.log(response);
                    if(response.success==true) {          
                        // window.location = "{{ url('/')}}"+"/sla/?created=true";
                        toastr.success('Candidate Created Successfully');
                        window.setTimeout(function(){
                            window.location = "{{ url('/guest/')}}"+"/candidates/";
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