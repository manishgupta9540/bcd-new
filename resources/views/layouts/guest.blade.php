<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'BCD System') }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{url('/').'/admin/images/BCD-favicon.png'}}">
    
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

  
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet" />
    <link href="{{ asset('guest/css/fonts/font-awesome-all.css') }}" rel="stylesheet" />
    <link href="{{ asset('guest/css/perfect-scrollbar.min.css?ver=1.0') }}" rel="stylesheet" />
    <link href="{{ asset('guest/css/guest.css?ver=1.6')}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/css/intlTelInput.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/microsite/css/style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>

    {{-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css"> --}}

    {{-- <script src="{{asset('guest/js/jquery-3.3.1.min.js')}}"></script> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{asset('guest/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('guest/js/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('guest/js/script.min.js?ver=0.1')}}"></script>
    <script src="{{asset('guest/js/sidebar.large.script.min.js?ver=0.1')}}"></script>
    <script src="{{asset('guest/js/echarts.min.js')}}"></script>
    <script src="{{asset('guest/js/echart.options.min.js')}}"></script>
    <script src="{{asset('guest/js/dashboard.v1.script.min.js')}}"></script>
   
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/intlTelInput.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

     {{-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script> --}}
     
     <script type="text/javascript">
        var loaderPath = "{{asset('admin/images/preload.gif')}}"; 
     </script>

     <style>
        .verification-side-bar .child
        {
            font-size: 15px;
        }
        .id-det-block img
        {
            width:70px !important;
        }
        .verifi-side.active a
        {
            color:#fff !important;
        }
        .idcard
        {
            font-size:30px;
        }
        .headwelcome
        {
            font-size: 20px;
            margin-top: 5px;
        }
        .headbtn
        {
            font-size: 14px;
            margin-top: 5px;
        }
        p
        {
            margin-top:0px;
            margin-bottom:0px;
        }
        @media only screen and (max-width: 768px) {

            .verification-1-button
            {
                margin-top:20px;
            }
            .totalprice button {
                width: 100px !important;
            }
            .verfication-top-tab
            {
                width: 90px;
            }
           
            .sidebar
            {
                display: flex;
                padding: 20px;
            }
        }

        /* .badge-light {
            border-radius: 100%;
            width: 24px;
            height: 23px;
            padding: 6px;
            color: #ffffff;
            background-color: #e23f23;
            position: relative;
            top: -22px;
            left: -19px;
            font-size: 14px;
        } */

        .updatebtn
   {
      color: #fff;
      background-color: #003473;
      border-color: #003473;
   }
   .updatebtn:hover
   {
      color: #fff;
      background-color: #e10813;
      border-color: #e10813;
   }
   .updatebtndiv
   {
      float: right;
      margin-right: 15px;
      margin-top: 15px;
   }
   .profile-block
   {
      background-color: #fff;
      padding: 20px;
      margin-top: 35px;
      border-radius:5px;
   }

   .content-container .aside-nav {
        position: static !important;
        padding-bottom: 10px;
        z-index: 0;
        min-height: auto !important;
     
    }
    .aside-nav{
        padding-bottom: 0px !important;
    }

    ul.breadcrumb {
            padding: 9px 0px;
            margin: 0px;
            list-style: none;
            background-color: transparent;
        }
        .breadcrumb {
            background: transparent;
                background-color: transparent;
            align-items: center;
            margin: 0 0 1.25rem;
            padding: 0;
        }
        .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            list-style: none;
            background-color: #eee;
            border-radius: 0.25rem;
        }
        ul.breadcrumb li {
            display: inline;
            font-size: 14px;
            font-weight: 800;
        }
        ul.breadcrumb li a {
            color: #002e62;
            text-decoration: none;
            font-weight: 800;
        }
        ul.breadcrumb li {
            font-size: 14px;
            font-weight: 800;
        }
        ul.breadcrumb {
            list-style: none;
        }
        ul.breadcrumb li + li::before {
            padding: 8px;
            color: black;
            content: "\f054";
            font-family: "Font Awesome 5 Free";
        }

        .content-container .aside-nav {
            width: 90%;
            position: absolute;
            top: 0;
            bottom: 0;
            border-left: 1px solid hsla(0,0%,64%,.2);
            border-right: 1px solid hsla(0,0%,64%,.2);
            background-color: #f8f9fa;
            padding-top: 0px;
            padding-bottom: 10px;
            z-index: 0;
            min-height: 100vh;
        }
        .content-container .aside-nav ul {
            overflow-y: auto;
            height: 90%;
            list-style: none;
            margin: 0;
            padding: 0;
        }  
        .content-container .aside-nav ul li {
            border-bottom: 1px solid #ddd;
        }

        .content-container .aside-nav a {
            padding: 15px;
            display: block;
            color: #000311;
        }
        .content-container .aside-nav .active a {
            background: #fff;
            border-top: 1px solid hsla(0,0%,64%,.2);
            border-bottom: 1px solid hsla(0,0%,64%,.2);
        }

        .content-container .aside-nav .active i {
            float: right;
            font-size: 18px;
        }

        .pb-border{border-bottom: 1px solid #ddd; padding-top: 0px;padding-bottom: 6px;}

        .faq {
          background: #FFFFFF;
          box-shadow: 0 2px 48px 0 rgba(0, 0, 0, 0.06);
          border-radius: 4px;
        }

        .faq .card {
          border: none;
          background: none;
          border-bottom: 1px dashed #CEE1F8;
        }

        .faq .card .card-header {
          padding: 0px;
          border: none;
          background: none;
          -webkit-transition: all 0.3s ease 0s;
          -moz-transition: all 0.3s ease 0s;
          -o-transition: all 0.3s ease 0s;
          transition: all 0.3s ease 0s;
        }

        .faq .card .card-header:hover {
            background: #e9eff7;
            padding-left: 10px;
        }
        .faq .card .card-header .faq-title {
          width: 100%;
          text-align: left;
          padding: 0px;
          padding-left: 30px;
          padding-right: 30px;
          font-weight: 400;
          font-size: 15px;
          letter-spacing: 1px;
          color: #002e62;
          text-decoration: none !important;
          -webkit-transition: all 0.3s ease 0s;
          -moz-transition: all 0.3s ease 0s;
          -o-transition: all 0.3s ease 0s;
          transition: all 0.3s ease 0s;
          cursor: pointer;
          padding-top: 20px;
          padding-bottom: 20px;
        }

        .faq .card .card-header .faq-title .badge {
          display: inline-block;
          width: 20px;
          height: 20px;
          line-height: 14px;
          float: left;
          -webkit-border-radius: 100px;
          -moz-border-radius: 100px;
          border-radius: 100px;
          text-align: center;
          background: #002e62;
          color: #fff;
          font-size: 12px;
          margin-right: 20px;
        }

        .faq .card .card-body {
          padding: 30px;
          padding-left: 35px;
          padding-bottom: 16px;
          font-weight: 400;
          font-size: 16px;
          color: #002e62;
          line-height: 28px;
          letter-spacing: 1px;
          border-top: 1px solid #F3F8FF;
        }

        .faq .card .card-body p {
          margin-bottom: 14px;
        }

    .date-icon {
        position: absolute;
        top: 40px;
        right: 26px;
        cursor: pointer;
        color: #aaa;
    }
    .date-icon2 {
        position: absolute;
        top: 40px;
        right: 26px;
        cursor: pointer;
        color: #aaa;
    }

        @media (max-width: 991px) {
          .faq {
            margin-bottom: 30px;
          }
          .faq .card .card-header .faq-title {
            line-height: 26px;
            margin-top: 10px;
          }
        }
        .cursor-pointer
        {
            cursor: pointer;
        }
        .badge-custom {
            color: #fff;
            background-color: #f48636;
        }
        @media only screen and (max-width: 768px) {
         .formCover
         {
            height:140vh !important;
         }
        }
        .disabled-link{
            pointer-events: none;
        }
     </style>
</head>
<body>
    <header>
        <section class="first-container">
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-12 px-0">
                  <nav class="navbar navbar-expand-lg navbar-light registration-nav">
                    <a class="navbar-brand" href="{{env('APP_INSTANT')}}"><img class="verification-logo py-2" src="https://my-bcd.com/admin/images/BCD-Logo3.png"></a>
                    <a class="navbar-brand pl-2" href="{{env('APP_INSTANT')}}"><img class="verification-logo1 py-2" src="https://my-bcd.com/admin/images/pcil.png"></a>
                    <a class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                    </a>
                    <div class="collapse navbar-collapse topdesli" id="navbarSupportedContent">
                     <ul class="navbar-nav">
                        <li class="nav-item headwelcome">
                            Welcome, {{ Auth::user()->name }}
                        </li>
                        <li class="nav-item">
                            <a class="nav-link headbtn" href="{{url('/verify/instant_verification')}}">Start a New Verification</a>
                        </li>
                       
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/verify/instant_verification')}}">
                                
                            @if(Auth::check())
                                <?php 
                                    $business_id=Auth::user()->business_id;
                                    
                                    $guest_cart_services = DB::table('guest_instant_cart_services as gcs')
                                                            ->join('guest_instant_masters as g','g.id','=','gcs.giv_m_id')
                                                            ->where(['g.is_payment_done'=>0,'g.business_id'=>$business_id])
                                                            ->get();
                                ?>
                                <span class="badge badge-light cart_count"><img class="headimg" src="{{ asset('admin/microsite/images/shopping-cart.png')}}"><span class="cart-number">{{count($guest_cart_services)}}</span></span>
                            @else
                                <span class="badge badge-light cart_count"><img class="headimg" src="{{ asset('admin/microsite/images/shopping-cart.png')}}"><span class="cart-number">0</span></span>
                            @endif
                            </a>
                        </li>
                        <div class="dropdown">
                            <div class="user col align-self-end">
                                <img src="{{asset('guest/images/1.jpg')}}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width:40px; height:40px; border-radius:50%">
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                    <div class="dropdown-header">
                                        <i class="i-Lock-User mr-1"></i> {{ Auth::user()->name }}
                                    </div>
                                    <a href="{{url('/verify/profile')}}" class="dropdown-item">Account Settings</a>
                                    <a href="{{url('/verify/change-password')}}" class="dropdown-item">Change Password</a>
                                    {{-- <a class="dropdown-item sign_out" >Sign out</a> --}}
                                    <a class="dropdown-item" href="{{ route('logout/microsite') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                                    <form id="logout-form" action="{{ route('logout/microsite') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </div>
                        </div>              
                      </ul>
                    </div>       
                  </nav>
                </div>
              </div>
            </div>
        </section>
    </header>

    <section class="verification-step-1">
        <div class="row">
            <div class="col-md-1 col-sm-12 padding0 verification-side-bar">
                <div class="sidebar">
                    {{-- <div class="verifi-side">
                        <div class="child">
                            <a class="nav-item-hold" href="{{ url('/verify/home') }}">
                            <img src="{{ asset('admin/microsite/images/name1.png')}}">
                           <i class="nav-icon i-Home1"></i>Dashboard</a>
                        </div>
                    </div> --}}
                 {{-- @php dd(request()->is('/verify/checkdetails*')); @endphp --}}
                    <div class="verifi-side {{ request()->is('verify/instant_verification*') ? 'active' : '' }}">
                        <div class="child first-child">
                            <p>
                                <a href="{{ url('/verify/instant_verification') }}" class="nav-item-hold" >
                                <!-- <img src="{{ asset('admin/microsite/images/name1.png')}}"> -->
                                <i class="fa fa-id-card idcard" aria-hidden="true"></i><br>
                                <i class="nav-icon i-ID-3 header-icon"></i>Verification</a>
                            </p>
                        </div>
                    </div>
                    <div class="verifi-side {{ Request::is('verify/checkdetails*') ? 'active' : '' }}">
                        <div class="child">
                            <p>
                            <a href="{{ url('/verify/checkdetails') }}"  class="nav-item-hold" >
                            <!-- <img src="{{ asset('admin/microsite/images/list.png')}}"><br> -->
                            <i class="fa fa-list-alt idcard" aria-hidden="true"></i><br>
                             <i class="nav-icon i-ID-3 header-icon"></i>Check Details</a>
                            </p>
                        </div>
                    </div>
                    <div class="verifi-side {{ Request::is('verify/instantverification/orders*') ? 'active' : '' }}">
                        <div class="child">
                            <p>
                                <a href="{{ url('/verify/instantverification/orders') }}" class="nav-item-hold" >
                                <!-- <img src="{{ asset('admin/microsite/images/checklist.png')}}"><br> -->
                                <i class="fa fa-file idcard" aria-hidden="true"></i><br>
                                <i class="nav-icon i-ID-3 header-icon"></i>Order Details</a>
                            </p>
                        </div>
                    </div>
                    <div class="verifi-side  {{ Request::is('verify/instantverification/reports*') ? 'active' : '' }}">
                        <div class="child">
                            <p>
                                <a href="{{ url('/verify/instantverification/reports') }}" class="nav-item-hold" >
                                <!-- <img src="{{ asset('admin/microsite/images/report.png')}}"><br> -->
                                <i class="fa fa-file-text idcard" aria-hidden="true"></i><br>
                                <i class="nav-icon i-ID-3 header-icon"></i>Reports</a>
                            </p>
                        </div>
                    </div>
                    {{-- <div class="verifi-side2">
                        <div class="child">
                            <a class="nav-item-hold" href="{{ url('/verify/profile') }}" class="nav-item-hold">
                                <img src="{{ asset('admin/microsite/images/list.png')}}">
                            <i class="nav-icon i-Gear header-icon"></i>Accounts</a>
                        </div>
                    </div> --}}
                </div>
            </div>
            @yield('content')
        </div>
    </section>

    <script type="text/javascript">
        //EWS-Tabs
        (() => {
            var ewsTabs = document.querySelectorAll("[ews-tab]");
            if (ewsTabs.length > 0) {
                ewsTabs.forEach((tabs) => {
                    var tabButtons = tabs.querySelectorAll("[ews-tab-selector]");
                    var tabContents = tabs.querySelectorAll("[ews-tab-content]");

                    tabButtons[0].classList.add("active");
                    tabContents[0].classList.add("active");

                    tabButtons.forEach((tabButton) => {
                        tabButton.addEventListener("click", () => {
                            var tabName = tabButton.getAttribute("ews-tab-selector");
                            var tabContent = tabs.querySelector(
                                '[ews-tab-content="' + tabName + '"]'
                            );

                            tabContents.forEach((content) => {
                                content.classList.remove("active");
                            });

                            tabButtons.forEach((button) => {
                                button.classList.remove("active");
                            });

                            tabButton.classList.add("active");
                            tabContent.classList.add("active");
                        });
                    });
                });
            }
        })();


        $(document).ready(function(){
          //
          $( ".commonDatepicker2" ).datepicker({
            
               changeMonth: true,
               changeYear: true,
               firstDay: 1,
               autoclose:true,
               todayHighlight: true,
               format: 'dd-mm-yyyy',
            });

            $('.date-icon2').on('click', function() {
                $('.commonDatepicker2').focus();
            });
        });

       

        $(document).ready(function(){
          //
          $( ".commonDatepicker" ).datepicker({
            
               changeMonth: true,
               changeYear: true,
               firstDay: 1,
               autoclose:true,
               todayHighlight: true,
               format: 'dd-mm-yyyy',
            });

            $('.date-icon').on('click', function() {
                $('.commonDatepicker').focus();
            });

        //     $( ".commonDatepicker1" ).datepicker({
            
        //     changeMonth: true,
        //     changeYear: true,
        //     firstDay: 1,
        //     autoclose:true,
        //     todayHighlight: true,
        //     format: 'dd-mm-yyyy',
        //  });

            //start from today
            $( ".datepicker_start_today" ).datepicker({
               changeMonth: true,
               changeYear: true,
               firstDay: 1,
               autoclose:true,
               todayHighlight: true,
               format: 'dd-mm-yyyy',
               startDate:'today'
            });
    });
//
      $("#phone1").intlTelInput({
          initialCountry: "in",
          separateDialCode: true,
        //   preferredCountries: ["ae", "in"],
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
      //
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
      //
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

    $(document).on('click','.sign_out',function(event){
        event.preventDefault();
        $.ajax({
            type: 'Get',
            url: "{{ url('/signout') }}",
            data:{"_token" : "{{ csrf_token() }}"}, 
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                // return false;
                if(response.success==true  ) {
                    
                    document.getElementById('logout-form').submit();
                }
            },
        });
    });
</script>
<script>
$(document).ready(function(){
  $("#bar-btn").click(function(){
    $(".sidebar-left").toggleClass("left-0");
  });
});
    </script>
</body>

</html>
