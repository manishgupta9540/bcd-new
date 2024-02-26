{{-- <div class="table-responsive"> --}}

    @php
    // $ADD_ACCESS    = false;
    $VIEW_ACCESS   = false;
    $EDIT_ACCESS = false;
    // $PDF_ACCESS   = false;
    // $SLA_ACCESS   = false;
    // $ADD_ACCESS    = Helper::can_access('SLA Create','');
    $VIEW_ACCESS   = Helper::can_access('Default Check Price','');
    $EDIT_ACCESS = Helper::can_access('Edit Default Check Price','');
    // $PDF_ACCESS = Helper::can_access('SLA PDF download','');
    // $SLA_ACCESS = Helper::can_access('SLA','');

    
    // $REPORT_ACCESS   = false;
    // $VIEW_ACCESS   = false;SLA
    @endphp 
    @if ($VIEW_ACCESS)
        
    
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                {{-- <th>#</th> --}}
                <th scope="col" style="position:sticky; top:60px">Service Name</th>
                <th scope="col" style="position:sticky; top:60px">Service Type</th>
                <th scope="col" style="position:sticky; top:60px">Default Price <small>(BCD Price)</small></th>
                <th scope="col" style="position:sticky; top:60px">Customer Price</th>
                <th scope="col" style="position:sticky; top:60px">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($items)>0)
                @foreach ($items as $key => $item)
                    <?php $check_price_coc=Helper::get_check_price_coc_global_data(Auth::user()->business_id,$item->service_id)?>
                    <?php $check_price_master=Helper::get_check_price_master_admin_data($parent_id,Auth::user()->business_id,$item->service_id)?>
                    @if($item->business_id==NULL || $item->business_id==Auth::user()->business_id)
                        <tr>
                            {{-- <td>{{$key+1}}</td> --}}
                            <td>{{$item->service_name}}</td>
                            @if($item->verification_type=='Auto' || $item->verification_type=='auto')
                                <td><span class="badge badge-success">Auto</span></td>
                            @else
                                <td><span class="badge badge-info">Manual</span></td>
                            @endif
                            <td>
                                @if($check_price_master!=NULL)
                                    <i class="fas fa-rupee-sign"></i> {{$check_price_master->price}}
                                @else
                                    <span class="">--</span> 
                                @endif
                            </td>
                            <td>
                                @if($check_price_coc!=NULL)
                                    <i class="fas fa-rupee-sign"></i> {{$check_price_coc->price}}
                                @else
                                    <span class="">--</span>
                                @endif
                            </td>
                            <td>
                                @if ($EDIT_ACCESS)
                                    
                                    @if($check_price_coc!=NULL)
                                        <button class="btn btn-outline-info btn-sm editcustompricebtn" data-id="{{base64_encode($check_price_coc->id)}}" data-default_p="{{$check_price_master!=NULL ? $check_price_master->price : '--'}}" data-price="{{$check_price_coc->price}}" data-service="{{$item->service_name}}" title="Edit Custom price" type="button"> <i class='fa fa-edit'></i> Edit</button>
                                    @else
                                        <span class="">--</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endif 
                @endforeach
            @else
                <tr class="text-center">
                    <td colspan="5">No Data Found</td>
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
 @else
      <span>You have not any permission to action...</span>  
 @endif
<div class="modal" id="edit_custom_price">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Edit Custom Price</h4>
             <button type="button" class="close close_btn" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('/checkprice/update')}}" id="checkpriceupdate">
          @csrf
            <input type="hidden" name="id" id="id">
             <div class="modal-body">
             {{-- <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p>  --}}
             <div class="form-group">
                <label for="label_name"> Service Name :</label>
                <span style="margin-bottom: 2px;" class="text-dark" id="service_n"></span> 
             </div>
             <div class="form-group">
                <label for="label_name"> Default Price :</label>
                <span style="margin-bottom: 2px;" class="text-dark" id="default_pr"></span> 
             </div>
                <div class="form-group">
                      <label for="label_name"> Price </label>
                      <input type="text" id="price" name="price" class="form-control price" placeholder="Enter Price"/>
                      <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-price"></p> 
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-info">Submit </button>
                <button type="button" class="btn btn-danger close_btn" data-dismiss="modal">Close</button>
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
        $('.editcustompricebtn').click(function(){
            var id=$(this).attr('data-id');
            var price=$(this).attr('data-price');
            var default_p=$(this).attr('data-default_p');
            var service_name=$(this).attr('data-service');
            $('#id').val(id);
            $('#price').val(price);
            $('#default_pr').html('<i class="fas fa-rupee-sign"></i> '+ default_p);
            $('#service_n').html(service_name);
            $('#edit_custom_price').modal({
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
                            $('input[price=' + control + ']').addClass('is-invalid');
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
    