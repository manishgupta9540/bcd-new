@extends('layouts.client')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content"> 
   <!-- ============Breadcrumb ============= -->
   <div class="row">
        <div class="col-sm-11">
            <ul class="breadcrumb">
            <li>
            <a href="{{ url('/my/home') }}">Dashboard</a>
            </li>
            <li>Checks Overview </li>
            </ul>
        </div>
        <!-- ============Back Button ============= -->
        <div class="col-sm-1 back-arrow">
            <div class="text-right">
            <a href="{{ url('/my/home') }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
            </div>
        </div>
    </div>
    <!-- ./breadbrum -->
    <div class="row">
        <div class="card text-left">
           <div class="card-body">            
                   <div class="row">
                   <div class="col-md-8">
                        <h4 class="card-title mb-1"> Checks Overview </h4> 
                        <p> Checks status </p>        
                    </div>
                    <div class="col-md-4">           
                        <div class="btn-group" style="float:right">        
                                       
                        </div>
                    </div>

                        <div class="col-md-12"> 
                            <div class="table-responsive">
                                <table class="table table-bordered  table-hover candidatesTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Completed</th>
                                            <th scope="col">Pending</th>
                                            <th scope="col">Insuff</th>
                                            <th scope="col">Calll</th>
                                            <th scope="col">SMS</th>
                                            <th scope="col">Link</th>
                                        </tr>
                                    </thead>
                                    <tbody class="candidateList"> 
                                    @if( count($checkResults) > 0 )
                                        @foreach($checkResults as $item)
                                        <tr>
                                            
                                            <td><a  href="" class="btn-link"> {{$item['check_name']}} </a><br>
                                                <small class="text-muted"></small>
                                            </td>
                                            <td> <a href="{{ url('/my/candidates/?verify_status=success&service='.$item['check_id']) }}"><small class="text-success">{{$item['completed']}} </small></a> <br>
                                                <small class="text-danger"> </small>
                                            </td>
                                            <td><a href="{{ url('/my/candidates/?verification_status=null&service='.$item['check_id']) }}"><small class="text-info"> {{$item['pending']}} </small></a></td>
                                            <td><a href="{{ url('/my/candidates/?insuffs=1&service='.$item['check_id'])}}">
                                            <small class="text-danger"> {{$item['insuff']}}  </small></a>
                                            </td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>
                                            0
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
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" role="status" aria-live="polite"></div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                            <div class=" paging_simple_numbers" >            
                                
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