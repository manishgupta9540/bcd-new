<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
                <table class="table table-bordered table-hover reportTable ">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Contact</th>
                            <th scope="col">SLA</th>
                            <th scope="col">Report Type</th>
                            <th scope="col">Created at</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if( count($items) > 0)
                        @foreach($items as $item)
                        <tr data-row="{{ $item->id }}">
                            <th scope="row"><input class="reports" type="checkbox" name="reports[]" value="{{ $item->id }}"></th>
                            <th scope="row">{{ $item->id }}</th>
                            <td class="candidateName">
                            {{ $item->candidate_id.'-'.Helper::get_candidate_fullname($item->candidate_id)}} <br>
                            <small class="text-muted">Customer: <b>{{ Helper::company_name($item->business_id)}}</b></small>
                            </td>
                            <td > {{ Helper::get_single_data('users','phone','id',$item->candidate_id) }} </td>
                            <td >
                            {{ $item->title }}  
                            </td>
                            <td>
                                @if($item->report_type == 'manual')
                                <span class="badge badge-info">
                                {{ ucfirst($item->report_type) }}</span>
                                @else
                                <span class="badge badge-success">
                                {{ ucfirst($item->report_type) }}</span>
                                @endif
                            </td>
                            <td>{{ date('d-m-Y',strtotime($item->created_at) ) }}</td>
                            <td>
                            
                                @if($item->status == 'incomplete')
                                <span class="badge badge-danger">
                                {{ ucfirst($item->status) }}</span><br>
                                    @if($item->manual_input_status == 'input_file')
                                        {{-- <a href="{{ url('candidate/report-generate',['id'=>  base64_encode($item->candidate_id) ]) }}" style='font-size:14px;' class="bnt-link">Generate Report</a> 
                                         --}}
                                         <a style='font-size:14px;' class="btn-lnk send_otp cursor-pointer" data-id={{ base64_encode($item->candidate_id) }}>Generate Report</a>  
                                    @endif

                                @else
                                <span class="badge badge-success">
                                {{ ucfirst($item->status) }}</span>
                                @endif
                            
                            </td>
                            <td>
                            @if($item->status == 'incomplete')
                                <a href="{{ url('/candidate/report-edit',['id'=> base64_encode($item->candidate_id)]) }}">
                                    <button class="btn btn-sm btn-info" type="button"> <i class="fa fa-edit"></i> Edit</button>
                                </a>
                            @else
                                <!-- <a href="">
                                    <button class="btn btn-sm btn-info" type="button"> <i class="fa fa-edit"></i> View</button>
                                </a> -->
                            @endif
                            @if($item->status == 'completed')
                                <a href="{{ url('/candidate/report-edit',['id'=> base64_encode($item->candidate_id)]) }}"> 
                                    <button class="btn btn-sm btn-info" type="button"> <i class="fa fa-edit"></i> Edit</button>
                                </a>
                                
                                <button class="btn btn-sm btn-primary reportExportBox" data-id="{{  base64_encode($item->id) }}" type="button"> <i class="fa fa-download"></i> PDF</button>
                                <button class="btn btn-sm btn-primary reportPreviewBox" data-id="{{base64_encode($item->id) }}" type="button">  Preview</button>
                            @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr><td colspan="7" class="text-center"> <h3>Report is not created yet!</h3> </td></tr>
                        @endif
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-5">
                <div class="dataTables_info" role="status" aria-live="polite"></div>
            </div>
            <div class="col-sm-12 col-md-7">
              <div class=" paging_simple_numbers" >            
                  {!! $items->render() !!}
              </div>
            </div>
        </div>

                </div>
         </div>
    </div>

</div>


<div class="modal" id="send_otp">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">OTP Verification</h4>
             <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('/candidates/verfiy_otp')}}" id="verify_otp">
          @csrf
            <input type="hidden" name="can_id" id="can_id">
             <div class="modal-body">
             <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                <div class="form-group">
                      <label for="label_name"> OTP </label>
                      <input type="text" id="otp " name="otp" class="form-control otp" placeholder="Enter OTP"/>
                      <p style="margin-bottom: 2px;" class="text-danger" id="error-otp"></p> 
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
             </div>
          </form>
       </div>
    </div>
</div>
{{-- Modal for Report preview --}}
<div class="modal"  id="preview">
    <div class="modal-dialog modal-lg" style="max-width: 90% !important;">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Report Preview</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          
             <div class="modal-body">
             <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                <iframe 
                    src="" 
                    style="width:100%; height:500px;" 
                    frameborder="0" id="preview_pdf">
                </iframe>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="button" class="btn btn-danger back" data-dismiss="modal">Close</button>
             </div>
       </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        $('.send_otp').click(function(){
            var id=$(this).attr('data-id');
            $('#can_id').val(id);
            // alert("hrllo");
            $.ajax({
                url:"{{ route('/candidates/send_otp') }}",
                method:"POST",
                data:{"_token": "{{ csrf_token() }}",'_id':id},      
                success:function(data)
                {
                    console.log(data);
                    if(data.fail == false)
                    {
                    //notify
                        $('#send_otp').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        // console.log(data.id);
                    }
                    else
                    {
                        alert('not working');
                    }
                }
            });
            
        });

        $(document).on('submit', 'form#verify_otp', function (event) {

            $("#overlay").fadeIn(300);　
            event.preventDefault();
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var $btn = $(this);

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
                            $('input[otp=' + control + ']').addClass('is-invalid');
                            $('#error-' + control).html(data.errors[control]);
                            }
                    } 
                    if (data.fail && data.error == 'yes') {
                        
                        $('#error-all').html(data.message);
                    }
                    if (data.fail == false) {
                        // $('#send_otp').modal('hide');
                        // alert(data.id);
                        var candidate_id=data.id;

                        window.location.href="{{ url('candidate/report-generate') }}/"+candidate_id;
                        // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                        //  location.reload(); 
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log("Error: " + errorThrown);
                    // alert("Error: " + errorThrown);

                }
            });
            return false;

        });
}); 
</script>

<script>
// Preview Report
$('.reportPreviewBox').click(function(){
                        // alert('ads');
    var report_id = $(this).attr('data-id');
    document.getElementById('preview_pdf').src="{{ url('/') }}"+"/candidate/report/preview/"+report_id;

    $('#preview').toggle();
});

$('.close').click(function(){
    $('#preview').hide();
});
$('.back').click(function(){
    $('#preview').hide();
});
</script>