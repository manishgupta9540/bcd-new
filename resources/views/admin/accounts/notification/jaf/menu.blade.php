<ul class="nav nav-tabs pt-3" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='default') active @endif"  href="{{url('/notification/jaf/default')}}" > Default</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='jaf-filled') active @endif "  href="{{url('/notification/jaf/jaf-filled')}}" > JAF Filled</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='jaf-to-candidate') active @endif "  href="{{url('/notification/jaf/jaf-to-candidate')}}" > JAF Sent to Candidate</a></li>
 </ul>