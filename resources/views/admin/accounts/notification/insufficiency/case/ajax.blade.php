<div class="table-responsive">
    <table class="table insuffTable table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Customer Name</th>
                <th>Status</th>
                <th width="20%">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($items)>0)
                @foreach ($items as $key => $item)
                    @php
                        $jaf_notify = Helper::getNotificationControlData($item->business_id,'case-insuff-raise');
                    @endphp
                    <tr>
                        <td>{{Helper::user_name($item->business_id)}} - {{ Helper::company_name($item->business_id)}}</td>
                        <td>
                            @if($jaf_notify!=NULL)
                                @if($jaf_notify->status==1)
                                    <span data-dc="{{base64_encode($item->id)}}" class="badge badge-dark d-none">Disable</span>
                                    <span data-ac="{{base64_encode($item->id)}}" class="badge badge-info">Enable</span>
                                @else
                                    <span data-dc="{{base64_encode($item->id)}}" class="badge badge-dark">Disable</span>
                                    <span data-ac="{{base64_encode($item->id)}}" class="badge badge-info d-none">Enable</span>   
                                @endif
                            @else
                                <span data-dc="{{base64_encode($item->id)}}" class="badge badge-dark">Disable</span>
                                <span data-ac="{{base64_encode($item->id)}}" class="badge badge-info d-none">Enable</span>   
                            @endif
                        </td>
                        <td>
                            @if($jaf_notify!=NULL)
                                @if($jaf_notify->status==1)
                                    <span data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-sm btn-outline-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('deactive')}}" title="Disable"><i class="fas fa-eye-slash"></i> Disable</a></span>
                                    <span data-a="{{base64_encode($item->id)}}" class="d-none"><a href="javascript:;" class="btn btn-sm btn-outline-info status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('active')}}" title="Enable"><i class="fas fa-eye"></i> Enable</a></span>    
                                @else
                                    <span class="d-none" data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-sm btn-outline-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('deactive')}}" title="Disable"><i class="fas fa-eye-slash"></i> Disable</a></span>
                                    <span data-a="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-sm btn-outline-info status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('active')}}"  title="Enable"><i class="fas fa-eye"></i> Enable</a></span>
                                @endif
                            @else
                                <span class="d-none" data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-sm btn-outline-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('deactive')}}" title="Disable"><i class="fas fa-eye-slash"></i> Disable</a></span>
                                <span data-a="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-sm btn-outline-info status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('active')}}"  title="Enable"><i class="fas fa-eye"></i> Enable</a></span>
                            @endif
                            <span><button class="btn btn-sm btn-outline-dark editBtn" data-id="{{base64_encode($item->id)}}"><i class="fa fa-edit"></i> Edit</button></span>
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

 
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
   
</div>

     