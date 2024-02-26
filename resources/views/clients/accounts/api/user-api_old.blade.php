@extends('layouts.client')
<style>
   /* .table tr {
      cursor: pointer;
   } */
   .table{
      background-color: #fff !important;
   }
   .hedding h1{
      color:#fff;
      font-size:25px;
   }
   .main-section{
      margin-top: 120px;
   }
   .hiddenRow {
       padding: 0 4px !important;
       background-color: #eeeeee;
       font-size: 13px;
   }
   .accordian-body span{
      color:#a2a2a2 !important;
   }
   </style>
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">

         <!-- ============Breadcrumb ============= -->
   <div class="row">
      <div class="col-sm-11">
         <ul class="breadcrumb">
         <li>
         <a href="{{ url('/my/home') }}">Dashboard</a>
         </li>
         <li>Instant Verification</li>
         </ul>
      </div>
      <!-- ============Back Button ============= -->
      <div class="col-sm-1 back-arrow">
         <div class="text-right">
            <a href="{{ url()->previous() }}"> <i class="fas fa-arrow-circle-left fa-2x"></i></a>
         </div>
      </div>
   </div>   
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Accounts/Instant Verification </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         
            <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('clients.accounts.sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style="background:#fff">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 ">
                              
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">Instant Verification Usage</h4>
                                       <p class="pb-border"> Instant Verification Usage overview </p>
                                    </div>
                                    <div class="col-md-6 text-right">
                                       <!-- <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a> -->
                                    </div>

                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                   <th class="text-center" width="5%">#</th>
                                                    <th>Name</th>
                                                    <th>No of hits</th>
                                                    <th>Total Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                             @if($aadhar!=NULL || $pan!=NULL || $voter_id!=NULL || $rc!=NULL || $dl!=NULL || $passport!=NULL || $bank!=NULL || $gst!=NULL || $telecom!=NULL || $e_court!=NULL || $upi!=NULL || $cin!=NULL)
                                                @if($aadhar!=NULL)  
                                                   <tr>
                                                      <td class="text-center">
                                                         <a data-toggle="collapse" data-target="#demo{{$aadhar->service_id}}" class="accordion-toggle btn btn-link text-info " href="javascript:;" style="font-size: 14px;">
                                                            <i class="fas fa-angle-double-down"></i>
                                                         </a>
                                                      </td>
                                                      <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($aadhar->service_id)])}}">{{$aadhar->name}}</a></td>
                                                      <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($aadhar->service_id)])}}">{{$aadhar->no_of_hits}}</a></td>
                                                      <td><i class="fas fa-rupee-sign"></i> {{$aadhar->total_price}} </td>
                                                      <td class="">
                                                         <button class="btn btn-outline-info downloadDetails" data-service="{{base64_encode($aadhar->service_id)}}" title="Download"><i class="fas fa-download"></i></button>
                                                         <span><a class="btn btn-outline-dark" href="{{url('/my/api-usage/details',['id'=>base64_encode($aadhar->service_id)])}}" title="Preview Details"><i class="far fa-eye"></i></a></span>
                                                      </td>
                                                   </tr>
                                                   <tr>
                                                      {{-- <th>#</th> --}}
                                                      <?php 
                                                         $service_name=Helper::get_service_name($aadhar->service_id); 

                                                      ?>
                                                      <td class="hiddenRow" colspan="5">
                                                         <div class="accordian-body collapse p-3" id="demo{{$aadhar->service_id}}">
                                                            <div class="row">
                                                               <div class="col-md-6">
                                                                  <h4 class="card-title mb-1 mt-3">{{$service_name}}</h4>
                                                                  <p class="pb-border"> Usage details of last 7 days </p>
                                                               </div>
                                                               <div class="col-sm-12">
                                                                  <table class="table table-bordered">
                                                                     <thead class="thead-dark">
                                                                         <tr>
                                                                             <th>Aadhar No.</th>
                                                                             <th>Used By</th>
                                                                             <th>Date & Time</th>
                                                                             <th>Price</th>
                                                                         </tr>
                                                                     </thead>
                                                                     <tbody>
                                                                        <?php $data=Helper::api_details(Auth::user()->business_id,$aadhar->service_id,'coc') ?>
                                                                        {{-- {{dd($data)}} --}}
                                                                        @if($data!=NULL && count($data)>0)
                                                                           @foreach ($data as $key => $d)
                                                                              <tr>
                                                                                 <td>{{$d->aadhar_number}}</td>
                                                                                 <td>{{Helper::user_name($d->user_id)}}</td>
                                                                                 <td>{{date('d-F-Y h:i A',strtotime($d->created_at))}}</td>
                                                                                 <td><i class="fas fa-rupee-sign"></i> {{$d->price}}</td>
                                                                              </tr>
                                                                           @endforeach
                                                                        @else
                                                                           <tr class="text-center">
                                                                              <td colspan="5">No Data found</td>
                                                                           </tr>
                                                                        @endif
                                                                     </tbody>
                                                                  </table>
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </td>
                                                   </tr>
                                                @endif
                                                @if($pan!=NULL)     
                                                <tr>
                                                   <td class="text-center">
                                                      <a data-toggle="collapse" data-target="#demo{{$pan->service_id}}" class="accordion-toggle btn btn-link text-info " href="javascript:;" style="font-size: 14px;">
                                                         <i class="fas fa-angle-double-down"></i>
                                                      </a>
                                                   </td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($pan->service_id)])}}">{{$pan->name}}</a></td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($pan->service_id)])}}">{{$pan->no_of_hits}}</a></td>
                                                   <td> <i class="fas fa-rupee-sign"></i> {{$pan->total_price}} </td>
                                                   <td class="">
                                                      <button class="btn btn-outline-info downloadDetails" data-service="{{base64_encode($pan->service_id)}}" title="Download"><i class="fas fa-download"></i></button>
                                                      <span><a class="btn btn-outline-dark" href="{{url('/my/api-usage/details',['id'=>base64_encode($pan->service_id)])}}" title="Preview Details"><i class="far fa-eye"></i></a></span>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   {{-- <th>#</th> --}}
                                                   <?php 
                                                      $service_name=Helper::get_service_name($pan->service_id); 

                                                   ?>
                                                   <td class="hiddenRow" colspan="5">
                                                      <div class="accordian-body collapse p-3" id="demo{{$pan->service_id}}">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <h4 class="card-title mb-1 mt-3">{{$service_name}}</h4>
                                                               <p class="pb-border"> Usage details of last 7 days </p>
                                                            </div>
                                                            <div class="col-sm-12">
                                                               <table class="table table-bordered">
                                                                  <thead class="thead-dark">
                                                                      <tr>
                                                                          <th>PAN No.</th>
                                                                          <th>Name</th>
                                                                          <th>Used By</th>
                                                                          <th>Date & Time</th>
                                                                          <th>Price</th>
                                                                      </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                     <?php $data=Helper::api_details(Auth::user()->business_id,$pan->service_id,'coc') ?>
                                                                     {{-- {{dd($data)}} --}}
                                                                     @if($data!=NULL && count($data)>0)
                                                                        @foreach ($data as $key => $d)
                                                                           <tr>
                                                                              <td>{{$d->pan_number}}</td>
                                                                              <td>{{ucfirst($d->full_name)}}</td>
                                                                              <td>{{Helper::user_name($d->user_id)}}</td>
                                                                              <td>{{date('d-F-Y h:i:s a',strtotime($d->created_at))}}</td>
                                                                              <td><i class="fas fa-rupee-sign"></i> {{$d->price}}</td>
                                                                           </tr>
                                                                        @endforeach
                                                                     @else
                                                                        <tr class="text-center">
                                                                           <td colspan="5">No Data found</td>
                                                                        </tr>
                                                                     @endif
                                                                  </tbody>
                                                               </table>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </td>
                                                </tr> 
                                                @endif
                                                @if($voter_id!=NULL)     
                                                <tr>
                                                   <td class="text-center">
                                                      <a data-toggle="collapse" data-target="#demo{{$voter_id->service_id}}" class="accordion-toggle btn btn-link text-info " href="javascript:;" style="font-size: 14px;">
                                                         <i class="fas fa-angle-double-down"></i>
                                                      </a>
                                                   </td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($voter_id->service_id)])}}">{{$voter_id->name}}</a></td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($voter_id->service_id)])}}">{{$voter_id->no_of_hits}}</a></td>
                                                   <td> <i class="fas fa-rupee-sign"></i> {{$voter_id->total_price}}</td>
                                                   <td class="">
                                                      <button class="btn btn-outline-info downloadDetails" data-service="{{base64_encode($voter_id->service_id)}}" title="Download"><i class="fas fa-download"></i></button>
                                                      <span><a class="btn btn-outline-dark" href="{{url('/my/api-usage/details',['id'=>base64_encode($voter_id->service_id)])}}" title="Preview Details"><i class="far fa-eye"></i></a></span>
                                                   </td>
                                                </tr> 
                                                <tr>
                                                   {{-- <th>#</th> --}}
                                                   <?php 
                                                      $service_name=Helper::get_service_name($voter_id->service_id); 

                                                   ?>
                                                   <td class="hiddenRow" colspan="5">
                                                      <div class="accordian-body collapse p-3" id="demo{{$voter_id->service_id}}">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <h4 class="card-title mb-1 mt-3">{{$service_name}}</h4>
                                                               <p class="pb-border"> Usage details of last 7 days </p>
                                                            </div>
                                                            <div class="col-sm-12">
                                                               <table class="table table-bordered">
                                                                  <thead class="thead-dark">
                                                                      <tr>
                                                                          <th>Voter ID No.</th>
                                                                          <th>Name</th>
                                                                          <th>Used By</th>
                                                                          <th>Date & Time</th>
                                                                          <th>Price</th>
                                                                      </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                     <?php $data=Helper::api_details(Auth::user()->business_id,$voter_id->service_id,'coc') ?>
                                                                     {{-- {{dd($data)}} --}}
                                                                     @if($data!=NULL && count($data)>0)
                                                                        @foreach ($data as $key => $d)
                                                                           <tr>
                                                                              <td>{{$d->voter_id_number}}</td>
                                                                              <td>{{ucfirst($d->full_name)}}</td>
                                                                              <td>{{Helper::user_name($d->user_id)}}</td>
                                                                              <td>{{date('d-F-Y h:i:s a',strtotime($d->created_at))}}</td>
                                                                              <td><i class="fas fa-rupee-sign"></i> {{$d->price}}</td>
                                                                           </tr>
                                                                        @endforeach
                                                                     @else
                                                                        <tr class="text-center">
                                                                           <td colspan="5">No Data found</td>
                                                                        </tr>
                                                                     @endif
                                                                  </tbody>
                                                               </table>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </td>
                                                </tr>
                                                @endif      
                                                @if($rc!=NULL)     
                                                <tr>
                                                   <td class="text-center">
                                                      <a data-toggle="collapse" data-target="#demo{{$rc->service_id}}" class="accordion-toggle btn btn-link text-info " href="javascript:;" style="font-size: 14px;">
                                                         <i class="fas fa-angle-double-down"></i>
                                                      </a>
                                                   </td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($rc->service_id)])}}">{{$rc->name}}</a></td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($rc->service_id)])}}">{{$rc->no_of_hits}}</a></td>
                                                   <td> <i class="fas fa-rupee-sign"></i> {{$rc->total_price}}</td>
                                                   <td class="">
                                                      <button class="btn btn-outline-info downloadDetails" data-service="{{base64_encode($rc->service_id)}}" title="Download"><i class="fas fa-download"></i></button>
                                                      <span><a class="btn btn-outline-dark" href="{{url('/my/api-usage/details',['id'=>base64_encode($rc->service_id)])}}" title="Preview Details"><i class="far fa-eye"></i></a></span>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   {{-- <th>#</th> --}}
                                                   <?php 
                                                      $service_name=Helper::get_service_name($rc->service_id); 

                                                   ?>
                                                   <td class="hiddenRow" colspan="5">
                                                      <div class="accordian-body collapse p-3" id="demo{{$rc->service_id}}">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <h4 class="card-title mb-1 mt-3">{{$service_name}}</h4>
                                                               <p class="pb-border"> Usage details of last 7 days </p>
                                                            </div>
                                                            <div class="col-sm-12">
                                                               <table class="table table-bordered">
                                                                  <thead class="thead-dark">
                                                                      <tr>
                                                                          <th>RC No.</th>
                                                                          <th>Name</th>
                                                                          <th>Used By</th>
                                                                          <th>Date & Time</th>
                                                                          <th>Price</th>
                                                                      </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                     <?php $data=Helper::api_details(Auth::user()->business_id,$rc->service_id,'coc') ?>
                                                                     {{-- {{dd($data)}} --}}
                                                                     @if($data!=NULL && count($data)>0)
                                                                        @foreach ($data as $key => $d)
                                                                           <tr>
                                                                              <td>{{$d->rc_number}}</td>
                                                                              <td>{{ucfirst($d->owner_name)}}</td>
                                                                              <td>{{Helper::user_name($d->user_id)}}</td>
                                                                              <td>{{date('d-F-Y h:i:s a',strtotime($d->created_at))}}</td>
                                                                              <td><i class="fas fa-rupee-sign"></i> {{$d->price}}</td>
                                                                           </tr>
                                                                        @endforeach
                                                                     @else
                                                                        <tr class="text-center">
                                                                           <td colspan="5">No Data found</td>
                                                                        </tr>
                                                                     @endif
                                                                  </tbody>
                                                               </table>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </td>
                                                </tr>   
                                                @endif   
                                                @if($dl!=NULL)     
                                                <tr>
                                                   <td class="text-center">
                                                      <a data-toggle="collapse" data-target="#demo{{$dl->service_id}}" class="accordion-toggle btn btn-link text-info " href="javascript:;" style="font-size: 14px;">
                                                         <i class="fas fa-angle-double-down"></i>
                                                      </a>
                                                   </td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($dl->service_id)])}}">{{$dl->name}}</a></td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($dl->service_id)])}}">{{$dl->no_of_hits}}</a></td>
                                                   <td> <i class="fas fa-rupee-sign"></i> {{$dl->total_price}}</td>
                                                   <td class="">
                                                      <button class="btn btn-outline-info downloadDetails" data-service="{{base64_encode($dl->service_id)}}" title="Download"><i class="fas fa-download"></i></button>
                                                      <span><a class="btn btn-outline-dark" href="{{url('/my/api-usage/details',['id'=>base64_encode($dl->service_id)])}}" title="Preview Details"><i class="far fa-eye"></i></a></span>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   {{-- <th>#</th> --}}
                                                   <?php 
                                                      $service_name=Helper::get_service_name($dl->service_id); 

                                                   ?>
                                                   <td class="hiddenRow" colspan="5">
                                                      <div class="accordian-body collapse p-3" id="demo{{$dl->service_id}}">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <h4 class="card-title mb-1 mt-3">{{$service_name}}</h4>
                                                               <p class="pb-border"> Usage details of last 7 days </p>
                                                            </div>
                                                            <div class="col-sm-12">
                                                               <table class="table table-bordered">
                                                                  <thead class="thead-dark">
                                                                      <tr>
                                                                          <th>DL No.</th>
                                                                          <th>Name</th>
                                                                          <th>Used By</th>
                                                                          <th>Date & Time</th>
                                                                          <th>Price</th>
                                                                      </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                     <?php $data=Helper::api_details(Auth::user()->business_id,$dl->service_id,'coc') ?>
                                                                     {{-- {{dd($data)}} --}}
                                                                     @if($data!=NULL && count($data)>0)
                                                                        @foreach ($data as $key => $d)
                                                                           <tr>
                                                                              <td>{{$d->dl_number}}</td>
                                                                              <td>{{ucfirst($d->name)}}</td>
                                                                              <td>{{Helper::user_name($d->user_id)}}</td>
                                                                              <td>{{date('d-F-Y h:i:s a',strtotime($d->created_at))}}</td>
                                                                              <td><i class="fas fa-rupee-sign"></i> {{$d->price}}</td>
                                                                           </tr>
                                                                        @endforeach
                                                                     @else
                                                                        <tr class="text-center">
                                                                           <td colspan="5">No Data found</td>
                                                                        </tr>
                                                                     @endif
                                                                  </tbody>
                                                               </table>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </td>
                                                </tr>  
                                                @endif  
                                                @if($passport!=NULL)     
                                                <tr>
                                                   <td class="text-center">
                                                      <a data-toggle="collapse" data-target="#demo{{$passport->service_id}}" class="accordion-toggle btn btn-link text-info " href="javascript:;" style="font-size: 14px;">
                                                         <i class="fas fa-angle-double-down"></i>
                                                      </a>
                                                   </td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($passport->service_id)])}}">{{$passport->name}}</a></td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($passport->service_id)])}}">{{$passport->no_of_hits}}</a></td>
                                                   <td> <i class="fas fa-rupee-sign"></i> {{$passport->total_price}}</td>
                                                   <td class="">
                                                      <button class="btn btn-outline-info downloadDetails" data-service="{{base64_encode($passport->service_id)}}" title="Download"><i class="fas fa-download"></i></button>
                                                      <span><a class="btn btn-outline-dark" href="{{url('/my/api-usage/details',['id'=>base64_encode($passport->service_id)])}}" title="Preview Details"><i class="far fa-eye"></i></a></span>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   {{-- <th>#</th> --}}
                                                   <?php 
                                                      $service_name=Helper::get_service_name($passport->service_id); 

                                                   ?>
                                                   <td class="hiddenRow" colspan="5">
                                                      <div class="accordian-body collapse p-3" id="demo{{$passport->service_id}}">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <h4 class="card-title mb-1 mt-3">{{$service_name}}</h4>
                                                               <p class="pb-border"> Usage details of last 7 days </p>
                                                            </div>
                                                            <div class="col-sm-12">
                                                               <table class="table table-bordered">
                                                                  <thead class="thead-dark">
                                                                      <tr>
                                                                          <th>Passport No.</th>
                                                                          <th>Name</th>
                                                                          <th>Used By</th>
                                                                          <th>Date & Time</th>
                                                                          <th>Price</th>
                                                                      </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                     <?php $data=Helper::api_details(Auth::user()->business_id,$passport->service_id,'coc') ?>
                                                                     {{-- {{dd($data)}} --}}
                                                                     @if($data!=NULL && count($data)>0)
                                                                        @foreach ($data as $key => $d)
                                                                           <tr>
                                                                              <td>{{$d->passport_number}}</td>
                                                                              <td>{{ucfirst($d->full_name)}}</td>
                                                                              <td>{{Helper::user_name($d->user_id)}}</td>
                                                                              <td>{{date('d-F-Y h:i:s a',strtotime($d->created_at))}}</td>
                                                                              <td><i class="fas fa-rupee-sign"></i> {{$d->price}}</td>
                                                                           </tr>
                                                                        @endforeach
                                                                     @else
                                                                        <tr class="text-center">
                                                                           <td colspan="5">No Data found</td>
                                                                        </tr>
                                                                     @endif
                                                                  </tbody>
                                                               </table>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </td>
                                                </tr>  
                                                @endif 
                                                @if($bank!=NULL)     
                                                <tr>
                                                   <td class="text-center">
                                                      <a data-toggle="collapse" data-target="#demo{{$bank->service_id}}" class="accordion-toggle btn btn-link text-info " href="javascript:;" style="font-size: 14px;">
                                                         <i class="fas fa-angle-double-down"></i>
                                                      </a>
                                                   </td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($bank->service_id)])}}">{{$bank->name}}</a></td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($bank->service_id)])}}">{{$bank->no_of_hits}}</a></td>
                                                   <td> <i class="fas fa-rupee-sign"></i> {{$bank->total_price}}</td>
                                                   <td class="">
                                                      <button class="btn btn-outline-info downloadDetails" data-service="{{base64_encode($bank->service_id)}}" title="Download"><i class="fas fa-download"></i></button>
                                                      <span><a class="btn btn-outline-dark" href="{{url('/my/api-usage/details',['id'=>base64_encode($bank->service_id)])}}" title="Preview Details"><i class="far fa-eye"></i></a></span>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   {{-- <th>#</th> --}}
                                                   <?php 
                                                      $service_name=Helper::get_service_name($bank->service_id); 

                                                   ?>
                                                   <td class="hiddenRow" colspan="5">
                                                      <div class="accordian-body collapse p-3" id="demo{{$bank->service_id}}">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <h4 class="card-title mb-1 mt-3">{{$service_name}}</h4>
                                                               <p class="pb-border"> Usage details of last 7 days </p>
                                                            </div>
                                                            <div class="col-sm-12">
                                                               <table class="table table-bordered">
                                                                  <thead class="thead-dark">
                                                                      <tr>
                                                                          <th>Bank Account No.</th>
                                                                          <th>Name</th>
                                                                          <th>Used By</th>
                                                                          <th>Date & Time</th>
                                                                          <th>Price</th>
                                                                      </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                     <?php $data=Helper::api_details(Auth::user()->business_id,$bank->service_id,'coc') ?>
                                                                     {{-- {{dd($data)}} --}}
                                                                     @if($data!=NULL && count($data)>0)
                                                                        @foreach ($data as $key => $d)
                                                                           <tr>
                                                                              <td>{{$d->account_number}}</td>
                                                                              <td>{{ucfirst($d->full_name)}}</td>
                                                                              <td>{{Helper::user_name($d->user_id)}}</td>
                                                                              <td>{{date('d-F-Y h:i:s a',strtotime($d->created_at))}}</td>
                                                                              <td><i class="fas fa-rupee-sign"></i> {{$d->price}}</td>
                                                                           </tr>
                                                                        @endforeach
                                                                     @else
                                                                        <tr class="text-center">
                                                                           <td colspan="5">No Data found</td>
                                                                        </tr>
                                                                     @endif
                                                                  </tbody>
                                                               </table>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </td>
                                                </tr>  
                                                @endif 
                                                @if($gst!=NULL)     
                                                <tr>
                                                   <td class="text-center">
                                                      <a data-toggle="collapse" data-target="#demo{{$gst->service_id}}" class="accordion-toggle btn btn-link text-info " href="javascript:;" style="font-size: 14px;">
                                                         <i class="fas fa-angle-double-down"></i>
                                                      </a>
                                                   </td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($gst->service_id)])}}">{{$gst->name}}</a></td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($gst->service_id)])}}">{{$gst->no_of_hits}}</a></td>
                                                   <td><i class="fas fa-rupee-sign"></i> {{$gst->total_price}}</td>
                                                   <td class="">
                                                      <button class="btn btn-outline-info downloadDetails" data-service="{{base64_encode($gst->service_id)}}" title="Download"><i class="fas fa-download"></i></button>
                                                      <span><a class="btn btn-outline-dark" href="{{url('/my/api-usage/details',['id'=>base64_encode($gst->service_id)])}}" title="Preview Details"><i class="far fa-eye"></i></a></span>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   {{-- <th>#</th> --}}
                                                   <?php 
                                                      $service_name=Helper::get_service_name($gst->service_id); 

                                                   ?>
                                                   <td class="hiddenRow" colspan="5">
                                                      <div class="accordian-body collapse p-3" id="demo{{$gst->service_id}}">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <h4 class="card-title mb-1 mt-3">{{$service_name}}</h4>
                                                               <p class="pb-border"> Usage details of last 7 days </p>
                                                            </div>
                                                            <div class="col-sm-12">
                                                               <table class="table table-bordered">
                                                                  <thead class="thead-dark">
                                                                      <tr>
                                                                          <th>GST No.</th>
                                                                          <th>Name</th>
                                                                          <th>Used By</th>
                                                                          <th>Date & Time</th>
                                                                          <th>Price</th>
                                                                      </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                     <?php $data=Helper::api_details(Auth::user()->business_id,$gst->service_id,'coc') ?>
                                                                     {{-- {{dd($data)}} --}}
                                                                     @if($data!=NULL && count($data)>0)
                                                                        @foreach ($data as $key => $d)
                                                                           <tr>
                                                                              <td>{{$d->gst_number}}</td>
                                                                              <td>{{ucfirst($d->legal_name)}}</td>
                                                                              <td>{{Helper::user_name($d->user_id)}}</td>
                                                                              <td>{{date('d-F-Y h:i:s a',strtotime($d->created_at))}}</td>
                                                                              <td><i class="fas fa-rupee-sign"></i> {{$d->price}}</td>
                                                                           </tr>
                                                                        @endforeach
                                                                     @else
                                                                        <tr class="text-center">
                                                                           <td colspan="5">No Data found</td>
                                                                        </tr>
                                                                     @endif
                                                                  </tbody>
                                                               </table>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </td>
                                                </tr>  
                                                @endif
                                                @if($telecom!=NULL)     
                                                <tr>
                                                   <td class="text-center">
                                                      <a data-toggle="collapse" data-target="#demo{{$telecom->service_id}}" class="accordion-toggle btn btn-link text-info " href="javascript:;" style="font-size: 14px;">
                                                         <i class="fas fa-angle-double-down"></i>
                                                      </a>
                                                   </td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($telecom->service_id)])}}">{{$telecom->name}}</a></td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($telecom->service_id)])}}">{{$telecom->no_of_hits}}</a></td>
                                                   <td> <i class="fas fa-rupee-sign"></i> {{$telecom->total_price}}</td>
                                                   <td class="">
                                                      <button class="btn btn-outline-info downloadDetails" data-service="{{base64_encode($telecom->service_id)}}" title="Download"><i class="fas fa-download"></i></button>
                                                      <span><a class="btn btn-outline-dark" href="{{url('/my/api-usage/details',['id'=>base64_encode($telecom->service_id)])}}" title="Preview Details"><i class="far fa-eye"></i></a></span>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   {{-- <th>#</th> --}}
                                                   <?php 
                                                      $service_name=Helper::get_service_name($telecom->service_id); 

                                                   ?>
                                                   <td class="hiddenRow" colspan="5">
                                                      <div class="accordian-body collapse p-3" id="demo{{$telecom->service_id}}">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <h4 class="card-title mb-1 mt-3">{{$service_name}}</h4>
                                                               <p class="pb-border"> Usage details of last 7 days </p>
                                                            </div>
                                                            <div class="col-sm-12">
                                                               <table class="table table-bordered">
                                                                  <thead class="thead-dark">
                                                                      <tr>
                                                                          <th>Mobile No.</th>
                                                                          <th>Name</th>
                                                                          <th>Used By</th>
                                                                          <th>Date & Time</th>
                                                                          <th>Price</th>
                                                                      </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                     <?php $data=Helper::api_details(Auth::user()->business_id,$telecom->service_id,'coc') ?>
                                                                     {{-- {{dd($data)}} --}}
                                                                     @if($data!=NULL && count($data)>0)
                                                                        @foreach ($data as $key => $d)
                                                                           <tr>
                                                                              <td>{{$d->mobile_no}}</td>
                                                                              <td>{{ucfirst($d->full_name)}}</td>
                                                                              <td>{{Helper::user_name($d->user_id)}}</td>
                                                                              <td>{{date('d-F-Y h:i:s a',strtotime($d->created_at))}}</td>
                                                                              <td><i class="fas fa-rupee-sign"></i> {{$d->price}}</td>
                                                                           </tr>
                                                                        @endforeach
                                                                     @else
                                                                        <tr class="text-center">
                                                                           <td colspan="5">No Data found</td>
                                                                        </tr>
                                                                     @endif
                                                                  </tbody>
                                                               </table>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </td>
                                                </tr> 
                                                @endif
                                                @if($e_court!=NULL)     
                                                <tr>
                                                   <td class="text-center">
                                                      <a data-toggle="collapse" data-target="#demo{{$e_court->service_id}}" class="accordion-toggle btn btn-link text-info " href="javascript:;" style="font-size: 14px;">
                                                         <i class="fas fa-angle-double-down"></i>
                                                      </a>
                                                   </td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($e_court->service_id)])}}">{{$e_court->name}}</a></td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($e_court->service_id)])}}">{{$e_court->no_of_hits}}</a></td>
                                                   <td> <i class="fas fa-rupee-sign"></i> {{$e_court->total_price}}</td>
                                                   <td class="">
                                                      <button class="btn btn-outline-info downloadDetails" data-service="{{base64_encode($e_court->service_id)}}" title="Download"><i class="fas fa-download"></i></button>
                                                      <span><a class="btn btn-outline-dark" href="{{url('/my/api-usage/details',['id'=>base64_encode($e_court->service_id)])}}" title="Preview Details"><i class="far fa-eye"></i></a></span>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   {{-- <th>#</th> --}}
                                                   <?php 
                                                      $service_name=Helper::get_service_name($e_court->service_id); 

                                                   ?>
                                                   <td class="hiddenRow" colspan="5">
                                                      <div class="accordian-body collapse p-3" id="demo{{$e_court->service_id}}">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <h4 class="card-title mb-1 mt-3">{{$service_name}}</h4>
                                                               <p class="pb-border"> Usage details of last 7 days </p>
                                                            </div>
                                                            <div class="col-sm-12">
                                                               <table class="table table-bordered">
                                                                  <thead class="thead-dark">
                                                                      <tr>
                                                                          <th>Name</th>
                                                                          <th>Father Name</th>
                                                                          <th>Address</th>
                                                                          <th>Used By</th>
                                                                          <th>Date & Time</th>
                                                                          <th>Price</th>
                                                                      </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                     <?php $data=Helper::api_details(Auth::user()->business_id,$e_court->service_id,'coc') ?>
                                                                     {{-- {{dd($data)}} --}}
                                                                     @if($data!=NULL && count($data)>0)
                                                                        @foreach ($data as $key => $d)
                                                                           <tr>
                                                                              <td>{{ucfirst($d->name)}}</td>
                                                                              <td>{{ucfirst($d->father_name)}}</td>
                                                                              <td>{{$d->address}}</td>
                                                                              <td>{{Helper::user_name($d->user_id)}}</td>
                                                                              <td>{{date('d-F-Y h:i:s a',strtotime($d->created_at))}}</td>
                                                                              <td><i class="fas fa-rupee-sign"></i> {{$d->price}}</td>
                                                                           </tr>
                                                                        @endforeach
                                                                     @else
                                                                        <tr class="text-center">
                                                                           <td colspan="5">No Data found</td>
                                                                        </tr>
                                                                     @endif
                                                                  </tbody>
                                                               </table>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </td>
                                                </tr> 
                                                @endif
                                                @if($upi!=NULL)     
                                                <tr>
                                                   <td class="text-center">
                                                      <a data-toggle="collapse" data-target="#demo{{$upi->service_id}}" class="accordion-toggle btn btn-link text-info " href="javascript:;" style="font-size: 14px;">
                                                         <i class="fas fa-angle-double-down"></i>
                                                      </a>
                                                   </td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($upi->service_id)])}}">{{$upi->name}}</a></td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($upi->service_id)])}}">{{$upi->no_of_hits}}</a></td>
                                                   <td> <i class="fas fa-rupee-sign"></i> {{$upi->total_price}}</td>
                                                   <td class="">
                                                      <button class="btn btn-outline-info downloadDetails" data-service="{{base64_encode($upi->service_id)}}" title="Download"><i class="fas fa-download"></i></button>
                                                      <span><a class="btn btn-outline-dark" href="{{url('/my/api-usage/details',['id'=>base64_encode($upi->service_id)])}}" title="Preview Details"><i class="far fa-eye"></i></a></span>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   {{-- <th>#</th> --}}
                                                   <?php 
                                                      $service_name=Helper::get_service_name($upi->service_id); 

                                                   ?>
                                                   <td class="hiddenRow" colspan="5">
                                                      <div class="accordian-body collapse p-3" id="demo{{$upi->service_id}}">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <h4 class="card-title mb-1 mt-3">{{$service_name}}</h4>
                                                               <p class="pb-border"> Usage details of last 7 days </p>
                                                            </div>
                                                            <div class="col-sm-12">
                                                               <table class="table table-bordered">
                                                                  <thead class="thead-dark">
                                                                      <tr>
                                                                          <th>UPI ID</th>
                                                                          <th>Name</th>
                                                                          <th>Used By</th>
                                                                          <th>Date & Time</th>
                                                                          <th>Price</th>
                                                                      </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                     <?php $data=Helper::api_details(Auth::user()->business_id,$upi->service_id,'coc') ?>
                                                                     {{-- {{dd($data)}} --}}
                                                                     @if($data!=NULL && count($data)>0)
                                                                        @foreach ($data as $key => $d)
                                                                           <tr>
                                                                              <td>{{$d->upi_id}}</td>
                                                                              <td>{{ucfirst($d->name)}}</td>
                                                                              <td>{{Helper::user_name($d->user_id)}}</td>
                                                                              <td>{{date('d-F-Y h:i A',strtotime($d->created_at))}}</td>
                                                                              <td><i class="fas fa-rupee-sign"></i> {{$d->price}}</td>
                                                                           </tr>
                                                                        @endforeach
                                                                     @else
                                                                        <tr class="text-center">
                                                                           <td colspan="5">No Data found</td>
                                                                        </tr>
                                                                     @endif
                                                                  </tbody>
                                                               </table>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </td>
                                                </tr> 
                                                @endif
                                                @if($cin!=NULL)     
                                                <tr>
                                                   <td class="text-center">
                                                      <a data-toggle="collapse" data-target="#demo{{$cin->service_id}}" class="accordion-toggle btn btn-link text-info " href="javascript:;" style="font-size: 14px;">
                                                         <i class="fas fa-angle-double-down"></i>
                                                      </a>
                                                   </td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($cin->service_id)])}}">{{$cin->name}}</a></td>
                                                   <td><a class="text-info btn-lnk" href="{{url('/my/api-usage/details',['id'=>base64_encode($cin->service_id)])}}">{{$cin->no_of_hits}}</a></td>
                                                   <td> <i class="fas fa-rupee-sign"></i> {{$cin->total_price}}</td>
                                                   <td class="">
                                                      <button class="btn btn-outline-info downloadDetails" data-service="{{base64_encode($cin->service_id)}}" title="Download"><i class="fas fa-download"></i></button>
                                                      <span><a class="btn btn-outline-dark" href="{{url('/my/api-usage/details',['id'=>base64_encode($cin->service_id)])}}" title="Preview Details"><i class="far fa-eye"></i></a></span>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   {{-- <th>#</th> --}}
                                                   <?php 
                                                      $service_name=Helper::get_service_name($cin->service_id); 

                                                   ?>
                                                   <td class="hiddenRow" colspan="5">
                                                      <div class="accordian-body collapse p-3" id="demo{{$cin->service_id}}">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <h4 class="card-title mb-1 mt-3">{{$service_name}}</h4>
                                                               <p class="pb-border"> Usage details of last 7 days </p>
                                                            </div>
                                                            <div class="col-sm-12">
                                                               <table class="table table-bordered">
                                                                  <thead class="thead-dark">
                                                                      <tr>
                                                                          <th>CIN Number</th>
                                                                          <th>Company Name</th>
                                                                          <th>Used By</th>
                                                                          <th>Date & Time</th>
                                                                          <th>Price</th>
                                                                      </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                     <?php $data=Helper::api_details(Auth::user()->business_id,$cin->service_id,'coc') ?>
                                                                     {{-- {{dd($data)}} --}}
                                                                     @if($data!=NULL && count($data)>0)
                                                                        @foreach ($data as $key => $d)
                                                                           <tr>
                                                                              <td>{{$d->cin_number}}</td>
                                                                              <td>{{ucfirst($d->company_name)}}</td>
                                                                              <td>{{Helper::user_name($d->user_id)}}</td>
                                                                              <td>{{date('d-F-Y h:i A',strtotime($d->created_at))}}</td>
                                                                              <td><i class="fas fa-rupee-sign"></i> {{$d->price}}</td>
                                                                           </tr>
                                                                        @endforeach
                                                                     @else
                                                                        <tr class="text-center">
                                                                           <td colspan="5">No Data found</td>
                                                                        </tr>
                                                                     @endif
                                                                  </tbody>
                                                               </table>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </td>
                                                </tr> 
                                                @endif
                                             @else
                                             <tr>
                                                <td colspan="5" class="text-center">No Data Found</td>
                                             </tr> 
                                             @endif            
                                            </tbody>
                                        </table>
                                    </div>
                                 </div>
                                 <!-- ./billing detail -->
                                 
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

{{-- Modal for download api details    --}}

<div class="modal" id="download_api">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Download API Details</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/my/api-usage/download')}}" id="downloadapiFrm">
         @csrf
           <input type="hidden" name="service_id" id="service_id">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               <div class="form-group">
                     <label for="label_name">Select Type : </label>
                     <select class="form-control type" name="type" id="type">
                        <option value="">--Select--</option>
                        <option value="excel">Excel</option>
                        <option value="pdf">PDF</option>
                     </select>
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-type"></p> 
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info">Submit </button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

  <!-- Footer Start -->
  <div class="flex-grow-1"></div>
  
</div>
@stack('scripts')
<script type="text/javascript">
   
   $(document).ready(function() {
      $('.downloadDetails').click(function(){
         var service_id=$(this).attr('data-service');
         $('#service_id').val(service_id);
         $('#download_api').modal({
               backdrop: 'static',
               keyboard: false
         });
      });

      $(document).on('submit', 'form#downloadapiFrm', function (event) {
         event.preventDefault();
         //clearing the error msg
         $('p.error-container').html("");
         $('.form-control').removeClass('border-danger');
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");

         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {

                  console.log(response);
                  //show the form validates error
                  if(response.success==false ) {                              
                     for (control in response.errors) {  
                        $('.'+control).addClass('border-danger'); 
                        $('#error-' + control).html(response.errors[control]);
                     }
                  }
                  if(response.success==true)
                  {
                     window.open(response.url);
                     $('#download_api').modal('hide');
                  }
                  else
                  {
                     // window.location=response;
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  // alert("Error: " + errorThrown);
            }
         });
         return false;
      });
   });
                     
</script>  
@endsection
