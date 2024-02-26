{{-- <div class="table-responsive"> --}}
   @php
      // $ADD_ACCESS    = false;
      $VIEW_ACCESS   = false;
      $EDIT_ACCESS = false;
      // $PDF_ACCESS   = false;
      // $SLA_ACCESS   = false;
      // $ADD_ACCESS    = Helper::can_access('Add COC Check Price','');
      $VIEW_ACCESS   = Helper::can_access('COC Check Price','');
      $EDIT_ACCESS = Helper::can_access('Edit COC Check Price','');
      // $PDF_ACCESS = Helper::can_access('SLA PDF download','');
      // $SLA_ACCESS = Helper::can_access('SLA','');
   @endphp
   @if ($VIEW_ACCESS)
       
  
   <table class="table table-bordered">
      <thead class="thead-light">
         <tr>
            {{-- <th>#</th> --}}
            <th scope="col" style="position:sticky; top:60px">Customer Name</th>
            <th scope="col" style="position:sticky; top:60px">Service Name</th>
            <th scope="col" style="position:sticky; top:60px">Service Type</th>
            <th scope="col" style="position:sticky; top:60px">Price</th>
            <th scope="col" style="position:sticky; top:60px" width="10%">Action</th>
         </tr>
      </thead>
      <tbody>
         @if(count($items)>0)
            @foreach ($items as $key => $item)
                  <tr>
                     {{-- <td>{{$key+1}}</td> --}}
                     <td>{{ Helper::company_name($item->coc_id)}} ({{Helper::user_name($item->coc_id)}})</td>
                     <td>{{$item->service_name}}</td>
                     @if($item->verification_type=='Auto' || $item->verification_type=='auto')
                        <td><span class="badge badge-success">Auto</span></td>
                     @else
                        <td><span class="badge badge-info">Manual</span></td>
                     @endif
                     <td><i class="fas fa-rupee-sign"></i> {{$item->price}}</td>
                     <td>
                        @if($EDIT_ACCESS)
                        <button class="btn btn-outline-info btn-sm editcustompricebtn" data-id="{{base64_encode($item->id)}}" data-price="{{$item->price}}" data-customer="{{Helper::user_name($item->coc_id)}} - {{ Helper::company_name($item->coc_id)}}" data-service="{{$item->service_name}}" title="Edit Custom price" type="button"> <i class='fa fa-edit'></i> Edit</button>  
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
@else
   <span>You have not any permission to action...</span>     
@endif
<div class="modal" id="edit_custom_price">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Edit Customer Price</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('/checkprice/customer_wise/update')}}" id="checkpriceupdate">
          @csrf
            <input type="hidden" name="id" id="id">
             <div class="modal-body">
             {{-- <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p>  --}}
             <div class="form-group">
                <label for="label_name"> Customer Name :</label>
                <span style="margin-bottom: 2px;" class="text-dark" id="cust_name"></span> 
             </div>
             <div class="form-group">
                <label for="label_name"> Service Name :</label>
                <span style="margin-bottom: 2px;" class="text-dark" id="service_n"></span> 
             </div>
            <div class="form-group">
                    <label for="label_name">Price </label>
                    <input type="text" id="price" name="price" class="form-control price" placeholder="Enter Price"/>
                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-price"></p> 
            </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-info">Submit </button>
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
        $('.editcustompricebtn').click(function(){
            var id=$(this).attr('data-id');
            var cust_name=$(this).attr('data-customer');
            var price=$(this).attr('data-price');
            var service_name=$(this).attr('data-service');
            $('#id').val(id);
            $('#cust_name').html(cust_name);
            $('#price').val(price);
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