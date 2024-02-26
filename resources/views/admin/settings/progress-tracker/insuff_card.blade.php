{{-- <input type="hidden" name="duration_type" class="duration_type" value="{{$type}}">
<input type="hidden" name="duration_from" class="duration_from" value="{{date('d M Y',strtotime($from_date))}}">
<input type="hidden" name="duration_to" class="duration_to" value="{{date('d M Y',strtotime($to_date))}}"> --}}
<input type="hidden" name="raise_insuff" class="raise_insuff" value="{{json_encode($raise_insuff_arr)}}">
<input type="hidden" name="clear_insuff" class="clear_insuff" value="{{json_encode($clear_insuff_arr)}}">
<div class="card mb-4">
    <div class="card-body">
        <div class="card-title">
            Insufficiency
        </div>
        <div id="insuff_chart" class="insuff_chart" style="border: 1px solid #ddd; padding:5px;"></div>
    </div>
</div>