<ul class="nav nav-tabs" id="myIconTab" role="tablist">
    @if (Request::segment(2)=='customers')
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='show') active @endif show" id="customers" href="{{ url('/app/customers/show',['id'=> base64_encode($item->id)]) }}" role="tab" > Customers </a></li>
    @endif
    @if (Request::segment(2)=='candidate')
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='show') active @endif show" id="candidates" href="{{ url('/app/candidate/show',['id'=> base64_encode($item->id),'old_id'=>Request::segment(5)]) }}" role="tab" > Candidates </a></li>

    @endif
    {{-- <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='show') active @endif show" id="candidates" href="{{ url('/app/candidate/show',['id'=> base64_encode($item->id)]) }}" role="tab" > Candidates </a></li> --}}
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='jobs') active @endif" id="jobs" href="{{ url('/app/customers/jobs',['id'=> base64_encode($item->id)]) }}" role="tab" > Cases </a></li>
    <li class="nav-item"><a class="nav-link " id="checks" href="#qctb1" role="tab" > Checks </a></li>
    @if (Request::segment(2)=='customers')
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='sla') active @endif" id="qcs" href="{{ url('/app/customers/sla',['id'=> base64_encode($item->id)]) }}" role="tab" > SLA </a></li>
    
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='reports') active @endif" id="reports" href="{{ url('/app/customers/reports/show',['id'=> base64_encode($item->id)]) }}" role="tab" > Reports </a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='payments') active @endif" id="payments" href="{{ url('/app/customers/payments',['id'=> base64_encode($item->id)]) }}" role="tab" > Payments </a></li>

    @endif
    {{-- @if (Request::segment(2)=='candidate')
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='reports') active @endif" id="reports" href="{{ url('/app/reports/show',['id'=> base64_encode($item->id)]) }}" role="tab" > Reports </a></li>

    @endif --}}
    @if (Request::segment(2)=='candidate')
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='sla') active @endif" id="qcs" href="{{ url('/app/candidates/sla',['id'=> base64_encode($item->id),'old_id'=>Request::segment(5)]) }}" role="tab" > SLA </a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='reports') active @endif " id="reports" href="{{ url('/app/candidate/reports/show',['id'=> base64_encode($item->id)]) }}" role="tab" > Reports </a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='payments') active @endif" id="payments" href="{{ url('/app/candidate/payments',['id'=> base64_encode($item->id)]) }}" role="tab" > Payments </a></li>

    @endif
    {{-- <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='payments') active @endif" id="payments" href="{{ url('/app/customers/payments',['id'=> base64_encode($item->id)]) }}" role="tab" > Payments </a></li> --}}
</ul>