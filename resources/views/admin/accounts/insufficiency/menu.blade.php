<ul class="nav nav-tabs pt-3" id="myIconTab" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='default') active @endif"  href="{{url('/settings/insuff_control/default')}}" > Default</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='report') active @endif"  href="{{url('/settings/insuff_control/report')}}" > Report </a></li>
</ul>