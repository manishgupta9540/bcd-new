<?php

use App\Http\Controllers\Admin\TaskController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//
// Route::view('/guest-home','guest.home_new');
// Route::view('/guest_register','main-web.guest-register');

Route::get('/','AppController@index')->name('/');

Route::get('instantchecks/login','AppController@micrositeLogin')->name('instantchecks');
Route::get('instantchecks/signup','AppController@micrositeSignUp')->name('/instantchecks-signup');

Route::post('logout/microsite', 'AppController@logoutMicrosite')->name('logout/microsite');

Route::post('instantuserregister','AppController@instantUserRegister')->name('instantuserregister');

// login with socialight
Route::get('auth/google', 'AppController@googleRedirect');
Route::get('/login/google/callback', 'AppController@loginWithGoogle')->name('login.google-callback');

//login with sociolight outlook
Route::get('/redirectoutlook', 'AppController@outllokRedirect');

// Route::get('/test_url',function(){
// 	return redirect('https://play.google.com/store/apps/details?id=com.myBCDVerification');
// });
Route::get('/signup','AppController@signup')->name('/signup');
// Route::post('/signup/store','AppController@save_signup')->name('/signup/store');
Route::get('/thank-you','AppController@thank_you')->name('/thank-you');
// Route::get('/pricing','AppController@pricing')->name('/pricing');
// Route::get('/checkout','AppController@checkout')->name('/checkout');
Route::get('/contactsales', 'AppController@contact');

Route::post('/user_loggedout', 'AppController@loggedOut');
Route::get('/signout', 'AppController@SignOut');
// Route::view('/payment_success_demo', 'payment-success_demo');
Route::get('/testApi','AppController@testApi');
Route::get('/demopdfreport','AppController@demoPDFReport');

Route::get('/panpdfreport','AppController@panPDFReport');

Route::get('/voterpdfreport','AppController@voterPDFReport');

Route::get('/rcpdfreport','AppController@rcPDFReport');

Route::get('/passportpdfreport','AppController@passportPDFReport');

Route::get('/dlpdfreport','AppController@dlPDFReport');

Route::get('/bankpdfreport','AppController@bankPDFReport');

// Route::get('/ecourtdemo','AppController@eCourtPDFReport');

Route::get('/ecourtpdfreport','AppController@eCourtSamplePDFReport');

Route::get('/upipdfreport','AppController@upiPDFReport');

Route::get('/cinpdfreport','AppController@cinPDFReport');

Route::get('/docsreport','DocsController@index')->name('/docsreport');

Route::get('/demoinvoice','AppController@demoInvoice');

Route::post('/contactstore', 'AppController@contactStore')->name('/contactstore');

Route::get('/terms', 'AppController@terms');

Route::get('/privacy-policy', 'AppController@privacyPolicy');

Route::get('/email-verify/','AppController@verify_email_link')->name('/email-verify');

Route::get('/forgot-password','AppController@forgotPassword')->name('/forgot-password');

Route::get('pay', 'PaymentController@pay')->name('pay');
Route::post('payment', 'PaymentController@payment')->name('payment');

Route::get('/plan/create', 'PaymentController@createPlan')->name('/plan/create');
Route::get('/plan/subscription/create', 'PaymentController@createSubscription')->name('/plan/subscription/create');

//test mail
// Route::get('/testMail', 'AppController@testMail')->name('/testMail');

Route::post('/userAuthenticate', 'AppController@userAuthenticate')->name('/userAuthenticate');
Route::post('/forget/password/email', 'AppController@forgetPasswordPopup')->name('/forget/password/email');
Route::post('/candidate/forget/password/ref', 'AppController@forgetPasswordRef')->name('/candidate/forget/password/ref');
//verify otp
Route::post('/verfiy_otp','AppController@verifyOtp')->name('/verify_otp');

Route::get('/forget/password/{id}/{token_no}', 'ForgetPasswordController@create')->name('/forget/password');
Route::post('/forget/password/update', 'ForgetPasswordController@update')->name('/forget/password/update');

Route::get('/candidate/forget/password/{id}/{token_no}', 'ForgetPasswordController@candidateCreate')->name('/candidate/forget/password');
Route::post('/candidate/forget/password/update', 'ForgetPasswordController@candidateUpdate')->name('/candidate/forget/password/update');

// Route::get('/guest/create','AppController@guest_create');

Route::get('/startverification','AppController@guest_create');

Route::post('/guest/store','AppController@guest_store')->name('/guest/store');

// Route::match(['get','post'],'/account_verification/{id}','AppController@verifyAccount')->name('/account_verification');

// Route::post('/account/resendotp','AppController@resendOTP')->name('/account/resendotp');

// Route::post('/account/mobileverify','AppController@mobileAccountVerify')->name('/account/mobileverify');

Route::get('/email_verification/{id}','AppController@emailverification')->name('/email_verification');

Route::get('/email_verify/','AppController@verifyEmailLink')->name('/email_verify');

Route::get('/thank-you-account_verify','AppController@thankyouemail')->name('/thank-you-account_verify');

Route::post('/login_activity','AppController@loginActivity')->name('/login_activity');

Route::get('QR-Code-Instant-Verification','AppController@qrCode')->name('QR-Code-Instant-Verification');

// Route::get('/guest-purge-notify','AppController@guestPurgeNotify')->name('/guest-purge-notify');

//Route::match(['get','post'],'/test-file-upload','AppController@testUpload')->name('/test-file-upload');

Route::get('/add/{code}','AppController@shortenLink')->name('/add');

Route::match(['get','post'],'/address-verification-form/{id}','AppController@addressVerificationForm')->name('/address-verification-form');

Route::post('/error-geolocation','AppController@errorGeoLocation')->name('/error-geolocation');

Route::post('/address-form-ajax','AppController@addressFormAjax')->name('/address-form-ajax');

Route::get('/geo-demo','AppController@geoDemo')->name('/geo-demo');

Route::post('/address-form-file-upload','AppController@addressVerificationFileUpload')->name('/address-form-file-upload');

Route::post('/address-form-file-delete','AppController@addressVerificationFileDelete')->name('/address-form-file-delete');

Route::get('/clear', function() {
	Artisan::call('cache:clear');
	Artisan::call('config:clear');
	// Artisan::call('config:cache');
	Artisan::call('view:clear');
	// Artisan::call('route:clear');
	return "Cleared!";
 });

 Route::get('/aws_s3_client_demo','AppController@awsS3ClientDemo')->name('/aws_s3_client_demo');

//
Auth::routes();

Route::match(['get','post'],'/user_login', 'AppController@login')->middleware('guest:candidate')->name('/user_login');

Route::match(['get','post'],'/candidateInsuffForm/{id}','AppController@candidateInsuffForm')->name('/candidateInsuffForm');

Route::match(['get','post'],'/candidateDocumentForm/{id}','AppController@candidateDocumentForm')->name('/candidateDocumentForm');
 
//superadmin

Route::group(['prefix'=> 'app','domain' => Config::get('app.superadmin_url'), 'namespace' => 'Superadmin', 'middleware' => ['auth:web','throttle:60,1'] ], function(){

	Route::get('/home','HomeController@index')->name('/home');
	
	//Route::get('/','HomeController@index')->name('/');

	Route::get('/apiUsageDate','AppController@apiUsageDate')->name('/apiUsageDate');

	Route::get('/testdata','AppController@report_list')->name('/testdata');

	Route::get('/calender','AppController@googleCalender')->name('/calender');

	Route::get('/update_client_name','AppController@updateClientName')->name('/update_client_name');

	Route::get('/complete_date_report','AppController@completeDateReportUpdate')->name('/complete_date_report');

	Route::get('/billing_price_update','AppController@billingPriceUpdate')->name('/billing_price_update');

	Route::get('/jaf_reference_type_update','AppController@jafReferenceTypeUpdate')->name('/jaf_reference_type_update');

	Route::get('/report_reference_type_update','AppController@reportReferenceTypeUpdate')->name('/report_reference_type_update');

	Route::get('/update_client_display_id','AppController@updateClientDisplayId')->name('/update_client_display_id');

	Route::get('/update_user_display_id','AppController@updateUserDisplayId')->name('/update_user_display_id');

	Route::get('/update_customer_display_id','AppController@updateCustomerDisplayId')->name('/update_customer_display_id');

	Route::get('/update_user_name','AppController@update_user_name')->name('/update_user_name');


	Route::get('/update_extra_space_name','AppController@update_extra_space_name')->name('/update_extra_space_name');

	Route::get('/update_fake_employment','AppController@updateFakeEmployment')->name('/update_fake_employment');

	Route::get('/update_fake_educational','AppController@updateFakeEducational')->name('/update_fake_educational');

	Route::get('/update_gen_employment','AppController@updateGenEmployment')->name('/update_gen_employment');

	Route::get('/update_gen_educational','AppController@updateGenEducational')->name('/update_gen_educational');

	//setting 
	Route::get('/settings/general','AppController@index')->name('/settings/general');
	Route::get('/settings/sla','AppController@sla')->name('/settings/sla');
	Route::get('/settings/sla/create','AppController@sla_create')->name('/settings/sla/create');
	Route::post('/settings/sla/save','AppController@sla_save')->name('/settings/sla/save');
	Route::get('/settings/sla/edit/{id}','AppController@sla_edit')->name('/settings/sla/edit');
	Route::post('/settings/sla/update','AppController@sla_update')->name('/settings/sla/update');
	Route::get('/settings/jaf','AppController@jaf')->name('/settings/jaf');
	Route::get('/setting','AppController@index')->name('/setting');
	Route::get('/settings/university','AppController@sla')->name('/settings/university');
	

	Route::get('/settings/checkprice','AppController@checkPriceMaster')->name('/settings/checkprice');
	Route::post('/settings/checkprice/update','AppController@checkPriceUpdate')->name('/settings/checkprice/update');

	Route::post('/settings/checkprice/store','AppController@checkPriceStore')->name('/settings/checkprice/store');

	Route::get('/settings/billing','AppController@billing')->name('/settings/billing');
	Route::get('/settings/billing/details/{id}','AppController@billing_details')->name('/settings/billing/details');
	
	// Route::get('/services/list','AppController@index')->name('/services/list');

	Route::post('/company/upload/logo', 'AppController@uploadCompanyLogo')->name('/company/upload/logo');

    //Route::get('/', function(){ return Redirect::to('/home'); });
	//Route::any('{all}', function($uri){ return Redirect::to('/404'); })->where('all', '.*');

	//services
	Route::post('/services/new/save','ServiceController@save_service')->name('/services/new/input');
	Route::match(['get', 'post'],'/services/edit', 'ServiceController@serviceEdit')->name('/services/edit');
	
	Route::get('/verifications','ServiceController@index')->name('/verifications');
	Route::get('/verifications/view/{id}','ServiceController@view')->name('/verifications/view');
	Route::post('/services/form-input','ServiceController@save_form_input')->name('/services/form-input');

	Route::post('/services/formInput/edit/', 'ServiceController@serviceFormInputEdit')->name('/services/formInput/edit');
	Route::post('/services/formInput/update', 'ServiceController@serviceFormInputUpdte')->name('/services/formInput/update');
	

	//setting 
	Route::get('/settings/general','AppController@index')->name('/settings/general');
	Route::get('/settings/sla','AppController@sla')->name('/settings/sla');
	Route::get('/settings/jaf','AppController@jaf')->name('/settings/jaf');
	Route::get('/settings/profile','AppController@profile')->name('/settings/profile');
	Route::post('/settings/profile/update','AppController@update_profile')->name('/settings/profile/update');

	Route::get('/settings/promocode','AppController@promocode')->name('/settings/promocode');

	Route::get('/settings/promocode/create','AppController@promoCreate')->name('/settings/promocode/create');

	Route::post('/settings/promocode/store','AppController@promoStore')->name('/settings/promocode/store');

	Route::get('/settings/promocode/edit/{id}','AppController@promoEdit')->name('/settings/promocode/edit');

	Route::post('/settings/promocode/update','AppController@promoUpdate')->name('/settings/promocode/update');

	Route::post('/settings/promocode/delete','AppController@promoDelete')->name('/settings/promocode/delete');

	Route::post('/settings/promocode/status','AppController@promoStatus')->name('/settings/promocode/status');

	Route::get('/settings/holiday','AppController@holidays')->name('/settings/holiday');

	Route::post('/settings/holiday/store','AppController@holidayStore')->name('/settings/holiday/store');

	Route::match(['get','post'],'/settings/holiday/edit','AppController@holidayEdit')->name('/settings/holiday/edit');

	Route::post('/settings/holiday/delete','AppController@holidayDelete')->name('/settings/holiday/delete');

	Route::post('/settings/holiday/status','AppController@holidayStatus')->name('/settings/holiday/status');

	//account
	// Route::get('/profile','CustomerController@create')->name('/profile');
	Route::get('/change-password','UserController@changePassword')->name('/change-password');
	Route::post('/updatePassword','UserController@updatePassword')->name('/updatePassword');

	// Customers
	Route::get('/customers','CustomerController@index')->name('/customers');
	Route::get('/customers/create','CustomerController@create')->name('/customers/create');
	Route::post('/customers/store','CustomerController@store')->name('/customers/store');
	Route::get('/customers/show/{id}','CustomerController@show')->name('/customers/show');
	Route::get('/customers/jobs/{id}','CustomerController@jobs')->name('/customers/jobs');
	Route::get('/customers/sla/{id}','CustomerController@slas')->name('/customers/sla');
	Route::get('/candidates/sla/{id}/{old_id}','CustomerController@CandidateSlas')->name('/candidates/sla');
	Route::get('/customers/payments/{id}','CustomerController@payments')->name('/customers/payments');
	Route::get('/customers/checks/{id}','CustomerController@payments')->name('/customers/checks');
	Route::get('/customers/edit/{id}','CustomerController@edit')->name('/customers/edit');
	Route::post('/customers/update','CustomerController@update')->name('/customers/update');
	Route::get('/candidate/show/{id}/{old_id}','CustomerController@candidateShow')->name('/candidate/show');
	Route::get('/customers/reports/show/{id}','CustomerController@reportShow')->name('/customers/reports/show');
	Route::get('/customers/reports/edit/{old_id}/{id}','CustomerController@reportEdit')->name('/customers/reports/edit');
	Route::post('/customers/reports/update','CustomerController@reportUpdate')->name('/customers/reports/update');
	Route::get('/candidate/reports/show/{id}','CustomerController@candidateReportShow')->name('/candidate/reports/show');
	Route::get('/customers/report/pdf/{id}','CustomerController@customerFullReport')->name('/customers/report/pdf');
	Route::get('/customers/reports/setData','CustomerController@setSessionData')->name('/customers/reports/setData');
	Route::get('/customers/report-generate/{id}','CustomerController@generateReport')->name('/customers/report-generate');
	Route::post('/customers/candidates/getlist','CustomerController@getCandidatesList')->name('/customers/candidates/getlist');

	
	// Contacts
	Route::get('/contacts','ContactController@index')->name('/contacts');
	Route::get('/contacts/my','ContactController@index')->name('/contacts/my');
	Route::get('/contacts/create','ContactController@create')->name('/contacts/create');
	Route::post('/contacts/store','ContactController@store')->name('/contacts/store');
 
	// Jobs
	Route::get('/jobs','JobController@index')->name('/jobs');
	Route::get('/job/import','JobController@importExcel')->name('/job/import');
	Route::post('/job/store/excel','JobController@storeExcelData')->name('/job/store/excel');
	Route::get('/job/create','JobController@createjob')->name('/job/create');
	Route::post('/job/store','JobController@store')->name('/job/store');

  
	// candidates
	Route::get('/candidates','CandidateController@index')->name('/candidates');
	Route::get('/candidates/show/{id}','CandidateController@show')->name('/candidates/show');

	// Report Mis
	Route::get('/qcs','QcsController@report_mis')->name('/qcs');
	Route::post('/report/store','QcsController@store')->name('/report/store');
	Route::get('/getjobdetails','QcsController@show');
	Route::get('confirmationQc/{id}','QcsController@confirmationQc')->name('/confirmationQc');

	// candidates
	Route::get('/subscriptions','SubscriptionController@index')->name('/subscriptions');
	Route::get('/subscriptions/create','SubscriptionController@create')->name('/subscriptions/create');
	Route::post('/subscriptions/store','SubscriptionController@store')->name('/subscriptions/store');
	Route::get('/subscriptions/edit/{id}','SubscriptionController@edit')->name('/subscriptions/edit');
	Route::post('/subscriptions/update','SubscriptionController@update')->name('/subscriptions/update');

	Route::post('/customers/getstate','CustomerController@getstate')->name('/customers/getstate');
	Route::post('/customers/getcity','CustomerController@getcity')->name('/customers/getcity');
	//roles
	Route::get('/roles','RoleController@index')->name('/roles');
	Route::get('/roles/create','RoleController@create')->name('/roles/create');
	Route::post('/roles/store','RoleController@store')->name('/roles/store');


	Route::resource('users','UserController');
	Route::get('/users','UserController@index')->name('/users');
	Route::get('/users/edit/{id}','UserController@edit')->name('/users/edit');
	Route::post('/users/update/{id}','UserController@update')->name('/users/update');
	Route::post('/users/store','UserController@store')->name('/users/store');

	//vendors
	Route::get('/vendor','VendorController@index')->name('/vendor');
	Route::get('/vendor/create','VendorController@create')->name('/vendor/create');
	Route::get('/vendor/edit/{id}','VendorController@edit')->name('/vendor/edit');
	Route::post('/vendor/save','VendorController@save')->name('/vendor/save');
	Route::post('/vendor/update','VendorController@update')->name('/vendor/update');

	Route::get('/guest/default','GuestController@index')->name('/guest/default');
	Route::post('/guest/status','GuestController@guestStatus')->name('/guest/status');
	Route::post('/guest/delete','GuestController@destroy')->name('/guest/delete');

	Route::post('/guest/resend_mail','GuestController@resendMail')->name('/guest/resend_mail');

	Route::get('/guest/order','GuestController@orders')->name('/guest/order');
	Route::get('/guest/order/details/{id}','GuestController@orderDetails')->name('/guest/order/details');
	Route::post('/guest/order/details/data','GuestController@orderDetailsData')->name('/guest/order/details/data');

	Route::get('/guest/help','GuestController@help')->name('/guest/help');
	Route::get('/guest/help/response/{id}','GuestController@requestResolve')->name('/guest/help/response');
	Route::post('/guest/help/update/{id}','GuestController@resolveStore')->name('/guest/help/update');

	Route::get('/guest/checkprice','GuestController@checkPrice')->name('/guest/checkprice');

	Route::match(['get','post'],'/guest/checkprice/edit','GuestController@checkPriceEdit')->name('/guest/checkprice/edit');

	//
	Route::get('pdf-generate/{id}','PDFController@PDFgenerate');

	Route::get('/user_business/update','AppController@user_businesses')->name('/app/user_business/update');





});

//admin or customer routes
//redirect if business info is not completed
Route::get('/business-info', 'AppController@business_info')->name('/business-info');
Route::post('/business-info', 'AppController@business_info_save')->name('/business-info');



Route::group(['domain' => Config::get('app.admin_url'), 'namespace' => 'Admin', 'middleware' => [ 'auth:web','businessProfile','sessionOut','throttle:60,1'] ], function(){

	// Route::get('/password', function () {
	// 	$password = Hash::make('1234');
	// 	return $password;
	// });

	//Route::get('/testnotify','AppController@testNotification')->name('/testnotify');

	//Route::get('/testRekrut','AppController@testValidateRekrut')->name('/testRekrut');

	Route::get('/s3_file','AppController@s3FileUpload');

	Route::get('/user_detail_update','AppController@user_updates')->name('/user_detail_update');
	Route::get('/jaf-form','JafController@jaf_form')->name('/jaf-form');
	Route::post('/jaf/store','JafController@jaf_store')->name('/jaf/store');

	Route::get('/fileAttachDeletion','AppController@fileAttachDeletion');
	Route::get('/updateservice/coc','AppController@checkControllerCOC');

	Route::get('/guestPayReport','AppController@guestPayReport');

	Route::get('/sla_display_id','AppController@slaDisplayId');

	Route::get('/userPasswordUpdate','AppController@userPasswordUpdate');
	//Route::get('/updateTaskStatus','AppController@updateTaskStatus');

	Route::get('/updateS3Attach','AppController@updateS3Attach');

	Route::get('/candidateReinitateTransfer','AppController@candidateReinitateTransfer');

	Route::get('/candidateDeletion','AppController@candidateDeletion');

	Route::get('/reportItemCreate','AppController@reportItemCreate');

	Route::match(['get','post'],'/sendLinkImport','AppController@sendLinkImport');

	//Route::get('/report_status_update','AppController@reportStatusUpdate')->name('/report_status_update');

	Route::get('/bulkReassignUser','AppController@bulkReassignUser');

	//frnid route
	Route::get('frnidIdmport','AppController@frnidIdmport');
	Route::post('excelfileimport','AppController@excelfileimport')->name('excelfileimport');

	//jaf item export
	Route::post('/jaf-export','CandidateController@export')->name('/jaf-export');

	Route::post('/mis-export','ExcelController@misExport')->name('/mis-export');

	Route::post('/ops-export','ExcelController@opsExport')->name('/ops-export');

	Route::get('/error-404-data','AppController@noDatafound')->name('/error-404-data');

	Route::post('/bulk/ready-to-report','CandidateController@readyToReport')->name('/bulk/ready-to-report');

	Route::post('/reference_number_export','CandidateController@referenceNumberExport')->name('/reference_number_export');
	//For export sales excel
	Route::post('/sales-tracker','ExcelController@salesTracker')->name('/sales-tracker');

	Route::get('/sales-dashboard','ExcelController@salesDashboard')->name('/sales-dashboard');

	Route::post('/sales-export','ExcelController@salesExport')->name('/sales-export');

	Route::get('/progress-dashboard','ExcelController@progressDashboard')->name('/progress-dashboard');

	Route::post('/progress-dashboard/total-hours-card','ExcelController@totalHoursCard')->name('/progress-dashboard/total-hours-card');

	Route::post('/progress-dashboard/jaf-task-card','ExcelController@jafTaskCard')->name('/progress-dashboard/jaf-task-card');

	Route::post('/progress-dashboard/verification-task-card','ExcelController@verificationTaskCard')->name('/progress-dashboard/verification-task-card');

	Route::post('/progress-dashboard/insuff-card','ExcelController@insuffCard')->name('/progress-dashboard/insuff-card');


	Route::post('/progress-export','ExcelController@progressExport')->name('/progress-export');

	Route::post('/progress-data-export','ExcelController@progressDataExport')->name('/progress-data-export');

	Route::get('/daily-data-export','ExcelController@dailyExcelReport')->name('/daily-data-export');

	Route::post('/progress/preview','ExcelController@progressPreview')->name('/progress/preview');

	Route::get('/master-dashboard','ExcelController@masterDashboard')->name('/master-dashboard');

	// Route::get('/path_ex','AppController@path_ex')->name('/path_ex');

	//excel format download
	// Route::get('/excel-format','CandidateController@excel')->name('/excel-format');

	//jaf all item export
	Route::get('/all-jaf-export','CandidateController@allExport')->name('/all-jaf-export');

	//QuickBook
	Route::get('/quickbook/connect','QuickBookController@index')->name('/quickbook/connect');
	Route::get('/quickbook/api/call','QuickBookController@apiCall')->name('/quickbook/api/call');
	Route::post('/quickbook/refresh/token','QuickBookController@refreshToken')->name('/quickbook/refresh/token');
	Route::get('/quickbook/callback','QuickBookController@processCode')->name('/quickbook/callback');
	Route::get('/quickbook/customers/list','QuickBookController@customerList')->name('/quickbook/customers/list');
	Route::get('/quickbook/customer/add/{id}','QuickBookController@customerCreate')->name('/quickbook/customer/add');
	Route::get('/quickbook/customers/invoice/{id}','QuickBookController@quickbookInvoice')->name('/quickbook/customers/invoice');


	//setting 
	Route::get('/settings/general','AppController@index')->name('/settings/general');
	Route::post('/settings/reportConfig','AppController@updateReportConfig')->name('/settings/reportConfig');
	Route::post('/settings/candidateConfig','AppController@updateCandidateConfig')->name('/settings/candidateConfig');
	Route::post('/settings/mailConfig','AppController@updateMailConfig')->name('/settings/mailConfig');

	// Route::get('/settings/sla','AppController@sla')->name('/settings/sla');
	// Route::get('/settings/sla/create','AppController@sla_create')->name('/settings/sla/create');
	Route::post('/settings/sla/save','AppController@sla_save')->name('/settings/sla/save');
	Route::get('/settings/jaf','AppController@jaf')->name('/settings/jaf');
	Route::get('/setting','AppController@index')->name('/setting');
	Route::get('/settings/sla/edit/{id}','AppController@sla_edit')->name('/settings/sla/edit');
	Route::post('/settings/sla/update','AppController@sla_update')->name('/settings/sla/update');
	Route::get('/settings/sla/view/{id}','AppController@sla_view')->name('/settings/sla/view');


	//sla export-data 
	Route::get('/sla-export-data/{id}','AppController@slaExportData')->name('/sla-export-data');
	Route::get('/csv-export-data/{id}','AppController@csvExportData')->name('/csv-export-data');

	Route::get('/updateCandidates','AppController@updateCandidateID')->name('/updateCandidates');

	Route::post('/company/upload/logo', 'AppController@uploadCompanyLogo')->name('/company/upload/logo');
	//
	Route::get('/home', 'HomeController@index')->name('/home');

	//Route::post('/home/render','HomeController@dashboardCardRender')->name('/home/render');

	Route::post('/home/dashboard-customer-card','HomeController@dashboardCustomerCard')->name('/home/dashboard-customer-card');

	//Route::post('/home/dashboard-candidate-card','HomeController@dashboardCandidateCard')->name('/home/dashboard-candidate-card');

	Route::post('/home/dashboard-candidate-count-card','HomeController@dashboardCandidateCountCard')->name('/home/dashboard-candidate-count-card');

	Route::post('/home/dashboard-candidate-jaf-customer-count-card','HomeController@dashboardCandidateJafCustomerCountCard')->name('/home/dashboard-candidate-jaf-customer-count-card');

	Route::post('/home/dashboard-candidate-jaf-coc-count-card','HomeController@dashboardCandidateJafCocCountCard')->name('/home/dashboard-candidate-jaf-coc-count-card');

	Route::post('/home/dashboard-candidate-jaf-candidate-count-card','HomeController@dashboardCandidateJafCandidateCountCard')->name('/home/dashboard-candidate-jaf-candidate-count-card');

	Route::post('/home/dashboard-jaf-card','HomeController@dashboardJafCard')->name('/home/dashboard-jaf-card');

	Route::post('/home/dashboard-check-card','HomeController@dashboardCheckCard')->name('/home/dashboard-check-card');

	Route::post('/home/dashboard-report-card','HomeController@dashboardReportCard')->name('/home/dashboard-report-card');

	Route::post('/home/check/render','HomeController@dashboardCheckRender')->name('/home/check/render');

	Route::post('/home/team-lead/render','HomeController@dashboardTeamLeadRender')->name('/home/team-lead/render');

	Route::post('/home/team-lead-jaf-fill/render','HomeController@dashboardTeamLeadJafFill')->name('/home/team-lead-jaf-fill/render');

	Route::post('/home/team-lead-ver/render','HomeController@dashboardTeamLeadVer')->name('/home/team-lead-ver/render');

	Route::post('/home/team-lead-report/render','HomeController@dashboardTeamLeadReport')->name('/home/team-lead-report/render');

	Route::post('/home/dashboard-allotted-task-card','HomeController@dashboardAllottedTask')->name('/home/dashboard-allotted-task-card');

	Route::post('/home/dashboard-completed-task-card','HomeController@dashboardCompletedTask')->name('/home/dashboard-completed-task-card');

	Route::post('/home/dashboard-pending-task-card','HomeController@dashboardPendingTask')->name('/home/dashboard-pending-task-card');

	Route::post('/home/dashboard-insuff-task-card','HomeController@dashboardInsuffTask')->name('/home/dashboard-insuff-task-card');

	Route::post('/user/getlist','AppController@userList')->name('/user/getlist');
	//task assign
	Route::get('/settings/task','AppController@assignTask')->name('/settings/task');

	
	
	// Route::get('/pdfjaf','PDFController@JAFPDFgenerate');


	//accounts
	Route::get('/report/add/page','AppController@reportAdd')->name('/report/add/page');
	Route::get('/sla','AppController@sla')->name('/sla');
	Route::get('/sla/create','AppController@sla_create')->name('/sla/create');
	Route::get('/profile','UserController@profile')->name('/profile');
	Route::post('/profile/update','UserController@update_profile')->name('/profile/update');
	Route::get('/business/info','UserController@business_info')->name('/business/info');
	Route::post('/business_info/update','UserController@updateBusinessInfo')->name('/business_info/update');
	Route::get('/business/contacts','UserController@business_contacts')->name('/business/contacts');
	Route::get('/config/email','UserController@emailConfig')->name('/config/email');
	Route::post('/config/email/save','UserController@emailConfigSave')->name('/config/email/save');
	Route::post('/contact_info/update','UserController@updateContactInfo')->name('/contact_info/update');
	Route::get('/package','UserController@package')->name('/package');
	Route::get('/billing/default','BillingController@billing')->name('/billing/default');
	Route::match(['get','post'],'/billing/send_request','BillingController@billingSendRequest')->name('/billing/send_request');
	Route::match(['get','post'],'/billing/cancel_request','BillingController@billingCancelRequest')->name('/billing/cancel_request');
	Route::match(['get','post'],'/billing/status','BillingController@billingStatus')->name('/billing/status');
	Route::post('/billing/completedetails','BillingController@billingCompleteDetails')->name('/billing/completedetails');
	Route::post('/billing/actiondetails','BillingController@billingApprovalActionDetails')->name('/billing/actiondetails');
	Route::get('/billing/details/{id}','BillingController@billing_details')->name('/billing/details');
	Route::post('/billing/additional_attachment/remove_file','BillingController@billingRemoveFile')->name('/billing/additional_attachment/remove_file');
	Route::match(['get','post'],'/billing/details_edit','BillingController@billingDetailsEdit')->name('/billing/details_edit');
	Route::get('/billing/details/downloadPDF/{id}','PDFController@billingDetailsPDF')->name('/billing/details/downloadPDF');
	Route::get('/billing/config','BillingController@billingConfig')->name('/billing/config');
	Route::post('/billing/config/cocwise/store','BillingController@billingCOCWiseConfigStore')->name('/billing/config/cocwise/store');
	Route::post('/billing/config/cocwise/update','BillingController@billingCOCWiseConfigUpdate')->name('/billing/config/cocwise/update');
	Route::get('/api-usage','HomeController@userAPI')->name('/api-usage');
	Route::get('/api-usage/details/{id}','HomeController@apiDetails')->name('/api-usage/details');
	Route::post('/api-usage/download','HomeController@downloadApiDetails')->name('/api-usage/download');
	Route::get('/users/setData','UserController@setSessionData')->name('/users/setData');
	Route::get('/bulk-bill-export','PDFController@bulkBillingDetailsPDF')->name('/bulk-bill-export');
	Route::post('/billing/mailInvoice','BillingController@billingMailInvoice')->name('/billing/mailInvoice');
	Route::post('/billing/discount','BillingController@billingDiscount')->name('/billing/discount');
	Route::post('/billing/discountref','BillingController@billDiscountRef')->name('/billing/discountref');
	Route::get('/billing/sample','PDFController@billSample')->name('/billing/sample');
	Route::get('/billing/details/preview/{id}','PDFController@billingPreviewPDF')->name('/billing/details/preview');
	Route::get('/billing/details/excelExport/{id}','BillingController@billingExcelExport')->name('/billing/details/excelExport');

	Route::get('/billing/settings','BillingController@billingSetting')->name('/billing/setting');
	Route::match(['get','post'],'/billing/settings/edit','BillingController@billingSettingEdit')->name('/billing/settings/edit');

	Route::get('/billing/action','BillingController@billingAction')->name('/billing/action');
	Route::match(['get','post'],'/billing/action/edit','BillingController@billingActionEdit')->name('/billing/action/edit');

	//Config
	Route::get('/zone','ConfigController@zoneIndex')->name('/zone');
	Route::get('/zone/create','ConfigController@zoneCreate')->name('/zone/create');
	Route::post('/zone/getcity','ConfigController@getCities')->name('/zone/getcity');
	Route::post('/zone/save','ConfigController@zoneSave')->name('/zone/save');
	Route::get('/zone/edit/{id}/{name}','ConfigController@zoneEdit')->name('/zone/edit');
	Route::post('/zone/update','ConfigController@zoneUpdate')->name('/zone/update');


	//for testing
	Route::get('/testdata','AppController@report_list')->name('/testdata');

	Route::get('/calender','AppController@googleCalender')->name('/calender');

	Route::get('/bill_approve','AppController@billApprove')->name('/bill_approve');

	Route::get('/insuff_notify','AppController@insuffNotify')->name('/insuff_notify');

	Route::get('/checkprice/default','HomeController@checkPriceMaster')->name('/checkprice/default');

	Route::post('/checkprice/update','HomeController@checkPriceUpdate')->name('/checkprice/update');

	Route::get('/checkprice/customer_wise','HomeController@checkPriceCustomerWise')->name('/checkprice/customer_wise');

	Route::get('/checkprice/settings','HomeController@checkPriceSetting')->name('/checkprice/settings');

	Route::post('/checkprice/customer/store','HomeController@checkPriceCustomerStore')->name('/checkprice/customer/store');

	Route::post('/checkprice/customer_wise/update','HomeController@checkPriceCustomerUpdate')->name('/checkprice/customer_wise/update');

	Route::post('/checkprice/customer_wise/store','HomeController@checkPriceCustomerWiseStore')->name('/checkprice/customer_wise/store');

	Route::get('/checkprice/hide','HomeController@hideCheckPriceCOC')->name('/checkprice/hide');

	Route::get('/checkprice/show','HomeController@showCheckPriceCOC')->name('/checkprice/show');

	Route::get('/check/control','HomeController@checkControl')->name('/check/control');
	Route::get('/check/input/control/{id}','HomeController@serviceInputControl')->name('/check/input/control');
	Route::get('/check/customer_wise/hide','HomeController@hideServiceInputCOCWise')->name('/check/customer_wise/hide');

	//jaf control link route
	Route::get('/jafcontrollink/control','HomeController@jafcontrollinkControl')->name('/jafcontrollink/control');
	Route::get('/jaf/link/video','HomeController@jafLinkVideo')->name('/jaf/link/video');
	Route::post('/settings/jafvideolink/store','HomeController@JafVideoLinksStore')->name('/settings/jafvideolink/store');
	Route::match(['get','post'],'/jafvideo/links/edit','HomeController@jafVideoLinksEdit')->name('/jafvideo/links/edit');
	Route::post('/jaf/video/links/status','HomeController@jafVideoLinksStatus')->name('/jaf/video/links/status');

	
	//disclaimer
	Route::get('/check/control/disclaimer','HomeController@checkControlDisclaimer')->name('/check/control/disclaimer');
	Route::get('/check/input/disclaimer/{id}','HomeController@controlDisclaimer')->name('/check/input/disclaimer');
	Route::post('/save/check/disclaimer','HomeController@saveServiceDisclaimer')->name('/save/check/disclaimer');
	Route::get('/edit_discliamer_data/{id}','HomeController@editDisclaimerData')->name('edit_discliamer_data');
	Route::post('update/disclaimer','HomeController@discliamerUpdated')->name('update/disclaimer');
	Route::post('/service/disclaimer/status','HomeController@serviceDisclaimerStatus')->name('/service/disclaimer/status');

	//send link sms 
	Route::get('/send/link/sms','HomeController@sendLinkSms')->name('/send/link/sms');
	Route::post('/settings/sendlinks/store','HomeController@settingsSendLinksStore')->name('/settings/sendlinks/store');
	Route::match(['get','post'],'/send/link/sms/edit','HomeController@sendLinkSmsEdit')->name('/send/link/sms/edit');
	Route::post('/settings/send/links/status','HomeController@sendLinksStatus')->name('/settings/send/links/status');


	Route::get('/check/customer_wise/show','HomeController@showServiceInputCOCWise')->name('/check/customer_wise/show');

	Route::post('/save/check/input','HomeController@saveServiceInput')->name('/save/check/input');
	
	
	Route::get('/verification/customer_wise','HomeController@verificationCustomerWise')->name('/verification/customer_wise');

	Route::get('/verification/customer_wise/hide','HomeController@hideVerificationCOCWise')->name('/verification/customer_wise/hide');

	Route::get('/verification/customer_wise/show','HomeController@showVerificationCOCWise')->name('/verification/customer_wise/show');

	Route::get('/verification/service_wise','HomeController@verificationCustomerServiceWise')->name('/verification/service_wise');

	Route::get('/verification/service_wise/show_hide','HomeController@showhideVerificationServiceWise')->name('/verification/service_wise/show_hide');

	Route::get('/settings/holiday','HomeController@holidays')->name('/settings/holiday');

	Route::post('/settings/holiday/store','HomeController@holidayStore')->name('/settings/holiday/store');

	Route::match(['get','post'],'/settings/holiday/edit','HomeController@holidayEdit')->name('/settings/holiday/edit');

	Route::post('/settings/holiday/delete','HomeController@holidayDelete')->name('/settings/holiday/delete');

	Route::post('/settings/holiday/status','HomeController@holidayStatus')->name('/settings/holiday/status');

	Route::get('/settings/insuff_control/default','HomeController@insuffControl')->name('/settings/insuffControl/default');

	Route::post('/settings/insuff_control/store','HomeController@insuffControlStore')->name('/settings/insuff_control/store');

	Route::match(['get','post'],'/settings/insuff_control/edit','HomeController@insuffControlEdit')->name('/settings/insuff_control/edit');

	Route::post('/settings/insuff_control/status','HomeController@insuffControlStatus')->name('/settings/insuff_control/status');

	Route::get('/settings/insuff_control/report','HomeController@insuffControlReport')->name('/settings/insuffControl/report');

	Route::post('/settings/insuff_control/report/status','HomeController@insuffControlReportStatus')->name('/settings/insuffControl/report/status');

	Route::match(['get','post'],'/settings/insuff_control/report/edit','HomeController@insuffControlReportEdit')->name('/settings/insuffControl/report/edit');

	Route::post('/settings/insuff_control/report/delete','HomeController@insuffControlReportDelete')->name('/settings/insuffControl/report/delete');

	//Default report Page
	Route::get('/reports/default/report','HomeController@defaultReport')->name('/reports/default/report');
	Route::get('/reports/template3/report','HomeController@templateThreeReport')->name('/reports/template3/report');
	Route::get('/reports/template3/report/enable','HomeController@templateThreeReportShow')->name('/reports/template3/report/enable');
	Route::get('/reports/template3/report/disable','HomeController@templateThreeReportHide')->name('/reports/template3/report/disable');
	//report page add on for particular COC
	Route::get('/reports/customer_wise','HomeController@reportCustomerWise')->name('/reports/customer_wise');
	Route::get('/reports/customer_wise/disable','HomeController@hideReportCOCWise')->name('/reports/customer_wise/disable');

	Route::get('/reports/customer_wise/enable','HomeController@showReportCOCWise')->name('/reports/customer_wise/enable');

	Route::get('/reports/template4/report','HomeController@templateFourReport')->name('/reports/template4/report');
	Route::get('/reports/template4/report/enable','HomeController@templateFourReportShow')->name('/reports/template4/report/enable');
	Route::get('/reports/template4/report/disable','HomeController@templateFourReportHide')->name('/reports/template4/report/disable');

	Route::get('/reports/custom','HomeController@reportCustomPage')->name('/reports/custom');
	Route::get('/reports/custom/disable','HomeController@hideReportCustomPage')->name('/reports/custom/disable');

	Route::get('/reports/custom/enable','HomeController@showReportCustomPage')->name('/reports/custom/enable');

	Route::get('/reports/fileconfig','HomeController@reportFileConfig')->name('/reports/fileconfig');

	Route::get('/reports/address/disable','HomeController@hideReportAddress')->name('/reports/address/disable');

	Route::get('/reports/address/enable','HomeController@showReportAddress')->name('/reports/address/enable');

	Route::get('/reports/address','HomeController@reportAddress')->name('/reports/address');

	Route::post('/reports/customer/address','HomeController@customerAddAddress')->name('/reports/customer/address');

	Route::post('/reports/fileconfig/status','HomeController@reportFileConfigStatus')->name('/reports/fileconfig/status');

	Route::match(['get','post'],'/reports/fileconfig/edit','HomeController@reportFileConfigEdit')->name('/reports/fileconfig/edit');

	// FeedBack Controller   
	Route::get('/feedback','FeedbackController@feedback')->name('/feedback');

	// Help Controller
	Route::get('/help','HelpController@help')->name('/help');
	Route::get('/help/response/{id}','HelpController@requestResolve')->name('/help/response');
	Route::post('/help/update/{id}','HelpController@store')->name('/help/update');
	
	//MIS
	Route::get('/mis','MISController@mis')->name('/mis');

	// Notification
	
	Route::get('/notification/default','NotificationController@notifications')->name('/notification/default');

	Route::post('/notification/default/status','NotificationController@notificationStatus')->name('/notification/default/status');

	Route::get('/notification/setting','NotificationController@notificationSetting')->name('/notification/setting');

	Route::post('/notification/setting/status','NotificationController@notificationSettingStatus')->name('/notification/setting/status');

	Route::match(['get','post'],'/notification/setting/edit','NotificationController@notificationSettingEdit')->name('/notification/setting/edit');

	Route::post('/notification/contact/delete','NotificationController@notificationContactDelete')->name('/notification/contact/delete');

	// Notification for JAF

	Route::get('/notification/jaf/default','NotificationController@notificationJaf')->name('/notification/jaf/default');

	Route::match(['get','post'],'/notification/jaf/default/edit','NotificationController@notificationJafEdit')->name('/notification/jaf/default/edit');

	Route::post('/notification/jaf/default/status','NotificationController@notificationJafStatus')->name('/notification/jaf/default/status');

	Route::post('/notification/jaf/default/delete','NotificationController@notificationJafDelete')->name('/notification/jaf/default/delete');

	Route::get('/notification/jaf/jaf-filled','NotificationController@notificationJafFill')->name('/notification/jaf/jaf-filled');

	Route::match(['get','post'],'/notification/jaf/jaf-filled/edit','NotificationController@notificationJafFillEdit')->name('/notification/jaf/jaf-filled/edit');

	Route::post('/notification/jaf/jaf-filled/delete','NotificationController@notificationJafFillDelete')->name('/notification/jaf/jaf-filled/delete');

	Route::post('/notification/jaf/jaf-filled/status','NotificationController@notificationJafFillStatus')->name('/notification/jaf/jaf-filled/status');

	Route::get('/notification/jaf/jaf-to-candidate','NotificationController@notificationJafCandidate')->name('/notification/jaf/jaf-to-candidate');

	Route::match(['get','post'],'/notification/jaf/jaf-to-candidate/edit','NotificationController@notificationJafCandidateEdit')->name('/notification/jaf/jaf-to-candidate/edit');

	Route::post('/notification/jaf/jaf-to-candidate/status','NotificationController@notificationJafCandidateStatus')->name('/notification/jaf/jaf-to-candidate/status');

	Route::post('/notification/jaf/jaf-to-candidate/delete','NotificationController@notificationJafCandidateDelete')->name('/notification/jaf/jaf-to-candidate/delete');

	// Notification for Insuff

	Route::get('/notification/insuff/default','NotificationController@notificationInsuff')->name('/notification/insuff/default');

	Route::match(['get','post'],'/notification/insuff/default/edit','NotificationController@notificationInsuffEdit')->name('/notification/insuff/default/edit');

	Route::post('/notification/insuff/default/delete','NotificationController@notificationInsuffDelete')->name('/notification/insuff/default/delete');

	Route::post('/notification/insuff/default/status','NotificationController@notificationInsuffStatus')->name('/notification/insuff/default/status');

	Route::get('/notification/insuff/case','NotificationController@notificationInsuffCase')->name('/notification/insuff/case');

	Route::match(['get','post'],'/notification/insuff/case/edit','NotificationController@notificationInsuffCaseEdit')->name('/notification/insuff/case/edit');

	Route::post('/notification/insuff/case/delete','NotificationController@notificationInsuffCaseDelete')->name('/notification/insuff/case/delete');

	Route::post('/notification/insuff/case/status','NotificationController@notificationInsuffCaseStatus')->name('/notification/insuff/case/status');

	// Notification for Report

	Route::get('/notification/report/default','NotificationController@notificationReport')->name('/notification/report/default');

	Route::post('/notification/report/default/store','NotificationController@notificationReportStore')->name('/notification/report/default/store');

	Route::match(['get','post'],'/notification/report/default/edit','NotificationController@notificationReportEdit')->name('/notification/report/default/edit');

	Route::post('/notification/report/default/delete','NotificationController@notificationReportDelete')->name('/notification/report/default/delete');

	Route::post('/notification/report/default/status','NotificationController@notificationReportStatus')->name('/notification/report/default/status');

	Route::get('/notification/report/mark-complete','NotificationController@notificationMarkReport')->name('/notification/report/mark-complete');

	Route::match(['get','post'],'/notification/report/mark-complete/edit','NotificationController@notificationMarkReportEdit')->name('/notification/report/mark-complete/edit');

	Route::post('/notification/report/mark-complete/delete','NotificationController@notificationMarkReportDelete')->name('/notification/report/mark-complete/delete');

	Route::post('/notification/report/mark-complete/status','NotificationController@notificationMarkReportStatus')->name('/notification/report/mark-complete/status');

	// venoder notification route
	Route::get('/notification/vendor/defalut','NotificationController@notificationsVendor')->name('/notification/vendor/defalut');
	Route::post('/notification/vendor/default/status','NotificationController@notificationVendorStatus')->name('/notification/vendor/default/status');
	Route::match(['get','post'],'/notification/vendor/default/edit','NotificationController@notificationVendorEdit')->name('/notification/vendor/default/edit');
	Route::post('/notification/vendor/default/delete','NotificationController@notificationVendorDelete')->name('/notification/vendor/default/delete');





	// Route::get('/checkprice/custom','HomeController@checkPriceAdmin')->name('/checkprice/custom');

	Route::get('/faq','FaqController@index')->name('/faq');
	Route::get('/faq/create','FaqController@create')->name('/faq/create');
	Route::post('/faq/save','FaqController@store')->name('/faq/save');
	Route::get('/faq/edit/{id}','FaqController@edit')->name('/faq/edit');
	Route::post('/faq/update/{id}','FaqController@update')->name('/faq/update');

	Route::get('/faq/delete/{id}','FaqController@destroy')->name('/faq/delete');

	//vendors
	Route::get('/admin/vendor','VendorController@index')->name('/admin/vendor');
	Route::get('/admin/vendor/create','VendorController@create')->name('/admin/vendor/create');
	Route::get('/admin/vendor/edit/{id}','VendorController@edit')->name('/admin/vendor/edit');
	Route::post('/admin/vendor/save','VendorController@save')->name('/admin/vendor/save');
	Route::post('/admin/vendor/update','VendorController@update')->name('/admin/vendor/update');
	Route::get('/admin/vendor/profile/{id}','VendorController@vendorProfile')->name('/admin/vendor/profile');
	Route::match(['get','post'],'/admin/vendor/business-info/{id}','VendorController@vendorBusinessInfo')->name('/admin/vendor/business-info');
	Route::get('/admin/vendor/sla/{id}','VendorController@vendorSla')->name('/admin/vendor/sla');
	Route::get('/admin/vendor/checkPrice/{id}','VendorController@vendorCheckPrice')->name('/admin/vendor/checkPrice');
	Route::get('/admin/vendor/checkPrice/create/{id}','VendorController@vendorCheckPriceCreate')->name('/admin/vendor/checkPrice/create');
	Route::get('/admin/vendor/checkPrice/edit/{id}/{service_item_id}','VendorController@vendorCheckPriceEdit')->name('/admin/vendor/checkPrice/edit');
	Route::post('/admin/vendor/checkPrice/save','VendorController@vendorCheckPriceSave')->name('/admin/vendor/checkPrice/save');
	Route::post('/admin/vendor/checkPrice/update','VendorController@vendorCheckPriceUpdate')->name('/admin/vendor/checkPrice/update');
	Route::post('/admin/vendor/upload/contractFile','VendorController@uploadFile')->name('/admin/vendor/upload/contractFile');
	Route::get('/admin/vendor/sla/create/{id}','VendorController@vendorSlaCreate')->name('/admin/vendor/sla/create');
	Route::post('/admin/vendor/sla/save','VendorController@vendorSlaSave')->name('/admin/vendor/sla/save');
	Route::get('/admin/vendor/sla/edit/{id}/{sla_id}','VendorController@vendorSlaEdit')->name('/admin/vendor/sla/edit');
	Route::post('/admin/vendor/sla/update','VendorController@vendorSlaUpdate')->name('/admin/vendor/sla/update');
	//Roles
	Route::get('/roles','RoleController@index')->name('/roles');
	Route::get('/roles/create','RoleController@create')->name('/roles/create'); 
	Route::post('/roles/store','RoleController@store')->name('/roles/store');
	Route::get('/roles/edit/{id}','RoleController@edit')->name('/roles/edit');
	Route::post('/roles/update/{id}','RoleController@update')->name('/roles/update');
	Route::post('/roles/delete','RoleController@destroy')->name('/roles/delete');
	Route::post('/roles/roleStatus','RoleController@roleChangeStatus')->name('/roles/roleStatus');
	Route::get('/roles/permission/{id}','RoleController@getAddPermissionPage')->name('/roles/permission');
	Route::post('/roles/permission/update','RoleController@addPermission')->name('/roles/permission/update');



	Route::resource('users','UserController');

	Route::get('/user/del','UserController@deleteUser')->name('/user/del');
	Route::get('/user/unblock','UserController@unblockUser')->name('/user/unblock');


	Route::post('/user/status','UserController@userStatus')->name('/user/status');

	Route::resource('products','ProductController');
	Route::resource('report','ReportMISController');
 
	//reports
	Route::get('/reports','ReportController@index')->name('/reports');
	Route::get('/reports/create','ReportController@create')->name('/reports/create');
	Route::post('/reports/attachment/save','ReportController@store')->name('/reports/attachment/save');
	Route::get('/reports/output-process/{id}','ReportController@outputProcess')->name('/reports/output-process');
	Route::post('/reports/output-process/save','ReportController@outputProcessSave')->name('/reports/output-process/save');
	Route::post('/reports/upload/file','ReportController@uploadFile')->name('/reports/upload/file');
	Route::post('/reports/remove/file','ReportController@removeFile')->name('/reports/remove/file');
	//
	Route::get('/candidate/report-generate/{id}','ReportController@generateCandidateReport')->name('/candidate/report-generate');
	Route::get('/candidate/report-edit/{id}','ReportController@candidateReportEdit')->name('/candidate/report-edit');
	Route::post('/reports/item/update','ReportController@reportItemUpdate')->name('/reports/item/update');
	Route::get('/reports/setData','ReportController@setSessionData')->name('/reports/setData');
	Route::post('/candidates/getlist','ReportController@getCandidatesList')->name('/candidates/getlist');
	Route::post('/candidates/getslalist','ReportController@getCandidatesSlaList')->name('/candidates/getslalist');
	Route::get('/reports/candidate','ReportController@candidateReport')->name('/reports/candidate');
	Route::get('/reports/sla','ReportController@slaReport')->name('/reports/sla');

	Route::post('/report-export','ReportController@export')->name('/report-export');

	Route::post('/report/reference_form','ReportController@reportReferenceTypeForm')->name('/report/reference_form');
	Route::get('/report/image/rearrange','ReportController@dragImage')->name('/report/image/rearrange');
	Route::get('/report/image/rearrange/save','ReportController@dragImageSave')->name('/report/image/rearrange/save');

	 Route::get('/vitals-report/zip/{id}','CandidateController@vitalReportZipDownload')->name('/vitals-report/zip');

	// Customers 
	Route::get('/customers','CustomerController@index')->name('/customers');
	Route::get('/customers/create','CustomerController@create')->name('/customers/create');
	Route::get('/customers/create_step','CustomerController@createStep')->name('/customers/create_step');
	Route::post('/customers/store','CustomerController@store')->name('/customers/store');
	Route::post('/customers/status','CustomerController@customerStatus')->name('/customers/status');
	Route::post('/customers/store_step','CustomerController@storeStep')->name('/customers/store_step');
	Route::get('/customers/edit/{id}','CustomerController@edit')->name('/customers/edit');
	Route::post('/customers/update','CustomerController@update')->name('/customers/update');
	Route::get('/customers/show/{id}','CustomerController@show')->name('/customers/show');
	Route::get('/customers/jobs/{id}','CustomerController@jobs')->name('/customers/jobs');
	Route::get('/customers/sla/{id}','CustomerController@slas')->name('/customers/sla');
	Route::get('/customers/payments/{id}','CustomerController@payments')->name('/customers/payments');
	Route::post('/customers/sla/getlist','CustomerController@getSlaList')->name('/customers/sla/getlist');
	Route::post('/customers/candidates/getlist','CustomerController@getCandidatesList')->name('/customers/candidates/getlist');
	Route::post('/customers/getstate','CustomerController@getstate')->name('/customers/getstate');
	Route::post('/customers/getcity','CustomerController@getcity')->name('/customers/getcity');
	Route::post('/customers/upload/contractFile','CustomerController@uploadFile')->name('/customers/upload/contractFile');

	Route::post('/customers/remove/contractFile','CustomerController@removeFile')->name('/customers/remove/contractFile');

	Route::get('/customers/delete_contact_type','CustomerController@deleteContactType')->name('/customers/delete_contact_type');

	Route::post('/customers/delete_spokeman','CustomerController@deleteSpokeman')->name('/customers/delete_spokeman');
 
	// Candidates  
	Route::get('/candidates/create-option','CandidateController@create_option')->name('/candidates/create-option');
	Route::get('/candidates','CandidateController@index')->name('/candidates');
	Route::get('/candidates/autocomplete','CandidateController@autocomplete')->name('/candidates/autocomplete');
	Route::get('/candidates/autocustomer','CandidateController@autocustomer')->name('/candidates/autocustomer');
	Route::get('/candidates/show/{id}','CandidateController@show')->name('/candidates/show');
	Route::get('/candidates/edit/{id}','CandidateController@edit')->name('/candidates/edit');
	Route::post('/candidates/update','CandidateController@update')->name('/candidates/update');
	Route::get('/candidates/create','CandidateController@create')->name('/candidates/create');
	Route::post('/candidates/updateCandidate','CandidateController@updateCandidate')->name('/candidates/updateCandidate');
	Route::post('/candidates/store','CandidateController@store')->name('/candidates/store');
	Route::post('/customer/sla/serviceItems/','CustomerController@getSlaItemList')->name('/customer/sla/serviceItems');
	Route::get('/candidates/jaf-fill/{case_id}/{id}','CandidateController@jafForm')->name('/candidates/jaf-fill');
	Route::post('/candidates/jafFormSave','CandidateController@jafSave')->name('/candidates/jafFormSave');
	Route::get('/candidates/jaf-info/{case_id}','CandidateController@jafInfo')->name('/candidates/jaf-info');
	Route::post('/candidates/jafFormUpdate','CandidateController@jafUpdate')->name('/candidates/jafFormUpdate');
	Route::get('/candidates/setData','CandidateController@setSessionData')->name('/candidates/setData');
	Route::get('/candidates/delete','CandidateController@deleteCandidate')->name('/candidates/delete');
	Route::get('/candidates/jaf/clearAllChecksInsuff','CandidateController@clearAllChecksInsuff')->name('/candidates/jaf/clearAllChecksInsuff');
	Route::post('/candidates/jaf/clearCheckInsuff','CandidateController@clearCheckInsuff')->name('/candidates/jaf/clearCheckInsuff');
	Route::get('/candidates/jaf/clearCheck','CandidateController@jafInfo')->name('/candidates/jaf/clearCheck');
	Route::get('/candidate/profile/report/{id}','CandidateController@candidateReportEdit')->name('/candidate/profile/report');
	Route::post('/candidate/report/update/{id}','CandidateController@reportUpdate')->name('/candidate/report/update');
	Route::get('/candidates/task/{id}','CandidateController@task')->name('/candidates/task');
	Route::post('/jaf/upload/file','CandidateController@uploadFile')->name('/jaf/upload/file');
	Route::post('/jaf/remove/file','CandidateController@removeFile')->name('/jaf/remove/file');
	Route::post('/candidates/importExcel','CandidateController@importExcel')->name('/candidate/importExcel');
	Route::post('/candidates/multiple','CandidateController@storeMultiple')->name('/candidate/multiple');
	Route::get('/candidates/hold','CandidateController@holdCandidate')->name('/candidates/hold');
	Route::get('/candidates/resume','CandidateController@resumeCandidate')->name('/candidates/resume');
	Route::match(['get','post'],'/candidates/additionalCharges','CandidateController@additionalChanges')->name('/candidates/additionalCharges');

	Route::match(['get','post'],'/candidates/create/custom','CandidateController@createCustom')->name('/candidates/create/custom');

	Route::post('/candidates/validate/custom','CandidateController@validateCustom')->name('/candidates/validate/custom');

	Route::post('/candidates/store/custom','CandidateController@storeCustom')->name('/candidates/store/custom');

	Route::post('/candidates/setExportData','CandidateController@setExportData')->name('/candidates/setExportData');

	Route::post('/jaf/reference_form','CandidateController@jafReferenceTypeForm')->name('/jaf/reference_form');

	Route::get('/candidates/resend_mail','CandidateController@resendMail')->name('/candidates/resend_mail');

	Route::get('/candidates/send_link_mail','CandidateController@sendLinkMail')->name('/candidates/send_link_mail');

	Route::post('/candidates/jaf/raiseInsuff','CandidateController@raiseInsuff')->name('/candidates/jaf/raiseInsuff');
	Route::get('/candidates/new_service/assign_modal','CandidateController@assignModal')->name('/candidates/new_service/assign_modal');
	Route::post('/candidates/new_service/reference_form','CandidateController@newReferenceTypeForm')->name('/candidates/new_service/reference_form');
	Route::post('/candidates/new_check_save','CandidateController@newCheckSave')->name('/candidates/new_check_save');
	//reference number filter
	Route::post('/candidates/candidate_filter_reference_number','CandidateController@candidateFilterReferenceNumber')->name('/candidates/candidate_filter_reference_number');

	Route::get('/candidates/fake_gen_edu_list/autocomplete','AppController@fakeGenEduList')->name('/candidates/fake_gen_edu_list/autocomplete');

	Route::get('/candidates/fake_gen_emp_list/autocomplete','AppController@fakeGenEmpList')->name('/candidates/fake_gen_emp_list/autocomplete');
	
	//admin list excel	
	Route::get('/adminclientlistexport','CandidateController@adminClientListExport');
	Route::get('/admincandidatelistexport','CandidateController@adminCandidateListExport');
	Route::get('/adminclientuserlistexport','CandidateController@adminClientUserListExport');


	Route::match(['get','post'],'/candidates/bulkRaiseInsuff/{id}','CandidateController@bulkRaiseInsuff')->name('/candidates/bulkRaiseInsuff');

	Route::get('candidates/documentdata/{id}','CandidateController@candidateDocumentData')->name('/candidates/documentdata');

	Route::post('candidates/documentdataadd','CandidateController@candidateDocumentDataAdd')->name('/candidates/documentdataadd');

	Route::post('/candidate/insuff/add_report','CandidateController@candidateInsuffaAddReport')->name('/candidate/insuff/add_report');

	//candidate zip download url
	Route::get('/vitals/zip/{id}','CandidateController@vitalZipDownload')->name('/vitals/zip');
	

	// Route::get('/candidates/sessionForget','CandidateController@sessionForget')->name('/candidates/sessionForget');

	Route::get('/jaf-download/{id}','PDFController@JAFPDFgenerate');
	Route::get('/candidate/filter','CandidateController@candidateFilter')->name('/candidate/filter');
	//send otp
	Route::post('/candidates/send_otp','CandidateController@send_otp')->name('/candidates/send_otp');
	//verify otp
	Route::post('/candidates/verfiy_otp','CandidateController@verify_otp')->name('/candidates/verify_otp');

	Route::post('/candidates/ignore_tat','CandidateController@ignore_tat')->name('/candidates/ignore_tat');

	Route::get('/candidates/notes/{id}','CandidateController@notes')->name('/candidates/notes');
	Route::get('/candidates/jaf/rearrange','CandidateController@dragImage')->name('/candidates/jaf/rearrange');
	Route::get('/candidates/jaf/rearrange/save','CandidateController@dragImageSave')->name('/candidates/jaf/rearrange/save');
	
	//call route

	Route::get('/candidates/call/{id}','CandidateController@call')->name('/candidates/call');
	Route::post('/candidates/callingdatasave','CandidateController@callingDataSave')->name('/candidates/callingdatasave');
	Route::get('/candidates/callingdataedit/{id}','CandidateController@callingDataEdit')->name('/candidates/callingdataedit');
	Route::post('/candidates/callingdataupdate','CandidateController@callingDataUpdate')->name('/candidates/callingdataupdate');
	Route::post('/call/remove/image','CandidateController@removeImage')->name('/call/remove/image');
	Route::get('/candidates/callingdatashow/{id}','CandidateController@callingDataShow')->name('/candidates/callingdatashow');
	

	Route::get('/candidate_clear_insuff_data','CandidateController@candidateClearInsuffData')->name('/candidate_clear_insuff_data');

	Route::post('/candidates/jaf/data-verified','CandidateController@check_data_verified')->name('/candidates/jaf/data-verified');

	Route::post('/candidates/address_verification_data','CandidateController@addressVerificationData')->name('/candidates/address_verification_data');

	Route::match(['get','post'],'/candidates/digital_address_verification/{id}','CandidateController@digitalAddressVerification')->name('/candidates/digital_address_verification');

	Route::match(['get','post'],'/candidates/address-verification-form/{id}','CandidateController@addressVerificationForm')->name('/candidates/address-verification-form');

	Route::get('/candidates/address_verification_report/{id}','PDFController@addressVerificationReport')->name('/candidates/address_verification_report');

	Route::post('/candidates/digital_address_add_report/{id}','PDFController@digitalAddressAddToReport')->name('/candidates/digital_address_add_report');

	Route::post('/candidates/address-verification-form-link','CandidateController@sendAddressVerificationFormLink')->name('/candidates/address-verification-form-link');

	Route::post('/candidates/address-verification-app-link','CandidateController@sendAddressVerificationAppLink')->name('/candidates/address-verification-app-link');

	Route::post('/candidates/check-email-exist','CandidateController@checkEmailExist')->name('/candidates/check-email-exist');

	Route::post('/candidates/address-verification-link-mail','CandidateController@sendAddressVerificationLinkMail')->name('/candidates/address-verification-link-mail');

	Route::post('/candidates/address-verification-link-sms','CandidateController@sendAddressVerificationLinkSms')->name('/candidates/address-verification-link-sms');

	Route::post('/candidates/digital-address-verification-log','CandidateController@digitalVerificationLog')->name('/candidates/digital-address-verification-log');

	Route::post('/candidates/address-verification-link-sms-mail','CandidateController@sendAddressVerificationLinkSmsMail')->name('/candidates/address-verification-link-sms-mail');
	
	//import candidates
	Route::get('/candidates/import','ExcelimportController@importCandidateForm')->name('/candidates/import');
	Route::post('/candidates/import','ExcelimportController@importCandidate')->name('/candidates/import');


	//bulk upload sla package wise 
	Route::get('/candidates/bulk/sla/create','CandidateController@BulkSlaCreate')->name('/candidates/bulk/sla/create');
	Route::post('/candidates/jaf/uploads','CandidateController@candidatesJafUploads')->name('/candidates/jaf/uploads');
	Route::post('/candidates/multiple/jafuploads','CandidateController@candidatesMultipleJafuploads')->name('/candidates/multiple/jafuploads');

	// Insuff Module
	Route::get('/insuff','InsuffController@index')->name('/insuff');

	Route::get('/insuff/setData','InsuffController@setSessionData')->name('/insuff/setData');

	Route::get('/insuff_detail','InsuffController@insuff_detail')->name('/insuff_detail');

	Route::get('/clear_insuff_data','InsuffController@clearInsuffData')->name('/clear_insuff_data');

	Route::get('/insuff-export','InsuffController@export')->name('/insuff-export');
	//
	Route::post('/customer/universityBoardList','CustomerController@universityBoardList')->name('/customer/universityBoardList');

	
	//all
	Route::post('/customer/mixSla/serviceItems/','CustomerController@getMixSlaItemList')->name('/customer/mixSla/serviceItems');

	// Jobs
	Route::get('/jobs','JobController@index')->name('/jobs');
	Route::get('/job/import','JobController@importExcel')->name('/job/import');
	Route::post('/job/store/excel','JobController@storeExcelData')->name('/job/store/excel');
	Route::get('/job/create','JobController@create')->name('/job/create');
	Route::post('/job/store','JobController@store')->name('/job/store');
	Route::get('/jobs/candidate','JobController@candidateChecks')->name('/jobs/candidate');
	Route::get('/jobs/sla','JobController@slaChecks')->name('/jobs/sla');

 
	// Contacts
	Route::get('/contacts','ContactController@index')->name('/contacts');
	Route::get('/contacts/create','ContactController@create')->name('/contacts/create');
	Route::post('/contacts/store','ContactController@store')->name('/contacts/store');

	// Report Mis
	Route::get('/report_mis','ReportMISController@report_mis')->name('/report_mis');
	Route::post('/report/store','ReportMISController@store')->name('/report/store');

	Route::get('/getjobdetails','ReportMISController@show');

	//confirmation Qc
	Route::get('confirmationQc/{id}','ReportMISController@confirmationQc')->name('/confirmationQc');

	// Pricise Plan
	Route::get('/price_plan','PricingPlansController@price_plan')->name('/price_plan');

	// chnage password
	Route::get('/change-password','UserController@changePassword')->name('/change-password');
	Route::post('/updatePassword','UserController@updatePassword')->name('/updatePassword');

	//Verifications
	// Route::get('/idChecks','VerificationController@index')->name('/idChecks');
	Route::get('/idChecks','VerificationController@idChecks')->name('/idChecks');
	Route::post('/idChecksFrm','VerificationController@instantIdCheckForm')->name('/idChecksFrm');
	Route::get('/bulkVerifications','VerificationController@bulkVerifications')->name('/bulkVerifications');
	Route::post('/bulkVerifications/importExcel','VerificationController@importBulkVerifications')->name('/bulkVerifications/importExcel');
	Route::get('/bulk/education','VerificationController@bulkEducation')->name('/bulk/education');
	Route::post('/bulk/education/importExcel','VerificationController@importEducationExcel')->name('/bulk/education/importExcel');
	Route::post('/education/multiple','VerificationController@storeEducationMultiple')->name('/education/multiple');
	Route::get('/candidates/bulk/criminal','VerificationController@bulkCriminal')->name('/candidates/bulk/criminal');
	Route::post('/bulk/criminal/importExcel','VerificationController@importExcel')->name('/bulk/criminal/importExcel');
	Route::post('/criminal/multiple','VerificationController@storeMultiple')->name('/criminal/multiple');
	Route::get('/criminal/update','VerificationController@updateMultipleCriminal')->name('/criminal/update');
	Route::get('/verifications','VerificationController@verifications')->name('/verifications');
	Route::post('/verifications/add/new','VerificationController@saveVerification')->name('/verifications/add/new');
	Route::post('/verifications/edit','VerificationController@editVerification')->name('/verifications/edit');
	Route::post('/verifications/update','VerificationController@updateVerification')->name('/verifications/update');
	Route::get('/verifications/view/{id}','VerificationController@verificationConfig')->name('/verifications/view');
	Route::post('/verifications/form-input','VerificationController@saveFormInput')->name('/verifications/form-input');
	Route::post('/verifications/formInput/edit/', 'VerificationController@serviceFormInputEdit')->name('/verifications/formInput/edit');
	Route::post('/verifications/formInput/update', 'VerificationController@serviceFormInputUpdte')->name('/verifications/formInput/update');
	Route::post('/verifications/formInput/delete', 'VerificationController@serviceFormInputDelete')->name('/verifications/formInput/delete');
	// Route::post('/verifications/form-input/drop-down','VerificationController@formInputDropDown')->name('/verifications/form-input/drop-down');
	Route::get('/verifications/view/form-input/{id}/{service_id}','VerificationController@verificationDropDownConfig')->name('/verifications/view/form-input');
	Route::post('/verifications/form-input/save', 'VerificationController@verificationDropDownConfigSave')->name('/verifications/form-input/save');

	Route::post('/manual/verification/status','VerificationController@manualVerificationStatus')->name('/manual/verification/status');
	// 
	Route::post('/customers/getstate','CustomerController@getstate')->name('/customers/getstate');
	Route::post('/customers/getcity','CustomerController@getcity')->name('/customers/getcity');
	
	//vital 4
	Route::post('/instatntverification/vitallist','VerificationController@instatntVerificationVitallist')->name('/instatntverification/vitallist');

	//pdf 
	Route::get('pdf-generate/{id}','PDFController@PDFgenerate');

	Route::get('candidate/report/pdf/{id}/{type}','PDFController@exportFullReport');
	Route::get('candidate/report/pdf-test/{id}','PDFController@mpdf_test');
	Route::get('/candidate/report/preview/{id}','PDFController@previewReport');
	
	//export ID checked report
	Route::get('IDcheck/aadhar/pdf/{id}','PDFController@aadharExportReport');
	Route::get('IDcheck/pan/pdf/{id}','PDFController@panExportReport');
	Route::get('IDcheck/voterID/pdf/{id}','PDFController@voterIDExportReport');
	Route::get('IDcheck/rc/pdf/{id}','PDFController@rcExportReport');
	Route::get('IDcheck/dl/pdf/{id}','PDFController@dlExportReport');
	Route::get('IDcheck/passport/pdf/{id}','PDFController@passportExportReport');
	Route::get('IDcheck/gstin/pdf/{id}','PDFController@gstinExportReport');
	Route::get('IDcheck/bank/pdf/{id}','PDFController@bankExportReport');
	Route::get('IDcheck/advanceaadhar/pdf/{id}','PDFController@advanceAadharExportReport');
	Route::get('IDcheck/telecom/pdf/{id}','PDFController@telecomExportReport');
	Route::get('IDcheck/ecourt/pdf/{id}','PDFController@ecourtExportReport');
	Route::get('IDcheck/upi/pdf/{id}','PDFController@upiExportReport');
	Route::get('IDcheck/cin/pdf/{id}','PDFController@cinExportReport');
	Route::get('IDcheck/court_check_v1/pdf/{id}','PDFController@courtCheckV1ExportReport');
	Route::get('IDcheck/vital/pdf/{id}','PDFController@vitalExportReport');


	//auto id checks
	Route::get('/check/aadhar','JafController@checkAadhar')->name('/check/aadhar');
	Route::get('/check/pan','JafController@checkPan')->name('/check/pan');
	Route::get('/check/voterid','JafController@checkVoterID')->name('/check/voterid');
	Route::get('/check/rc','JafController@checkRC')->name('/check/rc');
	Route::get('/check/dl','JafController@checkDL')->name('/check/dl');

	//id checks
	Route::get('/idCheck/aadhar','VerificationController@idCheckAadhar')->name('/idCheck/aadhar');
	Route::post('/idAdvanceCheck/aadhar','VerificationController@idAdvanceCheck')->name('/idAdvanceCheck/aadhar');
	Route::get('/idCheck/pan','VerificationController@idCheckPan')->name('/idCheck/pan');
	Route::get('/idCheck/voterID','VerificationController@idCheckVoterID')->name('/idCheck/voterID');
	Route::get('/idCheck/RC','VerificationController@idCheckRC')->name('/idCheck/RC');
	Route::get('/idCheck/passport','VerificationController@idCheckPassport')->name('/idCheck/passport');
	Route::get('/idCheck/DL','VerificationController@idCheckDL')->name('/idCheck/DL');
	Route::get('/idCheck/gstin','VerificationController@idCheckGSTIN')->name('/idCheck/gstin');
	Route::get('/idCheck/eletricity','VerificationController@idCheckDL')->name('/idCheck/eletricity');
	Route::get('/idCheck/banking','VerificationController@idCheckBankAccount')->name('/idCheck/banking');
	Route::get('/verifications/instaFinance','VerificationController@InstaDetailedCompanyCIN')->name('/verifications/instaFinance');
	Route::get('/verifications/instaFinance/OrderStatus','VerificationController@InstaFinanceStatus')->name('/verifications/instaFinance/OrderStatus');
	Route::get('/verifications/instaFinance/DownloadReport','VerificationController@InstaFinanceDownloadReport')->name('/verifications/instaFinance/DownloadReport');
	Route::post('/idAdvanceCheckOtp/aadharOtp','VerificationController@idAdvanceCheckOtp')->name('/idAdvanceCheckOtp/aadharOtp');
	Route::get('/aadharchecks/show/{client_id}','VerificationController@advanceAadharReport')->name('/aadharchecks/show');
	Route::get('/idCheck/ecourt','VerificationController@idCheckECourt')->name('/idCheck/ecourt');
	Route::get('/idCheck/upi','VerificationController@idCheckUPI')->name('/idCheck/upi');
	Route::post('/idCheck/cin','VerificationController@idCheckCIN')->name('/idCheck/cin');
	Route::post('/idCheck/court_check_v1','VerificationController@idCheckCourtV1')->name('/idCheck/court_check_v1');

	Route::get('/idCheck/telecom','VerificationController@idTelecomCheck')->name('/idCheck/telecom');
	Route::post('/idAdvanceCheck/telecom','VerificationController@idVerifyTelcomCheck')->name('/idAdvanceCheck/telecom');

	Route::get('/idCheck/covid19','VerificationController@idCovid19Check')->name('/idCheck/covid19');
	Route::post('/idAdvanceCheck/covid19','VerificationController@idVerifyCovid19Check')->name('/idAdvanceCheck/covid19');
	Route::post('/idAdvanceCheck/covid19ref','VerificationController@idVerifyCovidRefCheck')->name('/idAdvanceCheck/covid19ref');
	// vital
	Route::get('/idCheck/vital','VerificationController@idCheckVital')->name('/idCheck/vital');
	

	//Task 
	Route::get('/task','TaskController@index')->name('/task');
	Route::post('/task/filter','TaskController@indexFilter')->name('/task/filter');
	Route::post('/task/reassign','TaskController@store')->name('/task/reassign');
	Route::post('/task/user/assign','TaskController@assignUser')->name('/task/user/assign');
	Route::post('/task/user/report/assign','TaskController@reportAssignUser')->name('/task/user/report/assign');
	Route::get('/task/report_reassign_modal','TaskController@reportReassignModal')->name('/task/report_reassign_modal');
	Route::post('/task/report/reassign','TaskController@reportReassign')->name('/task/report/reassign');
	Route::post('/task/verification/reassign','TaskController@taskReassign')->name('/task/verification/reassign');
	Route::get('/task/assign','TaskController@assignIndex')->name('/task/assign');
	Route::post('/task/assign/filter','TaskController@assignIndexFilter')->name('/task/assign/filter');
	Route::get('/task/unassign','TaskController@unassignIndex')->name('/task/unassign');
	Route::get('/task/assign_modal','TaskController@assignModal')->name('/task/assign_modal');
	Route::get('/task/bulk_assign_modal','TaskController@bulkAssignModal')->name('/task/bulk_assign_modal');
	Route::get('/task/reassign_modal','TaskController@reassignModal')->name('/task/reassign_modal');
	Route::get('/task/filling_reassign_modal','TaskController@fillingReassignModal')->name('/task/filling_reassign_modal');
	Route::get('/task/setData','TaskController@setSessionData')->name('/task/setData');
	Route::get('/task/complete','TaskController@completeIndex')->name('/task/complete');
	Route::get('/task/vendor','TaskController@vendorIndex')->name('/task/vendor');
	Route::get('/task/vendor_sla','TaskController@vendorSla')->name('/task/vendor_sla');
	Route::get('/task/bulk_vendor_sla','TaskController@bulkVendorSla')->name('/task/bulk_vendor_sla');
	Route::get('/task/reassign_vendor_sla','TaskController@reassignVendorSla')->name('/task/reassign_vendor_sla');
	Route::get('/task/preview','TaskController@taskPreview')->name('/task/preview');
	Route::get('/task/verification_modal','TaskController@userTaskAssigned')->name('/task/verification_modal');
	Route::get('/task/verify/info','TaskController@taskVerifyInfo')->name('/task/verify/info');
	Route::post('/task/getuserlist','TaskController@getUserList')->name('/task/getuserlist');

	Route::post('/task/vendor/add_report/{id}','PDFController@vendorDataAddToReport')->name('/task/vendor/add_report');
	
	//taskvendor raise clear 
	Route::get('/vendor/taskinsuffcleare','TaskController@taskinsuffcleare')->name('/vendor/taskinsuffcleare');
	Route::post('/vendor/task/raiseInsuff','TaskController@vendortaskraiseInsuff')->name('/vendor/task/raiseInsuff');

	//Checks item export
	Route::get('task/checks-export','TaskController@exportChecks')->name('task/checks-export');
	Route::post('task/bulk-assign','TaskController@bulkAssign')->name('task/bulk-assign');

	//preview image delete 
	Route::post('/task/remove/image','TaskController@vendorRemoveImage')->name('/task/remove/image');	

	
	//Batch
	Route::get('/batches','BatchController@index')->name('/batches');
	Route::get('/batches/create','BatchController@create')->name('/batches/create');
	Route::post('/batches/store','BatchController@store')->name('/batches/store');
	Route::get('/batches/zip/{id}','BatchController@zipDownload')->name('/batches/zip');
	Route::post('/batches/update','BatchController@update')->name('/batches/update');
	Route::post('/batches/mixSla/serviceItems/','BatchController@getMixSlaItemList')->name('/batches/mixSla/serviceItems');
	Route::get('/batches/delete','BatchController@deleteBatch')->name('/batches/delete');

	Route::get('/reference','AppController@reference')->name('/reference');

	Route::get('/verification_status','AppController@verificationStatus')->name('/verification_status');

	Route::get('/jaf_filled_by','AppController@jafFilled')->name('/jaf_filled_by');
	Route::get('/jaf_filled_by_type','AppController@jafFilledType')->name('/jaf_filled_by_type');
	Route::get('/filled_at','AppController@FilledAt')->name('/filled_at');
	Route::get('/is_insuff','AppController@isInsuff')->name('/is_insuff');
	Route::get('/report_created_by','AppController@reportCreatedBy')->name('/report_created_by');
	// Update parent_id in task and task assignment table
	Route::get('/task_parent_update','AppController@taskParentUpdate')->name('/task_parent_update');
	Route::get('/task_updated_at_update','AppController@taskupdateatUpdate')->name('/task_updated_at_update');

	Route::get('/report_status','AppController@report_status')->name('/report_status');

	
	Route::get('/coc_updated_by','AppController@cocUpdatedBy')->name('/coc_updated_by');
	Route::get('/coc_created_by','AppController@cocCreatedBy')->name('/coc_created_by');
	Route::get('/hold_candidates_parent_id','AppController@holdCandidateParentId')->name('/hold_candidates_parent_id');
	
	Route::get('/jaf_send_to','AppController@jafSend')->name('/jaf_send_to');
	
	Route::get('/jaf_blank','AppController@blankJaf')->name('/jaf_blank');
	Route::get('/jaf_candidate_id','AppController@jafCandidateId')->name('/jaf_candidate_id');
	Route::get('/clearInsuff','AppController@clearInsuff')->name('/clearInsuff');
	Route::get('/raisedInsuff','AppController@raisedInsuff')->name('/raisedInsuff');


});



//client 
Route::group(['prefix'=>'my','domain' => Config::get('app.client_url'), 'namespace' => 'Client', 'middleware' => ['auth:web','throttle:60,1'] ], function(){

    Route::get('/home','HomeController@index')->name('/my/home');
	//Route::post('/home/render','HomeController@dashboardCardRender')->name('/my/home/render');
	Route::post('/home/dashboard-candidate-card','HomeController@dashboardCandidateCard')->name('/my/home/dashboard-candidate-card');
	Route::post('/home/dashboard-jaf-card','HomeController@dashboardJafCard')->name('/my/home/dashboard-jaf-card');
	Route::post('/home/dashboard-check-card','HomeController@dashboardCheckCard')->name('/my/home/dashboard-check-card');
	Route::post('/home/dashboard-report-card','HomeController@dashboardReportCard')->name('/my/home/dashboard-report-card');
	Route::post('/home/check/render','HomeController@dashboardCheckRender')->name('/my/home/check/render');
    Route::get('/sla','SlaController@index')->name('/my/sla');
	Route::get('/sla/view/{id}','SlaController@sla_view')->name('/my/sla/view');
	Route::get('/cases','CaseController@index')->name('/my/cases');

	Route::get('/progress-export','ExcelController@progressExport')->name('/my/progress-export');
	

	// Route::get('/sla/create','SlaController@sla_create')->name('/my/sla/create');
	// Route::post('/sla/save','SlaController@sla_save')->name('/my/sla/save');

	//account
	Route::get('/profile','HomeController@profile')->name('/my/profile');
	Route::post('/profile/update','HomeController@update_profile')->name('/my/profile/update');
	Route::get('/business-info','HomeController@businessInfo')->name('/my/business-info');
	Route::post('/business_info/update','HomeController@updateBusinessInfo')->name('/business_info/update');
	Route::get('/contact-info','HomeController@contactInfo')->name('/my/contact-info');
	Route::post('/contact_info/update','HomeController@updateContactInfo')->name('/my/contact_info/update');
	Route::get('/contact_info/delete_contact_type','HomeController@deleteContactType')->name('/my/contact_info/delete_contact_type');
	Route::get('/vendor-info','HomeController@vendorInfo')->name('/my/vendor-info');
	Route::get('/billing','BillingController@billing')->name('/my/billing');
	Route::post('/billing/send_request','BillingController@billApproveSendRequestDetails')->name('/my/billing/send_request');
	Route::match(['get','post'],'/billing/approve_status','BillingController@billingApproveStatus')->name('/my/billing/approve_status');
	Route::post('/billing/completedetails','BillingController@billingCompleteDetails')->name('/my/billing/completedetails');
	Route::post('/billing/actiondetails','BillingController@billingActionDetails')->name('/my/billing/actiondetails');
	Route::get('/billing/details/{id}','BillingController@billing_details')->name('/my/billing/details');
	Route::post('/billing/details_add','BillingController@billingDetailsAdditional')->name('/my/billing/details_add');
	Route::get('/billing/details/downloadPDF/{id}','PDFController@billingDetailsPDF')->name('/my/billing/details/downloadPDF');
	Route::get('/api-usage','HomeController@userAPI')->name('/my/api-usage');
	Route::get('/api-usage/details/{id}','HomeController@apiDetails')->name('/my/api-usage/details');
	Route::post('/api-usage/download','HomeController@downloadApiDetails')->name('/my/api-usage/download');
	Route::get('/wallet','WalletController@userWallet')->name('/my/wallet');
	Route::get('/billing/details/preview/{id}','PDFController@billingPreviewPDF')->name('/my/billing/details/preview');

	Route::post('/wallet/add-money','WalletController@addMoney')->name('/my/wallet/add-money');
	
	Route::get('/wallet/payment-page/{order_id}','WalletController@payment')->name('/my/wallet/payment-page');
	// for Payment complete
	Route::post('/wallet/payment-complete','WalletController@Complete')->name('/my/wallet/payment-complete');

	Route::get('/checkprice','HomeController@checkPriceMaster')->name('/my/checkprice');
	
	// FeedBack Controller
	Route::get('/feedback','FeedbackController@feedback')->name('/my/feedback');
	Route::post('/feedback/store','FeedbackController@store')->name('/my/feedback/store');

	// Help Controller
	Route::get('/help','HelpController@help')->name('/my/help');
	Route::get('/question/create','HelpController@createQuestion')->name('/my/question/create');
	Route::post('/question/save','HelpController@saveQuestion')->name('/my/question/save');



	// candidates 
	Route::get('/candidates','CandidateController@index')->name('/my/candidates');
	Route::get('/candidates/autocomplete','CandidateController@autocomplete')->name('/my/candidates/autocomplete');

	Route::get('/candidates/create','CandidateController@create')->name('/my/candidates/create');

	Route::get('/candidates/create-option','CandidateController@create_option')->name('/my/candidates/create-option');
	Route::get('/candidates/show/{id}','CandidateController@show')->name('/my/candidates/show');
	Route::post('/customers/candidates/getlist','CandidateController@getCandidatesList')->name('/my/customers/candidates/getlist');
	Route::get('/candidates/setData','CandidateController@setSessionData')->name('/my/candidates/setData');
	Route::post('/customer/mixSla/serviceItems/','CandidateController@getMixSlaItemList')->name('/my/customer/mixSla/serviceItems');
	Route::post('/candidates/store','CandidateController@store')->name('/my/candidates/store');
	Route::get('/candidates/jaf-fill/{case_id}/{id}','CandidateController@jafForm')->name('/my/candidates/jaf-fill');
	Route::post('/candidates/jafFormSave','CandidateController@jafSave')->name('/my/candidates/jafFormSave');
	Route::get('/candidates/jaf-info/{case_id}','CandidateController@jafInfo')->name('/my/candidates/jaf-info');
	Route::post('/jaf/upload/file','CandidateController@uploadFile')->name('/my/jaf/upload/file');
	Route::post('/jaf/remove/file','CandidateController@removeFile')->name('/my/jaf/remove/file');

	Route::get('/candidates/hold','CandidateController@holdCandidate')->name('/my/candidates/hold');
	Route::get('/candidates/resume','CandidateController@resumeCandidate')->name('/my/candidates/resume');

	Route::get('/candidates/resend_mail','CandidateController@resendMail')->name('/my/candidates/resend_mail');

	Route::post('/candidates/delete','CandidateController@deleteCandidate')->name('/my/candidates/delete');

	Route::post('/candidates/jafFormUpdate','CandidateController@jafUpdate')->name('/my/candidates/jafFormUpdate');

	Route::get('/jaf-download/{id}','PDFController@PDFgenerate');

	Route::get('/candidates/notes/{id}','CandidateController@notes')->name('/my/candidates/notes');

	Route::get('/candidates/fake_gen_edu_list/autocomplete','CandidateController@fakeGenEduList')->name('/my/candidates/fake_gen_edu_list/autocomplete');

	Route::get('/candidates/fake_gen_emp_list/autocomplete','CandidateController@fakeGenEmpList')->name('/my/candidates/fake_gen_emp_list/autocomplete');

	// Insuff Module
	
	Route::get('/insuff','InsuffController@index')->name('/my/insuff');

	Route::get('/insuff/setData','InsuffController@setSessionData')->name('/my/insuff/setData');

	Route::get('/insuff_detail','InsuffController@insuff_detail')->name('/my/insuff_detail');

	Route::get('/insuff-export','InsuffController@export')->name('/my/insuff-export');


	//reports
	Route::get('/reports','ReportController@index')->name('/my/reports');
	Route::get('/reports/setData','ReportController@setSessionData')->name('/my/reports/setData');
	//report item export
	Route::post('/report-export','ReportController@export')->name('/my/report-export');
	Route::get('/candidate/report/pdf/{id}/{type}','PDFController@exportsFullReport');
	Route::get('/candidate/report/preview/{id}','PDFController@previewReport');
	Route::post('/candidate/report/feedback','ReportController@feedback')->name('my/candidate/report/feedback');

	//checks
	Route::get('/checks','CheckController@index')->name('/my/checks');

	

	Route::get('/idChecks','VerificationController@idChecks')->name('/my/idChecks');
	Route::post('/idChecksFrm','VerificationController@idCheckForm')->name('/my/idChecksFrm');

	//verifications
	Route::get('/verifications','VerificationController@index')->name('/my/verifications');
	Route::get('/idCheck/aadhar','VerificationController@idCheckAadhar')->name('/my/idCheck/aadhar');
	Route::get('/idCheck/pan','VerificationController@idCheckPan')->name('/my/idCheck/pan');
	Route::get('/idCheck/voterID','VerificationController@idCheckVoterID')->name('/my/idCheck/voterID');
	Route::get('/idCheck/RC','VerificationController@idCheckRC')->name('/my/idCheck/RC');
	Route::get('/idCheck/passport','VerificationController@idCheckPassport')->name('/my/idCheck/passport');
	Route::get('/idCheck/DL','VerificationController@idCheckDL')->name('/my/idCheck/DL');
	Route::get('/idCheck/gstin','VerificationController@idCheckGSTIN')->name('/my/idCheck/gstin');
	Route::post('/idAdvanceCheck/aadhar','VerificationController@idAdvanceCheck')->name('/my/idAdvanceCheck/aadhar');
	Route::post('/idAdvanceCheckOtp/aadharOtp','VerificationController@idAdvanceCheckOtp')->name('/my/idAdvanceCheckOtp/aadharOtp');
	Route::get('/idCheck/banking','VerificationController@idCheckBankAccount')->name('/my/idCheck/banking');
	Route::get('/aadharchecks/show/{client_id}','VerificationController@advanceAadharReport')->name('/my/aadharchecks/show');
	Route::get('/idCheck/ecourt','VerificationController@idCheckECourt')->name('/my/idCheck/ecourt');
	Route::get('/idCheck/upi','VerificationController@idCheckUPI')->name('/my/idCheck/upi');
	Route::post('/idCheck/cin','VerificationController@idCheckCIN')->name('/my/idCheck/cin');

	Route::get('/idCheck/telecom','VerificationController@idTelecomCheck')->name('/my/idCheck/telecom');
	Route::post('/idAdvanceCheck/telecom','VerificationController@idVerifyTelcomCheck')->name('/my/idAdvanceCheck/telecom');

	Route::get('/idCheck/covid19','VerificationController@idCovid19Check')->name('/my/idCheck/covid19');
	Route::post('/idAdvanceCheck/covid19','VerificationController@idVerifyCovid19Check')->name('/my/idAdvanceCheck/covid19');
	Route::post('/idAdvanceCheck/covid19ref','VerificationController@idVerifyCovidRefCheck')->name('/my/idAdvanceCheck/covid19ref');
	Route::post('/idCheck/court_check_v1','VerificationController@idCheckCourtV1')->name('/my/idCheck/court_check_v1');


	Route::get('/verifications/term_accept','VerificationController@termAccept')->name('/my/verifications/term_accept');

	//Export ID's pdf 
	Route::get('my/IDcheck/aadhar/pdf/{id}','PDFController@aadharExportReport');
	Route::get('my/IDcheck/pan/pdf/{id}','PDFController@panExportReport');
	Route::get('my/IDcheck/voterID/pdf/{id}','PDFController@voterIDExportReport');
	Route::get('my/IDcheck/rc/pdf/{id}','PDFController@rcExportReport');
	Route::get('my/IDcheck/dl/pdf/{id}','PDFController@dlExportReport');
	Route::get('my/IDcheck/passport/pdf/{id}','PDFController@passportExportReport');
	Route::get('my/IDcheck/gstin/pdf/{id}','PDFController@gstinExportReport');
	Route::get('/IDcheck/advanceaadhar/pdf/{id}','PDFController@advanceAadharExportReport');
	Route::get('my/IDcheck/telecom/pdf/{id}','PDFController@telecomExportReport');
	Route::get('/IDcheck/bank/pdf/{id}','PDFController@bankExportReport');
	Route::get('/IDcheck/ecourt/pdf/{id}','PDFController@ecourtExportReport');
	Route::get('/IDcheck/upi/pdf/{id}','PDFController@upiExportReport');
	Route::get('/IDcheck/cin/pdf/{id}','PDFController@cinExportReport');
	Route::get('IDcheck/court_check_v1/pdf/{id}','PDFController@courtCheckV1ExportReport');

	//Users
	Route::get('/users','UserController@index')->name('/my/users');
	Route::get('/users/create','UserController@create')->name('/my/users/create');

	Route::get('/users/edit/{id}','UserController@edit')->name('/my/users/edit');
	Route::post('/users/update/{id}','UserController@update')->name('/my/users/update');
	Route::post('/users/store','UserController@store')->name('/my/users/store');

	Route::get('/users/delete','UserController@deleteUser')->name('/my/users/delete');
	Route::get('/user/unblock','UserController@unblockUser')->name('/my/user/unblock');
	
	Route::post('/user/status','UserController@userStatus')->name('/my/user/status');


	//Roles And Permission
	Route::get('/roles','RoleController@index')->name('/my/roles');
	Route::get('/roles/create','RoleController@create')->name('/my/roles/create');
	Route::post('/roles/store','RoleController@store')->name('/my/roles/store');
	Route::get('/roles/edit/{id}','RoleController@edit')->name('/my/roles/edit');
	Route::post('/roles/update/{id}','RoleController@update')->name('/my/roles/update');
	Route::post('/roles/delete','RoleController@destroy')->name('/my/roles/delete');
	Route::post('/roles/roleStatus','RoleController@roleChangeStatus')->name('/my/roles/roleStatus');
	Route::get('/roles/permission/{id}','RoleController@getAddPermissionPage')->name('/my/roles/permission');
	Route::post('/roles/permission/update','RoleController@addPermission')->name('/my/roles/permission/update');

	//Batch
	Route::get('/batches','BatchController@index')->name('/my/batches');
	Route::get('/batches/create','BatchController@create')->name('/my/batches/create');
	Route::post('/batches/store','BatchController@store')->name('/my/batches/store');
	Route::get('/batches/zip/{id}','BatchController@zipDownload')->name('/my/batches/zip');
	Route::get('/batches/delete','BatchController@deleteBatch')->name('/my/batches/delete');

	

	// Route::get('/password', function () {
	// 	$password = Hash::make('digisoft');
	// 	return $password;
	// });

	Route::get('/change-password','UserController@changePassword')->name('/my/change-password');
	Route::post('/updatePassword','UserController@updatePassword')->name('/my/updatePassword');

	Route::get('/faq','FaqController@index')->name('/my/faq');
	Route::get('/faq/show/{id}','FaqController@show')->name('/my/faq/show');

});

//Candidate 
Route::group(['prefix'=>'candidate','domain' => Config::get('app.candidate_url'), 'namespace' => 'Candidate', 'middleware' => ['auth:candidate','throttle:60,1'] ], function(){
	
	Route::get('/candidates/jaf-fill','CandidateController@jafForm')->name('/candidate/candidates/jaf-fill');
	Route::post('/candidates/jafFormSave','CandidateController@jafSave')->name('/candidate/candidates/jafFormSave');
	Route::post('/jaf/upload/file','CandidateController@uploadFile')->name('/candidate/candidates/jaf/upload/file');
	Route::post('/jaf/remove/file','CandidateController@removeFile')->name('/candidate/candidates/jaf/remove/file');
	Route::get('/thank-you','CandidateController@thank_you')->name('/candidate/candidates/thank-you');

	Route::get('/error-404','CandidateController@error')->name('/candidate/candidates/error-404');

	Route::get('/error-403','CandidateController@error403')->name('/candidate/candidates/error-403');

	Route::post('/logout','CandidateController@logout')->name('candidate.logout');

	Route::get('/candidates/fake_gen_edu_list/autocomplete','CandidateController@fakeGenEduList')->name('/candidate/candidates/fake_gen_edu_list/autocomplete');

	Route::get('/candidates/fake_gen_emp_list/autocomplete','CandidateController@fakeGenEmpList')->name('/candidate/candidates/fake_gen_emp_list/autocomplete');

});
//Candidate 
Route::group(['prefix'=>'user','domain' => Config::get('app.user_url'), 'namespace' => 'User','middleware' => ['throttle:60,1']], function(){



	// Open form at the time of click link on email by user
	Route::get('/password/{business_id}/{user_id}/{token_no}','UserLinkController@create')->name('user/password');
	Route::post('/password/store','UserLinkController@store')->name('/user/password/store');

	
	// Route::get('/thank-you-user','UserLinkController@thankyou')->name('/user/thank-you-user');

	Route::get('/downloadReportZip/{zip_id}','AppController@downloadReportZip')->name('/user/downloadReportZip');

	Route::get('/error-404-file','AppController@errorFile')->name('/user/error-404-file');

	Route::get('/downloadguestReportZip/{zip_id}','AppController@downloadguestReportZip')->name('/user/downloadguestReportZip');

	Route::get('/downloadguestInstantReportZip/{zip_id}','AppController@downloadguestInstantReportZip')->name('/user/downloadguestInstantReportZip');

	Route::get('/downloadguestinstant/orders/checkpdf/{reports_id}','AppController@downloadguestInstantReportCheckPdf')->name('/user/downloadguestinstant/orders/checkpdf');

	Route::get('/downloadInsuffReport/{id}','AppController@downloadInsuffReport')->name('/user/downloadInsuffReport');

	Route::get('/billing/downloadInvoice/{id}','AppController@downloadBillingInvoice')->name('/user/billing/downloadInvoice');

	Route::get('{code}','AppController@shortenLink')->name('/user/shortlink');

	Route::get('/downloadReportShare/{id}','AppController@downloadReportShare')->name('/user/downloadReportShare');

	Route::get('/downloadProgressExcel/{id}','AppController@downloadProgressExcel')->name('/user/downloadProgressExcel');

});

Route::group(['prefix'=>'vendor','domain' => Config::get('app.vendor_url'), 'namespace' => 'Vendor', 'middleware' => [ 'auth:web','throttle:60,1'] ], function(){


	//roles route vendor
	Route::get('/roles','RoleController@index')->name('/vendor/roles');
	Route::get('/roles/create','RoleController@create')->name('vendor/roles/create'); 
	Route::post('vendor/roles/store','RoleController@store')->name('vendor/roles/store');
	Route::get('roles/edit/{id}','RoleController@edit')->name('vendor/roles/edit');
	Route::post('roles/update/{id}','RoleController@update')->name('vendor/roles/update');
	Route::post('/roles/delete','RoleController@delete')->name('/vendor/roles/delete');
	Route::post('/roles/rolestatus','RoleController@roleChangeStatus')->name('/vendor/roles/rolestatus');

	Route::get('/home','HomeController@index')->name('/vendor/home');

	//dashboard card count 
	Route::post('/dashboard-tat-card','HomeController@dashboardTatCard')->name('/vendor/dashboard-tat-card');
	Route::post('/dashboard-task-card','HomeController@dashboardTaskCard')->name('/vendor/dashboard-task-card');
	Route::post('/dashboard-insuff-card','HomeController@dashboardInsuffCard')->name('/vendor/dashboard-insuff-card');

	// SLA section
	Route::get('/sla','SlaController@index')->name('/vendor/sla');

	//Accounts Section
	Route::get('/profile','AccountController@profile')->name('/vendor/profile');
	Route::get('/business/info','AccountController@business_info')->name('/vendor/business/info');
	//get all task
	Route::get('/task','TaskController@index')->name('/vendor/task');

	Route::get('jaf-download/{id}/{service_id}/{check_number}','TaskController@generatePdf');
	Route::post('/task/upload','TaskController@uploadData')->name('/vendor/task/upload');
	Route::get('/task/preview','TaskController@taskPreview')->name('/vendor/task/preview');
	Route::post('/task/user/assign','TaskController@assignUser')->name('/vendor/task/user/assign');
	Route::post('/task/user/reassign','TaskController@reassignUser')->name('/vendor/task/user/reassign');
	Route::get('/task/setData','TaskController@setSessionData')->name('/vendor/task/setData');
	Route::get('/task/pdf/export','TaskController@pdfExport')->name('/vendor/task/pdf/export');
	//Users
	Route::get('/users','UserController@index')->name('/vendor/users');
	Route::get('/users/create','UserController@create')->name('/vendor/users/create');

	Route::get('/users/edit/{id}','UserController@edit')->name('/vendor/users/edit');
	Route::post('/users/update/{id}','UserController@update')->name('/vendor/users/update');
	Route::post('/users/store','UserController@store')->name('/vendor/users/store');

	Route::get('/users/delete','UserController@deleteUser')->name('/vendor/users/delete');
	Route::get('/user/unblock','UserController@unblockUser')->name('/vendor/user/unblock');
	
	Route::post('/user/status','UserController@userStatus')->name('/vendor/user/status');

	Route::get('/raiseInsuff/show','TaskController@vendorraiseInsuffshow')->name('/vendor/taskinsuffshow');
	Route::post('/jaf/raiseInsuff','TaskController@vendorraiseInsuff')->name('/vendor/jaf/raiseInsuff');


	
	


});


Route::group(['prefix'=>'verify','domain' => Config::get('app.instant_url'), 'namespace' => 'Guest', 'middleware' => [ 'auth:web','throttle:60,1' ]], function(){

	Route::get('/home','HomeController@index')->name('/verify/home');
	// Route::get('/candidates','CandidateController@index')->name('/verify/candidates');
	// Route::get('/candidates/create','CandidateController@create')->name('/verify/candidates/create');
	// Route::post('/candidates/store','CandidateController@store')->name('/verify/candidates/store');

	// Route::get('/candidates/edit/{id}','CandidateController@edit')->name('/verify/candidates/edit');
	// Route::post('/candidates/update','CandidateController@update')->name('/verify/candidates/update');

	// Route::get('/candidates/verification/{id}','AppController@verificationServices')->name('/verify/candidates/verification');

	// Route::post('/candidates/verification/store','AppController@verificationServicesStore')->name('/verify/candidates/store');

	// Route::get('/candidates/verification/checkout/{id}','AppController@verificationCheckout')->name('/verify/candidates/verification/checkout');

	// Route::post('/candidates/verification/promocode','AppController@verificationPromoCode')->name('/verify/candidates/verification/promocode');

	// Route::post('/candidates/verification/remove_promocode','AppController@verificationRemovePromoCode')->name('/verify/candidates/verification/remove_promocode');

	// Route::post('/candidates/verification/checkout/store','AppController@verificationServicesCheckoutStore')->name('/verify/candidates/verification/checkout/store');

	// Route::get('/candidates/verification/payment-page/{candidate_id}/{order_id}','AppController@payment')->name('/verify/candidates/verification/payment-page');
	// // for Payment complete
	// Route::post('/candidates/verification/payment-complete','AppController@Complete')->name('/verify/candidates/verification/payment-complete');

	// Route::get('/candidates/verification/payment-success/{zipname}','AppController@paymentSuccess')->name('/verify/candidates/verification/payment-success');

	// Route::get('/candidates/verification/payment-failed/{candidate_id}/{order_id}','AppController@paymentFailed')->name('/verify/candidates/verification/payment-failed');

	// Route::get('/orders','OrderController@index')->name('/verify/orders');

	Route::get('/profile','UserController@profile')->name('/verify/profile');

	Route::post('/profile/update','UserController@update_profile')->name('/verify/profile/update');

	Route::post('/delete-account','UserController@deleteAccount')->name('/verify/delete-account');

	Route::get('/help','HelpController@index')->name('/verify/help');

	Route::get('/help/create','HelpController@createQuestion')->name('/verify/help/create');

	Route::post('/help/save','HelpController@saveQuestion')->name('/verify/help/save');

	Route::get('/settings','AppController@Settings')->name('/verify/settings');

	Route::post('/settings/purge-data','AppController@purgeDataUpdate')->name('/verify/settings/purge-data');


	Route::get('/instant_verification','InstantVerificationController@index')->name('/verify/instant_verification'); 

	Route::post('/instant_verification/store','InstantVerificationController@store')->name('/verify/instant_verification/store');

	Route::post('/instant_verification/addcartstore','InstantVerificationController@addCartStore')->name('/verify/instant_verification/addcartstore');

	Route::get('/instant_verification/services/{guest_master_id}','InstantVerificationController@servicesCreate')->name('/verify/instant_verification/services');

	Route::post('/instant_verification/services/store','InstantVerificationController@servicesStore')->name('/verify/instant_verification/services/store');

	Route::get('/instant_verification/checkout/{guest_master_id}','InstantVerificationController@checkOut')->name('/verify/instant_verification/checkout');

	Route::post('/instant_verification/checkout/store','InstantVerificationController@checkOutStore')->name('/verify/instant_verification/checkout/store');

	Route::post('/instant_verification/add_promocode','InstantVerificationController@addPromoCode')->name('/verify/instant_verification/add_promocode');

	Route::post('/instant_verification/remove_promocode','InstantVerificationController@removePromoCode')->name('/verify/instant_verification/remove_promocode');

	Route::post('/instant_verification/services/delete_all','InstantVerificationController@deleteAllServices')->name('/verify/instant_verification/services/delete_all');

	Route::post('/instant_verification/services/delete_service','InstantVerificationController@deleteServices')->name('/verify/instant_verification/services/delete_service');

	Route::post('/instant_verification/services/delete_by_check','InstantVerificationController@deleteServiceByCheckItem')->name('/verify/instant_verification/services/delete_by_check');

	Route::get('/instant_verification/payment-page/{guest_master_id}/{order_id}','InstantVerificationController@payment')->name('/verify/instant_verification/payment-page');

	Route::post('/instant_verification/payment-complete','InstantVerificationController@Complete')->name('/verify/instant_verification/payment-complete');

	Route::get('/instant_verification/payment-success/{guest_master_id}','InstantVerificationController@paymentSuccess')->name('/verify/instant_verification/payment-success');

	Route::post('/instant_verification/whatsapp_report','InstantVerificationController@instantWhatsappReport')->name('/verify/instant_verification/whatsapp_report');

	Route::get('/instant_verification/payment-failed/{guest_master_id}/{order_id}','InstantVerificationController@paymentFailed')->name('/verify/instant_verification/payment-failed');

	Route::get('/instantverification/orders','OrderController@instantOrder')->name('/verify/instantverification/orders');

	Route::get('/instantverification/orders/details/{id}','OrderController@instantOrderDetails')->name('/verify/instantverification/orders/details');

	Route::get('/instantverification/orders/detailscheck/{id}','OrderController@instantOrderDetailsCheck')->name('/verify/instantverification/orders/detailscheck');

	Route::post('/instantverification/orders/details/data','OrderController@instantOrderDetailsData')->name('/verify/instantverification/orders/details/data');

	Route::post('/instantverification/orders/details/data/edit','OrderController@instantOrderDetailsDataEdit')->name('/verify/instantverification/orders/details/data/edit');

	Route::get('/instantverification/orders/masterzip/{id}','OrderController@instantMasterZipReport')->name('/verify/instantverification/orders/masterzip');

	Route::get('/instantverification/orders/checkpdf/{id}','OrderController@instantCheckPDFReport')->name('/verify/instantverification/orders/checkpdf');

	Route::get('/instantverification/reports','OrderController@instantReports')->name('/verify/instantverification/reports');

	Route::get('/instantverification/reports/reportsdetails/{id}','OrderController@instantReportsDetails')->name('/instantverification/reports/reportsdetails');

	Route::get('/instantverification/reports/reportdetails/{id}','OrderController@instantReportsReportsDetails')->name('/verify/instantverification/reports/reportdetails');

	Route::get('/checkdetails','OrderController@instantCheckDetails')->name('/verify/checkdetails');

	Route::get('/checkdetails/details/{id}','OrderController@instantCheckDetailsData')->name('/verify/checkdetails/details');

	Route::get('/checkdetails/detailscheck/{id}','OrderController@instantCheckDetailsDataCheck')->name('/verify/checkdetails/detailscheck');

	Route::get('/instantverification/resendreport','OrderController@instantverificationResendReport')->name('/verify/instantverification/resendreport');

	Route::get('/instantverification/resendreportdetails','OrderController@instantverificationResendDetails')->name('/verify/instantverification/resendreportdetails');

	Route::get('/idChecks','InstantVerificationController@idChecks')->name('/verify/idChecks');

	Route::get('/idCheck/covid19','InstantVerificationController@idCovid19Check')->name('/verify/idCheck/covid19');
	Route::post('/idCovid19Check/covid19','InstantVerificationController@idVerifyCovid19Check')->name('/verify/idCovid19Check/covid19');
	Route::post('/idCovid19Check/covid19ref','InstantVerificationController@idVerifyCovidRefCheck')->name('/verify/idCovid19Check/covid19ref');

	Route::post('/feedback/store','InstantVerificationController@feedback')->name('/verify/feedback/store');

	// pdf generated

	Route::get('/instant/preview','PDFController@previewReport')->name('verify/instant/preview');

	// Route::view('/checkout', 'guest.verifications.checkout');

	// Route::get('/mail',function(){
	// 	$guest_v=DB::table('guest_verifications')->where('id',4)->first();
                
    //             $email=$guest_v->email;
    //             $name=$guest_v->name;
    //             $date=$guest_v->created_at;

    //             $data  = array('name'=>$name,'email'=>$email,'date' => $date,'zip_id'=>base64_encode($guest_v->id),'guest_v'=>$guest_v);
        
    //             Mail::send(['html'=>'mails.guest-payment'], $data, function($message) use($email,$name) {
    //                 $message->to($email, $name)->subject
    //                     ('BCD System - Payment Notification');
    //                 $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
    //             });

	// 			echo 'done';
	// })->name('/guest/mail');


	// Route::get('/orders/downloadpdf/{gv_id}/{service_id}','OrderController@downloadPDF')->name('/guest/orders/downloadpdf');

	Route::match(['get','post'],'/change-password','UserController@changePassword')->name('/verify/change-password');

	Route::get('/invoice','AppController@invoicePDF')->name('/invoice');

});

// route for Store sent link to user's data

// Route::get('/password', function () {
// 		$password = Hash::make('Vickrant@123');
// 		return $password;
// 	});



// Route::get('/new', function (HttpRequest $request) {
// 	$impre['useragent'] = $request->server('HTTP_USER_AGENT');
// 	$input['ip'] = $request->ip();
// 	print_r($input);
// 	return $input;
// });

	