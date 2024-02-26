@php
    $hide = Helper::check_price_show(Auth::user()->business_id); 
@endphp
<div class="aside-nav">

    <ul>
        <li class="@if(Request::segment(2) == 'profile') active @endif "><a href="{{ url('/vendor/profile') }}"> Profile @if(Request::segment(2) == 'profile') <i class="fa fa-arrow-right"></i> @endif </a></li>                 
         <li class="@if(Request::segment(2) == 'business') active @endif "><a href="{{ url('/vendor/business/info') }}"> Business info @if(Request::segment(2) == 'business') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        {{--<li class="@if(Request::segment(2) == 'contact-info') active @endif "><a href="{{ url('/my/contact-info') }}">Business Contact @if(Request::segment(2) == 'contact-info') <i class="fa fa-arrow-right"></i> @endif </a></li>
        <li class="@if(Request::segment(2) == 'vendor-info') active @endif "><a href="{{ url('/my/vendor-info') }}">Vendor Contact @if(Request::segment(2) == 'vendor-info') <i class="fa fa-arrow-right"></i> @endif </a></li>               
        <li class="@if(Request::segment(2) == 'billing') active @endif "><a href="{{ url('/my/billing') }}">Billing @if(Request::segment(2) == 'billing') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        @if($hide==NULL)
            <li class="@if(Request::segment(2) == 'checkprice') active @endif "><a href="{{ route('/my/checkprice') }}">Check Price @if(Request::segment(2) == 'checkprice') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        @endif
        <li class="@if(Request::segment(2) == 'api-usage') active @endif "><a href="{{ url('/my/api-usage') }}">Instant Verification @if(Request::segment(2) == 'api-usage') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        <li class="@if(Request::segment(2) == 'wallet') active @endif "><a href="{{ url('/my/wallet') }}">Wallet @if(Request::segment(2) == 'wallet') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        <li class="@if(Request::segment(2) == 'faq') active @endif "><a href="{{ route('/my/faq') }}">FAQ @if(Request::segment(2) == 'faq') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        <li class="@if(Request::segment(2) == 'feedback') active @endif "><a href="{{ url('/my/feedback') }}">Feedback @if(Request::segment(2) == 'feedback') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        <li class="@if(Request::segment(2) == 'help') active @endif "><a href="{{ url('/my/help') }}">Help & Support @if(Request::segment(2) == 'help') <i class="fa fa-arrow-right"></i> @endif </a></li>  --}}

    </ul>  

</div>