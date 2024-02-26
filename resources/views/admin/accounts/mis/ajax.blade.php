<div class="row">
    <div class="col-md-12">
        {{-- <div class="table-responsive"> --}}
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        {{-- <th>#</th> --}}
                        <th scope="col" style="position:sticky; top:60px">Candidate Name</th>
                        <th scope="col" style="position:sticky; top:60px">Activity </th>
                        <th scope="col" style="position:sticky; top:60px">Username</th>
                        <th scope="col" style="position:sticky; top:60px">Date</th>
                    </tr>
                </thead>
                <tbody class="">
                    <tbody>
                    {{-- @if(count($activities)>0)
                        @foreach ($activities as $activity)
                            <tr>
                                @php
                                 $created_by=   Helper::user_name($activity->created_by);
                                @endphp
                               
                                <td>{{ $created_by }}</td>
                                <td>{{ $activity->activity_title }}-{{ $activity->activity }}</td>
                                <td>
                                   
                                </td>
                                <td>
                                    
                                </td>
                            </tr> 
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td colspan="6">No Data Found</td>
                        </tr>
                    @endif       --}}
                    
                </tbody>
            </table>
        {{-- </div> --}}
    </div>
    {{-- <div class="flex-grow-1"></div> --}}
</div>
 <div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite"></div>
    </div>
    <div class="col-sm-12 col-md-7">
      <div class=" paging_simple_numbers" >            
          
    </div>
 </div>