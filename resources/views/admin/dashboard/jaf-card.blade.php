<div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
    <div class="card-body pd-10 ">
      <i class="fa fa-id-card"></i>
        <div class="data-content">
            <div class="row">
              @php
                $completed_jaf_url = '';
                if(count($kams)>0)
                {
                    $completed_jaf_url = 'candidate_kams_url=1';
                }
              @endphp
              @if ($completed_jaf_url)
                <div class="col-lg-12 top-heading-dash">
                  <a href="{{ url('/candidates'.'?active_case=filled'.'&'.$completed_jaf_url) }}" class="text-24 line-height-2 mb-2"> {{$completed_jaf}} </a><br>
                  <a href="{{ url('/candidates'.'?active_case=filled'.'&'.$completed_jaf_url) }}" class="mt-2 mb-0 sort_desc"> <strong>Completed JAF</strong> </a>
              </div>
              @else
                <div class="col-lg-12 top-heading-dash">
                  <a href="{{ url('/candidates?active_case=filled') }}" class="text-24 line-height-2 mb-2"> {{$completed_jaf}} </a><br>
                  <a href="{{ url('/candidates?active_case=filled') }}" class="mt-2 mb-0 sort_desc"> <strong>Completed JAF</strong> </a>
                </div>
              @endif
            </div>

            @if (count($kams)>0)
              <div class="row mt-20">
                <div class="col-lg-12 below-heading-dash">
                  <a href="{{ url('/candidates/?sendto=customer&jafstatus1=pending&jafstatus2=draft&'.$completed_jaf_url)}}" class="mt-2 mb-0"><strong> JAF Pending from Customer </strong></a>
                  <a href="{{ url('/candidates/?sendto=customer&jafstatus1=pending&jafstatus2=draft&'.$completed_jaf_url)}}" class="text-wh text-18 line-height-2 counting"> {{$completed_jaf_by_customer}} </a> 
                </div>
                <div class="col-lg-12 below-heading-dash">
                  <a href="{{ url('/candidates/?sendto=coc&jafstatus1=pending&jafstatus2=draft&'.$completed_jaf_url)}}" class="mt-2 mb-0"><strong> JAF Pending from COC </strong></a>
                  <a href="{{ url('/candidates/?sendto=coc&jafstatus1=pending&jafstatus2=draft&'.$completed_jaf_url)}}" class="text-wh text-18 line-height-2 counting"> {{$completed_jaf_by_coc}} </a>
                </div>
            
                <div class="col-lg-12 below-heading-dash">
                  <a href="{{ url('/candidates/?sendto=candidate&jafstatus1=pending&jafstatus2=draft&'.$completed_jaf_url)}}" class="mt-2 mb-0"><strong> JAF Pending form Candidate </strong></a>
                  <a href="{{ url('/candidates/?sendto=candidate&jafstatus1=pending&jafstatus2=draft&'.$completed_jaf_url)}}" class="text-wh text-18 line-height-2 counting"> {{$completed_jaf_by_candidate}} </a>
                </div>
              </div>
            @else
              <div class="row mt-20">
                  <div class="col-lg-12 below-heading-dash">
                    <a href="{{ url('/candidates/?sendto=customer&jafstatus1=pending&jafstatus2=draft')}}" class="mt-2 mb-0"><strong> JAF Pending from Customer </strong></a>
                    <a href="{{ url('/candidates/?sendto=customer&jafstatus1=pending&jafstatus2=draft')}}" class="text-wh text-18 line-height-2 counting"> {{$completed_jaf_by_customer}} </a> 
                  </div>
                  <div class="col-lg-12 below-heading-dash">
                    <a href="{{ url('/candidates/?sendto=coc&jafstatus1=pending&jafstatus2=draft')}}" class="mt-2 mb-0"><strong> JAF Pending from COC </strong></a>
                    <a href="{{ url('/candidates/?sendto=coc&jafstatus1=pending&jafstatus2=draft')}}" class="text-wh text-18 line-height-2 counting"> {{$completed_jaf_by_coc}} </a>
                  </div>
                  <div class="col-lg-12 below-heading-dash">
                    <a href="{{ url('/candidates/?sendto=candidate&jafstatus1=pending&jafstatus2=draft')}}" class="mt-2 mb-0"><strong> JAF Pending form Candidate </strong></a>
                    <a href="{{ url('/candidates/?sendto=candidate&jafstatus1=pending&jafstatus2=draft')}}" class="text-wh text-18 line-height-2 counting"> {{$completed_jaf_by_candidate}} </a>
                  </div>
                {{-- <div class="col-lg-3 below-heading-dash no-pd-data">
                  <a href="#" class="text-wh text-18 line-height-2 mb-2"> {{$incompleted_jaf_insuff}} </a>
                  <a href="#" class="mt-2 mb-0 text-wh"> Insufficiency </a>
                </div> --}}
              </div>
            @endif
        </div>
    </div>
</div>