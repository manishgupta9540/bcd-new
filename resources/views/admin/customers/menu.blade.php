
<ul class="nav nav-tabs" id="myIconTab" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='show') active @endif  show" href="{{ route('/customers/show',['id'=> base64_encode($item->id)]) }}" > Candidates </a></li>
    {{-- <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='jobs') active @endif" href="{{ url('/customers/jobs',['id'=> base64_encode($item->id)]) }}" > Cases </a></li> --}}
    <li class="nav-item"><a class="nav-link" href="#qctb1" > Checks </a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='sla') active @endif" href="{{ route('/customers/sla',['id'=> base64_encode($item->id)]) }}" > SLA </a></li>
    <li class="nav-item"><a class="nav-link" href="#paymenttb1" > Payments </a></li>
</ul>