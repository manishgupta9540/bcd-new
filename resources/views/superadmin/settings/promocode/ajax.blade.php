<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table promoTable table-bordered">
                <thead class="thead-light">
                    <tr>
                        {{-- <th>#</th> --}}
                        <th>Code Name</th>
                        <th>Status</th>
                        <th>Expiry Date & Time</th>
                        <th>Total Used</th>
                        <th width="15%">Date & Time</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>
                <tbody class="">
                    @if(count($items)>0)
                        @foreach ($items as $key => $item)
                            <tr>
                                <td>{{$item->title}}</td>
                                <td>
                                    @if($item->is_expired==0)
                                        @if($item->status==0)
                                            <span data-dc="{{base64_encode($item->id)}}" class="badge badge-warning">Deactive</span>
                                            <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success d-none">Active</span>
                                        @else
                                            <span data-dc="{{base64_encode($item->id)}}" class="badge badge-warning d-none">Deactive</span>
                                            <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success">Active</span>
                                        @endif
                                    @else
                                        <span class="badge badge-danger">Expired</span>
                                    @endif
                                </td>
                                <td>
                                    <span>
                                        From - {{$item->start_date!=NULL?date('d-F-Y h:i A',strtotime($item->start_date)):'--'}}
                                    </span><br>
                                    <span>
                                        To - {{$item->end_date!=NULL?date('d-F-Y h:i A',strtotime($item->end_date)):'--'}}
                                    </span>
                                </td>
                                <td>
                                    <span>{{$item->used_limit}} / {{$item->uses_limit}}</span>
                                </td>
                                <td>
                                    {{$item->updated_at!=NULL ? date('d-F-Y',strtotime($item->updated_at)) : date('d-F-Y',strtotime($item->created_at))}}
                                </td>
                                <td>
                                    <span><a href="{{url('/app/settings/promocode/edit',['id'=>base64_encode($item->id)])}}" class="btn btn-outline-info" title="Edit"> <i class="fas fa-edit"></i> </a></span>
                                    <span><a href="javascript:;" class="btn btn-outline-danger deleteBtn" data-id="{{base64_encode($item->id)}}" title="Delete"><i class="fas fa-trash-alt"></i></a></span>
                                    @if($item->is_expired==0)
                                        @if($item->status==1)
                                            <span data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-outline-warning status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('deactive')}}" title="Deactivate"><i class="far fa-times-circle"></i></a></span>
                                            <span data-a="{{base64_encode($item->id)}}" class="d-none"><a href="javascript:;" class="btn btn-outline-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('active')}}" title="Activate"><i class="far fa-check-circle"></i></a></span>
                                        @else
                                            <span class="d-none" data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-outline-warning status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('deactive')}}" title="Deactivate"><i class="far fa-times-circle"></i></a></span>
                                            <span data-a="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-outline-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('active')}}"  title="Activate"><i class="far fa-check-circle"></i></a></span>
                                        @endif
                                    @endif
                                </td>
                            </tr> 
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td colspan="6">No data found</td>
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