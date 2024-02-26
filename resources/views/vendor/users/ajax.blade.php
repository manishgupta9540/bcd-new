<div class="row">
    <div class="col-md-12">
       <div class="table-responsive">
          <table class="table table-bordered userTable">
             <thead>
                <tr>
                   {{-- <th scope="col">#</th> --}}
                   <th scope="col">Name</th>
                   <th scope="col">Contact </th>
                   <th scope="col">Email</th>
                   {{-- <th scope="col">Role</th> --}}
                   <th scope="col" class="text-center">Status</th>
                   <th scope="col">Action</th>
                </tr>
             </thead>
             <tbody>
                @if(count($users) > 0)
                @foreach ($users as $key => $user)
                <tr>
                   
                   <td>{{ $user->name }}</td>
                   <td>{{ $user->phone }}</td>
                   <td>{{ $user->email }}</td>
                   {{-- <td>{{ Helper::get_role_name($user->role) }}</td> --}}
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
                      <a class="btn btn-sm btn-primary" href="{{url('vendor/users/edit',$user->id)}}"><i class="far fa-edit"></i> Edit</a>
                      <button class="btn btn-sm btn-danger deleteBtn" data-user_id="{{base64_encode($user->id)}}"><i class="far fa-trash-alt"></i> Delete</button>

                        @if($user->status==1)
                            <span data-d="{{base64_encode($user->id)}}"><a href="javascript:;" class="btn btn-sm btn-dark status" data-id="{{base64_encode($user->id)}}" data-type="{{base64_encode('disable')}}" data-name="{{$user->name}}" title="Deactivate"><i class="far fa-times-circle"></i> Deactivate</a></span>
                            <span data-a="{{base64_encode($user->id)}}" class="d-none"><a href="javascript:;" class="btn btn-sm btn-success status" data-id="{{base64_encode($user->id)}}" data-type="{{base64_encode('enable')}}" data-name="{{$user->name}}" title="Active"><i class="far fa-check-circle"></i> Activate</a></span>
                        @else
                            <span class="d-none" data-d="{{base64_encode($user->id)}}"><a href="javascript:;" class="btn btn-sm btn-dark status" data-id="{{base64_encode($user->id)}}" data-type="{{base64_encode('disable')}}" data-name="{{$user->name}}" title="Deactivate"><i class="far fa-times-circle"></i> Deactivate</a></span>
                            <span data-a="{{base64_encode($user->id)}}"><a href="javascript:;" class="btn btn-sm btn-success status" data-id="{{base64_encode($user->id)}}" data-type="{{base64_encode('enable')}}" data-name="{{$user->name}}"  title="Active"><i class="far fa-check-circle"></i> Activate</a></span>
                        @endif
                      @if ($user->is_blocked=='1')
                          <a class="btn btn-sm btn-primary unblockBtn text-wh" data-user="{{base64_encode($user->id)}}"><i class="fas fa-unlock-alt"></i> Unblock</a>
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