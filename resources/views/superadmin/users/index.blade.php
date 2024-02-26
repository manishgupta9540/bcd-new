@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
      <div class="col-md-12">

         @if ($message = Session::get('success'))
            
               <div class="alert alert-success">
               <strong>{{ $message }}</strong> 
               </div>
            
         @endif

         <div class="card text-left">
            <div class="card-body">
               <div class="row">
                  <div class="col-md-8">
                     <h4 class="card-title mb-1"> Users </h4>
                     <p> List of all Users </p>
                  </div>
                  <div class="col-md-4">
                     <div class="btn-group" style="float:right">
                        <!-- <button class="btn btn-secondary btn-lg dropdown-toggle" id="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   Actions  </button>
                           <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div> -->
                        <a class="btn btn-success " href="{{ url('/app/users/create') }}" > <i class="fa fa-plus"></i> Add New </a>             
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
                                 <th scope="col">Contact </th>
                                 <th scope="col">Email</th>
                                 <th scope="col">Role</th>
                                 <th scope="col">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              @if(count($users) > 0)
                              @foreach ($users as $key => $user)
                              <tr>
                                 <th scope="row">{{ $user->id }}</th>
                                 <td>{{ $user->name }}</td>
                                 <td>{{ $user->phone }}</td>
                                 <td>{{ $user->email }}</td>
                                 <td>
                                    @if(!empty($user->getRoleNames())) 
                                    @foreach($user->getRoleNames() as $v)
                                    <label class="badge badge-success">{{ $v }}</label>
                                    @endforeach
                                    @endif
                                 </td>
                                 <td>
                                    <a class="btn btn-primary" href="{{ url('app/users/edit',[$user->id]) }}">Edit</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                    {!! Form::close() !!}
                                 </td>
                              </tr>
                              @endforeach
                              @else
                              <tr>
                                 <td scope="row" colspan="6">
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
