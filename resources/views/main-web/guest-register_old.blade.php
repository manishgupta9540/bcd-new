@extends('layouts.app')
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
         left: 30%;mailverify
         }
     	 }*/
.verification-logo{
	max-width:150px;
	/*position: relative;
	left:0px;
	top:0px;
	z-index: 1;*/

}
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
	padding:10px 15px;
	margin:0px 20px;

}
.custom-input{
	margin:0px 0px 15px 0px;
	padding:5px;
	width:100%;
	height:auto;

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
	background-image: url('admin/images/bg-banner.png');
	background-size: cover;
	background-repeat: no-repeat;
	height: 700px;
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
    margin: 50px 0;
    padding: 50px 0;
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
	background-color: #002E62/*linear-gradient(#437FEC, #235BBE)*/;
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
@media only screen and (min-width: 320px) and (max-width: 767px){
	/*.first-container{
	background-image: url('images/bg-banner.png');
	background-size: cover;
	background-repeat: no-repeat;

}*/
.banner-section{
	background-image: url('admin/images/bg-banner.png');
	background-size: cover;
	background-repeat: no-repeat;
	background-position: 0% 100%;
	height:900px;
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
    margin: 0px;
    padding:50px 0px;
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
}

span.show-hide-password 
{
    position: absolute;
    top: 155px;
    right: 40px;
    font-size: 16px;
    color: #748a9c;
    cursor: pointer;
    z-index: 9999;
}

/*.ban-data{position: absolute;
top:80px;left:0px;}*/

     </style>
@section('content')

<section class="banner-section">
	<div class="container">
		<div class="row ban-data">
			<div class="col-md-7">
				<div class="sec-one-left">
						<h4>Automate Your Background Verification Process</h4>
						<h6>Real-time tracking and Instant Verification - Reliable, Seamless &amp; User-friendly</h6>
				</div>
			</div>
			<div class="col-md-1"></div>
			<div class="col-md-4">
				
				<div class="form-banner" id="form-banner">
					<h6>Create your <span>myBCD</span> account and start your INSTANT checks at <span>₹50</span> only!</h6>
				</div>
				<form method="post" action="{{route('/guest/store')}}" id="createGuestForm" style="margin-top:0px; box-shadow: none; padding: 15px; background: #fff;margin-bottom: 55px;">
					@csrf
					<div class="verifiy-form mx-0">							
						
						<input type="text" class="custom-input error-control full_name" name="full_name"  placeholder="Full Name*" >
						<span style="" class="text-danger error_container" id="error-full_name"></span>
						
						<input type="text" class="custom-input error-control company_name" name="company_name" placeholder="Company Name" >
						<span style="" class="text-danger error_container" id="error-company_name"></span>
						
						<input type="text" class="custom-input error-control job_title" name="job_title" placeholder="Your Job Title" >
						<span style="" class="text-danger error_container" id="error-job_title"></span>
						
						<input type="text" class="custom-input error-control mobile_number" minlength="10" maxlength="10" name="mobile_number" placeholder="Mobile Number *" >
						<span style="" class="text-danger error_container" id="error-mobile_number"></span>
						
						<input type="text" class="custom-input error-control email" name="email" placeholder="Official Email *" >
						<span style="" class="text-danger error_container" id="error-email"></span>

						<input type="password" class="custom-input error-control password" name="password" placeholder="Password *">
						{{-- <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span> --}}
						<span style="" class="text-danger error_container" id="error-password"></span>

						<input type="password" class="custom-input error-control confirm_password" name="confirm_password" placeholder="Confirm Password *">
						{{-- <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span> --}}
						<span style="" class="text-danger error_container" id="error-confirm_password"></span>

						<button type="submit" class="verifiy-btn submit-verify"> Start verification now!</button>
					</div>
				</form>
			</div>
		</div>
	</div>	
</section>


<section>
	<div class="container mt-80">
		<div class="row">
			
			<div class="col-md-6">
				<h2 class="text-blue mb-2 fw-600">Change the Game with Instant and Automated Verification</h2>
				<p class="para-custom">Spending all your time and resources on manually performing background checks? We have got you covered. Our 100% Automated Verification Platform enables you to perform Instant and Quality checks across employers, candidates and business partners.<br>

				And what more? Everything is performed in real-time with the assurance of complete transparency and 100% accuracy throughout the process. You can have a bird’s eye view and keep track of all the verification data on a Central Dashboard.<br>
				Premier Consultancy’s robust myBCD platform can help you scale, by dramatically reducing your cost incurred, through the process of manual checking</p>
			</div>
			<div class="col-md-6">
				<img src="{{ asset('admin/images/sec-two-right.png')}}" class="img-fluid wow FadeInUp">
				{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
				<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>	 --}}
			</div>
		</div>
	</div>
</section>

<section>
	<div class="container mt-80">
		<div class="row">
			<div class="col-md-6">
				<img src="{{ asset('admin/images/sec-five-right.png')}}" class="img-fluid">
			</div>
			
			<div class="col-md-6">
				<h2 class="text-blue mb-2 fw-600">Why Choose Premier Consultancy?</h2>
				<p class="para-custom">A unit of Premier Shield Group (incorporated in 1976), Premier Consultancy & Investigation is India’s leading “Risk Management & Advisory Enterprise” providing a wide range of specialized strategic business solutions to mitigate losses and maximize profits.<br>

				Premier Consultancy & Investigation conducts intelligent background verification checks which helps the recruiting firms to streamline their hiring processes and reduce their manual paperwork.<br>
				With Premier Consultancy & Investigation (PCIL), you will get access to the Hybrid Model of the operational and delivery process. Combining the power of our tech-enabled and intelligent automation platform, myBCD and 16+ years of on-field verifications experience, you are assured to get high-quality verification services.</p>


			</div>
			
		</div>
		<div class="row mt-5">
			<div class="col-md-4 text-center">
				<img src="{{ asset('admin/images/icon-one.png')}}" class="img-fluid">
				<h5 class="text-dark">NASSCOM, NSR Empanelled ISO 9001 and ISO 27001 certified.</h5>
			</div>
			<div class="col-md-4 text-center">
				<img src="{{ asset('admin/images/icon-two.png')}}" class="img-fluid">
				<h5 class="text-dark">PAN India Services.</h5>
			</div>
			<div class="col-md-4 text-center">
				<img src="{{ asset('admin/images/icon-three.png')}}" class="img-fluid">
				<h5 class="text-dark">45+ Years of Experience.</h5>
			</div>
		</div>
	</div>
</section>

<section class="instant-verify mt-80" >
	<div class="container">
		<div class="row">
			<div class="col-md-12">				
				<h3 class=" ">Are You Ready To Take Your Background Verification Process to The Next Level?</h3>
				<h4 class="text-center text-dark fw-600">Sign up now and get instant verifications done starting at ₹50 only!</h4>
				{{-- @auth
					<!--<?php $account_type =  Helper::get_user_type(Auth::user()->business_id); ?>--!>
					@if($account_type != 'guest')
						<button class="verifiy-btn " data-toggle="modal" data-target="#exampleModal">Start verification now!</button>
					@endif
				@else
					<button class="verifiy-btn " data-toggle="modal" data-target="#exampleModal">Start verification now!</button>
				@endauth --}}
			</div>
			<!-- Modal -->
			{{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
					
						<!-- <input type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span> -->
							<div class="modal-body">
								<div class="verifiy-form">
									<form method="post" action="{{route('/guest/store')}}" id="createGuestForm">
										@csrf
										<h4 class=" text-dark my-3 fw-600">Create your <strong class="text-danger">myBCD</strong> account and start your INSTANT checks at ₹50 only!</h4>
										<label><b>Full Name</b></label>
										<input type="text" class="custom-input full_name" name="full_name" >
										<span style="" class="text-danger error_container error-full_name"></span>
										<label><b>Company Name</b></label>
										<input type="text" class="custom-input"  >
										<label><b>Your Job Title</b></label>
										<input type="text" class="custom-input"  >
										<label><b>Mobile Number</b></label>
										<input type="text" class="custom-input" >
										<label><b>Official Email</b></label>
										<input type="text" class="custom-input"  >

										<button class="verifiy-btn"> Start verification now!</button>
									</form>
								</div>
							</div>
					</div>
				</div>
			</div> --}}
		</div>
	</div>
</section>

<section>
	<div class="container advance-feature mt-80">
		<div class="row">
			<div class="col-md-12">
				<h2>Advanced Features to Revolutionize Your Verification Process</h2>
			</div>
			<div class="col-md-4">
				<img src="{{ asset('admin/images/feature-one-1.png')}}" class="img-fluid">
				<h6>Centralized Dashboard</h6>
				<p>Get a real-time view of all your verification data and track the status of your applications on our integrated dashboard. Furthermore, get daily email updates on the summary of activities, conducted each day.</p>		
			</div>
			<div class="col-md-4">
				<img src="{{ asset('admin/images/feature-two.png')}}" class="img-fluid">
				<h6>Instant Checks</h6>
				<p>Serves as a holistic background verification system that checks, every detail of the identification and provides an instant detailed report.</p>		
			</div>
			<div class="col-md-4">
				<img src="{{ asset('admin/images/feature-three.png')}}" class="img-fluid">
				<h6>End to End Automation</h6>
				<p>Simplify the employee background check process with the help of our intelligent automated platform like myBCD. You can view the status of your ongoing verifications and can view/ download the report on the platform.</p>		
			</div>	
		</div>

		<div class="row">
			<div class="col-md-4">
				<img src="{{ asset('admin/images/feature-one-1.png')}}" class="img-fluid">
				<h6>Data Security & Repository</h6>
				<p>Mitigate the issues of data leakage and share information responsibly with our advanced platform. Get access to past candidate data at any point in time from our huge data repository.</p>		
			</div>
			<div class="col-md-4">
				<img src="{{ asset('admin/images/feature-two.png')}}" class="img-fluid">
				<h6>Automated Reports</h6>
				<p>Generate pre and post verification reports at your convenience with the help of our automated reporting feature. Now you don’t have to approach other stakeholders every time you need a report.</p>		
			</div>
			<div class="col-md-4">
				<img src="{{ asset('admin/images/feature-three.png')}}" class="img-fluid">
				<h6>myBCD DIY</h6>
				<p>The ‘Do-It-Yourself’ (DIY) feature helps in filling up employee data faster. As an HR, you just need to track and approve the candidate digitally to get the results online.</p>		
			</div>	
		</div>
	</div>
	
</section>



<section class=" hiring-process mt-80" >
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h2 class="text-blue mb-2 fw-600">Hiring Process Made Seamless and Easy</h2>

				<p class="para-custom">Solve all your hiring worries with our value-driven benefits:</p>

					<h5 class="fw-600 text-dark">Faster Turnaround Time</h5>
					<p class="para-custom">Save hiring time by 30% to 40% with our robust automated verification process. What used to take months, now only is a matter of a few minutes - myBCD cuts down silos and lengthy verification processes.</p>

					<h5 class="fw-600 text-dark">Achieve Accuracy & Quality</h5>
					<p class="para-custom">myBCD targets SLA’s and helps in tracking and ensuring 99.99% accuracy and data quality. Eliminates the chances of human error with automation of the verification process.</p>

					<h5 class="fw-600 text-dark">Cost-effective</h5>
					<p class="para-custom">Using the instant verifications feature in myBCD, can help you save a huge cost on Manual, Global ID checks; for multiple candidates at all levels.</p>
			</div>
			<div class="col-md-6">
				<img src="{{ asset('admin/images/sec-four-left.png')}}" class="img-fluid">
			</div>
			
		</div>
	</div>
</section>

<section>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="sec-six-heading">
				<h4>You Are In Good Hands</h4>
				<h6>We have provided timely, high-quality and result-oriented services for some of the top brands from across the globe.</h6>
			</div>
				
			</div>
			<div class="col-md-12 pb-5">
				<div class="customer-logos slider ">
					<div class="slide"><img src="{{ asset('admin/images/slide-1.png')}}"></div>
					<div class="slide"><img src="{{ asset('admin/images/slide-2.png')}}"></div>
					<div class="slide"><img src="{{ asset('admin/images/slide-3.png')}}"></div>
					<div class="slide"><img src="{{ asset('admin/images/slide-4.png')}}"></div>
					<div class="slide"><img src="{{ asset('admin/images/slide-5.png')}}"></div>
					<div class="slide"><img src="{{ asset('admin/images/slide-6.png')}}"></div>
					<div class="slide"><img src="{{ asset('admin/images/slide-7.png')}}"></div>
					<div class="slide"><img src="{{ asset('admin/images/slide-8.png')}}"></div>
					<div class="slide"><img src="{{ asset('admin/images/slide-9.png')}}"></div>
					<div class="slide"><img src="{{ asset('admin/images/slide-10.png')}}"></div>
					<div class="slide"><img src="{{ asset('admin/images/slide-11.png')}}"></div>
					<div class="slide"><img src="{{ asset('admin/images/slide-12.png')}}"></div>
					<div class="slide"><img src="{{ asset('admin/images/slide-13.png')}}"></div>
					<div class="slide"><img src="{{ asset('admin/images/slide-14.png')}}"></div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection