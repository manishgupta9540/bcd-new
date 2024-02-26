<div class="row">
    <div class="col-md-12">
       <div class="table-responsive">
          <table class="table table-bordered guestTable">
             <thead>
                <tr>
                   <th scope="col">Service Name</th>
                   <th scope="col">Service type </th>
                   <th scope="col">Price</th>
                   <th scope="col">Action</th>
                </tr>
             </thead>
             <tbody>
                @if(count($items) > 0)
                @foreach ($items as $key => $item)
                <tr>
                   <td>
                       {{ $item->name }}
                  </td>
                   <td><span class="badge badge-success">{{ $item->verification_type }}</span></td>
                   <td><i class="fas fa-rupee-sign"></i> {{ $item->price }}</td>
                   <td>
                      <span><a href="javascript:;" class="btn btn-md btn-outline-primary editpricebtn" data-id="{{base64_encode($item->id)}}" title="Edit Check Price"><i class="fas fa-edit"></i></a></span>
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