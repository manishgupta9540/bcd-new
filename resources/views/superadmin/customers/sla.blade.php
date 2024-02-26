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
                        
                        <li class="breadcrumb-item active_text">Client</li>
                    </ol>
                     <h4 class="card-title mb-3"> Customer </h4>
                     <p> Details of customer </p>
                  </div>
                  <div class="col-md-4">        
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <div class="details-box">
                        <ul>
                           <li><strong>Comapny Name :</strong> {{$item->company_name}}</li>
                           <li><strong>Contact Person :</strong> {{ $item->first_name.' '.$item->last_name }} </li>
                           <li><strong>Email :</strong> {{$item->email}}</li>
                           <li><strong>Phone :</strong> {{$item->phone}}</li>
                           <li><strong>Address :</strong> {{$item->address_line1.', '.$item->zipcode.' '.$item->city_name}}</li>
                        </ul>
                     </div>
                     <div class="table-box mt-40">
                        <!-- menu -->
                        @include('superadmin.customers.tab-menu-item')
                        <!-- ./menu -->
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
