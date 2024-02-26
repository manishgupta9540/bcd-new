<div class="aside-nav">
    @php
    $PROFILE_ACCESS    = false;
    $BUSINESSINFO_ACCESS   = false;
    $BUSINESS_CONTACT_ACCESS = false;
    $PACKAGE_ACCESS   = false;
    $BILLING_ACCESS   = false;
    $API_ACCESS   = false;
    $REPORT_ACCESS   = false;
    $FEEDBACK_ACCESS   = false;
    $HELP_ACCESS   = false;
    $SETTING_ACCESS   = false;
    $CONFIG_ACCESS   = false;
    $CONFIG_ACCESS = Helper::can_access('Billing Config','');
    $PROFILE_ACCESS    = Helper::can_access('Profile','');
    $BUSINESSINFO_ACCESS   = Helper::can_access('Business Info','');
    $BUSINESS_CONTACT_ACCESS = Helper::can_access('Business Contact','');
    $PACKAGE_ACCESS = Helper::can_access('Package','');
    $BILLING_ACCESS = Helper::can_access('Billing','');
    $API_ACCESS = Helper::can_access('Instant Verification ','');
    $REPORT_ACCESS = Helper::can_access('Report','');
    $FEEDBACK_ACCESS = Helper::can_access('Feedback','');
    $HELP_ACCESS = Helper::can_access('Help & Support','');
    $SETTING_ACCESS = Helper::can_access('Setting','');

  
    // $REPORT_ACCESS   = false;
    // $VIEW_ACCESS   = false;SLA 
    @endphp 

    <ul>
        @if ($PROFILE_ACCESS)
            <li class="@if(Request::segment(1) == 'profile') active @endif "><a href="{{ route('/profile') }}"> Profile @if(Request::segment(1) == 'profile') <i class="fa fa-arrow-right"></i> @endif </a></li>                 
        @endif
        @if ($BUSINESSINFO_ACCESS)
            <li class="@if(Request::segment(2) == 'info') active @endif "><a href="{{ route('/business/info') }}"> Business info @if(Request::segment(2) == 'info') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        @endif
        @if ($BUSINESS_CONTACT_ACCESS)
            <li class="@if(Request::segment(2) == 'contacts') active @endif "><a href="{{ route('/business/contacts') }}">Business Contact @if(Request::segment(2) == 'contacts') <i class="fa fa-arrow-right"></i> @endif </a></li>   
        @endif
        @if ($BUSINESS_CONTACT_ACCESS)
            <li class="@if(Request::segment(1) == 'config') active @endif "><a href="{{ url('/config/email') }}">SMTP Email Config @if(Request::segment(1) == 'config') <i class="fa fa-arrow-right"></i> @endif </a></li>   
        @endif
        {{-- @if ($PACKAGE_ACCESS)
             <li class="@if(Request::segment(1) == 'package') active @endif "><a href="{{ route('/package') }}">Package @if(Request::segment(1) == 'package') <i class="fa fa-arrow-right"></i> @endif</a></li>   
        @endif --}}
            <li class="@if(Request::segment(1) == 'sla') active @endif "><a href="{{ route('/sla') }}">SLA @if(Request::segment(1) == 'sla') <i class="fa fa-arrow-right"></i> @endif</a></li>               
        {{-- @if ($BILLING_ACCESS || $CONFIG_ACCESS)
            <li class="@if(Request::segment(1) == 'billing') active @endif "><a href="{{ $BILLING_ACCESS ?route('/billing/default'):url('/billing/config') }}">Billing @if(Request::segment(1) == 'billing') <i class="fa fa-arrow-right"></i> @endif </a></li>    
        @endif --}}
        <li class="@if(Request::segment(1) == 'checkprice') active @endif "><a href="{{ route('/checkprice/default') }}">Check Price @if(Request::segment(1) == 'checkprice') <i class="fa fa-arrow-right"></i> @endif </a></li> 
       @if ($API_ACCESS)
             <li class="@if(Request::segment(1) == 'api-usage') active @endif "><a href="{{ route('/api-usage') }}">Instant Verification @if(Request::segment(1) == 'api-usage') <i class="fa fa-arrow-right"></i> @endif </a></li>   
       @endif
       <li class="@if(Request::segment(1) == 'check') active @endif "><a href="{{ url('/check/control') }}">Check Control @if(Request::segment(1) == 'check') <i class="fa fa-arrow-right"></i> @endif </a></li>
       
       <li class="@if(Request::segment(1) == 'jafcontrollink') active @endif ">
        <a href="{{ url('/jafcontrollink/control') }}">Jaf Control Link @if(Request::segment(1) == 'jafcontrollink') <i class="fa fa-arrow-right"></i> @endif 
          </a>
      </li> 

        <li class="@if(Request::segment(1) == 'verification') active @endif "><a href="{{ url('/verification/customer_wise') }}">Verification Control @if(Request::segment(1) == 'verification') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        @if ($REPORT_ACCESS)
            <li class="@if(Request::segment(1) == 'reports') active @endif "><a href="{{ url('/reports/customer_wise') }}">Report Control @if(Request::segment(1) == 'reports') <i class="fa fa-arrow-right"></i> @endif </a></li>      
        @endif
            <li class="@if(Request::segment(2) == 'insuff_control') active @endif "><a href="{{ url('/settings/insuff_control/default') }}">Insufficiency Control @if(Request::segment(2) == 'insuff_control') <i class="fa fa-arrow-right"></i> @endif </a></li>      

            <li class="@if(Request::segment(2) == 'holiday') active @endif "><a href="{{ url('/settings/holiday') }}">Holidays @if(Request::segment(2) == 'holiday') <i class="fa fa-arrow-right"></i> @endif </a></li>      
        
            <li class="@if(Request::segment(1) == 'faq') active @endif "><a href="{{ route('/faq') }}">FAQ @if(Request::segment(1) == 'faq') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        @if ($FEEDBACK_ACCESS)
            <li class="@if(Request::segment(1) == 'feedback') active @endif "><a href="{{ url('/feedback') }}">Feedback @if(Request::segment(1) == 'feedback') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        @endif

        {{-- <li class="@if(Request::segment(1) == 'mis') active @endif "><a href="{{ url('/mis') }}">Activity Log @if(Request::segment(1) == 'mis') <i class="fa fa-arrow-right"></i> @endif </a></li>  --}}
        <li class="@if(Request::segment(1) == 'notification') active @endif "><a href="{{ url('/notification/default') }}">Notifications @if(Request::segment(1) == 'notification') <i class="fa fa-arrow-right"></i> @endif </a></li>      

        <li class="@if(Request::segment(2) == 'task') active @endif"><a href="{{ url('/settings/task') }}"> Tasks @if(Request::segment(2) == 'task') <i class="fa fa-arrow-right"></i> @endif </a></li>

        @if ($HELP_ACCESS)
            <li class="@if(Request::segment(1) == 'help') active @endif "><a href="{{ url('/help') }}">Help & Support @if(Request::segment(1) == 'help') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        @endif
        <li class="@if(Request::segment(1) == 'zone') active @endif "><a href="{{ url('/zone') }}">Zone @if(Request::segment(1) == 'zone') <i class="fa fa-arrow-right"></i> @endif </a></li>
        @if ($SETTING_ACCESS)
            <li class="@if(Request::segment(2) == 'general') active @endif "><a href="{{ url('/settings/general') }}">Settings @if(Request::segment(2) == 'general') <i class="fa fa-arrow-right"></i> @endif </a></li> 
        @endif

    </ul>  

</div>