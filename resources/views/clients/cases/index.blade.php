@extends('layouts.client')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">          
 
        <div class="row">
            <div class="card text-left">
               <div class="card-body">
            
            <div class="row">
              
              @if ($message = Session::get('success'))
              <div class="col-md-12">   
                <div class="alert alert-success">
                <strong>{{ $message }}</strong> 
                </div>
              </div>
              @endif

                <div class="col-md-8">
                    <h4 class="card-title mb-1"> Cases </h4> 
                    <p> List of all Cases </p>        
                </div>
            
                <div class="col-md-4">           
                <div class="btn-group" style="float:right">
                    <!-- <button class="btn btn-secondary btn-lg dropdown-toggle" id="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   Actions  </button>
                    <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div> -->
                    <a class="btn btn-success createJob" href="javascript:;" > <i class="fa fa-plus"></i> Add New </a>             
                </div>
                </div>
            </div>
                
                <div class="row">
                    <div class="col-md-12">
                    <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                {{-- <th scope="col">#ID</th> --}}
                                                <th scope="col">Candidate</th>
                                                <th scope="col">Case Type</th>
                                                <th scope="col">Total Candidate</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @if( count($cases) > 0 )
                                            @foreach($cases as $item)
                                            <tr>
                                                {{-- <th scope="row">{{ $item->id }}</th> --}}
                                                <td>{{ Helper::company_name($item->business_id)}}</td>
                                                <td>{{ Helper::get_sla_name($item->sla_id)}}</td>
                                                <td>{{ $item->total_candidates }}</td>
                                                <td><span class="badge badge-danger">Pending</span></td>
                                                <td>
                                                <a href="">
                                                  <a class="btn btn-sm btn-info" href=""> <i class="fa fa-eye"></i> View</a>
                                                </a>
                                                </td>
                                            </tr>
                                             @endforeach
                                              @else
                                             <tr>
                                                <td scope="row" colspan="6"><h3 class="text-center">No record!</h3></td>
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
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
   
</div>
@endsection
