<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>BCD</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <!-- <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon"> -->

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('main-web/vendor/bootstrap/css/bootstrap.min.css') }}  " rel="stylesheet">
  <link href="{{ asset('main-web/vendor/icofont/icofont.min.css') }} " rel="stylesheet">
  <link href="{{ asset('main-web/vendor/boxicons/css/boxicons.min.css') }} " rel="stylesheet">
  <link href="{{ asset('main-web/vendor/remixicon/remixicon.css') }} " rel="stylesheet">
  <link href="{{ asset('main-web/vendor/venobox/venobox.css') }} " rel="stylesheet">
  <link href="{{ asset('main-web/vendor/owl.carousel/assets/owl.carousel.min.css') }} " rel="stylesheet">
  <link href="{{ asset('main-web/vendor/aos/aos.css') }} " rel="stylesheet">


  <script src="{{ asset('main-web/vendor/jquery/jquery.min.js') }} "></script>

  <!-- phone input -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/css/intlTelInput.css" rel="stylesheet" />
     <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/intlTelInput.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>

  <!-- Template Main CSS File -->
  <link href="{{ asset('main-web/css/style.css?ver=1.5') }}  " rel="stylesheet">

  <style>
    .intl-tel-input {
    position: relative;
    display: inline-block;
    width: 100%;
    }
  </style>

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top header-inner-pages">

    <div class="container d-flex align-items-center">

      <h1 class="logo mr-auto"><a href="{{ url('/') }}">  <img src="{{ asset('admin/images/logo.png') }}" /> </a></h1>
      
      <nav class="nav-menu d-none d-lg-block">
        <ul>
          <li><a href="{{ url('/') }}"> Home </a></li>
          <li><a href=""> About us </a></li>
          <li><a href=""> Products </a></li>
          <li><a href="{{ url('/pricing') }}"> Pricing </a></li>
          <li><a href=""> Contact us </a></li>
        </ul>
      </nav>
      <!-- .nav-menu -->
      @auth
            <?php $account_type =  Helper::get_user_type(Auth::user()->business_id); ?>
            @if($account_type == 'superadmin')
                <a href="{{ env('APP_SUPERADMIN_URL') }}" class="get-started-btn scrollto">My Account </a>
            @else
                <a href="{{ route('/home')}}" class="get-started-btn scrollto">My Account </a>
            @endif
      @else
          <a href="{{ env('APP_LOGIN_URL')}}" class="get-started-btn scrollto">Login</a>
          <a href="{{ route('/signup')}}" class="get-started-btn scrollto">Get Started</a>
      @endauth
    </div>
  </header>
  <!-- End Header -->

<!-- main content -->
  @yield('content')
<!-- ./main content -->

  <a href="#" class="back-to-top"><i class="ri-arrow-up-line"></i></a>
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('main-web/vendor/bootstrap/js/bootstrap.bundle.min.js') }} "></script>
  <script src="{{ asset('main-web/vendor/jquery.easing/jquery.easing.min.js') }} "></script>
  <script src="{{ asset('main-web/vendor/php-email-form/validate.js') }} "></script>
  <script src="{{ asset('main-web/vendor/waypoints/jquery.waypoints.min.js') }} "></script>
  <script src="{{ asset('main-web/vendor/isotope-layout/isotope.pkgd.min.js ') }} "></script>
  <script src="{{ asset('main-web/vendor/venobox/venobox.min.js') }} "></script>
  <script src="{{ asset('main-web/vendor/owl.carousel/owl.carousel.min.js') }} "></script>
  <script src="{{ asset('main-web/vendor/aos/aos.js') }} "></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('main-web/js/main.js') }} "></script>

<script type="text/javascript">

$("#phone1").intlTelInput({
    initialCountry: "in",
    separateDialCode: true,
    preferredCountries: ["ae", "in"],
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
//

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
//
// phone3
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
//

</script>


</body>

</html>