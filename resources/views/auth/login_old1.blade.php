<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="{{ asset('admin/fonts/font-awesome-all.css')}}" rel="stylesheet" />
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
  {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
  <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
    background-color: rgb(91, 99, 254);
    
    
    position: absolute;
    top: 0;
    bottom: 0;

}
.col-lg-6.login {
    position: absolute;
    left: 50%;
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
    margin: 40px 40px
}
/* .text-danger {
    color: #03136b!important;
}
.text-danger:hover {
    color: #03136b!important;
} */
.logo {
    width: 150px;
    height: 100px;
    margin-top: 20px;
    margin-left: 40vh;
}

img.image {
    width: 100%;
    height: 600px;
}
.border-line {
    border-right: 1px solid #EEEEEE
}

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
    padding: 10px 12px 10px 12px;
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
    background-color: #1A237E;
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
    background-color: rgb(91, 99, 254);
    position: relative;
    left: 0%;
    bottom: 0;
    top: 0px;
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

span.show-hide-password 
{
    position: absolute;
    top: 155px;
    right: 40px;
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
                <div class="row px-3 justify-content-center mt-4 mb-5 border-line"><img src="{{ asset('admin/images/login.png')}}" class="image"> </div>
            </div>
            <div class="col-lg-6 login">
                <div class="card1 pb-5">
                    <div class="row"> <center><a href="{{ url('/') }}"><img src="{{ asset('admin/images/BCD-Logo.png')}}" class="logo text-center"> </a></center></div>
                    <form method="Post" action="{{ url('/userAuthenticate') }}" id="userAuthForm">
                        @csrf
                        <div class="card2 card border-0 px-4 py-5">
                        
                            <div class="row px-3"> 
                                <label class="mb-1">
                                    <h6 class="mb-0 text-sm">Email Address</h6>
                                </label> 
                                <input class="mb-2 error-control email" type="text" name="email" placeholder="Enter a valid email address">
                                <p class="text-danger error_container" id="error-email"></p> 
                            </div>
                            <div class="row px-3"> 
                                <label class="mb-1">
                                    <h6 class="mb-0 text-sm">Password</h6>
                                </label> 
                                <input class="error-control password" type="password" name="password" placeholder="Enter password"> 
                                <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                                <p class="text-danger error_container" id="error-password"></p>
                            </div>
                            <p style="" class="text-danger error_container" id="wrong-credential"> </p>
                            <div class="row px-3 mb-4">
                                <div class="custom-control custom-checkbox custom-control-inline"> <input id="chk1" type="checkbox" name="chk" class="custom-control-input"> <label for="chk1" class="custom-control-label text-sm">Remember me</label> </div> <a class="ml-auto mb-0 text-sm forget" href="javascript:">Forgot Password?</a>
                            </div>
                            <div class="row mb-3 px-3"> <button type="submit" class="btn btn-blue text-center">Login</button> </div>
                    </form>
                    {{-- <div class="row mb-4 px-3"> <small class="font-weight-bold">Don't have an account? <a class="text-danger ">Register</a></small> </div> --}}
                </div>
                    <!-- <div class="row px-3 justify-content-center mt-4 mb-5 border-line"> <img src="https://i.imgur.com/uNGdWHi.png" class="image"> </div> -->
                </div>
            </div>
        </div>
</div>


{{-- Modal for Forget Password --}}
 <div class="modal"  id="forgetpass">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Forget Password</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('/forget/password/email')}}" id="forgetPasswordForm">
          @csrf
          
             <div class="modal-body">
             <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
             
             <div class="form-group">
                <label for="email">Email</label> 
                <input type="text" name="forgetemail" class="form-control" id="forgetemail" placeholder="Enter email" value="{{ old('forgetemail') }}">
                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-forgetemail"></p>  
            </div>
                
             </div>
             <!-- Modal successfooter -->
             <div class="modal-footer">
                
                <button type="submit" class="btn btn-primary  " >Submit </button>
                <button type="button" class="btn btn-danger back" data-dismiss="modal">Close</button>
             </div>
          </form>
       </div>
    </div>
 </div>
{{-- End of Forget Password Model --}}

<script>
$('.forget').click(function(){
    // Open Model Pop up
    $('#forgetpass').toggle();

    $(document).on('submit', 'form#forgetPasswordForm', function (event) {
        event.preventDefault();
        //clearing the error msg
        $('p.error_container').html("");

        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {

                console.log(response);
                if(response.success==true  ) {          
                
                //     //notify
                //    toastr.success("Password changed successfully");
                    // redirect to google after 5 seconds
                    window.setTimeout(function() {
                        window.location = "{{ url('/')}}"+"/login";
                    }, 2000);
                    // history.go(-1);
                
                }
                //show the form validates error
                else if(response.success==false ) {                              
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
    //close model
    $('.close').click(function(){
    $('#forgetpass').hide();
    });
    $('.back').click(function(){
    $('#forgetpass').hide();
    });
});

//user authenticate

$(document).on('submit', 'form#userAuthForm', function (event) {
    event.preventDefault();
    var form = $(this);
    var data = new FormData($(this)[0]);
    var url = form.attr("action");
    $(".error_container").html("");
    $('.error-control').removeClass('border-danger');
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

            $('.is-invalid').removeClass('is-invalid');

            if(response.success==false  ) {
                if( response.error_type == 'validation' ){
                                                                                
                    for (control in response.errors) {
                        $('.'+control).addClass('border-danger');   
                        $('#error-' + control).html(response.errors[control]);
                    }
                    return false;
                }
                if( response.error_type == 'wrong_email_or_password' ){
                    console.log(response);                                                           
                    $("#wrong-credential").html("");
                    $("#wrong-credential").html("Enter a valid email or password!");
                    return false;
                }
                if(response.error_type=='account-inactive')
                {
                    console.log(response);                                                           
                    $("#wrong-credential").html("");
                    $("#wrong-credential").html("Your Account has been Deactivated! Please Contact to System Administrator");
                }
                if(response.error_type=='account-deleted')
                {
                    console.log(response);                                                           
                    $("#wrong-credential").html("");
                    $("#wrong-credential").html("Your Account has been Deleted!");
                }

                if(response.error_type=='account-email')
                {
                    console.log(response);                                                           
                    $("#wrong-credential").html("");
                    $("#wrong-credential").html("Your Account has not been verified yet,check your mail to verify email!");
                }
                
            }
            if(response.success==true  ) {    
                window.location = response.redirect;
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
