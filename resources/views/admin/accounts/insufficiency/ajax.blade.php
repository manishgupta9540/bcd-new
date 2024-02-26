<div class="table-responsive">
    <table class="table insuffTable table-bordered">
        <thead class="thead-light">
            <tr>
                {{-- <th>#</th> --}}
                <th>Customer Name</th>
                <th>No. of Days <small>(frequency)</small></th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($items)>0)
            @foreach ($items as $key => $item)
                <tr>
                    {{-- <td>{{$key+1}}</td> --}}
                    <td>{{Helper::user_name($item->business_id)}} - {{ Helper::company_name($item->business_id)}}</td>
                    <td>{{$item->days}}</td>
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
                        <button class="btn btn-outline-info btn-md editinsuffbtn" data-id="{{base64_encode($item->id)}}" title="Edit" type="button"> <i class='fa fa-edit'></i> </button>
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

 <div class="modal" id="edit_insuff">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
              <div class="row">
                <div class="col-11">
                    <h4 class="modal-title">Edit Insufficiency Control for Customer Wise</h4>
                </div>
                <div class="col-1">
                    <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
                </div>
              </div>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('/settings/insuff_control/edit')}}" id="insuff_update">
          @csrf
            <input type="hidden" name="id" id="id">
             <div class="modal-body">
             <div class="form-group">
                <label for="label_name">Customer Name : <strong class="cust_name"></strong></label>
                <p style="margin-bottom: 2px;" class="text-danger error-container error-name" id="error-name"></p> 
             </div>
                <div class="form-group">
                    <label for="label_name">No of days :</label>
                    <input type="text" id="no_of_days" name="no_of_days" class="form-control no_of_days" placeholder="Enter no_of_days"/>
                    <p style="margin-bottom: 2px;" class="text-danger error-container error-no_of_days" id="error-no_of_days"></p> 
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
 
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
   
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
                url: "{{ url('/settings/insuff_control/edit') }}",
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
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });
        });

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
                        toastr.success("Record Updated Successfully");
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
     