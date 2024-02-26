<div class="row">
    <div class="col-md-12">
        
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        {{-- <th>#</th> --}}
                        <th>Check Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody class="">
                    @if(count($billing_details)>0)
                        @foreach ($billing_details as $key =>$item)
                            <tr>
                                {{-- <td>{{$key + 1}}</td> --}}
                                <td>
                                    @if($item->verification_type=='Manual' || $item->verification_type=='manual')
                                        {{$item->service_name}} - {{$item->service_item_number}}
                                    @else
                                        {{$item->service_name}}
                                    @endif
                                </td>
                                <td>{{$item->quantity}}</td>
                                <td><i class="fas fa-rupee-sign"></i> {{$item->price}}</td>
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
          {!! $billing_details->render() !!}
      </div>
    </div>
</div>