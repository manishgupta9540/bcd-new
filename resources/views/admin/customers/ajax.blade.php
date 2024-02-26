@php
     $EDIT_ACCESS   = false;
    $VIEW_ACCESS   = false;
    $VIEW_CUSTOMER_ACCESS =false;

    $EDIT_ACCESS    = Helper::can_access('Edit Customers','');
    $VIEW_ACCESS   = Helper::can_access('View Candidates List','');
    $VIEW_CUSTOMER_ACCESS =Helper::can_access('View Customers List','');
@endphp
<div class="row">
    <div class="col-md-12">
            {{-- <div class="table-responsive">  --}}
                @if($VIEW_CUSTOMER_ACCESS)
                    <table class="table table-bordered userTable">
                        <thead>
                            <tr>
                                {{-- <th scope="col">#</th> --}}
                                <th scope="col" style="position:sticky; top:60px">Company Name</th>
                                <th scope="col" style="position:sticky; top:60px">Contact Person</th>
                                <th scope="col" style="position:sticky; top:60px">Email</th>
                                <th scope="col" style="position:sticky; top:60px" width="15%">Phone</th>
                                <th scope="col" style="position:sticky; top:60px" width="10%">Created at</th>
                                <th scope="col" style="position:sticky; top:60px">Status</th>
                                <th scope="col" style="position:sticky; top:60px" width="15%">Action</th>
                            </tr>
                        </thead> 
                        <tbody>
                            
                            @if( count($items) > 0 )
                            @foreach($items as $item)
                            <?php
                                $display_id =NULL;
                                if($item->display_id!=NULL)
                                {
                                    $display_id = $item->display_id;
                                }
                                else {
                                    $u_id = str_pad($item->id, 10, "0", STR_PAD_LEFT);
                                    $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr($item->company_name,0,4)))).'-'.$u_id;
                                }
                                
                            ?>
                            <tr>
                                {{-- <th scope="row">{{ $item->id }}</th> --}}
                                <td> <b>{{ ucfirst($item->company_name) }} </b><br>
                                    <small class="text-muted">Customer ID: <b>{{$display_id }} </b></small>
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ "+".$item->phone_code."-".str_replace(' ','',$item->phone) }}</td>
                                <td>{{ date('d-m-Y',strtotime($item->created_at)) }}</td>
                                <td>
                                    @if($item->status==0)
                                        <span data-dc="{{base64_encode($item->id)}}" class="badge badge-danger">Inactive</span>
                                        <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success d-none">Active</span>
                                    @else
                                        <span data-dc="{{base64_encode($item->id)}}" class="badge badge-danger d-none">Inactive</span>
                                        <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success">Active</span>
                                    @endif
                                    {{-- <span class="badge badge-success">ACTIVE</span> --}}
                                </td>
                                <td>

                                        {{-- @if ($user_type == 'customer') --}}
                                        {{-- @foreach ($role as $key)
                                        @if($ADD_ACCESS)
                                        @if ( $key->action_title == 'View'  && $key->status == '1' ) --}}
                                    @if($VIEW_ACCESS)
                                        <a href="{{ url('/customers/show',['id'=>base64_encode($item->id)]) }}"><button class="btn btn-primary btn-sm mb-1" type="button"> <i class="fa fa-eye"></i> View</button></a>
                                    @endif
                                    {{-- @if ($key->action_title == 'Edit'  && $key->status == '1') --}}
                                    @if($EDIT_ACCESS)
                                        <a href="{{ url('/customers/edit',['id'=>base64_encode($item->id)]) }} "><button class="btn btn-info btn-sm mb-1" type="button"><i class="fa fa-edit"></i> Edit</button></a>

                                    @endif
                                    @if($item->status==1)
                                        <span data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-sm btn-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('disable')}}" data-name="{{$item->name}}" title="Deactivate"><i class="far fa-times-circle"></i> Deactivate</a></span>
                                        <span data-a="{{base64_encode($item->id)}}" class="d-none"><a href="javascript:;" class="btn btn-sm btn-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('enable')}}" data-name="{{$item->name}}" title="Active"><i class="far fa-check-circle"></i> Active</a></span>
                                    @else
                                        <span class="d-none" data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-sm btn-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('disable')}}" data-name="{{$item->name}}" title="Deactivate"><i class="far fa-times-circle"></i> Deactivate</a></span>
                                        <span data-a="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-sm btn-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('enable')}}" data-name="{{$item->name}}"  title="Active"><i class="far fa-check-circle"></i> Active</a></span>
                                    @endif
                                    {{-- @endforeach --}}
                                

                                    {{-- <a href="{{ route('/customers/show',['id'=>base64_encode($item->id)]) }}"><button class="btn btn-success btn-sm" type="button"> <i class="fa fa-eye"></i> View</button></a>
                                        <a href="{{ url('/customers/edit',['id'=>base64_encode($item->id)]) }} "><button class="btn btn-info btn-sm" type="button"><i class="fa fa-edit"></i> Edit</button></a> --}}
                                    
                                </td>
                            </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td scope="row" colspan="7"><h3 class="text-center">No record!</h3></td>
                                </tr>
                            @endif
                            
                        </tbody>
                    </table>
                @else
                
                    <span><h3 class="text-center">You have no access to View Customers Lists </h3></span>
                
                @endif
                {{-- </div> --}}
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite"></div>
    </div>
    <div class="col-sm-12 col-md-7">
      <div class=" paging_simple_numbers" >            
          {!!  $items->render()  !!}
      </div>
    </div>
 </div>

        {{-- </div>
        </div>
</div> --}}
