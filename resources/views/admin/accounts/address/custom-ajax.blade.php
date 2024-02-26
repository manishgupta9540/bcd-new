<div class="table-responsive">
    <table class="table table-bordered customerTable">
       <thead class="thead-light">
          <tr>
             {{-- 
             <th>#</th>
             --}}
             <th>Customer Name</th>
             <th>Status</th>
             <th>Address</th>
             <th width="10%">Action</th>
          </tr>
       </thead>
       <tbody>
          @if(count($items)>0)
          @foreach ($items as $key => $item)
          <?php $hide = Helper::report_address_custom($item->id); 
            //  dd($hide);
            $address = Helper::get_footer_addresses($item->business_id);
            $addresses = Helper::get_footer_addresses($item->business_id);
            //  dd($address);
            ?>
          <tr>
             <td> {{ Helper::company_name($item->id)}} - ({{Helper::user_name($item->id)}})</td>
             <td>
                @if($hide==NULL || $hide == "0")
                <span class="badge badge-dark" data-cus_id="{{ base64_encode($item->id)}}">Disable</span>
                <span class="d-none badge badge-info" data-cust_id="{{ base64_encode($item->id)}}">Enable</span>
                @else
                <span class="d-none badge badge-dark" data-cus_id="{{ base64_encode($item->id)}}">Disable</span>
                <span class="badge badge-info" data-cust_id="{{ base64_encode($item->id)}}">Enable</span>
                @endif
             </td>
             <td>
               @if ($address!=NULL || $address!='')
                   {{$address}}
               @endif
             </td>
             <td>
               @if ($addresses!=NULL || $addresses!='')
                  <button class="btn btn-outline-info btn-md address-btn edit" type="button" data-customers_id="{{ base64_encode($item->id) }}" data-address="{{ $addresses }}"><strong></strong> Edit</button>

               @else
                  <button class="btn btn-outline-info btn-md address-btn edit" type="button" data-customers_id="{{ base64_encode($item->id) }}" data-address="{{ $addresses}}"><strong></strong> Add</button>
               @endif

                @if ($hide==null || $hide == "0")
                <button class="btn btn-outline-info btn-md address-btn resume" type="button" data-customer_id="{{ base64_encode($item->id) }}"><strong><i class="fas fa-eye"></i></strong> Enable</button>
                <button class="btn btn-outline-dark btn-md address-btn hold d-none" type="button" data-customer="{{ base64_encode($item->id) }}"><strong><i class="fas fa-eye-slash"></i></strong> Disable</button>
                @else

                <button class="btn btn-outline-dark btn-md hold address-btn" type="button" data-customer="{{ base64_encode($item->id) }}"><strong><i class="fas fa-eye-slash"></i></strong> Disable</button>
                <button class="btn btn-outline-info btn-md address-btn resume d-none" type="button" data-customer_id="{{ base64_encode($item->id) }}"><strong><i class="fas fa-eye"></i></strong> Enable</button>
                @endif
               
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
       <div class=" paging_simple_numbers" >            
          {!! $items->render() !!}
       </div>
    </div>
 </div>
 