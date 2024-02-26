{{-- <div class="table-responsive"> --}}
    @php
    // $ADD_ACCESS    = false;
    $VIEW_ACCESS   = false;
    $EDIT_ACCESS = false;
    // $PDF_ACCESS   = false;
    // $SLA_ACCESS   = false;
    // $ADD_ACCESS    = Helper::can_access('Add COC Check Price','');
    $VIEW_ACCESS   = Helper::can_access('View Billing Config','');
    $EDIT_ACCESS = Helper::can_access('Edit Billing Config','');
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
          {{-- <th scope="col" style="position:sticky; top:60px" width="15%">Price</th> --}}
          <th scope="col" style="position:sticky; top:60px">Incentive</th>
          <th scope="col" style="position:sticky; top:60px">Penalty</th>
          <th scope="col" style="position:sticky; top:60px" width="10%">Action</th>
       </tr>
    </thead>
    <tbody>
       @if(count($items)>0)
          @foreach ($items as $key => $item)
                {{-- <?php $check_price=Helper::get_check_coc_wise_price($item->service_id,$item->coc_id,Auth::user()->business_id);?> --}}
                <tr>
                   {{-- <td>{{$key+1}}</td> --}}
                   <td>{{ Helper::company_name($item->coc_id)}} - {{Helper::user_name($item->coc_id)}}</td>
                   <td>{{$item->service_name}}</td>
                   @if($item->verification_type=='Auto' || $item->verification_type=='auto')
                      <td><span class="badge badge-success">Auto</span></td>
                   @else
                      <td><span class="badge badge-info">Manual</span></td>
                   @endif
                   {{-- @if($check_price!=NULL)
                        <td><i class="fas fa-rupee-sign"></i> {{$check_price}}</td>
                   @else
                        <td>--</td>
                   @endif --}}
                   <td>{{$item->incentive}}%</td>
                   <td>{{$item->penalty}}%</td>
                   <td>
                      @if($EDIT_ACCESS)
                      <button class="btn btn-outline-info btn-sm editcustomincbtn" data-id="{{base64_encode($item->id)}}" data-customer="{{Helper::user_name($item->coc_id)}} - {{ Helper::company_name($item->coc_id)}}" data-service="{{$item->service_name}}" data-incentive="{{$item->incentive}}" data-penalty="{{$item->penalty}}" title="Edit Custom Incentive & Penalty" type="button"> <i class='fa fa-edit'></i> Edit</button>  
                      @endif
                   </td>
                </tr> 
          @endforeach
       @else
          <tr class="text-center">
            <td colspan="8">No Data Found</td>
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
<div class="modal" id="edit_custom_inc">
  <div class="modal-dialog">
     <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
           <h4 class="modal-title">Edit Customer Incentive & Penalty</h4>
           <button type="button" class="close btn_check" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
        </div>
        <!-- Modal body -->
        <form method="post" action="{{url('/billing/config/cocwise/update')}}" id="checkincupdate">
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
           {{-- <div class="form-group">
                <label for="label_name"> Price :</label>
                <span style="margin-bottom: 2px;" class="text-dark" id="price"></span> 
            </div> --}}
          <div class="form-group">
                <label for="label_name">Incentive <small>(in %)</small> : <span class="text-danger">*</span></label>
                <input type="text" id="incentive" name="incentive" class="form-control incentive" placeholder="Enter Incentive"/>
                <p style="margin-bottom: 2px;" class="text-danger error-container error-incentive" id="error-incentive"></p> 
          </div>
          <div class="form-group">
                <label for="label_name">Penalty <small>(in %)</small> : <span class="text-danger">*</span></label>
                <input type="text" id="penalty" name="penalty" class="form-control penalty" placeholder="Enter Penalty"/>
                <p style="margin-bottom: 2px;" class="text-danger error-container error-penalty" id="error-penalty"></p> 
            </div>
           </div>
           <!-- Modal footer -->
           <div class="modal-footer">
              <button type="submit" class="btn btn-info btn_check btn_submit">Update </button>
              <button type="button" class="btn btn-danger btn_check" data-dismiss="modal">Close</button>
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
      $('.editcustomincbtn').click(function(){
          var id=$(this).attr('data-id');
          var cust_name=$(this).attr('data-customer');
         //  var price=$(this).attr('data-price');
          var service_name=$(this).attr('data-service');
          var incentive=$(this).attr('data-incentive');
          var penalty=$(this).attr('data-penalty');
          $('#checkincupdate')[0].reset();
          $('#id').val(id);
          $('#cust_name').html(cust_name);
         //  $('#price').html('<i class="fas fa-rupee-sign"></i> '+price);
          $('#service_n').html(service_name);
          $('#incentive').val(incentive);
          $('#penalty').val(penalty);
          $('.btn_check').attr('disabled',false);
          $('.btn_submit').html('Update');
          $('.form-control').removeClass('is-invalid');
          $('.error-container').html('');
          $('#edit_custom_inc').modal({
              backdrop: 'static',
              keyboard: false
          });
      });
      $(document).on('submit', 'form#checkincupdate', function (event) {
      
          $("#overlay").fadeIn(300);　
          event.preventDefault();
          var form = $(this);
          var data = new FormData($(this)[0]);
          var url = form.attr("action");
          var $btn = $(this);
          var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
          $('.form-control').removeClass('is-invalid');
          $('.error-container').html('');
          $('.btn_check').attr('disabled',true);
          if ($('.btn_submit').html() !== loadingText) {
            $('.btn_submit').html(loadingText);
          }
          $.ajax({
              type: form.attr('method'),
              url: url,
              data: data,
              cache: false,
              contentType: false,
              processData: false,
              success: function (data) {
                  console.log(data);
                //   $('.error-container').html('');

                window.setTimeout(function(){
                    $('.btn_check').attr('disabled',false);
                    $('.btn_submit').html('Update');
                  },2000);
                  if (data.fail && data.error_type == 'validation') {
                          
                        //$("#overlay").fadeOut(300);
                        for (control in data.errors) {
                        $('input[name=' + control + ']').addClass('is-invalid');
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
              error: function (data) {
                  
                console.log(data);

              }
          });
          event.stopImmediatePropagation();
          return false;

      });
  });
</script>