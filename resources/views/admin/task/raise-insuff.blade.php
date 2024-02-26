@foreach ($vendor_insuffs as $vendor_insuff)
   @if($vendor_insuff->status == 'raise')
        <b><label style="margin-left: 14px;"> Insuff Raised By : {{Helper::user_name($vendor_insuff->business_id)}} </label></b><br>
        <b><label for="label_name" style="margin-left: 14px;">Status : </label>  </b>{{$vendor_insuff->status}}<br> 
        <b><label for="label_name" style="margin-left: 14px;">Raise Date and Time : </label>  </b> {{ date('d M Y h:i', strtotime($vendor_insuff->created_at)) }}<br> 
        <b><label for="label_name" style="margin-left: 14px;">Remarks : </label> </b>{{$vendor_insuff->comments}}<br>
        @php
            $vendor =  Helper::get_vendor_attachments($vendor_insuff->id);
        
        @endphp
        @if(count($vendor)>0)
            <label style="margin-left: 14px;"><strong>Attachments: </strong></label>
            <div class="row">
               
            @foreach($vendor as $item)
                @if(stripos($item->attachments, 'pdf')!==false) 
                    <img src="admin/images/icon_pdf.png" title="file_name" alt="preview" style="height:100px;"/>';
                @else
                <div class="col-2">
                    <div class="image-area" style="width:110px;">
                        <img src="{{asset('uploads/vendor-raise-insuff/'.$item->attachments)}}" title="file_name" alt="preview" style="height:100px;"/>
                    </div>
                </div>
                @endif
            @endforeach
            </div>
            <p class="pb-border"></p>
        @endif
       <b style="margin-left: 14px;">Vendor Log Details</b><br> 
    @elseif($vendor_insuff->status == 'cleared')
        <b><label style="margin-left: 14px;"> Insuff Cleared By : {{Helper::user_name($vendor_insuff->created_by)}} </label></b><br>
        <b><label for="label_name" style="margin-left: 14px;">Status : </label>  </b>{{$vendor_insuff->status}}<br> 
        <b><label for="label_name" style="margin-left: 14px;">Cleared Date & Time : </label>  </b> {{ date('d M Y h:i', strtotime($vendor_insuff->created_at)) }}<br> 
        <b><label for="label_name" style="margin-left: 14px;">Remarks : </label> </b>{{$vendor_insuff->comments}}<br>
        @php
           $vendor =  Helper::get_vendor_attachments($vendor_insuff->id);
           
        @endphp
        @if(count($vendor)>0)
            <label style="margin-left: 14px;"><strong>Attachments: </strong></label>
            <div class="row">
            @foreach($vendor as $item)
                @if(stripos($item->attachments, 'pdf')!==false) 
                    <img src="admin/images/icon_pdf.png" title="file_name" alt="preview" style="height:100px;"/>';
                @else
                <div class="col-2">
                    <div class="image-area" style="width:110px;">
                        <img src="{{asset('uploads/vendor-raise-insuff/'.$item->attachments)}}" title="file_name" alt="preview" style="height:100px;"/>
                        {{--<a class="remove-image" data-id="{{ $item->id }}" href="javascript:;" style="display: inline;">×</a> --}}
                        {{-- <input type="hidden" name="fileID[]" value="{{ $item->id }}">--}}
                    </div>
                </div>
                @endif
            @endforeach
            </div>
        @endif
    @endif
@endforeach


