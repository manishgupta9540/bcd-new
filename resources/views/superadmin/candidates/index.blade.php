@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="card text-left">
            <div class="card-body">
               <div class="row">
                  <div class="col-md-8">
                     <h4 class="card-title mb-3"> Candidates </h4>
                     <p> List of all Candidates </p>
                  </div>
                  <div class="col-md-4">
                     <div class="btn-group" style="float:right">
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
                                 <th scope="col"></th>
                                 <th scope="col">Name</th>
                                 <th scope="col">Email</th>
                                 <th scope="col">Phone</th>
                                 <th scope="col">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              @if( count($items) > 0 )
                              @foreach($items as $item)
                              <tr>
                                 <th scope="row">{{ $item->id }}</th>
                                 <td></td>
                                 <td>{{ $item->name }}</td>
                                 <td>{{ $item->email }}</td>
                                 <td>{{ $item->phone }}</td>
                                 <td>
                                    <a href="{{ route('/candidates/show',['id'=>base64_encode($item->id)]) }}">
                                        <button class="btn btn-success" type="button">View</button>
                                    </a>
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
@endsection
