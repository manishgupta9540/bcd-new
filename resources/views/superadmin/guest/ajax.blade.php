<div class="row">
    <div class="col-md-12">
       <div class="table-responsive">
          <table class="table table-bordered guestTable">
             <thead>
                <tr>
                   <th scope="col">#</th>
                   <th scope="col">Name</th>
                   <th scope="col">Contact </th>
                   <th scope="col">Email</th>
                   {{-- <th scope="col">Status</th> --}}
                   <th scope="col">Created At</th>
                   <th scope="col">Status</th>
                   <th scope="col">Action</th>
                </tr>
             </thead>
             <tbody>
                @if(count($items) > 0)
                @foreach ($items as $key => $item)
                <tr>
                   <th scope="row">{{ $item->id }}</th>
                   <td>
                       {{ $item->name }} <br>
                       <?php $guest_business=Helper::user_businesses($item->business_id);?>
                       @if($guest_business!=NULL)
                          <small class="text-muted">
                              Company-Name: <b>{{$guest_business->company_name}}</b>
                          </small><br>
                          <small class="text-muted">
                              Job-Title: <b>{{$guest_business->job_title}}</b>
                          </small><br>
                      @endif
                  </td>
                   <td>{{ $item->phone }}</td>
                   <td>{{ $item->email }}</td>
                   {{-- <td></td> --}}
                   <td>{{ $item->updated_at!=NULL? date('d-M-Y h:i A',strtotime($item->updated_at)) : date('d-M-Y h:i A',strtotime($item->created_at)) }}</td>
                   <td>
                     @if($item->status==0)
                           <span data-dc="{{base64_encode($item->id)}}" class="badge badge-warning">Inactive</span>
                           <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success d-none">Active</span>
                     @else
                           <span data-dc="{{base64_encode($item->id)}}" class="badge badge-warning d-none">Inactive</span>
                           <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success">Active</span>
                     @endif
                     @if($item->is_email_verified==0)
                        <div><a href="javascript:;" style="font-size: 14px;;" id="resendMail{{base64_encode($item->id)}}" class="btn-link resendMail resendMail{{base64_encode($item->id)}}" data-id="{{base64_encode($item->id)}}"><i class="far fa-envelope"></i> Re-send Mail</a></div>
                     @endif
                   </td>
                   <td>
                      {{-- <span><a class="btn btn-md btn-outline-primary" href="{{ url('app/users/edit',[$item->id]) }}" title="Edit"><i class="fas fa-edit"></i></a></span> --}}
                      <span><a href="javascript:;" class="btn btn-md btn-outline-danger deleteBtn" data-id="{{base64_encode($item->id)}}" title="Delete"><i class="fas fa-trash-alt"></i></a></span>
                        @if($item->status==1)
                           <span data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-outline-warning status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('deactive')}}" title="Deactivate"><i class="far fa-times-circle"></i></a></span>
                           <span data-a="{{base64_encode($item->id)}}" class="d-none"><a href="javascript:;" class="btn btn-outline-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('active')}}" title="Activate"><i class="far fa-check-circle"></i></a></span>
                        @else
                           <span class="d-none" data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-outline-warning status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('deactive')}}" title="Deactivate"><i class="far fa-times-circle"></i></a></span>
                           <span data-a="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-outline-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('active')}}"  title="Activate"><i class="far fa-check-circle"></i></a></span>
                        @endif
                   </td>
                </tr>
                @endforeach
                @else
                <tr>
                   <td scope="row" colspan="6">
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
          {!! $items->render() !!}
      </div>
    </div>
 </div>