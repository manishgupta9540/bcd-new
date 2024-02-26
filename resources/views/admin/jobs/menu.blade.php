<ul class="nav nav-tabs" id="myIconTab" role="tablist">
    <li class="nav-item"><a class="nav-link  @if(Request::segment(2)=='') active @endif "   href="{{route('/jobs')}}" > Customer </a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='candidate') active @endif" href="{{route('/jobs/candidate')}}" > Candidate</a></li>
    <li class="nav-item"><a class="nav-link  @if(Request::segment(2)=='sla') active @endif" href="{{route('/jobs/sla')}}" > SLA </a></li>
</ul>