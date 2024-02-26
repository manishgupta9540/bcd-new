@php
$ADD_ACCESS    = false;
$EDIT_ACCESS   = false;
$VIEW_ACCESS   = false;
$ADD_ACCESS    = Helper::can_access('Add User','');//passing action title and route group name
$EDIT_ACCESS    = Helper::can_access('Edit User','');//passing action title and route group name
$VIEW_ACCESS   = Helper::can_access('View User List','');//passing action title and route group name
@endphp
<div class="row">
    <div class="col-md-12">
       {{-- <div class="table-responsive"> --}}
        @if ($VIEW_ACCESS)
          <table class="table table-bordered userTable">
             <thead>
                <tr>
                   {{-- <th scope="col" style="position:sticky; top:60px">#</th> --}}
                   <th scope="col" style="position:sticky; top:60px" width="15%">Name</th>
                   <th scope="col" style="position:sticky; top:60px" width="15%">Contact </th>
                   <th scope="col" style="position:sticky; top:60px">Email</th>
                   <th scope="col" style="position:sticky; top:60px">Role</th>
                   <th scope="col" style="position:sticky; top:60px; width:30%">Services</th>
                   <th scope="col" style="position:sticky; top:60px" class="text-center">Status</th>
                   {{-- <th scope="col" style="position:sticky; top:60px">Block Status</th> --}}
                   <th scope="col" style="position:sticky; top:60px">Action</th>
                </tr>
             </thead>
             <tbody>
                @if(count($users) > 0)
                @foreach ($users as $key => $user)
                   <tr>
                      {{-- <th scope="row">{{ $user->id }}</th> --}}
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
                      <td>{{ $user->name }}<br>
                         <small class="text-muted">User ID:- <b>{{$display_id }} </b></small>
                      </td>
                      <td>{{$user->phone!=NULL ? "+".$user->phone_code."-".str_replace(' ','',$user->phone) : '--'}}</td>
                      <td>{{ $user->email }}</td>

                      <td>{{ Helper::role_name($user->role)}}</td>
                      <td>
                           @foreach ($checks as $check)
                              @if ($check->user_id == $user->id)
                                 
                           
                              {{-- @foreach ($services as $service) --}}
                              {!! Helper::get_service_items($check->user_id) !!}
                              <?php  
                           
                                 break;
                              ?>
                              @endif
                           
                           @endforeach
                         {{-- @endforeach --}}
                      </td>
                      <td class="text-center">
                         {{-- <input data-id="{{base64_encode($user->id)}}" class="toggle-class" name="status" type="checkbox" {{ $user->status ? 'checked' : '' }}> --}}
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
                      </td>
                      
                      <td>

                         @if($EDIT_ACCESS)
                         <a class="btn btn-sm btn-info" href="{{ route('users.edit',base64_encode($user->id)) }}"><i class="far fa-edit"></i> Edit</a>
                         @endif
                         <br>
                         <button class="btn btn-sm btn-danger deleteBtn" data-user_id="{{base64_encode($user->id)}}"><i class="far fa-trash-alt"></i> Delete</button>
                         @if($user->status==1)
                               <span data-d="{{base64_encode($user->id)}}"><a href="javascript:;" class="btn btn-sm btn-dark status" data-id="{{base64_encode($user->id)}}" data-type="{{base64_encode('disable')}}" data-name="{{$user->name}}" title="Deactivate"><i class="far fa-times-circle"></i> Deactivate</a></span>
                               <span data-a="{{base64_encode($user->id)}}" class="d-none"><a href="javascript:;" class="btn btn-sm btn-success status" data-id="{{base64_encode($user->id)}}" data-type="{{base64_encode('enable')}}" data-name="{{$user->name}}" title="Active"><i class="far fa-check-circle"></i> Activate</a></span>
                         @else
                               <span class="d-none" data-d="{{base64_encode($user->id)}}"><a href="javascript:;" class="btn btn-sm btn-dark status" data-id="{{base64_encode($user->id)}}" data-type="{{base64_encode('disable')}}" data-name="{{$user->name}}" title="Deactivate"><i class="far fa-times-circle"></i> Deactivate</a></span>
                               <span data-a="{{base64_encode($user->id)}}"><a href="javascript:;" class="btn btn-sm btn-success status" data-id="{{base64_encode($user->id)}}" data-type="{{base64_encode('enable')}}" data-name="{{$user->name}}"  title="Active"><i class="far fa-check-circle"></i> Activate</a></span>
                         @endif
                         @if ($user->is_blocked=='1')
                            <a class="btn btn-sm btn-info unblockBtn text-wh" data-user="{{base64_encode($user->id)}}"><i class="fas fa-unlock-alt"></i> Unblock</a>
                         @endif
                      </td>
                   </tr>
                @endforeach
                @else
                <tr>
                   <td scope="row" colspan="9">
                      <h3 class="text-center">No record!</h3>
                   </td>
                </tr>
                @endif
             </tbody>
          </table>
          @else
          <span><h3 class="text-center">You have no access to View User lists</h3></span>
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
          {!!  $users->render()  !!}
      </div>
    </div>
 </div>