@extends('layouts.vendor')
@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column"> 
<div class="main-content">         
    <div class="row">
        <div class="col-sm-11">
            <ul class="breadcrumb">
            <li>
            <a href="{{ url('/home') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{ url('/vendor/roles') }}">Roles</a>
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
    <div class="row align-items-center justify-content-center">
        <div class="col-md-10">
            <form action="{{route('vendor/roles/store')}}"  method="POST" id="createRoleFrmvendor">
                @csrf
                    <div class="form-body">
                        <div class="card radius shadow-sm">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                    <h4 class="card-title mb-1 mt-3">Create a New Role  </h4>
                                    <p class="pb-border"> </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="role" class="form-control-label font-weight-300">Role <span class="text-danger">*</span></label>
                                            <input type="text" id="role" class="form-control role" name="role" placeholder="e.g. Role Name">
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-role"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    <button class="btn btn-success" type="submit">Save</button>
                                </div>
                                <div class="text-center">
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
$(document).ready(function(){
    
    $(document).on('submit', 'form#createRoleFrmvendor', function (event) {
      event.preventDefault();
      //clearing the error msg
      $('p.error_container').html("");
      $('.form-control').removeClass('border-danger');
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");

      $.ajax({
         type: form.attr('method'),
         url: url,
         data: data,
         cache: false,
         contentType: false,
         processData: false,      
         success: function (response) {

               console.log(response);
               if(response.success==true) {          
                  // window.location = "{{ url('/')}}"+"/sla/?created=true";
                  toastr.success('Role created successfully');
                  window.setTimeout(function(){
                     window.location = "{{ url('/')}}"+"/vendor/roles";
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
         error: function (response) {
            console.log(response);
         }
        //  error: function (xhr, textStatus, errorThrown) {
        //        // alert("Error: " + errorThrown);
        //  }
      });
      return false;
    });
});
    
</script>
@endsection