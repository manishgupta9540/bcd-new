@extends('layouts.admin')
@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">
<div class="row">
   <div class="card text-left">
      <div class="card-body">
         <div class="row">
            <div class="col-md-8">
               <h4 class="card-title mb-1">Complete manual report </h4>
               <p>Add your comment and supportings.</p>
            </div>
            <div class="col-md-12">
               <form class="mt-2" method="post" action="{{ url('/reports/output-process/save') }}" id="report_form">
                @csrf
                <!-- candidate info -->
                <input type="hidden" name="report_id" value="{{ Request::segment(3) }}">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="card-title mb-3 mt-2">Candidate Profile </h4>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>First name </label>
                                    <input class="form-control " type="text" name="first_name" value="{{ $candidate->first_name }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>Last name</label>
                                <input class="form-control number_only" type="text" name="last_name" value="{{ $candidate->last_name }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>Email </label>
                                <input class="form-control " type="text" name="email" value="{{ $candidate->email }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>Phone</label>
                                <input class="form-control number_only" type="text" name="phone" value="{{ $candidate->phone }}">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                     <div class="col-md-12">
                         <hr>
                     </div>
                </div>   
                <!-- service item -->

                @if( count($report_items) >0  )
                @foreach($report_items as $item)
                  <div class="row">
                     <div class="col-md-6">
                        <h3 class=" mb-2 mt-2">Verification - {{$item->service_name}}</h3>
                        <p>Provide the inputs and Comments</p>
                        <!--  -->
                        <?php
                            $jafArray = json_decode($jaf->form_data_all,true); 
                            $i=0; $form_items= Helper::get_sla_item_inputs($item->service_id); ?>

                        @foreach($form_items as $input)
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <label> {{ $input->label_name }} </label>
                                    <input type="hidden" name="service-input-label-{{ $i.'-'.$item->service_id }}" value="{{ $input->label_name }}">
                                    <input class="form-control " type="text" name="service-input-value-{{ $i.'-'.$item->service_id }}" value="{{ $jafArray['service-input-value-'.$i.'-'.$item->service_id] }}">
                                    </div>
                                </div>
                            </div>
                        <?php $i++; ?>
                        @endforeach
                        <!-- comment  -->
                            <div class="row">
                                <div class="col-sm-12"> 
                                    <h4 class="card-title mb-2 mt-2">Approval Inputs  </h4>
                                </div>   
                                
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label> Verified By</label>
                                        <input class="form-control " type="text" name="verified_by-{{ $item->service_id }}" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label> Comments</label>
                                        <textarea class="form-control " type="text" name="comments-{{ $item->service_id }}" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Additional Comments</label>
                                        <textarea class="form-control " type="text" name="additional-comments-{{ $item->service_id }}" ></textarea>
                                    </div>
                                </div>
                            </div>

                     </div>
                     <!-- attachment  -->
                     <div class="col-md-6">
                        <p>Attachments</p>
                        <?php $item_files = Helper::getReportAttachFiles($item->id); //print_r($item_files); ?>
                        @foreach($item_files as $file)
                            @if($file['attachment_type'] == 'main')
                            <div class="col-sm-6"> 
                                <img src="{{ $file['filePath'] }}" style='height:100px; border:1px solid #ddd;' >
                            </div>
                            @endif
                        @endforeach
                        <p class="mt-2" style="margin-bottom:1px">Add Supportings</p>

                        <a class='btn-link clickSelectFile' add-id="{{$item->service_id}}" style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                        <input type='file' class='fileupload' name="file-{{$item->service_id}}[]" id='file-{{$item->service_id}}' multiple="multiple" style='display:none'/>
                        <div class='row fileResult' id="fileResult-{{$item->service_id}}" style='min-height: 20px; margin-top: 20px;'>
                        <?php $item_files = Helper::getReportAttachFiles($item->id); //print_r($item_files); ?>
                        @foreach($item_files as $file)
                            @if($file['attachment_type'] == 'supporting')
                            <div class="col-sm-6"> 
                                <img src="{{ $file['filePath'] }}" style='height:100px; border:1px solid #ddd;' >
                            </div>
                            @endif
                        @endforeach
                        </div>

                     </div>
                  </div>
                
                 @endforeach
                 @endif
                 
                  <button type="submit" class="btn btn-primary mt-3">Save</button>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

@stack('scripts')
<script type="text/javascript">
   //
   $(document).ready(function() {
    
   $('.customer_list').prop('selectedIndex',0);
   // on select customer list
    $(document).on('change','.customer_list',function(e) {
        e.preventDefault();
        $('.candidate_list').empty();
        $('.candidate_list').append("<option value=''>-Select-</option>");
        var customer_id = $('.customer_list option:selected').val();
        $.ajax({
        type:"POST",
        url: "{{ url('/customers/candidates/getlist') }}",
        data: {"_token": "{{ csrf_token() }}",'customer_id':customer_id},      
        success: function (response) {
            console.log(response);
            if(response.success==true  ) {   
                $.each(response.data, function (i, item) {
                  $(".candidate_list").append("<option value='"+item.id+"'>" + item.first_name +' '+item.last_name+ "</option>");
                });
            }
            //show the form validates error
            if(response.success==false ) {                              
                for (control in response.errors) {   
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

      //on select sla item
    $(document).on('change','.customer_list',function(e) {
        e.preventDefault();
        $('.sla_list').empty();
        $('.sla_list').append("<option value=''>-Select-</option>");

        var customer_id = $('.customer_list option:selected').val();
        $.ajax({
        type:"POST",
        url: "{{ url('/customers/sla/getlist') }}",
        data: {"_token": "{{ csrf_token() }}",'customer_id':customer_id},      
        success: function (response) {
            console.log(response);
            if(response.success==true  ) {   
                $.each(response.data, function (i, item) {
                  $(".sla_list").append("<option value='"+item.id+"'>" + item.title +"</option>");
                });
            }
            //show the form validates error
            if(response.success==false ) {                              
                for (control in response.errors) {   
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

     //on select sla item
     $(document).on('change','.sla_list',function(e) {
        e.preventDefault();
        $(".SLAResult").html("");
        var sla_id = $('.sla_list option:selected').val();
        $.ajax({
        type:"POST",
        url: "{{ url('/customer/sla/serviceItems') }}",
        data: {"_token": "{{ csrf_token() }}",'sla_id':sla_id},      
        success: function (response) {
            console.log(response);
            if(response.success==true  ) {   
                $.each(response.data, function (i, item) {
                    $(".SLAResult").append("<div class='form-check form-check-inline'><input class='form-check-input services_list' type='checkbox' name='services[]' value='"+item.sla_item_id+"' id='"+item.sla_item_id+"' data-string='"+item.service_name+"' data-type='"+item.verification_type+"'><label class='form-check-label' for='"+item.sla_item_id+"'>"+item.service_name+"</label></div>");
                });
            }
            //show the form validates error
            if(response.success==false ) {                              
                for (control in response.errors) {   
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

    //
    $(document).on("change",".services_list",function() {
         if(this.checked)
         {
            var id   =  $(this).attr("value");
            var text =  $(this).attr("data-string");
            var type =  $(this).attr("data-type");
            $(".service_result").append("<div class='row mt-3' style='min-height:120px; padding:15px; border: 1px solid #ddd;' id='row-"+id+"'><div class='col-sm-3'><label stye='font-size:14px;'>"+text+"</label></div><a class='btn-link clickSelectFile' add-id='1' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a><input type='file' class='fileupload' name='file-"+id+"' style='display:none'/></div>");
            if(type == 'Auto'){
               // $(".service_result").append("<div class='row mt-3' style='min-height:120px; padding:15px; border: 1px solid #ddd;' id='row-"+id+"'><div class='col-sm-3'><label stye='font-size:14px;'>"+text+"</label></div><div class='col-sm-4'><input class='form-control' type='text' name='service_item' value=''></div><div class='col-sm-2'><button type='button' class='btn btn-sm btn-primary' >Check</button></div></div>");
            }else{
               
            }
         }
         else
         {
            var id =  $(this).attr("value");
            $("div#row-"+id).remove();
         }
      });

      // 
      var curNum ='';
      //
      $(document).on('click','.clickSelectFile',function(){ 
         curNum = $(this).attr('add-id');
         $(this).next('input[type="file"]').trigger('click');
      });
      //
      $(document).on('change','.fileupload',function(e){        
        uploadFile(curNum);
      });

   });

function uploadFile(dynamicID){

$("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 

var fd = new FormData();
var ins = document.getElementById('file-'+dynamicID).files.length;
// alert(ins);
for (var x = 0; x < ins; x++) {
    fd.append("files[]", document.getElementById('file-'+dynamicID).files[x]);
}

fd.append('report_id',"{{ Request::segment(3) }}");
fd.append('service_id',dynamicID);
fd.append('_token', '{{csrf_token()}}');
//
$.ajax({
      type: 'POST',
      url: "{{ url('/reports/upload/file') }}",
      data: fd,
      processData: false,
      contentType: false,
      success: function(data) {
        console.log(data);
        if (data.fail == false) {
        //reset data
        $('.fileupload').val("");
        $("#fileUploadProcess").html("");
        //append result
        $.each(data.data, function(key, value) {
            console.log(value);
            $("#fileResult-"+dynamicID).prepend("<div class='image-area'><img src='"+value.filePrev+"'  alt='Preview'><a class='remove-image' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.file_id+"'><p style='font-size:12px;'>"+data.filename+"</p></div>");
        });

            
        } else {
          $("#fileUploadProcess").html("");
          alert("Please upload valid file! allowed file type, Image JPG, PNG etc. ");
          console.log("file error!");
          
        }
      },
      error: function(error) {
          console.log(error);
          // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
      }
  });
  
  return false;
}

</script>  
@endsection