<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                {{-- <th>#</th> --}}
                <th>Service Name</th>
                <th>Service Type</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($items)>0)
            @foreach ($items as $key => $item)
                <tr>
                    {{-- <td>{{$key+1}}</td> --}}
                    <td>{{$item->service_name}}</td>
                    @if($item->verification_type=='Auto' || $item->verification_type=='auto')
                        <td><span class="badge badge-success">Auto</span></td>
                    @else
                        <td><span class="badge badge-info">Manual</span></td>
                    @endif
                    <td><i class="fas fa-rupee-sign"></i> {{$item->default_price}}</td>
                    <td><button class="btn btn-outline-info btn-sm editpricebtn" data-id="{{base64_encode($item->check_price_id)}}" data-default_p="{{$item->default_price}}" data-service="{{$item->service_name}}" title="Edit price" type="button"> <i class='fa fa-edit'></i> Edit</button></td>
                </tr> 
            @endforeach
        @else
            <tr class="text-center">
                <td colspan="4">No Data Found</td>
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

 <div class="modal" id="edit_price">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Edit Price</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('app/settings/checkprice/update')}}" id="checkpriceupdate">
          @csrf
            <input type="hidden" name="id" id="id">
             <div class="modal-body">
             <div class="form-group">
                <label for="label_name"> Service Name :</label>
                <span style="margin-bottom: 2px;" class="text-dark service_name" id="service_name"></span> 
             </div>
             <div class="form-group">
                <label for="label_name"> Price :</label>
                <span style="margin-bottom: 2px;" class="text-dark" id="default_pr"></span> 
             </div>
                <div class="form-group">
                      <label for="label_name">New Price :</label>
                      <input type="text" id="price" name="price" class="form-control price" placeholder="Enter Price"/>
                      <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-price"></p> 
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
 
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
   
</div>

<script>
    $(document).ready(function(){
        $('.editpricebtn').click(function(){
            var id=$(this).attr('data-id');
            var default_p=$(this).attr('data-default_p');
            var service_name=$(this).attr('data-service');
            $('#id').val(id);
            $('#default_pr').html('<i class="fas fa-rupee-sign"></i> '+ default_p);
            $('#price').val(default_p);
            $('.service_name').html(service_name);
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('#edit_price').modal({
                backdrop: 'static',
                keyboard: false
            });
        });
        $(document).on('submit', 'form#checkpriceupdate', function (event) {
        
            $("#overlay").fadeIn(300);　
            event.preventDefault();
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var $btn = $(this);
            $('.error-container').html('');
            $('.form-control').removeClass('is-invalid');
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
                            $('input[name='+control+']').addClass('is-invalid');
                            $('#error-' + control).html(data.errors[control]);
                            }
                    } 
                    if (data.fail && data.error == 'yes') {
                        
                        $('#error-all').html(data.message);
                    }
                    if (data.fail == false) {
                        toastr.success("Price Updated Successfully");
                        window.setTimeout(function(){
                            location.reload();
                        },2000);
                        
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    
                    alert("Error: " + errorThrown);

                }
            });
            event.stopImmediatePropagation();
            return false;

        });
    });
</script>
     