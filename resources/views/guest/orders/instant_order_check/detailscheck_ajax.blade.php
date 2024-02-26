<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover candidatesTable ">
                <thead>
                    <tr>
                        {{-- <th scope="col">#</th> --}}
                        <th scope="col">Service Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Status</th>
                        <th scope="col">Date & Time</th>
                        {{-- <th scope="col">Status</th> --}}
                        <th class="text-center" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="candidateList">
                    @if(count($items)>0)
                        @foreach ($items as $item)
                            <tr>
                                {{-- <td scope="row"><input class="priority" type="checkbox" name="priority[]" value="{{ $item->id }}"></td> --}}
                                
                                <td>{{stripos($item->name,'Driving')!==false ? 'Driving License' : $item->name}}{{' - '.$item->check_item_number}}</td>
                                <td><i class="fas fa-rupee-sign"></i> {{$item->price}}</td>
                                <td>
                                    @if(stripos($item->status,'success')!==false)
                                        <span class="badge badge-success">Success</span>
                                    @elseif($item->status=='failed')
                                        <span class="badge badge-danger">Failed</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{$item->updated_at!=NULL?date('d-M-Y h:i A',strtotime($item->updated_at)):date('d-M-Y h:i A',strtotime($item->created_at))}}</td>
                                <td class="text-center">
                                    {{-- @if($item->file_name!=NULL) --}}
                                        {{-- <a class="btn btn-outline-info" href="{{url('/verify/instantverification/orders/checkpdf',['id'=>base64_encode($item->id)])}}" target="_blank" title="Download Report"><i class="fas fa-download"></i></a> --}}
                                        <a class="btn btn-outline-info resenddetails" id="resend" href="javascript:void(0)" data-id="{{ base64_encode($item->id)}}" title="Resend report">Resend Report</a>
                                    {{-- @else
                                        <span>--</span>
                                    @endif --}}
                                    <span>
                                        @if(stripos($item->status,'success')!==false)
                                            <button class="btn btn-outline-info detailBtn" data-id="{{base64_encode($item->id)}}" title="Order Details Data"><i class="far fa-eye"></i></button>
                                        @else
                                            @if($item->refund_count>=3)
                                                <button class="btn btn-outline-info detailBtn" data-id="{{base64_encode($item->id)}}" title="Order Details Data"><i class="far fa-eye"></i></button>
                                                <div class="pt-1">
                                                    <span class="badge badge-custom">Refund Request Sent</span>
                                                </div>
                                            @else
                                                <button class="btn btn-outline-info detailBtn" data-id="{{base64_encode($item->id)}}" title="Edit"><i class="far fa-edit"></i></button>
                                            @endif
                                        @endif
                                    </span>
                                </td>
                            <tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td colspan="7">No Data Found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@if(count($items)>0)
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
@endif