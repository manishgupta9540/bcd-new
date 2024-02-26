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
               <h4 class="card-title mb-1">Create manual report </h4>
               <p>Select a Customer and a Candidate with multiple checks to create reporting.</p>
            </div>
            <div class="col-md-8">
               <form class="mt-2" method="post" action="{{ url('/reports/attachment/save') }}" id="report_form">
                  @csrf
                  <!-- select customer  -->
                  <div class="form-group">
                     <label for="customer">Customer</label>
                     <select class="form-control customer_list" name="customer">
                        <option value="">-Select-</option>
                        @if( count($customers) > 0 )
                        @foreach($customers as $item)
                        <option value="{{ $item->id }}">{{ ucfirst($item->company_name).' '.'('.$item->first_name.')' }}</option>
                        @endforeach
                        @endif
                     </select>
                     @if ($errors->has('customer'))
                     <div class="error text-danger">
                        {{ $errors->first('customer') }}
                     </div>
                     @endif
                  </div>

                  <!-- select a Candidate  -->
                  <div class="form-group">
                     <label for="candidate">Candidate</label>
                     <select class="form-control candidate_list" name="candidate">
                        <option value="">-Select-</option>
                        <option value=""></option>
                     </select>                     
                  </div>
                 
                  <div class="form-group">
                     <div class="form-group SLAResult"></div>
                  </div>

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
                  $(".candidate_list").append("<option value='"+item.id+"'> "+item.id+"-" + item.first_name +' '+item.last_name+ "</option>");
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
                    $(".SLAResult").append("<div class='form-check form-check-inline'><input class='form-check-input services_list' type='checkbox' name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' data-string='"+item.service_name+"' data-type='"+item.verification_type+"'><label class='form-check-label' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
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
            
            if(type == 'Auto'){
               $(".service_result").append("<div class='row mt-3' style='min-height:120px; padding:15px; border: 1px solid #ddd;' id='row-"+id+"'><div class='col-sm-3'><label stye='font-size:14px;'>"+text+"</label></div><div class='col-sm-4'><input class='form-control' type='text' name='check_number-"+id+"' value=''></div><a class='btn-link clickSelectFile' add-id='"+id+"' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a><input type='file' class='fileupload' name='file-"+id+"' style='display:none'/><div class='col-md-12'><div class='row fileResult' id='fileResult-"+id+"' style='min-height: 20px; margin-top: 20px;'></div></div></div>");
            }else{
               $(".service_result").append("<div class='row mt-3' style='min-height:120px; padding:15px; border: 1px solid #ddd;' id='row-"+id+"'><div class='col-sm-3'><label stye='font-size:14px;'>"+text+"</label></div><a class='btn-link clickSelectFile' add-id='"+id+"' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a><input type='file' class='fileupload' name='file-"+id+"' style='display:none'/><div class='col-md-12'><div class='row fileResult' id='fileResult-"+id+"' style='min-height: 20px; margin-top: 20px;'></div></div></div>");
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
         $('.fileupload').trigger('click');
      });

      $(document).on('click','.remove-image',function(){ 
         $('#fileupload-'+curNum).val("");
         $(this).parent('.image-area').detach();
      });

      $(document).on('change','.fileupload',function(e){          
         uploadFile(curNum);
      });


      // submit form
      $(document).on('submit', 'form#report_form1', function (event) {
         event.preventDefault();
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
            success: function (data) {
               
                  $('.is-invalid').removeClass('is-invalid');
                  if (data.fail) {
                     for (control in data.errors) {
                        $('input[name=' + control + ']').addClass('is-invalid');
                        $('#error-' + control).html(data.errors[control]);
                     }
                  } else {
                     $('#modalForm').modal('hide');
                     // window.location.reload();
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  alert("Error: " + errorThrown);
            }
         });
         return false;
      });

   });

function uploadFile(dynamicID){

$("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 

var fd = new FormData();
var file = $('.fileupload')[0].files[0];
fd.append('files[]',file);
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
         $("#fileResult-"+dynamicID).prepend("<div class='image-area'><img src='"+data.data[0].filePrev+"'  alt='Preview'><a class='remove-image' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.file_id+"'><p style='font-size:12px;'>"+data.filename+"</p></div>");
        } else {
          $("#fileUploadProcess").html("");
          alert("Please upload valid file! allowed file type, Image: JPG, PNG etc. ");
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