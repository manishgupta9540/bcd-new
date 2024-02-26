    <!-- ICON BG-->
    <div class="col-lg-4 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #c2c2c2; border-radius: 13px;">
            <div class="card-body text-center pd-10 ">
              <i class="fa fa-user"></i>
                <div class="data-content">
                    <div class="row">
                      <div class="col-lg-12 top-heading-dash">
                        <a class="text-wh text-24 line-height-2 mb-2" href="{{ url('/customers') }}"> {{$customers_count}} </a><br>
                        <a class="text-wh mt-2 mb-0" href="{{ url('/customers') }}"> <strong>Customer</strong> </a>
                      </div>
                    </div>
                    <div class="row mt-30">
                      <div class="col-lg-6 below-heading-dash">
                        <a href="{{ url('/customers/?active_case=0') }}" class="text-wh text-18 line-height-2 mb-2"> {{$customers_active}} </a><br>
                        <a href="{{ url('/customers/?active_case=0') }}" class="mt-2 mb-0 text-wh"><strong>Active </strong></a>
                      </div>
                      <div class="col-lg-6 below-heading-dash">
                        <a href="{{ url('/customers?active_case=1') }}" class="text-wh text-18 line-height-2 mb-2"> {{$customers_inactive}} </a><br>
                        <a href="{{ url('/customers?active_case=1') }}" class="mt-2 mb-0 text-wh"><strong> Inactive </strong></a>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4 " style="background-color: #c2c2c2; border-radius: 13px;">
            <div class="card-body text-center pd-10 ">
              <i class="fa fa-users"></i>
                <div class="data-content">
                    <div class="row">
                      <div class="col-lg-12 top-heading-dash">
                        <a class="text-wh text-24 line-height-2 mb-2" href="{{url('/candidates')}}"> {{$candidate_count}} </a><br>
                        <a class="text-wh mt-2 mb-0" href="{{url('/candidates')}}"> <strong>Candidates</strong> </a>
                      </div>
                    </div>
                    <div class="row mt-30">
                    <div class="col-lg-4 below-heading-dash">
                        <a href="{{ url('/candidates/?sendto=customer') }}" class="text-wh text-18 line-height-2 mb-2"> {{$jaf_send_to_customer}} </a><br>
                        <a href="{{ url('/candidates/?sendto=customer') }}" class="mt-2 mb-0 text-wh"><strong> Customer </strong></a>
                        </div>
                      <div class="col-lg-4 below-heading-dash">
                        <a href="{{ url('/candidates/?sendto=coc') }}" class="text-wh text-18 line-height-2 mb-2"> {{$jaf_send_to_coc}} </a><br>
                        <a href="{{ url('/candidates/?sendto=coc') }}" class="mt-2 mb-0 text-wh"><strong> COC </strong></a>
                      </div>
                      
                      <div class="col-lg-4 below-heading-dash no-pd-data">
                        <a href="{{ url('/candidates/?sendto=candidate') }}" class="text-wh text-18 line-height-2 mb-2"> {{$jaf_send_to_candidate}} </a><br>
                        <a href="{{ url('/candidates/?sendto=candidate') }}" class="mt-2 mb-0 text-wh"><strong> Candidate </strong></a>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #c2c2c2; border-radius: 13px;">
            <div class="card-body text-center pd-10 ">
              <i class="fa fa-id-card"></i>
                <div class="data-content">
                    <div class="row">
                      <div class="col-lg-12 top-heading-dash">
                        <a href="{{ url('/candidates/?active_case=filled') }}" class="text-wh text-24 line-height-2 mb-2"> {{$completed_jaf}} </a><br>
                        <a href="{{ url('/candidates/?active_case=filled') }}" class="text-wh mt-2 mb-0"> <strong>JAF</strong> </a>
                      </div>
                    </div>
                    <div class="row mt-30">
                        <div class="col-lg-4 below-heading-dash">
                            <a href="{{ url('/candidates/?sendto=customer&jafstatus1=pending&jafstatus2=draft')}}" class="text-wh text-18 line-height-2 mb-2"> {{$completed_jaf_by_customer}} </a><br>
                            <a href="{{ url('/candidates/?sendto=customer&jafstatus1=pending&jafstatus2=draft')}}" class="mt-2 mb-0 text-wh"><strong> Customer </strong></a>
                        </div>
                      <div class="col-lg-4 below-heading-dash">
                        <a href="{{ url('/candidates/?sendto=coc&jafstatus1=pending&jafstatus2=draft')}}" class="text-wh text-18 line-height-2 mb-2"> {{$completed_jaf_by_coc}} </a><br>
                        <a href="{{ url('/candidates/?sendto=coc&jafstatus1=pending&jafstatus2=draft')}}" class="mt-2 mb-0 text-wh"><strong> COC </strong></a>
                      </div>
                    
                      <div class="col-lg-4 below-heading-dash no-pd-data">
                        <a href="{{ url('/candidates/?sendto=candidate&jafstatus1=pending&jafstatus2=draft')}}" class="text-wh text-18 line-height-2 mb-2"> {{$completed_jaf_by_candidate}} </a><br>
                        <a href="{{ url('/candidates/?sendto=candidate&jafstatus1=pending&jafstatus2=draft')}}" class="mt-2 mb-0 text-wh"><strong> Candidate </strong></a>
                      </div>
                      {{-- <div class="col-lg-3 below-heading-dash no-pd-data">
                        <a href="#" class="text-wh text-18 line-height-2 mb-2"> {{$incompleted_jaf_insuff}} </a>
                        <a href="#" class="mt-2 mb-0 text-wh"> Insufficiency </a>
                      </div> --}}
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #c2c2c2; border-radius: 13px;">
            <div class="card-body text-center pd-10 ">
              <i class="fa fa-check"></i>
                <div class="data-content">
                    <div class="row">
                      <div class="col-lg-12 top-heading-dash">
                        <a href="{{ url('/jobs') }}" class="text-wh text-24 line-height-2 mb-2"> {{$total_checks}} </a><br>
                        <a href="{{ url('/jobs') }}" class="text-wh mt-2 mb-0"> <strong>Checks</strong> </a>
                      </div>
                    </div>
                    <div class="row mt-30">
                      <div class="col-lg-6 below-heading-dash">
                        <a href="{{ url('/jobs') }}" class="text-wh text-18 line-height-2 mb-2">{{$completed_checks}}</a><br>
                        <a href="{{ url('/jobs') }}" class="mt-2 mb-0 text-wh"><strong> Completed </strong></a>
                      </div>
                      <div class="col-lg-6 below-heading-dash">
                        <a href="{{ url('/jobs') }}" class="text-wh text-18 line-height-2 mb-2"> {{$incompleted_checks}} </a><br>
                        <a href="{{ url('/jobs') }}" class="mt-2 mb-0 text-wh"><strong> Remaining </strong></a>
                      </div>
                      
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="col-lg-4 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #c2c2c2; border-radius: 13px;">
            <div class="card-body text-center pd-10 ">
              <i class="fa fa-briefcase"></i>
                <div class="data-content">
                    <div class="row">
                      <div class="col-lg-12 top-heading-dash">
                        <p class="text-primary text-24 line-height-1 mb-2"> 30 </p>
                        <p class="text-muted mt-2 mb-0"> <strong>Vendor</strong> </p>
                      </div>
                    </div>
                    <div class="row mt-30">
                      <div class="col-lg-6 below-heading-dash">
                        <a href="#" class="text-wh text-18 line-height-2 mb-2"> 20 </a><br>
                        <a href="#" class="mt-2 mb-0 text-wh"> Active </a>
                      </div>
                      <div class="col-lg-6 below-heading-dash">
                        <a href="#" class="text-wh text-18 line-height-2 mb-2"> 10 </a><br>
                        <a href="#" class="mt-2 mb-0 text-wh"> Inactive </a>
                      </div>
                      
                    </div>
                    
                </div>
            </div>
        </div>
    </div> --}}
    <div class="col-lg-4 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #c2c2c2; border-radius: 13px;">
            <div class="card-body text-center pd-10 ">
              <i class="fa fa-book"></i>
                <div class="data-content">
                    <div class="row">
                      <div class="col-lg-12 top-heading-dash">
                        <a href="{{ url('/reports') }}" class="text-wh text-24 line-height-2 mb-2"> {{$reports}} </a><br>
                        <a href="{{ url('/reports') }}" class="text-wh mt-2 mb-0"> <strong>Report</strong> </a>
                      </div>
                    </div>
                    <div class="row mt-30">
                      <div class="col-lg-6 below-heading-dash">
                        <a class="text-wh text-18 line-height-2 mb-2" href="{{ url('/reports/?report_status1=completed&report_status2=interim') }}"> {{$complete_report}} </a> <br>
                        <a class="mt-2 mb-0 text-wh" href="{{ url('/reports/?report_status1=completed&report_status2=interim') }}" ><strong>Completed</strong>  </a>
                      </div>
                      <div class="col-lg-6 below-heading-dash">
                        <a class="text-wh text-18 line-height-2 mb-2"  href="{{ url('/reports/?report_status=incomplete') }}"> {{$pending_report}} </a> <br>
                        <a class="mt-2 mb-0 text-wh"  href="{{ url('/reports/?report_status=incomplete') }}"><strong>Pending</strong>  </a>
                      </div>
                      {{-- <div class="col-lg-4 below-heading-dash">
                        <a class="text-wh text-18 line-height-2 mb-2"  href=" {{ url('/my/reports') }}"> {{$reports}} </a><br>
                        <a class=" mt-2 mb-0 text-wh"  href=" {{ url('/my/reports') }}"><strong>Completed</strong>  </a>
                      </div> --}}
                    </div>
                    {{-- <div class="row mt-30">
                      <div class="col-lg-6 below-heading-dash">
                        <a href="#" class="text-wh text-18 line-height-2 mb-2"> 20 </a><br>
                        <a href="#" class="mt-2 mb-0 text-wh"> Generated by user </a>
                      </div>
                      <div class="col-lg-6 below-heading-dash">
                        <a href="#" class="text-wh text-18 line-height-2 mb-2"> 0 </a><br>
                        <a href="#" class="mt-2 mb-0 text-wh"> Generated by vendor </a>
                      </div> --}}
                      
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4">
            <div class="card-body text-center">
                <i class="fa fa-user"></i>
                <div class="content">
                    <p class="text-primary text-24 line-height-1 mb-2"> {{ $customers_count }} </p>
                    <p class="text-muted mt-2 mb-0"> Customers </p>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-2 o-hidden mb-4">
            <div class="card-body text-center">
                <i class="fa fa-check"></i>
                <div class="content">
                    <p class="text-primary text-24 line-height-1 mb-2">  </p>
                    <p class="text-muted mt-2 mb-0"> Checks </p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-3 o-hidden mb-4">
            <div class="card-body text-center">
                <i class="fa fa-book"></i>
                <div class="content"> 
                    <p class="text-primary text-24 line-height-1 mb-2">  </p>
    <p class="text-muted mt-2 mb-0"> Verifications done </p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-4 o-hidden mb-4">
            <div class="card-body text-center">
                <i class="fa fa-paper-plane"></i>
                <div class="content">
                    <p class="text-primary text-24 line-height-1 mb-2"> {{$reports}} </p>
    <p class="text-muted mt-2 mb-0"> Reports sent  </p>
                </div>
            </div>
        </div>
    </div> -->



