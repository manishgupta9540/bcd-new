@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Account / Billing </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/app/home') }}">Dashboard</a>
             </li>
             <li>
                 <a href="{{ url('/app/settings/promocode') }}">Promocode</a>
             </li>
             <li>Edit</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow">
             <div class="text-right">
             <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
      </div>
      <div class="row">
        <div class="col-md-3 content-container">
            <!-- left-sidebar -->
            @include('superadmin.settings.left-sidebar') 
        </div>
        <!-- start right sec -->
        <div class="col-md-9 content-wrapper" style="background:#fff">
            <div class="formCover" style="height: 100vh;">
            <!-- section -->
            <section>
                <div class="col-sm-12 ">
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title mb-1 mt-3">Edit Promocode</h4>
                                <p class="pb-border"> </p>
                            </div>
                            {{-- <div class="col-md-6 text-right">
                                <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a>
                            </div> --}}
                        </div>
                        <form method="post" action="{{ url('/app/settings/promocode/update') }}" id="editpromocode" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="p_id" value="{{base64_encode($promocode->id)}}">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Code Name <span class="text-danger">*</span></label>
                                        <input class="form-control code_name" type="text" name="code_name" value="{{$promocode->title}}">
                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-code_name"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Type <span class="text-danger">*</span></label>
                                        <select class="form-control type" name="type">
                                            <option value="">-Select-</option>
                                            <option @if($promocode->discount_type=='percentage') selected @endif value="percentage">Percentage</option>
                                            <option @if($promocode->discount_type=='fixed_amount') selected @endif value="fixed_amount">Fixed Amount</option>
                                        </select>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-type"></p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Value <span class="text-danger">*</span></label>
                                        <input class="form-control value" type="text" name="value"  @if($promocode->discount_type=='percentage') value="{{$promocode->discount}}" @else value="{{intval($promocode->discount)}}" @endif>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-value"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Uses Limit <span class="text-danger">*</span></label>
                                        <input class="form-control value" type="number" name="uses_limit" value="{{$promocode->uses_limit}}" min="1">
                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-uses_limit"></p>
                                    </div>
                                </div> 
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Start Date <span class="text-danger">*</span></label>
                                        <input class="form-control commonDatepicker start_date" type="text" id="start_date" name="start_date" value="{{$promocode->start_date!=NULL ? date('d-m-Y',strtotime($promocode->start_date)):''}}">
                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-start_date"></p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> Start Time <span class="text-danger">*</span></label>
                                        <input class="form-control timepicker1 start_time" type="text" name="start_time" value="{{$promocode->start_date!=NULL ? date('h:i a',strtotime($promocode->start_date)):'12:00 am'}}">
                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-start_time"></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>End Date <span class="text-danger">*</span></label>
                                        <input class="form-control commonDatepicker end_date" type="text" id="end_date" name="end_date" value="{{$promocode->end_date!=NULL ? date('d-m-Y',strtotime($promocode->end_date)):''}}">
                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-end_date"></p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> End Time <span class="text-danger">*</span></label>
                                        <input class="form-control timepicker1 end_time" type="text" name="end_time" value="{{$promocode->end_date!=NULL ? date('h:i a',strtotime($promocode->end_date)):'12:00 am'}}">
                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-end_time"></p>
                                    </div>
                                </div>
                            </div>
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-all"></p>
                            <!-- ./business detail -->
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-success submit">Update</button>
                                </div>
                            </div>
                        </form>
                </div>
            </section>
            <!-- ./section -->
            <!--  -->
            <!-- ./section -->
            </div>
        </div>
        <!-- end right sec -->
      </div>
   </div>
</div>
@stack('scripts')

<script>
    
    $(document).on('change','.start_date',function() {

        var from = $('.start_date').datepicker('getDate');
        var to_date   = $('.end_date').datepicker('getDate');

        if($('.end_date').val() !=""){
            if (from > to_date) {
                alert ("Please select appropriate date range!");
                $('.start_date').val("");
                $('.end_date').val("");
            }

        }

        // alert(from);
        // alert(to_date);

        // alert("heello");

    });

    $(document).on('change','.end_date',function() {

            var to_date = $('.end_date').datepicker('getDate');
            var from   = $('.start_date').datepicker('getDate');
            if($('.start_date').val() !=""){
                if (from > to_date) {
                    alert ("Please select appropriate date range!");
                    $('.start_date').val("");
                    $('.end_date').val("");
                }
            }


    });

    

    $(document).on('change keyup','.start_time',function() {

        var to_date = $('.end_date').val();
        var from   = $('.start_date').val();

        var from_time= $('.start_time').val();

        var to_time= $('.end_time').val();

        if($('.start_date').val() !="" && $('.end_date').val()!=""){
            var start = Date.parse(from+' '+from_time)/1000;
            var end = Date.parse(to_date+' '+to_time)/1000;

            var diff = end - start;

            // console.log(diff);

            // console.log(start);

            // console.log(end);

            if(diff<=0)
            {
                alert ("Please select appropriate date time range!");
                $('.start_time').val("12:00 am");
                $('.end_time').val("12:15 am");
            }
        }


    });

    $(document).on('change keyup','.end_time',function() {

        var to_date = $('.end_date').val();
        var from   = $('.start_date').val();

        var from_time= $('.start_time').val();

        var to_time= $('.end_time').val();

        if($('.start_date').val() !="" && $('.end_date').val()!=""){
            var start = Date.parse(from+' '+from_time)/1000;
            var end = Date.parse(to_date+' '+to_time)/1000;

            var diff = end - start;

            // console.log(diff);

            // console.log(start);

            // console.log(end);

            if(diff<=0)
            {
                alert ("Please select appropriate date time range!");
                $('.start_time').val("12:00 am");
                $('.end_time').val("12:15 am");
            }
        }


    });

    


    $(document).on('submit', 'form#editpromocode', function (event) {
      event.preventDefault();
      //clearing the error msg
      $('p.error_container').html("");
      $('.form-control').removeClass('border-danger');
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
      $('.submit').attr('disabled',true);
      $('.form-control').attr('readonly',true);
      if ($('.submit').html() !== loadingText) {
        $('.submit').html(loadingText);
      }

      $.ajax({
         type: form.attr('method'),
         url: url,
         data: data,
         cache: false,
         contentType: false,
         processData: false,      
         success: function (response) {
                window.setTimeout(function(){
                    $('.form-control').attr('readonly',false);
                    $('.submit').attr('disabled',false);
                    $('.submit').html('Update');
                },2000);
               console.log(response);
               if(response.success==true) {          
                  // window.location = "{{ url('/')}}"+"/sla/?created=true";
                  toastr.success('Promocode Updated Successfully');
                  window.setTimeout(function(){
                     window.location = "{{ url('/app/')}}"+"/settings/promocode";
                  },2000);
               }
               //show the form validates error
               if(response.success==false ) {                              
                  for (control in response.errors) {  
                     $('.'+control).addClass('border-danger'); 
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
</script>

@endsection
