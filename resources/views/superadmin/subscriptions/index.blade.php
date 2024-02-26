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
                     <h4 class="card-title mb-1"> Subscriptions Package </h4>
                     <p> Manage Subscriptions package </p>
                  </div>
                  <div class="col-md-4">
                     <div class="btn-group" style="float:right">        
                        <a class="btn btn-success " href="{{ route('/subscriptions/create') }}" > <i class="fa fa-plus"></i> Add New </a>             
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
                                 <th scope="col"> Name</th>
                                 <th scope="col"> Price</th>
                                 <th scope="col"> Status</th>
                                 <th scope="col">Created at</th>
                                 <th scope="col">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              @if( count($items) > 0 )
                              @foreach($items as $item)
                              <tr>
                                 <th scope="row">{{ $item->id }}</th>
                                 <td><b>{{ $item->name }}</b><br>
                                    User Limit: {{$item->candiates_allowed}} <br>
                                    Verifications Limit: {{$item->verifications_allowed}}
                                 </td>
                                 <td>{{ 'INR'.' '.$item->price }}</td>
                                 <td> <label class="badge badge-success">Active</label></td>
                                 <td>{{ $item->created_at }}</td>
                                 <td>
                                    <a href="{{ route('/subscriptions/edit',['id'=>base64_encode($item->id) ]) }}"><button class="btn btn-success" type="button">Edit</button></a>
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
