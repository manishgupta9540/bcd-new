<ul class="nav nav-tabs pt-3" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='default') active @endif"  href="{{url('/notification/insuff/default')}}" > Default</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='case') active @endif"  href="{{url('/notification/insuff/case')}}" > Case</a></li>
 </ul>