<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
| 
*/


Route::group([ 'namespace' => 'API\Client', 'prefix' => 'client','middleware' => 'auth:api' ], function(){

   
    Route::post('candidate/upload', 'CandidateController@candidateStore');
    Route::get('sla','CandidateController@get_sla_list');
    Route::get('candidates/jaf', 'CandidateController@candidateJaf');
    Route::post('file/upload', 'CandidateController@fileUpload');
    Route::post('jaf/upload', 'CandidateController@JafUpload');
    Route::get('reports', 'ReportController@index');
    Route::post('reports/download', 'ReportController@exportFullReport');
    Route::post('report/download', 'ReportController@exportReport');
    //handle the not found error 
    Route::fallback(function(){
       return response()->json([
           'message' => 'URL not found, If error persists, contact to your api provider'], 404);
   });
});


//auth:api

Route::group([ 'namespace' => 'API', 'prefix' => 'v1','middleware' => 'auth:api' ], function(){

    Route::post('/user', 'CandidateController@list');
    
    //
    Route::post('candidates/account/sendSMSOTP', 'CandidateController@sendSMSOTP');
    Route::post('candidates/account/verifySMSOTP', 'CandidateController@verifySMSOTP');
    Route::get('candidates/profile', 'CandidateController@profile');

    //verification form

    Route::post('candidates/verification/jaf/form', 'JafController@candidateJafForm');
    Route::post('candidates/verification/jaf/form/save', 'JafController@candidateJafFormSave');

    //address data
    Route::get('candidates/verification/address/form', 'CandidateController@address_verification');
    Route::post('candidates/verification/address', 'CandidateController@saveVerificationAddress');
    Route::get('candidates/verification/address/data', 'CandidateController@get_address_verification_data');

    Route::get('candidates/verification/aadhar', 'CandidateController@address_verification');

    //status : all, verified, pending
    Route::get('candidates/verifications/{status}', 'CandidateController@list');

    Route::post('candidates/store/address', 'CandidateController@addressSave');

    //State List
    Route::get('states/', 'CandidateController@stateList');

    Route::get('addresstypelist/', 'CandidateController@get_address_type_verification');

    Route::post('candidates/addresstypelist', 'CandidateController@get_candidate_address_type_verification');

    Route::post('candidates/addressfileupload', 'CandidateController@addressFileUpload');

    Route::post('candidates/addressfiledelete', 'CandidateController@addressFileDelete');

    Route::post('/test-upload','AppController@index');

    //handle the not found error 
    Route::fallback(function(){
       return response()->json([
           'message' => 'URL not found, If error persists, contact to your api provider'], 404);
   });


});


Route::group([ 'namespace' => 'API\AddressVerification\V1', 'prefix' => 'v1','middleware' => 'auth:api' ], function(){

    // Route::post('/user', 'CandidateController@list');
    
    //
    // Route::post('candidates/account/sendSMSOTP', 'CandidateController@sendSMSOTP');
    // Route::post('candidates/account/verifySMSOTP', 'CandidateController@verifySMSOTP');
    // Route::get('candidates/profile', 'CandidateController@profile');

  
    //Login 
    Route::post('login/account/sendSMSOTP', 'Login\LoginController@sendSMSOTP');
    Route::post('login/account/verifySMSOTP', 'Login\LoginController@verifySMSOTP');

    //Candidate 
    Route::post('candidate/verificationAddress', 'Candidates\CandidateController@verificationAddress');
    Route::post('candidate/store/address', 'Candidates\CandidateController@addressSave');

    //Vendor 
    Route::post('vendor/verificationAddress', 'Vendor\VendorController@verificationAddress');
    Route::post('vendor/verification/start', 'Vendor\VendorController@verificationStart');
    Route::post('vendor/verification/end', 'Vendor\VendorController@verificationEnd');
    Route::post('vendor/store/address', 'Vendor\VendorController@addressSave');


    //handle the not found error 
    Route::fallback(function(){
       return response()->json([
           'message' => 'URL not found, If error persists, contact to your api provider'], 404);
   });


});

Route::group(['domain' => Config::get('app.bws_url'), 'namespace' => 'API\V1\InstantVerification', 'prefix' => 'instant-verification/v1','middleware' => 'auth:api' ], function(){

    Route::post('idcheck/aadhar','VerificationController@idCheckAadhar');

    Route::post('idcheck/pan','VerificationController@idCheckPan');

    Route::post('idcheck/voterid','VerificationController@idCheckVoterID');

    Route::post('idcheck/rc','VerificationController@idCheckRC');

    Route::post('idcheck/passport','VerificationController@idCheckPassport');

    Route::post('idcheck/driving','VerificationController@idCheckDL');

    Route::post('idcheck/gstin','VerificationController@idCheckGSTIN');

    Route::post('idcheck/bankaccount','VerificationController@idCheckBankAccount');

    Route::post('idcheck/telecom','VerificationController@idTelecomCheck');

    Route::post('idcheck/verify_telecom','VerificationController@idVerifyTelcomCheck');

    Route::post('idcheck/covid19_generateotp','VerificationController@idCovid19OTPCheck');

    Route::post('idcheck/covid19_verifyotp','VerificationController@idCovid19VerifyOTPCheck');

    Route::post('idcheck/covid19_refcheck','VerificationController@idVerifyCovid19RefCheck');

    //handle the not found error
    Route::fallback(function(){
       return response()->json([
           'message' => 'URL not found, If error persists, contact to your api provider'], 404);
   });


});

//updating to V2
Route::group([ 'namespace' => 'API\V2', 'prefix' => 'v2','middleware' => 'auth:api' ], function(){

    Route::post('/user', 'CandidateController@list');

    //handle the not found error
    Route::fallback(function(){
       return response()->json([
           'message' => 'URL not found, If error persists, contact to your api provider'], 404);
   });


});