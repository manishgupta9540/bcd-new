@if(count($jaf_datas)>0)
   <form method="post" id="bulk_raise_frm" action="{{url('/candidates/bulkRaiseInsuff',['id'=>base64_encode($candidate->id)])}}" enctype="multipart/form-data">
      @csrf
      <div class="modal-body">
         <div class="row">
            <div class="col-12">
               <div class="form-group">
                  <label for="label_name"> Candidate Name: </label>
                  <span class="candidate_name">{{$candidate->name}} ({{$candidate->display_id}})</span>
               </div>
            </div>
         </div>
         <div class="bulk_raise_data">
            @if(count($jaf_datas)>0)
               <div class="row">
                  @foreach($jaf_datas as $jaf)
                     <div class="col-12 form-check">
                        
                        <label class="check-inline " style="font-size: 14px;">
                           <input type="checkbox" class="check-{{$jaf->id}}" id="jaf-ready-report" name="check-{{$jaf->id}}"> {{$jaf->verification_type=='Manual' ? $jaf->service_name.' - '.$jaf->check_item_number : $jaf->service_name}}
                        </label>
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-check-{{$jaf->id}}"></p> 
                     </div>

                     @if($jaf->type_name=='pan' || $jaf->type_name=='educational' || $jaf->type_name=='employment')
                     @php
                        $form_data = $jaf->form_data;
                     @endphp
                     @if($form_data!=null)
                           @php
                              $input_item_data_array =  json_decode($form_data, true);
                           @endphp

                           @foreach ($input_item_data_array as $key => $input) 
                              @php
                                 $key_val = array_keys($input);
                                 $input_val = array_values($input);
                                 // dd($key_val);
                              @endphp
                              @if($jaf->type_name=='pan')   
                                 @if(stripos($key_val[0],'PAN Number')!==false)
                                    <div class="col-6">
                                       <label><b>Pan Number:</b> {{$input_val[0]}}</label>
                                    </div>
                                 @endif
                              @elseif($jaf->type_name=='educational')
                                 @if(stripos($key_val[0],'University Name / Board Name')!==false)
                                    <div class="col-6">
                                       <label><b>University Name / Board Name:</b> {{ $input_val[0] ? $input_val[0] : 'N/A'}}</label>
                                    </div>
                                 @endif
                              @elseif($jaf->type_name=='employment')
                                 @if(stripos($key_val[0],'Company name')!==false)
                                    <div class="col-6">
                                       <label><b>Company name:</b> {{ $input_val[0] ? $input_val[0] : 'N/A'}}</label>
                                    </div>
                                 @endif
                              @endif
                           @endforeach
                     @endif
                  @endif
                     <div class="col-12">
                        <div class="form-group">
                           <label for="label_name"> Comments: <span class="text-danger">*</span></label>
                           <textarea id="comments" name="comments-{{$jaf->id}}" class="form-control comments" placeholder=""></textarea>
                           <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-comments-{{$jaf->id}}"></p> 
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="form-group">
                           <label for="label_name"> Attachments: <i class="fa fa-info-circle tool" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i> </label>
                           <input type="file" name="attachments-{{$jaf->id}}[]" id="attachments" multiple class="form-control attachments">
                           <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachments-{{$jaf->id}}"></p>  
                        </div>
                     </div>
                  @endforeach
                  <div class="col-12">
                     <div class="form-group pt-2">
                        <label class="check-inline " style="font-size: 14px;">
                           <input type="checkbox" class="is_send_mail" id="is_send_mail" name="is_send_mail"> Send Mail to Candidate
                        </label>
                     </div>
                     <div class="form-group">
                        <label for="label_name"> No of Days: <span class="text-danger">*</span></label>
                        <input type="text" id="no_of_days" name="no_of_days" class="form-control no_of_days" placeholder=""/>
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-no_of_days"></p> 
                     </div>
                     <div class="form-group">
                        <label for="label_name"> No of Follow Up: <span class="text-danger">*</span></label>
                        <input type="text" id="number_of_follow" name="number_of_follow" class="form-control number_of_follow" placeholder=""/>
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-number_of_follow"></p> 
                     </div>
                  </div>
               </div>
            @endif
         </div>
         <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"></p> 
      </div>
       <div class="modal-footer">
         <button type="submit" class="btn btn-info bulk_raise_btn">Submit</button>
         <button type="button" class="btn btn-danger closebulkinsuffraise" data-dismiss="modal">Close</button>
      </div>
   </form>
@else
   <div class="modal-body">
      <div class="row">
         <div class="col-12 text-center">
            <h4><b>No Data Available to Raise</b></h4>
         </div>
      </div>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
   </div>
@endif