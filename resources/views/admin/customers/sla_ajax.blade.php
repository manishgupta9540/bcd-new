<div class="table-responsive">
    <table class="table table-bordered">
       <thead>
          <tr>
             <th scope="col">#ID</th>
             <th scope="col">Name</th>
             <th scope="col">Verification type</th>
             <th scope="col">Status</th>
             <th scope="col">Action</th>
          </tr>
       </thead>
       <tbody>
          @if(count($sla)>0)
          @foreach($sla as $item)
          <tr>
             <th scope="row">{{$item->id}}</th>
             <td>{{$item->title}}</td>
             <td> {{ Helper::get_sla_items($item->id)}} </td>
             <td><span class="badge badge-success">Active</span></td>
             <td>
                <a href="{{ url('/settings/sla/view',['id'=>base64_encode($item->id)]) }}"><button class="btn btn-sm btn-info" type="button">View</button></a>
             </td>
          </tr>
          @endforeach
          @else
          <tr class="text-center">
             <td colspan="5">
                <h3>Record not available!</h3>
             </td>
          </tr>
          @endif
       </tbody>
    </table>
 </div>