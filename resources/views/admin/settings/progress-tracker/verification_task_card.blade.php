<input type="hidden" name="ver_assign" class="ver_assign" value="{{json_encode($verification_assign_arr)}}">
<input type="hidden" name="ver_completed" class="ver_completed" value="{{json_encode($verification_completed_arr)}}">
<div class="card mb-4">
    <div class="card-body">
        <div class="card-title">
            Task Verification Assigned / Completed
        </div>
        <div id="verification_task_chart" class="verification_task_chart" style="border: 1px solid #ddd; padding:5px;"></div>
    </div>
</div>