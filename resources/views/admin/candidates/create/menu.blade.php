<ul class="nav nav-tabs pt-3" id="myIconTab" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='') active @endif"   href="{{url('/candidates/create')}}" > Default</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='custom') active @endif"   href="{{url('/candidates/create/custom')}}" > Custom</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='criminal') active @endif" href="{{ url('/candidates/bulk/criminal') }}">Bulk Manual Verifications</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(4)=='create') active @endif" href="{{ url('/candidates/bulk/sla/create') }}">Bulk Manual Sla package Verifications</a></li>
</ul>