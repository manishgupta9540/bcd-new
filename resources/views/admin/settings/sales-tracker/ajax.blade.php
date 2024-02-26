<input type="hidden" name="company_name" class="company_name" value="{{json_encode($company_name)}}">
<input type="hidden" name="avg_week_case" class="avg_week_case" value="{{json_encode($avg_week_case)}}">
<input type="hidden" name="avg_monthly_case" class="avg_monthly_case" value="{{json_encode($avg_monthly_case)}}">
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
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">
                    Average Size of Transaction
                </div>
                <div id="avg_size_chart" class="avg_size_chart" style="border: 1px solid #ddd; padding:5px;"></div>
            </div>
        </div>
    </div>
    {{-- <div class="col-5">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">
                    Average Size of Transaction
                </div>
                <div id="avg_" style="border: 1px solid #ddd; padding:5px;"></div>
            </div>
        </div>
    </div>           --}}
</div>