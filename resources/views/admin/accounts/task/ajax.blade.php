{{-- <div class="table-responsive"> --}}

    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                {{-- <th>#</th> --}}
                <th scope="col" style="position:sticky; top:60px">User Name</th>
                <th scope="col" style="position:sticky; top:60px">Total Task</th>
                <th scope="col" style="position:sticky; top:60px">Pending Task</th>
                <th scope="col" style="position:sticky; top:60px">Completed Task</th>
                {{-- <th scope="col" style="position:sticky; top:60px">In-Tat</th>
                <th scope="col" style="position:sticky; top:60px">Out-Tat</th> --}}
            </tr>
        </thead>
        <tbody>
          @foreach ($user_ids_arr as $item)
            
                    <tr>
                        <td>
                            {{$item['name']}}
                        </td>
                        <td>
                            {{$item['all']}}
                        </td>
                        <td>
                            {{ $item['pending'] }}
                        </td>
                        <td>
                            {{ $item['completed'] }}
                        </td>
                        {{-- <td>

                        </td>
                        <td>

                        </td> --}}
                    </tr>
        @endforeach 
              
        </tbody>
    </table>
{{-- </div> --}}
<div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite"></div>
    </div>
    <div class="col-sm-12 col-md-7">
      <div class=" paging_simple_numbers" >            
          {{-- {!! $users->render() !!} --}}
      </div>
    </div>
 </div>


<script>
    $(document).ready(function(){
        // });
    });
</script>
    