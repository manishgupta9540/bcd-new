<div class="row">
    <div class="col-12">           
        <div class="btn-group" style="float:right;font-size:24px;">   
            <a href="#" class="filter_close text-danger"><i class="far fa-times-circle"></i></a>        
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 form-group mb-1 level_selector">
        <label for="picker1"> Customer </label>
        <select class="form-control customer_list select" name="customer" id="customer">
        <option>-Select-</option>
        @foreach($customers as $customer)
            <option value="{{ $customer->id }}"> {{ $customer->company_name.' - '.$customer->name}} </option>
        @endforeach
        </select>
    </div>
    <div class="col-md-3 form-group mb-1 level_selector">
        <label>Candidate name</label><br>
        <select class="form-control candidate_list select" name="candidate" id="candidate">
            <option>-Select-</option>
        </select>
        {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
    </div>
    <div class="col-md-2 form-group mb-1">
        <label> From date </label>
        <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
    </div>
    <div class="col-md-2 form-group mb-1">
        <label> To date </label>
        <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
    </div>
    <div class="col-md-2 form-group mb-1">
        <label>JAF From Date </label>
        <input class="form-control jaf_from_date commonDatePicker" type="text" placeholder="From date">
    </div>
    <div class="col-md-2 form-group mb-1">
        <label>JAF To Date </label>
        <input class="form-control jaf_to_date commonDatePicker" type="text" placeholder="To date">
    </div>
    <div class="col-md-2 form-group mb-1">
        <label>Phone number </label>
        <input class="form-control mob" type="text" placeholder="phone">
    </div>
    <div class="col-md-2 form-group mb-1 level_selector">
        <label>Reference number </label><br>
        {{-- <select class="form-control ref_list select" name="ref[]" id="ref" multiple>
            <option>-Select-</option>
            
        </select> --}}
       
        {{-- {{-- <label>Reference number </label><br>
        <select class="form-control ref_list select w-100" name="ref[]" id="ref" multiple="multiple">
            @foreach ($candidates as $candidate)
                <option value="{{$candidate->display_id }}"> {{$candidate->display_id }} </option>
            @endforeach
        </select> --}}
        <input class="form-control ref" type="text" placeholder="reference number">
    </div>
    <div class="col-md-2 form-group mb-1">
        <label>Email id</label>
        <input class="form-control email" type="email" placeholder="email">
    </div>
    <div class="col-md-2 form-group mb-1">
        <label>JAF send to</label>
        <select class="form-control "  name="remain" id="remain">
            <option value="">All</option>
            <option value="customer" >Customer</option>
            <option value="coc" >COC</option>
            <option value="candidate" >Candidate</option>
        </select>
    </div>
    <div class="col-md-2 form-group mb-1">
        <label>JAF filled</label>
        <select class="form-control" name="jaf_filled" id="active_case" >
            <option value="">All</option>
            <option  value="filled" <?=$filled=="filled"?"selected":""?>>Filled</option>
            <option  value="draft" <?=$filled=="draft"?"selected":""?>>Draft</option>
            <option  value="pending"  <?=$filled=="pending"?"selected":""?> >Pending</option>
        </select>
    </div>
    <div class="col-md-2 form-group mb-1">
        <label>Insuff raised in</label>
        <select class="form-control" name="insuff_raised" id="insuff_raised" >
            <option value="">All</option>
            @foreach($array_result as $result)
             <option value="{{$result['check_id']}}"> {{$result['check_name']}} ({{$result['insuf']}})</option>
             @endforeach
        </select>
    </div>
    <div class="col-md-2 form-group mb-1">
        <label>Service</label>
        <select class="form-control" name="service_name" id="service_name" >
            <option value="">All</option>
            @foreach($services as $service)
                <option value="{{ $service->id}}">{{ $service->name  }}</option>   
            @endforeach
        </select>
    </div>
    
    <div class="col-md-1">
        <button class="btn btn-danger resetBtn" style="padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i>  Reset </button>
     </div>
    <div class="col-md-1">
        <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
    </div>
</div>
<script>
    $(".select").select2();
    var uriNum = location.hash;
    pageNumber = uriNum.replace("#", "");
    // alert(pageNumber);
    getData(pageNumber);
    
    $( ".commonDatepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        firstDay: 1,
        autoclose:true,
        todayHighlight: true,
        format: 'dd-mm-yyyy',
    });

</script>