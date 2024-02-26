<table class="table table-bordered">
    <thead class="thead-light">
        <tr>
            {{-- <th>#</th> --}}
            @if($service_d->name=='Aadhar' || $service_d->name=='aadhar')
                <th>Aadhar No.</th>
            @elseif($service_d->id==3)
                <th>PAN No.</th>
                <th>Name</th>
            @elseif($service_d->name=='Voter ID' || $service_d->name=='voter id')
                <th>Voter ID No.</th>
                <th>Name</th>
            @elseif($service_d->name=='RC' || $service_d->name=='rc')
                <th>RC No.</th>
                <th>Name</th>
            @elseif($service_d->name=='Passport' || $service_d->name=='passport')
                <th>Passport No.</th>
                <th>Name</th>
            @elseif($service_d->name=='Driving' || $service_d->name=='driving')
                <th>DL No.</th>
                <th>Name</th>
            @elseif($service_d->name=='Bank Verification' || $service_d->name=='bank verification')
                <th>Bank Account No.</th>
                <th>Name</th>
            @elseif($service_d->name=='GSTIN' || $service_d->name=='gstin')
                <th>GST No.</th>
                <th>Name</th>
            @elseif($service_d->name=='Telecom' || $service_d->name=='telecom')
                <th>Phone No.</th>
                <th>Name</th>
            @elseif(stripos($service_d->type_name,'e_court')!==false)
                <th>Name</th>
                <th>Father Name</th>
                <th>Address</th>
            @elseif(stripos($service_d->type_name,'upi')!==false)
                <th>UPI ID</th>
                <th>Name</th>
            @elseif(stripos($service_d->type_name,'cin')!==false)
                <th>CIN Number</th>
                <th>Company Name</th>
            @endif
            <th>Used By</th>
            <th>Date & Time</th>
            <th width="10%">Price</th>
        </tr>
    </thead>
    <tbody>
        @if($data!=NULL && count($data)>0)
            @foreach ($data as $key => $d)
                <tr>
                    {{-- <td>{{$key + 1}}</td> --}}
                    @if($service_d->name=='Aadhar' || $service_d->name=='aadhar')
                        <td>{{$d->aadhar_number}}</td>
                        {{-- <td></td> --}}
                    @elseif($service_d->id==3)
                        <td>{{$d->pan_number}}</td>
                        <td>{{ucfirst($d->full_name)}}</td>
                    @elseif($service_d->name=='Voter ID' || $service_d->name=='voter id')
                        <td>{{$d->voter_id_number}}</td>
                        <td>{{ucfirst($d->full_name)}}</td>
                    @elseif($service_d->name=='RC' || $service_d->name=='rc')
                        <td>{{$d->rc_number}}</td>
                        <td>{{ucfirst($d->owner_name)}}</td>
                    @elseif($service_d->name=='Passport' || $service_d->name=='passport')
                        <td>{{$d->passport_number}}</td>
                        <td>{{ucfirst($d->full_name)}}</td>
                    @elseif($service_d->name=='Driving' || $service_d->name=='driving')
                        <td>{{$d->dl_number}}</td>
                        <td>{{ucfirst($d->name)}}</td>
                    @elseif($service_d->name=='Bank Verification' || $service_d->name=='bank verification')
                        <td>{{$d->account_number}}</td>
                        <td>{{ucfirst($d->full_name)}}</td>
                    @elseif($service_d->name=='GSTIN' || $service_d->name=='gstin')
                        <td>{{$d->gst_number}}</td>
                        <td>{{ucfirst($d->legal_name)}}</td>
                    @elseif($service_d->name=='Telecom' || $service_d->name=='telecom')
                        <td>{{$d->mobile_no}}</td>
                        <td>{{ucfirst($d->full_name)}}</td>
                    @elseif(stripos($service_d->type_name,'e_court')!==false)
                        <td>{{ucfirst($d->name)}}</td>
                        <td>{{ucfirst($d->father_name)}}</td>
                        <td>{{$d->address}}</td>
                    @elseif(stripos($service_d->type_name,'upi')!==false)
                        <td>{{$d->upi_id}}</td>
                        <td>{{ucfirst($d->name)}}</td>
                    @elseif(stripos($service_d->type_name,'cin')!==false)
                        <td>{{$d->cin_number}}</td>
                        <td>{{ucfirst($d->company_name)}}</td>
                    @else
                        <td>-</td>
                        <td>-</td>
                    @endif
                    <td>{{Helper::user_name($d->user_id)}}</td>
                    <td>{{date('d-F-Y h:i A',strtotime($d->created_at))}}</td>
                    <td><i class="fas fa-rupee-sign"></i> {{$d->price}}</td>
                </tr>
            @endforeach
        @else
            <tr class="text-center">
                <td colspan="5">No Data found</td>
            </tr>
        @endif                   
    </tbody>
</table>

@if($data!=NULL && count($data)>0)
<div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite"></div>
    </div>
    <div class="col-sm-12 col-md-7">
      <div class=" paging_simple_numbers" >            
          {!! $data->render() !!}
      </div>
    </div>
</div>
@endif