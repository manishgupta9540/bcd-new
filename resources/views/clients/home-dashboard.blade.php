<div class="row">
    <!-- ICON BG-->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4 " style="background-color: #c2c2c2; border-radius: 13px;">
            <div class="card-body text-center pd-10 ">
              <i class="fa fa-user"></i>
                <div class="data-content">
                    <div class="row">
                      <div class="col-lg-12 top-heading-dash px-10">
                        <a class="text-wh text-24 line-height-2 mb-2" href=" {{ url('/my/candidates') }}"> {{$candidates_count}} </a><br>
                        <a class="text-20 mt-2 mb-0 text-wh"  href=" {{ url('/my/candidates') }}"> <strong>Candidates</strong> </a>
                      </div>
                    </div>
                    <div class="row mt-60">
                      <div class="col-lg-6 below-heading-dash px-10">
                        <a class="text-wh text-18 line-height-2 mb-2" href="{{ url('/my/candidates/?active_case=filled') }}"> {{$jaf_total_filled}} </a> <br>
                        <a class="mt-2 mb-0 text-wh" href="{{ url('/my/candidates/?active_case=filled') }}" ><strong>Active Cases</strong>  </a>
                      </div>
                      <div class="col-lg-6 below-heading-dash">
                        
                        <a class="text-wh text-18 line-height-2 mb-2"  href="{{ url('/my/candidates/?active_case1=pending&active_case2=draft') }}"> {{$inactive_candidate}} </a> <br>
                        <a class="mt-2 mb-0 text-wh"  href="{{ url('/my/candidates/?active_case1=pending&active_case2=draft') }}"><strong>Inactive</strong>  </a>
                      </div>
                      {{-- <div class="col-lg-4 below-heading-dash">
                        <a class="text-wh text-18 line-height-2 mb-2"  href=" {{ url('/my/reports') }}"> {{$reports}} </a><br>
                        <a class=" mt-2 mb-0 text-wh"  href=" {{ url('/my/reports') }}"><strong>Completed</strong>  </a>
                      </div> --}}
                    </div> 
                    
                </div> 
            </div>
        </div>
    </div>

    <!-- 2nd box -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4 " style="background-color: #c2c2c2; border-radius: 13px;">
            <div class="card-body text-center pd-10 ">
              <i class="fa fa-check"></i>
                <div class="data-content">
                    <div class="row">
                      <div class="col-lg-12 top-heading-dash px-10">
                        <a class="text-wh text-24 line-height-2 mb-2" href="{{ url('/my/candidates/?active_case=filled') }}"> {{ $jaf_total_filled }} </a><br>
                        <a class=" text-20 mt-2 mb-0" href="{{ url('/my/candidates/?active_case=filled') }}"> <strong>JAF</strong> </a>
                      </div>
                    </div>
                    <div class="row mt-60">
                      <div class="col-lg-4 below-heading-dash px-10">
                        <a class="text-wh text-18 line-height-2 mb-2" href="{{ url('/my/candidates/?sendto=coc&jafstatus=filled')}}"> {{$jaf_send_to_coc}} </a><br>
                        <a class="mt-2 mb-0 text-wh"  href="{{ url('/my/candidates/?sendto=coc&jafstatus=filled')}}"> <strong>{{Helper::company_sort_name($business_id)}}</strong>  </a>
                      </div>
                      <div class="col-lg-4 below-heading-dash px-10">
                        <a class="text-wh text-18 line-height-2 mb-2" href="{{ url('/my/candidates/?sendto=customer&jafstatus=filled')}}"> {{$jaf_send_to_customer}} </a><br>
                        <a class="mt-2 mb-0 text-wh" href="{{ url('/my/candidates/?sendto=customer&jafstatus=filled') }}"><strong>{{Helper::company_sort_name($parent_id)}}</strong> </a>
                      </div>
                      <div class="col-lg-4 below-heading-dash no-pd-data px-10">
                        <a class="text-wh text-18 line-height-2 mb-2" href="{{ url('/my/candidates/?sendto=candidate&jafstatus=filled')}}" > {{$jaf_send_to_candidate}} </a><br>
                        <a class="mt-2 mb-0 text-wh" href="{{ url('/my/candidates/?sendto=candidate&jafstatus=filled') }}"> <strong>Candidate</strong> </a>
                      </div> 
                      {{-- <div class="col-lg-3 below-heading-dash no-pd-data">
                        <p class="text-primary text-18 line-height-1 mb-2"> 1 </p>
                        <p class="mt-2 mb-0 text-wh"> <strong>Insufficiency</strong>  </p>
                      </div>  --}}
                    </div>
                    
                </div>  
            </div>
        </div>
    </div>

    <!-- 3rd box -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4 " style="background-color: #c2c2c2; border-radius: 13px;">
            <div class="card-body text-center pd-10 ">
              <i class="fa fa-paper-plane"></i>
                <div class="data-content">
                    <div class="row">
                      <div class="col-lg-12 top-heading-dash px-10">
                      
                        <a class="text-wh text-24 line-height-2 mb-0"  href=" {{ url('/my/checks') }}"> {{$total_checks}} </a><br>
                        <a class="text-wh text-20 mt-2 mb-0"  href=" {{ url('/my/checks') }}"> <strong>Checks</strong> </a>
                      </a>
                      </div>
                    </div>
                    <div class="row mt-60">
                      <div class="col-lg-4 below-heading-dash px-10">
                        <a class="text-wh text-18 line-height-2 mb-2"  href=" {{ url('/my/checks') }}"> {{$completed_checks}} </a><br>
                        <a class="mt-2 mb-0 text-wh"  href=" {{ url('/my/checks') }}"><strong>Completed</strong>  </a>
                      </div>
                      <div class="col-lg-4 below-heading-dash px-10">
                        <a class="text-wh text-18 line-height-2 mb-2"  href="{{ url('/my/candidates/?insuff=1') }}"> {{$insuff_checks}} </a><br>
                        <a class="mt-2 mb-0 text-wh" href="{{ url('/my/candidates/?insuff=1') }}"><strong>Insuff Raised</strong>  </a>
                      </div>
                      <div class="col-lg-4 below-heading-dash px-10">
                        <a class="text-wh text-18 line-height-2 mb-2" href=" {{ url('/my/checks') }}"> {{$incompleted_checks}} </a><br>
                        <a class="mt-2 mb-0 text-wh"  href=" {{ url('/my/checks') }}"> <strong>Remaining</strong>  </a>
                      </div>
                      
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- 4th box -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #c2c2c2; border-radius: 13px;">
            <div class="card-body text-center pd-10 ">
              <i class="fa fa-book"></i>
                <div class="data-content">
                    <div class="row">
                      <div class="col-lg-12 top-heading-dash px-10">
                        <a class="text-wh text-24 line-height-2 mb-2 " href=" {{ url('/my/reports') }}"> {{$reports}} </a><br>
                        <a class="text-20 mt-2 mb-0 text-wh"  href=" {{ url('/my/reports') }}"><strong>Reports Received</strong> </p>
                      </div>
                    </div>
                    <div class="row mt-60">
                      <div class="col-lg-6 below-heading-dash px-10">
                        <a class="text-wh text-18 line-height-2 mb-2" href="{{ url('/my/reports/?report_status1=completed&report_status2=interim') }}"> {{$complete_report}} </a> <br>
                        <a class="mt-2 mb-0 text-wh" href="{{ url('/my/reports?report_status1=completed&report_status2=interim') }}" ><strong>Completed</strong>  </a>
                      </div>
                      <div class="col-lg-6 below-heading-dash px-10">
                        <a class="text-wh text-18 line-height-2 mb-2"  href="{{ url('/my/reports/?report_status=incomplete') }}"> {{$pending_report}} </a> <br>
                        <a class="mt-2 mb-0 text-wh"  href="{{ url('/my/reports/?report_status=incomplete') }}"><strong>Pending</strong>  </a>
                      </div>
                      {{-- <div class="col-lg-4 below-heading-dash">
                        <a class="text-wh text-18 line-height-2 mb-2"  href=" {{ url('/my/reports') }}"> {{$reports}} </a><br>
                        <a class=" mt-2 mb-0 text-wh"  href=" {{ url('/my/reports') }}"><strong>Completed</strong>  </a>
                      </div> --}}
                    </div>
                    <!-- <div class="row mt-30">
                      <div class="col-lg-4 below-heading-dash">
                        <p class="text-primary text-18 line-height-1 mb-2"> 12 </p>
                        <p class="mt-2 mb-0 text-wh"> COC </p>
                      </div>
                      <div class="col-lg-4 below-heading-dash">
                        <p class="text-primary text-18 line-height-1 mb-2"> 10 </p>
                        <p class="mt-2 mb-0 text-wh"> BCD </p>
                      </div>
                      <div class="col-lg-4 below-heading-dash">
                        <p class="text-primary text-18 line-height-1 mb-2"> 5 </p>
                        <p class="mt-2 mb-0 text-wh"> Candidate </p>
                      </div>
                    </div> -->
                    
                </div>
            </div>
        </div>
    </div>



    <!-- <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-2 o-hidden mb-4">
            <div class="card-body text-center">
                <i class="fa fa-check"></i>
                <div class="content">
                    <p class="text-primary text-24 line-height-1 mb-2</p>
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
                    <p class="text-primary text-24 line-height-1 mb-2"> {{$reports}}</p>
    <p class="text-muted mt-2 mb-0"> Reports  </p>
                </div>
            </div>
        </div>
    </div> -->
</div>

