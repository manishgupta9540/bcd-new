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
                           <li><strong>Contact Person :</strong> {{ $item->first_name.' '.$item->last_name }}</li>
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
                           <div class="tab-pane fade active show" id="candidatetb1" role="tabpanel" aria-labelledby="candidatetab">
                              <div class="row" style="margin-bottom:15px">
                                 <div class="col-md-2">
                                    <div class="search-bar">
                                       <input type="text" placeholder="Search" autocomplete="off" style="padding: 5px;border-radius: 4px;background: #f6f8fc;">
                                    </div>
                                 </div>
                              </div>
                              <div class="table-responsive tableFixHead" style="height: 300px;">
                                 <table class="table table-bordered">
                                    <thead>
                                       <tr>
                                          <th scope="col">#</th>
                                          <th scope="col">Company Name</th>
                                          <th scope="col">Name</th>
                                          <th scope="col">Email</th>
                                          <th scope="col">Phone</th>
                                          <th scope="col">Status</th>
                                          <th scope="col">Action</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @if(count($candidates)>0)
                                       @foreach($candidates as $candidate)
                                       <tr>
                                          <th scope="row">BCD-{{$candidate->id}}</th>
                                          <td><b>{{$candidate->company_name}}</b></td>
                                          <td>{{$candidate->name}}</td>
                                          <td>{{$candidate->email}}</td>
                                          <td>{{$candidate->phone}}</td>
                                          <td></td>
                                          <td>
                                             <a href="{{ url('/app/candidate/show',['id'=>base64_encode($candidate->id),Request::segment(4)]) }}"><button class="btn btn-success btn-sm" type="button"> <i class="fa fa-eye"></i> View</button></a>

                                             {{-- <a href="{{ url('/app/candidates/show',['id'=>  base64_encode($candidate->id)]) }}"><button class="btn btn-success" type="button">View</button></a> --}}
                                          </td>
                                       </tr>
                                       @endforeach
                                       @else 
                                       <tr>
                                          <td colspan="7">
                                             <h3 class="text-center">Record is not available!</h3>
                                          </td>
                                       </tr>
                                       @endif
                                    </tbody>
                                 </table>
                              </div>
                           </div> 
                           <!-- 1st Tab Has Been End Here -->
                           
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
</div>
@endsection
