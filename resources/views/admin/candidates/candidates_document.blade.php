<div class="document">
    <div class="modal-body">
        <div class="row align-items-center mb-2 px-3">
           
            <div class="col-md-6 px-1 py-2 ">
                <div class="form-group mb-0">
                    <label for="label_name"><strong>Candidate Name:- </strong></label>
                    {{Helper::get_candidate_name_fullname($candidate_documentsId->candidate_id)}}
                </div>
            </div>
            <div class="col-md-6 px-1 py-2 text-right">
                <a class="btn btn-sm btn-outline-info add_submit addToReport" data-id="{{ base64_encode($candidate_documentsId->candidate_id)}}" title="Add to Report"><i class="fas fa-plus"></i> Add to Report </a>
            </div>
          
        </div>
        
       
        <div class="form-group">
            <div class="col-6 pl-2">
                <label><strong>Service Name:- </strong></label>
                <select name="service_id" id="service_id" class="form-control">
                    <option value="">Select Service</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">{{ $service->service_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @foreach ($candidate_documents as $documents)

            <div class="form-group pl-2">
                <label for="label_name"> <strong>Document Name:- </strong></label>
                {{Str::ucfirst($documents->document_name)}}
            </div>

            <div class="form-group pl-2">
                <label><strong>Id Number:- </strong></label>
                {{$documents->id_number}}
            </div>

            <div class="form-group pl-2">
                <label><strong>Remarks:- </strong></label>
                {{$documents->remarks}}
            </div>
        
        @if($documents->attachments != null)

            @php $atcachmentimg = explode(',', $documents->attachments); @endphp

            <div class="form-group pl-2">
                <label><strong>Attachments:- </strong></label>
                <div class="row">
                    @foreach ($atcachmentimg as $item)
                        @if(stripos($item,'pdf')!==false) 
                            <div class="col-2">
                                <div class="image-area" style="width:110px;">
                                    <a href="{{asset('/uploads/documents/'.$item)}}" target="_blank">
                                        <img src="{{asset('admin/images/icon_pdf.png')}}" title="file_name" alt="preview" class="w-100" style="height:100px;"/>
                                    </a>
                                </div>
                                <input class="checks" type="checkbox" name="checks[]" style="margin-left: 56px;" id="selected" value="{{$item}}">
                            </div>
                        @else
                            <div class="col-2">
                                <div class="image-area" style="width:110px;">
                                    <img src="{{asset('/uploads/documents/'.$item)}}" title="file_name" alt="preview" style="height:100px;"/>
                                </div>
                                <input class="checks selected" type="checkbox" name="checks[]" style="margin-left: 56px;" value="{{$item}}">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif 
        @endforeach
    </div>
<!-- Modal footer -->
    
