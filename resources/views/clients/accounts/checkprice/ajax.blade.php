<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                {{-- <th>#</th> --}}
                <th>Service Name</th>
                <th>Service Type</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @php
                $hide = Helper::check_price_show(Auth::user()->business_id); 
            @endphp
            @if(count($items)>0 && $hide==NULL)
                @foreach ($items as $key => $item)
                    @if($item->business_id==NULL || $item->business_id==Auth::user()->parent_id)
                        <tr>
                            {{-- <td>{{$key+1}}</td> --}}
                            <td>{{$item->name}}</td>
                            @if($item->verification_type=='Auto' || $item->verification_type=='auto')
                                <td><span class="badge badge-success">Auto</span></td>
                            @else
                                <td><span class="badge badge-info">Manual</span></td>
                            @endif
                            <?php $price=Helper::get_check_coc_price($item->id);?>
                            <td>
                                @if($price!=NULL)
                                    <i class="fas fa-rupee-sign"></i> {{$price}}
                                @else
                                    --
                                @endif
                            </td>
                        </tr>
                    @endif 
                @endforeach
            @else
                <tr class="text-center">
                    <td colspan="4">No Data Found</td>
                </tr>
            @endif    
        </tbody>
    </table>
</div>
@if(count($items)>0 && $hide==NULL)
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