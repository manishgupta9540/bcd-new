@php
    $ADD_ACCESS    = false;
    $VIEW_ACCESS   = false;
    $EDIT_ACCESS = false;
    $PDF_ACCESS   = false;
    $SLA_ACCESS   = false;
    $ADD_ACCESS    = Helper::can_access('SLA Create','');
    $VIEW_ACCESS   = Helper::can_access('SLA View','');
    $EDIT_ACCESS = Helper::can_access('SLA Edit','');
    $PDF_ACCESS = Helper::can_access('SLA PDF download','');
    $SLA_ACCESS = Helper::can_access('SLA','');


    // $REPORT_ACCESS   = false;
    // $VIEW_ACCESS   = false;SLA
@endphp 
<div class="row">
    <div class="col-md-12">
       <table class="table">
          <thead class="thead-light">
             <tr>
                <th scope="col" style="position:sticky; top:60px" width="10%">ID</th>
                <th scope="col" style="position:sticky; top:60px">Name</th>
                <th scope="col" style="position:sticky; top:60px" width="10%">Company</th>
                <th scope="col" style="position:sticky; top:60px">TAT</th>
                <th scope="col" style="position:sticky; top:60px">Days Type</th>
                <th scope="col" style="position:sticky; top:60px">TAT Type</th>
                <th scope="col" style="position:sticky; top:60px">Price Type</th>
                <th scope="col" style="position:sticky; top:60px">Check Items</th> 
                <th scope="col" style="position:sticky; top:60px" width="15%">Action</th>
             </tr>
          </thead>
          <tbody>
             @if ($SLA_ACCESS)
                @if(count($sla) > 0 )
                   @foreach($sla as $item)
                   <tr>
                      <td>{{ $item->display_id!=NULL ?  $item->display_id : '--'}}</td>
                      <td>{{ $item->title }}</td>
                      <td> <b>{{ Helper::company_name($item->business_id) }} </b></td>
                      <td><?php $tat=  Helper::get_sla_tat($item->id);?>
                         <small > <span class="text-info"> Internal TAT-</span> {{$tat['tat']}} </small><br>
                         <small class=""><span class="text-danger">Client TAT -</span> {{$tat['client_tat']}}</small></td>
                      <td>{{ucwords($item->days_type)}} Days</td>
                      <td>{{ucwords($item->tat_type)}}-Wise</td>
                      <td>{{ucwords($item->price_type)}}-Wise</td>
                      <td> 
                         {{ Helper::get_sla_items($item->id) }} 
                      </td>
                      <td>
                         @if ( $EDIT_ACCESS)
                            <span><a href="{{ url('/settings/sla/edit',['id'=>base64_encode($item->id)]) }}" class="btn btn-outline-info" title="Edit"> <i class="far fa-edit"></i> </a> </span>
                         @endif
                         @if ($VIEW_ACCESS)
                            <span><a href="{{ url('/settings/sla/view',['id'=>base64_encode($item->id)]) }}" class="btn btn-outline-dark" title="View"> <i class="far fa-eye"></i> </a></span>
                         @endif
                         @if ($PDF_ACCESS)
                            <span><a href="{{ url('/pdf-generate',['id'=>$item->id]) }}" class="btn btn-outline-info" title="PDF"> <i class="far fa-file-pdf"></i> </a>  </span>
                         @endif

                         <span><a href="javascript:void(0)" data-id="{{$item->id}}" class="btn btn-outline-info slaexcel" title="EXCEL"> <i class="fa fa-file-excel-o"></i> </a>  </span>
                         <span><a href="javascript:void(0)" data-id="{{$item->id}}" class="btn btn-outline-info slacsv" title="CSV"> <i class="fa fa-file-excel-o"></i> </a>  </span>
                         <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p>
                      </td>
                   </tr>
                   @endforeach
                @else
                      <tr> <td class="text-center" colspan="4">SLA is not created!</td> </tr>
                @endif
             @else
                <tr> <td class="text-center" colspan="4">You have not any permission to view list...</td> </tr>
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
      <div class=" paging_simple_numbers" >            
          {!! $sla->render() !!}
      </div>
    </div>
 </div>