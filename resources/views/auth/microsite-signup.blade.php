@extends('layouts.app')
@section('content')
<style>
	.feature-icon
	{
		margin-top: -60px !important;
	}
	.checkbox-blk input {
    margin-top: -37px;
    }
	.withgoogle-blk button
	{
		height: 44px;
    	font-size: 14px;
	}
	
	button
	{
		margin-top: 20px;
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
								<img src="{{asset('admin/microsite/images/1-screen_4.gif')}}">
						</div>
					</div>
 
					<div class="first-logo-section">
						<p>Government approved APIs * : </p>
						<img src="{{asset('admin/microsite/images/kkkk1a.png')}}">
					</div>
				</div>
				<div class="col-md-5 col-sm-12 first-page-div-second order-1 order-md-2">
					@if ($message = Session::get('error'))
                        <div class="alert alert-danger">
                            {{-- <button type="button" class="close" data-dismiss="alert">×</button>	 --}}
                                <strong>{{ $message }}</strong>
                        </div>
                    @endif
					<div class="child-block">
					 	<h4>Sign up to continue</h4>
					 	<form method="post" action="{{ url('/instantuserregister') }}" id="userAuthFormInstant">
                            @csrf
					 		<div class="email-blk mt-3">
                                <label>EMAIL ADDRESS <span class="text-danger">*</span></label>
								<input class="error-control email" type="text" name="email" placeholder="Enter a valid email address"> 
								{{-- <p class="mb-3 text-danger error_container" id="error-email"></p> --}}
                                <span style="" class="text-danger error_container" id="error-email"></span>
					 		</div>
                             <div class="email-blk mt-3">
                                <label>PASSWORD <span class="text-danger">*</span></label>
                                <input type="password" class="error-control password" name="password" placeholder="Enter password">
								<span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                                <p class="text-danger error_container" id="error-password"></p>
                                
                             </div>
                             <span style="" class="text-left text-danger error_container" id="wrong-credential"> </span>
					 		{{-- <div class="checkbox-blk">
					 			<p>
									<input type="checkbox" name="term" id="term">
									<label>By submitting this form, I accept myBCD Terms of Service.</label>
								</p>
									<p  class="text-danger error_container" id="error-term"></p>
					 			<p>
									<input type="checkbox" name="feature" class="feature-icon" id="feature">
									<label>I agree that I am 18+ & using the above feature for my personal purpose only.</label>
								</p>
									<p class="text-danger error_container" id="error-feature"></p>
					 		</div> --}}
							 <ul>
								<li>
									<div class="form-check form-check-inline error-control">
										<input class="form-check-input term" type="checkbox" name="term" id="term">
										<label class="form-check-label pt-1" for="term">By submitting this form, I accept myBCD Terms of Service.</label>
									</div><br>
									<p style="" class="text-danger error_container" id="error-term"></p>
								</li>
								<li>
									<div class="form-check form-check-inline error-control">
										<input class="form-check-input feature" type="checkbox" name="feature" id="feature">
										<label class="form-check-label pt-1" for="feature">I agree that I am 18+ & using the above feature for my personal purpose only.</label>
									</div><br>
									<p style="" class="text-danger error_container" id="error-feature"></p>
								</li>
							</ul>
					 		<button type="submit" class="continue-btn submit-verify">Continue</button>
					 		<p style="color:black !important">Already have an account?<a href="{{route('instantchecks')}}">Log in</a></p>
					 	</form>	
					 	<div class="withgoogle-blk">
					 		<p style="color:black !important">OR</p>
					 		<button><a href="{{url('auth/google')}}"><img src="{{asset('admin/microsite/images/google_dd.png')}}"> &nbsp;Continue with Google</a></button>
					 		{{-- <button><a href="#"><img src="{{asset('admin/microsite/images/Microsoft_Office.png')}}"> &nbsp;&nbsp;Continue with Outlook</a></button> --}}
					 	</div>
					</div>
				</div>
			</div>
	</section>
</body>
</html>

<script>

$(document).on('submit', 'form#userAuthFormInstant', function (event) {
	event.preventDefault();
	//clearing the error msg
	$('span.error_container').html("");
	$('.error-control').removeClass('border-danger');
	var loadingText = '<i class="fa fa-circle-o-notch fa-spin px-2"></i> loading...';
	var form = $(this);
	var data = new FormData($(this)[0]);
	data.set('email',btoa($('.email').val()));
    data.set('password',btoa($('.password').val()));
	var url = form.attr("action");
	$('.submit-verify').addClass('btn-opacity');
	$('.submit-verify').attr('disabled',true);
	if ($('.submit-verify').html() !== loadingText) {
		$('.submit-verify').html(loadingText);
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
				$('.submit-verify').removeClass('btn-opacity');
				$('.submit-verify').attr('disabled',false);
				$('.submit-verify').html('Continue');
			},2000);
			console.log(response.redirect);
			if(response.success==true) {  
				window.location = response.redirect;       
				
				toastr.success('You are register Successfully !');
				//toastr.success('Form Submitted Successfully !');
				//var user_id=response.user_id;
				window.setTimeout(function(){
					// window.location="{{url('/account_verification/')}}"+'/'+user_id;
					//window.location="{{route('/verify/home')}}";
					
				},4000);
			}
			//show the form validates error
			else if(response.success==false) {                              
				for (control in response.errors) {  
					var len = 0;
					var error_msg='';
					if(Array.isArray(response.errors[control]))
					{
						len = response.errors[control].length;
					}
					console.log(len);
					$('.'+control).addClass('border-danger');
					if(len > 1)
					{
						$(response.errors[control]).each(function(key,value){
							if(key+1!=len)
							{
								error_msg+=value+' & ';
							}
							else
							{
								error_msg+=value;
							}
						});
					}
					else
					{
						error_msg+= response.errors[control];
					}

					$('#error-' + control).html(error_msg); 
					
				}
			}
			else
			{
				$('#error-all').html(response.message);
			}
		},
		error: function (xhr, textStatus, errorThrown) {
			// alert("Error: " + errorThrown);
		}
	});
	return false;
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
</script>
@endsection