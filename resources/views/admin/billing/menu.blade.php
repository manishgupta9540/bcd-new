
@php
     $BILLING_ACCESS   = false;
    $CONFIG_ACCESS   = false;
    $CONFIG_ACCESS = Helper::can_access('Billing Config','');
    $BILLING_ACCESS = Helper::can_access('Billing','');
@endphp
 <ul class="nav nav-tabs" id="myIconTab" role="tablist">
     @if ($BILLING_ACCESS)
     <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='default') active @endif"   href="{{url('/billing/default')}}" > Default</a></li>

     @endif
     @if ($CONFIG_ACCESS)
         
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='config') active @endif"  href="{{url('/billing/config')}}" > Config</a></li>
    @endif

    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='settings') active @endif"  href="{{url('/billing/settings')}}" > Setting</a></li>

    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='action') active @endif"  href="{{url('/billing/action')}}" > Action</a></li>
</ul>