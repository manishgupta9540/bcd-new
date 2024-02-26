@php
    $EDIT_ACCESS = false;
    $EDIT_ACCESS = Helper::can_access('Edit Billing ','');
@endphp
<div class="row">
    <div class="col-md-12">
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" width="5%">#</th>
                        <th width="25%">Candidate</th>
                        <th width="20%">SLA</th>
                        <th width="20%">Total Checks</th>
                        <th width="20%">Total Additional Charges</th>
                        <th width="20%">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($billing_detail_candidate)>0 || count($billing_detail_api)>0)
                        <?php $i=0;?>
                        @if(count($billing_detail_candidate)>0)
                            @foreach ($billing_detail_candidate as $key => $c_item)
                                <tr>
                                    <td>
                                        <a data-toggle="collapse" data-target="#demo{{$i}}" class="accordion-toggle btn btn-link text-info" href="javascript:;" style="font-size: 14px;">
                                            <i class="fas fa-angle-double-down"></i>
                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="{{route('/candidates/show',['id'=>base64_encode($c_item->candidate_id)])}}">
                                            {{Helper::user_name($c_item->candidate_id)}}<br>
                                        </a>
                                        <small class="text-muted">Ref. No. <b>{{Helper::user_reference_id($c_item->candidate_id)}}</b></small>
                                    </td>
                                    <td>
                                        <?php 
                                            $sla_item = DB::table('job_items as j')
                                                            ->select('j.*','c.title','c.parent_id')
                                                            ->join('customer_sla as c','c.id','=','j.sla_id')
                                                            ->where(['j.candidate_id'=>$c_item->candidate_id])
                                                            ->first();
                                        ?>
                                        @if($sla_item!=NULL)
                                            @if($sla_item->parent_id==0)
                                                {{$sla_item->title}}
                                            @else
                                                <a href="{{ url('/settings/sla/edit',['id'=>base64_encode($sla_item->sla_id)]) }}">
                                                    {{$sla_item->title}}
                                                </a>
                                            @endif
                                            <br>
                                            <small class="text-muted mt-2">TAT Type :<b>{{ucwords($sla_item->tat_type)}} - Wise</b></small><br>
                                            <small class="text-muted mt-2">Price Type :<b>{{ucwords($sla_item->price_type)}} - Wise</b></small>
                                        @else
                                            --
                                        @endif                                
                                    </td>
                                    <td>
                                        {{$c_item->total_quantity}}
                                    </td>
                                    <td>
                                        <i class="fas fa-rupee-sign"></i> {{$c_item->total_additional_charges}}
                                    </td>
                                    <td>
                                        <i class="fas fa-rupee-sign"></i> {{$c_item->total_check_price}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="hiddenRow collapse" colspan="7" id="demo{{$i}}">
                                        <div class="accordian-body p-1">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4 class="card-title mb-1 mt-1">Item Details</h4>
                                                    <p class="pb-border"> </p>
                                                </div>
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>Check Name</th>
                                                                <th>Quantity</th>
                                                                <th width="10%">Price</th>
                                                                <th>Incentive</th>
                                                                <th>Penalty</th>
                                                                <th>Additional Charges</th>
                                                                <th width="10%">Total Price</th>
                                                                @if(stripos($billing->status,'draft')!==false)
                                                                    <th>Action</th>
                                                                @elseif(stripos($billing->status,'under_review')!==false)
                                                                    <?php
                                                                        $billing_approval = NULL;
                                                                        $billing_approval=Helper::billingApproval($billing->id);
                                                                    ?>
                                                                    @if($billing_approval!=NULL)
                                                                        @if($billing_approval->request_approve_by_coc_id==NULL)
                                                                            <th>Action</th>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    <th>Action</th>
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $billing_details = DB::table('billing_items as bi')
                                                                                    ->select('bi.*','s.verification_type')
                                                                                    ->join('services as s','s.id','=','bi.service_id')
                                                                                    ->where(['bi.billing_id'=>$billing->id,'bi.candidate_id'=>$c_item->candidate_id])
                                                                                    ->whereNotNull('bi.candidate_id')
                                                                                    ->get();
                                                            ?>
                                                            @if(count($billing_details)>0)
                                                                @foreach ($billing_details as $item)
                                                                    <tr>
                                                                    
                                                                        <td>
                                                                            @if(stripos($item->verification_type,'Manual')!==false)
                                                                                {{$item->service_name}} - {{$item->service_item_number}}<br>
                                                                            @else
                                                                                {{$item->service_name}}
                                                                            @endif
                                                                        </td>
                                                                        <td>{{$item->quantity}}</td>
                                                                        <td><i class="fas fa-rupee-sign"></i> {{$item->price}}</td>
                                                                        <td>{{$item->incentive}} %</td>
                                                                        <td>{{$item->penalty}} %</td>
                                                                        <td><i class="fas fa-rupee-sign"></i> {{$item->additional_charges}}</td>
                                                                        <td>
                                                                            <i class="fas fa-rupee-sign"></i> {{$item->final_total_check_price}}
                                                                        </td>
                                                                        @if(stripos($billing->status,'draft')!==false)
                                                                            <td>
                                                                                @if ($EDIT_ACCESS)
                                                                                    <button class="btn btn-outline-info editBtn" data-id="{{base64_encode($item->id)}}" title="Edit"><i class="far fa-edit"></i></button>
                                                                                @endif
                                                                            </td>
                                                                        @elseif(stripos($billing->status,'under_review')!==false)
                                                                            <?php
                                                                                $billing_approval = NULL;
                                                                                $billing_approval=Helper::billingApproval($billing->id);
                                                                            ?>
                                                                            @if($billing_approval!=NULL)
                                                                                @if($billing_approval->request_approve_by_coc_id==NULL)
                                                                                    <td>
                                                                                        @if ($EDIT_ACCESS)
                                                                                            <button class="btn btn-outline-info editBtn" data-id="{{base64_encode($item->id)}}" title="Edit"><i class="far fa-edit"></i></button>
                                                                                        @endif                                            
                                                                                    </td>
                                                                                @endif
                                                                            @endif
                                                                        @else
                                                                            @if($item->additional_charge_notes!=NULL)
                                                                                <td>
                                                                                    <button class="btn btn-outline-info detailBtn" data-id="{{base64_encode($item->id)}}" title="Details"><i class="far fa-eye"></i></button>
                                                                                </td>
                                                                            @else
                                                                                <td class="text-center">
                                                                                    --
                                                                                </td>
                                                                            @endif
                                                                        @endif
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php $i++;?>
                            @endforeach
                        @endif
                        @if(count($billing_detail_api)>0)
                            @foreach ($billing_detail_api as $a_item)
                                <tr>
                                    <td>
                                        <a data-toggle="collapse" data-target="#demo{{$i}}" class="accordion-toggle btn btn-link text-info" href="javascript:;" style="font-size: 14px;">
                                            <i class="fas fa-angle-double-down"></i>
                                        </a>
                                    </td>
                                    <td>
                                        --
                                    </td>
                                    <td>
                                        --
                                    </td>
                                    <td>
                                        {{$a_item->total_quantity}}
                                    </td>
                                    <td>
                                        <i class="fas fa-rupee-sign"></i> {{$a_item->total_additional_charges}}
                                    </td>
                                    <td>
                                        <i class="fas fa-rupee-sign"></i> {{$a_item->total_check_price}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="hiddenRow collapse" colspan="7" id="demo{{$i}}">
                                        <div class="accordian-body p-1">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="card-title mb-1 mt-1">Item Details</h4>
                                                <p class="pb-border"> </p>
                                            </div>
                                            <div class="col-sm-12">
                                                <table class="table table-bordered">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th width="30%">Check Name</th>
                                                            <th>Quantity</th>
                                                            <th width="10%">Price</th>
                                                            <th>Incentive</th>
                                                            <th>Penalty</th>
                                                            <th>Additional Charges</th>
                                                            <th width="10%">Total Price</th>
                                                            @if(stripos($billing->status,'draft')!==false)
                                                                <th>Action</th>
                                                            @elseif(stripos($billing->status,'under_review')!==false)
                                                                <?php
                                                                    $billing_approval = NULL;
                                                                    $billing_approval=Helper::billingApproval($billing->id);
                                                                ?>
                                                                @if($billing_approval!=NULL)
                                                                    @if($billing_approval->request_approve_by_coc_id==NULL)
                                                                        <th>Action</th>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                <th>Action</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $billing_details = DB::table('billing_items as bi')
                                                                                ->select('bi.*','s.verification_type')
                                                                                ->join('services as s','s.id','=','bi.service_id')
                                                                                ->where(['bi.billing_id'=>$billing->id])
                                                                                ->whereNull('bi.candidate_id')
                                                                                ->get();
                                                        ?>
                                                        @if(count($billing_details)>0)
                                                            @foreach ($billing_details as $item)
                                                                    <?php
                                                                        $service_data=$item->service_data;
                                                                        $content=''; 
                                                                        if($service_data!=NULL)
                                                                        {
                                                                            $service_data_array=json_decode($service_data,true);

                                                                            //Get Array Last Key
                                                                            $key_v=key(array_slice($service_data_array, -1, 1, true));
                                                                            foreach($service_data_array as $key => $value)
                                                                            {
                                                                                $content.='<small class="text-muted">';
                                                                                if($key_v!=$key)
                                                                                    $content.=$key.' '.':'.' <b>'.$value.'</b>, ';
                                                                                else
                                                                                    $content.=$key.' '.':'.' <b>'.$value.'</b>';

                                                                                $content.='</small>';
                                                                            }
                                                                        }
                                                                    ?> 
                                                                <tr>
                                                                    <td>
                                                                        @if(stripos($item->verification_type,'Manual')!==false)
                                                                            {{$item->service_name}} - {{$item->service_item_number}}<br>
                                                                            {!!$content!!}
                                                                        @else
                                                                            {{$item->service_name}}<br>
                                                                            {!!$content!!}
                                                                        @endif
                                                                    </td>
                                                                    <td>{{$item->quantity}}</td>
                                                                    <td><i class="fas fa-rupee-sign"></i> {{$item->price}}</td>
                                                                    <td>{{$item->incentive}} %</td>
                                                                    <td>{{$item->penalty}} %</td>
                                                                    <td><i class="fas fa-rupee-sign"></i> {{$item->additional_charges}}</td>
                                                                    <td>
                                                                        <i class="fas fa-rupee-sign"></i> {{$item->final_total_check_price}}
                                                                    </td>
                                                                    @if(stripos($billing->status,'draft')!==false)
                                                                        <td>
                                                                            @if ($EDIT_ACCESS)
                                                                                <button class="btn btn-outline-info editBtn" data-id="{{base64_encode($item->id)}}" title="Edit"><i class="far fa-edit"></i></button>
                                                                            @endif
                                                                        </td>
                                                                    @elseif(stripos($billing->status,'under_review')!==false)
                                                                        <?php
                                                                            $billing_approval = NULL;
                                                                            $billing_approval=Helper::billingApproval($billing->id);
                                                                        ?>
                                                                        @if($billing_approval!=NULL)
                                                                            @if($billing_approval->request_approve_by_coc_id==NULL)
                                                                                <td>
                                                                                    @if ($EDIT_ACCESS)
                                                                                        <button class="btn btn-outline-info editBtn" data-id="{{base64_encode($item->id)}}" title="Edit"><i class="far fa-edit"></i></button>
                                                                                    @endif                                            
                                                                                </td>
                                                                            @endif
                                                                        @endif
                                                                    @else
                                                                        @if($item->additional_charge_notes!=NULL)
                                                                            <td>
                                                                                <button class="btn btn-outline-info detailBtn" data-id="{{base64_encode($item->id)}}" title="Details"><i class="far fa-eye"></i></button>
                                                                            </td>
                                                                        @else
                                                                            <td class="text-center">
                                                                                --
                                                                            </td>
                                                                        @endif
                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                        @endif
                    @else
                        <tr>
                            <td colspan="7">No Data Found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    {{-- <div class="flex-grow-1"></div> --}}
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

<div class="modal" id="edit_total_check_price">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Edit Total Price</h4>
             <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('/billing/details_edit')}}" id="total_check_price_update" enctype="multipart/form-data">
          @csrf
            <input type="hidden" name="id" id="id">
             <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="add_details">
                            
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name">Service Name :</label>
                            <span class="s_name" id="s_name"></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="label_name">Base Price :</label>
                            <span class="base_check_price" id="base_check_price"></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="label_name">Total Price :</label>
                            <span class="total_check_price" id="total_check_price"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name">Additional Charges: <small>(in <i class="fas fa-rupee-sign"></i>)</small></label>
                            <input type="text" id="additional_charges" name="additional_charges" class="form-control additional_charges" placeholder="Enter Additional Charges"/>
                            <p style="margin-bottom: 2px;" class="text-danger error-container error-additional_charges" id="error-additional_charges"></p> 
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Comments </label>
                            <textarea id="comment" name="comments" class="form-control comments" placeholder=""></textarea>
                            {{-- <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/> --}}
                            <p style="margin-bottom: 2px;" class="text-danger error-container error-comments" id="error-comments"></p> 
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="attach_data">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Attachments: <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,pdf are accepted "></i></label>
                            <input type="file" name="attachment[]" id="attachment" multiple class="form-control attachment">
                            <p style="margin-bottom: 2px;" class="text-danger error-container error-attachment" id="error-attachment"></p>  
                        </div>
                    </div>
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

<div class="modal" id="add_details">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Additional Charge Details</h4>
             <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
            <input type="hidden" name="id" id="id">
             <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="add_details">
                            
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name">Service Name :</label>
                            <span class="s_name" id="s_name"></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="label_name">Base Price :</label>
                            <span class="base_check_price" id="base_check_price"></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="label_name">Total Price :</label>
                            <span class="total_check_price" id="total_check_price"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name">Additional Charges : </label>
                            <span class="additional_charges" id="additional_charges"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Comments: </label>
                            <span class="comments" id="comments"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="attach_data">
                        </div>
                    </div>
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
             </div>
       </div>
    </div>
</div>

<script>
    $(document).ready(function(){

        $('[data-toggle="tooltip"]').tooltip();

        $('.editBtn').click(function(){
            var id=$(this).attr('data-id');
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#edit_total_check_price').modal({
                backdrop: 'static',
                keyboard: false
            });
            $.ajax({
                type: 'GET',
                url: "{{ url('/billing/details_edit') }}",
                data: {'id':id},        
                success: function (data) {
                    console.log(data);
                    $("#total_check_price_update")[0].reset();
                    if(data !='null')
                    { 
                        // alert(data.result.additional_charge_notes);
                        //check if primary data 
                        $('#id').val(id);
                        $('.base_check_price').html('<i class="fas fa-rupee-sign"></i> '+data.result.total_check_price);
                        $('.total_check_price').html('<i class="fas fa-rupee-sign"></i> '+data.result.final_total_check_price);
                        $('.additional_charges').val(data.result.additional_charges);
                        $('.comments').text(data.result.additional_charge_notes);
                        $('.attach_data').html(data.form);
                        $('.add_details').html(data.add_detail);

                        if(data.result.verification_type.toLowerCase()=='Manual'.toLowerCase())
                            $('.s_name').html(data.result.service_name+' - '+data.result.service_item_number);
                        else
                            $('.s_name').html(data.result.service_name);
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });
        });

        $('.detailBtn').click(function(){
            var id=$(this).attr('data-id');

            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#add_details').modal({
                backdrop: 'static',
                keyboard: false
            });
            $.ajax({
                type: 'GET',
                url: "{{ url('/billing/details_edit') }}",
                data: {'id':id},        
                success: function (data) {
                    
                    if(data !='null')
                    { 
                        // alert(data.result.additional_charge_notes);
                        //check if primary data 
                        $('.base_check_price').html('<i class="fas fa-rupee-sign"></i> '+data.result.total_check_price);
                        $('.total_check_price').html('<i class="fas fa-rupee-sign"></i> '+data.result.final_total_check_price);
                        $('.additional_charges').html('<i class="fas fa-rupee-sign"></i> '+data.result.additional_charges);
                        $('.comments').text(data.result.additional_charge_notes);
                        $('.attach_data').html(data.form);
                        $('.add_details').html(data.add_detail);
                        
                        if(data.result.verification_type.toLowerCase()=='Manual'.toLowerCase())
                            $('.s_name').html(data.result.service_name+' - '+data.result.service_item_number);
                        else
                            $('.s_name').html(data.result.service_name);
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });

        });

        $(document).on('submit', 'form#total_check_price_update', function (event) {
        
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
                                $('textarea[name='+control+']').addClass('is-invalid');
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
