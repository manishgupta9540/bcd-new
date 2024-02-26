@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">
<div class="row">
   <div class="card text-left">
      <div class="card-body" style="padding:0px">
         <div class="row">
            <div class="col-md-3 col-12">
               <div class="span10 offset1">
                  <div id="modalTab">
                     <div class="tab-content">
                        <div class="tab-pane active" id="about">
                           <img src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcRbezqZpEuwGSvitKy3wrwnth5kysKdRqBW54cAszm_wiutku3R" name="aboutme" width="140" height="140" border="0" class="img-circle">
                           <p class="m-0 text-24"> {{ $item->name}} <a class="text-success mr-2" href="#"><i class="nav-icon i-Pen-2 font-weight-bold" style="font-size: 10px;"></i></a></p>
                           <p class="text-muted m-0"> Verified </p>
                           <ul class="nav nav-tabs profile-nav mb-4" id="profileTab" role="tablist">
                              <li class="nav-item"><a class="nav-link" id="timeline-tab">  <i class="fa fa-tasks"> </i>  <br> Note</a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab">  <i class="fa fa-envelope"></i><br> Email</a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-phone-square" aria-hidden="true"></i> <br> Call </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-plus" aria-hidden="true"></i><br> Log </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-tasks" aria-hidden="true"></i><br> Task  </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-calendar" aria-hidden="true"></i> <br> Meet </a></li>
                           </ul>
                           <p class="text-left" style=" display: grid;"><strong> About This Account </strong><br>
                              <label> First Name  </label>
                              <?php 
                                 $last_name ="";
                                 $name = explode(' ', $item->name); 
                                 if( count($name ) >1 ){ $last_name = $name[1]; }
                                 ?>
                              <label> {{ $name[0] }}  </label>
                              <label> Last Name  </label>
                              <label>   {{$last_name}} </label>
                              <label> Email </label>
                              <label> {{ $item->email }}  </label>
                              <label>  Phone  </label>
                              <label> {{ $item->phone }}  </label>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-6 col-12" style="background: #f6f8fc;">
               <h4 class="card-title mb-3"> </h4>
               <ul class="nav nav-pills" id="myPillTab" role="tablist" style="border-bottom: 1px solid #cdd1d8;">
                  <li class="nav-item"><a class="nav-link active show" id="home-icon-pill" data-toggle="pill" href="#homePIll" role="tab" aria-controls="homePIll" aria-selected="true"> Activity </a></li>
                  <li class="nav-item"><a class="nav-link" id="profile-icon-pill" data-toggle="pill" href="#profilePIll" role="tab" aria-controls="profilePIll" aria-selected="false"> Notes </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" data-toggle="pill" href="#contactPIll" role="tab" aria-controls="contactPIll" aria-selected="false">  Emails </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" data-toggle="pill" href="#contactPIll" role="tab" aria-controls="contactPIll" aria-selected="false">  Call </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" data-toggle="pill" href="#contactPIll" role="tab" aria-controls="contactPIll" aria-selected="false">  Task </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" data-toggle="pill" href="#contactPIll" role="tab" aria-controls="contactPIll" aria-selected="false">  Meeting </a></li>
               </ul>
               <div class="tab-content" id="myPillTabContent">
                  <div class="row" style="margin-bottom:15px">
                     <div class="col-md-2">
                        <div class="search-bar" style="padding: 10px 0px;">
                           Filter By :
                        </div>
                     </div>
                     <div class="col">
                        <button class="btn  btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;">  Filter Activity   </button>
                        <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                        <button class="btn  btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;">  All Users </button>
                        <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                        <button class="btn  btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;">  All Items </button>
                        <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                     </div>
                     <div class="search-bar" style="padding: 10px 0px;">
                        <i class="search-icon text-muted i-Magnifi-Glass1"></i>                   
                     </div>
                  </div>
                  <div class="tab-pane fade active show" id="homePIll" role="tabpanel" aria-labelledby="home-icon-pill">
                     <div class="inbox-main-sidebar-container sidebar-container" data-sidebar-container="main">
                        <div class="inbox-main-content sidebar-content" data-sidebar-content="main">
                           <!-- SECONDARY SIDEBAR CONTAINER-->
                           <div class="inbox-secondary-sidebar-container box-shadow-1 sidebar-container" data-sidebar-container="secondary">
                              <!-- Secondary Inbox sidebar-->
                              <div class="inbox-secondary-sidebar perfect-scrollbar rtl-ps-none ps sidebar" data-sidebar="secondary" style="left: 0px;">
                                 <i class="sidebar-close i-Close" data-sidebar-toggle="secondary"></i>
                                 <div class="mail-item">
                                    <div class="avatar" style="width:10%"> <i class="fa fa-phone" aria-hidden="true"></i></div>
                                    <div class="col-xs-6 details">
                                       <span class="name text-muted"><b> Call </b></span>
                                       <p class="m-0"> will talk after some time </p>
                                    </div>
                                    <div class="col-xs-4 date"><span class="text-muted">20 Dec at 1GMT +5:30 </span></div>
                                 </div>
                                 <div class="mail-item">
                                    <div class="avatar"> </div>
                                    <div class="col-xs-6 details">
                                       <span class="name text-muted"> Outcome </span>
                                       <button class="btn  btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;margin-left: -15px;font-size: 12px;">  Select an outcome  </button>
                                       <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                                    </div>
                                    <div class="col-xs-6 details">
                                       <span class="name text-muted"> Type</span>
                                       <button class="btn  btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;margin-left: -15px;font-size: 12px;">  Select all type  </button>
                                       <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                                    </div>
                                    <div class="col-xs-6 details"><span class="name text-muted"> Duration </span>
                                       <span class="text-muted"> 5:30 </span>
                                    </div>
                                 </div>
                                 <div class="mail-item">
                                    <div class="avatar"><img src="images/1.jpg" alt=""></div>
                                    <div class="col-xs-9 details">
                                       <span class="name text-muted">John Doe(+9199999999)</span>
                                       <p class="m-0"> to prakash (+9199999999)</p>
                                    </div>
                                 </div>
                                 <div class="mail-item" style="padding: 10px;">
                                    <div class="col-xs-9 details"> 
                                       <a href=""> <i class="fa fa-comment" aria-hidden="true"></i>  To add Comments </a>
                                    </div>
                                 </div>
                                 <div class="mail-item">
                                    <div class="avatar"><img src="images/1.jpg" alt=""></div>
                                    <div class="col-xs-6 details">
                                       <span class="name text-muted">John Doe</span>
                                       <p class="m-0">Confirm your email</p>
                                    </div>
                                    <div class="col-xs-3 date"><span class="text-muted">20 Dec 2018</span></div>
                                 </div>
                                 <div class="mail-item">
                                    <div class="avatar"><img src="images/1.jpg" alt=""></div>
                                    <div class="col-xs-6 details">
                                       <span class="name text-muted">John Doe</span>
                                       <p class="m-0">Confirm your email</p>
                                    </div>
                                    <div class="col-xs-3 date"><span class="text-muted">20 Dec 2018</span></div>
                                 </div>
                                 <div class="mail-item">
                                    <div class="avatar"><img src="images/1.jpg" alt=""></div>
                                    <div class="col-xs-6 details">
                                       <span class="name text-muted">John Doe</span>
                                       <p class="m-0">Confirm your email</p>
                                    </div>
                                    <div class="col-xs-3 date"><span class="text-muted">20 Dec 2018</span></div>
                                 </div>
                                 <div class="mail-item">
                                    <div class="avatar"><img src="images/1.jpg" alt=""></div>
                                    <div class="col-xs-6 details">
                                       <span class="name text-muted">John Doe</span>
                                       <p class="m-0">Confirm your email</p>
                                    </div>
                                    <div class="col-xs-3 date"><span class="text-muted">20 Dec 2018</span></div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="profilePIll" role="tabpanel" aria-labelledby="profile-icon-pill">
                  </div>
                  <div class="tab-pane fade" id="contactPIll" role="tabpanel" aria-labelledby="contact-icon-pill"> 
                  </div>
               </div>
            </div>
            <div class="col-md-3 col-6">
               <div class="inbox-main-sidebar-container sidebar-container" data-sidebar-container="main">
                  <div class="inbox-main-content sidebar-content" data-sidebar-content="main">
                     <!-- SECONDARY SIDEBAR CONTAINER-->
                     <div class="inbox-secondary-sidebar-container box-shadow-1 sidebar-container" data-sidebar-container="secondary" style="box-shadow: none;">
                        <!-- Secondary Inbox sidebar-->
                        <div class="inbox-secondary-sidebar perfect-scrollbar rtl-ps-none ps sidebar" data-sidebar="secondary" style="left: 0px;">
                           <i class="sidebar-close i-Close" data-sidebar-toggle="secondary"></i>
                           <div class="mail-item">
                              <div class="col-xs-9 details" style="width:80%">
                                 <span class="name text-muted"><b><i class="fa fa-caret-down" aria-hidden="true"></i>  Company(0) </b></span>
                                 <p class="m-0"> will talk after some time </p>
                                 <p class="m-0"> will talk after some time </p>
                              </div>
                              <div class="col-xs-3 date"> <a href=""> <i class="fa fa-plus" aria-hidden="true"></i> add </a></div>
                           </div>
                           <div class="mail-item">
                              <div class="col-xs-9 details" style="width:80%">
                                 <span class="name text-muted"><b><i class="fa fa-caret-down" aria-hidden="true"></i>  Company(0) </b></span>
                                 <p class="m-0"> will talk after some time </p>
                                 <p class="m-0"> will talk after some time </p>
                              </div>
                              <div class="col-xs-3 date"> <a href=""> <i class="fa fa-plus" aria-hidden="true"></i> add </a></div>
                           </div>
                           <div class="mail-item">
                              <div class="col-xs-9 details" style="width:80%">
                                 <span class="name text-muted"><b><i class="fa fa-caret-down" aria-hidden="true"></i>  Company(0) </b></span>
                                 <p class="m-0"> will talk after some time </p>
                                 <p class="m-0"> will talk after some time </p>
                              </div>
                              <div class="col-xs-3 date"> <a href=""> <i class="fa fa-plus" aria-hidden="true"></i> add </a></div>
                           </div>
                           <div class="mail-item">
                              <div class="col-xs-9 details" style="width:80%">
                                 <span class="name text-muted"><b><i class="fa fa-caret-down" aria-hidden="true"></i>  Company(0) </b></span>
                                 <p class="m-0"> will talk after some time </p>
                                 <p class="m-0"> will talk after some time </p>
                              </div>
                              <div class="col-xs-3 date"> <a href=""> <i class="fa fa-plus" aria-hidden="true"></i> add </a></div>
                           </div>
                           <div class="mail-item">
                              <div class="col-xs-9 details" style="width:80%">
                                 <span class="name text-muted"><b><i class="fa fa-caret-down" aria-hidden="true"></i>  Company(0) </b></span>
                                 <p class="m-0"> will talk after some time </p>
                                 <p class="m-0"> will talk after some time </p>
                              </div>
                              <div class="col-xs-3 date"> <a href=""> <i class="fa fa-plus" aria-hidden="true"></i> add </a></div>
                           </div>
                           <div class="mail-item">
                              <div class="col-xs-9 details" style="width:80%">
                                 <span class="name text-muted"><b><i class="fa fa-caret-down" aria-hidden="true"></i>  Company(0) </b></span>
                                 <p class="m-0"> will talk after some time </p>
                                 <p class="m-0"> will talk after some time </p>
                              </div>
                              <div class="col-xs-3 date"> <a href=""> <i class="fa fa-plus" aria-hidden="true"></i> add </a></div>
                           </div>
                           <div class="mail-item">
                              <div class="col-xs-9 details" style="width:80%">
                                 <span class="name text-muted"><b><i class="fa fa-caret-down" aria-hidden="true"></i>  Company(0) </b></span>
                                 <p class="m-0"> will talk after some time </p>
                                 <p class="m-0"> will talk after some time </p>
                              </div>
                              <div class="col-xs-3 date"> <a href=""> <i class="fa fa-plus" aria-hidden="true"></i> add </a></div>
                           </div>
                           <div class="mail-item">
                              <div class="col-xs-9 details" style="width:80%">
                                 <span class="name text-muted"><b><i class="fa fa-caret-down" aria-hidden="true"></i>  Company(0) </b></span>
                                 <p class="m-0"> will talk after some time </p>
                                 <p class="m-0"> will talk after some time </p>
                              </div>
                              <div class="col-xs-3 date"> <a href=""> <i class="fa fa-plus" aria-hidden="true"></i> add </a></div>
                           </div>
                           <div class="mail-item">
                              <div class="col-xs-9 details" style="width:80%">
                                 <span class="name text-muted"><b><i class="fa fa-caret-down" aria-hidden="true"></i>  Company(0) </b></span>
                                 <p class="m-0"> will talk after some time </p>
                                 <p class="m-0"> will talk after some time </p>
                              </div>
                              <div class="col-xs-3 date"> <a href=""> <i class="fa fa-plus" aria-hidden="true"></i> add </a></div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
