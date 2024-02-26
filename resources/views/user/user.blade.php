<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{ config('app.name', 'BCD System') }}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="{{ asset('admin/fonts/font-awesome-all.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>
  {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script type="text/javascript">
    var loaderPath = "https://f52.in/admin/assets/images/preload.gif";
   </script>
</head>
<style type="text/css">

  body {
    color: #000;
    overflow-x: hidden;
    height: 100%;
    background-color: #ffffff;
    background-repeat: no-repeat
}
.col-lg-6.imageblock {
    background-color: #002e62;
    height: auto;    
     /*padding: 50px 0px;
   position: absolute;
    top: 0;
    bottom: 0;*/

}
.col-lg-6.login {
    /*position: absolute;
    left: 50%;*/
    text-align: center;
}
.container-fluid.px-1.px-md-5.px-lg-1.px-xl-5.py-5.mx-auto {
    padding: 0px!important;
}
.card0 {
    /*box-shadow: 0px 4px 8px 0px #757575;*/
    border-radius: 0px
}
.card2.card.border-0.px-4.py-5 {
    padding-bottom: 0px!important;
}
.card1.pb-5{
    padding-bottom:0px!important;
}
.card2 {
    margin: 40px 80px;
}
/* .text-danger {
    color: #03136b!important;
}
.text-danger:hover {
    color: #03136b!important;
} */
/* .logo {
    width: 180px;
    height: auto;
    margin-top: 40px;
  
} */

.logo {
    width: 180px;
    height: auto;
    margin-top: 20%;
   /* margin-left: 40vh;*/
}

img.image {
    width: 100%;
    height: auto;
}
/*.border-line {
    border-right: 1px solid #EEEEEE
}*/

.facebook {
    background-color: #3b5998;
    color: #fff;
    font-size: 18px;
    padding-top: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    cursor: pointer
}

.twitter {
    background-color: #1DA1F2;
    color: #fff;
    font-size: 18px;
    padding-top: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    cursor: pointer
}

.linkedin {
    background-color: #2867B2;
    color: #fff;
    font-size: 18px;
    padding-top: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    cursor: pointer
}

.line {
    height: 1px;
    width: 45%;
    background-color: #E0E0E0;
    margin-top: 10px
}

.or {
    width: 10%;
    font-weight: bold
}

.text-sm {
    font-size: 14px !important
}

::placeholder {
    color: #BDBDBD;
    opacity: 1;
    font-weight: 300
}

:-ms-input-placeholder {
    color: #BDBDBD;
    font-weight: 300
}

::-ms-input-placeholder {
    color: #BDBDBD;
    font-weight: 300
}

input,
textarea {
    padding: 10px 50px 10px 12px;
    border: 1px solid lightgrey;
    border-radius: 2px;
    margin-bottom: 5px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    color: #2C3E50;
    font-size: 14px;
    letter-spacing: 1px
}

input:focus,
textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid #304FFE;
    outline-width: 0
}

button:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    outline-width: 0
}

a {
    color: inherit;
    cursor: pointer
}

.btn-blue {
    background-color:#002e62 /*#1A237E*/;
    width: 150px;
    color: #fff;
    border-radius: 2px
}

.btn-blue:hover {
    background-color: #03136b;
    color: white;
    cursor: pointer
}

.bg-blue {
    color: #fff;
    background-color: #1A237E
}

@media screen and (max-width: 991px) {
    .col-lg-6.login {
    position: relative;
     left: 0%; 
}
    .col-lg-6.imageblock {
    background-color: /*rgb(91, 99, 254)*/ #7a9ecf;
    height: auto;
    padding:0px;
    /*position: relative;
    left: 0%;
    bottom: 0;
    top: 0px;*/
}
    .logo {
        margin-left: 0px
    }

    .image {
        width: 300px;
        height: 220px
    }

    .border-line {
        border-right: none
    }

    .card2 {
        border-top: 1px solid #EEEEEE !important;
        margin: 0px 15px
    }
}
span.show-hide-password {
    position: relative;
    top: -37px;
    right: -93%;
    font-size: 16px;
    color: #748a9c;
    cursor: pointer;
    z-index: 1;
}


</style>
<body>
<div class="container-fluid">
    
        <div class="row">
            <div class="col-lg-6 imageblock">
                <div class="row px-3 justify-content-center mt-4 mb-5 border-line"><img src="{{ asset('admin/images/Data_security_03.png')}}" class="image"> </div>
            </div>
            <div class="col-lg-6 login">
                <div class="card1 pb-5">
                    <div class="row"> <div class="col-md-12 text-center"><a href="{{ url('/') }}"><img src="{{ asset('admin/images/BCD-Logo1.png')}}" class="logo text-center"> </a></div></div>
                    <form method="Post" action="{{ url('/user/password/store') }}" id="addpasswordForm">
                        @csrf  
                            <input type="hidden" name="business_id" value="{{$business_id}}">
                            <input type="hidden" name="user_id" value="{{$user_id}}">
                            <input type="hidden" name="token_no" value="{{$token_no}}">
                            <div class="card2 card border-0 px-4 py-5">

                                <div class="row"> 
                                    <div class="col-md-12 text-left">
                                        <h4>Create New Password</h4>
                                    </div>
                                </div>
                            
                                <div class="row px-3"> <label class="mb-1">
                                        <h6 class="mb-0 text-sm">Password</h6>
                                    </label> 
                                    <input class="error-control password" type="password" name="password" placeholder="Enter password" value="{{ old('password') }}"> 
                                    <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                                    <p style="margin-left: -15px" class="text-danger error_container" id="error-password"></p> 
                                </div>
                                <div class="row px-3"> <label class="mb-1">
                                        <h6 class="mb-0 text-sm">Confirm Password</h6>
                                    </label> 
                                    <input type="password" class="error-control confirm-password" name="confirm-password" placeholder="Enter password" value="{{ old('confirm-password') }}">
                                    <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                                    <p style="margin-left: -15px" class="text-danger error_container" id="error-confirm-password"></p>
                                </div>
                                <p style="" class="text-left text-danger error_container" id="wrong-credential"> </p>
                                <div class="row mb-3 px-3"> 
                                    <button type="submit" class="btn btn-blue text-center login_submit">Submit</button> 
                                </div>
                                {{-- <div class="row mb-4 px-3"> <small class="font-weight-bold">Don't have an account? <a class="text-danger ">Register</a></small> </div> --}}
                            </div>
                    </form>
                    <!-- <div class="row px-3 justify-content-center mt-4 mb-5 border-line"> <img src="https://i.imgur.com/uNGdWHi.png" class="image"> </div> -->
                </div>
            </div>
            
        </div>
        
    
</div>


<script>
    // $('.forget').click(function(){
    //     // Open Model Pop up
    //     $('#forgetpass').toggle();
    
    //     $(document).on('submit', 'form#forgetPasswordForm', function (event) {
    //         event.preventDefault();
    //         //clearing the error msg
    //         $('p.error_container').html("");
    //         $('.forgot_submit').attr('disabled',true);
    //         $('.form-control').attr('readonly',true);
    //         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
    //         var form = $(this);
    //         var data = new FormData($(this)[0]);
    //         var url = form.attr("action");
    //         if ($('.forgot_submit').html() !== loadingText) {
    //           $('.forgot_submit').html(loadingText);
    //         }
    //         $.ajax({
    //             type: 'POST',
    //             url: url,
    //             data: data,
    //             cache: false,
    //             contentType: false,
    //             processData: false,      
    //             success: function (response) {
    
    //                 console.log(response);
    //                 window.setTimeout(function(){
    //                     $('.forgot_submit').attr('disabled',false);
    //                     $('.forgot_submit').html('Submit');
    //                     $('.form-control').attr('readonly',false);
    //                 },2000);
    //                 if(response.success==true  ) {          
                    
    //                 //     //notify
    //                    var email=response.email;
    //                    toastr.success("Mail has been sent successfully");
    //                    $('#error-all').html('Link has been sent on your email address('+email+'), Check your inbox for reset password! <br> Thank you');
    //                    $('.form-control').val('');
    //                     // redirect to google after 5 seconds
    //                     // window.setTimeout(function() {
    //                     //     window.location = "{{ url('/')}}"+"/login";
    //                     // }, 2000);
    //                     // history.go(-1);
                    
    //                 }
    //                 //show the form validates error
    //                 else if(response.success==false ) {                              
    //                     for (control in response.errors) {   
    //                         $('#error-' + control).html(response.errors[control]);
    //                     }
    //                 }
    
    //             },
    //             error: function (xhr, textStatus, errorThrown) {
    //                 // alert("Error: " + errorThrown);
    //             }
    //         });
    //         return false;
    //     });
    //     //close model
    //     $('.close').click(function(){
    //         $('#forgetpass').hide();
    //         $('.form-control').val('');
    //         $('.error_container').html('');
    //     });
    //     $('.back').click(function(){
    //         $('#forgetpass').hide();
    //         $('.form-control').val('');
    //         $('.error_container').html('');
    //     });
    // });
    
    //user authenticate
    
    $(document).on('submit', 'form#addpasswordForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        $(".error_container").html("");
        $('.error-control').removeClass('border-danger');
        $('.login_submit').attr('disabled',true);
        if($('.login_submit').html()!=loadingText)
        {
            $('.login_submit').html(loadingText);
        }
        $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                // return false;
                window.setTimeout(function(){
                    $('.login_submit').attr('disabled',false);
                    $('.login_submit').html('Submit');
                },2000);
                $('.is-invalid').removeClass('is-invalid');
    
                if(response.success==true  ) {          
                    
                    //notify
                    toastr.success("Password created successfully");
                    // redirect to google after 5 seconds
                    // window.setTimeout(function() {
                    //     window.location = "{{ url('/')}}"+"/user/thank-you-user";
                    // }, 2000);
                    window.location = response.redirect;
                
                }
                //show the form validates error
                if(response.success==false ) { 
                    if( response.error_type == 'validation' ){                             
                        for (control in response.errors) {  
                            $('.'+control).addClass('border-danger');    
                            $('#error-' + control).html(response.errors[control]);
                        }
                    }
                    if(response.error_type=='account-deleted')
                    {
                        console.log(response);                                                           
                        $("#wrong-credential").html("");
                        $("#wrong-credential").html("Your Account has been Deleted!");
                    }

                    if(response.error_type=='account-inactive')
                    {
                        console.log(response);                                                           
                        $("#wrong-credential").html("");
                        $("#wrong-credential").html("Your Account has been Deactivated! Please Contact to System Administrator");
                    }
    
                    if(response.error_type=='account-email')
                    {
                        console.log(response);                                                           
                        $("#wrong-credential").html("");
                        $("#wrong-credential").html("Your Account has not been verified yet,check your mail to verify email!");
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
</script>
</body>
</html>
