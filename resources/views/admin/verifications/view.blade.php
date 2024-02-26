@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li><a href="{{ url('/home') }}">Dashboard</a></li>
             <li><a href="{{ url('/verifications') }}">Verification</a></li>
             <li>{{ $service->name}}</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow">
             <div class="text-right">
             <a href="{{url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div>
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Verifications/ {{ $service->name}} </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         
                  <!-- start right sec -->
                  <div class="col-md-12 content-wrapper" style=" ">
                     <div class="formCover" style="height: 100vh; background:#fff;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 ">
                              
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">{{ $service->name}}  </h4>
                                       <p class=""> Form control overview   </p>
                                    </div>
                                    <div class="col-md-6 text-right">
                                       <a href="javascript:;" class="mt-3 btn btn-sm btn-info createFormInput"> <i class="fa fa-plus"></i> Add Form Input</a></a>
                                    </div>
                                   
                                    <div class="col-md-12">

                                       <table class="table table-bordered formInputTable" id="formInputsTable">
                                          <thead class="thead-light">
                                             <tr>
                                                <th> Input Label Name </th>
                                                <th> Type </th>
                                                <th> Report Output </th>
                                                <th width="20%"> Action </th>
                                             </tr>

                                          </thead>
                                          <tbody class="rowResult" > 
                                             @if( count($form_inputs) > 0 )
                                                @foreach($form_inputs as $item)
                                                   <tr row-id="{{$item->id}}">
                                                      <td class="labelName-{{$item->id}}"> {{ $item->label_name }} </td>
                                                      <td class="type-{{$item->id}}"> {{ $item->type }} </td>
                                                      <td class="report_output-{{$item->id}}"> 
                                                         @if($item->is_report_output =='1')
                                                            <span class="text-success">Yes</span>
                                                         @else
                                                            <span class="">No</span>
                                                         @endif
                                                         <br>
                                                         @if($item->is_executive_summary =='1')
                                                         <span class="text-small">Executive Summary: <span class="text-success">Yes</span> </span>
                                                         @endif
                                                      </td>
                                                      <td> 
                                                            <a href="javascript:;" data-id="{{$item->id}}" class="editInput btn btn-md btn-outline-info"><i class="far fa-edit"></i> Edit</a>
                                                         @php
                                                            $service_form_input= Helper::get_service_variable_form_input($item->id);
                                                         @endphp
                                                         @if ($service_form_input!=NULL)
                                                            <a href="{{ url('/verifications/view/form-input',['id'=> base64_encode($item->id),'service_id'=>base64_encode($service->id)]) }}" class="btn btn-md btn-outline-info"> <i class="far fa-copyright"></i>DropDown Config</a>

                                                            {{-- <a href="javascript:;" data-id="{{$item->id}}" class="dropDownConfig btn btn-md btn-outline-info"><i class="far fa-edit"></i> DropDown Config</a> --}}
                                                         @endif
                                                         {{-- <span>
                                                            <a href="javascript:;" data-id="{{$item->id}}" class="deleteInput btn btn-md btn-outline-danger"><i class="far fa-trash-alt"></i> Delete</a>
                                                         </span>  --}}
                                                      </td>
                                                   </tr>
                                                @endforeach
                                             @else 
                                                <tr class="no_record">
                                                   <td class="text-center" colspan="4">No record!</td>
                                                </tr>
                                          
                                             @endif
                                       
                                          </tbody>
                                       </table>

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

<!-- crate Option input The Modal -->
{{-- <div class="modal" id="drop_down_modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Add form input </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{ route('/verifications/form-input') }}" id="drop_down_inputs">
         @csrf
            <div class="modal-body data">
               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               

               
               <!-- <label class="checkbox-inline"><input type="radio" class="jobEntryType" name="jobEntryType" value="single" checked="checked"> Single Entry</label>
               <label class="checkbox-inline"><input type="radio" class="jobEntryType" name="jobEntryType" value="multiple"> Multiple Entry</label> -->
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <input type="hidden" name="service_id" value="{{ $service->id }}">
               <button type="submit" class="btn btn-info " >  Save </button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div> --}}

 <!-- crate form input The Modal -->
 <div class="modal" id="form_input_modal">
      <div class="modal-dialog">
         <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
               <h4 class="modal-title">Add form input </h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form method="post" action="{{ route('/verifications/form-input') }}" id="form_inputs">
            @csrf
               <div class="modal-body">

               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 

                  <div class="form-group">
                     <label for="exampleInputEmail1">Type </label>
                        <select class="form-control type" name="type">
                           <option value=""> -Select- </option>
                           @foreach($form_input_masters as $item)
                              <option value="{{ $item->id }}"> {{ ucfirst($item->name) }} </option>
                           @endforeach
                        </select>
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-type"></p> 
                  </div>
                 

                  <div class="form-group">
                     <label for="label_name"> Label name </label>
                        <input type="text" id="label_name" name="label_name" class="form-control" placeholder=""/>
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-label_name"></p> 
                  </div>

                  <div class="form-group">
                     <label for="label_name"> Mandatory </label>
                        <select class="form-control" name="mandatory">
                           <option value=""> -Select- </option>
                           <option value="1"> Yes </option>
                           <option value="0" selected> No </option>
                        </select>
                        <p style="margin-bottom: 2px;" class="text-danger error-container " id="error-mandatory"></p> 
                  </div>

                  <div class="form-group">
                     <label for="label_name"> Report Output (Executuve Summary) </label>
                        <select class="form-control executive_output" name="executive_output">
                           <option value=""> -Select- </option>
                           <option value="1"> Yes </option>
                           <option value="0" selected> No </option>
                        </select>
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-executive_output"></p> 
                  </div>

                  <div class="form-group">
                     <label for="label_name">Report Output (Check Table) </label>
                        <select class="form-control report_output" name="report_output">
                           <option value=""> -Select- </option>
                           <option value="1"> Yes </option>
                           <option value="0" selected> No </option>
                        </select>
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-report_output"></p> 
                  </div>
                  <div class="form-group option_number d-none">
                     <label for="exampleInputEmail1">Number of option </label>
                        <select class="form-control" name="no_of_option" id="no_of_option">
                           <option value=""> -Select- </option>
                           <option value="1"> 1 </option>
                           <option value="2"> 2 </option>
                           <option value="3"> 3 </option>
                           <option value="4"> 4 </option>
                           <option value="5"> 5 </option>
                           <option value="6"> 6 </option>
                           <option value="7"> 7 </option>
                           <option value="8"> 8 </option>
                           <option value="9"> 9 </option>
                           <option value="10"> 10 </option>

                        </select>
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-no_of_option"></p>  
                  </div>
                  <div id="add_option">

                  </div>
                  <!-- <label class="checkbox-inline"><input type="radio" class="jobEntryType" name="jobEntryType" value="single" checked="checked"> Single Entry</label>
                  <label class="checkbox-inline"><input type="radio" class="jobEntryType" name="jobEntryType" value="multiple"> Multiple Entry</label> -->
               </div>
               <!-- Modal footer -->
               <div class="modal-footer">
                  <input type="hidden" name="service_id" value="{{ $service->id }}">
                  <button type="submit" class="btn btn-info " >  Save </button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
               </div>
            </form>
         </div>
      </div>
</div>
<!-- create form input modal -->


 <!-- crate form input The Modal -->
 <div class="modal"  id="edit_form_input_modal">
      <div class="modal-dialog">
         <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
               <h4 class="modal-title">Edit form input</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form method="post" action="{{ url('/verifications/formInput/update') }}" id="form_update">
            @csrf
               <div class="modal-body">

               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 

                  <div class="form-group">
                     <label for="exampleInputEmail1">Type </label>
                        <select class="select form-control type" name="type">
                        <option value=""> -Select- </option>
                           @foreach($form_input_masters as $item)
                           <option value="{{ $item->id }}"> {{ ucfirst($item->name) }} </option>
                           @endforeach
                        </select>
                        <p style="margin-bottom: 2px;" class="text-danger error-container error-type" id="error-type"></p> 
                  </div>

                  <div class="form-group">
                     <label for="label_name"> Label name </label>
                        <input type="text" id="label_name" name="label_name" class="form-control label_name" placeholder=""/>
                        <p style="margin-bottom: 2px;" class="text-danger error-container error-label_name" id="error-label_name"></p> 
                  </div>

                  <div class="form-group">
                     <label for="label_name"> Mandatory </label>
                        <select class="select form-control mandatory"  name="mandatory">
                           <option value=""> -Select- </option>
                           <option value="1"> Yes </option>
                           <option value="0"> No </option>
                        </select>
                        <p style="margin-bottom: 2px;" class="text-danger error-container error-mandatory" id="error-mandatory"></p> 
                  </div>

                  <div class="form-group">
                     <label for="label_name"> Report Output (Executuve Summary) </label>
                        <select class="form-control executive_output" name="executive_output">
                           <option value=""> -Select- </option>
                           <option value="1"> Yes </option>
                           <option value="0"> No </option>
                        </select>
                        <p style="margin-bottom: 2px;" class="text-danger error-container error-executive_output" id="error-executive_output"></p> 
                  </div>

                  <div class="form-group">
                     <label for="label_name">Report Output (Check Table) </label>
                        <select class="form-control report_output" name="report_output">
                           <option value=""> -Select- </option>
                           <option value="1"> Yes </option>
                           <option value="0"> No </option>
                           <p style="margin-bottom: 2px;" class="text-danger error-container error-report_output" id="error-report_output"></p> 
                        </select>
                  </div>

                  <!-- <label class="checkbox-inline"><input type="radio" class="jobEntryType" name="jobEntryType" value="single" checked="checked"> Single Entry</label>
                  <label class="checkbox-inline"><input type="radio" class="jobEntryType" name="jobEntryType" value="multiple"> Multiple Entry</label> -->
               </div>
               <!-- Modal footer -->
               <div class="modal-footer">
                  <input type="hidden" name="service_id" value="">
                  <input type="hidden" class="input_id" name="input_id" >
                  <button type="submit" class="btn btn-primary">  Save </button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
               </div>
            </form>
         </div>
      </div>
</div>

<!-- create form input modal -->

@stack('scripts')
<script type="text/javascript">
   //
   $(document).ready(function() {
      //open modal
      $(document).on('click','.createFormInput',function(){
         $("#form_inputs")[0].reset();
         $('.form-control').removeClass('is-invalid');
         $('.error-container').html('');
         $('#form_input_modal').modal();
      });
      $(document).on('click','.dropDownConfig',function(){
         // $("#drop_down_inputs")[0].reset();
         $('.form-control').removeClass('is-invalid');
         $('.error-container').html('');
         // $('#drop_down_modal').modal();
         var id = $(this).attr("data-id");
            $('#drop_down_modal').modal({
               backdrop: 'static',
               keyboard: false
            });
         $.ajax({
            type: 'POST',
            url: "{{ url('/verifications/form-input/drop-down') }}",
            data: {"_token": "{{ csrf_token() }}",'input_id':id},        
            success: function (data) {
                  console.log(data);
                  // $("#form_update")[0].reset();
                  if(data.fail ==false)
                  {              
                     //check if primary data 
                     $(".data").html(data.data);
                     // $(".type option[value= '"+data.result.id+"']").attr("selected", "selected");
                     // $('.label_name').val(data.result.label_name);
                     // $('.input_id').val(data.result.input_id);
                     // $(".mandatory option[value= '"+data.result.is_mandatory+"']").attr("selected", "selected");
                     // $(".report_output option[value= '"+data.result.is_report_output+"']").attr("selected", "selected");
                     // $(".executive_output option[value= '"+data.result.is_executive_summary+"']").attr("selected", "selected");
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  alert("Error: " + errorThrown);
            }
         });
         // data-id
      });
      //get Option Number by Type
      $(document).on('change','.type',function(){
         var type= $(this).val();
         
         if (type=='6') {
            $('.option_number').removeClass('d-none');
         } else {
            $('.option_number').addClass('d-none');
            $('#no_of_option').val('');
            $('#add_option').html('');
         }
      });

      //Number of option
      $(document).on('change','#no_of_option',function(){
        var no_of_option= $(this).val();
        
        $('#add_option').html('');
        for (let i = 1; i <= no_of_option; i++) {
            $("#add_option").append("<div class='form-group'><label for='option'> Option-"+i+"</label><input type='text' id='option_name"+i+"' name='option_name"+i+"' class='form-control' placeholder=''/><p style='margin-bottom: 2px;' class='text-danger error-container' id='error-label_name'></p></div>");
        }
        //alert(no_of_option);
      }); 
      //get input data
      $(document).on('click','.editInput',function(){
         $("#form_update")[0].reset();
         $('.form-control').removeClass('is-invalid');
         $('.error-container').html('');
         var id = $(this).attr("data-id");
            $('#edit_form_input_modal').modal({
               backdrop: 'static',
               keyboard: false
            });
         $.ajax({
            type: 'POST',
            url: "{{ url('/verifications/formInput/edit') }}",
            data: {"_token": "{{ csrf_token() }}",'input_id':id},        
            success: function (data) {
                  console.log(data);
                  $("#form_update")[0].reset();
                  if(data !='null')
                  {              
                     //check if primary data 
                     $(".type option[value= '"+data.result.id+"']").attr("selected", "selected");
                     $('.label_name').val(data.result.label_name);
                     $('.input_id').val(data.result.input_id);
                     $(".mandatory option[value= '"+data.result.is_mandatory+"']").attr("selected", "selected");
                     $(".report_output option[value= '"+data.result.is_report_output+"']").attr("selected", "selected");
                     $(".executive_output option[value= '"+data.result.is_executive_summary+"']").attr("selected", "selected");
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  alert("Error: " + errorThrown);
            }
         });

      });

      //
      $(document).on('submit', 'form#form_update', function (event) {
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
                  $('#error-all').html(data.message);
               }
               if (data.fail == false) {
                  $('#edit_form_input_modal').modal('hide');
                  //notify
                  toastr.success("Updated successfully");
                  $('#error-all').html(data.message);
                  var labelName ="";
                  var type ="";
                  var report_output = 0;
                  var executive_summary = 0;
                  $('#formInputsTable tr').each(function() {
                     labelName = data.data.label_name;
                     type = data.data.type;
                     report_output=data.data.report_output;
                     executive_summary = data.data.executive_summary;
                     $(this).find("td.labelName-"+data.data.input_id).html(labelName);   
                     $(this).find("td.type-"+data.data.input_id).html(type);
                     if(report_output==1)
                     {
                        $(this).find("td.report_output-"+data.data.input_id).html('<span class="text-success">Yes</span><br>');
                     }
                     else if(report_output==0)
                     {
                        $(this).find("td.report_output-"+data.data.input_id).html('<span class="">No</span><br>');
                     }

                     if(executive_summary==1)
                     {
                        $(this).find("td.report_output-"+data.data.input_id).append('<span class="text-small">Executive Summary: <span class="text-success">Yes</span> </span>');
                     }
                  });

                  // alert(labelName);
                  //
                  // $(".rowResult").append(" <tr><td> "+data.data.label_name+" </td><td> "+data.data.type+" </td><td> <a href='javascript:;' >Edit</a> </td></tr>"); 
               }
            },
            error: function (data) {
               console.log(data);
            }
         });
         return false;

         });

      //
      $(document).on('submit', 'form#form_inputs', function (event) {
         $("#overlay").fadeIn(300);　
         event.preventDefault();
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var $btn = $(this);
         var report_output='';
         var executive_summary='';
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
                  $('#form_input_modal').modal('hide');
                  toastr.success("Created successfully");
                  $('.no_record').remove();
                  if(data.data.report_output==1)
                  {
                     report_output='<span class="text-success">Yes</span><br>';
                  }
                  else if(data.data.report_output==0)
                  {
                     report_output='<span class="text-success">No</span><br>';
                  }

                  if(data.data.executive_summary==1)
                  {
                     executive_summary='<span class="text-small">Executive Summary: <span class="text-success">Yes</span> </span>';
                  }
                  $(".rowResult").append(" <tr><td class='labelName-"+data.data.id+"'> "+data.data.label_name+" </td><td class='type-"+data.data.input_id+"'> "+data.data.type+" </td><td class='report_output-"+data.data.input_id+"'>"+report_output+" "+executive_summary+" </td><td> <a href='javascript:;' class='editInput btn btn-md btn-outline-primary' data-id='"+data.data.id+"'><i class='far fa-edit'></i> Edit</a> </td></tr>"); 
               }
            },
            error: function (data) {
               console.log(data);
            }
         });
         return false;

      });

      //when click on delete button
      // $(document).on('click', '.deleteInput', function (event) {
         
      //    var id = $(this).attr('data-id');
      //    //  alert(id);
      //    if(confirm("Are you sure want to delete this input ?")){
      //    $.ajax({
      //       type:'POST',
      //       url: "{{ url('/verifications/formInput/delete')}}",
      //       data: {"_token": "{{ csrf_token() }}",'id':id},        
      //       success: function (response) {        
      //       console.log(response);
            
      //             if (response.status=='ok') { 

      //                toastr.success("Input Deleted Successfully");
      //                // window.setTimeout(function(){
      //                //    location.reload();
      //                // },2000);
      //                $('table.formInputTable tr').find("[data-id='" + id + "']").parent().parent().fadeOut("slow");

      //                if(response.db==false)
      //                {
      //                   $('.rowResult').append('<tr class="no_record"> <td class="text-center" colspan="4">No record!</td> </tr>');
      //                }
      //             } 
      //       },
      //       error: function (xhr, textStatus, errorThrown) {
      //             alert("Error: " + errorThrown);
      //       }
      //    });

      //    }
      //    return false;

      // });
   
   });
                     
</script>  
@endsection
