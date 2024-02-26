<input type="hidden" name="duration_type" class="duration_type" value="{{$type}}">
<input type="hidden" name="duration_from" class="duration_from" value="{{date('d M Y',strtotime($from_date))}}">
<input type="hidden" name="duration_to" class="duration_to" value="{{date('d M Y',strtotime($to_date))}}">
<input type="hidden" name="company_name" class="company_name" value="{{json_encode($client_company_name)}}">
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
                    Total Number of Users (Client - Wise)
                </div>
                <div id="total_coc_users" class="total_coc_users" style="border: 1px solid #ddd; padding:5px;"></div>
            </div>
        </div>
    </div>
</div>