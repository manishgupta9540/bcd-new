<ul class="nav nav-tabs pt-3" id="myIconTab" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='customer_wise') active @endif "  href="{{url('/verification/customer_wise')}}" > Customer Wise</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='service_wise') active @endif "  href="{{url('/verification/service_wise')}}" > Service Wise</a></li>
</ul>