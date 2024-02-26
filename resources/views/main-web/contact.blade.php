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

	.custom-input{
		margin:0px 0px 10px 0px;
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
	/*background-image: url('images/verification_banner_no_text.jpg');
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
		background-image: url('admin/images/Contact-us-ban.png');
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
	.contact-form {
		/*background:rgba(0, 46, 98, 0.55);*/
		color: #585858;
		/* text-align: center; */
		padding: 50px 10px;
	}

	.contact-form h4{
		text-align: left;
		color: #171C3A;
		font-size: 30px;
		font-weight: 600;
		margin-bottom: 35px;
	}
	.contact-form label{
		text-align: left;
		font-weight: 600;
		margin-bottom: 5px !important;
	}
	.contact-form textarea{width: 100%;}
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
		padding:50px 10px 0px 10px;
	}
	.sec-six-heading h4 {
		text-align:left;
		color: #171C3A;
		font-size: 30px;
		font-weight: 600;
		margin-bottom: 35px;
	}
	.sec-six-heading h6 {
		font-size: 18px;
		color: #447C8D;
		line-height: 24px;
	}
	.contact-ways{
		list-style-type: none;
		padding: 0px;
	}
	.contact-ways li {
		color: #002E62;
		margin: 40px 0px;
		font-weight: 600;
		display: flex;
		justify-content: start;
	}
	.contact-ways li .zmdi{
		color: #ff0000;
		font-size: 25px;
		margin-right: 10px;	

	}
	.btn-opacity
	{
		opacity: .65;
	}
	@media only screen and (min-width: 320px) and (max-width: 767px){
		/*.first-container{
		background-image: url('images/Contact-us-ban.png');
		background-size: cover;
		background-repeat: no-repeat;
		background-position: 0% 100%;
		height: 300px;
	}*/

	.banner-section{
		background-image: url('admin/images/Contact-us-ban.png');
		background-size: cover;
		background-repeat: no-repeat;
		background-position: 0% 100%;
		height:130px;
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
	.sec-one-left h4 {
		color: #F63A55;
		font-size: 25px;
		font-weight: 600;
	}
	.sec-one-left h6 {
		font-size: 13px;
		color: #000000;
		font-weight: 600;
		line-height: 23px;
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


}

/*.ban-data{position: absolute;
top:80px;left:0px;}*/

     </style>

<section class="banner-section contact-banner">
	<div class="container">
		<div class="row ban-data">
			<div class="col-md-7">
				<div class="sec-one-left">
                        <h4>Get in Touch</h4>
                        <h6>We are always available for your help, for better experience you can connect with us</h6>
                </div>
			</div>	
		</div>
		
	</div>
</section>


<section class="contact-us">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="sec-six-heading">
                <h4>Contact us</h4>
                <ul class="contact-ways">
                	<li><i class="zmdi zmdi-pin"></i> <span> &nbsp;W-77, Lane No. 10, Mandir Marg, Anupam Garden, Near Sainik Farms, New Delhi - 110068 | INDIA</span></li>
                	<li><i class="zmdi zmdi-phone"> </i> <span> +91 73034 95236, +91 73032 22239</span></li>
                	<li><i class="zmdi zmdi-email"> </i> <span> info@my-bcd.com</span></li>

                	<li><i class="zmdi zmdi-globe"> </i> <span> www.my-bcd.com</span></li>
                </ul>
            	</div>
            	{{-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3503.566955655392!2d77.31226121455852!3d28.582763693083614!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce50f14b09d5d%3A0x2fdc0c8df470e62a!2sTechsaga%20Corporations!5e0!3m2!1sen!2sin!4v1622452088387!5m2!1sen!2sin" style="border:0; width:100%; height:300px;" allowfullscreen="" loading="lazy"></iframe> --}}
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d224488.27113415676!2d77.01320381640626!3d28.46059099999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d1e5ca273ffeb%3A0x6f0afce68233f326!2sPremier%20Consultancy%20%26%20Investigation!5e0!3m2!1sen!2sin!4v1626770526245!5m2!1sen!2sin" style="border:0; width:100%; height:300px;" allowfullscreen="" loading="lazy"></iframe>
			</div>
			<div class="col-md-6">

				<form method="post" action="{{route('/contactstore')}}" id="contact_form" class="contact-form">
					@csrf
					<h4>Connect with us</h4>
					<div>
						<label>Name <span class="text-danger">*</span></label>
						<input type="text" name="name" class="custom-input error_control name">
						<p style="margin-bottom:2px;" class="text-danger error_container" id="error-name"></p>

						<label>Mobile <span class="text-danger">*</span></label>
						<input tel="text" name="mobile" class="custom-input error_control mobile">
						<p style="margin-bottom:2px;" class="text-danger error_container" id="error-mobile"></p>

						<label>Email <span class="text-danger">*</span></label>
						<input type="email" name="email" class="custom-input error_control email">
						<p style="margin-bottom:2px;" class="text-danger error_container" id="error-email"></p>

						<label>Subject <span class="text-danger">*</span></label>
						<input type="text" style="" name="subject" class="custom-input error_control subject">
						<p style="margin-bottom:2px;" class="text-danger error_container" id="error-subject"></p>

						<label>Message <span class="text-danger">*</span></label>
						<textarea name="message" class="message" rows="10" cols="10"></textarea>
						<p style="margin-bottom:2px;" class="text-danger error_container" id="error-message"></p>

						<script src="https://www.google.com/recaptcha/api.js" async defer></script>
    					<div class="g-recaptcha" id="feedback-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY')  }}"></div>
						<p style="margin-bottom:2px;" class="text-danger error_container" id="error-g-recaptcha-response"></p>

						<button type="submit" class="verifiy-btn submit-contact">Submit</button>
					</div>
				</form>
			</div>
					
		</div>
	</div>
</section>
@endsection