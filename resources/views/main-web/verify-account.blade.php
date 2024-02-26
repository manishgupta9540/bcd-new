<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCD</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{url('/').'/admin/images/BCD-favicon.png'}}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link href="{{ asset('admin/fonts/font-awesome-all.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
  <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
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
    /* background-color: rgb(91, 99, 254); */
    background-color: #002e62;
    position: absolute;
    /*left: 50%;*/
    top: 0;
    bottom: 0;

}
.col-lg-6.login {
    position: absolute;
    left:50%;
    
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
    /* width: 150px; */
    height: 100px;
    margin-top: 20px;
    
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

.btn-info {
  color: #fff;
  background-color: #003473;
  border-color: #003473; }
  .btn-info:hover {
    color: #fff;
    background-color: #00234d;
    border-color: #001d40; }
  .btn-info:focus, .btn-info.focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 52, 115, 0.5); }
  .btn-info.disabled, .btn-info:disabled {
    color: #fff;
    background-color: #003473;
    border-color: #003473; }
  .btn-info:not(:disabled):not(.disabled):active, .btn-info:not(:disabled):not(.disabled).active,
  .show > .btn-info.dropdown-toggle {
    color: #fff;
    background-color: #001d40;
    border-color: #001733; }
    .btn-info:not(:disabled):not(.disabled):active:focus, .btn-info:not(:disabled):not(.disabled).active:focus,
    .show > .btn-info.dropdown-toggle:focus {
      box-shadow: 0 0 0 0.2rem rgba(0, 52, 115, 0.5); }

/* input,
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
} */

input:focus,
textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 3px solid #304FFE;
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
h1.count {
    position: relative;
    /* top: 30px; */
    top: 24px;
    left: 55px;
    border: 1px solid #e2ecfe;
    width: 34px;
    height: 43px;
    border-radius: 50%;
    padding: 7px;
    font-size: 29px;
    color: #213b7e;
    background-color: #e2ecfe;
}
.verifiy-btn{
			   background: #E10813;
			   max-width:200px;
			   color:#fff;
			   padding:10px 15px;
			   font-size:12px;
			   border-radius:5px;
			   margin-top:25px;
		   }
.disabled-link
{
    pointer-events: none;
}
@media screen and (max-width: 991px) {
    .col-lg-6.login {
    position: relative;
     left: 0%; 
}
    .col-lg-6.imageblock {
    /* background-color: rgb(91, 99, 254); */
    background-color: #002e62;
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
</style>
<body>
<div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 login">
                <div class="card1 pb-5">
                    <div class="row"> 
                         <div class="col-lg-12">
                        <center><a href="{{ url('/') }}"><img src="{{ asset('admin/images/BCD-Logo1.png')}}" class="logo text-center"> </a></center>
                     </div>
                    </div>
                    <div class="card2 card border-0 px-4 py-2">
                        <h1 class="count">1</h1>
                    {{-- <i class="fab fa-whatsapp ml-2 mb-1" style="font-size: 70px;color: #2b4691;border-radius: 50%;border: 1px solid #e2ecfe;width: 90px;padding: 7px 15px;background-color: #e2ecfe;"></i> --}}
                    <i class="fas fa-envelope ml-2 mb-1" style="font-size: 55px;;color: #2b4691;border-radius: 50%;border: 1px solid #e2ecfe;width: 90px;padding: 7px 15px;background-color: #e2ecfe;"></i>
                    <h4 class="mb-3 text-center" style="color: #304ca8; font-weight: 600;">Please Enter The OTP To Verify Your Account</h4>
                    <span class="text-center mb-2" style="color: #304ca8; font-weight: 600;"> Mobile Number : +{{$user->phone_code.'-'.$user->phone}} </span>
                    <form method="post" action="{{url('/account_verification',['id' =>  base64_encode($user->id)])}}" id="verifyaccountFrm">
                        @csrf
                        <input type="hidden" name="user_id" class="user_id" value="{{base64_encode($user->id)}}">
                        <input type="hidden" name="sms_date_time" class="sms_date_time" value="{{$user->sms_otp_sent_at}}">
                        <div class="form-group">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-sm-5 text-center" style="color: #304ca8; font-weight: 600;">
                                    <label for="label_name"> OTP </label>
                                </div>
                            </div>
                            <div class="row justify-content-center align-items-center">
                                <div class="col-sm-6 text-center">
                                    <input name="otp[]" class="digit text-center otp" type="text" id="first_otp" size="1" maxlength="1" tabindex="0" >
                                    <input name="otp[]" class="digit text-center otp" type="text" id="second_otp" size="1" maxlength="1" tabindex="1">
                                    <input name="otp[]" class="digit text-center otp" type="text" id="third_otp" size="1" maxlength="1"  tabindex="2">
                                    <input name="otp[]" class="digit text-center otp" type="text" id="fourth_otp" size="1" maxlength="1" tabindex="3">
                                </div>
                            </div>
                            <div class="row justify-content-center align-items-center">
                                <div class="col-sm-6 text-center">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container pt-2" id="error-otp"></p> 
                                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                                </div>
                            </div>
                            <div class="row justify-content-center align-items-center pt-1">
                                <div class="col-sm-6 text-center">
                                    @php
                                        $resend = 'd-none disabled-link';

                                        $time_limit='';

                                        $sms_date_time = date('Y-m-d H:i:s',strtotime($user->sms_otp_sent_at.' +'.'3 minutes'));

                                        $today_date_time = date('Y-m-d H:i:s');

                                        if(strtotime($today_date_time) > strtotime($sms_date_time))
                                        {
                                            $resend = '';

                                            $time_limit = 'd-none';
                                        }
                                    @endphp
                                    <span class="resend {{$resend}} resend_otp" style="color: #304ca8;"><a href="javascript:void(0)"> <i class="fas fa-undo-alt"></i> Resend OTP</a></span>
                                    <span class="time {{$time_limit}} text-dark" id="time" style="color: #304ca8;">Time left : <span id="timer"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info verify"> Verify </button>
                        </div>
                    </form>
                    
                </div>
                    <!-- <div class="row px-3 justify-content-center mt-4 mb-5 border-line"> <img src="https://i.imgur.com/uNGdWHi.png" class="image"> </div> -->
                </div>
            </div>
            <div class="col-lg-6 imageblock">
                <div class="row px-3 justify-content-center mt-4 mb-5 border-line"><img src="{{ asset('admin/images/login-vector-img.png')}}" class="image"> </div>
            </div>
        </div>    
</div>
<script>
$(document).ready(function(){
    $(document).on('submit', 'form#verifyaccountFrm', function (event) {
    
        $("#overlay").fadeIn(300);　
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var $btn = $(this);
        $('.verify').attr('disabled',true);
        $('.otp').removeClass('border-danger');
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
        if($('.verify').html()!=loadingText)
        {
            $('.verify').html(loadingText);
        }
        $('.error-container').html('');
        $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                // console.log(data);
                // $('.error-container').html('');
                window.setTimeout(function(){
                    $('.verify').attr('disabled',false);
                    $('.verify').html('Verify');
                },2000);
                if (data.fail && data.error_type == 'validation') {
                        
                        //$("#overlay").fadeOut(300);
                        for (control in data.errors) {
                            $('.' + control).addClass('border-danger');
                            $('#error-' + control).html(data.errors[control]);
                        }
                } 
                if (data.fail && data.error == 'yes') {
                    
                    $('#error-all').html(data.message);
                }
                if (data.fail == false) {
                    // $('#send_otp').modal('hide');
                    // alert(data.id);
                    // alert('abd');
                    toastr.success('Account Verified Successfully');

                    window.setTimeout(function(){
                        window.location="{{ url('/') }}"+"/thank-you-account_verify/";
                    },2000);
                    
                    // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                    //  location.reload(); 
                }
            },
            error: function (data) {
                // console.log(data);
            }
            // error: function (xhr, textStatus, errorThrown) {
            //     console.log("Error: " + errorThrown);
            //     // alert("Error: " + errorThrown);

            // }
        });
        event.stopImmediatePropagation();
        return false;

    });
    $(document).on('click','.resend_otp',function(event){
        var _this=$(this);
        var user_id = $('.user_id').val();
        // alert(user_id);
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
        _this.addClass('disabled-link');
        if(_this.html()!=loadingText)
        {
            _this.html(loadingText);
        }
        $.ajax
        ({
                type:'POST',
                url: "{{ url('/')}}"+"/account/resendotp",
                data: {"_token": "{{ csrf_token() }}",'id':user_id},        
                success: function (response) {        
                    window.setTimeout(function(){
                        _this.removeClass('disabled-link');
                        _this.html('<a href="javascript:void(0)"> <i class="fas fa-undo-alt"></i> Resend OTP</a>');
                    },2000);

                    if (response.status) { 

                        toastr.success("OTP Sent Successfully");

                        _this.addClass('d-none disabled-link');

                        $('.sms_date_time').val(response.date);

                        $('.otp').val();

                        $('.error-container').html('');

                        $('.otp').removeClass('border-danger');

                        $('#timer').html(03 + ":" + 00);
                        $('.time').removeClass('d-none');
                        startTimer();

                    } else {
                        toastr.error("Something Went Wrong !!");
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
        });

    });
});
    function OTPInput() {
        const inputs = document.querySelectorAll('.otp');
        // alert(inputs.length);
        for (let i = 0; i < inputs.length; i++) 
        { 
            inputs[i].addEventListener('keyup', function(event) 
            { 
                if (event.key==="Backspace" ) 
                { 
                    inputs[i].value='' ; 
                    if (i !==0) inputs[i - 1].focus();
                    
                } 
                else { 
                    if (i===inputs.length - 1 && inputs[i].value !=='' ) 
                    { return true; } 
                    else if (event.keyCode> 47 && event.keyCode < 58) 
                    { 
                        inputs[i].value=event.key; 
                        if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); 
                        
                    } 
                    else if (event.keyCode> 95 && event.keyCode < 106) 
                    { 
                        inputs[i].value=event.key; 
                        if (i !==inputs.length - 1) 
                        inputs[i + 1].focus(); event.preventDefault(); 
                        
                    }
                } 
                
            }); 
            
        } 
        
    } 
    OTPInput();

        var date_future = new Date('{{$user->sms_otp_sent_at}}');
        date_future.setMinutes(date_future.getMinutes() + 3);

        var today_date =  new Date().toISOString().slice(0, 10);
        var today = new Date();
        var today_time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        var date_now = today_date+' '+today_time;
		var diff = new Date(date_future) - new Date(date_now);

        // alert(diff);

        if(diff > 0)
        {
            var sec = Math.floor(diff/1000);
            var min = Math.floor(sec/60);
            var h = Math.floor(min/60);
            var d = Math.floor(h/24);
            
            h = h-(d*24);
            min = min-(d*24*60)-(h*60);
            sec = sec-(d*24*60*60)-(h*60*60)-(min*60);
        
            $('#timer').html(min + ":" + sec);
            startTimer();
        }
        else
        {
            $('.resend').removeClass('d-none');
            $('.resend').removeClass('disabled-link');
            $('.time').addClass('d-none');
        }
        


function startTimer() {
  var presentTime = $('#timer').html();
  var timeArray = presentTime.split(/[:]+/);
  var m = timeArray[0];
  var s = checkSecond((timeArray[1] - 1));
  if(s==59){m=m-1}
  if(m<0){
    $('.time').addClass('d-none');
    $('.resend').removeClass('d-none');
    $('.resend').removeClass('disabled-link');
    return
  }
  
  
  $('#timer').html(m + ":" + s);
    //   console.log(m);
  setTimeout(startTimer, 1000);
  
}

function checkSecond(sec) {
  if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
  if (sec < 0) {sec = "59"};
  return sec;
}
</script>
</body>
</html>
