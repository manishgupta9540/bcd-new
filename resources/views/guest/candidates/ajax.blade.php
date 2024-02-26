<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover candidatesTable ">
                <thead>
                    <tr>
                        {{-- <th scope="col">#</th> --}}
                        <th scope="col">Name</th>
                        <th scope="col">Email ID</th>
                        <th scope="col">Phone Number</th>
                        <th scope="col">Created at</th>
                        {{-- <th scope="col">Status</th> --}}
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="candidateList">
                    @if(count($items)>0)
                        @foreach ($items as $item)
                            <tr>
                                {{-- <td scope="row"><input class="priority" type="checkbox" name="priority[]" value="{{ $item->id }}"></td> --}}
                                <td>
                                  {{-- @if($item->priority == 'normal')
                                    <i class="fa fa-circle normal"></i> 
                                  @elseif($item->priority == 'high')
                                      <i class="fa fa-circle high"></i>
                                  @else
                                      <i class="fa fa-circle low"></i>
                                  @endif --}}
                                  {{ $item->name }}
                                </td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->phone}}</td>
                                <td>{{$item->created_at}}</td>
                                {{-- <td></td> --}}
                                <td>
                                    <a href="{{ route('/guest/candidates/verification',['id'=>base64_encode($item->id)]) }}">
                                    <button class="btn btn-primary btn-sm" type="button"> Start Verification <i class="fas fa-arrow-right"></i></button>
                                    </a>
                                </td>
                            <tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td colspan="7">No Data Found</td>
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