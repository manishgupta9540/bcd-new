<style>
  .hiddenRow {
      padding: 0 4px !important;
      background-color: #eeeeee;
      font-size: 13px;
  }
  .accordian-body span{
     color:#a2a2a2 !important;
  }
  </style>
<div class="row">
    <div class="col-lg-12 col-sm-12 jaf-fill-task d-none" id="jaf-fill-task-card">
      <div class="card mb-4">
          <div class="card-body">
              <div class="card-title">JAF Filling</div>
              <div class="table-responsive" style="max-height: 400px;">
                <table class="table">
                  <thead>
                    <tr>
                      <th style="position:sticky; top:0px" scope="col" width="10%"><strong>Service</strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Total Case Allotted</strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Total Case Done</strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Total Case Done in TAT</strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Total Case Done out of TAT</strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Total Pending Case </strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Total Pending Case in TAT</strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Total Pending Case out of TAT</strong></th>
                    </tr>
                  </thead>
                  <tbody>
                      @if (count($task_jaf_result)>0)
                          @foreach ($task_jaf_result as $item)
                              <tr>
                                  @php
                                    $user_id_url = '';
                                    if(count($kams)>0 || Auth::user()->user_type== 'customer')
                                    {
                                        $user_id_url = '&task_user='.base64_encode($item['user_id']);
                                    }
                                  @endphp
                                  <td>{{$item['service']}}</td>
                                  <td><a class="jaf_fill_link" href="{{ url('/task/assign?t_type=jaf_fill&verify_status=all'.$user_id_url) }}">{{$item['allocated']}}</a></td>
                                  <td><a class="jaf_fill_link" href="{{ url('/task/assign?t_type=jaf_fill&verify_status=2'.$user_id_url) }}">{{$item['completed']}}</a></td>
                                  <td><a class="jaf_fill_link" href="{{ url('/task/assign?t_type=jaf_fill&verify_status=2&in_tat=1'.$user_id_url) }}">{{$item['completed_in']}}</a></td>
                                  <td><a class="jaf_fill_link" href="{{ url('/task/assign?t_type=jaf_fill&verify_status=2&out_tat=1'.$user_id_url) }}">{{$item['completed_out']}}</a></td>
                                  <td><a class="jaf_fill_link" href="{{ url('/task/assign?t_type=jaf_fill&verify_status=1'.$user_id_url) }}">{{$item['pending']}}</a></td>
                                  <td><a class="jaf_fill_link" href="{{ url('/task/assign?t_type=jaf_fill&verify_status=1&in_tat=1'.$user_id_url) }}">{{$item['pending_in']}}</a></td>
                                  <td><a class="jaf_fill_link" href="{{ url('/task/assign?t_type=jaf_fill&verify_status=1&out_tat=1'.$user_id_url) }}">{{$item['pending_out']}}</a></td>
                              </tr>
                          @endforeach
                      @endif
                  </tbody>
                </table>
              </div>
          </div>
      </div>
    </div>
    <div class="col-lg-12 col-sm-12 verify-task d-none" id="verify-task-card">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">Task for Verification</div>
                <div class="table-responsive" style="max-height: 400px;">
                  <table class="table">
                    <thead>
                      <tr>
                        @if(count($kams)>0)
                          <th scope="col" style="position:sticky; top:0px" class="text-center" width="3%">#</th>
                        @endif
                        <th style="position:sticky; top:0px" scope="col" width="10%"><strong>Service</strong></th>
                        <th style="position:sticky; top:0px" scope="col"><strong>Total Case Allotted</strong></th>
                        <th style="position:sticky; top:0px" scope="col"><strong>Total Case Done</strong></th>
                        <th style="position:sticky; top:0px" scope="col"><strong>Total Case Done in TAT</strong></th>
                        <th style="position:sticky; top:0px" scope="col"><strong>Total Case Done out of TAT</strong></th>
                        <th style="position:sticky; top:0px" scope="col"><strong>Total Pending Case </strong></th>
                        <th style="position:sticky; top:0px" scope="col"><strong>Total Pending Case in TAT</strong></th>
                        <th style="position:sticky; top:0px" scope="col"><strong>Total Pending Case out of TAT</strong></th>
                        <th style="position:sticky; top:0px" scope="col"><strong>Insuff Raised Case </strong></th>
                        <th style="position:sticky; top:0px" scope="col"><strong>Insuff Raised Case in TAT</strong></th>
                        <th style="position:sticky; top:0px" scope="col"><strong>Insuff Raised Case out of TAT</strong></th>
                      </tr>
                    </thead>
                    <tbody>
                        @if (count($task_verification_result)>0)
                            @foreach ($task_verification_result as $key => $item)
                                @php
                                  $service_url = '';
                                  if(count($kams)==0)
                                  {
                                    $service_url='&task_service='.$item['service_id'];
                                  }

                                  $user_id_url = '';
                                  if(count($kams)>0)
                                  {
                                      $user_id_url = '&task_user='.$item['user_id'];
                                  }
                                @endphp
                                <tr>
                                    @if(count($kams)>0)
                                      @php
                                        $user_type=Helper::getUserType($item['user_id']);
                                      @endphp
                                      <td>
                                        <a data-toggle="collapse" data-target="#demo{{$key}}" class="accordion-toggle btn btn-link text-info" href="javascript:;" style="font-size: 14px;">
                                          <i class="fas fa-angle-double-down"></i>
                                        </a>
                                      </td>
                                      <td>{{$user_type!=NULL && stripos($user_type,'customer')!==false ? Helper::user_name($item['user_id']).' (Customer)' : Helper::user_name($item['user_id'])}}</td>
                                    @else
                                      <td>{{$item['service']}}</td>
                                    @endif
                                    <td><a class="verify_task_link" href="{{ url('/task/assign?t_type=verify_task&verify_status=all'.$service_url.$user_id_url) }}">{{$item['allocated']}}</a></td>
                                    <td><a class="verify_task_link" href="{{ url('/task/assign?t_type=verify_task&verify_status=2'.$service_url.$user_id_url) }}">{{$item['completed']}}</a></td>
                                    <td><a class="verify_task_link" href="{{ url('/task/assign?t_type=verify_task&verify_status=2&in_tat=1'.$service_url.$user_id_url) }}">{{$item['completed_in']}}</a></td>
                                    <td><a class="verify_task_link" href="{{ url('/task/assign?t_type=verify_task&verify_status=2&out_tat=1'.$service_url.$user_id_url) }}">{{$item['completed_out']}}</a></td>
                                    <td><a class="verify_task_link" href="{{ url('/task/assign?t_type=verify_task&verify_status=1'.$service_url.$user_id_url) }}">{{$item['pending']}}</a></td>
                                    <td><a class="verify_task_link" href="{{ url('/task/assign?t_type=verify_task&verify_status=1&in_tat=1'.$service_url.$user_id_url) }}">{{$item['pending_in']}}</a></td>
                                    <td><a class="verify_task_link" href="{{ url('/task/assign?t_type=verify_task&verify_status=1&out_tat=1'.$service_url.$user_id_url) }}">{{$item['pending_out']}}</a></td>
                                    <td><a class="verify_task_link" href="{{ url('/task/assign?t_type=verify_task&verify_status=1&insuff=1'.$service_url.$user_id_url) }}">{{$item['insuff']}}</a></td>
                                    <td><a class="verify_task_link" href="{{ url('/task/assign?t_type=verify_task&verify_status=1&insuff=1&in_tat=1'.$service_url.$user_id_url) }}">{{$item['insuff_in']}}</a></td>
                                    <td><a class="verify_task_link" href="{{ url('/task/assign?t_type=verify_task&verify_status=1&insuff=1&out_tat=1'.$service_url.$user_id_url) }}">{{$item['insuff_out']}}</a></td>
                                </tr>
                                @if(count($kams)>0)
                                  <tr>
                                    <td class="hiddenRow" colspan="13">
                                      <div class="accordian-body collapse p-3" id="demo{{$key}}">
                                          <div class="row">
                                            <div class="col-sm-12">
                                              <table class="table table-bordered">
                                                <thead class="thead-dark">
                                                    <tr>
                                                      <th style="position:sticky; top:0px" scope="col" width="10%"><strong>Service</strong></th>
                                                      <th style="position:sticky; top:0px" scope="col"><strong>Total Case Allotted</strong></th>
                                                      <th style="position:sticky; top:0px" scope="col"><strong>Total Case Done</strong></th>
                                                      <th style="position:sticky; top:0px" scope="col"><strong>Total Case Done in TAT</strong></th>
                                                      <th style="position:sticky; top:0px" scope="col"><strong>Total Case Done out of TAT</strong></th>
                                                      <th style="position:sticky; top:0px" scope="col"><strong>Total Pending Case </strong></th>
                                                      <th style="position:sticky; top:0px" scope="col"><strong>Total Pending Case in TAT</strong></th>
                                                      <th style="position:sticky; top:0px" scope="col"><strong>Total Pending Case out of TAT</strong></th>
                                                      <th style="position:sticky; top:0px" scope="col"><strong>Insuff Raised Case </strong></th>
                                                      <th style="position:sticky; top:0px" scope="col"><strong>Insuff Raised Case in TAT</strong></th>
                                                      <th style="position:sticky; top:0px" scope="col"><strong>Insuff Raised Case out of TAT</strong></th>
                                                    </tr>
                                                </thead>
                                                @php
                                                  $results = Helper::getKAMTaskVerification($item['user_id'],Auth::user()->id);
                                                @endphp
                                                <tbody>
                                                  @if(count($results)>0)
                                                    @foreach ($results as $result)
                                                      <tr>
                                                        <td>{{$result['service']}}</td>
                                                        <td>{{$result['allocated']}}</td>
                                                        <td>{{$result['completed']}}</td>
                                                        <td>{{$result['completed_in']}}</td>
                                                        <td>{{$result['completed_out']}}</td>
                                                        <td>{{$result['pending']}}</td>
                                                        <td>{{$result['pending_in']}}</td>
                                                        <td>{{$result['pending_out']}}</td>
                                                        <td>{{$result['insuff']}}</td>
                                                        <td>{{$result['insuff_in']}}</td>
                                                        <td>{{$result['insuff_out']}}</td>
                                                      </tr>
                                                    @endforeach
                                                  @else
                                                    <tr class="text-center">
                                                      <td colspan="13">No Data found</td>
                                                    </tr>
                                                  @endif
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>
                                      </div>
                                    </td>
                                  </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-sm-12 report-write-task d-none" id="report-write-task-card">
      <div class="card mb-4">
          <div class="card-body">
              <div class="card-title">Report Writing</div>
              <div class="table-responsive" style="max-height: 400px;">
                <table class="table">
                  <thead>
                    <tr>
                      <th style="position:sticky; top:0px" scope="col" width="10%"><strong>Service</strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Total Case Allotted</strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Total Case Done</strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Total Case Done in TAT</strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Total Case Done out of TAT</strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Total Pending Case </strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Total Pending Case in TAT</strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Total Pending Case out of TAT</strong></th>
                      {{-- <th style="position:sticky; top:0px" scope="col"><strong>Insuff Raised Case </strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Insuff Raised Case in TAT</strong></th>
                      <th style="position:sticky; top:0px" scope="col"><strong>Insuff Raised Case out of TAT</strong></th> --}}
                    </tr>
                  </thead>
                  <tbody>
                      @if (count($task_report_result)>0)
                          @foreach ($task_report_result as $item)
                              <tr>
                                  <td>{{$item['service']}}</td>
                                  <td><a class="report_write_link" href="{{ url('/task/assign?t_type=report_write&verify_status=all') }}">{{$item['allocated']}}</a></td>
                                  <td><a class="report_write_link" href="{{ url('/task/assign?t_type=report_write&verify_status=2') }}">{{$item['completed']}}</a></td>
                                  <td><a class="report_write_link" href="{{ url('/task/assign?t_type=report_write&verify_status=2&in_tat=1') }}">{{$item['completed_in']}}</a></td>
                                  <td><a class="report_write_link" href="{{ url('/task/assign?t_type=report_write&verify_status=2&out_tat=1') }}">{{$item['completed_out']}}</a></td>
                                  <td><a class="report_write_link" href="{{ url('/task/assign?t_type=report_write&verify_status=1') }}">{{$item['pending']}}</a></td>
                                  <td><a class="report_write_link" href="{{ url('/task/assign?t_type=report_write&verify_status=1&in_tat=1') }}">{{$item['pending_in']}}</a></td>
                                  <td><a class="report_write_link" href="{{ url('/task/assign?t_type=report_write&verify_status=1&out_tat=1') }}">{{$item['pending_out']}}</a></td>
                                  {{-- <td>{{$item['insuff']}}</td>
                                  <td>{{$item['insuff_in']}}</td>
                                  <td>{{$item['insuff_out']}}</td> --}}
                              </tr>
                          @endforeach
                      @endif
                  </tbody>
                </table>
              </div>
          </div>
      </div>
    </div>
</div>