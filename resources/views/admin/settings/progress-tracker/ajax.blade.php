<input type="hidden" name="duration_type" class="duration_type" value="{{$type}}">
<input type="hidden" name="duration_from" class="duration_from" value="{{date('d M Y',strtotime($from_date))}}">
<input type="hidden" name="duration_to" class="duration_to" value="{{date('d M Y',strtotime($to_date))}}">
{{-- <style>
.avg_size_chart{
  height:300px !important;
  min-height:300px !important;
  overflow-y:scroll !important;
}
</style> --}}
<div class="row pt-2">
    <div class="col-12 total_hours">
       
    </div>
</div>
<div class="row">
    <div class="col-6 jaf_task">
       
    </div>
    <div class="col-6 task_verification">
        
    </div>
</div>
<div class="row">
    <div class="col-12 insuff_card">
        
    </div>
</div>