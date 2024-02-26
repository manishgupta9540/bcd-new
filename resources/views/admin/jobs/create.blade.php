@extends('layouts.admin')
@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">
<div class="row">
   <div class="card text-left">
      <div class="card-body">
         <div class="row">
            <div class="col-md-8">
               <h4 class="card-title mb-1">Create a case </h4>
               <p>Select a Customer and a Candidate to start case.</p>
            </div>
            <div class="col-md-8">
               <form class="mt-2" method="post" action="{{ route('/job/store') }}">
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
                     <label for="job_name">Case Name</label>
                     <input type="text" name="job_name" class="form-control" placeholder="Case name" value="{{ old('job_name') }}">
                     <small class="form-text text-muted">(e.g Case-023)</small>
                     @if ($errors->has('job_name'))
                     <div class="error text-danger">
                        {{ $errors->first('job_name') }}
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
                  <div class="row">
                     <div class="col-md-8">
                        <h4 class="card-title mb-3 mt-2">Candidate detail</h4>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="name">First Name</label>
                     <input type="text" name="first_name" class="form-control" placeholder="Enter first name" value="{{ old('first_name') }}">
                     @if ($errors->has('first_name'))
                     <div class="error text-danger">
                        {{ $errors->first('first_name') }}
                     </div>
                     @endif
                  </div>
                  <div class="form-group">
                     <label for="name">Middle Name</label>
                     <input type="text" name="middle_name" class="form-control" placeholder="Enter middle name" value="{{ old('middle_name') }}">
                     @if ($errors->has('last_name'))
                     <div class="error text-danger">
                        {{ $errors->first('last_name') }}
                     </div>
                     @endif
                  </div>
                  <div class="form-group">
                     <label for="name">Last Name</label>
                     <input type="text" name="last_name" class="form-control" placeholder="Enter last name" value="{{ old('last_name') }}">
                     @if ($errors->has('last_name'))
                     <div class="error text-danger">
                        {{ $errors->first('last_name') }}
                     </div>
                     @endif
                  </div>
                  <div class="form-group">
                     <label for="phone">Phone</label>
                     <input type="text" name="phone" class="form-control" id="phone1" placeholder="" value="{{ old('phone') }}">
                     @if ($errors->has('phone'))
                     <div class="error text-danger">
                        {{ $errors->first('phone') }}
                     </div>
                     @endif
                  </div>
                  <div class="form-group">
                     <label for="email">Email</label>
                     <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" value="{{ old('email') }}">
                     @if ($errors->has('email'))
                     <div class="error text-danger">
                        {{ $errors->first('email') }}
                     </div>
                     @endif
                  </div>

                  <div class="form-group mt-2">
                  <div class='form-check form-check-inline'><label class='form-check-label' for=''>Send JAF Link</label></div>  
                       <!-- Rounded switch -->
                       <label class="switch">
                        <input type="checkbox" name="is_send_jaf_link">
                        <span class="slider round"></span>
                        </label>
                  </div>

                  <button type="submit" class="btn btn-primary mt-2">Submit</button>
               </form>
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