<div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
    <div class="card-body pd-10 ">
      <i class="fa fa-user"></i>
        <div class="data-content">
            <div class="row">
              <div class="col-lg-12 top-heading-dash">
                <a class="text-24 line-height-2 mb-2" href="{{url('/vendor/task')}}">{{count($tasks)}}  </a><br>
                <a class="mt-2 mb-0 sort_desc" href="{{url('/vendor/task')}}"> <strong>Total TAT</strong> </a>
              </div>
            </div>
            
            <div class="row mt-20">
              <div class="col-lg-12 below-heading-dash">
                <a href="{{url('/vendor/task/?in_tat=1')}}" class="mt-2 mb-0 "><strong>in TAT </strong></a>
                <a href="{{ url('/vendor/task/?in_tat=1') }}" class="text-18 line-height-2 mb-2 counting"> {{$total_in}} </a>
              </div>

              <div class="col-lg-12 below-heading-dash">
                <a href="{{url('/vendor/task/?out_tat=1')}}" class="mb-0"><strong> out TAT </strong></a>
                <a href="{{ url('/vendor/task/?out_tat=1') }}" class="text-18 line-height-2 mb-2 counting"> {{$total_out}} </a>
              </div>
            </div>
           
        </div>
    </div>
</div>