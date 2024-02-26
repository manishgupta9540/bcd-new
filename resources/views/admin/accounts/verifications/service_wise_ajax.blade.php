{{-- <div class="table-responsive"> --}}
    @php
    // $ADD_ACCESS    = false;
    $VIEW_ACCESS   = false;
    // $EDIT_ACCESS = false;
    // $PDF_ACCESS   = false;
    // $SLA_ACCESS   = false;
    // $ADD_ACCESS    = Helper::can_access('SLA Create','');
    $VIEW_ACCESS   = Helper::can_access('Verification Service Wise ','');
    // $EDIT_ACCESS = Helper::can_access('Edit Default Check Price','');
    // $PDF_ACCESS = Helper::can_access('SLA PDF download','');
    // $SLA_ACCESS = Helper::can_access('SLA','');

    
    // $REPORT_ACCESS   = false;
    // $VIEW_ACCESS   = false;SLA
    @endphp 
    @if ($VIEW_ACCESS)
    <table class="table table-bordered customerTable">
       <thead class="thead-light">
          <tr>
             {{-- <th>#</th> --}}
             <th scope="col" style="position:sticky; top:60px">Customer Name</th>
             {{-- <th>Show / Hide Status</th>
             <th width="10%">Action</th> --}}
          </tr>
       </thead>
       <tbody>
          @if(count($items)>0)
             @foreach ($items as $key => $item)
                   <tr data-toggle="collapse" data-target="#demo{{$item->id}}" class="accordion-toggle">
                      {{-- <td>{{$key+1}}</td> --}}
                      <td>{{Helper::user_name($item->id)}} - {{ Helper::company_name($item->id)}}</td>
                   </tr>
                   <tr class="p">
                        <td colspan="6" class="hiddenRow">
                            <div class="accordian-body collapse p-3" id="demo{{$item->id}}">
                                <label class="pb-2">Services :</label>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="row">
                                            @foreach($services as $service)
                                                @php
                                                    $hide=Helper::verification_service_show($item->id,$service->id);
                                                    if($hide==NULL)
                                                    {
                                                        $status='checked';
                                                    }
                                                    else {
                                                        $status='';
                                                    }
                                                @endphp
                                                {{-- <div class="form-check form-check-inline">
                                                    <input class="form-check-input services" type="checkbox" name="services" id="services" data-customer="{{base64_encode($item->id)}}" value="{{$service->id}}" data-service="{{base64_encode($service->id)}}" {{$status}}>
                                                    <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label>
                                                </div> --}}
                                                <div class="col-sm-3 mb-4">
                                                    <label class="switch">
                                                        <input type="checkbox" class="services" name="services" id="services" data-customer="{{base64_encode($item->id)}}" value="{{$service->id}}" data-service="{{base64_encode($service->id)}}" {{$status}}>
                                                        <span class="slider round"></span>
                                                        <label style="color: #000 !important;">{{ $service->name  }}</label>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </td> 
                   </tr>
             @endforeach
          @else
             <tr class="text-center">
                   <td colspan="6">No Data Found</td>
             </tr>
          @endif    
       </tbody>
    </table>
 {{-- </div> --}}
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
@else
 <span>You have not any permission to access...</span>
@endif
 <script>
     $(document).ready(function(){
         
     });
 </script>