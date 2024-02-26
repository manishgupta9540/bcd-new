<div class="table-responsive">
    <table class="table table-bordered customerTable">
       <thead class="thead-light">
          <tr>
             {{-- <th>#</th> --}}
             <th>Zone name</th>
             <th>Country</th>
             <th>States</th>
             <th width="10%">Action</th>
          </tr>
       </thead>
       <tbody>
        @if (count($zones)>0)
            @foreach ($zones as $item)
                     
                  
            <tr>
               
                  <td>
                     {{ $item->name }}
                  </td>
                  <td>
                     @php
                      $country=   Helper::get_country_name($item->country_id);
                     @endphp
                     
                     {{ $country->name }}
                  </td>
                  <td>
                     @php
                         $state = Helper::get_state_name($item->name,$item->business_id)
                     @endphp
                     {!! $state !!}
                  </td>
                  <td>
                     <a href="{{ url('/zone/edit',['id'=>base64_encode($item->business_id),'name'=>base64_encode($item->name)]) }}">
                        <button class="btn btn-info btn-sm mb-1" type="button"> <i class='fa fa-edit'></i> Edit</button>
                        </a>
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
 </div>
 <div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite"></div>
    </div>
    <div class="col-sm-12 col-md-7">
      {{-- <div class=" paging_simple_numbers" >            
          {!! $items->render() !!}
      </div> --}}
    </div>
 </div>

 <script>
     $(document).ready(function(){
         
     });
 </script>