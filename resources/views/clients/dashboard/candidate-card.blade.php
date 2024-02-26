<div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4 " style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
    <div class="card-body pd-10 ">
      <i class="fa fa-user"></i>
        <div class="data-content">
            <div class="row">
              <div class="col-lg-12 top-heading-dash">
                <a class="text-24 line-height-2 mb-2" href=" {{ url('/my/candidates') }}"> {{$candidates_count}} </a><br>
                <a class="text-20 mt-2 mb-0 sort_desc"  href=" {{ url('/my/candidates') }}"> <strong>Candidates</strong> </a>
              </div>
            </div>
            <div class="row mt-60">
              <div class="col-lg-12 below-heading-dash">
              <a class="mt-2 mb-0" href="{{ url('/my/candidates/?active_case=filled') }}" ><strong>Active Cases</strong>  </a>
                <a class="text-wh text-18 line-height-2 counting" href="{{ url('/my/candidates/?active_case=filled') }}"> {{$jaf_total_filled}} </a> 
               
              </div>
              <div class="col-lg-12 below-heading-dash">
              <a class="mt-2 mb-0"  href="{{ url('/my/candidates/?active_case1=pending&active_case2=draft') }}"><strong>Inactive</strong>  </a>
                <a class="text-wh text-18 line-height-2 mb-2 counting"  href="{{ url('/my/candidates/?active_case1=pending&active_case2=draft') }}"> {{$inactive_candidate}} </a> 
                
              </div>
              
            </div> 
            
        </div> 
    </div>
</div>