<div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4 " style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
    <div class="card-body pd-10 ">
      <i class="fa fa-paper-plane"></i>
        <div class="data-content">
            <div class="row">
              <div class="col-lg-12 top-heading-dash px-10">
              
                <a class="text-24 line-height-2 mb-0"  href=" {{ url('/my/checks') }}"> {{$total_checks}} </a><br>
                <a class="text-20 mt-2 mb-0 sort_desc"  href=" {{ url('/my/checks') }}"> <strong>Checks</strong> </a>
              </a>
              </div>
            </div>
            <div class="row mt-60">
              <div class="col-lg-12 below-heading-dash px-10">
              <a class="mt-2 mb-0"  href=" {{ url('/my/checks') }}"><strong>Completed</strong>  </a>
                <a class="text-wh text-18 line-height-2 counting"  href=" {{ url('/my/checks') }}"> {{$completed_checks}} </a>
               
              </div>
              <div class="col-lg-12 below-heading-dash px-10">
              <a class="mt-2 mb-0" href="{{ url('/my/candidates/?insuff=1') }}"><strong>Insuff Raised</strong>  </a>
                <a class="text-wh text-18 line-height-2 counting"  href="{{ url('/my/candidates/?insuff=1') }}"> {{$insuff_checks}} </a>
                
              </div>
              <div class="col-lg-12 below-heading-dash px-10">
              <a class="mt-2 mb-0"  href=" {{ url('/my/checks') }}"> <strong>Remaining</strong>  </a>
                <a class="text-wh text-18 line-height-2 mb-2 counting" href=" {{ url('/my/checks') }}"> {{$incompleted_checks}} </a>
               
              </div>
              
            </div>
            
        </div>
    </div>
</div>