@extends('layouts.app')
@section('content')
<body>
<style>
.otp1 {
    display: flex;
    }
    .withgoogle-blk button
    {
        margin-top: 20px;
    }
    .withgoogle-blk button
	{
		height: 44px;
    	font-size: 14px;
	}
  
    .digit {
    margin-left: 9px;
    padding: 0px;
    height: 40px;
    width:40px;
}
.otpbtn1
{
    width:90px;
    background-color:#002e62;
    border:1px solid #002e62;
    color:#fff;
}
.otpbtn2
{
    width:90px;
    background-color:#e10813;
    border:1px solid #e10813;
    color:#fff;
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
	
	<section class="first-page-section">
			<div class="row">
				<div class="col-md-7 col-sm-12 automate-section-one order-2 order-md-1">
					<div>
						<h1>Automate Your Background Verification Process</h1>
						<p class="automate-section-one-p">Spending all your time and resources on manually performing background checks? We have got you covered. Our 100% Automated Verification.</p>
					</div>
					<div class="automate-list">
						<div class="col-md-4 col-sm-12 padding0">
							<ul>
								<li>Real-time tracking</li>
								<li>Instant Verification</li>
								<li>Reliable</li>
								<li>Seamless</li>
								<li>User-friendly</li>
								<li>Full Contactless Support</li>
								<li>Identity-thefts</li>
								<li>Cheapest Price Services</li>
								<li>Profitable Business</li>
							</ul>
						</div>
						<div class="col-md-8 col-sm-12 padding0">
								<img src="{{asset('admin/microsite/images/BCD.gif.gif')}}">
						</div>
					</div>

					<div class="first-logo-section">
						<p>Government approved APIs * : </p>
						<img src="{{asset('admin/microsite/images/kkkk1a.png')}}">
					</div>
                    <br>
                    <p class="automate-section-one-p12">The Aadhaar card, issued by the Government of India, is a trusted source of personal information, including full name, address, and mobile number, which can be used to verify an individual's identity. To further enhance trust and prevent fraudulent activities, organizations can use various verification APIs for PAN Card, Voter ID, Driving License, and Passport. These APIs are recommended by government agencies such as the Income Tax Department, and provide a reliable way to confirm the legitimacy of an individual's identity. By using these APIs, organizations can ensure the credibility of their customers or users, and prevent potential security risks. Simply providing a PAN number or uploading the relevant document can quickly verify an individual's identity, making the process simple, efficient, and trustworthy.</p>
				</div>

               
				<div class="col-md-5 col-sm-12 first-page-div-second order-1 order-md-2">
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger">
                            {{-- <button type="button" class="close" data-dismiss="alert">×</button>	 --}}
                                <strong>{{ $message }}</strong>
                        </div>
                    @endif
                       
					<div class="child-block">
					 	<h4>Log in to continue</h4>
					 	<form method="Post" action="{{ url('/userAuthenticate') }}" id="userAuthForm">
                            @csrf
					 		<div class="email-blk mt-3">
								<label>EMAIL ADDRESS <span class="text-danger">*</span></label>
								<input class="error-control email" type="text" name="email" placeholder="Enter a valid email address"> 
								<p class="mb-3 text-danger error_container" id="error-email"></p>
					 		</div>
					 		<div class="email-blk">
								<label>PASSWORD <span class="text-danger">*</span></label>
								<input type="password" class="error-control password" name="password" placeholder="Enter password">
                                {{-- <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span> --}}
                                <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                                <p class="text-danger error_container" id="error-password"></p>
					 		</div>
                             <span style="" class="text-left text-danger error_container" id="wrong-credential"> </span>
					 		<a href="{{url('/forgot-password')}}" class="ml-auto mb-0 text-sm forget">Forgot Password?</a>
					 		<!-- <div class="checkbox-blk">
					 			<p><input type="checkbox" name="">
								<label>By submitting this form, I accept myBCD Terms of Service.</label></p>
					 			<p><input type="checkbox" name="">
								<label>I agree that I am 18+ & using the above feature for my personal purpose only.</label></p>
					 		</div> -->
					 		<button type="submit" class="continue-btn login_submit">Continue</button>
					 		<p style="color:black !important">Don't have an account?111111<a href="{{route('/instantchecks-signup')}}"> Sign up</a></p>
					 	</form>	
					 	<div class="withgoogle-blk">
					 		<p style="color:black !important">OR</p>
					 		<button><a href="{{ url('auth/google') }}"><img src="{{asset('admin/microsite/images/google_dd.png')}}"> &nbsp;Continue with Google</a></button>
					 		{{-- <button><a href=""><img src="{{asset('admin/microsite/images/Microsoft_Office.png')}}"> &nbsp;&nbsp;Continue with Outlook</a></button> --}}
					 	</div>
					</div>
				</div>
			</div>
	</section>

    {{-- account verification modal start --}}
    <div class="modal fade"  id="account_ver_mdl">
        <div class="modal-dialog">
           <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                 <h4 class="modal-title text-muted">Account Verification</h4>
                 <button type="button" class="close btn-disable" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
              </div>
              <!-- Modal body -->
              <form method="post" action="{{url('/account/mobileverify')}}" id="accountverifyfrm">
              @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email" class="text-muted">Mobile Number <span class="text-danger">*</span> <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Please Enter Your Whatsapp Number"></i></label> 
                        <input type="text" name="mobile_number" class="form-control mobile_number" id="mobile_number" autocomplete="off">
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-mobile_number"></p>  
                    </div>
                    <p style="margin-bottom: 2px;font-size:12px;" class="text-success error-container error-all" id="error-all"> </p> 
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info submit btn-disable">Submit</button>
                    <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
                </div>
              </form>
           </div>
        </div>
    </div>
    {{-- end account verification modal --}}

    {{-- otp verification modal --}}
    <div class="modal" id="verificaion">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="ser_name">Email verification!</h4>
                    {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
                </div>
                <!-- Modal body -->
                <form method="post" action="{{url('/verfiy_otp')}}" id="verificationfrm">
                    @csrf
                    <input type="hidden" name="verify_email" id="verify_email">
                    <div class="modal-body">
                        {{-- <div id="verify_msg"> --}}
                        <div class="form-group">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-sm-12 text-center">
                                    <label for="label_name"> OTP </label>
                                </div>
                            </div>
                            <div class="row justify-content-center align-items-center">
                                <div class="col-sm-6 text-center otp1">
                                    <input name="otp[]" class="digit text-center otp" type="text" id="first_otp" size="1" maxlength="1" tabindex="0" >
                                    <input name="otp[]" class="digit text-center otp" type="text" id="second_otp" size="1" maxlength="1" tabindex="1">
                                    <input name="otp[]" class="digit text-center otp" type="text" id="third_otp" size="1" maxlength="1"  tabindex="2">
                                    <input name="otp[]" class="digit text-center otp" type="text" id="fourth_otp" size="1" maxlength="1" tabindex="3">
                                </div>
                            </div>
                            <div class="row justify-content-center align-items-center">
                                <div class="col-sm-6 text-center">
                                    
                                    <p style="margin-bottom: 2px;" class="text-danger error-container pt-2 error-otp" id="error-otp"></p> 
                                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                                </div>
                            </div>
                        </div>
                        {{-- </div> --}}
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info verificaion-submit otpbtn1">Submit </button>
                        <button type="button" class="btn btn-danger closeemail otpbtn2" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end otp verification  --}}

    {{-- user logged out modal --}}
    <div class="modal" id="logged_in">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="ser_name">Warning!</h4>
                    {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
                </div>
                <!-- Modal body -->
                <form method="post" action="{{url('/user_loggedout')}}" id="loggedinfrm">
                 @csrf
                    <input type="hidden" name="loggedin_email" id="loggedin_email">
                    <div class="modal-body">
                        <div id="loggedin_msg">
    
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info logout-submit">Log out </button>
                        <button type="button" class="btn btn-danger btn_otp" id="otp_close" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end user logged out modal --}}

<script>
$(document).ready(function(){
    $(document).on('click','.logout-submit',function(){
        var form = $('#loggedinfrm');
        var data = new FormData(form[0]);
        var url = form.attr("action");
        // var email=$('#loggedin_email').val();
        // console.log(email);
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                // return false;
                
                if(response.success==true  ) {
                    // toastr.success("OTP Sent Successfully");
                    window.location="{{url('/login')}}";
                    // window.location.href='{{ Config::get('app.admin_url')}}';
                }
            },
        });
    });
            

    $(document).on('submit', 'form#verificationfrm', function (event) {    
        var verform = $(this);
        var verdata = new FormData($("#verificationfrm")[0]);
        // var data = new FormData($(this)[0]);
        // var verurl = verform.attr("action");
        // alert(verdata);
        // var $btn = $(this);
        $('.btn_otp').attr('disabled',true);
        $('.otp').removeClass('border-danger');
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        if($('.verificaion-submit').html()!=loadingText)
        {
            $('.verificaion-submit').html(loadingText);
        }
        $('.error-container').html('');
        $.ajax({
            type: 'POST',
            url: "{{ route('/verify_otp') }}",
            data: verdata,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                // console.log(data);
                // $('.error-container').html('');
                window.setTimeout(function(){
                    $('.btn_otp').attr('disabled',false);
                    $('.verificaion-submit').html('Submit');
                },2000);
                if (data.fail && data.error_type == 'validation') {
                                            
                        //$("#overlay").fadeOut(300);
                        for (control in data.errors) {
                            $('.' + control).addClass('border-danger');
                            $('#error-' + control).html(data.errors[control]);
                        }
                } 
                if (data.success==false && data.error_type == 'yes') {
                
                    $('.error-otp').html(data.message);
                }
                if(data.success==true  ) {   
                    window.setTimeout(function(){
                    $('#verificaion').hide();   
                    },2000);
                    window.location = data.redirect;
                }
            },
            error: function (data) {
                console.log(data);
            }
            // error: function (xhr, textStatus, errorThrown) {
            //     console.log("Error: " + errorThrown);
            //     // alert("Error: " + errorThrown);

            // }
        });
        event.stopImmediatePropagation();
        return false;
    });

    $(document).on('submit', 'form#userAuthForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        data.set('email',btoa($('.email').val()));
        data.set('password',btoa($('.password').val()));
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
                        $('.login_submit').html('LOGIN');
                    },2000);
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
                    if( response.error_type == 'To many attempts' ){
                        console.log(response);                                                           
                        $("#wrong-credential").html("");
                        $("#wrong-credential").html("To many wrong attempts ..You are blocked for 3 hours!");
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
                        // $("#wrong-credential").html("Your Account has not been verified yet, here's a link for <span class='account_verify' style='color: #304ca8;'><a href='javascript:void(0)'>Account Verification</a></span>");
                        $("#wrong-credential").html("Your Account has not been verified yet, Check your email to verify your account !!");
                    }
                    if(response.error_type=='logged-in')
                    {
                        console.log(response);                                                           
                        // $("#loggedin_msg").html("");
                        // $("#wrong-credential").html("Your Account has not been verified yet, here's a link for <span class='account_verify' style='color: #304ca8;'><a href='javascript:void(0)'>Account Verification</a></span>");
                        $('#verify_email').val(response.email);
                        $('#loggedin_msg').html("It seems like you have logged in another browser, If you want to login here?");
                        // $("#logged_in").modal("show");
                
                        // $('#logged_in').modal({
                        //     backdrop: 'static',
                        //     keyboard: false
                        // });
                        $('#verificationfrm')[0].reset();
                        $('.otp').removeClass('border-danger');
                            $('.error-container').html('');
                        $('#verificaion').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        // $("#wrong-credential").html("It seems like you have logged in another browser, If you want to login here?");
                    }

                    // window.setTimeout(function(){
                    //     $('.login_submit').attr('disabled',false);
                    //     $('.login_submit').html('LOGIN');
                    // },2000);
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

    $(document).on('click','.account_verify',function(){
        $('#accountverifyfrm')[0].reset();
        $('.error-container').html('');
        $('.form-control').removeClass('border-danger');
        $('#account_ver_mdl')
            .modal({
                backdrop: 'static',
                keyboard: false
            });
    });

    // $(document).on('click','.js-show-hide',function (e) {
        
    //     e.preventDefault();

    //     var _this = $(this);

    //     if (_this.hasClass('has-show-hide'))
    //     {
    //         _this.parent().find('input').attr('type','text');
    //         _this.html('<i class="fa fa-eye"></i>');
    //         _this.removeClass('has-show-hide');
    //     }
    //     else
    //     {
    //         _this.addClass('has-show-hide');
    //         _this.parent().find('input').attr('type','password');
    //         _this.html('<i class="fa fa-eye-slash"></i>');
    //     }


    // });

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
</script>
@endsection