@extends('layouts.admin')

@section('content')

<?php  
use QuickBooksOnline\API\DataService\DataService;

 $dataService = DataService::Configure(array(
  'auth_mode' => 'oauth2',
  'ClientID' =>Config::get('app.client_id'), 
  'ClientSecret' =>  Config::get('app.client_secret'),
  'RedirectURI' =>Config::get('app.oauth_redirect_uri'),
  'scope' => Config::get('app.oauth_scope'),
  'baseUrl' => "development"
));

$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
$authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
session(['authUrl' => $authUrl]);
// $_SESSION['authUrl'] = ;
// dd($_SESSION['sessionAccessToken']);
$accessTokenJson=array();
// dd('hello');
if (!empty(session('sessionAccessToken')) || session('sessionAccessToken')!== null) {
  // dd('hello');
$accessToken = session('sessionAccessToken');
$accessTokenJson = array('token_type' => 'bearer',
    'access_token' => $accessToken->getAccessToken(),
    'refresh_token' => $accessToken->getRefreshToken(),
    'x_refresh_token_expires_in' => $accessToken->getRefreshTokenExpiresAt(),
    'expires_in' => $accessToken->getAccessTokenExpiresAt()
);
$dataService->updateOAuth2Token($accessToken);
$oauthLoginHelper = $dataService -> getOAuth2LoginHelper();
$CompanyInfo = $dataService->getCompanyInfo();

}

?>
<div class="main-content-wrap sidenav-open d-flex flex-column">
    <!-- ============ Body content start ============= -->
    <div class="main-content">   
        <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                <li>
                <a href="{{ url('/home') }}">Dashboard</a>
                </li>
                <li>Quicksbook</li>
                </ul>
            </div>
            <!-- ============Back Button ============= -->
            <div class="col-sm-1 back-arrow">
                <div class="text-right">
                    <a href="{{url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card text-left">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('admin.quickbook.menu')
                        </div>
                        @if ($message = Session::get('success'))
                            <div class="col-md-12">   
                                <div class="alert alert-success">
                                    <strong>{{ $message }}</strong> 
                                </div>
                            </div>
                        @endif
                    
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <a href="http://developer.intuit.com">
                                <img src="{{ asset('admin/images/quickbook/quickbooks_logo_horz.png') }}" id="headerLogo">
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="well text-center">

                        <h1>QuickBooks application</h1>
                        <h2> Connect to QuickBooks flow and API Request</h2>
                        <br>
                    </div>

                    <p>If there is no access token or the access token is invalid, click the <b>Connect to QuickBooks</b> button below.</p>
                    <pre id="accessToken">
                        <style= "background-color: #efefef; overflow-x:scroll"></style> <?php
                    $displayString = !empty($accessTokenJson) ? $accessTokenJson : "No Access Token Generated Yet";
                    echo json_encode($displayString, JSON_PRETTY_PRINT); ?>
                    </pre>
                    <a class="imgLink" href="#" onclick="oauth.loginPopup()"><img src="{{ asset('admin/images/quickbook/C2QB_green_btn_lg_default.png') }}" width="178" /></a>
                    <hr />

                    <div class="row">
                        <div class="col-md-9">
                            <h2>Make an API call</h2>
                            <p>If there is no access token or the access token is invalid, click either the <b>Connect to QuickBooks</b> button above.</p>
                        </div>
                        {{-- <div class="col-md-3">
                            <a class="btn btn-success" href="" > <i class="fa fa-plus"></i> Add New Customer</a> 
                        </div> --}}
                    </div>
                    <pre id="apiCall"></pre>
                    <button  type="button" class="btn btn-success company_info" >Get Company Info</button>
                    <hr />
                </div>
            </div>  
        </div>  
    </div>
    <!-- Footer Start -->
    <div class="flex-grow-1"></div>
</div>
<script>
    $(document).on('click','.company_info',function() {
            // console.log('abc');
               var $this = $(this);
               
               var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
               if ($(this).html() !== loadingText) {
               $this.data('original-text', $(this).html());
               $this.html(loadingText);
               }
               setTimeout(function() {
                  $this.html($this.data('original-text'));
               }, 5000);
              
               $.ajax({
                   type: 'GET',
                   url:"{{ url('/quickbook/api/call') }}",
                   success: function (msg) {
                       // console.log(data.success);
                       $( '#apiCall' ).html( msg );
                       
                       }
                       //show the form validates error
                       
                   });
    });
   
    var url = '<?php echo $authUrl; ?>';
    var OAuthCode = function(url) {
      
      this.loginPopup = function (parameter) {
          this.loginPopupUri(parameter);
      }
    
      this.loginPopupUri = function (parameter) {
    
          // Launch Popup
          var parameters = "location=1,width=800,height=650";
          parameters += ",left=" + (screen.width - 800) / 2 + ",top=" + (screen.height - 650) / 2;
    
          var win = window.open(url, 'connectPopup', parameters);
          var pollOAuth = window.setInterval(function () {
              try {
    
                  if (win.document.URL.indexOf("code") != -1) {
                      window.clearInterval(pollOAuth);
                      win.close();
                      location.reload();
                  }
              } catch (e) {
                  console.log(e)
              }
          }, 100);
      }
    }
   
   
    
   
    var apiCall = function() {
      
    
      this.refreshToken = function() {
          $.ajax({
              type: "POST",
              url: "{{url('/')}}"+"/quickbook/refresh/token",
          }).done(function( msg ) {
    
          });
      } 
    }
   
   var oauth = new OAuthCode(url);
   var apiCall = new apiCall();
      
</script>
  

@endsection