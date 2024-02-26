<ul class="nav nav-tabs pt-3" id="myIconTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link @if(Request::segment(2)=='default') active @endif "  href="{{url('/notification/default')}}" > Default</a>
    </li>
    {{-- <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='setting') active @endif "  href="{{url('/notification/setting')}}" > Setting</a></li> --}}
    <li class="nav-item">
        <a class="nav-link @if(Request::segment(2)=='jaf') active @endif "  href="{{url('/notification/jaf/default')}}" > JAF</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Request::segment(2)=='insuff') active @endif "  href="{{url('/notification/insuff/default')}}" > Insufficiency</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Request::segment(2)=='report') active @endif "  href="{{url('/notification/report/default')}}" > Report</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Request::segment(2)=='vendor') active @endif "  href="{{url('/notification/vendor/defalut')}}" >Vendor</a>
    </li>
</ul>