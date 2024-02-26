<div class="aside-nav">
    <ul>
        <li class="@if(Request::segment(2) == 'profile') active @endif "><a href="{{ route('/verify/profile') }}"> Profile @if(Request::segment(2) == 'profile') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        <li class="@if(Request::segment(2) == 'help') active @endif "><a href="{{ url('/verify/help') }}">Help & Support @if(Request::segment(2) == 'help') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        <li class="@if(Request::segment(2) == 'settings') active @endif "><a href="{{ url('/verify/settings') }}">Settings @if(Request::segment(2) == 'settings') <i class="fa fa-arrow-right"></i> @endif </a></li> 
    </ul>  
</div>