<ul class="nav nav-tabs pt-3" id="myIconTab" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='default') active @endif "   href="{{url('/checkprice/default')}}" > Default</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='customer_wise') active @endif "  href="{{url('/checkprice/customer_wise')}}" > Customer Wise</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='settings') active @endif "  href="{{url('/checkprice/settings')}}" > Setting</a></li>
</ul>