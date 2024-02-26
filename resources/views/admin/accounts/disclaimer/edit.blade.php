@extends('layouts.admin')

@section('content')
    <style type="text/css">
        ul,
        li {
            list-style-type: none;
        }
    </style>
    <div class="main-content-wrap sidenav-open d-flex flex-column">
        <div class="main-content">
            <div class="row pb-3">
                <div class="col-sm-11">
                    <ul class="breadcrumb">
                        <li>
                            <a href="{{ url('/home') }}">Dashboard</a>
                        </li>
                        <li>
                            <a href="{{ url('/check/control') }}">Check Control</a>
                        </li>
                        <li>
                            {{-- @if ($role_data == null)
                Permission  
              @else
                {{$role_data->role}}
              @endif --}}
                        </li>
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
                    @include('admin.accounts.left-sidebar')
                    <!-- start right sec -->
                </div>
                <div class="col-md-9 content-wrapper" style="background:#fff">
                    <div class="formCover">
                        <!-- section -->
                        <div class="col-sm-12 ">
                            <!-- row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="card-title mb-1 mt-3">Diaclaimer Add & Edit </h4>
                                    <p class="pb-border"> </p>
                                </div>

                                <div class="col-md-12">
                                    <form action="{{ url('/save/check/disclaimer') }}" method="post" id="adddisclaimer">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ base64_encode($id) }}">
                                        <input type="hidden" name="parent_id" value="{{$parentData}}">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <label for="">Check name <span class="text-danger">*</span></label>
                                                        <select name="service_id" id="service_id" class="form-control">
                                                            <option value="">Select Check Name</option>
                                                            @foreach ($newcheck_services as $service)
                                                                <option value="{{$service->id}}">{{$service->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-service_id"></p>
                                                    </div>

                                                    <div class="col-4">
                                                        <label for="">Disclaimer <span class="text-danger">*</span></label>
                                                        <textarea id="disclaimer" name="disclaimer" class="form-control disclaimer" placeholder=""></textarea>
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-disclaimer"></p>
                                                    </div>

                                                    <div class="col-4">
                                                        <button type="submit" class="btn btn-info submit"
                                                            style="margin-top: 32px;">Submit</button>
                                                        <a href="" class="btn  btn-danger"
                                                            style="margin-top: 32px;"><i
                                                                class="metismenu-icon"></i>Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <br><br>
                                    <table class="table table-bordered customerTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col" style="position:sticky; top:60px">Customer Name</th>
                                                <th scope="col" style="position:sticky; top:60px">Disclaimer</th>
                                                <th scope="col" style="position:sticky; top:60px">Check Name</th>
                                                <th scope="col" style="position:sticky; top:60px">Status</th>
                                                <th scope="col" style="position:sticky; top:60px" width="10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($disclaimers)>0)
                                                @foreach ($disclaimers as $disclaimer)
                                                    <tr>
                                                        <td>{{Helper::user_name($disclaimer->business_id)}} - {{ Helper::company_name($disclaimer->business_id)}}</td>
                                                        <td>{{ $disclaimer->disclaimer }}</td>
                                                        <td>{{Helper::get_service_name($disclaimer->service_id)}}</td>
                                                        <td>
                                                            @if($disclaimer->status==0)
                                                                <span data-dc="{{base64_encode($disclaimer->id)}}" class="badge badge-warning">Deactive</span>
                                                                <span data-ac="{{base64_encode($disclaimer->id)}}" class="badge badge-success d-none">Active</span>
                                                            @else
                                                                <span data-dc="{{base64_encode($disclaimer->id)}}" class="badge badge-warning d-none">Deactive</span>
                                                                <span data-ac="{{base64_encode($disclaimer->id)}}" class="badge badge-success">Active</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                             <button class="btn btn-outline-info btn-md mb-1 edit_disclaimer" data-id="{{ $disclaimer->id }}" type="button"> <i class="fa fa-edit"> </i> Edit</button> 
                                                             @if($disclaimer->status==1)
                                                                <span data-d="{{base64_encode($disclaimer->id)}}"><a href="javascript:;" class="btn btn-md btn-outline-warning status" data-id="{{base64_encode($disclaimer->id)}}" data-type="{{base64_encode('deactive')}}" title="Deactivate"><i class="far fa-times-circle"></i></a></span>
                                                                <span data-a="{{base64_encode($disclaimer->id)}}" class="d-none"><a href="javascript:;" class="btn btn-md btn-outline-success status" data-id="{{base64_encode($disclaimer->id)}}" data-type="{{base64_encode('active')}}" title="Activate"><i class="far fa-check-circle"></i></a></span>
                                                            @else
                                                                <span class="d-none" data-d="{{base64_encode($disclaimer->id)}}"><a href="javascript:;" class="btn btn-md btn-outline-warning status" data-id="{{base64_encode($disclaimer->id)}}" data-type="{{base64_encode('deactive')}}" title="Deactivate"><i class="far fa-times-circle"></i></a></span>
                                                                <span data-a="{{base64_encode($disclaimer->id)}}"><a href="javascript:;" class="btn btn-md btn-outline-success status" data-id="{{base64_encode($disclaimer->id)}}" data-type="{{base64_encode('active')}}"  title="Activate"><i class="far fa-check-circle"></i></a></span>
                                                            @endif
                                                        </td>
                                                    </tr> 
                                                @endforeach
                                            @else
                                                <tr class="text-center">
                                                    <td colspan="5">No Data Found</td>
                                                </tr>
                                            @endif    
                                        </tbody>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div> 
                       
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    {{-- </div> --}}

    <div class="modal" id="edit_disclaimer_modal">
        <div class="modal-dialog">
           <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                 <h4 class="modal-title">Edit Disclaimer</h4>
                 <button type="button" class="close close_btn" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
              </div>
              <!-- Modal body -->
              <form method="post" action="{{route('update/disclaimer')}}" id="dislaimer_data_update">
              @csrf
                 <div class="modal-body">
                    <label for="">Check name <span class="text-danger">*</span></label>
                    <input type="hidden" name="business_id" id="business_id">
                    <input type="hidden" name="parent_id" value="{{$parentData}}">
                    <input type="hidden" name="disc_id" id="disclaimer_id">
                    <select name="service_id" id="service_id1" class="form-control">
                        <option value="">Select Check Name</option>
                        @foreach ($services as $service)
                            <option value="{{$service->id}}">{{$service->name}}</option>
                        @endforeach
                    </select>
                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-service_id"></p>
                    <div class="form-group">
                        <label for="">Disclaimer <span class="text-danger">*</span></label>
                        <textarea id="disclaimer1" name="disclaimer" class="form-control disclaimer" placeholder=""></textarea>
                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-disclaimer"></p> 
                    </div>
                 </div>
                 <!-- Modal footer -->
                 <div class="modal-footer">
                    <button type="submit" class="btn btn-info submit">Submit </button>
                    <button type="button" class="btn btn-danger close_btn" data-dismiss="modal">Close</button>
                 </div>
              </form>
           </div>
         </div>
    </div>
    <script type="text/javascript">

        //add disclaimer
        $(document).on('submit', 'form#adddisclaimer', function (event) {
            event.preventDefault();
        
            //clearing the error msg
            $('p.error_container').html("");

            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            $('.submit').attr('disabled',true);
            $('.close').attr('disabled',true);
            $('.form-control').attr('readonly',true);
            $('.form-control').addClass('disabled-link');
            $('.error-control').addClass('disabled-link');
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
                            $('.close').attr('disabled',false);
                            $('.form-control').attr('readonly',false);
                            $('.form-control').removeClass('disabled-link');
                            $('.error-control').removeClass('disabled-link');
                            $('.submit').html('Submit');
                        },2000);
                        // console.log(response);
                        if(response.success==true  ) {          
                        
                            //notify
                            toastr.success("Disclaimer Added successfully");
                            // redirect to google after 5 seconds
                            window.setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        
                        }
                        //show the form validates error
                        if(response.success==false ) {  
                            var i=0;                            
                            for (control in response.errors) {   
                                $('#error-' + control).html(response.errors[control]);
                                if(i==0)
                                {
                                    $('select[name='+control+']').focus();
                                    $('input[name='+control+']').focus(); 
                                    $('textarea[name='+control+']').focus();
                                }
                                i++;  
                            }
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        // alert("Error: " + errorThrown);
                    }
                });
                return false;
        });
        
        // edit disclaimer
        $('.edit_disclaimer').click(function(){

            var id = $(this).attr('data-id');
            
            $.ajax({
                type: "get",
                url: "{{url('/edit_discliamer_data')}}"+'/'+id,
                cache: false,
                contentType: false,
                processData: false,      
                success: function (data) {
                    console.log(data.editdata.disclaimer)   
                    if(data !='null')
                    {        
                        $('#disclaimer_id').val(data.editdata.id)
                        $('#business_id').val(data.editdata.business_id)
                        $('#service_id1').val(data.editdata.service_id)
                        $("#disclaimer1").val(data.editdata.disclaimer);
                    
                        $('#edit_disclaimer_modal').modal({
                                backdrop: 'static',
                                keyboard: false
                        });  
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
                });

                $('#edit_disclaimer_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                });
        });

        //update disclaimer
        $(document).on('submit', 'form#dislaimer_data_update', function (event) {
            event.preventDefault();
        
            //clearing the error msg
            $('p.error_container').html("");

            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            $('.submit').attr('disabled',true);
            $('.close').attr('disabled',true);
            $('.form-control').attr('readonly',true);
            $('.form-control').addClass('disabled-link');
            $('.error-control').addClass('disabled-link');
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
                            $('.close').attr('disabled',false);
                            $('.form-control').attr('readonly',false);
                            $('.form-control').removeClass('disabled-link');
                            $('.error-control').removeClass('disabled-link');
                            $('.submit').html('Submit');
                        },2000);
                        // console.log(response);
                        if(response.success==true  ) {          
                        
                            //notify
                            toastr.success("Disclaimer Updated successfully");
                            // redirect to google after 5 seconds
                            window.setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        
                        }
                        //show the form validates error
                        if(response.success==false ) {  
                            var i=0;                            
                            for (control in response.errors) {   
                                $('#error-' + control).html(response.errors[control]);
                                if(i==0)
                                {
                                    $('select[name='+control+']').focus();
                                    $('input[name='+control+']').focus(); 
                                    $('textarea[name='+control+']').focus();
                                }
                                i++;  
                            }
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        // alert("Error: " + errorThrown);
                    }
                });
                return false;
        });

        //status updated
        $(document).on('click', '.status', function (event) {

            var id = $(this).attr('data-id');
            var type =$(this).attr('data-type');
            //  alert(user_id);
            if(confirm("Are you sure want to change the status ?")){
            $.ajax({
                    type:'POST',
                    url: "{{ url('/')}}"+"/service/disclaimer/status",
                    data: {
                        "_token" : "{{ csrf_token() }}",
                        'id':id,
                        'type':type
                    },        
                    success: function (response) {        
                    // console.log(response);
                    
                        if (response.status=='ok') { 
                        window.setTimeout(function(){
                           location.reload();
                        },2000);
                        toastr.success("Status Changed Successfully");

                        if(response.type=='active')
                        {
                                $('table.insuffTable tr').find("[data-ac='" + id + "']").fadeIn("slow");
                                $('table.insuffTable tr').find("[data-ac='" + id + "']").removeClass("d-none");

                                $('table.insuffTable tr').find("[data-dc='" + id + "']").fadeOut("slow");

                                $('table.insuffTable tr').find("[data-dc='" + id + "']").addClass("d-none");

                                $('table.insuffTable tr').find("[data-a='" + id + "']").fadeOut("slow");
                                $('table.insuffTable tr').find("[data-a='" + id + "']").addClass("d-none");

                                $('table.insuffTable tr').find("[data-d='" + id + "']").fadeIn("slow");

                                $('table.insuffTable tr').find("[data-d='" + id + "']").removeClass("d-none");

                                
                        }
                        else if(response.type=='deactive')
                        {
                                $('table.insuffTable tr').find("[data-dc='" + id + "']").fadeIn("slow");
                                $('table.insuffTable tr').find("[data-dc='" + id + "']").removeClass("d-none");

                                $('table.insuffTable tr').find("[data-ac='" + id + "']").fadeOut("slow");

                                $('table.insuffTable tr').find("[data-ac='" + id + "']").addClass("d-none");

                                $('table.insuffTable tr').find("[data-d='" + id + "']").fadeOut("slow");
                                $('table.insuffTable tr').find("[data-d='" + id + "']").addClass("d-none");

                                $('table.insuffTable tr').find("[data-a='" + id + "']").fadeIn("slow");

                                $('table.insuffTable tr').find("[data-a='" + id + "']").removeClass("d-none");
                        }
                        } 
                        else {
                        
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        // alert("Error: " + errorThrown);
                    }
            });

            }
            return false;

            });
    </script>
@endsection

