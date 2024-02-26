<div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4 " style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
    <div class="card-body pd-10 ">
      <i class="fa fa-users"></i>
        <div class="data-content">
            <div class="row">
              <div class="col-lg-12 top-heading-dash">
                <a class="text-24 line-height-2 mb-2 candidate_count" href="{{url('/candidates')}}"> {{$candidate_count}} </a><br>
                <a class="mt-2 mb-0 sort_desc" href="{{url('/candidates')}}"> <strong>Total Candidate</strong> </a>
              </div>
            </div>
            <div class="row mt-30">
            <div class="col-lg-12 below-heading-dash">
                <a href="{{ url('/candidates/?sendto=customer') }}" class="mt-2 mb-0"><strong> JAF send to customer </strong></a>
                <a href="{{ url('/candidates/?sendto=customer') }}" class="text-18 line-height-2  jaf_customer_count counting"> {{$jaf_send_to_customer}} </a>
               
                </div>
              <div class="col-lg-12 below-heading-dash">
              <a href="{{ url('/candidates/?sendto=coc') }}" class="mt-2 mb-0"><strong> JAF send to COC </strong></a>
                <a href="{{ url('/candidates/?sendto=coc') }}" class="text-wh text-18 line-height-2 jaf_coc_count counting"> {{$jaf_send_to_coc}} </a>
                
              </div>
              
              <div class="col-lg-12 below-heading-dash">
              <a href="{{ url('/candidates/?sendto=candidate') }}" class="mt-2 mb-0"><strong> JAF send to candidate </strong></a>
                <a href="{{ url('/candidates/?sendto=candidate') }}" class="text-wh text-18 line-height-2 mb-2 jaf_candidate_count counting"> {{$jaf_send_to_candidate}} </a>
                
              </div>
            </div>
        </div>
    </div>
</div>