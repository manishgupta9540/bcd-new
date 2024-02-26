<div class="card mb-4">
    <div class="card-body">
        <div class="card-title">JAF Filling</div>
        <div class="table-responsive" style="max-height: 400px;">
          <table class="table">
            <thead>
              <tr>
                @if(count($kams)==0 && Auth::user()->user_type== 'user')
                  <th style="position:sticky; top:0px" scope="col" width="10%"><strong>Service</strong></th>
                @else
                  <th style="position:sticky; top:0px" scope="col" width="10%"><strong>User Name</strong></th>
                @endif
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
                        @php
                          $user_id_url = '';
                          if(count($kams)>0 || Auth::user()->user_type== 'customer')
                          {
                              $user_id_url = '&task_user='.$item['user_id'];
                          }
                        @endphp
                        <tr>
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