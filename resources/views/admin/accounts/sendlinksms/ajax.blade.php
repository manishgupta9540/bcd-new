{{-- <div class="table-responsive"> --}}
    @php
    // $ADD_ACCESS    = false;
    // $VIEW_ACCESS   = false;
    // $EDIT_ACCESS = false;
    // $PDF_ACCESS   = false;
    // $SLA_ACCESS   = false;
    // $ADD_ACCESS    = Helper::can_access('SLA Create','');
    // $VIEW_ACCESS   = Helper::can_access('Verification','');
    // $EDIT_ACCESS = Helper::can_access('Edit Default Check Price','');
    // $PDF_ACCESS = Helper::can_access('SLA PDF download','');
    // $SLA_ACCESS = Helper::can_access('SLA','');

    
    // $REPORT_ACCESS   = false;
    // $VIEW_ACCESS   = false;SLA
    @endphp 
    {{-- @if ($VIEW_ACCESS) --}}
        <table class="table table-bordered customerTable">
        <thead class="thead-light">
            <tr>
                {{-- <th>#</th> --}}
                <th scope="col" style="position:sticky; top:60px">Customer Name</th>
                <th scope="col" style="position:sticky; top:60px">Days</th>
                <th scope="col" style="position:sticky; top:60px">Follow Up</th>
                <th scope="col" style="position:sticky; top:60px">Status</th>
                <th scope="col" style="position:sticky; top:60px" width="10%">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($items)>0)
                @foreach ($items as $key => $item)
                {{--  --}}
                    <tr>
                        {{-- <td>{{$key+1}}</td> --}}
                        <td>{{Helper::user_name($item->business_id)}} - {{ Helper::company_name($item->business_id)}}</td>
                        <td>{{$item->days}}</td>
                        <td>{{$item->days_follow_up}}</td>
                        <td>
                            @if($item->status==0)
                                <span data-dc="{{base64_encode($item->id)}}" class="badge badge-warning">Deactive</span>
                                <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success d-none">Active</span>
                            @else
                                <span data-dc="{{base64_encode($item->id)}}" class="badge badge-warning d-none">Deactive</span>
                                <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success">Active</span>
                            @endif
                        </td>
                        <td>         
                            <a href="javascript:void(0)"> <button class="btn btn-outline-info btn-md mb-1 editinsuffbtn" data-id="{{ base64_encode($item->id) }}" type="button" > <i class="fa fa-edit"> </i> Edit</button> </a>   
                            @if($item->status==1)
                                <span data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-md btn-outline-warning status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('deactive')}}" title="Deactivate"><i class="far fa-times-circle"></i></a></span>
                                <span data-a="{{base64_encode($item->id)}}" class="d-none"><a href="javascript:;" class="btn btn-md btn-outline-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('active')}}" title="Activate"><i class="far fa-check-circle"></i></a></span>
                            @else
                                <span class="d-none" data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-md btn-outline-warning status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('deactive')}}" title="Deactivate"><i class="far fa-times-circle"></i></a></span>
                                <span data-a="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-md btn-outline-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('active')}}"  title="Activate"><i class="far fa-check-circle"></i></a></span>
                            @endif 
                        </td>
                    </tr> 
                @endforeach
            @else
                <tr class="text-center">
                    <td colspan="6">No Data Found</td>
                </tr>
            @endif    
        </tbody>
        </table>
    {{-- </div> --}}
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
{{-- @else
 <span>You have not any permission to access...</span>
@endif --}}

<div class="modal" id="edit_insuff">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
              <div class="row">
                <div class="col-11">
                    <h4 class="modal-title">Send link sms follw candiadte Wise</h4>
                </div>
                <div class="col-1">
                    <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
                </div>
              </div>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('send/link/sms/edit')}}" id="insuff_update">
          @csrf
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="business_id" id="business_id1">
             <div class="modal-body">
             <div class="form-group">
                <label for="label_name">Customer Name : <strong class="cust_name"></strong></label>
                <p style="margin-bottom: 2px;" class="text-danger error-container error-name" id="error-name"></p> 
             </div>
                <div class="form-group">
                   <label for="label_name">No of days :</label>
                   <input type="text" id="no_of_days" name="no_of_days" class="form-control no_of_days" placeholder="Enter no of days"/>
                   <p style="margin-bottom: 2px;" class="text-danger error-container error-no_of_days" id="error-no_of_days"></p> 
                </div>
 
                <div class="form-group">
                    <label for="label_name">No of Follow Up:</label>
                    <input type="text" id="days_follow_up" name="days_follow_up" class="form-control days_follow_up" placeholder="Enter days follow up"/>
                    <p style="margin-bottom: 2px;" class="text-danger error-container error-days_follow_up" id="error-days_follow_up"></p> 
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-disable">Submit </button>
                <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
             </div>
          </form>
       </div>
    </div>
</div>
 <script>
     $(document).ready(function(){
        $('.editinsuffbtn').click(function(){
            var id=$(this).attr('data-id');
            
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#edit_insuff').modal({
                backdrop: 'static',
                keyboard: false
            });
            $.ajax({
                type: 'GET',
                url: "{{ url('/send/link/sms/edit') }}",
                data: {'id':id},        
                success: function (data) {
                    console.log(data);
                    $("#insuff_update")[0].reset();
                    if(data !='null')
                    {              
                        //check if primary data 
                        $('#id').val(id);
                        $('.cust_name').html(data.result.company_name+' - '+data.result.first_name);
                        $('.no_of_days').val(data.result.days);
                        $('#business_id1').val(data.result.business_id);
                        $('.days_follow_up').val(data.result.days_follow_up);
            
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });
         });

         //add 

         $(document).on('submit', 'form#insuff_update', function (event) {
        
            $("#overlay").fadeIn(300);　
            event.preventDefault();
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var $btn = $(this);
            $('.error-container').html('');
            $('.form-control').removeClass('is-invalid');
            $('.btn-disable').attr('disabled',true);
            $.ajax({
                type: form.attr('method'),
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    window.setTimeout(function(){
                        $('.btn-disable').attr('disabled',false);
                    },2000);
                    if (data.fail && data.error_type == 'validation') {
                            
                            //$("#overlay").fadeOut(300);
                            for (control in data.errors) {
                                $('input[name='+control+']').addClass('is-invalid');
                                $('.error-' + control).html(data.errors[control]);
                            }
                    } 
                    if (data.fail && data.error == 'yes') {
                        
                        $('#error-all').html(data.message);
                    }
                    if (data.fail == false) {
                        toastr.success("Record Updeted Successfully");
                        window.setTimeout(function(){
                            location.reload();
                        },2000);
                        
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

    
 </script>