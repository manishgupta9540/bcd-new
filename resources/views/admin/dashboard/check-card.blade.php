<div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
    <div class="card-body pd-10 ">
      <i class="fa fa-check"></i>
        <div class="data-content">
            <div class="row">
              <div class="col-lg-12 top-heading-dash">
                <a href="{{ url('/jobs') }}" class="text-24 line-height-2 mb-2"> {{$total_checks}} </a><br>
                <a href="{{ url('/jobs') }}" class="mt-2 mb-0 sort_desc"> <strong>Total checks</strong> </a>
              </div>
            </div>
            <div class="row mt-20">
              <div class="col-lg-12 below-heading-dash">
              <a href="{{ url('/jobs') }}" class="mt-2 mb-0"><strong> Completed Checks </strong></a>
                <a href="{{ url('/jobs') }}" class="text-wh text-18 line-height-2 counting">{{$completed_checks}}</a>
               
              </div>
              <div class="col-lg-12 below-heading-dash">
              <a href="{{ url('/jobs') }}" class="mt-2 mb-0"><strong> Pending Checks </strong></a>
                <a href="{{ url('/jobs') }}" class="text-wh text-18 line-height-2 counting"> {{$incompleted_checks}} </a>
                
              </div>
              
            </div>
            
        </div>
    </div>
</div>