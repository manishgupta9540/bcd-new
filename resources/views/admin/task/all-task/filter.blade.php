<div class="row">
    <div class="col-12">           
        <div class="btn-group" style="float:right;font-size:24px;">   
            <a href="#" class="filter_close text-danger"><i class="far fa-times-circle"></i></a>        
        </div>
    </div>
 </div>
 <div class="row">
     <div class="col-md-2 form-group mb-1">
         <label> From date </label>
         <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
     </div>
     <div class="col-md-2 form-group mb-1">
         <label> To date </label>
         <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
     </div>
     <div class="col-md-2 form-group mb-1">
        <label>Reference number </label><br>
        {{-- <select class="form-control ref_list select w-100" name="ref[]" id="ref" multiple="multiple">
            @foreach ($candidates as $candidate)
                <option value="{{$candidate->display_id }}"> {{$candidate->display_id }} </option>
            @endforeach
        </select> --}}
        <input class="form-control ref" type="text" placeholder="reference number">
     </div>
     <div class="col-md-3 form-group mb-1 level_selector">
       <label>Customer</label><br>
       <select class="form-control customer_list select" name="customer" id="customer">
          <option value=''>-All-</option>
           @foreach($clients as $item)
           <option value="{{$item->id}}"> {{ ucfirst($item->company_name)}} </option>
           @endforeach
       </select>
     </div>
     <div class="col-md-2 form-group mb-1 level_selector">
         <label>Candidate Name</label><br>
         <select class="form-control candidate_list select " name="candidate" id="candidate">
          <option value=''>-Select-</option>
         </select>
     </div>
     <div class="col-md-2 form-group mb-1 level_selector">
       <label>User Type</label><br>
       <select class="form-control user_typ select" name="user_typ" id="user_typ">
          <option value=''>-Select-</option>
          <option  value="user" >User</option>
          <option  value="vendor">Vendor</option>
       </select>
      
     </div> 
     <div class="col-md-2 form-group mb-1 level_selector users_list ">
          <label>User/Vendor Name</label><br>
          <select class="form-control user_list select" name="user" id="user">
             <option value=''>-Select-</option>
             {{-- @foreach($users_list as $item)
                <option value="{{$item->id}}"> {{ ucfirst($item->name)}} </option>
             @endforeach --}}
          </select>
     </div>
     <div class="col-md-2 form-group mb-1">
          <label>Checks</label>
          <select class="form-control "  name="service" id="service">
             <option value="">Select</option>
             @foreach ($services as $service)
                <option value="{{ $service->id }}" >{{ $service->name }}</option> 
             @endforeach
          </select>
     </div>
     <div class="col-md-2 form-group mb-1">
        <label>Task's Type</label>
        <select class="form-control" name="task_type" id="task_type" >
            <option value="">All</option>
            <option  value="JAF Filling" >JAF Filling</option>
            <option  value="Task for Verification " >Task Verification</option>
            <option  value="Report generation" >Report Generation</option>
        </select>
     </div>
     <div class="col-md-3 form-group mb-1">
        <label>Assign Status</label>
        <select class="form-control" name="assign_status" id="assign_status" >
            <option value="">All</option>
            <option  value="assigned" >Assigned</option>
            <option  value="unassigned" >Unassign</option>
        </select>
     </div>
    <div class="col-md-2 form-group mb-1">
        <label>Complete Status</label>
        <select class="form-control" name="complete_status" id="complete_status" >
            <option value="">All</option>
            <option  value="0" >Pending</option>
            <option  value="1" >Completed</option>
        </select>
    </div>
    <div class="col-md-1">
        <button class="btn btn-danger  resetBtn" style="padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i>  Reset </button>
    </div>
    <div class="col-md-1">
        <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
    </div>
 </div>
 <script>
    $("#akiko").select2();
    $("#candidate").select2();
    $("#ref").select2();
    $("#customer").select2();
    $("#sla").select2();
    $("#user").select2();
    $(".check").select2();
    $(".rows").select2();
    // $("#report_users").select2();
    $('.filter0search').click(function(){
        $('.search-drop-field').toggle();
    });
    $('.filter_close').click(function(){
        $('.search-drop-field').toggle();
    });

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