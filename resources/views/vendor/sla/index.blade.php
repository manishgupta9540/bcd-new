@extends('layouts.vendor')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content"> 
         <!-- ============Breadcrumb ============= -->
        <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                <li>
                <a href="{{ url('/vendor/home') }}">Dashboard</a>
                </li>
                <li>SLA</li>
                </ul>
            </div>
            <!-- ============Back Button ============= -->
            <div class="col-sm-1 back-arrow">
                <div class="text-right">
                <a href="{{ url()->previous() }}"> <i class="fas fa-arrow-circle-left fa-2x"></i></a>
                </div>
            </div>
        </div>    
            <div class="row">
                <div class="card text-left">
                    <div class="card-body">
            
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="card-title mb-1"> SLA </h4> 
                                <p> Your SLA </p>        
                            </div>
                            {{-- <div class="col-md-4">           
                            <div class="btn-group" style="float:right">        
                                <a class="btn btn-success " href="{{ url('my/sla/create') }}" > <i class="fa fa-plus"></i> Add New </a>              
                            </div>
                            </div> --}}
                        </div>
                
                        <div class="row">
                            <div class="col-md-12"> 
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                {{-- <th scope="col">#ID</th> --}}
                                                <th scope="col">Name</th>
                                                <th scope="col">TAT</th>
                                                <th scope="col">Checks</th>
                                                <th scope="col">Status</th>
                                                {{-- <th scope="col">Action</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody class="candidateList">
                                            @foreach($sla as $item)
                                                <tr>
                                                    {{-- <th scope="row">{{ $item->id }}</th> --}}
                                                    <td>{{ $item->title }}</td>
                                                    <td> <span class="text-danger"> {{ $item->tat }} Days </span></td>
                                                    <td> <label for="">{!! Helper::get_vendor_sla_items($item->id) !!} </label></td>
                                                    
                                                    <td><span class="badge badge-success">Active</span></td>
                                                    {{-- <td>
                                                    <a href="{{ url('/my/sla/view',['id'=>$item->id]) }}">
                                                    <button class="btn btn-info" type="button">View</button>
                                                    </a>
                                                    </td> --}}
                                                </tr>
                                            @endforeach     
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
