@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content"> 
        <div class="row">
            @if ($message = Session::get('success'))
              <div class="col-md-12">   
                <div class="alert alert-success">
                <strong>{{ $message }}</strong> 
                </div>
              </div>
              @endif
            <div class="card text-left">
               <div class="card-body">
            
            <div class="row">
                <div class="col-md-8">
                    <h4 class="card-title mb-1"> Vendors </h4> 
                    <p> List of all Vendors </p>        
                </div>
                <div class="col-md-4">           
                <div class="btn-group" style="float:right">        
                    <a class="btn btn-success " href="{{ url('/app/vendor/create') }}" > <i class="fa fa-plus"></i> Add New </a>              
                </div>
                </div>
            </div>
                
                <div class="row">
                    <div class="col-md-12">
                    <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email ID</th>
                                                <th scope="col">Phone Number</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="vendorList">
                                          @if( count($vendor) > 0 )
                                            @foreach($vendor as $item)
                                            <tr>
                                                <th scope="row">{{ $item->id }}</th>
                                                <td>{{ $item->first_name.' '.$item->last_name }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>{{ $item->phone }}</td>
                                                <td>@if($item->status == '1')<span class="badge badge-success">Active</span>@else<span class="badge badge-danger">Inactive</span>@endif</td>
                                                <td>
                                                <a href="{{ route('/vendor/edit',['id'=>base64_encode($item->id)]) }}">
                                                  <button class="btn btn-success" type="button">View</button>
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
