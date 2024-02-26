<div class="row">
    <div class="col-md-12">
        <div class="table-responsive " style="height: 300px;">
            <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if(count($candidates)>0)
                        @foreach($candidates as $candidate)

                        <tr>
                            <th scope="row">BCD-{{$candidate->id}}</th>
                            <td>{{$candidate->name}}</td>
                            <td>{{$candidate->email}}</td>
                            <td>{{"+".$candidate->phone_code."-".str_replace(' ','',$candidate->phone)}}</td>
                            <td><span class="badge badge-success">ACTIVE</span></td>
                            <td>
                            <a href="{{ route('/candidates/show',['id'=>  base64_encode($candidate->id)]) }}"><button class="btn btn-success" type="button">View</button></a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="text-center"><h3>Record not available!</h3></td>
                        </tr>
                        @endif
                                            
                    </tbody>
                </table>
            </div>
        </div> 
    </div>
</div>