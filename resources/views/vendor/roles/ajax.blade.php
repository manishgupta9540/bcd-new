<div class="row">
    <div class="col-md-12">
        @php
            $EDIT_ACCESS   = false;
            $STATUS_ACCESS   = false;
            $DELETE_ACCESS   = false;
            $PERMISSION_ACCESS   = false;
            $EDIT_ACCESS   =  Helper::can_access('Edit Role','');//passing action title and route group name
            $STATUS_ACCESS   =  Helper::can_access('Role Status','');//passing action title and route group name
            $DELETE_ACCESS   =  Helper::can_access('Delete Role','');//passing action title and route group name
            $PERMISSION_ACCESS   =  Helper::can_access('Permissions','');//passing action title and route group name
        @endphp
       {{-- <div class="table-responsive"> --}}
          <table class="table table-bordered roleTable">
             <thead>
                <tr>
                    <th scope="col" style="position:sticky; top:60px">Role Name</th>
                    {{-- <th scope="col" style="position:sticky; top:60px">Role type</th> --}}
                    <th scope="col" style="position:sticky; top:60px">Status</th>
                    <th scope="col" style="position:sticky; top:60px">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $item)
                    <tr>
                        <td>{{$item->role}}</td>
                        {{-- <td>{{$item->role_type}}</td> --}}
                      <td>
                         {{-- @if($STATUS_ACCESS)
                            <input data-id="{{base64_encode($item->id)}}"  class="toggle-class" id="check-{{base64_encode($item->id)}}" name="status" type="checkbox"   {{ $item->status ? 'checked' : '' }}>
                         @endif --}}
                         @if($item->status==0)
                            <span data-dc="{{base64_encode($item->id)}}" class="badge badge-danger">Inactive</span>
                            <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success d-none">Active</span>
                         @else
                            <span data-dc="{{base64_encode($item->id)}}" class="badge badge-danger d-none">Inactive</span>
                            <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success">Active</span>
                         @endif
                      </td>
                        <td>
                          
                               <a class="btn btn-outline-info" href="{{url('vendor/roles/edit',['id'=>base64_encode($item->id)])}}" title="Edit" ><i class="far fa-edit"></i></a>
                            
                               <a class="btn btn-outline-danger deleteBtn" href="javascript:;" title="Delete" data-id="{{base64_encode($item->id)}}"><i class="fa fa-trash"></i></a>
                            
                           
                               @if($item->status==1)
                                  <span data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-outline-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('disable')}}" title="Deactivate"><i class="far fa-times-circle"></i></a></span>
                                  <span data-a="{{base64_encode($item->id)}}" class="d-none"><a href="javascript:;" class="btn btn-outline-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('enable')}}" title="Active"><i class="far fa-check-circle"></i></a></span>
                               @else
                                  <span class="d-none" data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-outline-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('disable')}}" title="Deactivate"><i class="far fa-times-circle"></i></a></span>
                                  <span data-a="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-outline-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('enable')}}"  title="Active"><i class="far fa-check-circle"></i></a></span>
                               @endif
                            
                            
                                <a class="btn btn-outline-info" href="{{url('/roles/permission',['id'=>base64_encode($item->id)])}}" title="Permission"><i class="fa fa-key" aria-hidden="true"></i></a>
                            
                        </td>
                    </tr>
                @empty
                     <tr class="text-center">
                        <td colspan="6"><h3>No record found</h3></td>
                    </tr>
                @endforelse
            </tbody>                       
          </table>
       {{-- </div> --}}
    </div>
 </div>
 <div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite"></div>
    </div>
    <div class="col-sm-12 col-md-7">
      <div class=" paging_simple_numbers" >            
          {!!  $roles->render()  !!}
      </div>
    </div>
 </div>