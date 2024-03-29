<div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
    <div class="card-body pd-10 ">
      <i class="fa fa-book"></i>
        <div class="data-content">
            <div class="row">
              @php
                $report_card_url = '';
                if(count($kams)>0)
                {
                    $report_card_url = 'report_card_url=1';
                }
              @endphp
              @if ($report_card_url)
                <div class="col-lg-12 top-heading-dash">
                  <a href="{{ url('/reports'.'?'.$report_card_url) }}" class="text-24 line-height-2 mb-2"> {{$reports}} </a><br>
                  <a href="{{ url('/reports'.'?'.$report_card_url) }}" class="mt-2 mb-0 sort_desc"> <strong>Total Reports</strong> </a>
                </div>
              @else
                <div class="col-lg-12 top-heading-dash">
                  <a href="{{ url('/reports') }}" class="text-24 line-height-2 mb-2"> {{$reports}} </a><br>
                  <a href="{{ url('/reports') }}" class="mt-2 mb-0 sort_desc"> <strong>Total Reports</strong> </a>
                </div>
              @endif
            </div>

            @if(count($kams)>0)
              <div class="row mt-20">
                <div class="col-lg-12 below-heading-dash">
                  <a class="mt-2 mb-0" href="{{ url('/reports/?report_status1=completed&report_status2=interim&'.$report_card_url) }}" ><strong>Completed Report</strong>  </a>
                  <a class="text-wh text-18 line-height-2 counting" href="{{ url('/reports/?report_status1=completed&report_status2=interim&'.$report_card_url) }}"> {{$complete_report}} </a> 
                </div>
                <div class="col-lg-12 below-heading-dash">
                  <a class="mt-2 mb-0"  href="{{ url('/reports/?report_status=incomplete&'.$report_card_url) }}"><strong>Pending Report</strong>  </a>
                  <a class="text-wh text-18 line-height-2 counting"  href="{{ url('/reports/?report_status=incomplete&'.$report_card_url) }}"> {{$pending_report}} </a> 
                </div>
                {{-- <div class="col-lg-12 below-heading-dash">
                  <a class=" mt-2 mb-0"  href=" {{ url('/my/reports') }}"><strong>Completed</strong>  </a>
                  <a class="text-wh text-18 line-height-2 counting"  href=" {{ url('/my/reports') }}"> {{$reports}} </a>
                </div> --}}
              </div>  
            @else
              <div class="row mt-20">
                <div class="col-lg-12 below-heading-dash">
                  <a class="mt-2 mb-0" href="{{ url('/reports/?report_status1=completed&report_status2=interim') }}" ><strong>Completed Report</strong>  </a>
                  <a class="text-wh text-18 line-height-2 counting" href="{{ url('/reports/?report_status1=completed&report_status2=interim') }}"> {{$complete_report}} </a> 
                </div>
                <div class="col-lg-12 below-heading-dash">
                  <a class="mt-2 mb-0"  href="{{ url('/reports/?report_status=incomplete') }}"><strong>Pending Report</strong>  </a>
                  <a class="text-wh text-18 line-height-2 counting"  href="{{ url('/reports/?report_status=incomplete') }}"> {{$pending_report}} </a> 
                </div>
                {{-- <div class="col-lg-12 below-heading-dash">
                  <a class=" mt-2 mb-0"  href=" {{ url('/my/reports') }}"><strong>Completed</strong>  </a>
                  <a class="text-wh text-18 line-height-2 counting"  href=" {{ url('/my/reports') }}"> {{$reports}} </a>
                </div> --}}
              </div>
            @endif
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