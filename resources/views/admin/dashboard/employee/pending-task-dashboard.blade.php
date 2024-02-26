<div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
    <div class="card-body pd-10 ">
      <i class="fas fa-clipboard-list"></i>
        <div class="data-content">
            <div class="row">
              <div class="col-lg-12 top-heading-dash">
                <a class="text-24 line-height-2 mb-2 pending_card_link" href="{{ url('/task/assign?t_type=verify_task&verify_status=1') }}"> {{count($total_pending_task)}} </a><br>
                <a class="mt-2 mb-0 pending_card_link sort_des" href="{{ url('/task/assign?t_type=verify_task&verify_status=1') }}"> <strong>Total Pending Case</strong> </a>
              </div>
            </div>
            <div class="row mt-30">
              <div class="col-lg-12 below-heading-dash">
              <a href="{{ url('/task/assign?t_type=verify_task&verify_status=1&in_tat=1') }}" class="mt-2 mb-0  pending_card_link"><strong>In TAT </strong></a>
                <a href="{{ url('/task/assign?t_type=verify_task&verify_status=1&in_tat=1') }}" class="text-wh text-18 line-height-2  pending_card_link counting"> {{$total_pending_task_in}} </a>
              
              </div>
              <div class="col-lg-12 below-heading-dash">
              <a href="{{ url('/task/assign?t_type=verify_task&verify_status=1&out_tat=1') }}" class="mt-2 mb-0 pending_card_link"><strong> Out of TAT </strong></a>
                <a href="{{ url('/task/assign?t_type=verify_task&verify_status=1&out_tat=1') }}" class="text-wh text-18 line-height-2 mb-2 pending_card_link counting"> {{$total_pending_task_out}} </a>
                
              </div>
            </div>
        </div>
    </div>
</div>