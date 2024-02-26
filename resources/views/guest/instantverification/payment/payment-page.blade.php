<!-- // Let's Click this button automatically when this page load using javascript -->
<!-- You can hide this button -->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'BCD System') }}</title>
        <link rel="shortcut icon" type="image/x-icon" href="{{url('/').'/admin/images/BCD-favicon.png'}}">
		{{-- <script src="{{asset('guest/js/jquery-3.3.1.min.js')}}"></script> --}}
        <style>
     /* Start by setting display:none to make this hidden.
   Then we position it in relation to the viewport window
   with position:fixed. Width, height, top and left speak
   for themselves. Background we set to 80% white with
   our animation centered, and no-repeating */

/* #bcd_loading {
    position: fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 )  
                url(/loader/loaderblue.gif) 
                  50% 50%
                  no-repeat;

} */

.cssload-loader {
	width: 344px;
	height: 69px;
	line-height: 69px;
	text-align: center;
    position: fixed;
    z-index:    1000;
    top: 50% !important;
	left: 50% !important;
	transform: translate(-50%, -50%) !important;
		-o-transform: translate(-50%, -50%);
		-ms-transform: translate(-50%, -50%);
		-webkit-transform: translate(-50%, -50%);
		-moz-transform: translate(-50%, -50%);
	font-family: helvetica, arial, sans-serif;
	text-transform: uppercase;
	font-weight: 900;
	font-size:32px;
	color: rgb(18,48,110);
	letter-spacing: 0.2em;
}
.cssload-loader::before, .cssload-loader::after {
	content: "";
	display: block;
	width: 21px;
	height: 21px;
	background: rgb(46,72,125);
	position: absolute;
	animation: cssload-load 0.7s infinite alternate ease-in-out;
		-o-animation: cssload-load 0.7s infinite alternate ease-in-out;
		-ms-animation: cssload-load 0.7s infinite alternate ease-in-out;
		-webkit-animation: cssload-load 0.7s infinite alternate ease-in-out;
		-moz-animation: cssload-load 0.7s infinite alternate ease-in-out;
}
.cssload-loader::before {
	top: 0;
}
.cssload-loader::after {
	bottom: 0;
}



@keyframes cssload-load {
	0% {
		left: 0;
		height: 41px;
		width: 21px;
	}
	50% {
		height: 11px;
		width: 55px;
	}
	100% {
		left: 323px;
		height: 41px;
		width: 21px;
	}
}

@-o-keyframes cssload-load {
	0% {
		left: 0;
		height: 41px;
		width: 21px;
	}
	50% {
		height: 11px;
		width: 55px;
	}
	100% {
		left: 323px;
		height: 41px;
		width: 21px;
	}
}

@-ms-keyframes cssload-load {
	0% {
		left: 0;
		height: 41px;
		width: 21px;
	}
	50% {
		height: 11px;
		width: 55px;
	}
	100% {
		left: 323px;
		height: 41px;
		width: 21px;
	}
}

@-webkit-keyframes cssload-load {
	0% {
		left: 0;
		height: 41px;
		width: 21px;
	}
	50% {
		height: 11px;
		width: 55px;
	}
	100% {
		left: 323px;
		height: 41px;
		width: 21px;
	}
}

@-moz-keyframes cssload-load {
	0% {
		left: 0;
		height: 41px;
		width: 21px;
	}
	50% {
		height: 11px;
		width: 55px;
	}
	100% {
		left: 323px;
		height: 41px;
		width: 21px;
	}
}
.display-none
{
    display: none;
}
.display-block
{
    display: block;
}

</style>
    </head>
<body>
<button id="rzp-button1" hidden>Pay</button>  
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "{{$response->razorpay_id}}", // Enter the Key ID generated from the Dashboard
    "amount": "{{$response->total_price * 100}}", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency": "{{$response->currency}}",
    "name": "{{$response->name}}",
    "description": "Payment for Instant Verification",
    //"image": "https://example.com/your_logo", // You can give your logo url
	// "image": "{{asset('admin/images/BCD-Logo.png')}}",
    "order_id": "{{$response->transaction_id}}", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
    "handler": function (response){
        // After payment successfully made response will come here
        // send this response to Controller for update the payment response
        // Create a form for send this data
        // Set the data in form
        document.getElementById('rzp_paymentid').value = response.razorpay_payment_id;
        document.getElementById('rzp_orderid').value = response.razorpay_order_id;
        document.getElementById('rzp_signature').value = response.razorpay_signature;
        
        document.getElementById('cssload-loader').classList.remove('display-none');
        document.getElementById('cssload-loader').classList.add('display-block');
        // // Let's submit the form automatically
        document.getElementById('rzp-paymentresponse').click();
    },
    "prefill": {
        "name": "{{$response->name}}",
        "email": "{{$response->email}}",
        "contact": "{{$response->contactNumber}}"
    },
    "theme": {
        "color": "#003473"
    },
	"modal": {
        "ondismiss": function(){
			var guest_master_id = "{{Crypt::encryptString($response->id)}}";
			var url = "{{url('/verify/')}}"+"/instant_verification/checkout/"+guest_master_id;
            window.location = url;
        }
    },
	// "method": {
	// "netbanking": "1",
	// "card": "1",
	// "upi": "1",
	// "wallet": "0",
	// }
};
var rzp1 = new Razorpay(options);
window.onload = function(){
    document.getElementById('rzp-button1').click();
	
};

document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();

}
</script>

<!-- This form is hidden -->
<div class="display-none cssload-loader" id="cssload-loader">Loading...</div>
<form action="{{url('/verify/instant_verification/payment-complete')}}" method="POST" hidden>
    <input type="hidden" value="{{csrf_token()}}" name="_token" /> 
    <input type="hidden" value="{{base64_encode($response->id)}}" name="guest_master_id">
    <input type="text" class="form-control" id="rzp_paymentid"  name="rzp_paymentid">
    <input type="text" class="form-control" id="rzp_orderid" name="rzp_orderid">
    <input type="text" class="form-control" id="rzp_signature" name="rzp_signature">
    <button type="submit" id="rzp-paymentresponse" class="btn btn-primary">Submit</button>
</form>
</body>
</html>