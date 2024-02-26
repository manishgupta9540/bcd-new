<div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
    <div class="card-body pd-10 ">
        <i class="fa fa-id-card"></i>
        <div class="data-content">
            <div class="row">
              <div class="col-lg-12 top-heading-dash">
                <a class="text-24 line-height-2 mb-2" href="{{ url('/task/assign?t_type=verify_task&verify_status=all') }}"> {{count($total_allocated_task)}} </a><br>
                <a class="mt-2 mb-0 card_link sort_desc" href="{{ url('/task/assign?t_type=verify_task&verify_status=all') }}" style="font-size:16px;"> <strong>Total Allotted Case</strong> </a>
              </div>
            </div>
            <div class="row mt-30">
             
            </div>
        </div>
    </div>
</div>