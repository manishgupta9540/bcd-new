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

        .default-card {

            box-shadow: 0px 0px 10px #ccc;
            padding: 10px;
            border-radius: 10px;
            margin: 15px;

        }

        .disabled-link {
            pointer-events: none;
        }

        /* h4.title1 {
        margin-top: -24px;
    } */
        .sweet-alert button.cancel {
            background: #DD6B55 !important;
        }

        .btn-submit:hover
        {
            background-color:#e10813 !important;
        }




        @media (max-width: 576px) {
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
    {{-- <div class="main-content-wrap sidenav-open d-flex flex-column">
        <!-- ============ Body content start ============= -->
        <div class="main-content"> --}}
            {{-- <div class="row"> --}}
                <div class="col-md-10 col-sm-12 marg-des">
                    <div class="verification-detail-1 p-4">
                        <div class="logosection">
                            <p><span>Instant Verification</span><br> Enter details you want to verify</p>
                            <p>Government approved APIs * : <img src="{{ asset('admin/microsite/images/udiai1.png') }}"></p>
                        </div>
                        <?php
                        $guest_id = Crypt::encryptString($guest_master_id);
                        
                        ?>
                        <div class="verification-1-button">

                            <!-- <a class="verfication-top-tab" href="verification-step-1.php">Choose items</a>
                        <a class="verfication-top-tab activeclass" href="verification-step-2.php">Enter details</a>
                        <a class="verfication-top-tab" href="verification-step-3.php">Make payment</a>
                        <a class="verfication-top-tab" href="verification-step-4.php">View results</a> -->
                            <a href="{{ url('verify/instant_verification') }}"><button class="verfication-top-tab">Choose
                                    items</button></a>
                            <a href="{{ url('verify/instant_verification/services/' . $guest_id) }}"><button
                                    class="verfication-top-tab activeclass">Enter details</button></a>
                            <a href="javascript:void(0)"><button class="verfication-top-tab">Make payment</button></a>
                            <a href="javascript:void(0)"><button class="verfication-top-tab">View results</button></a>
                        </div>
                        <form class="mt-2" method="post" id="addCartDetailsForm"
                            action="{{ url('/verify/instant_verification/services/store') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="guest_master_id" id="guest_master_id"
                                value="{{ Crypt::encryptString($guest_master_id) }}">
                            <div class="row mt-4">
                                @foreach ($guest_cart as $key => $gc)
                                    <?php $service_name = Helper::get_service_name($gc->service_id);
                                    
                                    $sample_url = ' ';
                                    $download = '';
                                    if (stripos($service_name, 'Aadhar') !== false) {
                                        $sample_url = url('/guest/') . '/sample/aadhar.pdf';
                                        $download = 'download';
                                    } elseif (stripos($service_name, 'PAN') !== false) {
                                        $sample_url = url('/guest/') . '/sample/pan.pdf';
                                        $download = 'download';
                                    } elseif (stripos($service_name, 'Voter ID') !== false) {
                                        $sample_url = url('/guest/') . '/sample/voter.pdf';
                                        $download = 'download';
                                    } elseif (stripos($service_name, 'RC') !== false) {
                                        $sample_url = url('/guest/') . '/sample/rc.pdf';
                                        $download = 'download';
                                    } elseif (stripos($service_name, 'Passport') !== false) {
                                        $sample_url = url('/guest/') . '/sample/passport.pdf';
                                        $download = 'download';
                                    } elseif (stripos($service_name, 'Driving') !== false) {
                                        $sample_url = url('/guest/') . '/sample/dl.pdf';
                                        $download = 'download';
                                    } elseif (stripos($service_name, 'Bank Verification') !== false) {
                                        $sample_url = url('/guest/') . '/sample/bank.pdf';
                                        $download = 'download';
                                    } elseif (stripos($service_name, 'E-Court') !== false) {
                                        $sample_url = url('/guest/') . '/sample/e_court.pdf';
                                        $download = 'download';
                                    } elseif (stripos($service_name, 'UPI Verification') !== false) {
                                        $sample_url = url('/guest/') . '/sample/upi.pdf';
                                        $download = 'download';
                                    } elseif (stripos($service_name, 'cin') !== false) {
                                        $sample_url = url('/guest/') . '/sample/cin.pdf';
                                        $download = 'download';
                                    }
                                    $i = 0;
                                    ?>
                                    <div class="col-md-4 col-sm-12 px-2 mt-3">
                                        <div class="second-step-details px-3">
                                            <div class="details-heading">
                                                <div class="row">
                                                    <div class="col-md-8 p-0">
                                                <h5>{{ stripos($service_name, 'Driving') !== false ? 'Driving License' : $service_name }}
                                                    - {{ $gc->number_of_verification }}</h5>
                                                    
                                                <?php
                                                    $guest_cart_services = Helper::get_instant_cart_service($guest_master_id, $gc->id, $gc->service_id);
                                                ?>
                                              
                                                {{-- <a href="{{ $sample_url }}" style="font-size: 16px;" title="Sample Report"
                                                    {{ $download }}><button type="button"
                                                        class="btn btn-md btn-outline-info sampleReport">Sample Report</button></a> --}}
                                                    </div>
                                                    <div class="col-md-4 p-0">
                                                <a href="javascript:void(0)" style="font-size: 12px;" class="btn btn-md btn-outline-info " data-url="{{$sample_url}}" data-id="{{$gc->service_id}}" id="samplereport" title="Sample Report" >Sample Report</a>
                                                    </div>
                                            </div>
                                            </div>
                                            @foreach ($guest_cart_services as $g_key => $gcs)
                                                <?php
                                                $service_name = Helper::get_service_name($gcs->service_id);
                                                ?>

                                                @if ($gcs->service_data != null)
                                                    <?php
                                                    $service_data_array = json_decode($gcs->service_data, true);
                                                    ?>
                                                    <div class="input-area">
                                                        @foreach ($service_data_array as $service_key => $service_value)
                                                            <?php $i = 0; ?>
                                                            @if (stripos($service_key, 'check') !== false)
                                                                @foreach ($service_value as $key => $value)
                                                                    @php
                                                                        $j = $i + 1;
                                                                    @endphp
                                                                    <label>{{ $key }} - {{ $j }} :
                                                                        @if (!(stripos($key, 'Middle Name') !== false || stripos($key, 'Email') !== false || stripos($key, 'Last Name') !== false ))
                                                                            <span class="text-danger">*</span>
                                                                        @endif
                                                                    </label>
                                                                    <input type="hidden" name="check_label-{{ $gcs->id . '-' . $gcs->service_id . '-' . $i }}" value="{{ $key }}">
                                                                    @if (stripos($key, 'Date of Birth') !== false)
                                                                        <input type="date" class="form-control dob" name="check_{{ $gcs->id . '-' . $gcs->service_id . '-' . $i }}" id="dob" value="{{ date('Y-m-d', strtotime($value)) }}">
                                                                        {{-- <a href="javascript:;" class="text-danger delete_btn cross-des" data-id="{{ base64_encode($gcs->id) }}" style="font-size: 24px;">
                                                                            <i class="far fa-times-circle"></i>
                                                                        </a> --}}
                                                                        
                                                                    @else
                                                                        <input type="text" class="form-control" name="check_{{ $gcs->id . '-' . $gcs->service_id . '-' . $i }}" value="{{ $value }}">
                                                                           {{-- @if($key==0) --}}
                                                                            <a href="javascript:;" class="text-danger delete_btn cross-des" data-id="{{ base64_encode($gcs->id) }}" style="font-size: 24px;">
                                                                                <i class="far fa-times-circle"></i>
                                                                            </a>
                                                                            {{-- @endif --}}
                                                                
                                                                    @endif
                                                                    <p style="margin-bottom: 2px;font-size: 13px;padding: 21px;" class="text-danger error_container" id="error-check_{{ $gcs->id . '-' . $gcs->service_id . '-' . $i }}"> </p>
                                                                    <?php $i++; ?>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="input-area">
                                                        <?php
                                                        $guest_service_inputs = Helper::get_guest_service_form_inputs($gcs->service_id);
                                                        // dd($guest_service_inputs);
                                                        $j = 0;
                                                        ?>
                                                        @foreach ($guest_service_inputs as $key=> $input)
                                                            @php
                                                                $k = $i + 1;
                                                            @endphp
                                                            <label>{{ $input->label_name }} - {{ $k }}: <span class="text-danger">*</span></label>
                                                            <input type="hidden" name="check_label-{{ $gcs->id . '-' . $gcs->service_id . '-' . $j }}" value="{{ $input->label_name }}">
                                                            @if (stripos($input->label_name, 'Date of Birth') !== false)
                                                                <input type="date" name="check_{{ $gcs->id . '-' . $gcs->service_id . '-' . $j }}" class="form-control dob" id="dob">
                                                            @else
                                                                <input type="text" class="form-control" name="check_{{ $gcs->id . '-' . $gcs->service_id . '-' . $j }}">
                                                                @if($key == 0)
                                                                    <a href="javascript:;" class="text-danger delete_btn cross-des" data-id="{{ base64_encode($gcs->id) }}" style="font-size: 24px;">
                                                                        <i class="far fa-times-circle"></i>
                                                                    </a>
                                                                @endif
                                                            @endif

                                                            <p style="margin-bottom: 2px;font-size: 13px;padding: 21px;" class="text-danger error_container" id="error-check_{{ $gcs->id . '-' . $gcs->service_id . '-' . $j }}">
                                                            </p>
                                                            <?php $j++; ?>    
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <?php $i++; ?>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="totalprice">
                                <button type="submit" class="btn btn-info submit btn-submit" style="width: 10%;padding: 6px;margin: 18px 0px;font-size:16px;">Next</button>
                        </form>
                    </div>
                </div>
            {{-- </div> --}}
        {{-- </div> --}}
        {{-- <div class="row"> --}}

        {{-- </div> --}}
    {{-- </div> --}}
    <!-- Footer Start -->
    <div class="flex-grow-1"></div>
    </div>
    </div><!-- ============ Search UI Start ============= -->

    
 {{-- Modal for Report preview --}}
 <div class="modal"  id="sample_report">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Report Preview</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
             <div class="modal-body">
             {{-- <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p>  --}}
                <iframe src="" style="width:100%; height:600px;" frameborder="0" id="preview_pdf"></iframe>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="button" class="btn btn-danger back" data-dismiss="modal">Close</button>
             </div>
       </div>
    </div>
</div>
 {{-- Modal for Report preview --}}
    <!-- ============ Search UI End ============= -->
    <script>
        $(document).ready(function() {
            $('.phone').parent().css({
                'width': '100%'
            });

            $(document).on('click', '#samplereport', function() {
                var dataUrl = $(this).attr('data-url');
                $('#preview_pdf').attr('src', dataUrl+'#toolbar=0');
            
                $('#sample_report').modal({
                backdrop: 'static',
                keyboard: false
            });

        });

            $(document).on('click', '.delete_btn', function() {
                var _this = $(this);
                // var result=confirm("Are You Sure You Want to Delete?");
                var id = $(this).data('id');
                // if(result){
                //     _this.addClass('disabled-link');
                //     $.ajax({
                //         type: "POST",
                //         dataType: "json",
                //         url: '{{ url('/verify/instant_verification/services/delete_by_check') }}',
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
                //                         window.location="{{ url('/verify/') }}"+"/instant_verification";
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
                    function(e) {
                        if (e == true) {
                            _this.addClass('disabled-link');
                            $.ajax({
                                type: "POST",
                                dataType: "json",
                                url: '{{ url('/verify/instant_verification/services/delete_by_check') }}',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    'id': id
                                },
                                success: function(data) {
                                    console.log(data);
                                    window.setTimeout(function() {
                                        _this.removeClass('disabled-link');
                                    }, 2000);

                                    if (data.success == true) {
                                        toastr.success('Record Deleted Successfully');
                                        if (data.db == false) {
                                            window.setTimeout(function() {
                                                window.location.reload();
                                            }, 2000);
                                        } else if (data.db == true) {
                                            window.setTimeout(function() {
                                                window.location =
                                                    "{{ url('/verify/') }}" +
                                                    "/instant_verification";
                                            }, 2000);
                                        }
                                    }
                                }
                            });
                            swal.close();
                        } else {
                            swal.close();
                        }
                    }
                );
            });

            $(document).on('submit', 'form#addCartDetailsForm', function(event) {
                event.preventDefault();
                //clearing the error msg
                $('p.error_container').html("");
                // $('.form-control').removeClass('border-danger');
                var form = $(this);
                var data = new FormData($(this)[0]);
                var url = form.attr("action");
                var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
                $('.submit').attr('disabled', true);
                if ($('.submit').html != loadingText) {
                    $('.submit').html(loadingText);
                }
                $.ajax({
                    type: form.attr('method'),
                    url: url,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        window.setTimeout(function() {
                            $('.submit').attr('disabled', false);
                            $('.submit').html('Next');
                        }, 2000);

                        console.log(response);
                        if (response.success == true) {
                            // window.location = "{{ url('/') }}"+"/sla/?created=true";
                            toastr.success('Form Submitted Successfully');
                            // var order_id=response.order_id;
                            var guest_master_id = response.guest_master_id;
                            window.setTimeout(function() {
                                window.location = "{{ url('/verify/') }}" +
                                    "/instant_verification/checkout/" + guest_master_id;
                            }, 2000);
                        }
                        //show the form validates error
                        if (response.success == false) {
                            for (control in response.errors) {
                                // $('.'+control).addClass('border-danger'); 
                                $('#error-' + control).html(response.errors[control]);
                                $('input[name=' + control + ']').focus();
                                $('select[name=' + control + ']').focus();
                            }
                        }
                    },
                    error: function(response) {
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
            geoIpLookup: function(callback) {
                $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"
        });

        /* ADD A MASK IN PHONE1 INPUT (when document ready and when changing flag) FOR A BETTER USER EXPERIENCE */

        var mask1 = $(".phone").attr('placeholder').replace(/[0-9]/g, 0);

        $(document).ready(function() {
            $('.phone').mask(mask1)
        });

        //
        $(".phone").on("countrychange", function(e, countryData) {
            $(".phone").val('');
            var mask1 = $(".phone").attr('placeholder').replace(/[0-9]/g, 0);
            $('.phone').mask(mask1);
            $('#code').val($(".phone").intlTelInput("getSelectedCountryData").dialCode);
            $('#iso').val($(".phone").intlTelInput("getSelectedCountryData").iso2);
        });
    </script>
@endsection
