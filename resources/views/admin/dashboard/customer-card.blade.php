<div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
    <div class="card-body pd-10 ">
      <i class="fa fa-user"></i>
        <div class="data-content">
            <div class="row">
              @php
                $kams_url = '';
                if(count($kams)>0)
                {
                    $kams_url = '?kams_user=1';
                }
              @endphp
              <div class="col-lg-12 top-heading-dash">
                <a class="text-24 line-height-2 mb-2" href="{{ url('/customers'.$kams_url) }}"> {{$customers_count}} </a><br>
                <a class="mt-2 mb-0 sort_desc" href="{{ url('/customers'.$kams_url) }}"> <strong>Total Customers</strong> </a>
              </div>
            </div>
            @if($kams_url)
            <div class="row mt-20">
              <div class="col-lg-12 below-heading-dash">
                <a href="{{ url('/customers'.$kams_url .'&active_case=1') }}" class="mt-2 mb-0 "><strong>Active Customer </strong></a>
                <a href="{{ url('/customers'.$kams_url .'&active_case=1') }}" class="text-18 line-height-2 mb-2 counting"> {{$customers_active}} </a>
              </div>

              <div class="col-lg-12 below-heading-dash">
              <a href="{{ url('/customers'.$kams_url .'&active_case=0') }}" class="mb-0"><strong> Inactive Customer </strong></a>
                <a href="{{ url('/customers'.$kams_url .'&active_case=0') }}" class="text-18 line-height-2 mb-2 counting"> {{$customers_inactive}} </a>
              </div>
            </div>
            @else
              <div class="row mt-20">
                <div class="col-lg-12 below-heading-dash">
                  <a href="{{ url('/customers?active_case=1') }}" class="mt-2 mb-0 "><strong>Active Customer </strong></a>
                  <a href="{{ url('/customers?active_case=1') }}" class="text-18 line-height-2 mb-2 counting"> {{$customers_active}} </a>
                </div>

                <div class="col-lg-12 below-heading-dash">
                <a href="{{ url('/customers?active_case=0') }}" class="mb-0"><strong> Inactive Customer </strong></a>
                  <a href="{{ url('/customers?active_case=0') }}" class="text-18 line-height-2 mb-2 counting"> {{$customers_inactive}} </a>
                </div>
              </div>
            @endif
        </div>
    </div>
</div>