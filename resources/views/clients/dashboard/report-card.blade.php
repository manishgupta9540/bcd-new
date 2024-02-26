<div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
    <div class="card-body pd-10 ">
      <i class="fa fa-book"></i>
        <div class="data-content">
            <div class="row">
              <div class="col-lg-12 top-heading-dash px-10">
                <a class="text-24 line-height-2 mb-2 " href=" {{ url('/my/reports') }}"> {{$reports}} </a><br>
                <a class="text-20 mt-2 mb-0 sort_desc"  href=" {{ url('/my/reports') }}"><strong>Reports Received</strong> </a>
              </div>
            </div>
            <div class="row mt-60">
              <div class="col-lg-12 below-heading-dash px-10">
              <a class="mt-2 mb-0" href="{{ url('/my/reports?report_status1=completed&report_status2=interim') }}" >
                <strong>Completed</strong>  
              </a>
                <a class="text-wh text-18 line-height-2 counting" href="{{ url('/my/reports/?report_status1=completed&report_status2=interim') }}">
                   {{$complete_report}} 
                  </a> 
                
              </div>
              <div class="col-lg-12 below-heading-dash px-10">
              <a class="mt-2 mb-0"  href="{{ url('/my/reports/?report_status=incomplete') }}"><strong>Pending</strong>  </a>
                <a class="text-wh text-18 line-height-2 mb-2 counting"  href="{{ url('/my/reports/?report_status=incomplete') }}"> {{$pending_report}} </a> 
               
              </div>
            </div>
            
        </div>
    </div>
</div>