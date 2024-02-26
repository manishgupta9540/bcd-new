<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>BCD</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" type="image/x-icon" href="{{url('/').'/admin/images/BCD-favicon.png'}}">
        <link rel="stylesheet" href="{{asset('main-web/fonts/material-design-iconic-font/css/material-design-iconic-font.min.css')}}">
        <link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Ruda&display=swap" rel="stylesheet">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
		
		{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css" /> --}}
		{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" /> --}}
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

		<link href="{{ asset('admin/fonts/font-awesome-all.css') }}" rel="stylesheet" />
		<link href="{{ asset('guest/css/responsive.css?ver=1.2')}}" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.1.0/css/all.css" />

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet"/>
		
		<link href="{{ asset('css/signature/signature.css') }}" rel="stylesheet">
		{{-- <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script> --}}
		{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
		<script src="{{ asset('admin/js/jquery.form-validator.min.js') }}"></script>
		{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

		<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/css/intlTelInput.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/intlTelInput.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>

		<link rel="stylesheet" type="text/css" href="{{asset('admin/microsite/css/style.css')}}">
	
		{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> --}}
		{{-- <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet"> 
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>   --}}
      	
      	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
		  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
		  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script> --}}
		 
		  {{-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> --}}
		  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" ></script> --}}

		<script src="{{ asset('js/signature/signature.js') }}"></script>
		{{-- Sweet alert --}}
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js"></script>
		{{-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-V9FYLJ3VPD"></script> --}}
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-252123846-1"></script>
		<script src="{{asset('js/easy.qrcode1.js')}}"></script>
		{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/aes-js/3.1.2/index.min.js"></script> --}}
		{{-- <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script> --}}
		

		<script>
			// window.dataLayer = window.dataLayer || [];
			// function gtag(){dataLayer.push(arguments);}
			// gtag('js', new Date());

			// gtag('config', 'G-V9FYLJ3VPD');
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'UA-252123846-1');
		</script>
		<!-- STYLE CSS -->
		<link href="{{ asset('main-web/css/style_new.css?ver=1.8') }}" rel="stylesheet">
		<link href="{{ asset('main-web/css/animate.css?ver=1.5') }}" rel="stylesheet">
	</head>
     <style type="text/css">
     	/*@media (min-width: 576px) and (max-width: 767.98px) { 
         img.images12 {
         width: 158px;
         position: relative;
         left: 30%;
         }

     	}
     	@media (max-width: 575.98px) { 
         img.images12 {
         width: 158px;
         position: relative;
         left: 30%;
         }
     	 }*/

.verification-logo{
	max-width:150px;
	/*position: relative;
	left:0px;
	top:0px;
	z-index: 1;*/

}
.verification-logo1{
	max-width:120px;
	/*position: relative;
	left:0px;
	top:0px;
	z-index: 1;*/

}

	.footer-list{
		padding: 0px;
		list-style-type: none;

	}

	.footer-list li{
		display: inline;
		margin: 0px 50px;
	}

  .footer-list li a{
    text-decoration:none;
    color: #fff;
  }

  @media only screen and (min-width: 320px) and (max-width: 767px){
	.verification-logo {
			max-width: 120px !important;
		}

		.verification-logo1 {
			max-width: 80px !important;
		}
  }

  
</style>
	<body>
		<section class="first-container">
			<div class="container">
				<div class="row">
					<div class="col-md-12 px-0">
						<nav class="navbar navbar-expand-lg navbar-light registration-nav">
							<a class="navbar-brand" href="{{ url('/') }}"><img class="verification-logo py-2" src="{{ asset('admin/images/BCD-Logo3.png')}}"></a>
							<a class="navbar-brand pl-2" href="{{ url('/') }}"><img class="verification-logo1 py-2" src="{{ asset('admin/images/pcil.png')}}"></a>
							<a class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon"></span>
							</a>
							<div class="collapse navbar-collapse" id="navbarSupportedContent">
								<ul class="navbar-nav ml-auto topnav registration-menu">
									<li class="nav-item active">
										<a class="nav-link" href="{{ url('/') }}">Home <span class="sr-only">(current)</span></a>
									</li>
									@auth
										<?php $account_type =  Helper::get_user_type(Auth::user()->business_id);
												$account_type_candidate =Auth::user()->user_type;
										?>
										@if ($account_type_candidate== 'candidate')
										<li class="nav-item">
											<a class="nav-link" href="{{ env('APP_CANDIDATE_URL') }}">My profile</a>
										</li>
										@else
										@if($account_type == 'superadmin')
											<li class="nav-item">
												<a class="nav-link" href="{{ env('APP_SUPERADMIN_URL') }}">Corporate login</a>
											</li>
										@else
											@if($account_type=='guest')
												<li class="nav-item">
													<a class="nav-link" href="{{env('APP_INSTANT_HOME')}}">My Profile</a>
												</li>
											@elseif ($account_type=='client')
												<li class="nav-item">
													<a class="nav-link" href="{{ route('/my/home')}}">My Profile</a>
												</li>
											@else
												<li class="nav-item">
													<a class="nav-link" href="{{ route('/home')}}">My Profile</a>
												</li>
											@endif
										@endif
										@endif
									@else
										<li class="nav-item">
											<a class="nav-link" href="{{ url('/login')}}">Corporate login</a>
										</li>
									@endauth
									<li class="nav-item">
										<a class="nav-link" href="{{ url('/contactsales') }}">Contact Us</a>
									</li>
									@auth
										<?php $account_type =  Helper::get_user_type(Auth::user()->business_id); ?>
										{{-- @if($account_type != 'guest')
											<li class="nav-item">
												<a class="nav-link" href="{{ url('/startverification') }}">Start a Verification</a>
											</li>
										@endif                --}}
										
									@else
										<li class="nav-item">
											<a class="nav-link" href="{{ route('instantchecks') }}">Start a Verification</a>
										</li>
										{{-- <li class="nav-item">
											<a class="nav-link" href="{{ url('/startverification') }}">Start a Verification</a>
										</li>                --}}
									@endauth
								</ul>
							</div>       
						</nav>
					</div>
				</div>
			</div>
		</section>
		@yield('content')
		<footer class="verification-footer">
			<div class="container-fluid">
			<div class="row">
				<div class="col-md-6">
			@copyright all rights reserved by BCD
				</div>
				<div class="col-md-6">
					<ul class="footer-list">
						<li><a href="{{url('/privacy-policy')}}">Privacy Policy</a></li>
						<li><a href="{{url('/terms')}}">Terms & Conditions</a></li>
					</ul>
				</div>
			</div>
		</div>
		</footer>
		{{-- <script src="js/jquery-3.3.1.min.js"></script> --}}
		{{-- <script src="js/jquery.form-validator.min.js"></script>	 --}}
		<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>	
		<script type="text/javascript">
			$(document).ready(function(){

				$('.customer-logos').slick({
					slidesToShow: 6,
					slidesToScroll: 1,
					autoplay: true,
					autoplaySpeed: 1500,
					arrows: false,
					dots: false,
					pauseOnHover: false,
					responsive: [{
						breakpoint: 768,
						settings: {
							slidesToShow: 4
						}
					}, {
						breakpoint: 520,
						settings: {
							slidesToShow: 3
						}
					}]
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

				$(document).on('submit', 'form#createGuestForm', function (event) {
					event.preventDefault();
					//clearing the error msg
					$('span.error_container').html("");
					$('.error-control').removeClass('border-danger');
					var loadingText = '<i class="fa fa-circle-o-notch fa-spin px-2"></i> loading...';
					var form = $(this);
					var data = new FormData($(this)[0]);
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
								$('.submit-verify').html('CREATE ACCOUNT');
							},2000);
							console.log(response);
							if(response.success==true) {          
								// window.location = "{{ url('/')}}"+"/sla/?created=true";
								// toastr.success('OTP Has Been Sent to Your Mobile Number !');
								// toastr.success('Check Your Whatapp to Verify your account !');
								toastr.success('Check Your Email to Verify your account !');
								toastr.success('Form Submitted Successfully !');
								var user_id=response.user_id;
								window.setTimeout(function(){
									// window.location="{{url('/account_verification/')}}"+'/'+user_id;
									window.location="{{url('/email_verification/')}}"+'/'+user_id;
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

				$(document).on('submit', 'form#contact_form', function (event) {
					event.preventDefault();
					//clearing the error msg
					$('p.error_container').html("");
					$('.error_control').removeClass('border-danger');
					var loadingText = '<i class="fa fa-circle-o-notch fa-spin px-2"></i> loading...';
					var form = $(this);
					var data = new FormData($(this)[0]);
					var url = form.attr("action");
					$('.submit-contact').addClass('btn-opacity');
					$('.submit-contact').attr('disabled',true);
					if ($('.submit-contact').html() !== loadingText) {
						$('.submit-contact').html(loadingText);
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
								$('.submit-contact').removeClass('btn-opacity');
								$('.submit-contact').attr('disabled',false);
								$('.submit-contact').html('SUBMIT');
							},2000);
							console.log(response);
							if(response.success==true) {          
								// window.location = "{{ url('/')}}"+"/sla/?created=true";
								// toastr.success('Check Your Mail to Confirm your account !');
								toastr.success('Contact Form Submitted Successfully !');
								
								window.setTimeout(function(){
									window.location.reload();
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

				$( ".commonDatepicker" ).datepicker({
					format: 'dd-mm-yyyy',
					changeMonth: true,
					changeYear: true,
					firstDay: 1,
					autoclose:true,
					todayHighlight: true,
					
				});


				$("#phone1").intlTelInput({
					initialCountry: "in",
					separateDialCode: true,
					// preferredCountries: ["in"],
					onlyCountries: ["in"],
					geoIpLookup: function (callback) {
						$.get('https://ipinfo.io', function () {
						}, "jsonp").always(function (resp) {
							var countryCode = (resp && resp.country) ? resp.country : "";
							callback(countryCode);
						});
					},
					utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"
				});

				/* ADD A MASK IN PHONE1 INPUT (when document ready and when changing flag) FOR A BETTER USER EXPERIENCE */

				var mask1 = $("#phone1").attr('placeholder').replace(/[0-9]/g, 0);

				$(document).ready(function () {
					$('#phone1').mask(mask1)
				});

				//
				$("#phone1").on("countrychange", function (e, countryData) {
					$("#phone1").val('');
					var mask1 = $("#phone1").attr('placeholder').replace(/[0-9]/g, 0);
					$('#phone1').mask(mask1);
					$('#code').val($("#phone1").intlTelInput("getSelectedCountryData").dialCode);
					$('#iso').val($("#phone1").intlTelInput("getSelectedCountryData").iso2);
				});

				 // phone2
				 $("#phone2").intlTelInput({
					initialCountry: "in",
					separateDialCode: true,
					preferredCountries: ["ae", "in",],
					geoIpLookup: function (callback) {
						$.get('https://ipinfo.io', function () {
						}, "jsonp").always(function (resp) {
							var countryCode = (resp && resp.country) ? resp.country : "";
							callback(countryCode);
						});
					},
					utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"
				 });

				/* ADD A MASK IN PHONE2 INPUT (when document ready and when changing flag) FOR A BETTER USER EXPERIENCE */

				var mask2 = $("#phone2").attr('placeholder').replace(/[0-9]/g, 0);

				$(document).ready(function () {
					$('#phone2').mask(mask2)
				});

				$("#phone2").on("countrychange", function (e, countryData) {
					$("#phone2").val('');
					var mask2 = $("#phone2").attr('placeholder').replace(/[0-9]/g, 0);
					$('#phone2').mask(mask2);
					$('#code2').val($("#phone2").intlTelInput("getSelectedCountryData").dialCode);
					$('#iso2').val($("#phone2").intlTelInput("getSelectedCountryData").iso2);
				});

				// phone3
				$("#phone3").intlTelInput({
					initialCountry: "in",
					separateDialCode: true,
					preferredCountries: ["ae", "in",],
					geoIpLookup: function (callback) {
						$.get('https://ipinfo.io', function () {
						}, "jsonp").always(function (resp) {
							var countryCode = (resp && resp.country) ? resp.country : "";
							callback(countryCode);
						});
					},
					utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"
				});

				/* ADD A MASK IN PHONE3 INPUT (when document ready and when changing flag) FOR A BETTER USER EXPERIENCE */

				var mask2 = $("#phone3").attr('placeholder').replace(/[0-9]/g, 0);

				$(document).ready(function () {
					$('#phone3').mask(mask2)
				});

				$("#phone3").on("countrychange", function (e, countryData) {
					$("#phone3").val('');
					var mask2 = $("#phone3").attr('placeholder').replace(/[0-9]/g, 0);
					$('#phone3').mask(mask2);
					$('#code2').val($("#phone3").intlTelInput("getSelectedCountryData").dialCode);
					$('#iso2').val($("#phone3").intlTelInput("getSelectedCountryData").iso2);
				});

				// phone4
				$("#phone4").intlTelInput({
					initialCountry: "in",
					separateDialCode: true,
					preferredCountries: ["ae", "in",],
					geoIpLookup: function (callback) {
						$.get('https://ipinfo.io', function () {
						}, "jsonp").always(function (resp) {
							var countryCode = (resp && resp.country) ? resp.country : "";
							callback(countryCode);
						});
					},
					utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"
				});

				/* ADD A MASK IN PHONE4 INPUT (when document ready and when changing flag) FOR A BETTER USER EXPERIENCE */

				var mask2 = $("#phone4").attr('placeholder').replace(/[0-9]/g, 0);

				$(document).ready(function () {
					$('#phone4').mask(mask2)
				});

				$("#phone4").on("countrychange", function (e, countryData) {
					$("#phone4").val('');
					var mask2 = $("#phone4").attr('placeholder').replace(/[0-9]/g, 0);
					$('#phone4').mask(mask2);
					$('#code2').val($("#phone4").intlTelInput("getSelectedCountryData").dialCode);
					$('#iso2').val($("#phone4").intlTelInput("getSelectedCountryData").iso2);
				});

				// phone5
				$("#phone5").intlTelInput({
					initialCountry: "in",
					separateDialCode: true,
					preferredCountries: ["ae", "in",],
					geoIpLookup: function (callback) {
						$.get('https://ipinfo.io', function () {
						}, "jsonp").always(function (resp) {
							var countryCode = (resp && resp.country) ? resp.country : "";
							callback(countryCode);
						});
					},
					utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"
				});

				/* ADD A MASK IN PHONE5 INPUT (when document ready and when changing flag) FOR A BETTER USER EXPERIENCE */

				var mask2 = $("#phone5").attr('placeholder').replace(/[0-9]/g, 0);

				$(document).ready(function () {
					$('#phone5').mask(mask2)
				});

				$("#phone5").on("countrychange", function (e, countryData) {
					$("#phone5").val('');
					var mask2 = $("#phone5").attr('placeholder').replace(/[0-9]/g, 0);
					$('#phone5').mask(mask2);
					$('#code2').val($("#phone5").intlTelInput("getSelectedCountryData").dialCode);
					$('#iso2').val($("#phone5").intlTelInput("getSelectedCountryData").iso2);
				});


			});
		</script>

<script>  
	function loginActive()  
	{  
		   
	  $.ajax({  
		  url:"{{url('/')}}"+'/login_activity',  
		  type: "POST",  
		  data: {"_token" : "{{ csrf_token() }}"},  
		  success:function(response)  
		  { 
			//  alert(response);
		  }  
	  });             
	}  
	setInterval(function(){   
	  loginActive();   
		}, 60000);    
</script> 
	</body>
</html>