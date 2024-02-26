        <div class="row">
            <div class="col-md-5"></div>
        <div class="col-md-6 mb-2">
            <span style=" text-align: right;">Total Candidates: <span > {{ $tota_candidates }}</span> </span>
        </div>
    </div>
        <div class="row">
                <div class="col-md-12">
                    {{-- <div class="table-responsive"> --}}
                            <table class="table table-bordered table-hover reportTable ">
                                <thead>
                                    <tr>
                                        <th scope="col" style="position:sticky; top:60px"   ><input  type="checkbox" name='showhide' onchange="checkAll(this)" ></th>
                                        {{-- <th scope="col" style="position:sticky; top:60px">ID</th> --}}
                                        <th scope="col" style="position:sticky; top:60px">Name</th>
                                        <th scope="col" style="position:sticky; top:60px" width="15%">Contact</th>
                                        <th scope="col" style="position:sticky; top:60px">SLA</th>
                                        <th scope="col" style="position:sticky; top:60px">Report Type</th>
                                        <th scope="col" style="position:sticky; top:60px">Created at</th>
                                        <th scope="col" style="position:sticky; top:60px">Status</th>
                                        <th scope="col" style="position:sticky; top:60px">Billing Status</th>
                                        <th scope="col" style="position:sticky; top:60px">Action</th>
                                    </tr>
                                </thead> 
                                <tbody> 
                                    @php
                                        // $ADD_ACCESS    = false;
                                        $REPORT_ACCESS   = false;
                                        $VIEW_ACCESS   = false;
                                        $EDIT_ACCESS =false; 
                                        $GENERATE_REPORT_ACCESS = false;
                                        // dd($ADD_ACCESS);
                                        $REPORT_ACCESS    = Helper::can_access('Download Report pdf','');//passing action title and route group name
                                        $VIEW_ACCESS   = Helper::can_access('View Reports','');//passing action title and route group name
                                        $EDIT_ACCESS   = Helper::can_access('Edit Reports','');//passing action title and route group name
                
                                        $GENERATE_REPORT_ACCESS = Helper::can_access('Generate Reports ','');
                                    @endphp
                                    @if( count($items) > 0)
                                        @foreach($items as $item)
                                            <tr data-row="{{ $item->id }}">
                                                <th scope="row"><input class="reports" type="checkbox" name="reports" value="{{ $item->id }}" onchange='checkChange();'></th>
                                                {{-- <th scope="row">{{ $item->id }}</th> --}}
                                                <td class="candidateName">
                                                    @if($item->is_manual_mark==1)
                                                        <i class="fa fa-circle" style="color: green;"></i>
                                                    @elseif($item->is_manual_mark==2)
                                                        <i class="fa fa-circle" style="color: rgb(83, 83, 83);"></i>
                                                    @else
                                                        @if($item->approval_status_id == '1')
                                                            <i class="fa fa-circle" style="color: red"></i> 
                                                        @elseif($item->approval_status_id == '2')
                                                            <i class="fa fa-circle high" style="color: yellow"></i>
                                                        @elseif($item->approval_status_id == '3')
                                                            <i class="fa fa-circle" style="color: Orange;"></i>
                                                        @else
                                                            <i class="fa fa-circle" style="color: green;"></i>
                                                        @endif
                                                    @endif
                                                    {{ Helper::get_candidate_fullname($item->candidate_id)}} <br>
                                                    <small class="text-muted">Customer: <b>{{ Helper::company_name($item->business_id)}}</b></small>
                                                    <br>
                                                        <small class="text-muted">Ref. No. <b>{{ Helper::get_single_data('candidate_reinitiates','display_id','id',$item->candidate_id) }}</b></small>
                                                </td>
                                                <td > 
                                                    {{ $item->phone!=null?'+'.$item->phone_code."-".str_replace(' ','',$item->phone):"--" }}
                                                    {{-- {{ Helper::get_single_data('users','phone','id',$item->candidate_id) }}  --}}
                                                    {{-- {{"+".$item->phone_code."-".str_replace(' ','',$item->phone)}} --}}
                                                </td>
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
                                                <td>
                                                    @if($item->generated_at !="" || $item->generated_at != null)
                                                        {{ date('d M Y', strtotime($item->generated_at)) }}
                                                    @else
                                                        {{ date('d M Y', strtotime($item->created_at)) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                    $user_type= Auth::user()->user_type;
                                                    if ($user_type=='user') {
                                                        
                                                        $cam = Helper::get_is_cam($item->business_id);
    
                                                    }
                                                    $ready_report = Helper::get_ready_report($item->candidate_id);
                                                @endphp
                                                @if ($user_type == 'customer' || $cam!==null)
                                                    @if($item->status == 'incomplete')
                                                        <span class="badge badge-danger">
                                                        {{ ucfirst($item->status) }}</span><br>
                                                        @if ($GENERATE_REPORT_ACCESS)
                                                    
                                                            {{-- @if($item->manual_input_status == 'input_file' && $ready_report== 1) --}}
                                                            @if($ready_report== 1)
                                                                <a style='font-size:14px;' class="btn-lnk send_report_otp cursor-pointer" data-id={{ base64_encode($item->candidate_id) }}>Generate Report</a>  
                                                                {{-- <a href="{{ url('candidate/report-generate',['id'=>  base64_encode($item->candidate_id) ]) }}" style='font-size:14px;' class="bnt-link">Generate Report</a>  --}}
                                                            @else
                                                                <span>
                                                                    Data not yet verified 
                                                                </span>
                                                            @endif
                                                        @endif
                                                    @endif
                                               
                                                    @if($item->status == 'completed' || $item->status == 'interim' )
                                                        @php 
                                                            $review = DB::table('feedback')->where('report_id',$item->id)->first();
                                                        @endphp
                                                        {{-- @if (cont($reviews)>0) --}}
                                                        {{-- @foreach ($reviews as $review) --}}
                                                            @if ($item->status == 'completed')
                                                                <span class="badge badge-success">
                                                                    {{ ucfirst($item->status) }}
                                                                </span>
                                                            @else
                                                                <span class="badge badge-warning">
                                                                    {{ ucfirst($item->status) }}
                                                                </span>
                                                            @endif
                                                    
                                                        <br/>
                                                        @if ($review)  
                                                            <div class="my-1">
                                                                <small>
                                                                    @php 
                                                                    for ($i = 0; $i < 5; $i++) {
                                                                    echo '<i class="fa fa-star' ,
                                                                    ($review->stars == $i + .5 ? '-half' : '') ,
                                                                    ($review->stars <= $i ? '-o' : '') ,
                                                                    '" aria-hidden="true" style="color: green;"></i>';
                                                                
                                                                    }
                                                                    @endphp
                                                                </small>
                                                            </div>
                                                            <div class="text-left">
                                                                <a href="#" class=" reportFeedback" data-stars="{{$review->stars}}" data-comment="{{$review->comments}}" data-username="{{ Helper::user_name($item->candidate_id)}}" type="button"> <i class="fa fa-eye"></i> </button>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if($item->status == 'completed' || $item->status == 'interim' )
                                                    
                                                        {{-- @if (cont($reviews)>0) --}}
                                                        {{-- @foreach ($reviews as $review) --}}
                                                            @if ($item->status == 'completed')
                                                                <span class="badge badge-success">
                                                                    {{ ucfirst($item->status) }}
                                                                </span>
                                                            @elseif($item->status == 'interim')
                                                                <span class="badge badge-warning">
                                                                    {{ ucfirst($item->status) }}
                                                                </span>
                                                                <br>
                                                            @else
                                                                <span class="badge badge-danger">Not Generated</span>
                                                            @endif
                                                     @endif
                                                @endif
                                                </td>
                                                <td>
                                                    @php
                                                        // Calling the record by model relationship
                                                        $billing_item = App\BillingItem::where('candidate_id',$item->candidate_id)->latest()->first();
                                                    @endphp

                                                    @if($billing_item!=NULL)
                                                        @if(stripos($billing_item->billing->status,'draft')!==false)
                                                            <span class="badge badge-danger">Draft Invoice</span>
                                                        @elseif(stripos($billing_item->billing->status,'under_review')!==false)
                                                            <span class="badge badge-warning">Under Review</span> 
                                                        @elseif(stripos($billing_item->billing->status,'completed')!==false)
                                                            <span class="badge badge-success">Approved</span> 
                                                        @endif
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->status == 'incomplete')
                                                        {{-- @if ($EDIT_ACCESS)
                                                            <a href="{{ url('/candidate/report-edit',['id'=> base64_encode($item->candidate_id)]) }}">
                                                                <button class="btn btn-sm btn-info" type="button"> <i class="fa fa-edit"></i> Edit</button>
                                                            </a>
                                                            @endif
                                                        @else
                                                            <a href="">
                                                                <button class="btn btn-sm btn-info" type="button"> <i class="fa fa-edit"></i> View</button>
                                                            </a>
                                                        @endif --}}
                                                    @else
                                                        @if ($EDIT_ACCESS)
                                                            <a href="{{ url('/candidate/report-edit',['id'=> base64_encode($item->candidate_id)]) }}"> 
                                                                <button class="btn btn-sm btn-info" type="button"> <i class="fa fa-edit"></i> Edit</button>
                                                            </a> 
                                                        @endif
                                                    
                                                        @if ($REPORT_ACCESS)
                                                            @if ($item->status == 'completed')
                                                                <button class="btn btn-sm btn-info reportExportBox" data-id="{{  base64_encode($item->id) }}"  type="button"> <i class="fa fa-download"></i> PDF</button>

                                                            @else

                                                                <button class="btn btn-sm btn-info interimReportExport" data-id="{{  base64_encode($item->id) }}" data-type ="{{$item->status}}" type="button"> <i class="fa fa-download"></i> PDF</button>

                                                            @endif

                                                        @endif
                                                        <button class="btn btn-sm btn-info reportPreviewBox" data-id="{{base64_encode($item->id) }}" type="button">  Preview</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="9" class="text-center"> <h3>Report is not created yet!</h3> </td></tr>
                                    @endif
                                </tbody>
                            </table>
                            {{-- </div> --}}
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


                    {{-- Modal for otp verification    --}}

                    <div class="modal" id="send_otp">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">OTP Verification to Generate Report</h4>
                                {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
                            </div>
                            <!-- Modal body -->
                            <form method="post" action="{{url('/candidates/verfiy_otp')}}" id="verify_otp">
                            @csrf
                                <input type="hidden" name="can_id" id="can_id">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="label_name"> <strong> Candidate Name : </strong> <span class="c_name"></span> </label>
                                    </div>
                                    <div class="form-group pb-3">
                                        <label for="label_name"> <strong> Reference No. : </strong> <span class="c_ref_no"></span> </label>
                                    </div>
                                    <div class="form-group">
                                        <div class="row justify-content-center align-items-center">
                                            <div class="col-sm-5 text-center">
                                                <label for="label_name"> OTP </label>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center align-items-center">
                                            <div class="col-sm-6 text-center">
                                                <input name="otp[]" class="digit text-center otp" type="text" id="first_otp" size="1" maxlength="1" tabindex="0" >
                                                <input name="otp[]" class="digit text-center otp" type="text" id="second_otp" size="1" maxlength="1" tabindex="1">
                                                <input name="otp[]" class="digit text-center otp" type="text" id="third_otp" size="1" maxlength="1"  tabindex="2">
                                                <input name="otp[]" class="digit text-center otp" type="text" id="fourth_otp" size="1" maxlength="1" tabindex="3">
                                            </div>
                                        </div>
                                        <div class="row justify-content-center align-items-center">
                                            <div class="col-sm-6 text-center">
                                                <p style="margin-bottom: 2px;" class="text-danger error-container pt-2" id="error-otp"></p> 
                                                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="form-group">
                                        <label for="label_name"> OTP </label>
                                        <input type="text" id="otp" name="otp" class="form-control otp" placeholder="Enter OTP"/>
                                        <p style="margin-bottom: 2px;" class="text-danger" id="error-otp"></p> 
                                    </div> --}}
                                </div>
                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-info otp_submit btn_otp">Submit </button>
                                    <button type="button" class="btn btn-danger btn_otp" id="otp_close" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                    
                    <!-- Footer Start -->
                    <div class="flex-grow-1"></div>
                    
                    </div>

                     {{-- Modal for Report Feedback --}}
                     <div class="modal"  id="feedback">
                        <div class="modal-dialog " style="max-width: 50% !important;">
                           <div class="modal-content">
                              
                              <!-- Modal Header -->
                              <div class="modal-header">
                                 <h4 class="modal-title" id="setname"></h4>
                                 <button type="button" class="close" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>&times;</small></button>
                              </div>
                              {{-- <form method="post" action="{{url('my/candidate/report/feedback')}}" id="feedback">
                                @csrf --}}
                                {{-- <input type="hidden" name="report_id" id="report_id">
                                <input type="hidden" name="business_id"  id="business_id">
                                <input type="hidden" name="candidate_id"  id="candidate_id" > --}}
                                 <!-- Modal body -->
                                 <div class="modal-body">
                                    <div class="form-group">
                                        <h4 class="modal-title">Comments</h4> 
                                        <textarea class="form-control" type="text" name="comments" id="setcomment" readonly></textarea>
                                    </div>
                                 </div>
                                 <!-- Modal footer -->
                              </form>
                           </div>
                        </div>
                     </div>

                     {{-- Modal for Report preview --}}
                    <div class="modal"  id="preview">
                        <div class="modal-dialog modal-lg">
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
                                        style="width:100%; height:600px;" 
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

                            $('.send_report_otp').click(function(){
                                var _this=$(this);
                                var id=$(this).attr('data-id');
                                $('#can_id').val(id);
                                // alert("hrllo");
                                var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
                                _this.addClass('disabled-link');
                                if (_this.html() !== loadingText) {
                                    _this.html(loadingText);
                                }
                                $.ajax({
                                    url:"{{ route('/candidates/send_otp') }}",
                                    method:"POST",
                                    data:{"_token": "{{ csrf_token() }}",'_id':id},      
                                    success:function(data)
                                    {
                                        window.setTimeout(function(){
                                            _this.removeClass('disabled-link');
                                            _this.html('Generate Report');
                                        },2000);
                                        console.log(data);
                                        if(data.fail == false)
                                        {
                                            //notify
                                            $('#verify_otp')[0].reset();
                                            $('.otp').removeClass('border-danger');
                                            $('.error-container').html('');
                                            $('.c_name').html(data.data.name);
                                            $('.c_ref_no').html(data.data.ref_no);
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
                                $('.btn_otp').attr('disabled',true);
                                $('.otp').removeClass('border-danger');
                                var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
                                if($('.otp_submit').html()!=loadingText)
                                {
                                    $('.otp_submit').html(loadingText);
                                }
                                $('.error-container').html('');
                                $.ajax({
                                    type: form.attr('method'),
                                    url: url,
                                    data: data,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: function (data) {
                                        // console.log(data);
                                        // $('.error-container').html('');
                                        window.setTimeout(function(){
                                            $('.btn_otp').attr('disabled',false);
                                            $('.otp_submit').html('Submit');
                                        },2000);
                                        if (data.fail && data.error_type == 'validation') {
                                                
                                                //$("#overlay").fadeOut(300);
                                                for (control in data.errors) {
                                                    $('.' + control).addClass('border-danger');
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
                                            // alert('abd');
                                            window.location="{{ url('/') }}"+"/candidate/report-generate/"+candidate_id;
                                            
                                            // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                                            //  location.reload(); 
                                        }
                                    },
                                    error: function (data) {
                                        console.log(data);
                                    }
                                    // error: function (xhr, textStatus, errorThrown) {
                                    //     console.log("Error: " + errorThrown);
                                    //     // alert("Error: " + errorThrown);

                                    // }
                                });
                                event.stopImmediatePropagation();
                                return false;

                            });
                        }); 
                    

                        $('.reportFeedback').click(function(){
                        
                            // var report_id = $(this).attr('data-report');
                            //  var business_id = $(this).attr('data-business');
                            //  var candidate_id = $(this).attr('data-candidate');
                            var username = $(this).attr('data-username');
                            var comments = $(this).attr('data-comment');
                            // var stars = $(this).attr('data-stars');
                            // alert(username);
                            $('#setname').text('Report Feedback'+' '+ '('+ username + ')');
                            $('#setcomment').val(comments);
                            $('#feedback').toggle();
                        });
                
                            $('.close').click(function(){
                                $('#feedback').hide();
                            });
                            $('.back').click(function(){
                                $('#feedback').hide();
                            });

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

                        // Select all check
                        function checkAll(e) {
                            var checkboxes = document.getElementsByName('reports');
                            
                            if (e.checked) {
                                for (var i = 0; i < checkboxes.length; i++) { 
                                checkboxes[i].checked = true;
                                }
                            } else {
                                for (var i = 0; i < checkboxes.length; i++) {
                                checkboxes[i].checked = false;
                                }
                            }
                        }
                        function checkChange(){

                            var totalCheckbox = document.querySelectorAll('input[name="reports"]').length;
                            var totalChecked = document.querySelectorAll('input[name="reports"]:checked').length;

                            // When total options equals to total checked option
                            if(totalCheckbox == totalChecked) {
                            document.getElementsByName("showhide")[0].checked=true;
                            } else {
                            document.getElementsByName("showhide")[0].checked=false;
                            }
                        }

                        function OTPInput() {
                            const inputs = document.querySelectorAll('.otp');
                            // alert(inputs.length);
                            for (let i = 0; i < inputs.length; i++) 
                            { 
                                inputs[i].addEventListener('keyup', function(event) 
                                { 
                                    if (event.key==="Backspace" ) 
                                    { 
                                        inputs[i].value='' ; 
                                        if (i !==0) inputs[i - 1].focus();
                                        
                                    } 
                                    else { 
                                        if (i===inputs.length - 1 && inputs[i].value !=='' ) 
                                        { return true; } 
                                        else if (event.keyCode> 47 && event.keyCode < 58) 
                                        { 
                                            inputs[i].value=event.key; 
                                            if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); 
                                            
                                        } 
                                        else if (event.keyCode> 95 && event.keyCode < 106) 
                                        { 
                                            inputs[i].value=event.key; 
                                            if (i !==inputs.length - 1) 
                                            inputs[i + 1].focus(); event.preventDefault(); 
                                            
                                        }
                                    } 
                                    
                                }); 
                                
                            } 
                            
                        } 
                        OTPInput(); 
                        // end All Check
                    //Interim report export
                      
                $(document).on('click','.interimReportExport',function(){
                    var report_id       = $(this).attr('data-id');
                    var reportType      = $(this).attr('data-type');;
                    var candidate_id    = '';
                        if(reportType !=''){
                            
                                $.ajax(
                                {
                                    url: "{{ url('/') }}"+'/reports/setData/?report_id='+report_id+'&reportType='+reportType+'&candidate_id='+candidate_id,
                                    type: "get",
                                    datatype: "html",
                                })
                                .done(function(data)
                                {
                                //console.log(data);
                                var path = "{{ url('/') }}"+"/candidate/report/pdf/"+report_id+'/'+reportType;
                                    console.log(path);
                                    window.open(path);
                                    //   $('#reportTypeModal').modal('hide');
                                })
                                .fail(function(jqXHR, ajaxOptions, thrownError)
                                {
                                    //alert('No response from server');
                                });
                            
                        }else{
                            alert('Please Check a type to export! ');
                        }
                });


                    </script>