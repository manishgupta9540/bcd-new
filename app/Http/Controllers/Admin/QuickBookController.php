<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use QuickBooksOnline\API\DataService\DataService;

class QuickBookController extends Controller
{
   public function index()
   {
       return view('admin.quickbook.index');
   }

   public function customerList()
   {
    $business_id = Auth::user()->business_id;

    // dd($business_id);

    $items = DB::table('users as u')
    ->select('u.id','u.name','u.email','u.phone','u.phone_iso','u.phone_code','b.company_name','u.created_at','u.display_id')
    ->join('user_businesses as b','b.business_id','=','u.id')
    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
    ->whereNotIn('u.id',[$business_id])->get();
      return view('admin.quickbook.customer-list',compact('items'));
   }
   public function apiCall(Request $request)
   {
           // Create SDK instance
   
    $dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' =>Config::get('app.client_id'), 
        'ClientSecret' =>  Config::get('app.client_secret'),
        'RedirectURI' =>Config::get('app.oauth_redirect_uri'),
        'scope' => Config::get('app.oauth_scope'),
        'baseUrl' => "development"
    ));

    /*
     * Retrieve the accessToken value from session variable
     */
    $accessToken = session('sessionAccessToken');
    
    /*
     * Update the OAuth2Token of the dataService object
     */
    $dataService->updateOAuth2Token($accessToken);
    $companyInfo = $dataService->getCompanyInfo();
    // dd($companyInfo->CompanyAddr->Line1);
    $address = "QBO API call Successful!! Response Company name: " . $companyInfo->CompanyName . " Company Address: " . $companyInfo->CompanyAddr->Line1. " " . $companyInfo->CompanyAddr->City . " " . $companyInfo->CompanyAddr->PostalCode;
    print_r($address);
    // die;
    return json_encode($companyInfo);
   }

   public function refreshToken(Request $request)
   {
         /*
     * Retrieve the accessToken value from session variable
     */
    $accessToken =session('sessionAccessToken');
    $dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' =>Config::get('app.client_id'), 
        'ClientSecret' =>  Config::get('app.client_secret'),
        'RedirectURI' =>Config::get('app.oauth_redirect_uri'),
        'baseUrl' => "development",
        'refreshTokenKey' => $accessToken->getRefreshToken(),
        'QBORealmID' => "The Company ID which the app wants to access",
    ));

    /*
     * Update the OAuth2Token of the dataService object
     */
    $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
    $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
    // dd($refreshedAccessTokenObj);
    $dataService->updateOAuth2Token($refreshedAccessTokenObj);
    session(['sessionAccessToken' =>  $refreshedAccessTokenObj]);
    

    print_r($refreshedAccessTokenObj);
    return $refreshedAccessTokenObj;
   }

   public function processCode(Request $request)
    {
        // Create SDK instance
    
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' =>Config::get('app.client_id'), 
            'ClientSecret' =>  Config::get('app.client_secret'),
            'RedirectURI' =>Config::get('app.oauth_redirect_uri'),
            'scope' => Config::get('app.oauth_scope'),
            'baseUrl' => "development"
        ));

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        
        $parseUrl = $this->parseAuthRedirectUrl($_SERVER['QUERY_STRING']);
       
        /*
        * Update the OAuth2Token
        */
        $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($parseUrl['code'], $parseUrl['realmId']);
        $dataService->updateOAuth2Token($accessToken);

        /*
        * Setting the accessToken for session variable
        */
        session(['sessionAccessToken' =>  $accessToken]);
       
   }

   function parseAuthRedirectUrl($url)
    {
        parse_str($url,$qsArray);
        return array(
            'code' => $qsArray['code'],
            'realmId' => $qsArray['realmId']
        );
    }   
    // send customer to quicksbook
    public function customerCreate(Request $request)
    {
        $accessToken =session('sessionAccessToken');
        // dd($accessToken->getAccessToken());
        // echo"<pre>";
        // print_r($accessToken);
        // die;
        //   dd(base64_decode($request->id));
      $id=base64_decode($request->id);
      $user = DB::table('users as u')
            ->select('u.id','u.business_id','u.parent_id','u.email','u.name','u.first_name','u.middle_name','u.last_name','u.phone','u.phone_iso','u.phone_code','ub.company_name','ub.city_name','ub.zipcode','ub.address_line1','ub.country_id')
            ->join('user_businesses as ub','ub.business_id','=','u.id')
            ->where(['u.id'=>$id])
            ->first();
      $country_name = DB::table('countries')->where('id',$user->country_id)->first();
            // dd($user);
            $payload='{
                "FullyQualifiedName": "", 
                "PrimaryEmailAddr": {
                  "Address": "'.$user->email.'"
                }, 
                "DisplayName": "'.$user->company_name.'", 
                "Suffix": "", 
                "Title": "", 
                "MiddleName": "'.$user->middle_name.'", 
                "Notes": "Here are other details.", 
                "FamilyName": "'.$user->last_name.'", 
                "PrimaryPhone": {
                  "FreeFormNumber": "'.$user->phone.'"
                }, 
                "CompanyName": "'.$user->company_name.'", 
                "BillAddr": {
                  "CountrySubDivisionCode": "", 
                  "City": "'.$user->city_name.'", 
                  "PostalCode": "'.$user->zipcode.'", 
                  "Line1": "'.$user->address_line1.'", 
                  "Country": "'.$country_name->name.'"
                }, 
                "GivenName": "'.$user->first_name.'"
              }';
              //dd($payload);
              $apiURL = "https://sandbox-quickbooks.api.intuit.com/v3/company/4620816365207903590/customer?minorversion=63";

              $ch = curl_init();
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
              curl_setopt ($ch, CURLOPT_POST, 1);
              $authorization = "Authorization: Bearer ".$accessToken->getAccessToken(); // Prepare the authorisation token
              curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
              curl_setopt($ch, CURLOPT_URL, $apiURL);
              // Attach encoded JSON string to the POST fields
              curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
              $resp = curl_exec ($ch);
              curl_close ($ch);
              $xml = simplexml_load_string($resp);
              $json = json_encode($xml);
              $array_data = json_decode($json,TRUE);
              // $array_data =  json_decode($resp,true);
              if ($array_data) {
                // print_r($array_data);
                $data = [
                   
                  'business_id' => $user->business_id,
                  'parent_id' =>$user->parent_id,
                  'domain_name'   =>$array_data['Customer']['@attributes']['domain'],
                  'quicksbook_customer_id'  => $array_data['Customer']['Id'],
                  'created_time'=>$array_data['Customer']['MetaData']['CreateTime'],
                  'last_updated_time'=>$array_data['Customer']['MetaData']['LastUpdatedTime'],
                  'given_name'  => $array_data['Customer']['GivenName'],
                  'fully_qualified_name'=>    $array_data['Customer']['FullyQualifiedName'],
                  'family_name'=>    $array_data['Customer']['FamilyName'],
                  'company_name'=>   $array_data['Customer']['CompanyName'],
                  'display_name'=>   $array_data['Customer']['DisplayName'],
                  'phone' =>  $array_data['Customer']['PrimaryPhone']['FreeFormNumber'],
                  'email' =>   $array_data['Customer']['PrimaryEmailAddr']['Address'],
                  'bill_id'=>$array_data['Customer']['BillAddr']['Id'],
                  'address_line1' =>$array_data['Customer']['BillAddr']['Line1'],
                  'country'=>$array_data['Customer']['BillAddr']['Country'],
                  'zipcode'=>$array_data['Customer']['BillAddr']['PostalCode'],
                  'city'=>$array_data['Customer']['BillAddr']['City'],
                  'notes' =>$array_data['Customer']['Notes'],
                  'job' =>$array_data['Customer']['Job'],
                  'bill_with_parent' =>$array_data['Customer']['BillWithParent'],
                  'balance' =>$array_data['Customer']['Balance'],
                  'balance_with_jobs' =>$array_data['Customer']['BalanceWithJobs'],
                  'currency' =>$array_data['Customer']['CurrencyRef'],

                ];
                DB::table('quicksbook_customers')->insertGetId($data);
              }  

              return redirect()
                 ->route('/quickbook/customers/list')
                 ->with('success', 'Customer has been sent successfully to quicksbook .');
    }

    // //list of completed 
    public function quickbookInvoice(Request $request)
    {
      $accessToken =session('sessionAccessToken');
      $id=base64_decode($request->id);
      // echo"<pre>";
      $billings = DB::table('billings')->where('id',$id)->first();

      $candidate_id = DB::table('billing_items')->where('billing_id',$billings->id)->groupBy('candidate_id')->get();
      // dd($candidate_id);
      $service_id = DB::table('billing_items')->where('billing_id',$billings->id)->groupBy('service_id')->get();
      // print_r($billings);
      // print_r($candidate_id);
      // print_r($service_id);
      // die;
      // dd($service_id);
      $i=0;
      $line=[];
      foreach ($candidate_id as $candidate) {
        
        foreach ($service_id as $service) {

          $billing_order = DB::table('billing_items')->where(['billing_id'=>$billings->id,'service_id'=>$service->service_id,'candidate_id'=>$candidate->candidate_id])->orderBy('id', 'DESC')->first();
          // dd($billing_order);
          if ($billing_order!= null) {

            $user_data = DB::table('users')
            ->select('name')                
            ->where(['id'=>$billing_order->candidate_id])
            ->first();

            if($user_data !=null){
                $res_data = ucfirst($user_data->name);
            }

            $desc = $res_data .' - '. $billing_order->service_name;
           
            $i++;
            $line[]=[ 
              "Description"=> "$desc", 
              "DetailType"=> "SalesItemLineDetail", 
              "SalesItemLineDetail"=>
              array("TaxCodeRef"=> 
                array("value"=>"TAX"),
                "Qty"=> $billing_order->service_item_number, 
                "UnitPrice"=> $billing_order->price, 
                "ItemRef"=>
                array("name"=>"$billing_order->service_name",
                  "value"=>"$billing_order->quantity")
              ),
              "LineNum"=> $i, 
              "Amount"=> $billing_order->price * $billing_order->service_item_number, 
              "Id"=> "$billing_order->id"
            ];
          }
          // dd($billing_order);
        }
        // echo"<pre>";
        // print_r($service);
        // die;
      }
      // dd($line);
      // $billing_items = DB::table('billing_items')->where('billing_id',$billings->id)->get();

      
      // foreach ($billing_items as $item) {

      // }
      // $line=[];
      // $i=0;
      // foreach ($billing_items as $billing_item) {
      //   $i++;
      //     $line[]=[ 
      //     "Description"=> "$billing_item->service_name $billing_item->service_item_number ", 
      //     "DetailType"=> "SalesItemLineDetail", 
      //     "SalesItemLineDetail"=>
      //     array("TaxCodeRef"=> 
      //       array("value"=>"TAX"),
      //       "Qty"=> $billing_item->quantity, 
      //       "UnitPrice"=> $billing_item->price, 
      //       "ItemRef"=>
      //       array("name"=>"$billing_item->service_name",
      //         "value"=>"$billing_item->quantity")
      //     ),
      //     "LineNum"=> $i, 
      //     "Amount"=> $billing_item->final_total_check_price, 
      //     "Id"=> "$billing_item->id"
      //   ];
      // }
      // dd($line);
      $line_json =json_encode($line);
      // $line_string = 
      // dd($line_json);

      // "DetailType"=> "SalesItemLineDetail", 
      //     "SalesItemLineDetail"=>
      //     array("TaxCodeRef"=> 
      //       array("value"=>"TAX"),
      //       "Qty"=> $billing_item->quantity, 
      //       "UnitPrice"=> $billing_item->price, 
      //       "ItemRef"=>
      //       array("name"=>"$billing_item->service_name",
      //         "value"=>"$billing_item->quantity")
      //     ),
      //     "LineNum"=> $i, 
      //     "Amount"=> $billing_item->final_total_check_price, 
      //     "Id"=> "$billing_item->id"
      // $payload='{
      //   "Line": [
      //     {
      //       "DetailType": "SalesItemLineDetail", 
      //       "Amount":"'.$billings->total_amount.'", 
      //       "SalesItemLineDetail": {
      //         "ItemRef": {
      //           "name": "Services", 
      //           "value": "1"
      //         }
      //       }
      //     }
      //   ], 
      //   "CustomerRef": {
      //     "value": "67"
      //   }
      // }';
      
    //  $payload ='{
    //     "TxnDate": "2014-09-19", 
    //     "domain": "QBO", 
    //     "PrintStatus": "NeedToPrint", 
    //     "SalesTermRef": {
    //       "value": "3"
    //     }, 
    //     "TotalAmt": "'.$billings->total_amount.'", 
    //     "Line": "'.$line_json.'", 
    //     "DueDate": "2014-10-19", 
    //     "ApplyTaxAfterDiscount": false, 
    //     "DocNumber": "1037", 
    //     "sparse": false, 
    //     "CustomerMemo": {
    //       "value": "Thank you for your business and have a great day!"
    //     }, 
    //     "Deposit": 0, 
    //     "Balance": 362.07, 
    //     "CustomerRef": {
    //       "name": "Sonnenschein Family Store", 
    //       "value": "24"
    //     }, 
    //     "TxnTaxDetail": {
    //       "TxnTaxCodeRef": {
    //         "value": "2"
    //       }, 
    //       "TotalTax": 26.82, 
    //       "TaxLine": [
    //         {
    //           "DetailType": "TaxLineDetail", 
    //           "Amount": 26.82, 
    //           "TaxLineDetail": {
    //             "NetAmountTaxable": 335.25, 
    //             "TaxPercent": 8, 
    //             "TaxRateRef": {
    //               "value": "3"
    //             }, 
    //             "PercentBased": true
    //           }
    //         }
    //       ]
    //     }, 
    //     "SyncToken": "0", 
    //     "LinkedTxn": [
    //       {
    //         "TxnId": "100", 
    //         "TxnType": "Estimate"
    //       }
    //     ], 
    //     "BillEmail": {
    //       "Address": "Familiystore@intuit.com"
    //     }, 
    //     "ShipAddr": {
    //       "City": "Middlefield", 
    //       "Line1": "5647 Cypress Hill Ave.", 
    //       "PostalCode": "94303", 
    //       "Lat": "37.4238562", 
    //       "Long": "-122.1141681", 
    //       "CountrySubDivisionCode": "CA", 
    //       "Id": "25"
    //     }, 
    //     "EmailStatus": "NotSet", 
    //     "BillAddr": {
    //       "Line4": "Middlefield, CA  94303", 
    //       "Line3": "5647 Cypress Hill Ave.", 
    //       "Line2": "Sonnenschein Family Store", 
    //       "Line1": "Russ Sonnenschein", 
    //       "Long": "-122.1141681", 
    //       "Lat": "37.4238562", 
    //       "Id": "95"
    //     },
    //     "Id": "67",
    // }';

    if($billings->tax==0)
    {
      $payload= '{"Line": '.$line_json.',"CustomerRef": {"value": "67"},"CurrencyRef": {"value": "INR"},"DocNumber": "","SyncToken": "0" }';
    }
    else
    {
      $payload= '{"Line": '.$line_json.',"CustomerRef": {"value": "67"},"CurrencyRef": {"value": "INR"},"DocNumber": "","TxnTaxDetail": {"TxnTaxCodeRef": {"value": "5"}},"SyncToken": "0" }';
    }
   
      
      // echo"<pre>";
      // print_r($payload);
      // die;

      //  $payload_test= str_replace("\n","",$payload);
      // $json = json_encode($payload);
      // $array_data = json_decode($json,TRUE);
      // dd($array_data);
      $apiURL = "https://sandbox-quickbooks.api.intuit.com/v3/company/4620816365207903590/invoice?minorversion=63";

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt ($ch, CURLOPT_POST, 1);
      $authorization = "Authorization: Bearer ".$accessToken->getAccessToken(); // Prepare the authorisation token
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
      curl_setopt($ch, CURLOPT_URL, $apiURL);
      // Attach encoded JSON string to the POST fields
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      $resp = curl_exec ($ch);
      curl_close ($ch);
      dd($resp);
      $xml = simplexml_load_string($resp);
      $json = json_encode($xml);
      $array_data = json_decode($json,TRUE);
      dd($array_data);

    }
}
