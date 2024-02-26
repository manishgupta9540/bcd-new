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
			.custom-input{
				margin:0px 0px 15px 0px;
				padding:5px;
				width:100%;
				height:auto;

				background-color: transparent;
				border: 1px solid #fff;
				box-shadow: 0px 0px 5px #C4C3C3;

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

			/*.ban-data{position: absolute;
			top:80px;left:0px;}*/

			/*DESIGN-1*/
			.banner-1{
				background-image: url('images/background-image-grey.png');
				background-size: cover;
				background-repeat: no-repeat;
				background-position: 0% 100%;
				height:100vh;
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
     </style>
	


<section>
	<div class="container-fluid">
		<div class="row ">
			<div class="col-md-12 text-center">
				<h2 class="fw-600 pb-5 text-blue" style=" margin-top: 30px;">Terms and Conditions</h2>
			</div>
			<div class="col-md-8 offset-md-2">
				
				<h5 class="my-3 text-dark fw-600">Use of the Website:</h5>
				<p class="text-dark"> This website includes all information, tools and services available and as a user,conditioned upon your acceptance of all terms, conditions, policies and notices stated here.</p>

				<p class="text-dark">By using www.bcd.com ,you agree to be bound by the terms and conditions set out below. The content and  material listed on this site is protected by copyright law throughout the world and is fully owned by BCD.You are denied to commercialise or copy it without our consent. Please read these Terms of Services carefully before accessing or using our website
				If you do not agree to be bound by these terms and conditions, access to the website is fully denied.</p>


				<h5 class="my-3 text-dark fw-600">CONTACT INFORMATION</h5>
				
				<p class="text-dark">If you have any queries related to these terms and conditions, please feel free to contact our customer services team at info@bcd.com.</p>

				<h3 class="text-center">OVERVIEW</h3>

				<h5 class="my-3 text-dark fw-600"><i>All rights reserved</i></h5>
				<p class="text-dark">By visiting our site and/ or purchasing something from us, you engage in our “Service” and agree to be bound by the following terms and conditions (“Terms of Service”).These Terms of Service apply to all users of the site, including without limitation users who are browsers, vendors, customers, merchants, and/ or contributors of content.</p>

				<h5 class="my-3 text-dark fw-600">Registration</h5>

				<p class="text-dark">As a customer, we expect all pieces of the information that you provide is true,updated and accurate and up to date. For any amendment in the information provided,please edit the relevant details in the 'My Account' section of the website.</p>
				<p class="text-dark">(If you have any problems doing this, please email customer services at info@bcd.com)</p>

				<ul class="text-dark">
					<li>You must not impersonate any other person and are prohibited to provide any false information on the website. You must not use the website in any way which will cause, or is likely to cause, the website to be damaged or interrupted in any way.You are responsible for the confidentiality and maintenance of your personal account information and password. BCD shall not be liable to any person for any loss or damages as a failure by you to protect your password or account details.</li>
					<li>You accept responsibility for all activities which occur under your account and password. It is important that you keep your personal details and password confidential and secure. If you suspect any fishy activity in your account or your password is stolen by someone and used without your permission, you should promptly inform us.</li>
					<li>BCD store reserves all the right to refuse access to the website, terminate personal accounts, amend and remove content and cancel orders,if any above information or any misleading action is found(at no cost to you).We reserve the right to terminate your use of the Service or any related website for violating any of the prohibited uses.</li>
				</ul>
				
				<h5 class="my-3 text-dark fw-600">Product Availability </h5>
				<p class="text-dark">All products for sale on BCD Store are subject to availability; if for any reason your order cannot be fulfilled owing to unavailability or out of stock, we will be sending you the email informing you the same.We may, in our sole discretion, limit or cancel quantities purchased per person, per household or per order.We reserve the right to discontinue any product at any time. Any offer for any product or service made on this site is void where prohibited.
				</p>

				<h5 class="my-3 text-dark fw-600">Product Prices </h5>
				<p class="text-dark">All prices include VAT and are subject to change without prior notice,, at the sole discretion of us.We shall not be liable to you or to any third-party for any modification, price change, suspension or discontinuance of the Service.</p>

				<h5 class="my-3 text-dark fw-600">Product Description</h5>
				<p class="text-dark">Every product is sold subject to the product description and supports all the required information such as size, colour, estimated delivery dates and warranty period. 
				[NOTE: All sizes are in U.A.E standard sizes (please see our Size Guide)]
				</p>
 				<h5 class="my-3 text-dark fw-600">Product Images</h5>
				<p class="text-dark">Images are for graphical representation only.We make every effort to ensure that the product colour is as accurate as possible, BUT Colors may vary in accordance with the quality of your computer/mobile screen. The products you receive may differ slightly from the provided images on the website.</p>

				<h5 class="my-3 text-dark fw-600">Product Measurements</h5>
				<p class="text-dark">If the body measurements provided by you are not adjusted during production, then the measurements of your new kandora should be the same as your original kandora. However, we do not guarantee that the seams and the design will be 100% identical to the original kandora. Also 1.5 cm or 0.5 inches of measurement should be allowed in adjustments on the finished product.</p>

				<p class="text-dark">If the measurements or design of your custom made product does not correspond with the order details or, for any reason not mentioned above, we will offer you a new product free of charge or your money back. </p>
				<p class="text-dark">And in this case, we of course take responsibility for the cost of return shipments, under the condition that you send with the cheapest shipping method possible. </p>

				<h5 class="my-3 text-dark fw-600">Product Delivery</h5>
				<p class="text-dark">Please note that the delivery times are only estimates, and subjected to uncertainty.We reserve the right to refuse orders where the product information, price or product promotion has been incorrectly published. All Made to Measure Kandoras are non-refundable and non-exchangeable.</p>

				<h5 class="my-3 text-dark fw-600">Modifications in Terms and services </h5>
				<p class="text-dark">We reserve the right, at our sole discretion, to update, change or replace any part of these Terms of Service by posting updates and changes to our website. Any new point added to the current store shall also be subject to the Terms of Service. You can review the most current version of the Terms of Service at any time on this page. We fully own this site and reserve the rights to update, change or replace any part of these Terms of Service by posting updates and/or changes to our website.  It is duly your responsibility to check this page regularly to see new or altered points. Your continued use of or access to the website following the posting of any changes constitutes acceptance of those changes.</p>

				<h6>A breach or violation of any of the Terms will result in an immediate termination of your Services.</h6>

				<h5 class="my-3 text-dark fw-600">General Conditions</h5>
				<p class="text-dark">We reserve the right to refuse service to anyone for any reason at any time.
				You understand that your content (not including credit card information), may be transferred unencrypted and involve (a) transmissions over various networks; and (b) changes to conform and adapt to technical requirements of connecting networks or devices. Credit card information is always encrypted during transfer over networks.
				</p>
				<p class="text-dark">You agree not to reproduce, duplicate, copy, sell, resell or exploit any portion of the Service, use of the Service, or access to the Service or any contact on the website through which the service is provided, without express written permission by us.</p>
				<h5 class="my-3 text-dark fw-600">Third Party Links</h5>
				<p class="text-dark">Certain content, products and services available via our Service may include materials from third-parties that are not affiliated with us. We are not responsible for examining or evaluating the content or accuracy and we do not warrant and will not have any liability or responsibility for any third-party materials or websites, or for any other materials, products, or services of third-parties.</p>
				<p class="text-dark">Also,we are not liable for any harm or damages related to the purchase or use of goods, services, resources, content, or any other transactions made in connection with any third-party websites. Please review carefully the third-party's policies and practices and make sure you understand them before you engage in any transaction. Complaints, claims, concerns, or questions regarding third-party products should be directed to the third-party.</p>

				<h5 class="my-3 text-dark fw-600">Inaccuracy or Error</h5>
				<p class="text-dark">Occasionally there may be information in the Service section that may contain typographical errors, inaccuracies or omissions that may relate to product descriptions, pricing, promotions, offers, product shipping charges, transit times and availability. We reserve the right to rectify them and can change or update information.</p>
				<h5 class="my-3 text-dark fw-600">Indemnification </h5>
				<p class="text-dark">You agree to indemnify, defend and hold harmless BCD store and our parent, subsidiaries, affiliates, partners, officers, directors, agents, contractors, licensors, service providers, subcontractors, suppliers, interns and employees, harmless from any claim or demand, including reasonable attorneys’ fees, made by any third-party due to or arising out of your breach of these Terms of Service or the documents they incorporate by reference, or your violation of any law or the rights of a third-party.</p>

				<h5 class="my-3 text-dark fw-600">Termination</h5>
				<p class="text-dark">The obligations and liabilities of the parties incurred prior to the termination date shall survive the termination of this agreement for all purposes.These Terms of Service are effective unless and until terminated by either you or us. You may terminate these Terms of Service at any time by notifying us that you no longer wish to use our Services, or when you cease using our site.
				If in our sole judgment you have failed, to comply with any term or provision of these Terms of Service, we also may terminate this agreement at any time without notice and you will remain liable for all amounts due up to and including the date of termination; and/or accordingly may deny you access to our Services (or any part thereof).
				</p>
				<p class="text-dark">The failure of us to exercise or enforce any right or provision of these Terms of Service shall not constitute a waiver of such right or provision.
				These Terms of Service and any policies or operating rules posted by us on this site or in respect to The Service constitutes the entire agreement and understanding between you and us and govern your use of the Service, superseding any prior or contemporaneous agreements, communications and proposals, whether oral or written, between you and us (including, but not limited to, any prior versions of the Terms of Service).
				Any ambiguities in the interpretation of these Terms of Service shall not be construed against the drafting party.
				</p>

				<h5 class="my-3 text-dark fw-600">Warrant Disclaimer</h5>
				<p class="text-dark">We do not guarantee, represent or warrant that your use of our service will be uninterrupted, timely, secure or error-free.We do not warrant that the results that may be obtained from the use of the service will be accurate or reliable.</p>
				<p class="text-dark">In no case shall BCD, our directors, officers, employees, affiliates, agents, contractors, interns, suppliers, service providers or licensors be liable for any injury, loss, claim, or any direct, indirect, incidental, punitive, special, or consequential damages of any kind, including, without limitation lost profits, lost revenue, lost savings, loss of data, replacement costs, or any similar damages, whether based in contract, tort (including negligence), strict liability or otherwise, arising from your use of any of the service or any products procured using the service, or for any other claim related in any way to your use of the service or any product, including, but not limited to, any errors or omissions in any content, or any loss or damage of any kind incurred as a result of the use of the service or any content (or product) posted, transmitted, or otherwise made available via the service, even if advised of their possibility. </p>
				<p class="text-dark">Because some states or jurisdictions do not allow the exclusion or the limitation of liability for consequential or incidental damages, in such states or jurisdictions, our liability shall be limited to the maximum extent permitted by law.</p>

				<h5 class="my-3 text-dark fw-600">Obligation to Law</h5>
				<p class="text-dark">BCD will NOT sell its  products to any OFAC sanctioned countries in compliance with the UAE laws.The United Arab Emirates is our company's country of domicile. In case of any disputes the law applicable would be based on UAE Governing Law.</p>
				<p class="text-dark">These Terms of Service and any separate agreements whereby we provide you Services shall be governed by and construed in accordance with the laws of the United Arab Emirates.</p>

			</div>
			
			
		</div>
		
	</div>
	
</section>

@endsection