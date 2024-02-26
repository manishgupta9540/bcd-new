<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        {{-- <th>#</th> --}}
                        <th>Customer Name</th>
                        <th>Duration</th>
                        <th>Description</th>
                        <th width="15%">Amount</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody class="">
                    @if(count($billings)>0)
                        @foreach ($billings as $key => $item) 
                        <tr>
                        {{-- <td>{{$key + 1}}</td> --}}
                        <td>{{$item->company_name}} - {{$item->name}}</td>
                        <td>({{date('d F',strtotime($item->start_date))}} - {{date('d F',strtotime($item->end_date))}}) {{date('Y',strtotime($item->start_date))}}</td>
                        <td class="text-center">{{$item->description!=NULL?$item->description:'-'}}</td>
                        <td><i class="fas fa-rupee-sign"></i> {{$item->total_amount}}</td>
                        <td>
                            <span><a href="#" class="btn btn-outline-info" title="Download Invoice"> <i class="fas fa-download"></i> </a></span>
                            <a href="{{url('app/settings/billing/details',['id'=>base64_encode($item->id)])}}" class=" btn btn-outline-dark" title="Billing Summary"><i class="far fa-eye"></i></a>
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
          {!! $billings->render() !!}
      </div>
    </div>
 </div>