@extends('layouts.app')
@section('content')
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
		  
		   .instant-verify{
			   background-color:#F9F9F9; 
			   padding:55px 225px;
			   margin:50px 0px;
		   }
		   .instant-verify h3{
			   font-size:30px;
			   color:#142550;
			   margin:15px 0px;
			   text-align: center;

		   }
		   .verifiy-form{
			   padding:10px 100px;
			   margin:0px 20px 50px 20px;

		   }
		   .verifiy-form label{
			   /* font-family:Segoe UI; */
			   color: #002e62!important;
			   margin-bottom: 8px!important;

		   }
		   .custom-input{
			   margin:0px 0px 15px 0px;
			   padding:5px 10px;
			   width:100%;
			   height:auto;

			   background-color: transparent;
			   border: 1px solid #fff;
			   box-shadow: 0px 0px 5px #C4C3C3;

		   }
		   .custom-input:disabled, .custom-input[readonly] {
				background-color: #eee;
				opacity: 1;
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
		   .fw-600{
			   font-weight: 600;
		   }
		   .advance-feature{
			   text-align: center;
		   }
		   .advance-feature h2{
			   color:#142550;
			   font-weight:600;
			   padding:25px 180px;
			   margin:20px 0px;
		   }
		   .advance-feature h6{
			   font-size:18px;
			   color:#142550;
			   font-weight:600;
			   padding:15px 0px;
		   }
		   .advance-feature p{
			   color:#474747;
			   line-height:29px;
			   text-align:justify;
			   padding:0px 20px;
		   }
		   .text-blue{color:#142550;}
		   .text-para{color:#474747;}
		   .para-custom{
			   line-height: 29px;
			   color:#444444;
		   }
		   .mt-80{margin-top:80px;}

		   .hiring-process{
			   background-color:#F9F9F9;
			   padding:50px 0px;
		   }

		   .first-container{
			   /*background-image: url('images/bg-banner.png');
			   background-size: cover;
			   background-repeat: no-repeat;
			   height: 590px;
			   box-shadow: 0px 5px 10px #ddd;*/
			   box-shadow: 0px 5px 10px #999;
			   position: sticky;
			   top: 0px;
			   background: #fff;
			   z-index: 4;

		   }
		   .banner-section{
			   background-image: url('images/bg-banner.png');
			   background-size: cover;
			   background-repeat: no-repeat;
			   height: 590px;
			   box-shadow: 0px 5px 10px #ddd;

		   }
		   .registration-menu{
			   list-style-type: none;
			   padding: 0px;
			   /*position: absolute;
			   right:130px;
			   top:20px;
			   z-index:2;*/
		   }
		   .registration-menu li{
			   display: inline;
			   margin:5px 10px;
		   }
		   .registration-menu li a{
			   font-weight: 500;
			   line-height: 1;
			   font-size: 17px;
			   font-family: 'Ruda', sans-serif;
			   color:#002e62!important;
			   text-decoration: none;

		   }
		   .registration-menu li a:hover{
			   color:#ff0000!important;
		   }

		   .sec-one-left {
				/* margin: 50px 0;
				padding: 50px 0; */
				position: absolute;
				top: 200px;
			}

		   .sec-one-left h4 {
			   color: #F63A55;
			   font-size: 44px;
			   font-weight: 600;
		   }

		   .sec-one-left h6 {
			   font-size: 24px;
			   color: #000000;
			   font-weight: 600;
			   line-height: 32px;
		   }
		   .form-banner {
			   background:rgba(241, 164, 175, 0.55);
			   color: #585858;
			   text-align: center;
			   padding: 10px;
			   margin-top:30px ;
		   }
		   .form-banner h6 {
			   font-size: 18px;
			   line-height: 24px;
		   }
		   .form-banner h6 span {
			   color: #E11E26;
			   font-weight: 600;
		   }
		   .registration-nav{
			   position: relative;
			   top: 0px;
			   left: 0px;
			   padding: 0px;
		   }
		   .verification-footer{
			   /* background-color: #002E62; */
			   background-color: #ACACAC;
			   text-align: center;
			   color: #fff!important;
			   padding: 10px ;
		   }
		   .sec-six-heading {
			   text-align: center;
			   padding:50px 380px;
		   }
		   .sec-six-heading h4 {
			   color: #171C3A;
			   font-size: 30px;
			   font-weight: 600;
		   }
		   .sec-six-heading h6 {
			   font-size: 18px;
			   color: #447C8D;
			   line-height: 24px;
		   }
		   /*slider*/
		   .slick-slide {
			   margin: 0px 20px;
		   }

		   .slick-slide img {
			   width: 100%;
		   }
		   .customer-logos img{
			   max-width: 130px;
		   }

		   .slick-slider
		   {
			   position: relative;
			   display: block;
			   box-sizing: border-box;
			   -webkit-user-select: none;
			   -moz-user-select: none;
			   -ms-user-select: none;
					   user-select: none;
			   -webkit-touch-callout: none;
			   -khtml-user-select: none;
			   -ms-touch-action: pan-y;
				   touch-action: pan-y;
			   -webkit-tap-highlight-color: transparent;
		   }

		   .slick-list
		   {
			   position: relative;
			   display: block;
			   overflow: hidden;
			   margin: 0;
			   padding: 0;
		   }
		   .slick-list:focus
		   {
			   outline: none;
		   }
		   .slick-list.dragging
		   {
			   cursor: pointer;
			   cursor: hand;
		   }

		   .slick-slider .slick-track,
		   .slick-slider .slick-list
		   {
			   -webkit-transform: translate3d(0, 0, 0);
			   -moz-transform: translate3d(0, 0, 0);
				   -ms-transform: translate3d(0, 0, 0);
				   -o-transform: translate3d(0, 0, 0);
					   transform: translate3d(0, 0, 0);
		   }

		   .slick-track
		   {
			   position: relative;
			   top: 0;
			   left: 0;
			   display: block;
		   }
		   .slick-track:before,
		   .slick-track:after
		   {
			   display: table;
			   content: '';
		   }
		   .slick-track:after
		   {
			   clear: both;
		   }
		   .slick-loading .slick-track
		   {
			   visibility: hidden;
		   }

		   .slick-slide
		   {
			   display: none;
			   float: left;
			   height: 100%;
			   min-height: 1px;
		   }
		   [dir='rtl'] .slick-slide
		   {
			   float: right;
		   }
		   .slick-slide img
		   {
			   display: block;
		   }
		   .slick-slide.slick-loading img
		   {
			   display: none;
		   }
		   .slick-slide.dragging img
		   {
			   pointer-events: none;
		   }
		   .slick-initialized .slick-slide
		   {
			   display: block;
		   }
		   .slick-loading .slick-slide
		   {
			   visibility: hidden;
		   }
		   .slick-vertical .slick-slide
		   {
			   display: block;
			   height: auto;
			   border: 1px solid transparent;
		   }
		   .slick-arrow.slick-hidden {
			   display: none;
		   }
		   .left-content{
			   padding:0px 80px; 
			   background-color: #002e62; 
			   /* height:150vh; */
			   box-shadow: 0px 0px 0px #ddd;
		   }

		   .left-content h4{
				font-size: 30px;
			}
			.left-content ul{
				list-style-type: none;
			}
			.left-content ul li{
				margin: 10px 0px;
			}
			.left-content ul li:before {
				display: inline-block;
				font-style: normal;
				font-variant: normal;
				text-rendering: auto;
				-webkit-font-smoothing: antialiased;
				font-family: "Font Awesome 5 Free"; 
				/*font-weight: 900;*/ 
				content: "\f058";
				margin-right:10px;
				font-size: 20px;
			}

		   .btn-opacity
		   {
			   opacity: .65;
		   }

		   /*.ban-data{position: absolute;
	   top:80px;left:0px;}*/

	   /*DESIGN-1*/
	   .banner-1{
		   background-image: url('admin/images/background-image-grey.png');
		   background-size: cover;
		   background-repeat: no-repeat;
		   background-position: 0% 100%;
		   /*opacity: 0.5*/
			   
	   }

	   ::placeholder {
	   color: #ddd;
	   opacity: 1; /* Firefox */
	   }

	   :-ms-input-placeholder { /* Microsoft Edge */
	   color: #ddd;
	   }


	   .text-red{color:#E10813;}

	   .text-blue{color:#002e62;}

	   .bg-blue{background-color:#002e62;}

	   .verifiy-form ul{
		   list-style-type:none;
		   padding: 0px;
		   text-align: left;
		   /*font-weight: 600;*/
		   color: #002e62;
	   }

	   .intl-tel-input.allow-dropdown.separate-dial-code{
			width:100%;
			margin-bottom: 3%;
	   }

	   .intl-tel-input.separate-dial-code.allow-dropdown .selected-flag
	   {
		   color: #47404f;
	   }

	   @media only screen and (min-width: 320px) and (max-width: 767px){
		   /*.first-container{
		   background-image: url('images/bg-banner.png');
		   background-size: cover;
		   background-repeat: no-repeat;

		   }*/
			   .banner-section{
				   background-image: url('images/bg-banner.png');
				   background-size: cover;
				   background-repeat: no-repeat;
				   background-position: 0% 100%;
				   height:750px;
			   }
			   .registration-menu {
				   background-color: #eacccc;
				   top: 0px;
				   position: relative;
			   }
				   .advance-feature h2 {
				   color: #142550;
				   font-weight: 600;
				   padding: 25px 65px;
				   margin: 20px 0px;
			   }
			   .instant-verify {
				   background-color: #F9F9F9;
				   padding: 38px;
				   margin: 50px 0px;
			   }
			   .sec-six-heading {
				   text-align: center;
				   padding:20px;
			   }
			   .sec-one-left {
					/* margin: 0px;
					padding: 10px 0px 50px 0px; */

					position: absolute;
					top: 30px;
				}

				
			   .sec-one-left h4 {
				   color: #F63A55;
				   font-size: 25px;
				   font-weight: 600;
			   }
			   .sec-one-left h6 {
				   font-size: 13px;
				   color: #000000;
				   font-weight: 600;
				   line-height: 21px;
			   }

			   .verifiy-form {
				   padding: 10px 0px;
				   margin: 0px 20px 50px 20px;
			   }
			   .left-content{
				   padding:0px 15px!important; 
				   background-color: #002e62; 
			   /*	height:100vh;*/
				   box-shadow: 0px 0px 0px #ddd;
			   }
			   .footer-list li {
				   display: inline;
				   margin: 0px 10px;
			   }
	   }

	   

		   
</style>
<section class="banner-1">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-5 left-content">
				<h4 class="mt-5 fw-600 text-white" style="">Automate Your Background Verification Process</h4>
				<p class="text-white mt-3 text-justify">Spending all your time and resources on manually performing background checks? We have got you covered. Our 100% Automated Verification Platform enables you to perform Instant and Quality checks across employers, candidates and business partners.</p>
				<ul class="text-white mt-3 ml-4 text-justify">
					<li>Real-time tracking</li>
					<li>Instant Verification</li>
					<li>Reliable</li>
					<li>Seamless</li>
					<li>User-friendly</li>
				</ul>

				<div class="row mt-5 ">
					<div class="col-sm-6 pt-3 text-center"><i class="fa fa-stopwatch fa-3x"></i><br><br>
						<h6>Full Contactless Support</h6>
					</div>
	
					<div class="col-sm-6 pt-3 text-center"><i class="fa fa-user-cog fa-3x"></i><br><br>
						<h6>Identity-thefts </h6>
					</div>
				</div>
	
				<div class="row mt-5 ">
					<div class="col-sm-6 py-3 text-center"><i class="fa fa-hand-holding-usd fa-3x"></i><br><br>
						<h6>Cheapest Price Services</h6>
					</div>
	
					<div class="col-sm-6 py-3 text-center"><i class="fa fa-funnel-dollar fa-3x"></i><br><br>
						<h6>Profitable Business</h6>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<form method="post" action="{{route('/guest/store')}}" id="createGuestForm">
					@csrf
					<div class="verifiy-form mt-4">
						<h4 class=" text-blue my-3 fw-600">Create your <strong class="text-danger">myBCD</strong> account and start your INSTANT checks now!</h4>
						<div class="row">
							<div class="col-md-6">
								<label>First Name <span class="text-danger">*</span></label>
								<input type="text" class="custom-input error-control first_name" name="first_name" placeholder="Enter First Name" autocomplete="off">
								<span style="" class="text-danger error_container" id="error-first_name"></span>
							</div>

							<div class="col-md-6">
								<label>Last Name</label>
								<input type="text" class="custom-input error-control last_name" name="last_name" placeholder="Enter Last Name" autocomplete="off">
								<span style="" class="text-danger error_container" id="error-last_name"></span>
							</div>
						</div>

						<label>Official Email <span class="text-danger">*</span></label>
						<input type="email" class="custom-input error-control email" name="email" placeholder="Enter Official Mail id" autocomplete="off">
						<span style="" class="text-danger error_container" id="error-email"></span>
						<div class="row">
							<div class="col-md-6">
								<label>Company Name</label>
								<input type="text" class="custom-input error-control company_name" name="company_name" placeholder="Enter Company Name"  autocomplete="off">
								<span style="" class="text-danger error_container" id="error-company_name"></span>
							</div>
							<div class="col-md-6">
								<label>Your Job Title</label>
								<input type="text" class="custom-input error-control job_title" name="job_title" placeholder="Enter Your Job Title" autocomplete="off">
								<span style="" class="text-danger error_container" id="error-job_title"></span>
							</div>
						</div>
						<label>Mobile Number <span class="text-danger">*</span>  <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Please Enter Your Whatsapp Number"></i></label>
						<input type="hidden" id="code" name ="primary_phone_code" value="91">
                        <input type="hidden" id="iso" name ="primary_phone_iso" value="in">
						<input type="text" class="custom-input error-control mobile_number" id="phone1" name="mobile_number" autocomplete="off">
						<span style="" class="text-danger error_container" id="error-mobile_number"></span>
						<div class="row">
							<div class="col-md-6">
							<label>Password <span class="text-danger">*</span></label>
							<input type="password" class="custom-input error-control password" name="password" placeholder="Enter Password" autocomplete="off">
							<span style="" class="text-danger error_container" id="error-password"></span>
							</div>

							<div class="col-md-6">
							<label>Confirm Password <span class="text-danger">*</span></label>
							<input type="password" class="custom-input error-control confirm_password" name="confirm_password" placeholder="Enter Confirm Password" autocomplete="off">
							<span style="" class="text-danger error_container" id="error-confirm_password"></span>
							</div>
						</div>
						{{-- <div class="row">
							<div class="col-md-6">
								<div class="form-check form-check-inline error-control">
									<input class="form-check-input purge_check" type="checkbox" name="purge_check" id="purge_check">
									<label class="form-check-label pt-2" for="purge_check">Do You Want Purge Your Data</label>
								</div><br>
								<span style="" class="text-danger error_container" id="error-purge_check"></span>
							</div>
							<div class="col-md-6">
								<label>Purge Data <small>(in days)</small> <span class="text-danger">*</span></label>
								<input type="text" class="custom-input error-control purge_data" name="purge_data" placeholder="Ex:- 5" autocomplete="off" readonly>
								<span style="" class="text-danger error_container" id="error-purge_data"></span>
							</div>
						</div> --}}
						<ul>
							<!-- <li>Already have an account? <a href="http://app.techsaga.live/microsite/login/login.php">Sign in.</a></li> -->
							<li>
								<div class="form-check form-check-inline error-control">
									<input class="form-check-input feature" type="checkbox" name="feature" id="feature">
									<label class="form-check-label pt-1" for="feature">I agree that I am 18+ & using the above feature for my personal purpose only.</label>
								</div><br>
								<span style="" class="text-danger error_container" id="error-feature"></span>
							</li>
							<li>
								<div class="form-check form-check-inline error-control">
									<input class="form-check-input term" type="checkbox" name="term" id="term">
									<label class="form-check-label pt-1" for="term">By submitting this form, I accept myBCD <a href="{{url('/terms')}}" target="_blank">Terms of Service.</a></label>
								</div><br>
								<span style="" class="text-danger error_container" id="error-term"></span>
							</li>
							<li>
								<span style="" class="text-danger error_container" id="error-all"></span>
							</li>
						</ul>
						<button type="submit" class="verifiy-btn submit-verify"> CREATE ACCOUNT</button>
					</div>
				</form>
			</div>
		</div>	
	</div>
</section>	

<script>
	 $(function () {
        $('[data-toggle="tooltip"]').tooltip();
      });
	// $(document).ready(function(){
	// 	$('.intl-tel-input.allow-dropdown.separate-dial-code.iti-sdc-3').addClass('mb-3');
	// });

	// $(document).ready(function(){
	// 	$('.purge_check').change(function(){
	// 		var _this = $(this);
	// 		var status=_this.prop('checked') ==  true ? 1 : 0
			
	// 		if(status==1)
	// 		{
	// 			$('.purge_data').attr('readonly',false);
	// 		}
	// 		else
	// 		{
	// 			$('.purge_data').attr('readonly',true);
	// 			$('.purge_data').val('');
	// 		}
	// 	});
	// });
</script>
@endsection