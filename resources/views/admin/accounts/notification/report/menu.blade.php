<ul class="nav nav-tabs pt-3" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='default') active @endif"  href="{{url('/notification/report/default')}}" > Report Send to the Client</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='mark-complete') active @endif "  href="{{url('/notification/report/mark-complete')}}" > Mark as Completed</a></li>
 </ul>