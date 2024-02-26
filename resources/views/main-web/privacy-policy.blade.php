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
				<h2 class="fw-600 pb-5 text-blue" style="margin-top: 30px;">Privacy Policy</h2>
			</div>
			<div class="col-md-8 offset-md-2">
					
				<h4 class="text-dark my-3">PRIVACY POLICY</h4>

				<p class="text-dark"> This Privacy Policy is in place to help you comprehend the information we collect from you, how we collect it, handle it, and how you can update, manage, export and also delete your information. This Privacy Policy applies to all the services offered by premier-consultancy.com. The application versions of Netless collectively known as “(Platform)”. This Privacy Policy applies to all Users (“Users”) of the Platform. Our range of services includes reading of a QR code and displaying information stored on the QR code. Our services can be used in various ways which allow you to choose how to deal with your personal information available with us, and also allows you to have more control over your own privacy. </p>

				<h5 class="text-dark my-3">INFORMATION WE COLLECT FROM YOU</h5>
				
				<p class="text-dark"> In order to provide our services, we collect various unique identifiers pertaining to you. Personal Information is information that you provide to us which personally identifies you, such as your name, email address, or other data that can be reasonably linked to such information by us, to associate with your profile with us. Information which you provide to us: When you create an account with us, you provide us with personal information that includes your name and a password, for the provisioning of an account and access to it. We also collect any further information pertaining to contact details including your contact number, e-mail address, name, location, profile image on the social media platforms of Facebook, Twitter, Google+ where you make such connection to our Platform. Information we collect as you use our services: In order to make our services more curated, we store the information we collect from you along with the personal information, as retrieved from your user, when you are accessing our Platform. The information we collect from you bear a lot many unique identifiers, including browser type settings, operating system, mobile network information including the carrier details and the phone number, device type settings. We also collect information about the interaction of your browsers, apps, and devices with our services, including IP address, crash reports, system activity, and the date, time, referrer URL of your request. We collect this information, when you have logged onto, or browse through our Platform. We collect information about your activity in our services, which we use to do things like recommend you videos you may like.</p>

				<h5 class="text-dark my-3">WHY WE COLLECT YOUR INFORMATION?</h5>
				
				<p class="text-dark">We collect and use your information in the following ways: ·</p>

                <p class="text-dark">Provision of our services.</p>
                    
                <ul style="font-family: 'Muli-SemiBold';">
                    <li class="text-dark">Maintain and improve our own services: we use your information to understand how efficiently our services are working for you. By tracking outages, troubleshooting issues, which are reported by you, we aim at providing better maintenance and upgrades and where necessary. This is aimed at making your experience on our Platform smoother.</li>
                    <li class="text-dark">Upgrades: by understanding your pattern in using various features of the Platform, we are able to understand better what can be bettered on our Platform, and accordingly provide with upgrades.</li>
                    <li class="text-dark">Personalized services, including various content and ads.</li>
                    <li class="text-dark">Data Analytics: we use data analytics and other techniques to understand how our services are utilized. By checking the number of visits to our Platform, we work around the design of the Platform. We also use such data to help advertisers understand the effect of their advertisement campaigns.</li>
                    <li class="text-dark">Communication: we use the information we collect from you, to interact with you. For sending necessary notifications, including provision of notifications related to any suspicious activity, we need to reach out to you directly. In order to notify any changes in our products, services, or policies, we would like to let you know in time. Where you reach out to us with any service requests, we also keep a record of such interactions in order to serve you better later. We will always ask for your consent, before we proceed with using your information for a purpose that has not been covered by this Privacy Policy.</li>
                </ul>

				<h5 class="text-dark my-3">PRIVACY CONTROLS</h5>
				
				<p class="text-dark">We want you to be aware of the controls you have of your information. This section will help you understand better how to manage your privacy across our services. You can also check with our Privacy Settings, which provides you with an opportunity to review and adjust important privacy features.</p>

                <ul style="font-family: 'Muli-SemiBold';">
                    <li class="text-dark">When you are signed into our Platform, you can access and review your information at all times.</li>
                    <li class="text-dark">Our Privacy controls have the following features: o Activity Controls: you can choose what you share with us, and you what you want to be customized for you. o Ad settings: you can choose what your interests are, and whether your personal information is being used to make your ads more relevant for you. You can also choose how to manage certain advertising services. o About you features: you can control what information shared by you, is public, and visible to others. o Sharing of information: you can also control whom you share your information with vide social media platforms.</li>
                    <li class="text-dark">Ways to review your information: You can edit your information as available with you, where you have provided such information to us directly, by visiting our Settings. Exporting, removing and deleting your information You will also be able to remove content from specific services, based on the applicable laws. To delete your information you can: Visit Settings menu and tap ‘Delete my account’ option SHARING YOUR INFORMATION You can share your information across various platforms, and you always have the control over what you share, and how you share. We do not share your information outside of premier-consultancy.com, with companies, organizations, or individuals, except in the following conditions: – With your consent: we will share your personal information outside of premier-consultancy.com only when we have your consent. We will always for your explicit consent to share any sensitive personal information. Sensitive personal information is a specific category of personal information relating to topics such as confidential medical history, racial or ethnic origins, political or religious beliefs, or sexuality. – For External Processing: we provide personal information, to our Affiliates, and other trusted businesses or persons, to process it for us, but such is carried out only basis our instructions, and in compliance with our Privacy Policy and other relevant confidentiality and security measures. For example, we take help from service providers for customer support. – For legal reasons: we will share personal information outside premier-consultancy.com only if we believe in good faith that such access, use, preservation, or disclosure of information is absolutely necessary to: o Meet any applicable law, regulation or legal process, or enforceable government request. o Enforce applicable Terms of Service, including potential investigation of any possible violations. o Detect, prevent, or otherwise, address fraud, security, or technical issues. o Protect against harm to the rights, property or safety of premier-consultancy.com, our users, or the public as required or permitted by law. – We may share non-personally identifiable information publicly with our partners. We also allow certain partners to collect information from your browser, or device for advertising and measurement purposes, using their own cookies, or similar technologies. – If premier-consultancy.com is involved in a merger, acquisition, or sale of assets, we will strive to continue to ensure the confidentiality of your personal information, and give affected users notice before personal information is transferred or becomes subject to a different Privacy Policy.</li>
                </ul>

				<h5 class="text-dark my-3">SECURITY OF YOUR INFORMATION</h5>
				
				<p class="text-dark">The Netless QR Android App is built with strong security features that work continuously towards protecting your information. The insights we gain from maintaining our services help us detect and automatically block security threats from reaching you. And if and when we detect something risky, and where we think you should be made aware of that, we will notify you and guide you through the process to stay better protected. – We use encryption to keep your data private while in transit; – We review our information collection, storage, and processing activities, including physical security measures, to prevent unauthorized access to our systems; – We restrict access to personal information to premier-consultancy.com employees, contractors, and agents, who need that information in order to process it. Anyone with this access right is subject to strict contractual confidentiality obligations and may be disciplined, or terminated if they fail to meet these obligations. DATA TRANSFERS We maintain servers around the world, and your information may be processed on servers located outside of your country, where you reside. Data protection laws vary across countries, with some providing stricter protection than the others. Regardless of where your information is being processed from, we employ the same protections described in this Policy. When we receive formal complaints, we respond by contacting the person directly who made the complaint.</p>

                <h5 class="text-dark my-3">APPLICATION OF THIS POLICY</h5>

                <p class="text-dark">This Privacy Policy applies to all the services offered by premier-consultancy.com. This Privacy Policy does not apply to services that have separate Privacy Policies in place, and that do not incorporate this Privacy Policy. However, this Privacy Policy does not apply to, – The information practices of other companies and organizations that advertise our services; – Services offered by other companies or individuals, including products or sites that may include premier-consultancy.com services, be displayed to you in search results, or be linked from our services. However, this Privacy Policy does not apply to, – The information practices of other companies and organizations that advertise our services; – Services offered by other companies or individuals, including products or sites that may include premier-consultancy.com services, be displayed to you in search results, or be linked from our services. CHANGES TO THIS POLICY We change this Privacy Policy from time to time. We will not reduce your rights as under this Policy without your explicit consent, unless required to. We always indicate the date that the last changes were published. If changes are significant, we will share more prominent notices, (including an e-mail notification) for any Privacy Policy change.</p>

                <h5 class="text-dark my-3">PRIVACY CONTACT</h5>

                <p class="text-dark">Please reach out to:</p>

                <p class="text-dark">sales@premier-consultancy.com</p>

                <p class="text-dark">In case of any queries pertaining to our Privacy Policy and Privacy Practices.</p>

			</div>
			
			
		</div>
		
	</div>
	
</section>	

@endsection
		
		