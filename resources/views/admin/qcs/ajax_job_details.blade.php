<table id="customes" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Job Name</th>
                <th>ID</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Address</th>
                <th>Date</th>
                <th>Call sent</th>
                <th>SMS sent</th>
                <th>Email sent</th>
                <th>SMS link clicked</th>
                <th>Form Filled</th>
                <th>QC Status</th>
                <th>Report</th>
            </tr>
        </thead>
        <tbody>
            @if(count($candidate_details)>0)
            @php $i=1 @endphp
            @foreach($candidate_details as $details)          
            <tr>
                <td>{{$i}}</td>
                <td>{{$details->title}}</td>
                <td>{{$details->business_id}}</td>
                <td>{{$details->name}}</td>
                <td>{{$details->phone}}</td>
                <td>{{$details->email}}</td>
                <td></td>
                <td>{{$details->created_at}}</td>
                <td>yes</td>
                <td>yes(0) delivered</td>
                <td>yes</td>
                <td>yes</td>
                <td>yes</td>
                <td>Done <a href="/confirmationQc/{{$details->id}}" class="qc1">QC</a></td>
                <td><a href="#" class="qc1">Link</a></td>
            </tr>
            @php $i++ @endphp            
            @endforeach
            @endif
        </tbody>
    </table>