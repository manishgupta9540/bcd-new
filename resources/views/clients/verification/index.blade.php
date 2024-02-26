@extends('layouts.client')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content"> 
    <div class="row">
        <div class="card text-left">
           <div class="card-body">            
                   <div class="row">
                        <div class="col-md-12"> 
                            <div class="table-responsive">
                                <table class="table table-bordered  table-hover candidatesTable">
                                    <thead>
                                        <tr>
                                            {{-- <th scope="col">#ID</th> --}}
                                            <th scope="col">Name</th>
                                            <th scope="col">Email ID</th>
                                            <th scope="col">Phone Number</th>
                                            <th scope="col">SLA</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Created at</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="candidateList"> 
                                    @if( count($items) > 0 )
                                        @foreach($items as $item)
                                        <tr>
                                            {{-- <th scope="row">{{ $item->id }}</th> --}}
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>
                                                {{ Helper::get_sla_name($item->sla_id)}} <br>
                                                <?php $tat=  Helper::get_sla_tat($item->sla_id);?>
                                                
                                                <small class=""><span class="text-danger"> TAT -</span> {{$tat['client_tat']}}</small>
                                            </td>
                                            <td>@if($item->jaf_status == 'pending')
                                                <span class="badge badge-danger" style="font-size: 14px;">Pending</span><br>
                                                
                                            @endif

                                            @if($item->jaf_status == 'filled' )

                                                <span class="badge badge-success" style="font-size: 14px;">  Verification Done </span><br>
                                                
                                             <?php $report_status = Helper::get_report_status($item->candidate_id); ?>
                                                @if($report_status != NULL && $report_status['status'] =='completed')    
                                                    
                                                    <a  style="font-size: 14px; color:green;" class="btn-link reportExportBox" data-id="{{  base64_encode($report_status['id']) }}" > Report Done</a>
                                                @else 
                                                    <a  style='font-size:14px; color:red;' class="bnt-link"> Report under process...</a>  
                                                @endif    

                                            @endif</td>
                                            <td>{{ date('d-m-Y',strtotime($item->created_at)) }}</td>
                                            <td>
                                            <a href="{{ url('/my/candidates/show',['id'=>base64_encode($item->id)]) }}">
                                            <button class="btn btn-success" type="button">View</button>
                                            </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td scope="row" colspan="6"><h3 class="text-center">No record!</h3></td>
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
                    </div>
                </div>
            </div>
        </div>
         <!-- Footer Start -->
   <div class="flex-grow-1"></div>
    </div>
    @endsection