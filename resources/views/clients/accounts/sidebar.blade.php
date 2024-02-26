@php
    $hide = Helper::check_price_show(Auth::user()->business_id); 
@endphp
<div class="aside-nav">
    @php
        $PROFILE_ACCESS    = false;
        $BUSINESSINFO_ACCESS   = false;
        $BUSINESS_CONTACT_ACCESS = false;
        $CHECK_PRICE_ACCESS   = false;
        $VENDOR_ACCESS   = false;
        $API_ACCESS   = false;
        $WALLET_ACCESS   = false;
        $FEEDBACK_ACCESS   = false;
        $HELP_ACCESS   = false;
        $FAQ_ACCESS   = false;
        // $CONFIG_ACCESS   = false;
        // $CONFIG_ACCESS = Helper::can_access('Billing Config','');
        $PROFILE_ACCESS    = Helper::can_access('Profile','/my');
        $BUSINESSINFO_ACCESS   = Helper::can_access('Business Info','/my');
        $BUSINESS_CONTACT_ACCESS = Helper::can_access('Business Contact','/my');
        $VENDOR_ACCESS = Helper::can_access('Vendor Details','/my');
        $CHECK_PRICE_ACCESS = Helper::can_access('Check Price','/my');
        $API_ACCESS = Helper::can_access('Instant Verification','/my');
        $WALLET_ACCESS = Helper::can_access('Wallet','/my');
        $FEEDBACK_ACCESS = Helper::can_access('Feedback','/my');
        $HELP_ACCESS = Helper::can_access('Help & Support','/my');
        $FAQ_ACCESS = Helper::can_access('FAQ','/my');

    
        // $REPORT_ACCESS   = false;
        // $VIEW_ACCESS   = false;SLA 
    @endphp 

    <ul>
        @if ($PROFILE_ACCESS)
            <li class="@if(Request::segment(2) == 'profile') active @endif "><a href="{{ url('/my/profile') }}"> Profile @if(Request::segment(2) == 'profile') <i class="fa fa-arrow-right"></i> @endif </a></li>                 
        @endif
        @if ($BUSINESSINFO_ACCESS)
            <li class="@if(Request::segment(2) == 'business-info') active @endif "><a href="{{ url('/my/business-info') }}"> Business info @if(Request::segment(2) == 'business-info') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        @endif
        @if ($BUSINESS_CONTACT_ACCESS)
            <li class="@if(Request::segment(2) == 'contact-info') active @endif "><a href="{{ url('/my/contact-info') }}">Business Contact @if(Request::segment(2) == 'contact-info') <i class="fa fa-arrow-right"></i> @endif </a></li>
        @endif
        @if ($VENDOR_ACCESS)
            <li class="@if(Request::segment(2) == 'vendor-info') active @endif "><a href="{{ url('/my/vendor-info') }}">Vendor Contact @if(Request::segment(2) == 'vendor-info') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        @endif
        {{-- <li class="@if(Request::segment(2) == 'billing') active @endif "><a href="{{ url('/my/billing') }}">Billing @if(Request::segment(2) == 'billing') <i class="fa fa-arrow-right"></i> @endif </a></li>  --}}
        @if ($CHECK_PRICE_ACCESS)
            @if($hide==NULL)
                <li class="@if(Request::segment(2) == 'checkprice') active @endif "><a href="{{ route('/my/checkprice') }}">Check Price @if(Request::segment(2) == 'checkprice') <i class="fa fa-arrow-right"></i> @endif </a></li> 
            @endif
        @endif
        @if ($API_ACCESS)
            <li class="@if(Request::segment(2) == 'api-usage') active @endif "><a href="{{ url('/my/api-usage') }}">Instant Verification @if(Request::segment(2) == 'api-usage') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        @endif
        @if ($WALLET_ACCESS)
             <li class="@if(Request::segment(2) == 'wallet') active @endif "><a href="{{ url('/my/wallet') }}">Wallet @if(Request::segment(2) == 'wallet') <i class="fa fa-arrow-right"></i> @endif </a></li>   
        @endif
        @if ($FAQ_ACCESS)
             <li class="@if(Request::segment(2) == 'faq') active @endif "><a href="{{ route('/my/faq') }}">FAQ @if(Request::segment(2) == 'faq') <i class="fa fa-arrow-right"></i> @endif </a></li>  
        @endif
        @if ($FEEDBACK_ACCESS)
            <li class="@if(Request::segment(2) == 'feedback') active @endif "><a href="{{ url('/my/feedback') }}">Feedback @if(Request::segment(2) == 'feedback') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        @endif
        @if ($HELP_ACCESS)
            <li class="@if(Request::segment(2) == 'help') active @endif "><a href="{{ url('/my/help') }}">Help & Support @if(Request::segment(2) == 'help') <i class="fa fa-arrow-right"></i> @endif </a></li>  
        @endif
    </ul>  

</div>