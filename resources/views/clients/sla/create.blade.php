@extends('layouts.client')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
        <!-- ============Breadcrumb ============= -->
        <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/my/home') }}">Dashboard</a>
             </li>
             <li><a href="{{ url('/my/sla') }}">SLA</a></li>
             <li>Create</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow">
             <div class="text-right">
             <a href="{{ url()->previous() }}"> <i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div>    
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
               @if ($message = Session::get('error'))
                  <div class="col-md-12">   
                     <div class="alert alert-danger">
                     <strong>{{ $message }}</strong> 
                     </div>
                  </div>
               @endif
            
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper">
                     <div class="formCover" style="height: 100vh; background:#fff">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 ">
                              
                                <!-- row -->
                                 <div class="row">
                                    <div class="col-md-12">
                                       <h4 class="card-title mb-1 mt-3">Create a new SLA </h4>
                                       <p class="pb-border"> Create the SLA with multiple checks  </p>
                                    </div>
                                    <div class="col-md-12">
                                    <form method="post" action="{{ url('my/sla/save') }}" id="createSLAForm">
                                       @csrf
                                      {{-- <input type="hidden" name="business_id" > --}}
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>SLA Name <span class="text-danger">*</span></label>
                                                <input class="form-control name" type="text" name="name" id="name">
                                                {{-- @if ($errors->has('name')) --}}
                                                <div class="error text-danger" id="error-name">
                                                   {{ $errors->first('name') }}
                                                </div>
                                                {{-- @endif --}}
                                             </div>
                                          </div>   
                                       </div>

                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>TAT <span class="text-danger">*</span></label>
                                                <input class="form-control client_tat" type="text" name="client_tat" >
                                                <small class="text-muted">Your TAT in days</small>
                                                {{-- @if ($errors->has('client_tat')) --}}
                                                <div class="error text-danger" id="error-client_tat">
                                                   {{ $errors->first('client_tat') }}
                                                </div>
                                                {{-- @endif --}}
                                             </div>
                                          </div>   
                                       </div>
                                       
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Select Checks <span class="text-danger">*</span></label>
                                              
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

                                                {{-- @if ($errors->has('services')) --}}
                                                <div class="error text-danger" id="error-services">
                                                   {{ $errors->first('services') }}
                                                </div>
                                                {{-- @endif --}}
                                             </div>
                                          </div>

                                          <div class="col-sm-6">
                                             
                                          </div>
                                       </div>

                                       <div class="service_result" style="border: 1px solid #ddd; padding:10px;">
                                          <div class="row">
                                             <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Configure Number of Verifications Need on each service</div>
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
   //clear

   //
   $(document).on('click','.remove-image',function(){ 
       
      $('#fileupload').val("");
      $(this).parent('.image-area').detach();
   
   });

     // $('.services_list').select2();

      $(".services_list").change(function() {
      if(this.checked)
      {
          var id =  $(this).attr("value");
          var text =  $(this).attr("data-string");
          var verify =$(this).attr("data-verify");

          var tat = 1;

         if(text.toLowerCase()=='Address'.toLowerCase())
         {
            tat=7;
         }
         else if(text.toLowerCase()=='Employment'.toLowerCase())
         {
            tat=5;
         }
         else if(text.toLowerCase()=='Educational'.toLowerCase())
         {
            tat=7;
         }
         else if(text.toLowerCase()=='Criminal'.toLowerCase())
         {
            tat=3;
         }
         else if(text.toLowerCase()=='Judicial'.toLowerCase())
         {
            tat=2;
         }
         else if(text.toLowerCase()=='Reference'.toLowerCase())
         {
            tat=2;
         }
         else if(text.toLowerCase()=='Covid-19 Certificate'.toLowerCase())
         {
            tat=5;
         }

         if(verify.toLowerCase()=="Auto".toLowerCase())
            $(".service_result").append("<p class='pb-border row-"+id+"'></p><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-2'><input class='form-control' type='text' name='service_unit-"+id+"' value='1' readonly><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='tat-"+id+"' value='"+tat+"' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-"+id+"'></p></div><div class='col-sm-3'><input class='form-control' type='text' name='notes-"+id+"' placeholder='Notes' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-notes-"+id+"'></p></div></div><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-3'></div><div class='col-sm-2 pt-2 text-right'><label>Incentive TAT</label></div><div class='col-sm-1'><input class='form-control' type='text' name='incentive-"+id+"' value='1'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-"+id+"'></p></div><div class='col-sm-2 pt-2 text-right'><label>Penalty TAT</label></div><div class='col-sm-1'><input class='form-control' type='text' name='penalty-"+id+"' value='"+tat+"'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-"+id+"'></p></div></div>");
         else
            $(".service_result").append("<p class='pb-border row-"+id+"'></p><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-2'><input class='form-control' type='text' name='service_unit-"+id+"' value='1' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='tat-"+id+"' value='"+tat+"' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-"+id+"'></p></div><div class='col-sm-3'><input class='form-control' type='text' name='notes-"+id+"' placeholder='Notes' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-notes-"+id+"'></p></div></div><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-3'></div><div class='col-sm-2 pt-2 text-right'><label>Incentive TAT</label></div><div class='col-sm-1'><input class='form-control' type='text' name='incentive-"+id+"' value='1'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-"+id+"'></p></div><div class='col-sm-2 pt-2 text-right'><label>Penalty TAT</label></div><div class='col-sm-1'><input class='form-control' type='text' name='penalty-"+id+"' value='"+tat+"'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-"+id+"'></p></div></div>");
         
      }
      else
      {
         var id =  $(this).attr("value");
         $("div.row-"+id).remove();
         $("p.row-"+id).remove();
      }
      
      });

      // $('.services_list').on("select2:unselect", function(e) { 
      //    var data = e.params.data;
      //    console.log(data);   
      //    $("div#row-"+data.id).remove();
      // });
      $('#createSLABtn').click(function(e) {
        e.preventDefault();
        $("#createSLAForm").submit();
   });

      $(document).on('submit', 'form#createSLAForm', function (event) {
         event.preventDefault();
         //clearing the error msg
         // $('p.error_container').html("");
         $('div.error').html("");
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
                  if(response.success==true  ) {          
                     toastr.success('SLA Created Successfully');
                     window.location = "{{ url('/')}}"+"/my/sla";
                  }
                  //show the form validates error
                  if(response.success==false ) {                              
                     for (control in response.errors) {
                        $('.'+control).addClass('border-danger');
                        $('#error-' + control).html(response.errors[control]); 
                     }
                  }
            },
            error: function (response) {
               console.log(response);
            }
         });
         return false;
      });

   });
      
</script>  
@endsection
