<div class="aside-nav">
    <ul>
        <li class="@if(Request::segment(3) == 'profile') active @endif "><a href="{{ url('/admin/vendor/profile/'.base64_encode($profile->id)) }}"> Profile @if(Request::segment(3) == 'profile') <i class="fa fa-arrow-right"></i> @endif </a></li>                 
        <li class="@if(Request::segment(3) == 'business-info') active @endif "><a href="{{ url('/admin/vendor/business-info/'.base64_encode($profile->id)) }}"> Business info @if(Request::segment(3) == 'business-info') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        <li class="@if(Request::segment(3) == 'checkPrice') active @endif "><a href="{{ url('/admin/vendor/checkPrice/'.base64_encode($profile->id)) }}">Check Price @if(Request::segment(3) == 'checkPrice') <i class="fa fa-arrow-right"></i> @endif</a></li>               
        <li class="@if(Request::segment(3) == 'sla') active @endif "><a href="{{ url('/admin/vendor/sla/'.base64_encode($profile->id)) }}">SLA @if(Request::segment(3) == 'sla') <i class="fa fa-arrow-right"></i> @endif</a></li>               

    </ul>
</div>