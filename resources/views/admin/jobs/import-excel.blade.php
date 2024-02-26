@extends('layouts.admin')
@section('content')

      <div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
            <div class="main-content">				
 
                <div class="row">
                    <div class="card text-left">
                        <div class="card-body">
			   
                            <div class="row">
                                <div class="col-md-12">

                                    @if ($message = Session::get('error'))
                                    <div class="alert alert-danger">
                                    <strong>{{ $message }}</strong> 
                                    </div>
                                    @endif

                                    @if ($message = Session::get('success'))
                                    <div class="alert alert-success">
                                    <strong>{{ $message }}</strong> 
                                    </div>
                                    @endif

                                    <h3 class="card-title mb-3"> Create a Case </h3> 
                                    <h4 class="card-title"> Instructions for fresh upload </h4>
                                    <div class="data-create">
                                        <ol>
                                            <li>Download the template <a href="{{ asset('job-candidate.csv') }}" download>Click Here</a></li>
                                            <li>Enter text only, Do not chnage the header sequence or column name</li>
                                            <li>If button is disable that means some fields might be invalidly filled</li>
                                            <li>Upload (.csv only)</li>
                                        </ol>
                                    </div>
                                    <div class="uploader">
                                    <div class="col-md-8">
                                    <form method="post" enctype="multipart/form-data" action="{{ url('/job/store/excel') }}">
                                        @csrf
                                       
                                        <!-- select customer  -->
                                        <div class="form-group">
                                            <label for="service">Customer</label>
                                            <select class="form-control customer" name="customer">
                                                <option value="">-Select-</option>
                                                @if( count($customers) > 0 )
                                                @foreach($customers as $item)
                                                <option value="{{ $item->id }}">{{ $item->company_name.' '.'('.$item->name.')' }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @if ($errors->has('customer'))
                                            <div class="error text-danger">
                                                {{ $errors->first('customer') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                        <label for="service">SLA</label>
                                        <select class="form-control slaList" name="sla">
                                            <option value="">-Select-</option>
                                        
                                        </select>
                                        @if ($errors->has('sla'))
                                        <div class="error text-danger">
                                            {{ $errors->first('sla') }}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="form-group SLAResult"></div>
                                        
                                        <div class="form-group">
                                        <label for="service">Select a file</label>
                                        <input class="form-control" type="file" id="csv_file" name="csv_file">
                                        @if ($errors->has('csv_file'))
                                        <div class="error text-danger">
                                            {{ $errors->first('csv_file') }}
                                        </div>
                                        @endif
                                        </div>
                                       
                                        <div class="form-group">
                                        <label for="service">Job Name</label>
                                            <input type="text" class="form-control" name="job_name" placeholder=" Enter job name" value="{{ old('job_name') }}">
                                            <small class="text-muted">Tip: Add a job's nickname to identify the easyly (example- PCIL-10-EMP)</small>
                                            @if ($errors->has('job_name'))
                                            <div class="error text-danger">
                                                {{ $errors->first('job_name') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="form-group mt-2">
                                            <div class='form-check form-check-inline'><label class='form-check-label' for=''>Send JAF Link</label></div>  
                                            <!-- Rounded switch -->
                                            <label class="switch">
                                                <input type="checkbox" name="is_send_jaf_link"><span class="slider round"></span>
                                            </label>
                                        </div>
                                       
                                        <div class="form-group mt-2">
                                        <button type="submit" class="btn btn-success">Upload</button>
                                        </div>
                                       
                                    </form>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
				    </div>
                </div>
				
            </div>
			
        </div>


<script>
 
 $(function(){
     $('.customer').prop('selectedIndex',0);
 
     $(document).on('change','.customer',function(e) {
         e.preventDefault();
         $('.slaList').empty();
         $('.slaList').append("<option value=''>-Select-</option>");
         $(".SLAResult").html("");
 
         var customer = $('.customer option:selected').val();
         $.ajax({
         type:"POST",
         url: "{{ url('/customers/sla/getlist') }}",
         data: {"_token": "{{ csrf_token() }}",'customer_id':customer},      
         success: function (response) {
             console.log(response);
             if(response.success==true  ) {   
                 $.each(response.data, function (i, item) {
                     $(".slaList").append("<option value='"+item.id+"'>" + item.title + "</option>");
                 });
             }
             //show the form validates error
             if(response.success==false ) {                              
                 for (control in response.errors) {   
                     $('#error-' + control).html(response.errors[control]);
                 }
             }
         },
         error: function (xhr, textStatus, errorThrown) {
             // alert("Error: " + errorThrown);
         }
     });
     return false;
         
     });
 
     //on select sla item
     $(document).on('change','.slaList',function(e) {
         e.preventDefault();
         $(".SLAResult").html("");
         var sla_id = $('.slaList option:selected').val();
         $.ajax({
         type:"POST",
         url: "{{ url('/customer/mixSla/serviceItems') }}",
         data: {"_token": "{{ csrf_token() }}",'sla_id':sla_id},      
         success: function (response) {
             console.log(response);
             if(response.success==true  ) {   
                 $.each(response.data, function (i, item) {
                    if(item.checked_atatus){$(".SLAResult").append("<div class='form-check form-check-inline'><input class='form-check-input services_list' type='checkbox' checked name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' data-type=''><label class='form-check-label' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
                    }else{
                        $(".SLAResult").append("<div class='form-check form-check-inline'><input class='form-check-input services_list' type='checkbox' name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' data-type=''><label class='form-check-label' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
                    }
                 });
             }
             //show the form validates error
             if(response.success==false ) {                              
                 for (control in response.errors) {   
                     $('#error-' + control).html(response.errors[control]);
                 }
             }
         },
         error: function (xhr, textStatus, errorThrown) {
             // alert("Error: " + errorThrown);
         }
     });
     return false;
     });
 
 });
 
 </script>

@endsection