@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Account / Billing </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>
                 <a href="{{ url('/settings/general') }}">Accounts</a>
             </li>
             <li>MIS</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow">
             <div class="text-right">
             <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div>
      <div class="row">
               <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('admin.accounts.left-sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style="background:#fff">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                            <div class="col-sm-12">
                              
                                <!-- row -->
                                <div class="row">
                                   <div class="col-md-6">
                                      <h4 class="card-title mb-1 mt-3">MIS  </h4>
                                      <p class="pb-border"> MIS activity/history  </p>
                                   </div>
                                   {{-- <div class="col-md-6 text-right">
                                      <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a>
                                   </div> --}}
                                   <div class="col-md-6 pt-3">
                                      <div class="btn-group" style="float:right">     
                                         <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>
                                      </div>
                                   </div>
                                </div>
                                <div class="search-drop-field pb-3" id="search-drop">
                                   <div class="row">
                                       <div class="col-md-3 form-group mb-1">
                                           <label> From date </label>
                                           <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
                                       </div>
                                       <div class="col-md-3 form-group mb-1">
                                           <label> To date </label>
                                           <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
                                       </div>
                                       
                                       <div class="col-md-2">
                                       <button class="btn btn-primary search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                       </div>
                                   </div>
                               </div>
                               <div id="candidatesResult">
                                   @include('admin.accounts.mis.ajax')
                               </div>
                                <!-- ./business detail -->
                                
                          </div>
                        </section>
                        <!-- ./section -->
                        <!--  -->
                        <!-- ./section -->
                     </div>
                  </div>
                  <!-- end right sec -->
               
            
         
      </div>
   </div>
</div>

@endsection
