@if (count($digital_logs)>0)
    @foreach ($digital_logs as $item)
        <div class="row">
            <div class="col-12">
                @php
                    $assign_user = Helper::user_details($item->assigned_by);
                @endphp
                <div class="form-group">
                    <label>
                        @if($item->platform_ref=='resend-sms' || $item->platform_ref=='resend-mail' || $item->platform_ref=='resend-sms-mail')
                            Resent By: 
                        @else
                            Sent By: 
                        @endif
                    </label>
                    <span class="text-justify">
                        @if($assign_user!=NULL)
                            {{Helper::user_name($assign_user->id)}} ({{Helper::company_name($assign_user->business_id)}})
                        @else
                            --
                        @endif
                    </span>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label>Date & Time: </label>
                    <span class="text-justify">{{date('d-M-y h:i A',strtotime($item->created_at))}}</span>
                </div>
            </div>
            {{-- <div class="col-12">
                <div class="form-group">
                    <label>Share Link: </label>
                    <span class="text-justify">
                        @if ($item->type=='form-link')
                            Form Link
                        @elseif($item->type=='app-link')
                            App Link
                        @else
                            --
                        @endif
                    </span>
                </div>
            </div> --}}
            @if($item->platform_ref=='sms' || $item->platform_ref=='sms-mail' || $item->platform_ref=='resend-sms' || $item->platform_ref=='resend-sms-mail')
                <div class="col-12">
                    <div class="form-group">
                        <label>Phone: </label>
                        <span class="text-justify">
                            {{$item->phone!=NULL ? $item->phone : '--'}}
                        </span>
                    </div>
                </div>
            @endif
            @if($item->platform_ref=='mail' || $item->platform_ref=='sms-mail' || $item->platform_ref=='resend-mail' || $item->platform_ref=='resend-sms-mail')
                <div class="col-12">
                    <div class="form-group">
                        <label>Email: </label>
                        <span class="text-justify">
                            {{$item->email!=NULL ? $item->email : '--'}}
                        </span>
                    </div>
                </div>
            @endif
            <div class="col-12">
                <div class="form-group">
                    <label>Platform : </label>
                    <span class="text-justify">
                        @if ($item->platform_ref=='sms')
                            SMS
                        @elseif($item->platform_ref=='mail')
                            Mail
                        @elseif($item->platform_ref=='sms-mail')
                            SMS & Mail
                        @elseif($item->platform_ref=='resend-sms')
                            Resend SMS
                        @elseif($item->platform_ref=='resend-mail')
                            Resend Mail
                        @elseif($item->platform_ref=='resend-sms-mail')
                            Resend SMS & Mail
                        @else
                            --
                        @endif
                    </span>
                </div>
                
            </div>
        </div>
        <p class="pb-border"></p>
    @endforeach
@endif