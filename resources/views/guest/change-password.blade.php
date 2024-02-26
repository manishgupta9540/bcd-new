@extends('layouts.guest')
@section('title', 'Change Password | ')
<style>
    span.show-hide-password {
        position: absolute;
        /* top: 34px; */
        margin-top: -25px;
        right: 30px;
        font-size: 14px;
        color: #748a9c;
        cursor: pointer;
    }

    .change-pswd .card-body {
        height: auto;
    }
    .verification-step-1
    {
        height: 86vh;
    }
    .changepass-section
    {
        display: flex;
        justify-content: center;
        background-color: #fff;
        margin-top: 13%;
        border-radius: 5px;
    }
    .pass-submit
    {
        color: #fff !important;
        background-color: #003473 !important;
        border-color: #003473 !important;
    }
    .pass-submit:hover
    {
        color: #fff !important;
        background-color: #e10813 !important;
        border-color: #e10813 !important;
    }
    .submit-section
    {
        float: right;
        margin-top: 20px;
    }


    @media only screen and (max-width: 768px) {
        .verification-step-1 
        {
            height: 165vh;
        }
        }
</style>
@section('content')

    <div class="col-md-4 offset-md-3 main-content-wrap sidenav-open d-flex flex-column change-pswd">
        <!-- ============ Body content start ============= -->
        <div class="main-content changepass-section">
            {{-- <div class="row">
                <div class="col-sm-11 col-10">
                    <ul class="breadcrumb">
                        <li>
                            <a href="{{ url('/verify/home') }}">Dashboard</a>
                        </li>
                        <li>Change Password</li>
                    </ul>
                </div>
                <!-- ============Back Button ============= -->
                <div class="col-sm-1 back-arrow col-2">
                    <div class="text-right">
                        <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                    </div>
                </div>
            </div> --}}

            
                
                    <div class="card-body"> 
                        <div class="row">
                            <div class="col-md-12">
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @endif
                                @if ($message = Session::get('error'))
                                    <div class="alert alert-danger">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @endif
                                <!-- Page Header -->
                                <div class="page-header" style="margin: 10px 0 0 0;">
                                    <div class="row align-items-center">
                                        <div class="col" style="padding:10px 5px 30px 5px;">
                                            <h3 class="page-title" style="">Change your password</h3>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Page Header -->
                                <form action="{{ url('/verify/change-password') }}" method="post" id="changePasswordForm">
                                    @csrf
                                    <div class="form-group">
                                        <label>Old password</label>
                                        <input type="password" class="form-control" name="old_password">
                                        <span class="show-hide-password js-show-hide has-show-hide"><i
                                                class="fa fa-eye-slash"></i></span>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container"
                                            id="error-old_password"></p>
                                        {{-- @if ($errors->has('old_password')) <p class="text-danger">{{ $errors->first('old_password') }}</p> @endif --}}
                                    </div>
                                    <div class="form-group">
                                        <label>New password</label>
                                        <input type="password" class="form-control" name="password">
                                        <span class="show-hide-password js-show-hide has-show-hide"><i
                                                class="fa fa-eye-slash"></i></span>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container"
                                            id="error-password"></p>

                                    </div>
                                    <div class="form-group">
                                        <label>Confirm new password</label>
                                        <input type="password" class="form-control" name="password_confirmation">
                                        <span class="show-hide-password js-show-hide has-show-hide"><i
                                                class="fa fa-eye-slash"></i></span>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container"
                                            id="error-password_confirmation"></p>

                                    </div>
                                    <div class="submit-section">
                                        <button type="submit" class="btn btn-info submit-btn pass-submit">Update Password</button>
                                    </div>
                                </form>
                                <!-- ./row -->
                            </div>
                        </div>
                    </div>
                
            
            <!-- /Page Content -->

            <script>
                $(function() {

                    // $('.submit-btn').on('click', function() {
                    //     var $this = $(this);
                    //     // $('.submit-btn').attr('disabled',true);
                    //     var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
                    //     if ($(this).html() !== loadingText) {
                    //     $this.data('original-text', $(this).html());
                    //     $this.html(loadingText);
                    //     }
                    //     setTimeout(function() {
                    //     $this.html($this.data('original-text'));
                    //     // $('.submit-btn').attr('disabled',false);
                    //     }, 5000);
                    // });

                    $('#createUserBtn').click(function(e) {
                        e.preventDefault();
                        $("#changePasswordForm").submit();
                    });

                    $(document).on('submit', 'form#changePasswordForm', function(event) {
                        event.preventDefault();
                        //clearing the error msg
                        $('p.error_container').html("");

                        var form = $(this);
                        var data = new FormData($(this)[0]);
                        var url = form.attr("action");
                        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
                        $('.submit-btn').attr('disabled', true);
                        if ($('.submit-btn').html() !== loadingText) {
                            $('.submit-btn').html(loadingText);
                        }
                        $.ajax({
                            type: form.attr('method'),
                            url: url,
                            data: data,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(response) {

                                // console.log(response);

                                window.setTimeout(function() {
                                    $('.submit-btn').attr('disabled', false);
                                    $('.submit-btn').html('Update Password');
                                }, 3000);
                                if (response.success == true) {

                                    //notify
                                    toastr.success("Password changed successfully");
                                    // redirect to google after 5 seconds
                                    window.setTimeout(function() {
                                        window.location = "{{ url('/') }}" + "/microsite/login";
                                    }, 3000);

                                }
                                //show the form validates error
                                if (response.success == false) {
                                    for (control in response.errors) {
                                        $('#error-' + control).html(response.errors[control]);
                                    }
                                }
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                        return false;
                    });

                    $(document).on('click', '.js-show-hide', function(e) {

                        e.preventDefault();

                        var _this = $(this);

                        if (_this.hasClass('has-show-hide')) {
                            _this.parent().find('input').attr('type', 'text');
                            _this.html('<i class="fa fa-eye"></i>');
                            _this.removeClass('has-show-hide');
                        } else {
                            _this.addClass('has-show-hide');
                            _this.parent().find('input').attr('type', 'password');
                            _this.html('<i class="fa fa-eye-slash"></i>');
                        }


                    });

                });
            </script>

        @endsection
