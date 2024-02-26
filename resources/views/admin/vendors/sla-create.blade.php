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
               <a href="{{ url('/admin/vendor/sla',['id'=>base64_encode($profile->id)]) }}">SLA</a>
            </li>
             <li>Create New SLA</li>
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
                                    <h4 class="card-title mb-1 mt-3">Create a new SLA <small class="text-muted"> ( {{ Helper::user_name($profile->user_id)  }} - {{  $profile->company_name!=null?$profile->company_name: 'Individual' }}) </small></h4>
                                    <p class="pb-border"> Create the SLA with multiple checks  </p>
                                 </div>
                                 <div class="col-md-12">
                                 <form method="post" action="{{ url('/admin/vendor/sla/save') }}" id="createSLAForm">
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
                                             <label>SLA Name <span class="text-danger">*</span></label>
                                             <input class="form-control" type="text" name="name" >
                                             <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-name"></p>
                                          </div>
                                       </div>   
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label>TAT <span class="text-danger">*</span></label>
                                             <input class="form-control" type="text" name="tat" >
                                             <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tat"></p>
                                             <small class="text-muted">Days in number</small>
                                          </div>
                                       </div>   
                                    </div>

                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label>Select Services <span class="text-danger">*</span></label>
                                             
                                             <div class="col-sm-12">
                                                <div class="form-group">
                                                   @foreach($services as $service) 
                                                      <div class="form-check form-check-inline">
                                                         <input class="form-check-input services_list" type="checkbox" name="services[]" value="{{ $service->id}}" data-string="{{ $service->name  }}" data-type="{{ $service->is_multiple_type }}" id="inlineCheckbox-{{ $service->id}}" data-verify={{$service->verification_type}}>
                                                         <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label>
                                                      </div>
                                                   @endforeach
                                                </div>
                                             </div>
                                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
                                          </div>
                                       </div>

                                       <div class="col-sm-6">
                                          
                                       </div>
                                    </div>

                                    <div class="service_result" style="border: 1px solid #ddd; padding:10px;">
                                       <div class="row">
                                          <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Configure TAT Need on each service</div>
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

   // $('.services_list').select2();

   $(".services_list").change(function() {
      if(this.checked)
      {
         var id =  $(this).attr("value");
         var text =  $(this).attr("data-string");
         var verify =$(this).attr("data-verify");
         // if(verify=="Auto")
         //    $(".service_result").append("<div class='row mt-2' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-2'><input class='form-control' type='text' name='service_unit-"+id+"' value='1' readonly><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-3'><input class='form-control' type='text' name='notes-"+id+"' placeholder='Notes' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-notes-"+id+"'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='tat-"+id+"' value='1' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-"+id+"'></p></div></div>");
         // else
            $(".service_result").append("<div class='row mt-2' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-3'><input class='form-control' type='text' name='notes-"+id+"' placeholder='Notes' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-notes-"+id+"'></p></div><div class='col-sm-1'><label>TAT</label><span class='text-danger'> *</span></div><div class='col-sm-3'><input class='form-control' type='text' name='tat-"+id+"' value='1' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-"+id+"'></p></div></div>");
      }
      else
      {
         var id =  $(this).attr("value");
         $("div#row-"+id).remove();
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
                  toastr.success('SLA Created Successfully');
                  window.setTimeout(function(){
                     window.location = "{{ url('/')}}"+"/admin/vendor/sla/"+id;
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
