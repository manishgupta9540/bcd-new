<div class="row">
    <div class="col-md-12">
       <table class="table table-bordered">
           <thead class="thead-light">
               <tr>
                   <th scope="col" style="position:sticky; top:60px" class="text-center" width="5%">#</th>
                   <th scope="col" style="position:sticky; top:60px">Name</th>
                   <th scope="col" style="position:sticky; top:60px">No of hits</th>
                   <th scope="col" style="position:sticky; top:60px">Total Price</th>
                   <th scope="col" style="position:sticky; top:60px">Action</th>
               </tr>
           </thead>
           <tbody>
            @if(count($items) > 0)
               @foreach ($items as $item)
                  <tr>
                     <td class="text-center">
                        <a data-toggle="collapse" data-target="#demo{{$item->service_id}}" class="accordion-toggle btn btn-link text-info " href="javascript:;" style="font-size: 14px;">
                           <i class="fas fa-angle-double-down"></i>
                        </a>
                     </td>
                     <td><a class="text-info btn-lnk" href="{{url('/api-usage/details',['id'=>base64_encode($item->service_id)])}}">{{$item->name}}</a></td>
                     <td><a class="text-info btn-lnk" href="{{url('/api-usage/details',['id'=>base64_encode($item->service_id)])}}">{{$item->no_of_hits}}</a></td>
                     <td><i class="fas fa-rupee-sign"></i> {{$item->total_price}} </td>
                     <td class="">
                        <button class="btn btn-outline-info downloadDetails" data-service="{{base64_encode($item->service_id)}}" title="Download"><i class="fas fa-download"></i></button>
                        <span><a class="btn btn-outline-dark" href="{{url('/api-usage/details',['id'=>base64_encode($item->service_id)])}}" title="Preview Details"><i class="far fa-eye"></i></a></span>
                     </td>
                  </tr>
                  <tr>
                     {{-- <th>#</th> --}}
                     <?php 
                        $service_name=Helper::get_service_name($item->service_id); 

                     ?>
                     <td class="hiddenRow" colspan="5">
                        <div class="accordian-body collapse p-3" id="demo{{$item->service_id}}">
                           <div class="row">
                              <div class="col-md-6">
                                 <h4 class="card-title mb-1 mt-3">{{$service_name}}</h4>
                                 <p class="pb-border"> Usage details of last 7 days </p>
                              </div>
                              <div class="col-sm-12">
                                 <table class="table table-bordered">
                                    <thead class="thead-dark">
                                       <tr>
                                          @if(stripos($item->type_name,'aadhaar_validation')!==false)
                                             <th>Aadhar No.</th>
                                          @elseif (stripos($item->type_name,'pan')!==false)
                                             <th>PAN No.</th>
                                             <th>Name</th>
                                          @elseif (stripos($item->type_name,'voter_id')!==false)
                                             <th>Voter ID No.</th>
                                             <th>Name</th>
                                          @elseif (stripos($item->type_name,'rc')!==false)
                                             <th>RC No.</th>
                                             <th>Name</th>
                                          @elseif (stripos($item->type_name,'passport')!==false)
                                             <th>Passport No.</th>
                                             <th>Name</th>
                                          @elseif (stripos($item->type_name,'driving_license')!==false)
                                             <th>DL No.</th>
                                             <th>Name</th>
                                          @elseif (stripos($item->type_name,'bank_verification')!==false)
                                             <th>Bank Account No.</th>
                                             <th>Name</th>
                                          @elseif (stripos($item->type_name,'gstin')!==false)
                                             <th>GST No.</th>
                                             <th>Name</th>
                                          @elseif (stripos($item->type_name,'telecom')!==false)
                                             <th>Mobile No.</th>
                                             <th>Name</th>
                                          @elseif (stripos($item->type_name,'e_court')!==false)
                                             <th>Name</th>
                                             <th>Father Name</th>
                                             <th>Address</th>
                                          @elseif (stripos($item->type_name,'upi')!==false)
                                             <th>UPI ID</th>
                                             <th>Name</th>
                                          @elseif (stripos($item->type_name,'cin')!==false)
                                             <th>CIN Number</th>
                                             <th>Company Name</th>
                                          @endif
                                          <th>Used By</th>
                                          <th>Date & Time</th>
                                          <th width="10%">Price</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php $data=Helper::api_details(Auth::user()->business_id,$item->service_id,'customer') ?>
                                       {{-- {{dd($data)}} --}}
                                       @if($data!=NULL && count($data)>0)
                                          @foreach ($data as $key => $d)
                                             <tr>
                                                @if(stripos($item->type_name,'aadhaar_validation')!==false)
                                                   <td>{{$d->aadhar_number}}</td>
                                                @elseif (stripos($item->type_name,'pan')!==false)
                                                   <td>{{$d->pan_number}}</td>
                                                   <td>{{ucfirst($d->full_name)}}</td>
                                                @elseif (stripos($item->type_name,'voter_id')!==false)
                                                   <td>{{$d->voter_id_number}}</td>
                                                   <td>{{ucfirst($d->full_name)}}</td>
                                                @elseif (stripos($item->type_name,'rc')!==false)
                                                   <td>{{$d->rc_number}}</td>
                                                   <td>{{ucfirst($d->owner_name)}}</td>
                                                @elseif (stripos($item->type_name,'passport')!==false)
                                                   <td>{{$d->passport_number}}</td>
                                                   <td>{{ucfirst($d->full_name)}}</td>
                                                @elseif (stripos($item->type_name,'driving_license')!==false)
                                                   <td>{{$d->dl_number}}</td>
                                                   <td>{{ucfirst($d->name)}}</td>
                                                @elseif (stripos($item->type_name,'bank_verification')!==false)
                                                   <td>{{$d->account_number}}</td>
                                                   <td>{{ucfirst($d->full_name)}}</td>
                                                @elseif (stripos($item->type_name,'gstin')!==false)
                                                   <td>{{$d->gst_number}}</td>
                                                   <td>{{ucfirst($d->legal_name)}}</td>
                                                @elseif (stripos($item->type_name,'telecom')!==false)
                                                   <td>{{$d->mobile_no}}</td>
                                                   <td>{{ucfirst($d->full_name)}}</td>
                                                @elseif (stripos($item->type_name,'e_court')!==false)
                                                   <td>{{ucfirst($d->name)}}</td>
                                                   <td>{{ucfirst($d->father_name)}}</td>
                                                   <td>{{$d->address}}</td>
                                                @elseif (stripos($item->type_name,'upi')!==false)
                                                   <td>{{$d->upi_id}}</td>
                                                   <td>{{ucfirst($d->name)}}</td>
                                                @elseif (stripos($item->type_name,'cin')!==false)
                                                   <td>{{$d->cin_number}}</td>
                                                   <td>{{ucfirst($d->company_name)}}</td>
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
                              </div>
                           </div>
                        </div>
                     </td>
                  </tr>
               @endforeach
            @else
               <tr>
                  <td colspan="5" class="text-center">No Data Found</td>
               </tr> 
            @endif            
           </tbody>
       </table>
    </div>
 </div>
 <div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite"></div>
    </div>
    <div class="col-sm-12 col-md-7">
      <div class="paging_simple_numbers" >            
          {!! $items->render() !!}
      </div>
    </div>
 </div>