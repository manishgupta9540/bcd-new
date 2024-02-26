@extends('layouts.guest_old')

@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
    <div class="main-content">         
  
          <div class="row">
              <div class="col-lg-10">
                  <h3 class="mr-2"> Dashboard </h3>
              </div>
  
              <div class="col-lg-2">
                  {{-- <div class="btn-group" style="float: right;">
                      <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Last 30 days
                      </button>
  
                      <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px,27px, 0px);">
                          <a class="dropdown-item" href="#">Today Only</a>
                          <a class="dropdown-item" href="#">This Week</a>
                          <a class="dropdown-item" href="#">This Month</a>
                          <a class="dropdown-item" href="#">This Year</a>
                      </div>
                  </div>       --}}
              </div>
          </div>
          
          <div class="row" >
              <!-- ICON BG-->
              {{-- <div class="col-lg-4 col-sm-6">
                  <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #c2c2c2; border-radius: 13px;">
                      <div class="card-body text-center pd-10 ">
                        <i class="fa fa-check"></i>
                          <div class="data-content">
                              <div class="row">
                                <div class="col-lg-12 top-heading-dash">
                                  <a href="{{ url('/jobs') }}" class="text-wh text-24 line-height-2 mb-2"> Checks </a><br>
                                  <a href="{{ url('/jobs') }}" class="text-wh mt-2 mb-0"> <strong>Checks</strong> </a>
                                </div>
                              </div>
                                
                            </div>
                              
                          </div>
                      </div>
                  </div> --}}
  
              <div class="col-lg-4 col-sm-6">
                  <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #c2c2c2; border-radius: 13px;">
                      <div class="card-body text-center pd-10 ">
                        <i class="fa fa-users"></i>
                          <div class="data-content">
                              <div class="row">
                                <div class="col-lg-12 top-heading-dash">
                                  <a class="text-wh text-24 line-height-2 mb-2" href="{{ url('/guest/candidates') }}"> {{$candidate_count}} </a><br>
                                  <a href="{{ url('/guest/candidates') }}" class="text-wh text-24 line-height-2 mb-2"> Candidates </a><br>
                                </div>
                              </div>
                            </div>
                      </div>
                  </div>
              </div>
              <div class="col-lg-4 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #c2c2c2; border-radius: 13px;">
                    <div class="card-body text-center pd-10 ">
                      <i class="fa fa-book"></i>
                        <div class="data-content">
                            <div class="row">
                              <div class="col-lg-12 top-heading-dash">
                                <a class="text-wh text-24 line-height-2 mb-2" href="{{ url('/guest/orders') }}"> {{$orders_count}} </a><br>
                                <a href="{{ url('/guest/orders') }}" class="text-wh text-24 line-height-2 mb-2"> Orders </a><br>
                              </div>
                            </div>
                            <div class="row mt-30">
                              <div class="col-lg-6 below-heading-dash">
                                <a href="{{ url('/guest/orders') }}" class="text-wh text-18 line-height-2 mb-2">{{$order_success_count}}</a><br>
                                <a href="{{ url('/guest/orders') }}" class="mt-2 mb-0 text-wh"> Success </a>
                              </div>
                              <div class="col-lg-6 below-heading-dash">
                                <a href="{{ url('/guest/orders') }}" class="text-wh text-18 line-height-2 mb-2"> {{$order_failed_count}} </a><br>
                                <a href="{{ url('/guest/orders') }}" class="mt-2 mb-0 text-wh"> Failed </a>
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
          </div>
          
        <div class="row">
            <div class="col-lg-10">
                {{-- <div class="btn-group">
                        <button class="btn  dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <h4> Most <span class="rm">Recent Checks <span> </h4>
                        </button>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 27px, 0px);"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                </div> --}}
            </div>

            {{-- <div class="col-lg-2">
                <div class="btn-group" style="float: right;">
                        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  Show <span class="alm"> all Checks </span>
                        </button>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 27px, 0px);"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                </div>       
            </div> --}}
        </div>
          
        {{-- <div class="row">
  
            <div class="col-lg-12 col-sm-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="card-title">Checks Overview</div>
                        <div class="table-responsive" style="max-height: 300px;">
                          <table class="table">
                            <thead>
                              <tr>
                                <th style="position:sticky; top:0px" scope="col"><strong>Checks</strong></th>
                                <th style="position:sticky; top:0px" scope="col"><strong>Completed</strong></th>
                                <th style="position:sticky; top:0px" scope="col"><strong>Remaining</strong></th>
                                <th style="position:sticky; top:0px" scope="col"><strong>Insuff</strong></th>
                                <th style="position:sticky; top:0px" scope="col"><strong>Call</strong></th>
                                <th style="position:sticky; top:0px" scope="col"><strong>SMS</strong></th>
                                <th style="position:sticky; top:0px" scope="col"><strong>Link</strong></th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                  <td><strong>Checks</strong></td>
                                  <td><strong>Completed</strong></td>
                                  <td><strong>Remaining</strong></td>
                                </tr> 
                                 <tr>
                                  <td><strong>Addhar</strong></td>
                                  <td>20</td>
                                  <td>12</td>
                                </tr>
                                <tr>
                                  <td><strong>Pan</strong></td>
                                  <td>20</td>
                                  <td>12</td>
                                </tr>
                                <tr>
                                  <td><strong>Education</strong></td>
                                  <td>20</td>
                                  <td>12</td>
                                </tr>
                            </tbody>
                          </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="card-title">Checks Overview</div>
                        <div id="echartBar" style="height: 300px;"></div>
                    </div>
                </div>
            </div>          
        </div> --}}
   
    </div>
  </div>
  
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@endsection