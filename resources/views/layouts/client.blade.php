<html lang="{{ app()->getLocale() }}">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>{{ config('app.name', 'BCD System') }}</title>
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="shortcut icon" type="image/x-icon" href="{{url('/').'/admin/images/BCD-favicon.png'}}">
      <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet" />

      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
      <link href="{{ asset('admin/gull/dist-assets/css/themes/lite-purple.min.css?ver=1.0') }}" rel="stylesheet" />
      <link href="{{ asset('admin/css/perfect-scrollbar.min.css?ver=1.0') }}" rel="stylesheet" />
      {{-- <link href="{{ asset('admin/fonts/font-awesome-all.css') }}" rel="stylesheet" /> --}}
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.1.0/css/all.css" />
      {{-- <link href="{{ asset('admin/resized/jquery.resizableColumns.css') }}" rel="stylesheet"> --}}
      {{-- <link href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> --}}
      <link href="{{ asset('admin/css/style.css?ver=1.8') }}" rel="stylesheet" />
      {{-- <link rel="stylesheet" href="{{asset('css/demo.css')}}">
      <link rel="stylesheet" href="{{asset('css/dropify.min.css')}}">
      <link rel="stylesheet" href="{{asset('css/dropify.css')}}"> --}}

      <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
      
      {{-- <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script> --}}
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>

      <!-- phone input -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/css/intlTelInput.css" rel="stylesheet" />
     <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/intlTelInput.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
     
      
    
      {{-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> --}}
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
     {{-- <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}"> --}}
     <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js" integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g==" crossorigin="anonymous"></script>

     <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

     <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
      <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet"/>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js"></script>

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
   
   .dropify-wrapper
   {
      height: 300px !important;
   }
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
    color: #003473;
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

      /* Start by setting display:none to make this hidden.
   Then we position it in relation to the viewport window
   with position:fixed. Width, height, top and left speak
   for themselves. Background we set to 80% white with
   our animation centered, and no-repeating */

   .bcd_loading {
    display: none;
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

}

/* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden */
   body.loading .bcd_loading {
    overflow: hidden;   
}

/* Anytime the body has the loading class, our
   modal element will be visible */
body.loading .bcd_loading {
    display: block;
}

.badge-custom {
    color: #fff;
    background-color: #f48636;
}


/* Ratings widget */
.rate {
    display: inline-block;
    border: 0;
}
/* Hide radio */
.rate > input {
    display: none;
}
/* Order correctly by floating highest to the right */
.rate > label {
    float: right;
}
/* The star of the show */
.rate > label:before {
    display: inline-block;
    font-size: 1.1rem;
    padding: .3rem .2rem;
    margin: 0;
    cursor: pointer;
    font-family: FontAwesome;
    content: "\f005 "; /* full star */
}
/* Zero stars rating */
.rate > label:last-child:before {
    content: "\f006 "; /* empty star outline */
}
/* Half star trick */
.rate .half:before {
    content: "\f089 "; /* half star no outline */
    position: absolute;
    padding-right: 0;
}
/* Click + hover color */
input:checked ~ .stars_1, /* color current and previous stars on checked */
.stars_1:hover, .stars_1:hover ~ .stars_1 { color: #73B100;  } /* color previous stars on hover */

/* Hover highlights */
input:checked + .stars_1:hover, input:checked ~ .stars_1:hover, /* highlight current and previous stars */
input:checked ~ .stars_1:hover ~ .stars_1, /* highlight previous selected stars for new rating */
.stars_1:hover ~ input:checked ~ .stars_1 /* highlight previous selected stars */ { color: #A6E72D;  } 

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

.image-area {
    width: 150px;
    /* height: 135px; */
    display: inline-block;
}

span.show-calender-icon {
        position: relative;
        top: -28px;
        right: -94%;
        font-size: 16px;
        color: #748a9c;
        cursor: pointer;
        z-index: 1;
    }


</style>

  <script type="text/javascript">
   var loaderPath = "{{asset('admin/images/preload.gif')}}";
  </script>

   </head>
   <body class="text-left">
      <div id="app" class="app-admin-wrap layout-sidebar-large">
         <!-- header -->
         <div class="main-header">
            <div class="col-sm-3">
               {!! Helper::company_logo(Auth::user()->business_id) !!}
            </div>
            <div class="d-flex align-items-center">
              <div class="search-bar" style="border: 1px solid #ddd; padding:0 10px; width:410px;">
                 <i class="search-icon text-muted i-Magnifi-Glass1"></i>
                 <input type="text" placeholder="Search by type name and phone " class="search" name="search">
              </div>
           </div> 
           @php
             $primary_data=Helper::primary_kam_list(Auth::user()->business_id);
            //  dd($primary_data);
            $secondary_data=Helper::secondary_kam_list(Auth::user()->business_id);
           @endphp
            <div style="margin: auto"></div>
            @if($primary_data!=NULL || $secondary_data!=NULL)
              <div class="row" style="">
                <div class="col-12">
                  @if($primary_data!=NULL)
                    <label class="text-info">Primary CAM :</label>
                    <span class="text-dark"> {{$primary_data->name}} , Mob: {{$primary_data->phone}}</span>
                  @endif
                  <br>
                  @if($secondary_data!=NULL)
                    <label class="text-info">Secondary CAM : </label>
                    <span>{{$secondary_data->name}} , Mob: {{$secondary_data->phone}}</span>
                  @endif
                </div>
              </div>
            @endif
            <div class="header-part-right" style="padding-right: 4px;">
               <!-- Grid menu Dropdown -->
               
               <!-- Notificaiton -->
               <div class="dropdown">
                  <!-- Notification dropdown -->
                 
               </div>
               <!-- Notificaiton End -->
               <!-- User avatar dropdown -->
               <div class="dropdown">
                  <div class="user col align-self-end">
                     <img src="{{ asset('admin/images/profile-default-avtar.jpg') }}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <div class="dropdown-header">
                           <i class="i-Lock-User mr-1"></i> {{ Auth::user()->first_name }}
                        </div>
                        <a class="dropdown-item" href="{{ route('/my/profile') }}">Profile</a>
                        <a class="dropdown-item" href="{{route('/my/change-password')}}">Change Password</a>
                        {{-- <a class="dropdown-item" href="{{ route('/my/billing') }}">Billing </a> --}}
                        <a class="dropdown-item" href="{{ url('/my/feedback') }}">Feedback </a>
                        <a class="dropdown-item" href="{{ url('/my/help') }}">Help & Support </a>
                        <a class="dropdown-item sign_out" >Sign out</a>
                        {{-- <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a> --}}
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                           {{ csrf_field() }}
                        </form>
                     </div>
                  </div>
               </div>
               <!-- <div class="dropdown">
                  <i class="i-Gear text-muted header-icon" role="button" id="settingbotton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>             
               </div> -->
            </div>
         </div>
         <!-- left sidebar start -->
         <div class="side-content-wrap">
            <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar="" data-suppress-scroll-x="true" style="top:65px">
               <ul class="navigation-left">
                  <?php $user = Auth::user()->user_type;
                  // dd($user);
                  $business_id=Auth::user()->business_id;
                  $verification_hide = Helper::verification_show($business_id); 
                   ?>
                @if ($user == 'client')
                  <li class="nav-item {{ request()->is('my/home') ? 'active' : '' }}"><a href="{{ url('/my/home') }}" class="nav-item-hold" ><i class="nav-icon i-Home1"></i>Dashboard</a>
                  </li>
                  
                  <?php $candidates = Request::segment(2); ?>
                  <li class="nav-item {{ ($candidates =='candidates') ? 'active' : '' }}"><a href="{{ url('/my/candidates') }}" class="nav-item-hold" > <i class="nav-icon i-MaleFemale"></i>Candidates</a>
                 </li>
                 <li class="nav-item {{ request()->is('my/insuff*') ? 'active' : '' }}" ><a href="{{ url('/my/insuff') }}"  class="nav-item-hold"><i class="nav-icon i-Flag"></i>Insufficiency</a>
                 </li>
                 <li class="nav-item {{ request()->is('my/batches*') ? 'active' : '' }}" ><a href="{{ url('/my/batches') }}"  class="nav-item-hold"><i class="nav-icon i-Suitcase" ></i>Batches</a>
                 </li>
                    {{--<li class="nav-item {{ request()->is('my/checks') ? 'active' : '' }}"><a href="/my/checks" class="nav-item-hold"><i class="nav-icon i-check" ></i>Checks</a> --}}
                  @if($verification_hide==NULL)
                    <li class="nav-item {{ request()->is('my/idChecks*') ? 'active' : '' }}" ><a href="{{ url('my/idChecks') }}"  class="nav-item-hold"><i class="nav-icon i-ID-3" ></i>Instant Verifications</a>
                    </li>
                  @endif
                  {{-- </li>  --}}
                  <li class="nav-item {{ request()->is('my/reports*') ? 'active' : '' }}"><a href="{{ url('/my/reports') }}" class="nav-item-hold"> <i class="nav-icon i-Notepad"></i>Reports</a>
                  </li>
                  <li class="nav-item  {{ request()->is('my/billing*') ? 'active' : '' }}"><a href="{{ url('my/billing') }}" class="nav-item-hold"> <i class="nav-icon i-Billing"></i>Billing</a> 
                  </li>
                  <li class="nav-item {{ request()->is('my/sla*') ? 'active' : '' }}"><a href="{{ url('/my/sla') }}" class="nav-item-hold"><i class="nav-icon i-Handshake" ></i>SLA</a>
                  </li>
                  <li class="nav-item {{ request()->is('my/users*') ? 'active' : '' }}"><a href="{{ url('/my/users') }}" class="nav-item-hold" > <i class="nav-icon i-Checked-User"></i>Users</a>
                  </li>
                  <li class="nav-item  {{ request()->is('my/roles*') ? 'active' : '' }}"><a href="{{ url('my/roles') }}" class="nav-item-hold"> <i class="nav-icon i-ID-Card"></i>Roles</a> 
                  </li>
                  <?php $billing = Request::segment(2); ?>
                  <li class="nav-item @if($billing == 'profile' || $billing =='business-info' || $billing =='profile' || $billing =='contact-info') active @endif "><a href="{{ url('/my/profile') }}" class="nav-item-hold"><i class="nav-icon i-Gear header-icon" ></i>Accounts</a>
                  </li>
                @else
                  
                  {{-- @if (get_user_permission($user)) --}}
                  <?php 
                          $user = Auth::user()->role;
                           $business_id= Auth::user()->business_id;
                        // dd($user); 
                        $childs =Helper::get_user_permission($user,$business_id);
                        // dd($childs);
                   $permissions = DB::table('action_masters')->select('*')->where('route_group','/my')->orderBy('display_order','ASC')->get();
                  //  dd($permissions)
                   ?>
                   @foreach ($permissions as $item)
                    @if ($item->action == '/home')
                    <?php $active = 'my/home' ?>
                    @endif       
                    @if ($item->action == '/candidates')
                    <?php $active = 'my/candidates' ?>
                    @endif 
                    @if ($item->action == '/insuff')
                    <?php $active = 'my/insuff*' ?>
                    @endif      
                    @if ($item->action == '/reports')
                    <?php $active = 'my/reports' ?>
                    @endif
                    @if ($item->action == '/idChecks')
                    <?php $active = 'my/idChecks' ?>
                    @endif
                    @if ($item->action == '/batches')
                    <?php $active = 'my/batches' ?>
                    @endif
                    @if ($item->action == '/billing')
                    <?php $active = 'my/billing' ?>
                    @endif
                    @php
                      //check if verification tab was hidden be an admin/user
                      if($item->action=='/idChecks' && $verification_hide!=NULL)
                      {
                        $class='d-none';
                      }
                      else
                      {
                        $class='';
                      }
                    @endphp
                    @if (in_array($item->id,json_decode($childs)) && $item->parent_id == '0' && $item->show_in_menu == '1' && $item->route_group == '/my' )
                    
                        <li class="nav-item {{ request()->is($active) ? 'active' : '' }} {{$class}}" >
                          <a href="{{ url($item->route_group.$item->action) }}"  class="nav-item-hold"><i class="nav-icon {{$item->icon}}" ></i>{{$item->action_title}}</a>
                        </li>
                    @endif
                   @endforeach
                @endif
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
                  <p>Download the report</p>
                  <!-- <p class="text-secondary"><small>If you don't save, your changes will be lost.</small></p> -->
                  <div class="form-group">
                      <label>Select Report Type</label>
                      <select class="form-control" id="selectType">
                          <option value=""> -Select- </option>
                          <option value="Interim"> Interim </option>
                          <option value="Supplementary"> Supplementary </option>
                          <option value="Final"> Final </option>
                      </select>
                  </div>

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="button" data-id="" data-url="{{  url('/my/candidate/report/pdf',['id'=> base64_encode(8)]) }}" class="btn btn-info downloadReportBtn">Download Now</button>
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
      <script type="text/javascript">
         $(document).ready(function() {
          $('[data-toggle="tooltip"]').tooltip();
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
         
         });
         
      </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>


<script type="text/javascript">

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
  var path = "{{ url('/my/candidates/autocomplete') }}";
  $('input.search').typeahead({
      source:  function (search, process) {
      return $.get(path, { search: search }, function (data) {
        // alert(data);
              return process(data);
          });
      }
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
</script>




<script type="text/javascript">
   
   $(document).on('click','.reportsExportBox',function(e){    
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
                  url: "{{ url('/') }}"+'/my/reports/setData/?report_id='+report_id+'&reportType='+reportType+'&candidate_id='+candidate_id,
                  type: "get",
                  datatype: "html",
            })
            .done(function(data)
            {
                console.log(data);
                var path = "{{ url('/') }}"+"/my/candidate/report/pdf/"+report_id+'/'+reportType;
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
          // preferredCountries: ["ae", "in"],
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
