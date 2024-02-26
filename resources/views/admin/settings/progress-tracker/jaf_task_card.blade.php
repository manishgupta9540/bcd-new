{{-- <input type="hidden" name="duration_type" class="duration_type" value="{{$type}}">
<input type="hidden" name="duration_from" class="duration_from" value="{{date('d M Y',strtotime($from_date))}}">
<input type="hidden" name="duration_to" class="duration_to" value="{{date('d M Y',strtotime($to_date))}}"> --}}
<input type="hidden" name="jaf_assign" class="jaf_assign" value="{{json_encode($jaf_assign_arr)}}">
<input type="hidden" name="jaf_completed" class="jaf_completed" value="{{json_encode($jaf_completed_arr)}}">
<div class="card mb-4">
    <div class="card-body">
        <div class="card-title">
            Task JAF Filling Assigned / Completed
        </div>
        <div id="jaf_task_chart" class="jaf_task_chart" style="border: 1px solid #ddd; padding:5px;"></div>
    </div>
</div>