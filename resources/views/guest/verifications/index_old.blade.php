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
                <li>Verification</li>
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
                        @if($guest_v!=NULL)
                            <form class="mt-2" method="post" id="verificationForm" action="{{ url('/guest/candidates/verification/store') }}" enctype="multipart/form-data">
                                @csrf
                                <h5 class="card-title mb-1" style="">Candidate Details :</h5> 
                                <div class="row" style="border: 1px solid #ddd; padding:10px;">
                                        <div class="col-md-4">
                                            <p class="mb-1" style="">Name: {{$candidate->name}}</p> 
                                        </div>
                                        <div class="col-md-4">
                                            <p class=" mb-1" style="">Email: {{$candidate->email}}</p> 
                                        </div>
                                        <div class="col-md-4">
                                            <p class=" mb-1" style="">Contact Number: {{$candidate->phone}}</p> 
                                        </div>
                                </div>
                                <div class="row">
                                        @if ($message = Session::get('error'))
                                            <div class="col-md-12">   
                                                <div class="alert alert-danger">
                                                <strong>{{ $message }}</strong> 
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-10 pt-3">
                                        <h4 class="card-title mb-1" style="border-bottom:1px solid #ddd;">Start Verification </h4> 
                                            <p class="mt-1"> Fill the required details </p>			
                                        </div>

                                        <div class="col-md-10">	
                                            <input type="hidden" name="candidate_id" value="{{base64_encode($candidate->id)}}">
                                            <div class="form-group">
                                                <label>Select Services <span class="text-danger">*</span></label>
                                                
                                                <div class="col-sm-12">
                                                <div class="form-group">
                                                    @foreach($services as $service)
                                                        <?php $check_service=Helper::get_verification_service($guest_v->id,$service->id);?>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input services_list" type="checkbox" name="services[]" value="{{ $service->id}}" data-string="{{ $service->name  }}" id="inlineCheckbox-{{ $service->id}}" data-verify="{{$service->verification_type}}" @if($check_service!=NULL) checked @endif>
                                                            <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <p style="margin-bottom: 10px;" class="text-danger error_container" id="error-services"></p>
                                                <div class="service_result" style="border: 1px solid #ddd; padding:10px;">
                                                    <div class="row">
                                                        <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Required all of the selected service</div>
                                                    </div>
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
                                                                <div class='row mt-2' id='row-{{$guest_service->service_id}}'>
                                                                    <div class='col-sm-6'>
                                                                        <label>{{ucwords($keys1)}}</label>
                                                                        <input class='form-control' type='text' name='service_unit-{{$guest_service->service_id}}' value="{{$values[0]}}">
                                                                        <p style='margin-top:2px;margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-{{$guest_service->service_id}}'></p>
                                                                    </div>
                                                                    <div class='col-sm-6'>
                                                                        <label>{{ucwords($keys2)}}</label>
                                                                        <input class='form-control' type='text' name='notes-{{$guest_service->service_id}}' placeholder='{{$service->name}}' value="{{$values[1]}}">
                                                                        <p style='margin-top:2px;margin-bottom: 2px;' class='text-danger error_container' id='error-notes-{{$guest_service->service_id}}'></p>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class='row mt-2' id='row-{{$guest_service->service_id}}'>
                                                                    <?php 
                                                                        $k_arr=explode('_',$keys[0]);
                                                                        $keys=implode(' ',$k_arr);
                                                                    ?>
                                                                    <div class='col-sm-6'>
                                                                        <label>{{ucwords($keys)}}</label>
                                                                    </div>
                                                                    <div class='col-sm-6'>
                                                                        <input class='form-control' type='text' name='service_unit-{{$guest_service->service_id}}' value="{{$values[0]}}">
                                                                        <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-{{$guest_service->service_id}}'></p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error_container" id="error-check"></p>
                                                </div>
                                            </div>
                                        
                                            <div class="form-group text-right mt-2">            
                                                <button type="submit" class="btn btn-primary submit">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @else
                            <form class="mt-2" method="post" id="verificationForm" action="{{ url('/guest/candidates/verification/store') }}" enctype="multipart/form-data">
                                @csrf
                                <h5 class="card-title mb-1" style="">Candidate Details :</h5> 
                                <div class="row" style="border: 1px solid #ddd; padding:10px;">
                                        <div class="col-md-4">
                                            <p class="mb-1" style="">Name: {{$candidate->name}}</p> 
                                        </div>
                                        <div class="col-md-4">
                                            <p class=" mb-1" style="">Email: {{$candidate->email}}</p> 
                                        </div>
                                        <div class="col-md-4">
                                            <p class=" mb-1" style="">Contact Number: {{$candidate->phone}}</p> 
                                        </div>
                                </div>
                                <div class="row">
                                        @if ($message = Session::get('error'))
                                            <div class="col-md-12">   
                                                <div class="alert alert-danger">
                                                <strong>{{ $message }}</strong> 
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-10 pt-3">
                                        <h4 class="card-title mb-1" style="border-bottom:1px solid #ddd;">Start Verification </h4> 
                                            <p class="mt-1"> Fill the required details </p>			
                                        </div>

                                        <div class="col-md-10">	
                                            <input type="hidden" name="candidate_id" value="{{base64_encode($candidate->id)}}">
                                            <div class="form-group">
                                                <label>Select Services <span class="text-danger">*</span></label>
                                                
                                                <div class="col-sm-12">
                                                <div class="form-group">
                                                    @foreach($services as $service)
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input services_list" type="checkbox" name="services[]" value="{{ $service->id}}" data-string="{{ $service->name  }}" id="inlineCheckbox-{{ $service->id}}" data-verify={{$service->verification_type}}>
                                                            <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <p style="margin-bottom: 10px;" class="text-danger error_container" id="error-services"></p>
                                                <div class="service_result" style="border: 1px solid #ddd; padding:10px;">
                                                    <div class="row">
                                                        <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Required all of the selected service</div>
                                                    </div>
                                                </div>
                                                <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error_container" id="error-check"></p>
                                                </div>
                                            </div>
                                        
                                            <div class="form-group text-right mt-2">            
                                                <button type="submit" class="btn btn-primary submit">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<script>
    // alert("hi");
    $(document).ready(function(){

        $(".services_list").change(function() {
            if(this.checked)
            {
                var id =  $(this).attr("value");
                var text =  $(this).attr("data-string");
                var verify =$(this).attr("data-verify");
                
                if(text=='Bank Verification')
                    $(".service_result").append("<div class='row mt-2' id='row-"+id+"'><div class='col-sm-6'><label>"+'Account Number'+"</label><input class='form-control' type='text' name='service_unit-"+id+"' ><p style='margin-top:2px;margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-6'><label>"+'Ifsc Code'+"</label><input class='form-control' type='text' name='notes-"+id+"' placeholder='IFSC Code' ><p style='margin-top:2px;margin-bottom: 2px;' class='text-danger error_container' id='error-notes-"+id+"'></p></div></div>");
                else if(text=='Passport')
                    $(".service_result").append("<div class='row mt-2' id='row-"+id+"'><div class='col-sm-6'><label>"+'File Number'+"</label><input class='form-control' type='text' name='service_unit-"+id+"' ><p style='margin-top:2px;margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-6'><label>"+'Dob'+"</label><input class='form-control commonDatepicker' type='text' name='notes-"+id+"' placeholder='' ><p style='margin-top:2px;margin-bottom: 2px;' class='text-danger error_container' id='error-notes-"+id+"'></p></div></div>");
                else if(text=='Driving')
                    $(".service_result").append("<div class='row mt-2' id='row-"+id+"'><div class='col-sm-6'><label>"+'DL'+' '+'Number'+"</label></div><div class='col-sm-6'><input class='form-control' type='text' name='service_unit-"+id+"' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></div></div>");
                // else if(text=='Telecom')
                //     $(".service_result").append("<div class='row mt-2' id='row-"+id+"'><div class='col-sm-6'><label>"+'Mobile'+' '+'Number'+"</label></div><div class='col-sm-6'><input class='form-control' type='text' name='service_unit-"+id+"' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></div></div>");
                else
                    $(".service_result").append("<div class='row mt-2' id='row-"+id+"'><div class='col-sm-6'><label>"+text+' '+'Number'+"</label></div><div class='col-sm-6'><input class='form-control' type='text' name='service_unit-"+id+"' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></div></div>");
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
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
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
                        $('.submit').html('Submit');
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