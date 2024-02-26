<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BCD</title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link href="{{ asset('guest/css/register.css')}}" rel="stylesheet" />
    <link href="{{ asset('admin/fonts/font-awesome-all.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<body>
    <div class="container register">
        <div class="row">
            <div class="col-md-3 register-left">
                <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt=""/>
                <h3>Welcome</h3>
                <p>Start Your Verification with BCD!</p>
                <a class="btn" href="{{Config::get('app.guest_url')}}/login">Login</a> <br/>
            </div>
            <div class="col-md-9 register-right">
                <h3 class="register-heading">Apply Now</h3>
                <div class="row register-form">
                    <div class="col-12">
                    <form method="post" action="{{route('/guest/store')}}" id="createGuestForm">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="full_name" class="form-control full_name" placeholder="Full Name *" value="{{old('full_name')}}" autocomplete="off"/>
                            {{-- @if ($errors->has('full_name'))
                            <div class="error text-danger">
                                {{ $errors->first('full_name') }}
                            </div>
                          @endif --}}
                          <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-full_name"></p>
                        </div>
                        <div class="form-group">
                            <input type="text" name="company_name" class="form-control company_name" placeholder="Company Name" value="{{old('company_name')}}" autocomplete="off"/>
                            {{-- @if ($errors->has('company_name'))
                            <div class="error text-danger">
                                {{ $errors->first('company_name') }}
                            </div>
                          @endif --}}
                          <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-company_name"></p>
                        </div>
                        <div class="form-group">
                            <input type="text" name="job_title" class="form-control job_title" placeholder="Your Title" value="{{old('title')}}" autocomplete="off"/>
                            {{-- @if ($errors->has('title'))
                            <div class="error text-danger">
                                {{ $errors->first('title') }}
                            </div>
                          @endif --}}
                          <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-job_title"></p>
                        </div>
                        <div class="form-group">
                            <input type="text" minlength="10" maxlength="10" name="mobile_number" class="form-control mobile_number" placeholder="Mobile Number *" value="{{old('mobile_number')}}" autocomplete="off"/>
                            {{-- @if ($errors->has('mobile_number'))
                            <div class="error text-danger">
                                {{ $errors->first('mobile_number') }}
                            </div>
                          @endif --}}
                          <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-mobile_number"></p>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control email" placeholder="Official Email *" value="{{old('email')}}" autocomplete="off"/>
                            {{-- @if ($errors->has('email'))
                            <div class="error text-danger">
                                {{ $errors->first('email') }}
                            </div>
                          @endif --}}
                          <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control password" placeholder="Password *" value="{{old('password')}}" autocomplete="off"/>
                            <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                            {{-- @if ($errors->has('password'))
                            <div class="error text-danger">
                                {{ $errors->first('password') }}
                            </div>
                          @endif --}}
                          <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-password"></p>
                        </div>
                        <div class="form-group">
                            <input type="password" name="confirm_password" class="form-control confirm_password"  placeholder="Confirm Password *" value="{{old('confirm_password')}}" autocomplete="off"/>
                            <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                            {{-- @if ($errors->has('confirm_password'))
                            <div class="error text-danger">
                                {{ $errors->first('confirm_password') }}
                            </div>
                          @endif --}}
                          <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-confirm_password"></p>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btnRegister submit-verify">
                                Start Verification
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    
    $(document).on('click','.js-show-hide',function (e) {

        e.preventDefault();

        var _this = $(this);

        if (_this.hasClass('has-show-hide'))
        {
        _this.parent().find('input').attr('type','text');
        _this.html('<i class="fa fa-eye"></i>');
        _this.removeClass('has-show-hide');
        }
        else
        {
        _this.addClass('has-show-hide');
        _this.parent().find('input').attr('type','password');
        _this.html('<i class="fa fa-eye-slash"></i>');
        }


    });

    $(document).on('submit', 'form#createGuestForm', function (event) {
      event.preventDefault();
      //clearing the error msg
      $('p.error_container').html("");
      $('.form-control').removeClass('border-danger');
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
        $('.submit-verify').attr('disabled',true);
      $.ajax({
         type: form.attr('method'),
         url: url,
         data: data,
         cache: false,
         contentType: false,
         processData: false,      
         success: function (response) {
             window.setTimeout(function(){
                $('.submit-verify').attr('disabled',false);
             },2000);
               console.log(response);
               if(response.success==true) {          
                  // window.location = "{{ url('/')}}"+"/sla/?created=true";
                  toastr.success('Check Your Mail to Confirm your account !');
                  toastr.success('Form Submitted Successfully !');
                  window.setTimeout(function(){
                    location.reload();
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
    
</script>
</body>
</html>
