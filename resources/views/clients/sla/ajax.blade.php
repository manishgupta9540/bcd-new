@php
// $ADD_ACCESS    = false;
$SLA_DETAIL_ACCESS   = false;
$VIEW_ACCESS   = false;
// dd($ADD_ACCESS);
$SLA_DETAIL_ACCESS    = Helper::can_access('View SLA Details','/my');//passing action title and route group name
$VIEW_ACCESS   = Helper::can_access('View SLA List','/my');//passing action title and route group name

@endphp
<div class="row">
    <div class="col-md-12"> 
        <div class="table-responsive">
            @if ($VIEW_ACCESS)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">TAT</th>
                            <th scope="col">Checks</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="candidateList">
                        @if(count($sla)>0)
                            @foreach($sla as $item)
                                <tr>
                                    <td scope="row">{{ $item->display_id!=NULL ? $item->display_id : '--' }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td> <span class="text-danger"> {{ $item->client_tat }} Days </span></td>
                                    <td> {{ Helper::get_sla_items($item->id) }} </td>
                                    
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        @if ($SLA_DETAIL_ACCESS)
                                            <a href="{{ url('/my/sla/view',['id'=>base64_encode($item->id)]) }}">
                                                <button class="btn btn-info" type="button">View</button>
                                            </a>
                                        @endif   
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="7">No Record Found</td>
                        @endif 
                    </tbody>
                </table>
            @else
                <span><h3 class="text-center">You have no access to View SLA lists</h3></span>
            @endif  
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite"></div>
    </div>
    <div class="col-sm-12 col-md-7">
      <div class=" paging_simple_numbers" >            
          {!! $sla->render() !!}
      </div>
    </div>
 </div>