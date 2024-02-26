<ul class="nav nav-tabs pt-3" id="myIconTab" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='default') active @endif"   href="{{url('/app/guest/default')}}" > Default</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='order') active @endif"  href="{{url('app/guest/order')}}" > Orders</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='help') active @endif"  href="{{url('app/guest/help')}}" > Help & Support</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='checkprice') active @endif"  href="{{url('app/guest/checkprice')}}" > Check Price</a></li>
</ul>