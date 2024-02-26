<ul class="nav nav-tabs pt-3" id="myIconTab" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='default') active @endif "   href="{{url('/check/control')}}"> Check Control</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='disclaimer') active @endif "  href="{{url('/check/control/disclaimer')}}"> Disclaimer</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='sms') active @endif "  href="{{url('/send/link/sms')}}"> Send Link</a></li>
</ul>