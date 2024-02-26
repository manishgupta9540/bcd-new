{{-- <input type="hidden" name="duration_type" class="duration_type" value="{{$type}}">
<input type="hidden" name="duration_from" class="duration_from" value="{{date('d M Y',strtotime($from_date))}}">
<input type="hidden" name="duration_to" class="duration_to" value="{{date('d M Y',strtotime($to_date))}}"> --}}
<input type="hidden" name="total_hrs" class="total_hrs" value="{{json_encode($total_hrs_arr)}}">
<div class="card mb-4">
    <div class="card-body">
        <div class="card-title">
            Total Hours of an Employee Login
        </div>
        <div id="emp_login_chart" class="emp_login_chart" style="border: 1px solid #ddd; padding:5px;"></div>
    </div>
</div>