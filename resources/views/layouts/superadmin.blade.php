<!DOCTYPE html>
<html lang="en" dir="">
   <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width,initial-scale=1" />
      <meta http-equiv="X-UA-Compatible" content="ie=edge" />
      <title> BCD Admin </title>
      <link rel="shortcut icon" type="image/x-icon" href="{{url('/').'/admin/images/BCD-favicon.png'}}">
      <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet" />

      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>

      <link href="{{ asset('superadmin/gull/dist-assets/css/themes/lite-purple.min.css?ver=1.2') }}" rel="stylesheet" />
      <link href="{{ asset('superadmin/css/perfect-scrollbar.min.css?ver=0.1') }}" rel="stylesheet" />
      {{-- <link href="{{ asset('superadmin/fonts/font-awesome-all.css') }}" rel="stylesheet" /> --}}
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.1.0/css/all.css" />
      {{-- <link href="{{ asset('superadmin/resized/jquery.resizableColumns.css') }}" rel="stylesheet" > --}}
      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
      {{-- <link href="{{ asset('superadmin/css/bootstrap-timepicker.min.css')}}" rel="stylesheet" type="text/css"> --}}
      {{-- <link href="https://formdox.com/assets/backend/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet"> --}}
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

      <link href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" rel="stylesheet">

      <!-- Select2 CSS -->
     {{-- <link rel="stylesheet" href="https://techsagacrm.in/css/select2.min.css"> --}}
     {{-- <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}"> --}}
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

      <link href="{{ asset('superadmin/css/style.css?ver=0.1') }}" rel="stylesheet" />
      <!-- notify -->
      <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
      <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
      {{-- <script src="{{ asset('superadmin/js/jquery-3.3.1.min.js') }}"></script> --}}
      {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

      <!-- Select2 JS -->
      {{-- <script src="https://techsagacrm.in/js/select2.min.js"></script> --}}
      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js" integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g==" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>

    <!-- phone input -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/css/intlTelInput.css" rel="stylesheet" />
     <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/intlTelInput.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
     {{-- <script src="{{ URL::to('/superadmin/js/bootstrap-timepicker.min.js')}}"></script> --}}
     <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
     {{-- <script src="https://formdox.com/assets/backend/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script> --}}
     <!-- notify -->
     <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

     <script type="text/javascript">
        var loaderPath = "https://f52.in/admin/assets/images/preload.gif";
     </script>

      {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-V9FYLJ3VPD"></script> --}}
      <script async src="https://www.googletagmanager.com/gtag/js?id=UA-252123846-1"></script>
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

     <style type="text/css">
        .intl-tel-input{width:100%;}

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
        color: #663399;
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
          color: #663399;
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
          background: #663399;
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
          color: #663399;
          line-height: 28px;
          letter-spacing: 1px;
          border-top: 1px solid #F3F8FF;
        }

        .faq .card .card-body p {
          margin-bottom: 14px;
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

        .badge-custom {
            color: #fff;
            background-color: #f48636;
        }
    
     </style>

   </head>
   <body class="text-left">
      <div id="app" class="app-admin-wrap layout-sidebar-large">
         <div class="main-header">

         <div class="col-sm-3">
            {!! Helper::company_logo(Auth::user()->business_id) !!}
            </div>
            <div class="d-flex align-items-center">
               <div class="search-bar">
                  <i class="search-icon text-muted i-Magnifi-Glass1"></i>
                  <input type="text" placeholder="Search ">
               </div>
            </div>


            <div style="margin: auto"></div>
            <div class="header-part-right">
               <!-- Grid menu Dropdown -->
               <div class="dropdown">
                  <i class="i-Safe-Box text-muted header-icon" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
               </div>
               <!-- Notificaiton -->
               <div class="dropdown">
                  <div class="badge-top-container" role="button" id="dropdownNotification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <span class="badge badge-primary">3</span>
                     <i class="i-Bell text-muted header-icon"></i>
                  </div>
                  <!-- Notification dropdown -->
                  <div class="dropdown-menu dropdown-menu-right notification-dropdown rtl-ps-none" aria-labelledby="dropdownNotification" data-perfect-scrollbar data-suppress-scroll-x="true">
                     <div class="dropdown-item d-flex">
                        <div class="notification-icon">
                           <i class="i-Speach-Bubble-6 text-primary mr-1"></i>
                        </div>
                        <div class="notification-details flex-grow-1">
                           <p class="m-0 d-flex align-items-center">
                              <span>New message</span>
                              <span class="badge badge-pill badge-primary ml-1 mr-1">new</span>
                              <span class="text-small text-muted ml-auto">10 sec ago</span>
                           </p>
                           <p class="text-small text-muted m-0">James: Hey! are you busy?</p>
                        </div>
                     </div>
                     <div class="dropdown-item d-flex">
                        <div class="notification-icon">
                           <i class="i-Receipt-3 text-success mr-1"></i>
                        </div>
                        <div class="notification-details flex-grow-1">
                           <p class="m-0 d-flex align-items-center">
                              <span>New order received</span>
                              <span class="badge badge-pill badge-success ml-1 mr-1">new</span>
                              <span class="text-small text-muted ml-auto">2 hours ago</span>
                           </p>
                           <p class="text-small text-muted m-0">1 Headphone, 3 iPhone x</p>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- Notificaiton End -->
               <!-- User avatar dropdown -->
               <div class="dropdown">
                  <div class="user col align-self-end">
                     <img src="{{ asset('superadmin/images/1.jpg') }}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <div class="dropdown-header">
                           <i class="i-Lock-User mr-1"></i> {{Auth::user()->first_name}} 
                        </div>
                        <a class="dropdown-item" href="{{ ('/app/settings/profile') }}">Profile</a>
                        <a class="dropdown-item" href="{{ ('/app/change-password') }}">Change Password</a>
                        <a class="dropdown-item sign_out" >Sign out</a>
                        {{-- <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a> --}}
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                           {{ csrf_field() }}
                        </form>
                     </div>
                  </div>
               </div>
               <div class="dropdown">
                  <i class="i-Gear text-muted header-icon" role="button" id="settingbotton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>             
               </div>
            </div>
         </div>
         <!-- left sidebar start -->
         <div class="side-content-wrap">
            <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar="" data-suppress-scroll-x="true">
               <ul class="navigation-left">
                  <li class="nav-item {{ request()->is('app/home*') ? 'active' : '' }}"><a href="/app/home" class="nav-item-hold" ><i class="nav-icon i-Home1"></i>Dashboard</a>
                  <li class="nav-item {{ request()->is('app/customers*') ? 'active' : '' }}" ><a href="{{ url('/app/customers') }}"  class="nav-item-hold"><i class="nav-icon i-Male" ></i>Customers</a>
                  </li>
                  <li class="nav-item {{ request()->is('app/contacts*') ? 'active' : '' }}" ><a href="{{ url('/app/contacts') }}"  class="nav-item-hold"><i class="nav-icon i-Male" ></i>Contacts</a>
                  </li>
                  <li class="nav-item {{ request()->is('app/jobs*') ? 'active' : '' }}"><a href="{{ url('/app/jobs') }}" class="nav-item-hold"> <i class="nav-icon i-Suitcase"></i>Cases</a>
                  </li>
                  <li class="nav-item {{ request()->is('app/verifications*') ? 'active' : '' }}" ><a href="{{ url('/app/verifications') }}"  class="nav-item-hold"><i class="nav-icon i-Suitcase"></i>Verifications</a>
                  </li>
                  <li class="nav-item {{ request()->is('app/vendor*') ? 'active' : '' }}" ><a href="{{ url('/app/vendor') }}"  class="nav-item-hold"><i class="nav-icon i-Male" ></i>Vendors</a>
                  {{-- <li class="nav-item {{ request()->is('app/qcs*') ? 'active' : '' }}"><a href="{{ url('/app/qcs') }}" class="nav-item-hold"> <i class="nav-icon i-Computer-Secure"></i>Quality Checks</a>
                  </li> --}}
                  <li class="nav-item {{ request()->is('app/users*') ? 'active' : '' }}"><a href="{{ url('/app/users') }}" class="nav-item-hold"> <i class="nav-icon i-Computer-Secure"></i>Users</a>
                  </li>
                  <li class="nav-item {{ request()->is('app/roles*') ? 'active' : '' }}"><a href="{{ url('/app/roles') }}" class="nav-item-hold"> <i class="nav-icon i-Computer-Secure"></i>Roles</a>
                  </li>
                  <li class="nav-item {{ request()->is('app/guest*') ? 'active' : '' }}"><a href="{{ url('/app/guest/default') }}" class="nav-item-hold"> <i class="nav-icon i-Male" ></i>Guest Users</a>
                  </li>
                  <li class="nav-item {{ request()->is('app/subscriptions*') ? 'active' : '' }}"><a href="{{ url('/app/subscriptions') }}" class="nav-item-hold"> <i class="nav-icon i-Computer-Secure"></i>Packages</a>
                  </li>
                  <li class="nav-item {{ request()->is('app/settings*') ? 'active' : '' }}"><a class="nav-item-hold" href="{{  url('/app/settings/general') }}"><i class="nav-icon i-Gear header-icon"></i>Settings</a>
                  </li>
               </ul>
            </div>
            <div class="sidebar-overlay"></div>
         </div>
         <!-- ./ end left side bar -->
         <!-- ============ Body content start ============= -->
         <!-- main content -->
         @yield('content')
         <!--  -->
         <div class="flex-grow-1"></div>
      </div>
      <!-- ./ main div -->
      <!-- crate job modal The Modal -->
      <div class="modal" id="job_option_modal">
         <div class="modal-dialog">
            <div class="modal-content">
               <!-- Modal Header -->
               <div class="modal-header">
                  <h4 class="modal-title"> Select Type </h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
               </div>
               <!-- Modal body -->
               <div class="modal-body">
                  <label class="checkbox-inline"><input type="radio" class="jobEntryType" name="jobEntryType" value="single" checked="checked"> Single Entry</label>
                  <label class="checkbox-inline"><input type="radio" class="jobEntryType" name="jobEntryType" value="multiple"> Multiple Entry</label>
               </div>
               <!-- Modal footer -->
               <div class="modal-footer">
                  <button type="button" class="btn btn-primary openJobForm" >  Go </button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>
      <!-- create job modal -->
      <!-- open pdf export modal-->
    <div id="reportTypeModal" class="modal fade" tabindex="-1">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Report: <span class="candidateNameLable"></span></h5>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                  <p>Download the report</p>
                  <!-- <p class="text-secondary"><small>If you don't save, your changes will be lost.</small></p> -->
                  <div class="form-group">
                      <label>Select Report Type</label>
                      <select class="form-control" id="selectType">
                          <option value=""> -Select- </option>
                          <option value="Interim"> Interim </option>
                          <option value="Supplementry"> Supplementary </option>
                          <option value="Final"> Final </option>
                      </select>
                  </div>

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="button" data-id="" data-url="{{  url('/customers/report/pdf',['id'=> base64_encode(8)]) }}" class="btn btn-primary downloadReportBtn">Download Now</button>
              </div>
          </div>
      </div>
  </div>

      <!-- assets files -->
   <script src="{{ asset('superadmin/js/bootstrap.bundle.min.js') }}"></script>
   <script src="{{ asset('superadmin/js/perfect-scrollbar.min.js') }}"></script>
   <script src="{{ asset('superadmin/js/script.min.js') }}"></script>
   <script src="{{ asset('superadmin/js/sidebar.large.script.min.js') }}"></script>
   <script src="{{ asset('superadmin/js/echarts.min.js') }}"></script>
   <script src="{{ asset('superadmin/js/echart.options.min.js') }}"></script>
   <script src="{{ asset('superadmin/js/dashboard.v1.script.min.js') }}"></script>

   <script type="text/javascript">
         //
      $(function () {
         
         $('[data-toggle="tooltip"]').tooltip();
      });
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
         $(document).on('click','.reportExportBox',function(e){    
            var report_id = $(this).attr('data-id');
            $('.downloadReportBtn').attr('data-id','');
            $('.downloadReportBtn').attr('data-id',report_id);
            var name    = $("table.reportTable").find("[data-row='" + report_id + "']").find('td.candidateName').html(); 
            // alert(name);
            $('.candidateNameLable').html(name);
            $('#selectType').prop('selectedIndex',0);
            $('#reportTypeModal').modal();
            
         });
   
         // print visits  
         $(document).on('click','.downloadReportBtn',function(){
            var report_id       = $(this).attr('data-id');
            var reportType      = $("#selectType option:selected").val();
            var candidate_id    = '';
               if(reportType !=''){
                  
                     $.ajax(
                     {
                           url: "{{ url('/') }}"+'/app/customers/reports/setData/?report_id='+report_id+'&reportType='+reportType+'&candidate_id='+candidate_id,
                           type: "get",
                           datatype: "html",
                     })
                     .done(function(data)
                     {
                        console.log(data);
                        var path = "{{ url('/') }}"+"/app/customers/report/pdf/"+report_id;
                           console.log(path);
                           window.open(path);
                           $('#reportTypeModal').modal('hide');
                     })
                     .fail(function(jqXHR, ajaxOptions, thrownError)
                     {
                           //alert('No response from server');
                     });
                  
               }else{
                     alert('Please select a type to export! ');
               }
         });

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

      <script type="text/javascript">
         $(document).ready(function() {
         
             //open modal
             $(document).on('click','.createJob',function(){
               $('#job_option_modal').modal();
             });   
         
             $(document).on('click','.openJobForm',function(){
         
               if($('.jobEntryType:checked ').val() == 'single')
               {
                 window.location.href = "{{url('/app/job/create')}}";
               }
               else{
                 window.location.href = "{{url('/app/job/import')}}";
               }
             });   

            //
            $( ".commonDatepicker" ).datepicker({
               changeMonth: true,
               changeYear: true,
               firstDay: 1,
               autoclose:true,
               todayHighlight: true,
               format: 'dd-mm-yyyy',
            });

            $('.timepicker').timepicker({
               timeFormat: 'hh:mm a',
               interval: 60,
               defaultTime: '12',
               startTime: '12:00',
               dynamic: false,
               dropdown: true,
               scrollbar: true
            });

            $('.timepicker1').timepicker({
               timeFormat: 'hh:mm a',
               interval: 60,
               dynamic: false,
               dropdown: true,
               scrollbar: true
            });

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
   </body>
</html>
