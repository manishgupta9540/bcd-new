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
                <a href="{{ url('/admin/vendor') }}">Vendors</a>
             </li>
             <li>
               <a href="{{ url('/admin/vendor/checkPrice',['id'=>base64_encode($profile->id)]) }}">Check Price</a>
            </li>
             <li>Create New Check price</li>
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
                  @include('admin.vendors.left-sidebar') 
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
                                    <h4 class="card-title mb-1 mt-3">Check Price <small class="text-muted"> ( {{ Helper::user_name($profile->user_id)  }} - {{  $profile->company_name!=null?$profile->company_name: 'Individual' }}) </small></h4>
                                    <p class="pb-border"> Create the new Check Price  </p>
                                 </div>
                                <div class="col-md-12">
                                    <form method="post" action="{{ url('/admin/vendor/checkPrice/save') }}" id="createSLAForm">
                                        @csrf
                                        <input type="hidden" name="vendor" value="{{ $profile->id }}" class="vendor">
                                        <input type="hidden" name="vendor_id" value="{{base64_encode($profile->id) }}" class="vendor_id">
                                        <input type="hidden" name="business_id" value="{{ $profile->user_id }}" class="bussiness_id">
                                        {{-- <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Select a Customer <span class="text-danger">*</span></label>
                                                <select class="form-control " type="text" name="customer" >
                                                <option value=""> -Select- </option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id}}">{{ $customer->company_name.' - '.$customer->first_name.' '.$customer->last_name }}</option>
                                                @endforeach
                                                </select>
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-customer"></p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            
                                        </div>
                                        </div> --}}
                                        
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Services <span class="text-danger">*</span></label>
                                                    
                                                
                                                    <select class="form-control services" name="services">
                                                        <option value="">Select Service</option>
                                                        @foreach($services as $service) 
                                                        <option value="{{ $service->id }}"  >{{ $service->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                    
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="check_price d-none ">
                                                    <div class="form-group">
                                                        <label for="price">Price <span class="text-danger">*</span></label>
                                                        <input type="text" name="price" class="form-control" id="price" placeholder="price" value="{{ old('price') }}">

                                                    </div> 
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-price"></p>
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

   // $('.services_list').select2();
   $(document).on('change', '.services', function (e) {
            e.preventDefault();  //stop the browser from following
            var _current =$(this);
            var id=$('.services').val();
            if (id =='') {
                $(".check_price").addClass('d-none');
                // $(".multiple").hide();
            }
            else {
                $(".check_price").removeClass('d-none');
                // $(".multiple").show();
            }
    });

//
   $('#createSLABtn').click(function(e) {
        e.preventDefault();
        $("#createSLAForm").submit();
   });

   $(document).on('submit', 'form#createSLAForm', function (event) {
      event.preventDefault();
      //clearing the error msg
      $('p.error_container').html("");
      $('.form-control').removeClass('border-danger');
      var form = $(this);
      var id =$('.vendor_id').val();
    //   var vendor_id =base64_encode(id);
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
                  toastr.success('Check price has been added Successfully');
                  window.setTimeout(function(){
                     window.location = "{{ url('/')}}"+"/admin/vendor/checkPrice/"+id;
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
