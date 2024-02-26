        <div class="row">
            <div class="col-md-12"> 
                 <div class="table-responsive">
                    <table class="table table-bordered  table-hover candidatesTable">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Emp. Code</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">Email</th>
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
                        
                                <td>
                                    
                                 
                                   <a href="" class="btn-link"> {{ $item->name }} </a><br>
                                    <small class="text-muted">Ref. No.: {{ $item->display_id }}</small>
                                </td>

                                <td>{{ $item->client_emp_code }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->email }}</td>
                                
                                <td>
                                    {{ Helper::get_sla_name($item->sla_id)}} <br>
                                    <?php $tat=  Helper::get_sla_tat($item->sla_id);?>
                                    <small class=""><span class="text-danger"> TAT -</span> {{$tat['client_tat']}}</small>
                                </td>
                                <td>
                                @if($item->jaf_status == 'pending')
                                
                                    @if ($item->jaf_send_to== 'coc')
                                        <span class="badge badge-danger">Not Filled</span><br>
                                        <?php $type = Auth::user()->user_type; ?>
                                  @if ($type == 'client')
                                  <a href="{{ url('my/candidates/jaf-fill',['case_id'=>  base64_encode($item->job_item_id) ]) }}" style='font-size:14px;' class="bnt-link">JAF Link</a>


                                    @else
                                        <?php  
                                        $user = Auth::user()->role;
                                        $business_id =Auth::user()->business_id;
                                        $child =Helper::get_user_permission($user,$business_id);

                                        $role = Helper::get_page_permission('28');
                                        // dd($role);
                                        ?>
                                    @foreach ($role as $key)
                                     
                                        @if (in_array($key->id,json_decode($child)) && $key->action_title == 'JAF Link'  && $key->status == '1' )
                                        <a href="{{ url('my/candidates/jaf-fill',['case_id'=>  base64_encode($item->job_item_id) ]) }}" style='font-size:14px;' class="bnt-link">JAF Link</a>
                                                     
                                         @endif
                                        

                                    @endforeach
                                    <span>you have not permission for JAF Filling</span>
                                  @endif
                                   
                                        {{-- <a href="{{ url('my/candidates/jaf-fill',['case_id'=>  base64_encode($item->job_item_id) ]) }}" style='font-size:14px;' class="bnt-link">JAF Link</a> --}}

                                    @endif
                                    @if ($item->jaf_send_to== 'customer')
                                        <span class="badge badge-danger">Not Filled</span><br>
                                        <span>JAF send to Customer</span>
                                    @endif
                                    @if ($item->jaf_send_to== 'candidate')
                                        <span class="badge badge-danger">Not Filled</span><br>
                                        <span>JAF send to Candidate</span>
                                    @endif
                                    
                                @endif

                                @if($item->jaf_status == 'filled' )
                                    <span class="badge badge-success" style="font-size: 14px;">  Completed </span><br>
                                    
                                @endif
                            </td>
                                <td>
                                    {{ date('d-m-Y',strtotime($item->created_at)) }}</td>
                                <td>
                                 <?php $type = Auth::user()->user_type; ?>
                                  @if ($type == 'client')
                                  <a href="{{ url('/my/candidates/show',['id'=>base64_encode($item->id)]) }}">
                                    <button class="btn btn-info" type="button">View</button>
                                    </a>

                                    @else
                                        <?php  
                                        $user = Auth::user()->role;
                                        $business_id =Auth::user()->business_id;
                                        $child =Helper::get_user_permission($user,$business_id);

                                        $role = Helper::get_page_permission('28');
                                        // dd($role);
                                        ?>
                                    @foreach ($role as $key)
                                     
                                        @if (in_array($key->id,json_decode($child)) && $key->action_title == 'View Candidate profile'  && $key->status == '1' )
                                            <a href="{{ url('/my/candidates/show',['id'=>base64_encode($item->id)]) }}">
                                            <button class="btn btn-info" type="button">View</button>
                                            </a>                                                      
                                         @endif
                                        

                                    @endforeach
                                  @endif
                                   
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
        