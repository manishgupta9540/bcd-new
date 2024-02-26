<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover candidatesTable order-table">
                <thead>
                    <tr>
                        {{-- <th scope="col">#</th> --}}
                        <th scope="col">Order ID</th>
                        <th scope="col">Candidate Name</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Services</th>
                        <th scope="col">Status</th>
                        <th scope="col">Date & Time</th>
                        {{-- <th scope="col">Status</th> --}}
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="candidateList">
                    @if(count($items)>0)
                        @foreach ($items as $item)
                            <tr>
                                {{-- <td scope="row"><input class="priority" type="checkbox" name="priority[]" value="{{ $item->id }}"></td> --}}
                                <td>
                                  {{-- @if($item->priority == 'normal')
                                    <i class="fa fa-circle normal"></i> 
                                  @elseif($item->priority == 'high')
                                      <i class="fa fa-circle high"></i>
                                  @else
                                      <i class="fa fa-circle low"></i>
                                  @endif --}}
                                  {{ $item->order_id }}
                                </td>
                                <td>{{Helper::user_name($item->candidate_id)}}</td>
                                <td><i class="fas fa-rupee-sign"></i> {{$item->total_price}}</td>
                                <td>{!! Helper::get_service_name_slot($item->services) !!}</td>
                                <td>
                                    @if($item->status=='success')
                                        <span class="badge badge-success">Success</span>
                                    @elseif($item->status=='failed')
                                        <span class="badge badge-danger">Failed</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{$item->updated_at!=NULL?date('d-M-Y h:i A',strtotime($item->updated_at)):date('d-M-Y h:i A',strtotime($item->created_at))}}</td>
                                <td class="text-center">
                                    @if($item->status=='success')
                                        <?php $pdf_file= Helper::get_guest_order_report_pdf($item->id) ?>
                                        @if($pdf_file!=NULL)
                                            <a class="btn btn-outline-primary" href="{{url('/guest/reports/pdf/').'/'.$pdf_file}}" download="{{$pdf_file}}" title="Download Report"><i class="fas fa-download"></i></a>
                                        @else
                                            @if($item->zip_name!=NULL)
                                                <a class="btn btn-outline-primary" href="{{url('/guest/reports/zip/').'/'.$item->zip_name}}" download="{{$item->zip_name}}" title="Download Report"><i class="fas fa-download"></i></a>
                                            @else
                                                <span>--</span>
                                            @endif
                                        @endif
                                    @else
                                        <span>--</span>
                                    @endif
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