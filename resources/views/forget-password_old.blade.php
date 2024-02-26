
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forget Password | BCD </title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
   <link href="{{ asset('admin/gull/dist-assets/css/themes/lite-purple.min.css')}}" rel="stylesheet" />
   <link href="{{ asset('admin/fonts/font-awesome-all.css')}}" rel="stylesheet" />
   <link href="{{ asset('admin/css/style.css')}}" rel="stylesheet" />
   <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">

   <style>
    span.show-hide-password {
        position: absolute;
        top: 30px;
        right: 10px;
        font-size: 14px;
        color: #748a9c;
        cursor: pointer;
    }
    </style>
   
</head>
<body>
<div class="auth-layout-wrap" style="background-image: url({{asset('admin/images/photo-wide-4.jpg')}})">
    <div class="auth-content">
        <div class="card o-hidden">
            <div class="row">
             
                <div class="col-md-12">
                    <div class="p-4">
                        <div class="auth-logo text-center mb-4">
                        <a href="{{ url('/') }}" > <img src="{{ asset('admin/images/logo.png')}}" alt=""> </a>
                        </div>
                        <h1 class="mb-3 text-18 text-center">Reset your Password</h1>
                    
                        <form method="Post" action="{{url('/forget/password/update')}}" id="changePasswordForm" enctype="multipart/form-data">
                        @csrf

                        @if ($message = Session::get('success'))
                        <div class="col-md-12">   
                           <div class="alert alert-success">
                           <strong>{{ $message }}</strong> 
                           </div>
                        </div>
                        @endif
                        {{-- <div class="form-group">
                            <label>Old password</label>
                            <input type="password" class="form-control" name="old_password">
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-old_password"></p>
                             @if ($errors->has('old_password')) <p class="text-danger">{{ $errors->first('old_password') }}</p> @endif 
                        </div> --}}
                        <input type="hidden" name="id" value="{{$id}}">
                        <div class="form-group">
                            <label>New password</label>
                            <input type="password" class="form-control" name="password">
                            <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-password"></p>
                            
                        </div>
                        <div class="form-group">
                            <label>Confirm new password</label>
                            <input type="password" class="form-control" name="password_confirmation">
                            <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-password_confirmation"></p>
                            
                        </div>
                        <div class="submit-section">
                            <input type="submit" class="btn btn-rounded btn-info btn-block mt-3" value="Reset Password">
                        </div>
                        </form>
                      
                    </div>
                </div>
 
            </div>
        </div>
    </div>
</div>



 {{-- @stack('scripts')  --}}

<script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script type="text/javascript">
    
   
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
            }, 5000);
        });
    
        //    $('#createUserBtn').click(function(e) {
        //         e.preventDefault();
        //         $("#changePasswordForm").submit();
        //     });
    
       $(document).on('submit', 'form#changePasswordForm', function (event) {
        event.preventDefault();
        //clearing the error msg
        $('p.error_container').html("");
        
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        
            $.ajax({
                type: form.attr('method'),
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,      
                success: function (response) {
        
                    console.log(response);
                    if(response.success==true  ) {          
                    
                        //notify
                    toastr.success("Password changed successfully");
                        // redirect to google after 5 seconds
                        window.setTimeout(function() {
                            window.location="{{url('/')}}"+'/login';
                        }, 2000);
                    
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


    });
    
    </script>  
</body>
</html>