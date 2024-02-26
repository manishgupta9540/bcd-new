<table class="table table-bordered">
    <thead class="thead-light">
       <tr >
          <th class="text-center" scope="col" style="position:sticky; top:60px" width="3%"><input type="checkbox" class="showhide" name='showhide' onchange="checkAll(this)" ></th>
          <th scope="col" style="position:sticky; top:60px" width="20%">Candidate Name</th>
          <th scope="col" style="position:sticky; top:60px" width="15%">Contact</th>
          <th scope="col" style="position:sticky; top:60px">Checks </th>
          {{-- <th scope="col">Raised By</th>
          <th scope="col">Raised Date</th> --}}
       </tr>
    </thead>
    <tbody>
       <?php $user_type = Auth::user()->user_type ?>
       {{-- if Login user is customer --}}
        @if (count($raised_insuff)>0)
            
            @foreach ($raised_insuff as $insuff)
            <tr>
                <td class="text-center" scope="row"><input class="checks" type="checkbox" name="checks[]" value="{{ $insuff->candidate_id }}" onchange='checkChange();'></td>
                <td>
                    <a href="{{url('/my/candidates/jaf-info',['id'=>base64_encode($insuff->candidate_id)])}}">{{Helper::candidate_user_name($insuff->candidate_id)}}</a> <br>
                    <small class="text-muted">Customer: <b>{{Helper::company_name($insuff->business_id)}}</b></small><br>
                    <small class="text-muted">Ref. No. <b>{{$insuff->display_id }}</b></small>
                </td>
                <td>
                    <small class="text-muted">Phone No: <b>{{"+".$insuff->phone_code."-".str_replace(' ','',$insuff->phone) }}</b></small><br>
                    <small class="text-muted">Email : <b>{{$insuff->email }}</b></small>
                </td> 
                <td>
                    {!!Helper::get_raise_service_name_slot($insuff->jaf_id,$insuff->candidate_id,$insuff->services)!!}    
                </td>
                {{-- <td>
                    {{Helper::user_name($insuff->created_by)}} 
                </td>
                <td>
                    {{ date('d-m-Y',strtotime($insuff->created_at) ) }}
                </td> --}}
            </tr>  
            @endforeach
        @else
            <tr>
            <td scope="row" colspan="4"><h3 class="text-center">No record!</h3></td>
            </tr>
        @endif
      
    </tbody>
 </table>
 <div class="row">
     <div class="col-sm-12 col-md-5">
         <div class="dataTables_info" role="status" aria-live="polite"></div>
     </div>
     <div class="col-sm-12 col-md-7">
       <div class=" paging_simple_numbers" >
            {!! $raised_insuff->render() !!}
       </div>
     </div>
  </div>