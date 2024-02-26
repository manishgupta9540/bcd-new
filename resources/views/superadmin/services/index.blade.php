@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li><a href="{{ url('/app/home') }}">Dashboard</a></li>
             <li>Verification</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow">
             <div class="text-right">
             <a href="{{url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card text-left">
               <div class="card-body">
                  <div class="row">
                     @if ($message = Session::get('success'))
                     <div class="col-md-12">
                        <div class="alert alert-success">
                           <strong>{{ $message }}</strong> 
                        </div>
                     </div>
                     @endif
                     <div class="col-md-8">
                        <h4 class="card-title mb-1"> Verifications </h4>
                        <p> Available Verifications</p>
                     </div>

                     <div class="col-md-4">
                        <div class="btn-group" style="float:right">
                            <a href="javascript:;" class="mt-3 btn btn-sm btn-primary VarificationFormInput"><i class="fa fa-plus"></i> Add Service</a>
                           <!-- <button class="btn btn-secondary btn-lg dropdown-toggle" id="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  Actions  </button>
                              <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#"> Else Here</a></div> -->
                           <!-- <a class="btn btn-success btn-lg" href="{!! url('/app/customers/create') !!} " > Add New </a>             -->
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="table-responsive">
                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">#</th>
                                    <th scope="col"> Name</th>
                                    <th scope="col"> Type</th>
                                    {{-- <th scope="col"> Vendor</th> --}}
                                    <th scope="col"> Status</th>
                                    <th scope="col" width="20%"> Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if( count($services) > 0 )
                                 @foreach($services as $item)
                                 <tr>
                                    <th scope="row">{{ $item->id }}</th>
                                    <td> <b>{{ $item->name }} </b><br>
                                       <small class="text-muted">  </small>
                                    </td>
                                    <td> 

                                          @if($item->verification_type=='Auto')
                                           {{ $item->verification_type }} Verify <span class="text-small text-muted">(With API)</span> 
                                           @else
                                           {{ $item->verification_type.' Verify' }}
                                          @endif
                                    </td>
                                    {{-- <td></td> --}}
                                    <td><span class="badge badge-success">ACTIVE</span></td>
                                    <td>
                                       <a href="javascript:;" data-id="{{$item->id}}" class="editservice btn btn-md btn-outline-primary"><i class="far fa-edit"></i> Edit</a>
                                       <span><a href="{{ url('/app/verifications/view',['id'=> base64_encode($item->id) ] ) }}" class="btn btn-md btn-outline-primary"><i class="far fa-copyright"></i> Config</a></span>
                                    </td>
                                 </tr>
                                 @endforeach
                                 @else
                                 <tr>
                                    <td scope="row" colspan="7">
                                       <h3 class="text-center">No record!</h3>
                                    </td>
                                 </tr>
                                 @endif
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
</div>
 <div class="modal" id="Varification_input_modal">
      <div class="modal-dialog">
         <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
               <h4 class="modal-title">Add Service </h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form method="post" action="{{ url('/app/services/new/save') }}" id="service_inputs">
            @csrf
               <div class="modal-body">

               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 

                  <div class="form-group">
                        <label for="label_name"> Service name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder=""/>
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-name"></p> 
                  </div>

                  <div class="form-group">
                     <label for="label_name"> Service Multiple <span class="text-danger">*</span></label>
                        <select class="form-control" name="multiple_type">
                           <option value=""> -Select- </option>
                           <option value="1"> Multiple </option>
                           <option value="0" selected> No </option>
                        </select>
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-multiple_type"></p>
                  </div>
                  <!-- dynamic table  -->
                  <!-- <div class="form-group">
                     <label for="label_name"> Table name (which is required)  </label>
                     <input type="text" name="tablename" class="form-control tablename" readonly />   
                  </div>       -->
                  <!--  -->
               </div>
               <!-- Modal footer -->
               <div class="modal-footer">
                  <button type="submit" class="btn btn-primary " >  Save </button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
               </div>
            </form>
         </div>
      </div>
</div>

 <div class="modal"  id="edit_service_modal">
      <div class="modal-dialog">
         <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
               <h4 class="modal-title">Edit Service</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form method="post" action="{{ route('/services/edit') }}" id="service_update">
            @csrf
               <div class="modal-body">
               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-edit"> </p> 
                  <div class="form-group">
                        <label for="label_name"> Service Name </label>
                        <input type="text" id="name" name="name" class="form-control name" placeholder=""/>
                        <p style="margin-bottom: 2px;" class="text-danger error-container error-name" id="error-name"></p> 
                  </div>

                  <div class="form-group">
                     <label for="label_name"> Is Multiple </label>
                        <select class="select form-control is_multiple_type"  name="is_multiple_type">
                           <option value=""> -Select- </option>
                           <option value="1"> Yes </option>
                           <option value="0"> No </option>
                        </select>
                        <p style="margin-bottom: 2px;" class="text-danger error-container error-is_multiple_type" id="error-is_multiple_type"></p> 
                  </div>
                  <!-- <label class="checkbox-inline"><input type="radio" class="jobEntryType" name="jobEntryType" value="single" checked="checked"> Single Entry</label>
                  <label class="checkbox-inline"><input type="radio" class="jobEntryType" name="jobEntryType" value="multiple"> Multiple Entry</label> -->
               </div>
               <!-- Modal footer -->
               <div class="modal-footer">
                  
                  <input type="hidden" class="service_id" name="service_id" >
                  <button type="submit" class="btn btn-primary " >  Save </button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
               </div>
            </form>
         </div>
      </div>
</div>
<script type="text/javascript">
   //
   $(document).ready(function() {

   //open modal
   $(document).on('click','.VarificationFormInput',function(){
            $('#service_inputs')[0].reset();
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('#Varification_input_modal').modal({
               backdrop: 'static',
               keyboard: false
            });
   }); 

   $(document).on('submit', 'form#service_inputs', function (event) {

      $("#overlay").fadeIn(300);　
      event.preventDefault();
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var $btn = $(this);
      $('.form-control').removeClass('is-invalid');
      $('.error-container').html('');
      $.ajax({
         type: form.attr('method'),
         url: url,
         data: data,
         cache: false,
         contentType: false,
         processData: false,
         success: function (data) {
            console.log(data);
            $('.error-container').html('');
            if (data.fail && data.error_type == 'validation') {
                  
                  //$("#overlay").fadeOut(300);
                  for (control in data.errors) {
                     $('input[name=' + control + ']').addClass('is-invalid');
                     $('select[name=' + control + ']').addClass('is-invalid');
                     $('#error-' + control).html(data.errors[control]);
                  }
            } 
            if (data.fail && data.error == 'yes') {
               
               $('#error-all').html(data.message);
            }
            if (data.fail == false) {
               $('#Varification_input_modal').modal('hide');
                location.reload(); 
            }
         },
         error: function (data) {
           
            console.log(data);

         }
      });
      return false;

   });

   //edit services item
   $(document).on('click','.editservice',function(){
      var id = $(this).attr("data-id");
      // $('#service_update')[0].reset();
      $('.form-control').removeClass('is-invalid');
      $('.error-container').html('');
      $('#edit_service_modal').modal({
            backdrop: 'static',
            keyboard: false
      });

      $.ajax({
         type: 'get',
         url: "{{ url('/app/services/edit') }}",
         data: {'service_id':id},        
         success: function (data) {
               console.log(data);
               //$("#form_update")[0].reset();
               
               if(data !='null')
               {              
                  //check if primary data 
                  $('.name').val(data.result.name);
                  $('.service_id').val(data.result.service_id);
               
                  $(".is_multiple_type option[value= '"+data.result.is_multiple_type+"']").attr("selected", "selected");
                  
               }
         },
         error: function (data) {
               console.log(data);
         }
      });

   });

   $(document).on('submit', 'form#service_update', function (event) {

      $("#overlay").fadeIn(300);　
      event.preventDefault();
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var $btn = $(this);
      $('.form-control').removeClass('is-invalid');
      $('.error-container').html('');

      $.ajax({
         type: form.attr('method'),
         url: url,
         data: data,
         cache: false,
         contentType: false,
         processData: false,
         success: function (data) {
            console.log(data);
            $('.error-container').html('');
            if (data.fail && data.error_type == 'validation') {
                  
                  //$("#overlay").fadeOut(300);
                  for (control in data.errors) {
                     $('input[name=' + control + ']').addClass('is-invalid');
                     $('select[name=' + control + ']').addClass('is-invalid');
                     $('.error-' + control).html(data.errors[control]);
                  }
            } 
            if (data.fail && data.error == 'yes') {
               
               $('#error-edit').html(data.message);
            }
            if (data.fail == false) {

               $('#edit_service_modal').modal('hide');
               location.reload(); 
            }
         },
         error: function (data) {
         
            console.log(data);

         }
      });
      return false;

   });

   }); 
</script>

@endsection
