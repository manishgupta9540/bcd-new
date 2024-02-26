@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Account / Contacts </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>
                 <a href="{{ url('/settings/general') }}">Accounts</a>
             </li>
             <li>Contacts</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow">
             <div class="text-right">
             <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div>
      <div class="row">
         
               <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('admin.accounts.left-sidebar') 
                  <!-- start right sec -->
                  </div>
                  <div class="col-md-9 content-wrapper bg-white">
                     <div class="formCover">
                        
                           <div class="col-sm-12 pt-2">
                                 <!-- row --> 
                                 <div class="row">
                                    <div class="col-md-7">
                                       <h4 class="card-title mb-1 mt-3">Contacts Information </h4>
                                       <p class="pb-border">Your contacts about Owner, Dealing officer and Account officer.  </p>
                                    </div>
                                    <div class="col-md-5 float-right text-right">
                                       <?php
                                          $display_id =NULL;
                                          if($profile->display_id!=NULL)
                                          {
                                             $display_id = $profile->display_id;
                                          }
                                          else {
                                             $u_id = str_pad($profile->id, 10, "0", STR_PAD_LEFT);
                                             $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr(Helper::company_name($profile->business_id),0,4)))).'-'.$u_id;
                                          }
                                       ?>
                                       <p class="mb-1 mt-3"><strong> Company Name : </strong> {{Helper::company_name($profile->business_id)}}</p>
                                       <p><strong> Reference No : </strong>{{$display_id}}</p>
                                    </div>
                                    @if ($message = Session::get('success'))
                                       <div class="col-md-12">   
                                          <div class="alert alert-success">
                                          <strong>{{ $message }}</strong> 
                                          </div>
                                       </div>
                                    @endif
                                 </div>
                              @if(stripos(Auth::user()->user_type,'user')!==false)
                                 <!-- ./owner contact detail -->
                                 <div class="row">
                                    <div class="col-md-12">

                                       <div class="row">
                                          <div class="col-md-12">
                                             <h4 class="card-title mb-3 mt-3">Owner contact Information </h4>
                                          </div>
                                       </div>

                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>First name<span class="text-danger">*</span></label>
                                                <input class="form-control number_only" type="text" name="owner_first_name" value="{{ $owner->first_name }}" disabled>
                                                @if ($errors->has('owner_first_name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('owner_first_name') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Last name <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="owner_last_name" value="{{ $owner->last_name }}" disabled>
                                                @if ($errors->has('owner_last_name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('owner_last_name') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Email <span class="text-danger">*</span></label>
                                                <input class="form-control" type="email" name="owner_email" value="{{ $owner->email }}" disabled>
                                                @if ($errors->has('owner_email'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('owner_email') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Phone Number <span class="text-danger">*</span></label>
                                                <input class="form-control number_only" maxlength="10" type="text" name="owner_phone_number" value="{{ $owner->phone}}" disabled>
                                                @if ($errors->has('owner_phone_number'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('owner_phone_number') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>

                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Designation <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="owner_designation" value="{{ $owner->designation }}" disabled>
                                                @if ($errors->has('owner_designation'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('owner_designation') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label> Landline number <span class="text-danger">*</span></label>
                                                <input class="form-control number_only" maxlength="10" type="text" name="owner_landline_number" value="{{ $owner->landline_number }}" disabled>
                                                @if ($errors->has('owner_landline_number'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('owner_landline_number') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>
                                       <!--  -->

                                    </div>
                                 </div>
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-12">
                                       <h4 class="card-title mb-3 mt-3">Dealing Officer </h4>
                                       <p>  </p>
                                    </div>
                                    <div class="col-md-12">
                                    
                                    <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>First name<span class="text-danger">*</span></label>
                                                <input class="form-control number_only" type="text" name="dealing_first_name" value="{{ $dealing->first_name }}" disabled>
                                                @if ($errors->has('dealing_first_name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('dealing_first_name') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Last name </label>
                                                <input class="form-control" type="text" name="dealing_last_name" value="{{ $dealing->last_name }}" disabled>
                                                @if ($errors->has('dealing_last_name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('dealing_last_name') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Email <span class="text-danger">*</span></label>
                                                <input class="form-control" type="email" name="dealing_email" value="{{ $dealing->email }}" disabled>
                                                @if ($errors->has('dealing_email'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('dealing_email') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Phone Number <span class="text-danger">*</span></label>
                                                <input class="form-control number_only" maxlength="10" type="text" name="dealing_phone_number" value="{{ $dealing->phone}}" disabled>
                                                @if ($errors->has('dealing_phone_number'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('dealing_phone_number') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>

                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Designation <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="dealing_designation" value="{{ $dealing->designation }}" disabled>
                                                @if ($errors->has('dealing_designation'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('dealing_designation') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label> Landline number <span class="text-danger">*</span></label>
                                                <input class="form-control number_only" maxlength="10" type="text" name="dealing_landline_number" value="{{ $dealing->landline_number }}"  disabled>
                                                @if ($errors->has('dealing_landline_number'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('dealing_landline_number') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>
                                      
                                    </div>
                                 </div>
                                 <!-- ./owner contact detail -->

                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-12">
                                       <h4 class="card-title mb-3 mt-3">Account officer Information </h4>
                                       <p>  </p>
                                    </div>
                                    <div class="col-md-12">
                                    
                                    <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>First name </label>
                                                <input class="form-control " type="text" name="account_first_name" value=" @if($account !="") {{ $account->first_name }} @endif " disabled>
                                                @if ($errors->has('account_first_name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('account_first_name') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Last name </label>
                                                <input class="form-control" type="text" name="account_last_name" value=" @if($account !="") {{ $account->last_name }} @endif " disabled>
                                                @if ($errors->has('account_last_name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('account_last_name') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Email </label>
                                                <input class="form-control" type="email" name="account_email" value=" @if($account !="") {{ $account->email }} @endif " disabled>
                                                @if ($errors->has('account_email'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('account_email') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Phone Number </label>
                                                <input class="form-control number_only" maxlength="10" type="text" name="account_phone_number" value=" @if($account !="")  {{ $account->phone}} @endif " disabled>
                                                @if ($errors->has('account_phone_number'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('account_phone_number') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Designation </label>
                                                <input class="form-control" type="text" name="account_designation" value="@if($account !="")  {{ $account->designation }} @endif " disabled>
                                                @if ($errors->has('account_designation'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('account_designation') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label> Landline number </label>
                                                <input class="form-control number_only" maxlength="10" type="text" name="account_landline_number" value="@if($account !="")  {{ $owner->landline_number }} @endif " disabled>
                                                @if ($errors->has('account_landline_number'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('account_landline_number') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>
                                       <!-- add more  -->
                                       {{-- <a href="javascript:;" class="btn btn-primary mt-2" id="addMore">Add more <i class="fa fa-plus"></i></a> --}}
                                       {{-- <span class="addMoreDiv"></span> --}}
                                    </div>
                                 </div>
                                 <!-- ./owner contact detail -->
                              @else
                                 <!-- ./owner contact detail -->
                                 <form class="mt-2" method="post" action="{{ url('/contact_info/update') }}">
                                 @csrf
                                    <div class="row">
                                       <div class="col-md-12">

                                          <div class="row">
                                             <div class="col-md-12">
                                                <h4 class="card-title mb-3 mt-3">Owner contact Information </h4>
                                             </div>
                                          </div>

                                          <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>First name<span class="text-danger">*</span></label>
                                                   <input class="form-control number_only" type="text" name="owner_first_name" value="{{ $owner->first_name }}" >
                                                   @if ($errors->has('owner_first_name'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('owner_first_name') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Last name <span class="text-danger">*</span></label>
                                                   <input class="form-control" type="text" name="owner_last_name" value="{{ $owner->last_name }}" >
                                                   @if ($errors->has('owner_last_name'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('owner_last_name') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Email <span class="text-danger">*</span></label>
                                                   <input class="form-control" type="email" name="owner_email" value="{{ $owner->email }}" >
                                                   @if ($errors->has('owner_email'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('owner_email') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Phone Number <span class="text-danger">*</span></label>
                                                   <input type="hidden" id="code" name ="primary_phone_code5" value="{{$profile->phone_code}}" >
                                                   <input type="hidden" id="iso" name ="primary_phone_iso5" value="{{$profile->phone_iso}}" >
                                                   <input class="form-control number_only phone1" maxlength="11" type="text" name="owner_phone_number" value="{{ $owner->phone}}" >
                                                   @if ($errors->has('owner_phone_number'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('owner_phone_number') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                          </div>

                                          <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Designation <span class="text-danger">*</span></label>
                                                   <input class="form-control" type="text" name="owner_designation" value="{{ $owner->designation }}" >
                                                   @if ($errors->has('owner_designation'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('owner_designation') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label> Landline number <span class="text-danger">*</span></label>
                                                   <input class="form-control number_only" maxlength="10" type="text" name="owner_landline_number" value="{{ $owner->landline_number }}" >
                                                   @if ($errors->has('owner_landline_number'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('owner_landline_number') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                          </div>
                                          <!--  -->

                                       </div>
                                    </div>
                                    <!-- row -->
                                    <div class="row">
                                       <div class="col-md-12">
                                          <h4 class="card-title mb-3 mt-3">Dealing Officer </h4>
                                          <p>  </p>
                                       </div>
                                       <div class="col-md-12">
                                       
                                       <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>First name<span class="text-danger">*</span></label>
                                                   <input class="form-control number_only" type="text" name="dealing_first_name" value="{{ $dealing->first_name }}" >
                                                   @if ($errors->has('dealing_first_name'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('dealing_first_name') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Last name </label>
                                                   <input class="form-control" type="text" name="dealing_last_name" value="{{ $dealing->last_name }}" >
                                                   @if ($errors->has('dealing_last_name'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('dealing_last_name') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Email <span class="text-danger">*</span></label>
                                                   <input class="form-control" type="email" name="dealing_email" value="{{ $dealing->email }}" >
                                                   @if ($errors->has('dealing_email'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('dealing_email') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Phone Number <span class="text-danger">*</span></label>
                                                   <input type="hidden" id="code" name ="primary_phone_code5" value="{{$profile->phone_code}}" >
                                                   <input type="hidden" id="iso" name ="primary_phone_iso5" value="{{$profile->phone_iso}}" >

                                                   <input class="form-control number_only phone1" maxlength="11" type="text" name="dealing_phone_number" value="{{ $dealing->phone}}" >
                                                   @if ($errors->has('dealing_phone_number'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('dealing_phone_number') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                          </div>

                                          <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Designation <span class="text-danger">*</span></label>
                                                   <input class="form-control" type="text" name="dealing_designation" value="{{ $dealing->designation }}" >
                                                   @if ($errors->has('dealing_designation'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('dealing_designation') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label> Landline number <span class="text-danger">*</span></label>
                                                   <input class="form-control number_only" maxlength="10" type="text" name="dealing_landline_number" value="{{ $dealing->landline_number }}"  >
                                                   @if ($errors->has('dealing_landline_number'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('dealing_landline_number') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                          </div>
                                       
                                       </div>
                                    </div>
                                    <!-- ./owner contact detail -->

                                    <!-- row -->
                                    <div class="row">
                                       <div class="col-md-12">
                                          <h4 class="card-title mb-3 mt-3">Account officer Information </h4>
                                          <p>  </p>
                                       </div>
                                       <div class="col-md-12">
                                       
                                       <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>First name </label>
                                                   <input class="form-control " type="text" name="account_first_name" value=" @if($account !=""){{ $account->first_name }}@endif" >
                                                   @if ($errors->has('account_first_name'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('account_first_name') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Last name </label>
                                                   <input class="form-control" type="text" name="account_last_name" value=" @if($account !=""){{ $account->last_name }}@endif" >
                                                   @if ($errors->has('account_last_name'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('account_last_name') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Email </label>
                                                   <input class="form-control" type="email" name="account_email" value=" @if($account !=""){{ $account->email }}@endif" >
                                                   @if ($errors->has('account_email'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('account_email') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Phone Number </label>
                                                   <input type="hidden" id="code" name ="primary_phone_code5" value="{{$profile->phone_code}}" >
                                                   <input type="hidden" id="iso" name ="primary_phone_iso5" value="{{$profile->phone_iso}}" >
                                                   <input class="form-control number_only phone1" type="text"  name="account_phone_number" value="@if($account !=""){{$account->phone}}@endif" autocomplete="off" placeholder="81234 56789" >
                                                   @if ($errors->has('account_phone_number'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('account_phone_number') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Designation </label>
                                                   <input class="form-control" type="text" name="account_designation" value="@if($account !=""){{ $account->designation }}@endif" >
                                                   @if ($errors->has('account_designation'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('account_designation') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label> Landline number </label>
                                                   <input class="form-control number_only" maxlength="10" type="text" name="account_landline_number" value="@if($account !=""){{ $account->landline_number }}@endif" >
                                                   @if ($errors->has('account_landline_number'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('account_landline_number') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                          </div>
                                          <!-- add more  -->
                                          {{-- <a href="javascript:;" class="btn btn-primary mt-2" id="addMore">Add more <i class="fa fa-plus"></i></a> --}}
                                          {{-- <span class="addMoreDiv"></span> --}}
                                       </div>
                                    </div>
                                    <!-- ./owner contact detail -->
                                    <div class="text-center">
                                       <button type="submit" class="btn btn-md btn-info">Update</button>
                                    </div>
                                 </form>
                              @endif  
                           </div>
                      
                  </div>
                  <!-- end right sec -->
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

@stack('scripts')
<script type="text/javascript">
//
$(document).ready(function() {
//
// $(document).on('click', '#addMore', function (event) {
//    $(".addMoreDiv").html("<div class='projectReport ' row-id='1' style='padding: 20px; margin-top:15px; border:1px solid #ddd; background:#fff;'><h3 style='padding: 10px;background:#eee;'>Add a new contact </h3><div class='row'><div class='col-sm-6'><div class='form-group'><label style='font-size: 16px;'> Contact Type </label><input class='form-control' type='text' name='type' value=''><small class='text-muted'>Add you contact title (Example: Manager)</small></div></div></div><div class='row'><div class='col-sm-6'><div class='form-group'><label>First name<span class='text-danger'>*</span></label><input class='form-control number_only' type='text' name='pincode' value=''></div></div><div class='col-sm-6'><div class='form-group'><label>Last name <span class='text-danger'>*</span></label><input class='form-control' type='text' name='address' value=''></div></div></div><div class='row'><div class='col-sm-6'><div class='form-group'><label>Email <span class='text-danger'>*</span></label><input class='form-control' type='email' name='business_email' value=''></div></div><div class='col-sm-6'><div class='form-group'><label>Phone Number <span class='text-danger'>*</span></label><input class='form-control number_only' maxlength='10' type='text' name='phone' value=''></div></div></div><div class='row'><div class='col-sm-6'><div class='form-group'><label>Designation <span class='text-danger'>*</span></label><input class='form-control' type='text' name='designation' value=''></div></div><div class='col-sm-6'><div class='form-group'><label> Landline number <span class='text-danger'>*</span></label><input class='form-control number_only' maxlength='10' type='text' name='business_phone_number' value=''></div></div></div><div class='row'><div class='col-sm-6'><div class='form-group'><button class='btn btn-primary save_contact' type='button' name='save_contact' >Save</button></div></div></div></div>");
// });

//submit form
$(document).on('click', '#submitReport', function (event) {
    event.preventDefault();
    var isFormValid = true;
    var selVal  = $('.report_type:checked').val();
    //
    if(selVal == 'other'){

      var chckData = $('.summernote2').summernote('isEmpty');
        if(chckData) {
            
            $('.errorContainer').addClass('show');
            $('.errorContainer').removeClass('hide');
            
            isFormValid = false;
            updateNotification('', 'Please enter requried data.', 'danger');
            return false;
        }
                
    }
     if(selVal == 'project'){

      //
      $('.summernote_editor').each(function() {

        $('.summernote_editor').each(function(v){
          // console.log($(this).summernote('isEmpty'));
          if($(this).summernote('isEmpty')){
            $('.errorContainer').addClass('show');
            $('.errorContainer').removeClass('hide');
            updateNotification('', 'Please enter requried data.', 'danger');
            isFormValid = false;   
          }
        });         
       
       });

      $('.associated_project_list').each(function() {

        $('.associated_project_list').each(function(v){
          // console.log('LLL'+$(this).val());
          if($(this).val() == null){
            $('.errorContainer').addClass('show');
            $('.errorContainer').removeClass('hide');
            updateNotification('', 'Please enter requried data.', 'danger');
            isFormValid = false;   
          }
        });         
       
       });

    }
    //
    if(isFormValid){
      $("#overlay").fadeIn(300);　
      $('#reportForm').submit();
    }

});

   //
   $(document).on('click','#clickSelectFile',function(){ 
       $('#fileupload').trigger('click');       
   });

   $(document).on('click','#clickSelectFile',function(){ 
       $('#fileupload').trigger('click');       
   });

   $(document).on('click','.remove-image',function(){ 
       $('#fileupload').val("");
       $(this).parent('.image-area').detach();
   });
   
   $(document).on('change','#fileupload',function(e){ 
   //show process 
   // $("").html("Uploading...");
   $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 
   
   var fd = new FormData();
   var inputFile = $('#fileupload')[0].files[0];
   fd.append('file',inputFile);
   fd.append('_token', '{{csrf_token()}}');
   //
     $.ajax({
             type: 'POST',
             url: "{{ url('/company/upload/logo') }}",
             data: fd,
             processData: false,
             contentType: false,
             success: function(data) {
               console.log(data);
               if (data.fail == false) {
               
               //reset data
               $('#fileupload').val("");
               $("#fileUploadProcess").html("");
               //append result
               $("#fileResult").html("<div class='image-area'><img src='"+data.filePrev+"'  alt='Preview'><a class='remove-image' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.file_id+"'></div>");
   
               } else {
   
                 $("#fileUploadProcess").html("");
                 alert("please upload valida file! allowed file type , Image, PDF, Doc, Xls and txt ");
                 console.log("file error!");
                 
               }
             },
             error: function(error) {
                 console.log(error);
                 // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
             }
      }); 
       return false;
   
   });

});

      $(".phone1").intlTelInput({
          initialCountry: "in",
          separateDialCode: true,
          // preferredCountries: ["ae", "in"],
          onlyCountries: ["in"],
          geoIpLookup: function (callback) {
              $.get('https://ipinfo.io', function () {
              }, "jsonp").always(function (resp) {
                  var countryCode = (resp && resp.country) ? resp.country : "";
                  callback(countryCode);
              });
          },
          utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"
      });

      /* ADD A MASK IN PHONE1 INPUT (when document ready and when changing flag) FOR A BETTER USER EXPERIENCE */

      var mask1 = $(".phone1").attr('placeholder').replace(/[0-9]/g, 0);

      $(document).ready(function () {
          $('.phone1').mask(mask1)
      });

      //
      $(".phone1").on("countrychange", function (e, countryData) {
          $(".phone1").val('');
          var mask1 = $(".phone1").attr('placeholder').replace(/[0-9]/g, 0);
          $('#phone1').mask(mask1);
          $('#code').val($(".phone1").intlTelInput("getSelectedCountryData").dialCode);
          $('#iso').val($(".phone1").intlTelInput("getSelectedCountryData").iso2);
      });
</script>  

@endsection
