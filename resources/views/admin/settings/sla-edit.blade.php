@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">SLA / Create </h3>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class=" text-left">
            <div class="">
               <div class="col-md-12 content-container">
                  <!-- left-sidebar -->
                  @include('admin.settings.left-sidebar') 
                  <!-- start right sec -->
                  <div class="col-sm-12 content-wrapper">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 ">
                              
                                <!-- row -->
                                 <div class="row">
                                    <div class="col-md-12">
                                       <h4 class="card-title mb-1 mt-3">Craete a new SLA </h4>
                                       <p class="pb-border"> Create the SLA with servcie  </p>
                                    </div>
                                    <div class="col-md-12">
                                    <form method="post" action="{{ route('/settings/sla/save') }}">
                                        @csrf
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Select a Customer <span class="text-danger">*</span></label>
                                                <select class="form-control " type="text" name="customer" >
                                                <option value=""> -Select- </option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id}}">{{ $customer->first_name.' '.$customer->last_name }}</option>
                                                @endforeach
                                                </select>
                                                @if ($errors->has('customer'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('customer') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Select services <span class="text-danger">*</span></label>
                                                <select class="form-control " type="text" name="service" >
                                                <option value=""> -Select- </option>
                                                    @foreach($services as $service)
                                                    <option value="{{ $service->id}}">{{ $service->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('last_name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('last_name') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>

                                       </div>
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>SLA Name <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="name" >
                                                @if ($errors->has('name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('name') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label> SLA Code <span class="text-danger">*</span></label>
                                                <input class="form-control" type="code" name="code" >
                                                @if ($errors->has('code'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('code') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>
                                       
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <input type="submit" class="btn btn-primary mt-3" value="Submit">
                                             </div>
                                          </div>
                                         
                                       </div>
                                       </form>
                                    </div>
                                 </div>
                                 <!-- ./business detail -->
                                 
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
   </div>
</div>
@stack('scripts')
<script type="text/javascript">
   //
   $(document).ready(function() {
   //
   $(document).on('click','#clickSelectFile',function(){ 
   
       $('#fileupload').trigger('click');
       
   });
   
   $(document).on('click','.remove-image',function(){ 
       
       $('#fileupload').val("");
       $(this).parent('.image-area').detach();
   
   });
   
   
   });
                     
</script>  
@endsection
