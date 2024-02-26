<ul class="nav nav-tabs pt-3" id="myIconTab" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='connect') active @endif "  href="{{url('/quickbook/connect')}}" > QuicksBook</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='list') active @endif "  href="{{url('/quickbook/customers/list')}}" >Customers</a></li>
    {{-- <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='invoice') active @endif "   href="{{ url('/quickbook/customers/invoice/list') }}" >Invoice</a></li> --}}
</ul>