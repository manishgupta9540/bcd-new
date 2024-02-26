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
                        
                        <li class="breadcrumb-item "><a href="javascript:history.go(-2)"   class="text-dark">Client</a></li>

                       
                        {{-- <li class="breadcrumb-item "><a href="{{ url('/app/customers/show',['id'=>base64_encode($item->id)])}}" class="text-dark">Client</a></li> --}}
                        <li class="breadcrumb-item active_text">Reports</li>
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
                           <li><strong>Contact Person :</strong>{{ $item->first_name.' '.$item->last_name }} </li>
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
                                        <th scope="col">#ID</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Report Date</th>
                                        <th scope="col">Candidate</th>
                                        <th scope="col">Report Type</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                        @if( count($data) > 0)
                                        @foreach($data as $key)
                                        <tr data-row="{{ $key->id }}">
                                            <th scope="row">{{ $key->id }}</th>
                                            <td>{{ Helper::company_name($key->business_id)}}</td>
                                            <td>{{ date('d-m-Y',strtotime($key->created_at) ) }}</td>
                                            <td class="candidateName">{{ $key->candidate_id.'-'.Helper::get_user_fullname($key->candidate_id)}}</td>
                                            <td>
                                                @if($key->report_type == 'manual')
                                                <span class="badge badge-info">
                                                {{ ucfirst($key->report_type) }}</span>
                                                @else
                                                <span class="badge badge-success">
                                                {{ ucfirst($key->report_type) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                            
                                                @if($key->status == 'incomplete')
                                                <span class="badge badge-danger">
                                                {{ ucfirst($key->status) }}</span><br>
                                                    @if($key->manual_input_status == 'input_file')
                                                        <a href="{{ url('candidate/report-generate',['id'=>  base64_encode($key->candidate_id) ]) }}" style='font-size:14px;' class="bnt-link">Generate Report</a> 
                                                    @endif
    
                                                @else
                                                <span class="badge badge-success">
                                                {{ ucfirst($key->status) }}</span>
                                                @endif
                                            
                                            </td>
                                            <td>
                                            @if($key->status == 'incomplete')
                                                <a href="{{ url('/app/customers/reports/edit',['id'=> base64_encode($key->candidate_id)]) }}">
                                                    <button class="btn btn-sm btn-info" type="button"> <i class="fa fa-edit"></i> Edit</button>
                                                </a>
                                            @else
                                                <!-- <a href="">
                                                    <button class="btn btn-sm btn-info" type="button"> <i class="fa fa-edit"></i> View</button>
                                                </a> -->
                                            @endif
                                            @if($key->status == 'completed')
                                                <a href="{{ url('/app/customers/reports/edit',['id'=> base64_encode($key->candidate_id),'old_id'=>Request::segment(5)]) }}"> 
                                                    <button class="btn btn-sm btn-info" type="button"> <i class="fa fa-edit"></i> Edit</button>
                                                </a>
                                                
                                                <button class="btn btn-sm btn-primary reportExportBox" data-id="{{  base64_encode($key->id) }}" type="button"> <i class="fa fa-download"></i> PDF</button>
                                            
                                            @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr><td colspan="7" class="text-center"> <h3>Report is not created yet!</h3> </td></tr>
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

