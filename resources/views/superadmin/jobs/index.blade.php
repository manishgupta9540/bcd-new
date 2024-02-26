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
                        <h4 class="card-title mb-1"> Cases </h4>
                        <p> List of all cases </p>
                     </div>
                     <div class="col-md-4">
                        <div class="btn-group" style="float:right">
                           <!-- <button class="btn btn-secondary btn-lg dropdown-toggle" id="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   Actions  </button>
                              <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div> -->
                           <a class="btn btn-success  createJob" href="javascript:;" > <i class="fa fa-plus"></i> Add New </a>             
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="table-responsive">
                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">#No</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Case</th>
                                    <th scope="col">SLA</th>
                                    <th scope="col">Total Candidate</th>
                                    <th scope="col">QC Status</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if( count($jobs) > 0 )
                                 @foreach($jobs as $item)
                                 <tr>
                                    <th scope="row">{{ $item->id }}</th>
                                    <td><b>{{ Helper::company_name($item->business_id) }}</b>
                                       <br>
                                       ID: {{ $item->customer_id }}
                                    </td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->verification_type }}</td>
                                    <td>{{ $item->total_candidates }}</td>
                                    <td><span class="badge badge-danger">QC-1</span></td>
                                    <td><span class="badge badge-danger">Processing</span></td>
                                    <td>
                                       <a href="">
                                       <button class="btn btn-success" type="button">View</button>
                                       </a>
                                    </td>
                                 </tr>
                                 @endforeach
                                 @else
                                 <tr>
                                    <td scope="row" colspan="8">
                                       <h3 class="text-center">No record!</h3>
                                    </td>
                                 </tr>
                                 @endif
                              </tbody>
                           </table>
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
