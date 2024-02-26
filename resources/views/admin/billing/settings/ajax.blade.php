 <table class="table table-bordered">
    <thead class="thead-light">
       <tr>
          {{-- <th>#</th> --}}
          <th scope="col" style="position:sticky; top:60px">Customer Name</th>
          <th scope="col" style="position:sticky; top:60px" width="10%">Action</th>
       </tr>
    </thead>
    <tbody>
       @if(count($items)>0)
          @foreach ($items as $key => $item)
                <tr>
                   {{-- <td>{{$key+1}}</td> --}}
                   <td>{{ Helper::company_name($item->id)}} - {{Helper::user_name($item->id)}}</td>
                   <td>
                      <button class="btn btn-outline-info btn-sm editBillCycle" data-id="{{base64_encode($item->id)}}" title="Edit Billing Cycle" type="button"> <i class='fa fa-edit'></i> Edit</button>  
                   </td>
                </tr> 
          @endforeach
       @else
          <tr class="text-center">
            <td colspan="3">No Data Found</td>
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


 <!-- Footer Start -->
 <div class="flex-grow-1"></div>
 
</div>

