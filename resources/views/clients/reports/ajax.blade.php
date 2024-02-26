<div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">

                @php
                // $ADD_ACCESS    = false;
                $REPORT_ACCESS   = false;
                $VIEW_ACCESS   = false;
                $FEEDBACK_ACCESS =false;
                $EXPORT_ACCESS = false;
                // dd($ADD_ACCESS);
                $REPORT_ACCESS    = Helper::can_access('Download Report pdf','/my');//passing action title and route group name
                $VIEW_ACCESS   = Helper::can_access('View Report List','/my');//passing action title and route group name
                $FEEDBACK_ACCESS   = Helper::can_access("Report's Feedback",'/my');//passing action title and route group name
                $EXPORT_ACCESS = Helper::can_access('Export Reports','/my');//passing action title and route group name
                @endphp

                            <table class="table table-bordered table-hover reportTable">
                                <thead>
                                    <tr> 
                                        @if ($EXPORT_ACCESS)
                                        <th scope="col"><input  type="checkbox" name='showhide' onchange="checkAll(this)" ></th>   
                                        @endif
                                        <th scope="col">Name</th>
                                        <th scope="col">Emp Code</th>
                                        <th scope="col">Contacts</th>
                                        <th scope="col">SLA</th>
                                        <th scope="col">Created at</th>
                                        <th scope="col">Case Initiated</th>
                                        <th scope="col">Case Due Date</th>
                                        <th scope="col">Report Generated</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Billing Status</th>
                                        <th scope="col">Rating</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($data) > 0)
                                        @foreach($data as $item)
                                            @php
                                                $candidate_date = date('Y-m-d',strtotime($item->candidate_creation_date));

                                                $date_arr = [];
                                                $tat = $item->client_tat - 1;
                                                $client_tat = $item->client_tat - 1;
                                                $tat_date = 'N/A';
                                                $client_tat_date = 'N/A';

                                                if(stripos($item->days_type,'working')!==false)
                                                {
                                                    $date_arr = Helper::workingDays($candidate_date,$tat,$client_tat);

                                                    // $tat_date = $date_arr['tat_date'];

                                                    $client_tat_date = $date_arr['inc_tat_date'];
                                                }
                                                else if(stripos($item->days_type,'calender')!==false)
                                                {
                                                    $holiday_master=DB::table('customer_holiday_masters')
                                                                        ->distinct('date')
                                                                        ->select('date')
                                                                        ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                                        ->orderBy('date','asc')
                                                                        ->get();

                                                    if(count($holiday_master)>0)
                                                    {
                                                        $date_arr = Helper::calenderDays($candidate_date,$holiday_master,$tat,$client_tat);
                                                    }
                                                    else
                                                    {
                                                        $date_arr = Helper::workingDays($candidate_date,$tat,$client_tat);
                                                    }

                                                    // $tat_date = $date_arr['tat_date'];

                                                    $client_tat_date = $date_arr['inc_tat_date'];
                                                }
                                            @endphp
                                            <tr data-row="{{ $item->id }}">
                                                @if ($EXPORT_ACCESS)
                                                    <th scope="row"><input class="reports" type="checkbox" name="reports" value="{{ $item->id }}" onchange='checkChange();'></th>
                                                @endif
                                                <td class="candidateName">
                                                {{ Helper::get_candidate_name_fullname($item->candidate_id)}} <br>
                                                <small class="text-muted">Ref. No.: <b>{{Helper::get_single_data('candidate_reinitiates','display_id','id',$item->candidate_id) }}
                                                </b>
                                                </small>
                                                </td>
                                                <td>{{$item->client_emp_code!=NULL?$item->client_emp_code:'--'}}</td>
                                                <td > 
                                                    {{-- {{ Helper::get_single_data('users','phone','id',$item->candidate_id) }}  --}}
                                                    {{"+".$item->phone_code."-".str_replace(' ','',$item->phone)}}
                                                </td>
                                                <td >
                                                        {{ $item->title }}  <br>
                                                        <?php $tat=  Helper::get_sla_tat($item->sla_id);?>
                                                        <small class=""><span class="text-danger"> TAT -</span> {{$tat['client_tat']}}</small>
                                                </td>
                                                <td>{{ date('d-m-Y',strtotime($item->created_at) ) }}</td>
                                                <td>{{ date('d-m-Y',strtotime($item->candidate_creation_date) ) }}</td>
                                                <td>
                                                    {{ $client_tat_date!='N/A' ? date('d-m-Y',strtotime($client_tat_date)) : $client_tat_date }}
                                                </td>
                                                <td>
                                                    @if(!(stripos($item->status,'incomplete')!==false))
                                                        {{$item->generated_at!=NULL ?  date('d-m-Y',strtotime($item->generated_at)) : '--'}}
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->status == 'incomplete')
                                                        <span class="badge badge-danger">
                                                            Pending
                                                        </span>
                                                    @else
                                                        @if($item->status=='completed')
                                                            <span class="badge badge-success">
                                                                {{ ucfirst($item->status) }}
                                                            </span>
                                                        @elseif($item->status=='interim')
                                                            <span class="badge badge-warning">
                                                                {{ ucfirst($item->status) }}
                                                            </span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        // Calling the record by model relationship
                                                        $billing_item = App\BillingItem::where('candidate_id',$item->candidate_id)->latest()->first();
                                                    @endphp

                                                    @if($billing_item!=NULL)
                                                        @if(stripos($billing_item->billing->status,'under_review')!==false)
                                                            <span class="badge badge-warning">Under Review</span> 
                                                        @elseif(stripos($billing_item->billing->status,'completed')!==false)
                                                            <span class="badge badge-success">Approved</span>
                                                        @else
                                                            -- 
                                                        @endif
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->status == 'incomplete')
                                                        --
                                                    @else
                                                        @if($FEEDBACK_ACCESS)
                                                            
                                                    
                                                            <?php $review = DB::table('feedback')->where('report_id',$item->id)->first();?>
                                                            {{-- @if (count($reviews)>0) --}}
                                                            {{-- @foreach ($reviews as $review) --}}
                                                            @if ($review)  
                                                            
                                                            
                                                                <?php 
                                                                for ($i = 0; $i < 5; $i++) {
                                                                echo '<i class="fa fa-star' ,
                                                                    ($review->stars == $i + .5 ? '-half' : '') ,
                                                                    ($review->stars <= $i ? '-o' : '') ,
                                                                    '" aria-hidden="true" style="color: green;"></i>';
                                                                
                                                                        }?>
                                                                    
                                                                    <br>
                                                                    <p> <i class="fa fa-info-circle " data-toggle="tooltip" data-original-title="{{ $review->comments ? $review->comments :'N/A' }}"></i> </p>
                                                            
                                                            @else 

                                                        
                                                                <button class="btn btn-sm btn-info reportFeedback" data-report="{{$item->id }}"  data-candidate="{{$item->candidate_id}}" data-business="{{$item->business_id}}" data-username="{{ Helper::user_name($item->candidate_id)}}" type="button">  Feedback</button>


                                                            @endif
                                                        @endif
                                                    @endif
                                                    
                                                
                                                </td>
                                                <td>
                                                    @if($item->status == 'incomplete')
                                                        <span class="badge badge-info">
                                                            In process...
                                                        </span>
                                                    @else
                                                        <!-- <a href="">
                                                            <button class="btn btn-sm btn-info" type="button"> <i class="fa fa-edit"></i> View</button>
                                                        </a> -->
                                                    @endif
                                                    @if($item->status == 'completed' || $item->status == 'interim')
                                                        
                                                
                                                        @if ($REPORT_ACCESS) 
                                                            <button class="btn btn-sm btn-info reportsExportBox mb-1" data-id="{{  base64_encode($item->id) }}" type="button"> <i class="fa fa-download"></i> PDF</button>
                                                        @endif
            
                                                        
                                                        {{-- <button class="btn btn-sm btn-info reportsExportBox" data-id="{{  base64_encode($item->id) }}" type="button"> <i class="fa fa-download"></i> PDF</button> --}}

                                                        <button class="btn btn-sm btn-info reportPreviewBox mb-1" data-id="{{base64_encode($item->id) }}" type="button"> Preview</button>
                                                    
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="12" class="text-center"> <h3>Report is not created yet!</h3> </td></tr>
                                    @endif


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
                              {!! $data->render() !!}
                          </div>
                        </div>
                    </div>


{{-- Modal for Report Feedback --}}
<div class="modal"  id="feedback">
    <div class="modal-dialog " style="max-width: 50% !important;">
        <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="setname"></h4>
                <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
            </div>
            <form method="post" action="{{url('my/candidate/report/feedback')}}" id="feedbackForm">
            @csrf
            <input type="hidden" name="report_id" id="report_id">
            <input type="hidden" name="business_id"  id="business_id">
            <input type="hidden" name="candidate_id"  id="candidate_id" >
                <!-- Modal body -->
            
                <div class="modal-body">
                <h4 class="modal-title">Rating <span class="text-danger">*</span></h4> 
                <fieldset class="rate">
                    <input type="radio" id="rating10" name="rating" value="5" /><label class="stars_1" for="rating10" title="5 stars"></label>
                    <input type="radio" id="rating9" name="rating" value="4.5" /><label class="half stars_1" for="rating9" title="4 1/2 stars"></label>
                    <input type="radio" id="rating8" name="rating" value="4" /><label class="stars_1" for="rating8" title="4 stars"></label>
                    <input type="radio" id="rating7" name="rating" value="3.5" /><label class="half stars_1" for="rating7" title="3 1/2 stars"></label>
                    <input type="radio" id="rating6" name="rating" value="3" /><label  class="stars_1" for="rating6" title="3 stars"></label>
                    <input type="radio" id="rating5" name="rating" value="2.5" /><label class="half stars_1" for="rating5" title="2 1/2 stars"></label>
                    <input type="radio" id="rating4" name="rating" value="2" /><label class="stars_1" for="rating4" title="2 stars"></label>
                    <input type="radio" id="rating3" name="rating" value="1.5" /><label class="half stars_1" for="rating3" title="1 1/2 stars"></label>
                    <input type="radio" id="rating2" name="rating" value="1" /><label class="stars_1" for="rating2" title="1 star"></label>
                    <input type="radio" id="rating1" name="rating" value=".5" /><label class="half stars_1" for="rating1" title="1/2 star"></label>
                    
                </fieldset>
                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-rating"></p>  
                <div class="form-group">
                    <h4 class="modal-title">Comments</h4> 
                            <textarea class="form-control" type="text" name="comments"></textarea>
                </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                
                <button type="submit" class="btn btn-success">Send </button>
                <button type="button" class="btn btn-danger back" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Script -->
<script type="text/javascript">

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
        function checkAll(e) {
            var checkboxes = document.getElementsByName('reports');
            // alert(checkboxes);
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
       

    $(document).ready(function(){
        
        // Report Feedback
        $('.reportFeedback').click(function(){

        var report_id = $(this).attr('data-report');
         var business_id = $(this).attr('data-business');
         var candidate_id = $(this).attr('data-candidate');
            var username = $(this).attr('data-username');
            // alert(username);
         $('#report_id').val(report_id);
         $('#business_id').val(business_id);
         $('#candidate_id').val(candidate_id);
         $('#setname').text('Report Feedback'+' '+ '('+ username + ')');
        $('#feedback').toggle();

            //Form submit with ajax.

        $('.submit').on('click', function() {
            var $this = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            if ($(this).html() !== loadingText) {
            $this.data('original-text', $(this).html());
            $this.html(loadingText);
            }
            setTimeout(function() {
            $this.html($this.data('original-text'));
            }, 5000);
        });
    
       $('#createPasswordBtn').click(function(e) {
            e.preventDefault();
            $("#feedbackForm").submit();
        });
    
    $(document).on('submit', 'form#feedbackForm', function (event) {
       event.preventDefault();
       //clearing the error msg
       $('p.error_container').html("");
    
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
                if(response.success==true  ) {          
                   
                    //notify
                   toastr.success("Feedback submitted successfully");
                    // redirect to google after 5 seconds
                    window.setTimeout(function() {
                        window.location = "{{ url('/')}}"+"/my/reports/";
                    }, 2000);
                  
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
        event.stopImmediatePropagation();
        return false;
    }); 

                
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
            document.getElementById('preview_pdf').src="{{ url('/') }}"+"/my/candidate/report/preview/"+report_id;
           
            $('#preview').toggle();
        });
  
        $('.close').click(function(){
            $('#preview').hide();
        });
        $('.back').click(function(){
            $('#preview').hide();
        });
    });
  </script>