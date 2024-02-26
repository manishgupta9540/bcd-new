<div class="row">
   @php
      $VIEW_ACCESS   = false;
      $ADD_ACCESS   = false;
      $VIEW_ACCESS    = Helper::can_access('View Wallet Transaction List','/my');
      $ADD_ACCESS   = Helper::can_access('Add Money','/my');
   @endphp
 <div class="col-md-12" >
    @if ($VIEW_ACCESS)
    
      <table class="table table-bordered">
         <thead class="thead-light">
               <tr>
                  {{-- <th>#</th> --}}
                  <th>Transaction No.</th>
                  <th>Transaction Type</th>
                  <th>Amount</th>
                  <th>Notes</th>
                  <th>Paid By</th>
                  <th>Created At</th>
               </tr>
         </thead>
         <tbody>
               @if(count($wallet_transactions)>0)
                  @foreach ($wallet_transactions as $key => $item)
                     <tr>
                        {{-- <td>{{$key +1 }}</td> --}}
                        <td>{{$item->transaction_user_id }}</td>
                        <td>{{$item->transaction_type=='debit'?'Debit':'Credit'}}</td>
                        <td>{{$item->amount }}</td>
                        <td>{{$item->notes }}</td>
                        <td>{{$item->payment_done_by!=NULL ? Helper::user_name($item->payment_done_by) : '--'}}</td>
                        <td>{{date('d/m/Y h:i:s A',strtotime($item->created_at)) }}</td>
                     </tr>
                  @endforeach
               @else
                     <tr>
                        <td colspan="6" class="text-center">No Transaction Found</td>
                     </tr>
               @endif
         </tbody>
      </table>
   @else
                
      <span><h3 class="text-center">You have no access to View Wallet Transaction Lists </h3></span>
    @endif
 </div>
</div>
@if(count($wallet_transactions)>0)
 <div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite"></div>
    </div>
    <div class="col-sm-12 col-md-7">
      <div class=" paging_simple_numbers" >            
          {!! $wallet_transactions->render() !!}
      </div>
    </div>
</div>
@endif