<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                {{-- <th scope="col">#</th> --}}
                <th scope="col">SLA</th>
                <th scope="col">Verifications type</th>
                <th scope="col">No. of Candidates</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($jobs)>0)
            @foreach($jobs as $item)
            <tr>
                {{-- <th scope="row">{{$item->id}}</th> --}}
                <td>{{ Helper::get_sla_name($item->sla_id)}}</td>
                <td>{{ Helper::get_sla_items($item->sla_id)}} </td>
                <td> 1 </td>
                <td><span class="badge badge-info">Pending</span></td>
                <td>
                <a href="#"><button class="btn btn-success" type="button">View</button></a>
                </td>
            </tr>
            @endforeach
            @else
            <tr class="text-center">
                <td colspan="5"><h3>Record not available!</h3></td>
            </tr>
            @endif
                            
        </tbody>
    </table>
</div>