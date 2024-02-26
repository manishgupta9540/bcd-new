<table class="table notifyTable table-bordered">
    <thead class="thead-light">
        <tr>
            {{-- <th>#</th> --}}
            @if(stripos($user_type,'client')!==false)
                <th scope="col" style="position:sticky; top:60px">Company Name</th>
            @else
                <th scope="col" style="position:sticky; top:60px">User Name</th>
            @endif
            {{-- <th scope="col" style="position:sticky; top:60px">Role</th> --}}
            <th scope="col" style="position:sticky; top:60px">Status</th>
            <th scope="col" style="position:sticky; top:60px" width="20%">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($items)>0)
            @foreach ($items as $key => $item)
                <tr>
                    @if(stripos($item->user_type,'client')!==false)
                        <td>{{$item->company_name}} - {{$item->name}}</td>
                    @else
                        <td>{{$item->name}}</td>
                    @endif
                    {{-- <td>{{Helper::role_name($item->role)}}</td> --}}
                    <td>
                        @if($item->is_notify==0)
                            <span data-dc="{{base64_encode($item->id)}}" class="badge badge-dark">Disable</span>
                            <span data-ac="{{base64_encode($item->id)}}" class="badge badge-info d-none">Enable</span>
                        @else
                            <span data-dc="{{base64_encode($item->id)}}" class="badge badge-dark d-none">Disable</span>
                            <span data-ac="{{base64_encode($item->id)}}" class="badge badge-info">Enable</span>
                        @endif
                    </td>
                    <td> 
                    @if($item->is_notify==1)
                        <span data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-sm btn-outline-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('disable')}}" title="Disable"><i class="fas fa-eye-slash"></i> Disable</a></span>
                        <span data-a="{{base64_encode($item->id)}}" class="d-none"><a href="javascript:;" class="btn btn-sm btn-outline-info status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('enable')}}" title="Enable"><i class="fas fa-eye"></i> Enable</a></span>
                    @else
                        <span class="d-none" data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-sm btn-outline-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('disable')}}" title="Disable"><i class="fas fa-eye-slash"></i> Disable</a></span>
                        <span data-a="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-sm btn-outline-info status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('enable')}}"  title="Enable"><i class="fas fa-eye"></i> Enable</a></span>
                    @endif
                    <span><button class="btn btn-sm btn-outline-dark editBtn" data-id="{{base64_encode($item->id)}}"><i class="fa fa-edit"></i> Edit</button></span>
                </td>
                </tr>
            @endforeach
        @else
            <tr class="text-center">
                <td colspan="5">No Data Found</td>
            </tr>
        @endif    
    </tbody>
</table>
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



<script>
    // $(document).ready(function(){
       
    // });
</script>
    