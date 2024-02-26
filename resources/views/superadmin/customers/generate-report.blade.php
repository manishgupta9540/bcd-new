@extends('layouts.superadmin')
@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">
<div class="row">
   <div class="card text-left">
      <div class="card-body">
         <div class="row">
            <div class="col-md-8">
               <h4 class="card-title mb-1">Generate Manual report </h4>
               <p>Add your comment and supportings. (Remarks: Checked = Yes, Left Blank = -)</p>
            </div>
            <div class="col-md-12">
               <form class="mt-2" method="post" action="{{ url('/reports/output-process/save') }}" id="report_form">
                @csrf
                <!-- candidate info -->
                <input type="hidden" name="report_id" value="{{ base64_encode($report_id) }}">
                <div class="row">
                    <div class="col-md-8">
                    <h4 class="card-title mb-3 mt-2">Candidate : <b> {{ $candidate->id.'-'.$candidate->first_name.' '.$candidate->last_name }} </b></h4>
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
                <!--  -->
                <?php
                    //get sale item count
                    $j=1;
                    $num ="";                    
                ?>
                  <div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                     <div class="col-md-6">
                        <h3 class=" mb-2 mt-2">Verification - {{$item->service_name}}</h3>
                        <p>Provide the inputs and Comments</p>
                        <!--  -->
                        <?php 
                            $input_item_data = $item->jaf_data;
                            $input_item_data_array =  json_decode($input_item_data, true); 
                            $i=0;
                        ?>
                        @foreach($input_item_data_array as $key => $input)
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <?php $key_val = array_keys($input); $input_val = array_values($input); ?>
                                    <label>  {{ $key_val[0]}} </label>
                                    <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                    <input class="form-control " type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                    <label> Remarks </label>
                                        <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="remarks-input-checkbox-{{ $item->id.'-'.$i}}" class="form-check-input" >
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
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
                                        <input class="form-control " type="text" name="verified_by-{{ $item->id }}" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label> Comments</label>
                                        <textarea class="form-control " type="text" name="comments-{{ $item->id }}" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Additional Comments</label>
                                        <textarea class="form-control " type="text" name="additional-comments-{{ $item->id }}" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Aproval Status</label>
                                        <select class="form-control " name="approval-status-{{ $item->id }}" >
                                            @foreach($status_list as $status)
                                            <option value="{{ $status->id}}" @if($status->id == '4') selected @endif > {{ $status->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <!-- Court inpput start -->
                            @if( $item->service_id == 15 || $item->service_id == 16 )  
                            <div class="row mt-2">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                    <label> <b> Court </b></label>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <label> <b>Court Name </b></label>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                    <label> <b>Result</b> </label>
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                       <p>District Court/ Lower Court/ Civil Court & Small Causes</p>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="district_court_name-{{$item->id}}" class="form-control" >
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="district_court_result-{{$item->id}}" class="form-control" >
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                            <!-- row. -->
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                       <p>High Court</p>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="high_court_name-{{$item->id}}" class="form-control" >
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="high_court_result-{{$item->id}}" class="form-control" >
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                            <!-- ./row -->
                            <!-- row. -->
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                       <p>Supreme Court</p>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="supreme_court_name-{{$item->id}}" class="form-control" >
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="supreme_court_result-{{$item->id}}" class="form-control" >
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                            <!-- ./row -->
                            @endif
                        <!-- ./ end court  -->

                     </div>
                     <!-- attachment  -->
                     <div class="col-md-6">
                        <p>Attachments</p>
                        <a class='btn-link clickSelectFile' add-id="{{$item->id}}" data-number='1' data-result='fileResult1' data-type='main' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                        <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file1-{{$item->id}}' multiple="multiple" style='display:none'/>
                        <div class='row fileResult' id="fileResult1-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>
                        <?php $item_files = Helper::getReportAttachFiles($item->id); //print_r($item_files); ?>
                        @foreach($item_files as $file)
                            @if($file['attachment_type'] == 'main')
                            <div class="image-area">
                                <img src="{{ $file['filePath'] }}" alt="Preview">
                                <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a>
                                <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                            </div>
                            @endif
                        @endforeach
                        </div>
                        <p class="mt-2" style="margin-bottom:1px">Add Supportings</p>
                        <a class='btn-link clickSelectFile' add-id="{{$item->id}}" data-number='2' data-result='fileResult2' data-type='supporting' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                        <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file2-{{$item->id}}' multiple="multiple" style='display:none'/>
                        <div class='row fileResult' id="fileResult2-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>
                        <?php $item_files = Helper::getReportAttachFiles($item->id); //print_r($item_files); ?>
                        @foreach($item_files as $file)
                            @if($file['attachment_type'] == 'supporting')
                            <div class="image-area">
                                <img src="{{ $file['filePath'] }}" alt="Preview">
                                <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a>
                                <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                            </div>
                            @endif
                        @endforeach
                        </div>
                     </div>
                  </div>
                  <!-- row close -->
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
      var curNum ='';
      var fileResult='fileResult2';
      var type = 'main';
      var number = '1';
      $(document).on('click','.clickSelectFile',function(){ 
         curNum     = $(this).attr('add-id');
         fileResult = $(this).attr('data-result');
         type = $(this).attr('data-type');
         number = $(this).attr('data-number');
        //  alert(fileResult);
         $(this).next('input[type="file"]').trigger('click');
      });
      //
      $(document).on('change','.fileupload',function(e){        
        uploadFile(curNum,fileResult,type,number);
      });

      //remove file
    $(document).on('click','.remove-image',function(){ 

var r = confirm("Are you want to remove?");
if (r == true) {
$('#fileupload-'+curNum).val("");
var current = $(this);
var file_id = $(this).attr('data-id');
//
var fd = new FormData();

fd.append('file_id',file_id);
fd.append('_token', '{{csrf_token()}}');
//
$.ajax({
    type: 'POST',
    url: "{{ url('/reports/remove/file') }}",
    data: fd,
    processData: false,
    contentType: false,
    success: function(data) {
        console.log(data);
        if (data.fail == false) {
        //reset data
        $('.fileupload').val("");
        //append result
        $(current).parent('.image-area').detach();
        } else {
        
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

});

   });

function uploadFile(dynamicID,fileResult,type,number){

$("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 

var fd = new FormData();
var ins = document.getElementById("file"+number+"-"+dynamicID).files.length;
// alert(ins);
for (var x = 0; x < ins; x++) {
    fd.append("files[]", document.getElementById("file"+number+"-"+dynamicID).files[x]);
}

fd.append('report_id',"{{ base64_encode($report_id) }}");
fd.append('report_item_id',dynamicID);
fd.append('type',type);
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
            $("#"+fileResult+"-"+dynamicID).append("<div class='image-area'><img src='"+value.filePrev+"'  alt='Preview'><a class='remove-image' data-id='"+value.file_id+"' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+value.file_id+"'></div>");
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