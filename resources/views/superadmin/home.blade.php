@extends('layouts.superadmin')

@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
<div class="main-content">          

             <div class="row">
             <div class="col-lg-10">
             <h3 class="mr-2"> Analytics Overview  </h3>
              </div>

              <div class="col-lg-2">
            <div class="btn-group" style="float: right;">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Last 30 days
                        </button>

                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px,27px, 0px);">
                        <a class="dropdown-item" href="#">Today Only</a>
                        <a class="dropdown-item" href="#">This Week</a>
                        <a class="dropdown-item" href="#">This Month</a>
                        <a class="dropdown-item" href="#">This Year</a>
                    </div>
                    </div>        
              </div>
              </div>
                
                <div class="row">
                    <!-- ICON BG-->
                    <div class="width20 widthmobi100">
                        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4">
                            <a href="Customer_List.php"><div class="card-body text-center">
                                <i class="fa fa-user"></i>
                                <div class="content">
                                 <p class="text-primary text-24 line-height-1 mb-2"> {{ $customers_count }} </p>
                                    <p class="text-muted mt-2 mb-0"> Customers </p>
                                   
                                </div>
                            </div></a>
                        </div>
                    </div>
                    <div class="width20 widthmobi100">
                        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4">
                        <a href="Customer_details.php"><div class="card-body text-center">
                                <i class="fa fa-user"></i>
                                <div class="content">
                                 <p class="text-primary text-24 line-height-1 mb-2"> 0 </p>
                                    <p class="text-muted mt-2 mb-0"> Candidates </p>
                                   
                                </div>
                            </div></a>
                        </div>
                    </div>
                    <div class="width20 widthmobi100">
                        <div class="card card-icon-bg card-icon-bg-2 o-hidden mb-4">
                        <a href="Job_varification.php"><div class="card-body text-center">
                                <i class="fa fa-check"></i>
                                <div class="content">
                                    <p class="text-primary text-24 line-height-1 mb-2"> 0 </p>
                                     <p class="text-muted mt-2 mb-0"> Cases </p>
                                </div>
                            </div></a>
                        </div>
                    </div>
                    <div class="width20 widthmobi100">
                        <div class="card card-icon-bg card-icon-bg-3 o-hidden mb-4">
                        <a href="varification_list.php"><div class="card-body text-center">
                                <i class="fa fa-book"></i>
                                <div class="content"> 
                                    <p class="text-primary text-24 line-height-1 mb-2">0 </p>
                                     <p class="text-muted mt-2 mb-0"> Verifications done </p>
                                </div>
                            </div></a>
                        </div>
                    </div>
                    {{-- <div class="width20 widthmobi100" style="margin-right:0px;">
                        <div class="card card-icon-bg card-icon-bg-4 o-hidden mb-4">
                        <a href="qcs.php"><div class="card-body text-center">
                                <i class="fa fa-paper-plane"></i>
                                <div class="content">
                                    <p class="text-primary text-24 line-height-1 mb-2">0 </p>
                                     <p class="text-muted mt-2 mb-0"> QCS </p>
                                </div>
                            </div></a>
                        </div>
                    </div> --}}
                </div>
                
                
             <div class="row">
             <div class="col-lg-10">
                <div class="btn-group">
                  <button class="btn  dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <h4> Most <span class="rm">Recent Cases <span> </h4>
                </button>
                   <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 27px, 0px);"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                </div>
              </div>

              <div class="col-lg-2">
                    <div class="btn-group" style="float: right;">
                     <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  Show <span class="alm"> all Sales </span>
                     </button>
                       <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 27px, 0px);"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                     </div>           
              </div>
              </div>
              
                <div class="row">
                <div class="col-lg-8 col-md-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-title">This Year Sales</div>
                                <div id="echartBar" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-title">Sales by Countries</div>
                                <div id="echartPie" style="height: 300px;"></div>
                            </div>
                        </div> 
                    </div>
                                        
                    
                </div>
 
            </div>
        </span>
      </div>
@endsection
