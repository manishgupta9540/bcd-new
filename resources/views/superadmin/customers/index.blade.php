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
                     @if ($message = Session::get('success'))
                     <div class="col-md-12">
                        <div class="alert alert-success">
                           <strong>{{ $message }}</strong> 
                        </div>
                     </div>
                     @endif
                     <div class="col-md-8">
                        <h4 class="card-title mb-1"> Customers </h4>
                        <p> List of all customers </p>
                     </div>
                     <div class="col-md-4">
                        <div class="btn-group" style="float:right">
                           <!-- <button class="btn btn-secondary btn-lg dropdown-toggle" id="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  Actions  </button>
                              <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#"> Else Here</a></div> -->
                           <a class="btn btn-success " href="{!! url('/app/customers/create') !!} " > <i class="fa fa-plus"></i> Add New </a>            
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="table-responsive">
                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">#ID</th>
                                    <th scope="col">Company Name</th>
                                    <th scope="col">Contact Person</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if( count($items) > 0 )
                                 @foreach($items as $item)
                                 <tr>
                                    <th scope="row">{{ $item->id }}</th>
                                    <td> <b>{{ $item->company_name }} </b><br>
                                       <!-- <small class="text-muted"> Type : </small> -->
                                    </td>
                                    <td>{{ $item->first_name.' '.$item->last_name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td><span class="badge badge-success">ACTIVE</span></td>
                                    <td> 
                                       <a href="{{ url('/app/customers/show',['id'=>base64_encode($item->id)]) }}"><button class="btn btn-success btn-sm" type="button"> <i class="fa fa-eye"></i> View</button></a>
                                       <a href="{{ url('/app/customers/edit',['id'=>base64_encode($item->id)]) }} "><button class="btn btn-info btn-sm" type="button"><i class="fa fa-edit"></i> Edit</button></a>
                                    </td>
                                 </tr>
                                 @endforeach
                                 @else
                                 <tr>
                                    <td scope="row" colspan="7">
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
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
</div>
@endsection
