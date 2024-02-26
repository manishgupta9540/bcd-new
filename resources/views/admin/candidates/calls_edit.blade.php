@php
    $values = explode(",",$candidateCalls->service_id);
@endphp


<form method="post" action="{{ url('/candidates/callingdataupdate') }}" id="call_edit_form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="candcall_id" value="{{$candidateCalls->id}}" id="candcall_id">
    <input type="hidden" name="candidate_id" value="{{base64_encode($candidateCalls->candidate_id)}}" id="candidate_id">
    <div class="modal-body">
        <div class="form-group">
            <label for="label_name">Person Name: <span class="text-danger">*</span></label>
            <input type="text" name="person_name" id="person_name" value="{{$candidateCalls->person_name}}" class="form-control person_name">
            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-person_name"></p>
        </div>
        
        <div class="form-group">
            <label for="label_name"> Remarks </label>
            <textarea id="remarks" name="remarks" class="form-control remarks"placeholder="">{{$candidateCalls->remarks}}</textarea>
            <!-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-remarks"></p> -->
        </div>

        <div class="form-group">
            <label>Start Date <span class="text-danger">*</span></label>
                <input type="text" class="form-control datetimepicker start_date" value="{{$candidateCalls->start_date}}" autocomplete="off" name="start_date" id="StartDate"/>
            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-start_date"></p>
        </div>

        <div class="form-group">
            <label>End Date <span class="text-danger">*</span></label>
            <input type="text" class="form-control datetimepicker end_date" value="{{$candidateCalls->end_date}}" autocomplete="off" name="end_date" id="EndDate"/>
            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-end_date"></p>
        </div>

        <div class="form-group">
            <label for="picker1"><strong>Check:</strong><span class="text-danger">*</span> </label>
            <select class="form-control check" name="check[]" id="check" data-actions-box="true" data-selected-text-format="count>1" multiple>
                    @foreach($services as $service)
                        <option value="{{ $service->id}}" {{ in_array($service->id,$values) ? 'selected' : '' }}>{{ $service->name  }}</option>   
                    @endforeach
            </select>
            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-check"></p>
        </div>
    
        <div class="form-group">
            <label for="label_name"> Attachments: <i class="fa fa-info-circle tool" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i></label>
            <input type="file" name="attachment[]" id="attachment" multiple class="form-control attachment">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachment"></p>
        </div>
        
        @if(count($candidatefile)>0)
                <label><strong>Attachments: </strong></label>
                <div class="row">
                @foreach($candidatefile as $item)
                    @if(stripos($item->file_name, 'pdf')!==false) 
                        <img src="admin/images/icon_pdf.png" title="file_name" alt="preview" style="height:100px;"/>
                    @else
                    <div class="col-2">
                        <div class="image-area" style="width:110px;">
                            <img src="{{asset('/uploads/calls/'.$item->file_name)}}" title="file_name" alt="preview" style="height:100px;"/>
                            <a class="remove-image" data-id="{{ $item->id }}" href="javascript:;" style="display: inline;">×</a>
                            <input type="hidden" name="fileID[]" value="{{ $item->id }}">
                        </div>
                    </div>
                    @endif
                @endforeach
                </div>
            @endif

    <!-- Modal footer -->
    <div class="modal-footer">
        <button type="submit" class="btn btn-info clear-submit submit">Submit</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>
</form>

<script>
     $('.check').selectpicker();

     $('.datetimepicker').datetimepicker({
        format:'d-m-Y H:i',
        inline:false,
        lang:'ru'
    });

      //remove file
$(document).on('click','.remove-image',function(){ 

    var current = $(this);
    var id = $(this).attr('data-id');
    swal({
        // icon: "warning",
        type: "warning",
        title: "Are You Want to Remove?",
        text: "",
        dangerMode: true,
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "YES",
        cancelButtonText: "CANCEL",
        closeOnConfirm: false,
        closeOnCancel: false
        },
        function(e){
            if(e==true)
            {
                var fd = new FormData();

                fd.append('id',id);
                fd.append('_token', '{{csrf_token()}}');

                $.ajax({
                    type: 'POST',
                    url: "{{ url('/call/remove/image') }}",
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        // console.log(data);
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
                swal.close();
            }
            else
            {
                swal.close();
            }
        }
    );

});
</script>