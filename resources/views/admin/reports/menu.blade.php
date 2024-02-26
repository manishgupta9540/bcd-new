<ul class="nav nav-tabs" id="myIconTab" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='') active @endif "   href="{{url('/reports')}}" > Customer </a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='candidate') active @endif "  href="{{url('/reports/candidate')}}" > Candidate</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='sla') active @endif" href="{{url('/reports/sla')}}" role="tab" > SLA </a></li>
</ul>