@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
      <div class="col-md-12">
         <div class="card text-left">
            <div class="card-body">
               <div class="row">
                  <div class="col-md-8">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item "><a href="{{ url('/app/customers')}}" class="text-dark">Customer</a></li>
                       
                        <li class="breadcrumb-item "><a href="javascript:history.go(-2)" class="text-dark">Client</a></li>

                       
                        {{-- <li class="breadcrumb-item "><a href="{{ url('/app/customers/show',['id'=>base64_encode($item->id)])}}" class="text-dark">Client</a></li> --}}
                        <li class="breadcrumb-item active_text">SLA</li>
                    </ol>
                     <h4 class="card-title mb-3"> Client </h4>
                     <p> Details of Client </p>
                  </div>
                  <div class="col-md-4">        
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <div class="details-box">
                        <ul>
                           <li><strong>Comapny Name :</strong> {{$item->company_name}}</li>
                           <li><strong>Contact Person :</strong> {{$item->contact_person}}</li>
                           <li><strong>Email :</strong> {{$item->email}}</li>
                           <li><strong>Phone :</strong> {{$item->phone}}</li>
                           <li><strong>Address :</strong> {{$item->address_line1.', '.$item->zipcode.' '.$item->city_name}}</li>
                        </ul>
                     </div>
                     <div class="table-box mt-40">
                         <!-- menu -->
                        {{-- @include('superadmin.customers.tab-menu-item') --}}
                        <!-- ./menu -->
                        <ul class="nav nav-tabs" id="myIconTab" role="tablist">
                           <li class="nav-item"><a class="nav-link " id="candidatetab" href="{{ url('/app/candidate/show',['id'=> base64_encode($item->id),'old_id'=>Request::segment(5)]) }}" role="tab" aria-controls="candidatetb1" aria-selected="true"> Candidates </a></li>
                           <li class="nav-item"><a class="nav-link  show" id="jobtab" data-toggle="tab" href="#jobtb1" role="tab" aria-controls="jobtb1" aria-selected="false"> Cases </a></li>
                           <li class="nav-item"><a class="nav-link" id="qctab" data-toggle="tab" href="#qctb1" role="tab" aria-controls="qctb1" aria-selected="false"> Checks </a></li>
                           <li class="nav-item"><a class="nav-link active" id="qctab"  href="{{ url('/app/candidates/sla',['id'=> base64_encode($item->id)]) }}" role="tab" > SLA </a></li>
                           <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='reports') active @endif " id="reports" href="{{ url('/app/candidate/reports/show',['id'=> base64_encode($item->id)]) }}" role="tab" > Reports </a></li>

                           <li class="nav-item"><a class="nav-link" id="paymenttab" data-toggle="tab" href="#paymenttb1" role="tab" aria-controls="paymenttb1" aria-selected="false"> Payments </a></li>
                        </ul>
                        <div class="tab-content" id="myIconTabContent">
                           <div class="tab-pane fade " id="candidatetb1" role="tabpanel" aria-labelledby="candidatetab">
                              <div class="row" style="margin-bottom:15px">
                                 <div class="col-md-2">
                                    <div class="search-bar">
                                       <input type="text" placeholder="Search" autocomplete="off" style="padding: 5px;border-radius: 4px;background: #f6f8fc;">
                                    </div>
                                 </div>
                              </div>
                              <div class="table-responsive tableFixHead" style="height: 300px;">
                              </div>
                           </div>
                           <!-- 1st Tab Has Been End Here -->
                           <!-- 2nd Tab Starts From Here -->
                           <div class="row" style="margin-bottom:15px">
                              <div class="col-md-2">
                                 <div class="search-bar">
                                    <input type="text" placeholder="Search" autocomplete="off" style="padding: 5px;border-radius: 4px;background: #f6f8fc;">
                                 </div>
                              </div>
                           </div>
                           <div class="tab-pane active show fade" id="jobtb1" role="tabpanel" aria-labelledby="jobtab">
                              <div class="table-responsive">
                                 <table class="table table-bordered">
                                    <thead>
                                       <tr>
                                          <th scope="col">#</th>
                                          <th scope="col">Job Name</th>
                                          <th scope="col">Verification type</th>
                                          <th scope="col">Status</th>
                                          <th scope="col">Action</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @if(count($sla)>0)
                                       @foreach($sla as $item)
                                       <tr>
                                          <th scope="row">1</th>
                                          <td>{{$item->title}}</td>
                                          <td> {{ Helper::get_sla_items($item->id)}} </td>
                                          <td><span class="badge badge-success">Active</span></td>
                                          <td>
                                             <a href="#"><button class="btn btn-sm btn-info" type="button">View</button></a>
                                          </td>
                                       </tr>
                                       @endforeach
                                       @else
                                       <tr>
                                          <td colspan="5" >
                                             <h3 class="text-center">Record is not available!</h3>
                                          </td>
                                       </tr>
                                       @endif
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <!-- 2nd Tab Has Been End Here -->
                           
                          
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
  
</div>
@endsection
