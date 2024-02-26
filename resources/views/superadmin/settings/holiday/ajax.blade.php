<div class="table-responsive">
    <table class="table holidayTable table-bordered">
        <thead class="thead-light">
            <tr>
                {{-- <th>#</th> --}}
                <th>Name</th>
                <th>Date</th>
                <th>Type</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($items)>0)
            @foreach ($items as $key => $item)
                <tr>
                    {{-- <td>{{$key+1}}</td> --}}
                    <td>{{$item->name}}</td>
                    <td>{{date('d-F-Y',strtotime($item->date))}}</td>
                    @if(stripos($item->type,'public')!==false)
                        <td><span class="badge badge-success">Public</span></td>
                    @else
                        <td><span class="badge badge-info">Custom</span></td>
                    @endif
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
                        <button class="btn btn-outline-info btn-md editholidaybtn" data-id="{{base64_encode($item->id)}}" title="Edit" type="button"> <i class='fa fa-edit'></i> </button>
                        @if(stripos($item->type,'custom')!==false)
                            <span><button class="btn btn-outline-danger btn-md deleteBtn" data-id="{{base64_encode($item->id)}}" title="Delete" type="button"> <i class="fas fa-trash-alt"></i> </button></span>
                        @endif
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

 <div class="modal" id="edit_holiday">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Edit Holiday</h4>
             <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('app/settings/holiday/edit')}}" id="holidayupdate">
          @csrf
            <input type="hidden" name="id" id="id">
             <div class="modal-body">
             <div class="form-group">
                <label for="label_name"> Name :</label>
                <input type="text" id="name" name="name" class="form-control name" placeholder="Enter Holiday Name"/>
                <p style="margin-bottom: 2px;" class="text-danger error-container error-name" id="error-name"></p> 
             </div>
                <div class="form-group">
                    <label for="label_name">Date :</label>
                    <input type="text" id="date" name="date" class="form-control date datePicker1" placeholder="Enter date"/>
                    <p style="margin-bottom: 2px;" class="text-danger error-container error-date" id="error-date"></p> 
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-disable">Submit </button>
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
        $('.editholidaybtn').click(function(){
            var id=$(this).attr('data-id');
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#edit_holiday').modal({
                backdrop: 'static',
                keyboard: false
            });
            $.ajax({
                type: 'GET',
                url: "{{ url('/app/settings/holiday/edit') }}",
                data: {'id':id},        
                success: function (data) {
                    console.log(data);
                    $("#holidayupdate")[0].reset();
                    if(data !='null')
                    {              
                        //check if primary data 
                        $('#id').val(id);
                        $('.name').val(data.result.name);

                        var today = new Date(data.result.date);
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = dd + '-' + mm + '-' + yyyy;
                        $('.date').val(today);
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });
        });

        $(document).on('submit', 'form#holidayupdate', function (event) {
        
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

        var year = (new Date).getFullYear();
        $( ".datePicker1" ).datepicker({
            changeMonth: true,
            changeYear: false,
            firstDay: 1,
            autoclose:true,
            todayHighlight: true,
            format: 'dd-mm-yyyy',
            startDate: new Date(year,0,1),
            endDate : new Date(year,11,31)
        });
    });
</script>
     