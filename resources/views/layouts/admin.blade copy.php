<html lang="{{ app()->getLocale() }}">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>{{ config('app.name', 'BCD System') }}</title>
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet" />
    

      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
      <link href="{{ asset('admin/gull/dist-assets/css/themes/lite-purple.min.css') }}" rel="stylesheet" />
      <link href="{{ asset('admin/css/perfect-scrollbar.min.css?ver=1.0') }}" rel="stylesheet" />
      <link href="{{ asset('admin/fonts/font-awesome-all.css') }}" rel="stylesheet" />
      <link href="{{ asset('admin/resized/jquery.resizableColumns.css') }}" rel="stylesheet">
      <link href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
      <link href="{{ asset('admin/css/style.css?ver=1.0')}}" rel="stylesheet" />

      <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
      <link rel="stylesheet" href="{{asset('css/demo.css')}}">
      <link rel="stylesheet" href="{{asset('css/dropify.min.css')}}">
      <link rel="stylesheet" href="{{asset('css/dropify.css')}}">
      <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
         
        <!-- Data Table Css -->
        <link rel="stylesheet" href="{{asset('css/data-table/bootstrap-table.css')}}">
        {{-- <link rel="stylesheet" href="{{asset('css/data-table/bootstrap-editable.css')}}"> --}}
        <!-- style CSS
          ============================================ -->
        {{-- <link rel="stylesheet" href="{{asset('css/data-table/style.css')}}"> --}}
        <!--  -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
      
      <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>
      
      <!-- jQuery UI -->
      <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>

      <!-- phone input -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/css/intlTelInput.css" rel="stylesheet" />
     <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/intlTelInput.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
     <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js" integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g==" crossorigin="anonymous"></script>


     <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
     <script type="text/javascript">
      var loaderPath = "https://f52.in/admin/assets/images/preload.gif";
     </script>

     <style type="text/css">
        .intl-tel-input{width:100%;}
        .select2-container .select2-selection--single .select2-selection__rendered {
            display: block;
            padding-left: 8px;
            padding-right: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            }

            .pb-border{border-bottom: 1px solid #ddd; padding-top: 0px;padding-bottom: 6px;}
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
    color: #0275d8;
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
     </style>
 
 <style>
   .dropify-wrapper
   {
       height: 300px !important;

   }
</style>
      <style>
         .pb-border{border-bottom: 1px solid #ddd; padding-top: 0px;padding-bottom: 6px;}
         .low{color:orange}
         .normal{color:green}
         .high{color:red}
      </style>
      <style>
         #myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (Image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image (Image Text) - Same Width as the Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation - Zoom in the Modal */
.modal-content, #caption {
  animation-name: zoom;
  animation-duration: 0.6s;
}

@keyframes zoom {
  from {transform:scale(0)}
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 77px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}


/* new 1 */
.datatable-dashv1-list .form-control{
	height:35px;
}
.datatable-dashv1-list .btn-default{
	outline:none
}
.datatable-dashv1-list .editable-submit{
	background:#006DF0;
	color:#fff;
	border:1px solid #006DF0;
}
.datatable-dashv1-list .btn-default:hover, .datatable-dashv1-list .btn-default:focus, .datatable-dashv1-list .btn-default:active, .datatable-dashv1-list .editable-submit:hover, .datatable-dashv1-list .editable-submit:focus, .datatable-dashv1-list .editable-submit:active{
	background:#006DF0;
	color:#fff;
}
.dropdown-segmented .btn{
	padding: 9px 12px;
}
.multi-uploader-cs .dropzone.dropzone-custom{
	border: 2px dashed #006DF0;
}

.bs-bars.pull-left{
width: 16%;
float: left;
}
.pull-right{
float: right;
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
  color: #3B566E;
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
  background: #0275d8;
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
  color: #6F8BA4;
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


.fixed-table-body{
	height:auto!important;
}

      </style>
   </head>
   <body class="text-left">
      <div id="app" class="app-admin-wrap layout-sidebar-large">
         <!-- header -->
         <div class="main-header">
            <div class="col-sm-3">
               {!! Helper::company_logo(Auth::user()->business_id) !!}
            </div>
            {{-- <div class="d-flex align-items-center">
               <div class="search-bar">
                  <i class="search-icon text-muted i-Magnifi-Glass1"></i>
                  <input type="text" placeholder="Search ">
               </div>
            </div> --}}
            <div class="d-flex align-items-center">
               <div class="search-bar" style="border: 1px solid #ddd; padding:0 10px; width:410px;">
                  <i class="search-icon text-muted i-Magnifi-Glass1"></i>
                  <input type="text" placeholder="Search by type name and phone " class="search" name="search">
               </div>
            </div>
            <div style="margin: auto"></div>
            <div class="header-part-right">
               <!-- Grid menu Dropdown -->
               {{-- <div class="dropdown">
                  <i class="i-Safe-Box text-muted header-icon" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
               </div> --}}
               <!-- Notificaiton -->
               {{-- <div class="dropdown">
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
               </div> --}}
               <!-- Notificaiton End -->
               <!-- User avatar dropdown -->
               <div class="dropdown">
                  <div class="user col align-self-end">
                     <img src="{{ asset('admin/images/profile-default-avtar.jpg') }}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <div class="dropdown-header">
                           <i class="i-Lock-User mr-1"></i> {{ Auth::user()->first_name }}
                        </div>
                        <a class="dropdown-item" href="{{ route('/profile') }}">Profile</a>
                        <a class="dropdown-item" href="{{ route('/change-password') }}">Change Password</a>
                        <a class="dropdown-item" href="{{ route('/billing') }}">Billing </a>
                        <a class="dropdown-item" href="{{url('/feedback')}}">Feedback </a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                           {{ csrf_field() }}
                        </form>
                     </div>
                  </div> 
               </div>
               {{-- <div class="dropdown">
                  <i class="i-Gear text-muted header-icon" role="button" id="settingbotton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>             
               </div> --}}
            </div>
         </div>
         <!-- left sidebar start --> 
         <div class="side-content-wrap">
            <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar="" data-suppress-scroll-x="true" style="top:65px">
               <ul class="navigation-left">
                  <?php $user = Auth::user()->user_type;
                  // dd($user);
                   ?>
               @if ($user == 'customer')
                   
              
                  <li class="nav-item {{ request()->is('home') ? 'active' : '' }}"><a href="{{ url('/home') }}" class="nav-item-hold" ><i class="nav-icon i-Home1"></i>Dashboard</a>
                  {{-- <li class="nav-item {{ request()->is('contacts*') ? 'active' : '' }}" ><a href="{{ url('/contacts') }}"  class="nav-item-hold"><i class="nav-icon i-Male" ></i>Contacts</a> --}}
                  {{-- </li> --}}
                   <li class="nav-item {{ request()->is('task*') ? 'active' : '' }}"><a class="nav-item-hold" href="{{  route('/task') }}"><i class="nav-icon i-check text-muted header-icon"></i>Tasks</a>
                  </li>
                  <li class="nav-item {{ request()->is('customers*') ? 'active' : '' }}" ><a href="{{ url('/customers') }}"  class="nav-item-hold"><i class="nav-icon i-Male" ></i>Customers</a>
                  </li>
                  <li class="nav-item {{ request()->is('batches*') ? 'active' : '' }}" ><a href="{{ url('/batches') }}"  class="nav-item-hold"><i class="nav-icon i-Suitcase" ></i>Batches</a>
                  </li>
                  <li class="nav-item {{ request()->is('candidates*') ? 'active' : '' }}"><a href="{{ url('/candidates') }}" class="nav-item-hold" > <i class="nav-icon i-Add-User"></i>Candidates</a>
                  </li>
                  <li class="nav-item {{ request()->is('insuff*') ? 'active' : '' }}" ><a href="{{ url('/insuff') }}"  class="nav-item-hold"><i class="nav-icon i-Flag"></i>Insufficiency</a>
                  </li>
                  <li class="nav-item {{ request()->is('jobs*') ? 'active' : '' }}"><a href="{{ url('/jobs') }}" class="nav-item-hold"> <i class="nav-icon i-Suitcase"></i>Checks</a>
                  </li>

                  <li class="nav-item {{ request()->is('idChecks*') ? 'active' : '' }}" ><a href="{{ url('/idChecks') }}"  class="nav-item-hold"><i class="nav-icon i-check" ></i>Verifications</a>
                  </li>
                  <li class="nav-item {{ request()->is('vendor*') ? 'active' : '' }}" ><a href="{{ url('/vendor') }} "  class="nav-item-hold"><i class="nav-icon i-Male" ></i>Vendors</a>
                  </li>
                  {{-- <li class="nav-item {{ request()->is('report_mis*') ? 'active' : '' }}"><a href="{{ url('/report_mis') }}" class="nav-item-hold"> <i class="nav-icon i-Computer-Secure"></i>Quality Checks</a> --}}
                  {{-- </li> --}}
                  <li class="nav-item {{ request()->is('reports*') ? 'active' : '' }}"><a href="{{ url('/reports') }}" class="nav-item-hold"> <i class="nav-icon i-Computer-Secure"></i>Reports</a>
                  </li>
                  </li>
                  <li class="nav-item {{ request()->is('users*') ? 'active' : '' }}"><a href="{{ route('users.index') }}" class="nav-item-hold"> <i class="nav-icon i-Computer-Secure"></i>Users</a>
                  </li>
                  <li class="nav-item  {{ request()->is('roles*') ? 'active' : '' }}"><a href="{{ url('/roles') }}" class="nav-item-hold"> <i class="nav-icon i-Computer-Secure"></i>Roles</a> 
                  </li>
                  {{-- <li class="nav-item"><a class="nav-item-hold" href="{{route('/price_plan')}}"><i class="nav-icon i-Computer-Secure"></i>Pricing Plans</a> --}}
                  {{-- </li> --}}
                  <li class="nav-item  "><a class="nav-item-hold" href="{{  url('/profile') }}"><i class="nav-icon i-Gear text-muted header-icon"></i>Accounts</a>
                  </li>
                 
                  @else
                  
                  {{-- @if (get_user_permission($user)) --}}
                  <?php 
                          $user = Auth::user()->role;
                          $business_id=Auth::user()->business_id;
                        // dd($business_id);
                        
                        $childs =Helper::get_user_permission($user,$business_id);
                   $permissions = DB::table('action_masters')->select('*')->where('route_group','')->orderBy('display_order','ASC')->get();
                  //  dd($permissions)
                   ?>
                   @foreach ($permissions as $item)
                   @if ($item->action == '/home') 
                       <?php $active = 'home' ?>
                   @endif
                   @if ($item->action == '/customers')
                   <?php $active = 'customers' ?>
                   @endif
                   @if ($item->action == '/task')
                   <?php $active = 'task' ?>
                   @endif
                   @if ($item->action == '/candidates')
                   <?php $active = 'candidates' ?>
                   @endif
                   @if ($item->action == '/batches')
                   <?php $active = 'batches' ?>
                   @endif
                   @if ($item->action == '/jobs')
                   <?php $active = 'jobs' ?>
                   @endif
                   @if ($item->action == '/reports')
                   <?php $active = 'reports' ?>
                   @endif
                   @if ($item->action == '/idChecks')
                   <?php $active = 'idChecks' ?>
                   @endif
                   @if (in_array($item->id,json_decode($childs)) && $item->parent_id == '0' && $item->show_in_menu == '1' )
                   <li class="nav-item {{ request()->is($active) ? 'active' : '' }}" ><a href="{{ url($item->action) }}"  class="nav-item-hold"><i class="nav-icon {{$item->icon}}" ></i>{{$item->action_title}}</a>
                   </li>
                 @endif
                 @endforeach
                  @endif
                  {{-- @endif --}}
               </ul>
            </div>
            <div class="sidebar-overlay"></div>
         </div>
         <!-- ./ end left side bar -->
         <!-- main content -->
         @yield('content')
         <!--  -->
         <div class="flex-grow-1"></div>
      </div>
      </div> 
      
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
                   <input type="hidden" name="report_type" id="report_type">
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
                    <button type="button" data-id="" data-url="{{  url('/candidate/report/pdf',['id'=> base64_encode(8)]) }}" class="btn btn-primary downloadReportBtn">Download Now</button>
                </div>
            </div>
        </div>
    </div>

 
      <!-- assets files -->
      <script src="{{ asset('admin/js/bootstrap.bundle.min.js') }}"></script>
      <script src="{{ asset('admin/js/perfect-scrollbar.min.js') }}"></script>
      <script src="{{ asset('admin/js/script.min.js') }}"></script>
      <script src="{{ asset('admin/js/sidebar.large.script.min.js') }}"></script>
      <script src="{{ asset('admin/js/echarts.min.js') }}"></script>
      <script src="{{ asset('admin/js/echart.options.min.js') }}"></script>
      <script src="{{ asset('admin/js/dashboard.v1.script.min.js') }}"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

      <script type="text/javascript">
      var path = "{{ url('/candidates/autocomplete') }}";
      $('input.search').typeahead({
            source:  function (search, process) {
            return $.get(path, { search: search }, function (data) {
            // alert(data);
                  return process(data);
               });
            }
      });
      </script>

<script>

   var echartElemBar = document.getElementById('echartBar');
   var graph_data = <?php $a=Helper::get_graph_admin_data('1'); echo json_encode($a); ?>;
 
   var check_name = [];
   var completed = [];
   var pending = [];
   // console.log(check_name);
   for (let i = 0; i < graph_data.length; i++) 
   {
       check_name.push(graph_data[i].check_name);
       completed.push(graph_data[i].completed);
       pending.push(graph_data[i].pending);
     
   }
   // console.log(check_name);
   if (echartElemBar) {
     var echartBar = echarts.init(echartElemBar);
     echartBar.setOption({
       legend: {
         borderRadius: 0,
         orient: 'horizontal',
         x: 'right',
         data: ['Remaining', 'Completed']
       },
       grid: {
         left: '8px',
         right: '8px',
         bottom: '0',
         containLabel: true
       },
       tooltip: {
         show: true,
         backgroundColor: 'rgba(0, 0, 0, .8)'
       },
       xAxis: [{
         type: 'category',
         data:check_name,
         
         axisTick: {
           alignWithLabel: true
         },
         splitLine: {
           show: false
         },
         axisLine: {
           show: false
         }
       }],
       yAxis: [{
         type: 'value',
         axisLabel: {
           formatter: '{value}'
         },
         min: 0,
         max: 500,
         interval: 50,
         axisLine: {
           show: false
         },
         splitLine: {
           show: true,
           interval: 'auto'
         }
       }],
       
       series: [{
         name: 'Remaining',
         data: pending,
         label: {
           show: false,
           color: '#0168c1'
         },
         type: 'bar',
         barGap: 0,
         color: '#bcbbdd',
         smooth: true,
         itemStyle: {
           emphasis: {
             shadowBlur: 10,
             shadowOffsetX: 0,
             shadowOffsetY: -2,
             shadowColor: 'rgba(0, 0, 0, 0.3)'
           }
         }
       }, {
         name: 'Completed',
         data: completed,
         label: {
           show: false,
           color: '#639'
         },
         type: 'bar',
         color: '#7569b3',
         smooth: true,
         itemStyle: {
           emphasis: {
             shadowBlur: 10,
             shadowOffsetX: 0,
             shadowOffsetY: -2,
             shadowColor: 'rgba(0, 0, 0, 0.3)'
           }
         }
       }]
       
     });
     // console.log(check_name);
     $(window).on('resize', function () {
       setTimeout(function () {
         echartBar.resize();
       }, 500);
     });
 }
 
 </script>
 

      <script type="text/javascript">
         //
         $(document).on('click','.reportExportBox',function(e){   
            // var report_type =$(this).attr('data-type');
            // $('#report_type').val(report_type); 
            
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
                     url: "{{ url('/') }}"+'/reports/setData/?report_id='+report_id+'&reportType='+reportType+'&candidate_id='+candidate_id,
                     type: "get",
                     datatype: "html",
               })
               .done(function(data)
               {
                  console.log(data);
                  var path = "{{ url('/') }}"+"/candidate/report/pdf/"+report_id;
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
                 window.location.href = "{{route('/job/create')}}";
               }
               else{
                 window.location.href = "{{route('/job/import')}}";
               }
             });   

            // 
            $( ".searchUniversity_board" ).autocomplete({
            source: function( request, response ) {
               // Fetch data
               $.ajax({
               url: "{{ url('/customer/universityBoardList') }}",
               type: 'post',
               dataType: "json",
               data: {search: request.term,"_token": "{{ csrf_token() }}"
               },
               success: function( data ) {
               response( data.data );
               }
               });
            },
            select: function (event, ui) {
               // Set selection
               console.log(ui);
               // alert("data="+ui.item.label);
               $('.searchUniversity_board').val(ui.item.label); // display the selected text
               // $('#searchUniversity_board').val(ui.value); // save selected id to input
               return false;
            },
            onSelect: function (data) {
               // alert('You selected: ' + data.data.label);
            }

            });
                     
         });

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
                 window.location.href = "{{url('/job/create')}}";
               }
               else{
                 window.location.href = "{{url('/job/import')}}";
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
