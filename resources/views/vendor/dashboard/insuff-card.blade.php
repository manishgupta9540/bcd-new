<div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
    <div class="card-body pd-10 ">
      <i class="fa fa-user"></i>
        <div class="data-content">
            <div class="row">
              <div class="col-lg-12 top-heading-dash">
                <a class="text-24 line-height-2 mb-2" href="{{ url('/vendor/task') }}"> {{$insuff_count}} </a><br>
                <a class="mt-2 mb-0 sort_desc" href="{{ url('/vendor/task') }}"> <strong>Total Insuff</strong> </a>
              </div>
            </div>
            
            <div class="row mt-20">
              <div class="col-lg-12 below-heading-dash">
                <a href="{{ url('/vendor/task/?raiseinsuff=raise') }}" class="mt-2 mb-0 "><strong>Raise insuff </strong></a>
                <a href="{{ url('/vendor/task/?raiseinsuff=raise') }}" class="text-18 line-height-2 mb-2 counting"> {{$raise_insuff_count}} </a>
              </div>

              <div class="col-lg-12 below-heading-dash">
              <a href="{{ url('/vendor/task/?raiseinsuff=cleared') }}" class="mb-0"><strong> Clear insuff </strong></a>
                <a href="{{ url('/vendor/task/?raiseinsuff=cleared') }}" class="text-18 line-height-2 mb-2 counting"> {{$clear_insuff_count}} </a>
              </div>
            </div>
           
        </div>
    </div>
</div>