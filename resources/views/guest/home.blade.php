@extends('layouts.guest')

@section('content')
  <!-- =============== Left side End ================-->
<div class="main-content-wrap sidenav-open d-flex flex-column">
  <!-- ============ Body content start ============= -->
  <div class="main-content notify home">
     <div class="row">
        <div class="card">
           <div class="card-body">
              <i class="fa fa-close close_notify" style="font-size:21px;float: right; cursor:pointer;"></i>
              <div class="row">
                 <div class="col-md-3"><img src="{{asset('guest/images/guest1.svg')}}"></div>
                 <div class="col-md-6">
                    <h3 class="card-title mb-3"> MyBCD Pre Verifications are Here !! </h3>
                    <p style="font-size:15px">You asked and we listened! BCD now supports reverifications of employment status with a click of a button. Reverify an employee at a lower rate within 90 days of submitting the original request. Now you have more time to focus on what matters!</p>
                 </div>
                 <div class="col-md-3 learnmore">
                  {{-- <button class="btn btn-rounded" style="border: 2px solid #dbc1f5;
                    background: transparent;border-radius: 0px!important;"> Learn More </button> --}}
                    </div>
              </div>
           </div>
        </div>
     </div>
  </div>
  <div class="main-content home-main-content pending" style="margin-top:30px !important">
     <div class="row">
        <div class="card">
           <div class="card-body">
              <div class="row">
                 <div class="col-md-12">
                    <h3 class="card-title mb-3"><i class="fa fa-hand-o-right" style="font-size:24px"></i> Pending Requests <small class="text-muted">(Recents)</small></h3>
                    @if(count($pending_list)>0)
                        <div class="table-responsive tableFixHead" style="height: 220px;">
                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    {{-- <th scope="col"> <input type="checkbox"> </th> --}}
                                    <th scope="col">Order ID</th>
                                    {{-- <th scope="col">Candidate Name</th> --}}
                                    <th scope="col">Total Price</th>
                                    <th scope="col">Services</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date & Time</th>
                                    <th scope="col">Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach ($pending_list as $item)
                                 <tr>
                                    {{-- <td scope="row"><input type="checkbox"></td> --}}
                                    <td class="text-center" scope="row">{{ $item->order_id!=NULL?$item->order_id : '--' }}</td>
                                    {{-- <td>{{Helper::user_name($item->candidate_id)}}</td> --}}
                                    <td><i class="fas fa-rupee-sign"></i> {{$item->total_price}}</td>
                                    <td>{!! Helper::get_guest_service_name_slot($item->services) !!}</td>
                                    <td>
                                       <span class="badge badge-warning">Pending</span>  
                                    </td>
                                    <td>{{$item->updated_at!=NULL?date('d-M-Y h:i A',strtotime($item->updated_at)):date('d-M-Y h:i A',strtotime($item->created_at))}}</td>
                                    <td>
                                       <a href="{{url('/verify/instant_verification')}}">
                                          <button class="btn btn-success btn-sm" type="button"> Start Verification  <i class="fas fa-arrow-right"></i></button>
                                       </a>
                                    </td>
                                 </tr>
                                 @endforeach
                                 {{-- <tr>
                                    <td scope="row"><input type="checkbox"></td>
                                    <td scope="row"><a href="qcs.html"> abc </a> </td>
                                    <td> abc </td>
                                    <td> 45654646</td>
                                    <td>65656456546</td>
                                    <td>vdsvdgd</td>
                                 </tr>
                                 <tr>
                                    <td scope="row"><input type="checkbox"></td>
                                    <td scope="row"><a href="qcs.html"> abc </a> </td>
                                    <td> abc </td>
                                    <td> 45654646</td>
                                    <td>65656456546</td>
                                    <td>vdsvdgd</td>
                                 </tr>
                                 <tr>
                                    <td scope="row"><input type="checkbox"></td>
                                    <td scope="row"><a href="qcs.html"> abc </a> </td>
                                    <td> abc </td>
                                    <td> 45654646</td>
                                    <td>65656456546</td>
                                    <td>vdsvdgd</td>
                                 </tr>
                                 <tr>
                                    <td scope="row"><input type="checkbox"></td>
                                    <td scope="row"><a href="qcs.html"> abc </a> </td>
                                    <td> abc </td>
                                    <td> 45654646</td>
                                    <td>65656456546</td>
                                    <td>vdsvdgd</td>
                                 </tr>
                                 <tr>
                                    <td scope="row"><input type="checkbox"></td>
                                    <td scope="row"><a href="qcs.html"> abc </a> </td>
                                    <td> abc </td>
                                    <td> 45654646</td>
                                    <td>65656456546</td>
                                    <td>vdsvdgd</td>
                                 </tr>
                                 <tr>
                                    <td scope="row"><input type="checkbox"></td>
                                    <td scope="row"><a href="qcs.html"> abc </a> </td>
                                    <td> abc </td>
                                    <td> 45654646</td>
                                    <td>65656456546</td>
                                    <td>vdsvdgd</td>
                                 </tr>
                                 <tr>
                                    <td scope="row"><input type="checkbox"></td>
                                    <td scope="row"><a href="qcs.html"> abc </a> </td>
                                    <td> abc </td>
                                    <td> 45654646</td>
                                    <td>65656456546</td>
                                    <td>vdsvdgd</td>
                                 </tr> --}}
                              </tbody>
                           </table>
                        </div>
                     @else
                        <center>
                           <img class="emptydata" src="{{asset('guest/images/empty-data.svg')}}"><br>
                           <p style="color: rgb(64, 68, 85);font-size: 18px;font-size: 17px;
                              ">No pending requests found.</p>
                           <p style="color: rgb(64, 68, 85);margin-top: -12px;"> Would you like to <a href="{{url('/guest/instant_verification')}}">Start a New Verification</a></p>
                        </center>
                    @endif
                 </div>
              </div>
           </div>
        </div>
     </div>
  </div>
  <div class="main-content home-main-content" style="margin-top:30px !important">
     <div class="row">
        <div class="card">
           <div class="card-body">
              <div class="row">
                 <div class="col-md-12">
                    <h3 class="card-title mb-3"><i class="fa fa-hand-o-right" style="font-size:24px"></i> Completed Requests <small class="text-muted">(Recents)</small></h3>
                    @if(count($order_list)>0)
                     <div class="table-responsive tableFixHead" style="height: 220px;">
                        <table class="table table-bordered">
                           <thead>
                              <tr>
                                 {{-- <th scope="col"> <input type="checkbox"> </th> --}}
                                 <th scope="col">Order ID</th>
                                 {{-- <th scope="col">Candidate Name</th> --}}
                                 <th scope="col">Total Price</th>
                                 <th scope="col">Services</th>
                                 <th scope="col">Status</th>
                                 <th scope="col">Date & Time</th>
                                 {{-- <th scope="col">Action</th> --}}
                              </tr>
                           </thead>
                           <tbody>
                              @foreach ($order_list as $item)
                              <tr>
                                 {{-- <td scope="row"><input type="checkbox"></td> --}}
                                 <td scope="row">{{ $item->order_id }}</td>
                                 {{-- <td>{{Helper::user_name($item->candidate_id)}}</td> --}}
                                <td><i class="fas fa-rupee-sign"></i> {{$item->total_price}}</td>
                                <td>{!! Helper::get_guest_service_name_slot($item->services) !!}</td>
                                <td>
                                    @if($item->status=='success')
                                        <span class="badge badge-success">Success</span>
                                    @elseif($item->status=='failed')
                                        <span class="badge badge-danger">Failed</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{$item->updated_at!=NULL?date('d-M-Y h:i A',strtotime($item->updated_at)):date('d-M-Y h:i A',strtotime($item->created_at))}}</td>
                              </tr>
                              @endforeach
                              {{-- <tr>
                                 <td scope="row"><input type="checkbox"></td>
                                 <td scope="row"><a href="qcs.html"> abc </a> </td>
                                 <td> abc </td>
                                 <td> 45654646</td>
                                 <td>65656456546</td>
                                 <td>vdsvdgd</td>
                              </tr>
                              <tr>
                                 <td scope="row"><input type="checkbox"></td>
                                 <td scope="row"><a href="qcs.html"> abc </a> </td>
                                 <td> abc </td>
                                 <td> 45654646</td>
                                 <td>65656456546</td>
                                 <td>vdsvdgd</td>
                              </tr>
                              <tr>
                                 <td scope="row"><input type="checkbox"></td>
                                 <td scope="row"><a href="qcs.html"> abc </a> </td>
                                 <td> abc </td>
                                 <td> 45654646</td>
                                 <td>65656456546</td>
                                 <td>vdsvdgd</td>
                              </tr>
                              <tr>
                                 <td scope="row"><input type="checkbox"></td>
                                 <td scope="row"><a href="qcs.html"> abc </a> </td>
                                 <td> abc </td>
                                 <td> 45654646</td>
                                 <td>65656456546</td>
                                 <td>vdsvdgd</td>
                              </tr>
                              <tr>
                                 <td scope="row"><input type="checkbox"></td>
                                 <td scope="row"><a href="qcs.html"> abc </a> </td>
                                 <td> abc </td>
                                 <td> 45654646</td>
                                 <td>65656456546</td>
                                 <td>vdsvdgd</td>
                              </tr>
                              <tr>
                                 <td scope="row"><input type="checkbox"></td>
                                 <td scope="row"><a href="qcs.html"> abc </a> </td>
                                 <td> abc </td>
                                 <td> 45654646</td>
                                 <td>65656456546</td>
                                 <td>vdsvdgd</td>
                              </tr>
                              <tr>
                                 <td scope="row"><input type="checkbox"></td>
                                 <td scope="row"><a href="qcs.html"> abc </a> </td>
                                 <td> abc </td>
                                 <td> 45654646</td>
                                 <td>65656456546</td>
                                 <td>vdsvdgd</td>
                              </tr> --}}
                           </tbody>
                        </table>
                     </div>
                     @else
                     <center>
                        <img class="emptydata" src="{{asset('guest/images/empty-data.svg')}}"><br>
                        <p style="color: rgb(64, 68, 85);font-size: 18px;font-size: 17px;
                           ">No completed requests found.</p>
                        <p style="color: rgb(64, 68, 85);margin-top: -12px;">Would you like to <a href="{{url('/verify/instant_verification')}}">Start a New Verification</a></p>
                     </center> 
                    @endif
                 </div>
              </div>
           </div>
        </div>
     </div>
  </div>
  <div class="main-content home-main-content" style="margin-top:30px !important; margin-bottom: 30px !important">
     <div class="row">
        <div class="col-md-12" style="padding-left:0px;padding-right: 0px">
           <div class="card">
              <div class="card-body">
                 <h3 class="card-title mb-3">Your Verifications at a Glance</h3>
                 <div class="row">
                    <div class="col-md-4"><img src="{{asset('guest/images/verification.png')}}"><span class="count">{{$total_order > 0? $total_order:'0'}}</span><br><span class="verify">Verification</span>
                    </div>
                    <div class="col-md-4"><img src="{{asset('guest/images/completed.png')}}"><span class="count">{{$total_order > 0 || $complete_order > 0 ? number_format(($complete_order/$total_order)*100,1):'0'}}%</span><br><span class="verify1">Completed</span>
                    </div>
                    <div class="col-md-4"><img src="{{asset('guest/images/clock (1).png')}}" style="width: 15%;"><span class="count1">1</span>Hour<br><span class="verify2">Turnaround</span>
                    </div>
                 </div>
              </div>
           </div>
        </div>
        {{-- <div class="col-md-5" style="padding-right: 0px">
           <div class="card">
              <div class="card-body">
                 <h3 class="card-title mb-3">Did you know?</h3>
                 <div class="row">
                    <div class="col-md-3"><img src="images/auth.png">
                    </div>
                    <div class="col-md-9">
                       <p class="authe1">Keep your account secure by setting up two-factor authentication.</p>
                       <button class="btn  btn-rounded" style="border: 2px solid #dbc1f5;
                          background: transparent;border-radius: 0px!important;"> Setup Two-Factor  </button>
                    </div>
                 </div>
              </div>
           </div>
        </div> --}}
     </div>
  </div>
  <!-- Footer Start -->
  <div class="flex-grow-1"></div>
</div>
</div><!-- ============ Search UI Start ============= -->
<!-- ============ Search UI End ============= -->
<script type="text/javascript">
   $(document).ready(function(){
      $(document).on('click','.close_notify',function(){
         $('.notify').fadeOut("slow");
         $('.pending').css('margin-top',110);
      });
   });
</script>
@endsection