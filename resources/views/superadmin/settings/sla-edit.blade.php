@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">SLA / Edit </h3>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         
               <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('superadmin.settings.left-sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper">
                     <div class="formCover" style="height: 100vh; background:#fff">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 ">
                              
                                <!-- row -->
                                 <div class="row">
                                    <div class="col-md-12">
                                       <h4 class="card-title mb-1 mt-3">Edit SLA </h4>
                                       <p class="pb-border"> Edit the SLA's service items.  </p>
                                    </div>
                                    <div class="col-md-12">
                                    <form method="post" action="{{ url('/app/settings/sla/update') }}">
                                        @csrf
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label> Customer :</label>
                                                <label> <b> {{ $sla->company_name}}  </b></label>
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>SLA Name <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="name" value="{{ $sla->title}}">
                                                @if ($errors->has('name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('name') }}
                                                </div>
                                                @endif
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
                                                <input class="form-check-input services_list" id="inlineCheckbox-{{ $service->id}}" type="checkbox" name="services[]" value="{{ $service->id}}" data-string="{{ $service->name  }}" data-type="{{ $service->is_multiple_type }}" 
                                               {{in_array($service->id, $selected_services_id) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label>
                                                </div>
                                                @endforeach
                                                </div>
                                             </div>
                                                @if ($errors->has('services'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('services') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>

                                          <div class="col-sm-6">
                                             
                                          </div>
                                       </div>

                                       <div class="service_result" style="border: 1px solid #ddd; padding:10px;">
                                          <div class="row">
                                             <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Configure Number of Verifications Need on each service</div>
                                          </div>
                                          @foreach($sla_items as $item)
                                            
                                                <div class='row mt-2' id="row-{{ $item->id}}">
                                                    <div class='col-sm-2'><label>{{ $item->name}}</label></div>
                                                    <div class='col-sm-2'><input class='form-control' type='text' name='service_unit-{{$item->id}}"' value="{{ $item->number_of_verifications }}" ></div>
                                                    <div class='col-sm-3'><input class='form-control' type='text' name='notes-{{$item->id}}"' value="{{ $item->notes }}" ></div>
                                                </div>

                                           @endforeach
                                          </div>
                              
                                       </div>
                                          
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <input type="hidden" name="sla_id" value="{{ $sla->id }}">
                                                <input type="hidden" name="business_id" value="{{ $sla->business_id }}">
                                                <input type="submit" class="btn btn-primary mt-3" value="Update">
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
     $(".services_list").change(function() {
      if(this.checked)
         {
         var id =  $(this).attr("value");
         var text =  $(this).attr("data-string");
         $(".service_result").append("<div class='row mt-2' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-2'><input class='form-control' type='text' name='service-"+id+"' value='1' ></div><div class='col-sm-3'><input class='form-control' type='text' name='notes-"+id+"' placeholder='Notes' ></div></div>");
      }
      else
      {
         var id =  $(this).attr("value");
         $("div#row-"+id).remove();
      }
      
      });

   });
      
</script>  
@endsection
