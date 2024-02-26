
<ul class="nav nav-tabs pt-3" id="myIconTab" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='default') active @endif"   href="{{url('/reports/default/report')}}" > Template-1 (Default) </a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='customer_wise') active @endif"   href="{{url('/reports/customer_wise')}}" >Template-2</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='template3') active @endif"   href="{{url('/reports/template3/report')}}" >Template-3</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='template4') active @endif"   href="{{url('/reports/template4/report')}}" >Template-4</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='custom') active @endif"  href="{{url('/reports/custom')}}" > Custom </a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='fileconfig') active @endif"  href="{{url('/reports/fileconfig')}}" > File Name Config </a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='address') active @endif"  href="{{url('/reports/address')}}" > Address </a></li>
</ul>