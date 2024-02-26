@php
$ADD_ACCESS    = false;
$EDIT_ACCESS   = false;
$VIEW_ACCESS   = false;
$STATUS_ACCESS   = false;
$DELETE_ACCESS   = false;
// dd($ADD_ACCESS);
$ADD_ACCESS    = Helper::can_access('Add User','/my');//passing action title and route group name
$EDIT_ACCESS    = Helper::can_access('Edit User','/my');//passing action title and route group name
$VIEW_ACCESS   = Helper::can_access('View Users List','/my');//passing action title and route group name
$STATUS_ACCESS    = Helper::can_access("User's Account Status",'/my');//passing action title and route group name
$DELETE_ACCESS   = Helper::can_access('Delete User','/my');//passing action title and route group name
@endphp
<div class="row">
    <div class="col-md-12">
       <div class="table-responsive">
          @if($VIEW_ACCESS)
             <table class="table table-bordered userTable">
                <thead>
                   <tr>
                      {{-- <th scope="col">#</th> --}}
                      <th scope="col">Name</th>
                      <th scope="col">Contact </th>
                      <th scope="col">Email</th>
                      <th scope="col">Role</th>
                      <th scope="col" class="text-center">Status</th>
                      <th scope="col">Action</th>
                   </tr>
                </thead>
                <tbody>
                   @if(count($users) > 0)
                      @foreach ($users as $key => $user)
                      <tr>
                        <?php
                            $display_id =NULL;
                            if($user->display_id!=NULL)
                            {
                            $display_id = $user->display_id;
                            }
                            else {
                            $u_id = str_pad($user->id, 10, "0", STR_PAD_LEFT);
                            $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr(Helper::company_name($user->business_id),0,4)))).'-'.$u_id;
                            }
                        ?>
                         {{-- <th scope="row">{{ $user->id }}</th> --}}
                         <td>
                             {{ $user->name }}<br>
                             <small class="text-muted">User ID:- <b>{{$display_id }} </b></small>
                        </td>
                         <td>{{ "+".$user->phone_code."-".str_replace(' ','',$user->phone) }}</td>
                         <td>{{ $user->email }}</td>
                         <td>{{ Helper::get_role_name($user->role) }}</td>
                         <td class="text-center">                            
                            @if ($STATUS_ACCESS)
                               @if($user->status==0)
                                  <span data-dc="{{base64_encode($user->id)}}" class="badge badge-danger">Inactive</span>
                                  <span data-ac="{{base64_encode($user->id)}}" class="badge badge-success d-none">Active</span>
                               @else
                                  <span data-dc="{{base64_encode($user->id)}}" class="badge badge-danger d-none">Inactive</span>
                                  <span data-ac="{{base64_encode($user->id)}}" class="badge badge-success">Active</span>
                               @endif
                               <br>
                               @if ($user->is_blocked=='1')
                                  <span class="badge badge-danger mt-2 " data-users_id="{{base64_encode($user->id)}}" >
                                     Blocked
                                  </span>
                               @endif
                            @endif
                         </td>
                         <td>
                            @if ($EDIT_ACCESS)
                               <a class="btn btn-sm btn-info" href="{{url('my/users/edit',base64_encode($user->id))}}"><i class="far fa-edit"></i> Edit</a>
                            @endif
                            @if ($DELETE_ACCESS)
                               <button class="btn btn-sm btn-danger deleteBtn" data-user_id="{{base64_encode($user->id)}}"><i class="far fa-trash-alt"></i> Delete</button>
                            @endif
                            @if ($STATUS_ACCESS)
                               @if($user->status==1)
                               <span data-d="{{base64_encode($user->id)}}"><a href="javascript:;" class="btn btn-sm btn-dark status" data-id="{{base64_encode($user->id)}}" data-type="{{base64_encode('disable')}}" data-name="{{$user->name}}" title="Deactivate"><i class="far fa-times-circle"></i> Deactivate</a></span>
                               <span data-a="{{base64_encode($user->id)}}" class="d-none"><a href="javascript:;" class="btn btn-sm btn-success status" data-id="{{base64_encode($user->id)}}" data-type="{{base64_encode('enable')}}" data-name="{{$user->name}}" title="Active"><i class="far fa-check-circle"></i> Active</a></span>
                               @else
                                     <span class="d-none" data-d="{{base64_encode($user->id)}}"><a href="javascript:;" class="btn btn-sm btn-dark status" data-id="{{base64_encode($user->id)}}" data-type="{{base64_encode('disable')}}" data-name="{{$user->name}}" title="Deactivate"><i class="far fa-times-circle"></i> Deactivate</a></span>
                                     <span data-a="{{base64_encode($user->id)}}"><a href="javascript:;" class="btn btn-sm btn-success status" data-id="{{base64_encode($user->id)}}" data-type="{{base64_encode('enable')}}" data-name="{{$user->name}}" title="Active"><i class="far fa-check-circle"></i> Active</a></span>
                               @endif
                               @if ($user->is_blocked=='1')
                                  <a class="btn btn-sm btn-info unblockBtn text-wh" data-user="{{base64_encode($user->id)}}"><i class="fas fa-unlock-alt"></i> Unblock</a>
                               @endif
                            @endif
                         </td>
                      </tr>
                      @endforeach
                   @else
                   <tr>
                      <td scope="row" colspan="7">
                         <h3 class="text-center">No record!</h3>
                      </td>
                   </tr>
                   @endif
                </tbody>
             </table>
          @else
             <span><h3 class="text-center">You have no access to View Users list</h3></span>
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
          {!!  $users->render()  !!}
      </div>
    </div>
 </div>