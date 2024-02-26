<div class="aside-nav">
    <ul>
        <li class="@if(Request::segment(3) == 'profile') active @endif "><a href="{{ url('/app/settings/profile') }}"> Profile @if(Request::segment(3) == 'profile') <i class="fa fa-arrow-right"></i> @endif </a></li>
        {{-- <li class="@if(Request::segment(3) == 'sla') active @endif "><a href="{{ url('/app/settings/sla') }}"> SLA @if(Request::segment(3) == 'sla') <i class="fa fa-arrow-right"></i> @endif  </a></li> 
        <li class="@if(Request::segment(3) == 'jaf') active @endif "><a href="{{ url('/app/settings/jaf') }}"> JAF @if(Request::segment(3) == 'jaf') <i class="fa fa-arrow-right"></i> @endif </a></li>   
        <li class="@if(Request::segment(2) == 'customer') active @endif "><a href="">Customer </a></li>               
        <li class="@if(Request::segment(2) == 'case') active @endif "><a href="">Case </a></li>  --}}
        <li class="@if(Request::segment(3) == 'billing') active @endif "><a href="{{ url('/app/settings/billing') }}"> Billing @if(Request::segment(3) == 'billing') <i class="fa fa-arrow-right"></i> @endif  </a></li> 
        <li class="@if(Request::segment(3) == 'checkprice') active @endif "><a href="{{ url('/app/settings/checkprice') }}"> Check's Price @if(Request::segment(3) == 'checkprice') <i class="fa fa-arrow-right"></i> @endif  </a></li> 
        <li class="@if(Request::segment(3) == 'promocode') active @endif "><a href="{{ url('/app/settings/promocode') }}"> Promocode @if(Request::segment(3) == 'promocode') <i class="fa fa-arrow-right"></i> @endif  </a></li> 
        <li class="@if(Request::segment(3) == 'general') active @endif "><a href="{{ url('/app/settings/general') }}"> General @if(Request::segment(3) == 'general') <i class="fa fa-arrow-right"></i> @endif </a></li>
        <li class="@if(Request::segment(3) == 'holiday') active @endif "><a href="{{ url('/app/settings/holiday') }}"> Holidays @if(Request::segment(3) == 'holiday') <i class="fa fa-arrow-right"></i> @endif </a></li>                 
    </ul>  

</div>