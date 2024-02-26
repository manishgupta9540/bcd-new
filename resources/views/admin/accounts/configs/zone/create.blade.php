@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">SLA / Create </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>
                 <a href="{{ url('/zone') }}">Zone</a>
             </li>
             <li>Create New</li>
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
                  {{-- @include('admin.configs.sidebar')  --}}
                  @include('admin.accounts.left-sidebar')
               </div>
               <!-- start right sec -->
               <div class="col-md-9 content-wrapper">
                  <div class="formCover" style="height: 100vh; background:#fff">
                     <!-- section -->
                     <section>
                        <div class="col-sm-12 ">
                           @if ($message = Session::get('error'))
                              <div class="col-md-12">   
                                 <div class="alert alert-danger">
                                 <strong>{{ $message }}</strong> 
                                 </div>
                              </div>
                           @endif
                              <!-- row -->
                              <div class="row">
                                 <div class="col-md-12">
                                    <h4 class="card-title mb-1 mt-3">Create a new Zone </h4>
                                    <p class="pb-border"> Create the Zone with multiple states and cities  </p>
                                 </div>
                                 <div class="col-md-12">
                                 <form method="post" action="{{ url('/zone/save') }}" id="createZoneForm" enctype="multipart/form-data">
                                       @csrf
                                    
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label>Zone Name <span class="text-danger">*</span></label>
                                             <input class="form-control" type="text" name="name" >
                                             <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-name"></p>
                                          </div>
                                       </div>   
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                        <div class="form-group">
                                           <label>Country <span class="text-danger">*</span></label>
                                           <select class="form-control country" name="country_id" id="country_id">
                                           <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                              <option value="{{ $country->id }}" @if($country->id == 101) selected="" @endif >{{ $country->name }}</option>
                                            @endforeach
                                           </select>
                                           <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-country_id"></p>
                                        </div>
                                        </div>
                
                                        <div class="col-sm-6">
                                        <div class="form-group">
                                           <label>State <span class="text-danger">*</span></label>
                                           <select class="form-control state" name="state_id[]" id="state_id" data-live-search="true" data-actions-box="true" data-selected-text-format="count>1" multiple>
                                           {{-- <option value="">Select State</option> --}}
                                            @foreach($state as $states)
                                              <option value="{{ $states->id }}">{{ $states->name }}</option>
                                            @endforeach
                                           </select>
                                           <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-state_id"></p>
                                        </div>
                                        </div>
                
                                     </div>
                
                                     <div class="row">
                                       <div class="col-sm-6">
                                        <div class="form-group">
                                           <label>City/Town/District <span class="text-danger">*</span></label>
                                           <select class="form-control city" name="city_id[]" id="city_id" data-live-search="true" data-actions-box="true" data-selected-text-format="count>1" multiple>
                                           </select>
                                           <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-city_id"></p>
                                        </div>
                                        </div>
                                      
                                     </div>
                                       
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <button type="submit" class="btn btn-info mt-3 submit">Submit</button>
                                          </div>
                                       </div>
                                    </div>
                                    </form>
                                 </div>
                              </div>
                              <!-- ./form section -->
                              
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
</div>
@stack('scripts')
<script type="text/javascript">
   //
  
   $(document).ready(function() {

    $('.state').selectpicker();
    $('.city').selectpicker();
    
         //on change country
   $(document).on('change','.country',function(event){ 
      var id = $('#country_id').val();
    //   alert(id);
      $.ajax({
            type:"post",
            url:"{{route('/customers/getstate')}}", 
            data:{'country_id':id,"_token": "{{ csrf_token() }}"},
               success:function(data)
            {     
                // .selectpicker('destroy')
                $('.state').selectpicker('destroy');
               
                  $("#state_id").empty();
                //   $("#state_id").html('<option>Select State</option>');
                  $.each(data,function(key,value){
                  $("#state_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                  
                //   alert(value.id); 
                  });
                  
                  $('.state').selectpicker();
            }
         });
         event.stopImmediatePropagation();
   });
    // on change state
    $(document).on('change','.state',function(event){ 
      var id = $('#state_id').val();
      //   alert(id);
      var city_arr = [];
      var i=0;
      $('.city option:selected').each(function () {
         city_arr[i++] = $(this).val();
      });
      // console.log(city_arr);
      $.ajax({
            type:"post",
            url:"{{route('/zone/getcity')}}", 
            data:{'state_id':id,"_token": "{{ csrf_token() }}"},
            success:function(data)
            {       
                $('.city').selectpicker('destroy');
                  $("#city_id").empty();
                //   $("#city_id").html('<option>Select City</option>');
                  $.each(data,function(key,value){
                     var selected = '';
                     if(city_arr.length > 0 && city_arr.includes(value.id.toString()))
                     {
                        selected= 'selected';
                     }
                     $("#city_id").append('<option value="'+value.id+'" '+selected+'>'+value.name+'</option>');
                  }); 
                  $('.city').selectpicker();
            }

         });
         event.stopImmediatePropagation();
   });





//
   $('#createSLABtn').click(function(e) {
        e.preventDefault();
        $("#createSLAForm").submit();
   });

   $(document).on('submit', 'form#createZoneForm', function (event) {
      event.preventDefault();
      //clearing the error msg
      $('p.error_container').html("");
      $('.form-control').removeClass('border-danger');
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
      $('.submit').attr('disabled',true);
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
                  $('.submit').attr('disabled',false);
                  $('.submit').html('Submit');
               },2000);
               console.log(response);
               if(response.success==true) {          
                  // window.location = "{{ url('/')}}"+"/sla/?created=true";
                  toastr.success('Zone Created Successfully');
                  window.setTimeout(function(){
                     window.location = "{{ url('/')}}"+"/zone";
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
});

</script>  
@endsection
