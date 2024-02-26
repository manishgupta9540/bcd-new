<div class="aside-nav">
    
    <ul>
        <li class="@if(Request::segment(2) == 'general') active @endif "><a href="{{ route('/settings/general') }}"> General @if(Request::segment(2) == 'general') <i class="fa fa-arrow-right"></i> @endif </a></li>                 
        <li class="@if(Request::segment(2) == 'sla') active @endif "><a href="{{ route('/settings/sla') }}"> SLA @if(Request::segment(2) == 'sla') <i class="fa fa-arrow-right"></i> @endif  </a></li> 
        <li class="@if(Request::segment(2) == 'jaf') active @endif "><a href="{{ route('/settings/jaf') }}"> JAF @if(Request::segment(2) == 'jaf') <i class="fa fa-arrow-right"></i> @endif </a></li>   
        <li class="@if(Request::segment(2) == 'customer') active @endif "><a href="">Customer </a></li>               
        <li class="@if(Request::segment(2) == 'case') active @endif "><a href="">Case </a></li> 
    </ul>  

</div>