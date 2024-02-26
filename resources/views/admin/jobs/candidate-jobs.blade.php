@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">          
    <div class="row">
        <div class="col-sm-11">
            <ul class="breadcrumb">
            <li>
            <a href="{{ url('/home') }}">Dashboard</a>
            </li>
            <li>Cases</li>
            </ul>
        </div>
        <!-- ============Back Button ============= -->
        <div class="col-sm-1 back-arrow">
            <div class="text-right">
            <a href="{{url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
            </div>
        </div>
    </div>
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
            </div>
            {{-- <div class="table-box mt-40"> --}}
             <!-- include menu -->
             @include('admin.jobs.menu')
             <!-- include menu -->
                {{-- <div class="col-md-12">           
                <div class="btn-group" style="float:right;  margin-top: 15px;">
                    <!-- <button class="btn btn-secondary btn-lg dropdown-toggle" id="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   Actions  </button>
                    <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div> -->
                    <a class="btn btn-success createJob" href="javascript:;" > <i class="fa fa-plus"></i> Add New </a>             
                </div>
                </div> --}}
            </div>
                
                <div class="row">
                    <div class="col-md-12">
                    <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">#ID</th>
                                                <th scope="col" width="20%">Candidate Name</th>
                                                <th scope="col">Checks Performed</th>
                                                <th scope="col" width="10%">Checks Completed</th>
                                                <th scope="col" width="12%">Checks Remaining</th>
                                                <th scope="col">Call</th>
                                                <th scope="col">Sms</th>
                                                <th scope="col">Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          {{-- @if( count($jobs) > 0 )
                                            @foreach($jobs as $item) --}}
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>Krishna</td>
                                                <td>6</td>
                                                <td>3</td>
                                                <td>3</td>
                                                <td>5</td>
                                                <td>3</td>
                                                <td>3
                                                {{-- <a href="">
                                                  <a class="btn btn-sm btn-info" href=""> <i class="fa fa-eye"></i> View</a>
                                                </a> --}}
                                                </td>
                                            </tr>
                                             {{-- @endforeach
                                              @else --}}
                                             {{-- <tr>
                                                <td scope="row" colspan="6"><h3 class="text-center">No record!</h3></td>
                                             </tr>
                                          @endif --}}

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
   <!-- <div class="app-footer">
      <div class="footer-bottom border-top pt-3 d-flex flex-column flex-sm-row align-items-center">
           
      <p><strong> 2020 &copy; Admin ! All rights reserved</strong></p>
      
          <span class="flex-grow-1"></span>
          <div class="d-flex align-items-center">
              <div>
                  <p class="m-0"> design by techsaga </p>
              </div>
          </div>
      </div>
      </div> -->
   <!-- fotter end -->
</div>
@endsection
