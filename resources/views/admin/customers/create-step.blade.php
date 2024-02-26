@extends('layouts.admin')
@section('content')

<style>
   .image-area img{
      height:90px;
      width:85px;
   }
   .image-area{
      width: 150px;
      height: 135px;
      display: inline-block;
   }

   span.show-hide-password {
            position: absolute;
            top: 34px;
            right: 10px;
            font-size: 14px;
            color: #748a9c;
            cursor: pointer;
   }
   img#preview_img{
      width:100% !important;
   }

   .disabled-link{
      pointer-events: none;
    }

    .sweet-alert button.cancel {
        background: #DD6B55 !important;
    }


    #heading {  
    text-transform: uppercase;  
    color: #003473;  
    font-weight: normal  
}  
#msform {  
    text-align: center;  
    position: relative;  
    margin-top: 20px  
}  
#msform fieldset {  
    background: white;  
    border: 0 none;  
    border-radius: 0.5rem;  
    box-sizing: border-box;  
    width: 100%;  
    margin: 0;  
    padding-bottom: 20px;  
    position: relative  
}  
.form-card {  
    text-align: left  
}  
#msform fieldset:not(:first-of-type) {  
    display: none  
}  

#msform .action-button {  
    width: 100px;  
    background: #003473;  
    font-weight: bold;  
    color: white;  
    border: 0 none;  
    border-radius: 0px;  
    cursor: pointer;  
    padding: 10px 5px;  
    margin: 10px 0px 10px 5px;  
    float: right  
}  
#msform .action-button:hover  
{  
    background-color: #003473  
}  
#msform .action-button:focus {  
    background-color: #003473 
}  
#msform .action-button-pre {  
    width: 100px;  
    background: #616161;  
    font-weight: bold;  
    color: white;  
    border: 0 none;  
    border-radius: 0px;  
    cursor: pointer;  
    padding: 10px 5px;  
    margin: 10px 5px 10px 0px;  
    float: right  
}  
#msform .action-button-pre:hover  
{  
    background-color: #000000  
}  
#msform .action-button-pre:focus {  
    background-color: #000000  
}  
.card {  
    z-index: 0;  
    border: none;  
    position: relative  
}  
.fs-title {  
    font-size: 25px;  
    color: #003473;  
    margin-bottom: 15px;  
    font-weight: normal;  
    text-align: left  
}  
.purple-text {  
    color: #003473;  
    font-weight: normal  
}  
.steps {  
    font-size: 15px;  
    color: gray;  
    margin-bottom: 1px;  
    font-weight: normal;  
    text-align: right  
}  
.fieldlabels {  
    color: gray;  
    text-align: left  
}  
#progressbar {  
    margin-bottom: 20px;  
    overflow: hidden;  
    color: lightgrey ;
    padding-inline-start: 0px;
}  
#progressbar .active {  
    color: #003473;  
}  
#progressbar li {  
    list-style-type: none;  
    font-size: 15px;  
    width: 16.33%;  
    float: left;  
    position: relative;  
    font-weight: 400  
}  
#progressbar #account:before {  
    font-family: FontAwesome;  
    content: "\f13e"  
}  
#progressbar #personal:before {  
    font-family: FontAwesome;  
    content: "\f007"  
}  
#progressbar #personal_contact:before {  
    font-family: FontAwesome;  
    content: "\f2bb"  
}  
#progressbar #sla_list:before {  
    font-family: FontAwesome;  
    content: "\f022"
}  
#progressbar #setting:before {  
    font-family: FontAwesome;  
    content: "\f013"
}  
  
#progressbar #confirm:before {  
    font-family: FontAwesome;  
    content: "\f00c"  
}  
#progressbar li:before {  
    width: 50px;  
    height: 50px;  
    line-height: 45px;  
    display: block;  
    font-size: 20px;  
    color: #ffffff;  
    background: lightgray;  
    border-radius: 50%;  
    margin: 0 auto 10px auto;  
    padding: 2px  
}  
  
#progressbar li:after {  
    content: '';  
    width: 100%;  
    height: 2px;  
    background: lightgray;  
    position: absolute;  
    left: 0;  
    top: 25px;  
    z-index: -1  
}  
#progressbar li.active:before  
{  
    background: #003473;  
}  
#progressbar li.active:after {  
    background: #003473;  
}  
.progress {  
    height: 20px  
}  
  
.pbar {  
    background-color: #003473;  
}  
.fit-image {  
    width: 100%;  
    object-fit: cover  
}  

img.check {
    margin-top: 8px;
}

.wrapper-1{
  width:100%;
  height:100vh;
  display: flex;
flex-direction: column;
}
.wrapper-2{
  padding :30px;
  text-align:center;
}
.congratulation{
  /* font-family: 'Kaushan Script', cursive; */
  /* font-size:4em; */
  letter-spacing:3px;
  /* color:#5892FF ; */
  margin:0;
  margin-bottom:20px;
  margin-top: 21px;
}
.wrapper-2 p {
    margin: 0;
    font-size: 27px;
    color: #002e60;
    font-family: 'Source Sans Pro', sans-serif;
    letter-spacing: 1px;
}

.footer-like p {
    margin: 0;
    padding: 4px;
    color: #444;
    font-family: 'Source Sans Pro', sans-serif;
    letter-spacing: 1px;
}
.footer-like p a {
    text-decoration: none;
    color: #444;
    font-weight: 600;
}

@media (min-width:600px){
  .content{
  max-width:1000px;
  margin:0 auto;
}
  .wrapper-1{
  height: initial;
  max-width:100%;
  margin:0 auto;
 
}


}
</style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
      <!-- ============ Body content start ============= -->
      <div class="main-content">          
         <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ url('/customers') }}">Customer</a></li>
                <li>Create New</li>
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
            
            <div class="card text-left">
              <div class="card-body">
                <div class="col-md-12">
                    <form method="post" enctype="multipart/form-data" action="{{ url('/customers/store_step') }}" id="msform">  
                        @csrf
                          <input type="hidden" name="customer_id" id="customer_id">
                          <h3 class="text-left" style="font-size: 22px; border-bottom:1px solid #ddd; padding-bottom:6px;" class="card-title mb-3">Create a new customer </h3>
                          <p class="text-left"> Fill the required details </p>
                           <ul id="progressbar">  
                               <li class="active" id="account"><strong> </strong></li>  
                               <li id="personal"><strong> </strong></li>
                               <li id="personal_contact"><strong> </strong></li>  
                               <li id="sla_list"><strong> </strong></li>
                               <li id="setting"><strong> </strong></li>  
                               <li id="confirm"><strong></strong></li>  
                           </ul>  
                           <div class="progress">  
                               <div class="pbar pbar-striped pbar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"> </div>  
                           </div> <br>   
                           
                           <fieldset class="step-1">  
                               <div class="form-card">  
                                   <div class="row">  
                                       <div class="col-7">  
                                           <h2 class="fs-title"> Account Information: </h2>  
                                       </div>  
                                       <div class="col-5">  
                                           <h2 class="steps"> Step 1 - 6 </h2>  
                                       </div>
                                   </div> 
                                   @if($user_board!=NULL && $user_board->step_1!=NULL && count($step_err=json_decode($user_board->step_1,true)) > 0)
                                        @php
                                            $step_1_arr = [];
                                            $step_1_arr = json_decode($user_board->step_1,true);
                                        @endphp
                                        <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>First Name <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text"  name="first_name" @if(array_key_exists("first_name",$step_1_arr) && $step_1_arr['first_name']!=NULL) value="{{$step_1_arr['first_name']}}" @endif>
                                                            
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-first_name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Middle Name </label>
                                                        <input class="form-control" type="text"  name="middle_name" @if(array_key_exists("middle_name",$step_1_arr) && $step_1_arr['middle_name']!=NULL) value="{{$step_1_arr['middle_name']}}" @endif>
                                                            
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-middle_name"></p>
                                                    </div>
                                                </div>
                                        </div>
                
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input class="form-control" type="text" name="last_name" @if(array_key_exists("last_name",$step_1_arr) && $step_1_arr['last_name']!=NULL) value="{{$step_1_arr['last_name']}}" @endif>
                                               
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-last_name"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Email <span class="text-danger">*</span></label>
                                                <input class="form-control" type="email" name="email" @if(array_key_exists("email",$step_1_arr) && $step_1_arr['email']!=NULL) value="{{$step_1_arr['email']}}" @endif>
                                                    
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Phone Number <span class="text-danger">*</span></label>
                                                <input type="hidden" id="code" name ="primary_phone_code"  @if(array_key_exists("phone_code",$step_1_arr) && $step_1_arr['phone_code']!=NULL) value="{{$step_1_arr['phone_code']}}" @else value="91" @endif>
                                                <input type="hidden" id="iso" name ="primary_phone_iso" @if(array_key_exists("phone_iso",$step_1_arr) && $step_1_arr['phone_iso']!=NULL) value="{{$step_1_arr['phone_iso']}}" @else value="in" @endif >
                                                <input class="form-control number_only" id="phone1" type="text" name="phone" @if(array_key_exists("phone",$step_1_arr) && $step_1_arr['phone']!=NULL) value="{{$step_1_arr['phone']}}" @endif>
                                                   
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-phone"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Password </label>
                                                <input class="form-control " type="password" name="password" @if(array_key_exists("password",$step_1_arr) && $step_1_arr['password']!=NULL) value="{{$step_1_arr['password']}}" @endif>
                                                <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                                                <small class="text-muted">(If left blank system will send auto-generated password.)</small>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            
                                            </div>
                                            
                                        </div>
                                   @else
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>First Name <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text"  name="first_name" value="{{old('first_name')}}">
                                                        {{-- @if ($errors->has('first_name'))
                                                        <div class="error text-danger">
                                                            {{ $errors->first('first_name') }}
                                                        </div>
                                                        @endif --}}
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-first_name"></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Middle Name </label>
                                                    <input class="form-control" type="text"  name="middle_name" value="{{old('middle_name')}}">
                                                        {{-- @if ($errors->has('middle_name'))
                                                        <div class="error text-danger">
                                                            {{ $errors->first('middle_name') }}
                                                        </div>
                                                        @endif --}}
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-middle_name"></p>
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input class="form-control" type="text" name="last_name" value="{{old('last_name')}}">
                                                {{-- @if ($errors->has('last_name'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('last_name') }}
                                                    </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-last_name"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Email <span class="text-danger">*</span></label>
                                                <input class="form-control" type="email" name="email" value="{{old('email')}}">
                                                    {{-- @if ($errors->has('email'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('email') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Phone Number <span class="text-danger">*</span></label>
                                                <input type="hidden" id="code" name ="primary_phone_code" value="91" >
                                                <input type="hidden" id="iso" name ="primary_phone_iso" value="in" >
                                                <input class="form-control number_only" id="phone1" type="text" name="phone" value="{{old('phone')}}">
                                                    {{-- @if ($errors->has('phone'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('phone') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-phone"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Password </label>
                                                <input class="form-control " type="password" name="password" value="{{old('password')}}">
                                                <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                                                <small class="text-muted">(If left blank system will send auto-generated password.)</small>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            
                                            </div>
                                            
                                        </div>
                                   @endif
                               </div> 
                               <button type="submit" name="next" class="next action-button next-1">Next</button>
                               {{-- <input type="submit" name="next" class="next action-button next-1" value="Next" />   --}}
                           </fieldset>  
                           <fieldset class="step-2">  
                               <div class="form-card">  
                                   <div class="row">  
                                       <div class="col-7">  
                                           <h2 class="fs-title"> Business Information: </h2>  
                                       </div>  
                                       <div class="col-5">  
                                           <h2 class="steps"> Step 2 - 6 </h2>  
                                       </div>  
                                   </div>
                                   @if($user_board!=NULL && $user_board->step_2!=NULL && count($step_err=json_decode($user_board->step_2,true)) > 0)
                                        @php
                                            $step_2_arr = [];
                                            $step_2_arr = json_decode($user_board->step_2,true);
                                        @endphp
                                        <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="company">Company or business name <span class="text-danger">*</span></label>
                                                        <input type="text" name="company" class="form-control" id="company" placeholder="Company" @if(array_key_exists("company_name",$step_2_arr) && $step_2_arr['company_name']!=NULL) value="{{$step_2_arr['company_name']}}" @endif>
                                                        
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-company"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Country <span class="text-danger">*</span></label>
                                                        <select class="form-control country" name="country_id" id="country_id">
                                                        <option value="">Select Country</option>
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->id }}" @if(array_key_exists("country_id",$step_2_arr) && $step_2_arr['country_id']!=NULL && $step_2_arr['country_id']==$country->id) selected @elseif ($country->id == 101) selected @endif >{{ $country->name }}</option>
                                                        @endforeach
                                                        </select>
                                                        
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-country_id"></p>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>State <span class="text-danger">*</span></label>
                                                @php
                                                    $country_id = NULL;
                                                    if(array_key_exists("country_id",$step_2_arr) && $step_2_arr['country_id']!=NULL)
                                                    {
                                                        $country_id = $step_2_arr['country_id'];
                                                    }

                                                    $state_list = Helper::get_states_list($country_id);
                                                @endphp
                                                <select class="form-control state" name="state_id" id="state_id">
                                                <option value="">Select State</option>
                                                    @if(count($state_list)>0)
                                                        @foreach ($state_list as $states)
                                                            <option value="{{ $states->id }}" @if(array_key_exists("state_id",$step_2_arr) && $step_2_arr['state_id']!=NULL && $step_2_arr['state_id']==$states->id) selected @endif >{{ $states->name }}</option>
                                                        @endforeach
                                                    @else
                                                        @foreach($state as $states)
                                                            <option value="{{ $states->id }}" >{{ $states->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-state_id"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>City/Town/District <span class="text-danger">*</span></label>
                                                @php
                                                    $state_id = NULL;
                                                    if(array_key_exists("state_id",$step_2_arr) && $step_2_arr['state_id']!=NULL)
                                                    {
                                                        $state_id = $step_2_arr['state_id'];
                                                    }

                                                    $city_list = Helper::get_city_list($state_id);
                                                @endphp
                                                <select class="form-control" name="city_id" id="city_id">
                                                    @if(count($city_list)>0)
                                                        <option value="">Select City</option>
                                                        @foreach ($city_list as $cities)
                                                            <option value="{{ $cities->id }}" @if(array_key_exists("city_id",$step_2_arr) && $step_2_arr['city_id']!=NULL && $step_2_arr['city_id']==$cities->id) selected @endif >{{ $cities->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-city_id"></p>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Pin Code<span class="text-danger">*</span></label>
                                                <input class="form-control number_only" type="text" name="pincode" @if(array_key_exists("zipcode",$step_2_arr) && $step_2_arr['zipcode']!=NULL) value="{{$step_2_arr['zipcode']}}" @endif>
                                            
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-pincode"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Address (HO) <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="address" @if(array_key_exists("address_line1",$step_2_arr) && $step_2_arr['address_line1']!=NULL) value="{{$step_2_arr['address_line1']}}" @endif>
                                                
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-address"></p>
                                            </div>
                                            </div>
                                        
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Email <span class="text-danger">*</span></label>
                                                <input class="form-control" type="email" name="business_email" @if(array_key_exists("email",$step_2_arr) && $step_2_arr['email']!=NULL) value="{{$step_2_arr['email']}}" @endif>
                                                
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-business_email"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                <label>Phone Number <span class="text-danger">*</span></label>
                                                <input type="hidden" id="code2" name ="primary_phone_code2" @if(array_key_exists("phone_code",$step_2_arr) && $step_2_arr['phone_code']!=NULL) value="{{$step_2_arr['phone_code']}}" @else value="91" @endif >
                                                <input type="hidden" id="iso2" name ="primary_phone_iso2" @if(array_key_exists("phone_iso",$step_2_arr) && $step_2_arr['phone_iso']!=NULL) value="{{$step_2_arr['phone_iso']}}" @else value="in" @endif >
                                                <input class="form-control number_only" id="phone2" type="text" name="business_phone_number" @if(array_key_exists("phone",$step_2_arr) && $step_2_arr['phone']!=NULL) value="{{$step_2_arr['phone']}}" @endif>
                                                
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-business_phone_number"></p>
                                                </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Website </label>
                                            <input class="form-control" type="text" name="website" @if(array_key_exists("website",$step_2_arr) && $step_2_arr['website']!=NULL) value="{{$step_2_arr['website']}}" @endif>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Type of facility </label>
                                            <input class="form-control " type="text" name="type_of_facility" @if(array_key_exists("type_of_facility",$step_2_arr) && $step_2_arr['type_of_facility']!=NULL) value="{{$step_2_arr['type_of_facility']}}" @endif>
                                            </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>TIN Number </label>
                                                <input class="form-control" type="text" name="tin_number" @if(array_key_exists("tin_number",$step_2_arr) && $step_2_arr['tin_number']!=NULL) value="{{$step_2_arr['tin_number']}}" @endif>
                                                
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tin_number"></p>
                                            </div>
                                            </div>
                                            {{-- <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Company Revenue <span class="text-danger">*</span></label>
                                                <select class="form-control revenue" name="revenue" id="revenue">
                                                    <option value="">Select Revenue</option>
                                                    <option value="A" >More than 1 Cr.</option>
                                                    <option value="B">50 lakhs to 1 Cr.</option>
                                                    <option value="C">Less than 50 lakhs</option>
                                                </select>
                                                
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-revenue"></p>
                                            </div>
                                            </div> --}}
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Department </label>
                                                <input class="form-control" type="text" name="department" @if(array_key_exists("department",$step_2_arr) && $step_2_arr['department']!=NULL) value="{{$step_2_arr['department']}}" @endif>
                                                
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-department"></p>
                                            </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Contract Signed By <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="contract_signed_by" @if(array_key_exists("contract_signed_by",$step_2_arr) && $step_2_arr['contract_signed_by']!=NULL) value="{{$step_2_arr['contract_signed_by']}}" @endif>
                                        
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-contract_signed_by"></p>
                                            <small class="text-muted">(Person name who signed the contract)</small>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>HR Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="hr_name" @if(array_key_exists("hr_name",$step_2_arr) && $step_2_arr['hr_name']!=NULL) value="{{$step_2_arr['hr_name']}}" @endif>
                                            
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-hr_name"></p>
                                            </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Contract Start Date <span class="text-danger">*</span></label>
                                            <input class="form-control commonDatepicker contract_start_date" type="text" name="contract_start_date" @if(array_key_exists("work_operating_date",$step_2_arr) && $step_2_arr['work_order_date']!=NULL) value="{{date('Y-m-d',strtotime($step_2_arr['work_order_date']))}}" @endif autocomplete="off">
                                        
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-contract_start_date"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Contract End Date <span class="text-danger">*</span></label>
                                            <input class="form-control commonDatepicker contract_end_date" type="text" name="contract_end_date" @if(array_key_exists("work_operating_date",$step_2_arr) && $step_2_arr['work_operating_date']!=NULL) value="{{date('Y-m-d',strtotime($step_2_arr['work_operating_date']))}}" @endif autocomplete="off">
                                        
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-contract_end_date"></p>
                                            </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Billing Details </label>
                                            <input class="form-control" type="text" name="billing_detail" @if(array_key_exists("billing_detail",$step_2_arr) && $step_2_arr['billing_detail']!=NULL) value="{{$step_2_arr['billing_detail']}}" @endif>
                                            
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Pan Number <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="pan_number" @if(array_key_exists("pan_number",$step_2_arr) && $step_2_arr['pan_number']!=NULL) value="{{$step_2_arr['pan_number']}}" @endif placeholder="Ex:- DPAGA4875J">
                                            
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-pan_number"></p>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Company Logo   <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,svg "></i> <small>   </small></label>
                                            <input class="form-control" type="file" name="company_logo" id="company_logo" accept=".jpeg,.png,.jpg,.gif,.svg">
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-company_logo"></p>
                                            </div>
                                            </div>
                                            @if(array_key_exists("company_logo",$step_2_arr) && $step_2_arr['company_logo']!=NULL)
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label for="company_logo"></label>
                                                    {{-- <span class="d-none btn btn-link float-right text-dark close_btn">X</span> --}}
                                                    <img id="preview_img" src="{{url('uploads/company-logo/')}}/{{$step_2_arr['company_logo']}}" width="200" height="150"/>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label for="company_logo"></label>
                                                    <span class="d-none btn btn-link float-right text-dark close_btn">X</span>
                                                    <img id="preview_img"   width="200" height="150"/>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-4">
                                            <div class="form-group">
                                            <label>Files (Contract files etc.)</label>
                                            <a class='btn-link clickSelectFile' add-id='1' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                                            <input type='file' class='fileupload' name='file' style='display:none'/>
                                            
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                            <div class="form-group fileResult" id='fileResult-1'>
                                            </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-2 mb-3">
                                            <div class="col-sm-12">
                                            <div class="form-check form-check-inline error-control">
                                                <input class="form-check-input gst_exempt" type="checkbox" name="gst_exempt" id="gst_exempt">
                                                <label class="form-check-label" for="gst_exempt">GST Exempt</label>
                                            </div>
                                            </div>
                                        </div>  
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>GST Number <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="gst_number" value="{{old('gst_number')}}" placeholder="Ex:- 22AAAAA4444A1Z5">
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gst_number"></p>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>GST Attachment <span class="text-danger">*</span> <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,svg,pdf"></i> <small>   </small></label>
                                                    <div class="custom-file error-control">
                                                        <input type="file" name="gst_attachment" class="custom-file-input gst_attachment" id="gst_attachment" data-pdf="{{url('/').'/admin/images/icon_pdf.png'}}" accept="image/*,.pdf">
                                                        <label class="custom-file-label" id="gst_label" for="gst_attachment">Choose File...</label>
                                                    </div>
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gst_attachment"></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 w-100">
                                                <div class="form-group">
                                                    <label for="gst_attachment"></label>
                                                    <span class="d-none btn btn-link float-right text-dark close_gst_btn">X</span>
                                                    <img id="preview_gst_img" width="200" height="150"/>
                                                </div>
                                            </div>
                                        </div>
                                   @else
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="company">Company or business name <span class="text-danger">*</span></label>
                                                    <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{ old('company') }}">
                                                    {{-- @if ($errors->has('company'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('company') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-company"></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Country <span class="text-danger">*</span></label>
                                                    <select class="form-control country" name="country_id" id="country_id">
                                                    <option value="">Select Country</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}" @if($country->id == 101) selected="" @endif >{{ $country->name }}</option>
                                                    @endforeach
                                                    </select>
                                                   
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-country_id"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>State <span class="text-danger">*</span></label>
                                                <select class="form-control state" name="state_id" id="state_id">
                                                <option value="">Select State</option>
                                                @foreach($state as $states)
                                                    <option value="{{ $states->id }}">{{ $states->name }}</option>
                                                @endforeach
                                                </select>
                                                {{-- @if ($errors->has('state_id'))
                                                <div class="error text-danger">
                                                    {{ $errors->first('state_id') }}
                                                </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-state_id"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>City/Town/District <span class="text-danger">*</span></label>
                                                <select class="form-control" name="city_id" id="city_id">
                                                </select>
                                                {{-- @if ($errors->has('city_id'))
                                                <div class="error text-danger">
                                                    {{ $errors->first('city_id') }}
                                                </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-city_id"></p>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Pin Code<span class="text-danger">*</span></label>
                                                <input class="form-control number_only" type="text" name="pincode" value="{{old('pincode')}}">
                                                {{-- @if ($errors->has('pincode'))
                                                <div class="error text-danger">
                                                    {{ $errors->first('pincode') }}
                                                </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-pincode"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Address (HO) <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="address" value="{{old('address')}}">
                                                {{-- @if ($errors->has('address'))
                                                <div class="error text-danger">
                                                    {{ $errors->first('address') }}
                                                </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-address"></p>
                                            </div>
                                            </div>
                                        
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Email <span class="text-danger">*</span></label>
                                                <input class="form-control" type="email" name="business_email" value="{{old('business_email')}}">
                                                {{-- @if ($errors->has('business_email'))
                                                <div class="error text-danger">
                                                    {{ $errors->first('business_email') }}
                                                </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-business_email"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                <label>Phone Number <span class="text-danger">*</span></label>
                                                <input type="hidden" id="code2" name ="primary_phone_code2" value="91" >
                                                <input type="hidden" id="iso2" name ="primary_phone_iso2" value="in" >
                                                <input class="form-control number_only" id="phone2" type="text" name="business_phone_number" value="{{old('business_phone_number')}}">
                                                {{-- @if ($errors->has('business_phone_number'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('business_phone_number') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-business_phone_number"></p>
                                                </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Website </label>
                                            <input class="form-control" type="text" name="website" value="{{old('website')}}">
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Type of facility </label>
                                            <input class="form-control " type="text" name="type_of_facility" value="{{old('type_of_facility')}}">
                                            </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>TIN Number </label>
                                                <input class="form-control" type="text" name="tin_number" value="{{old('tin_number')}}">
                                                {{-- @if ($errors->has('tin_number'))
                                                <div class="error text-danger">
                                                    {{ $errors->first('tin_number') }}
                                                </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tin_number"></p>
                                            </div>
                                            </div>
                                            {{-- <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Company Revenue <span class="text-danger">*</span></label>
                                                <select class="form-control revenue" name="revenue" id="revenue">
                                                    <option value="">Select Revenue</option>
                                                    <option value="A" >More than 1 Cr.</option>
                                                    <option value="B">50 lakhs to 1 Cr.</option>
                                                    <option value="C">Less than 50 lakhs</option>
                                                </select>
                                                
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-revenue"></p>
                                            </div>
                                            </div> --}}
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Department </label>
                                                <input class="form-control" type="text" name="department">
                                                {{-- @if ($errors->has('department'))
                                                <div class="error text-danger">
                                                    {{ $errors->first('department') }}
                                                </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-department"></p>
                                            </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Contract Signed By <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="contract_signed_by" value="{{old('contract_signed_by')}}">
                                            {{-- @if ($errors->has('contract_signed_by'))
                                                <div class="error text-danger">
                                                    {{ $errors->first('contract_signed_by') }}
                                                </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-contract_signed_by"></p>
                                            <small class="text-muted">(Person name who signed the contract)</small>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>HR Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="hr_name" value="{{old('hr_name')}}">
                                            {{-- @if ($errors->has('hr_name'))
                                                <div class="error text-danger">
                                                    {{ $errors->first('hr_name') }}
                                                </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-hr_name"></p>
                                            </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Contract Start Date <span class="text-danger">*</span></label>
                                            <input class="form-control commonDatepicker contract_start_date" type="text" name="contract_start_date" value="{{old('contract_start_date')}}" autocomplete="off">
                                            {{-- @if ($errors->has('contract_start_date'))
                                                <div class="error text-danger">
                                                    {{ $errors->first('contract_start_date') }}
                                                </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-contract_start_date"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Contract End Date <span class="text-danger">*</span></label>
                                            <input class="form-control commonDatepicker contract_end_date" type="text" name="contract_end_date" value="{{old('contract_end_date')}}" autocomplete="off">
                                            {{-- @if ($errors->has('contract_end_date'))
                                                <div class="error text-danger">
                                                    {{ $errors->first('contract_end_date') }}
                                                </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-contract_end_date"></p>
                                            </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Billing Details </label>
                                            <input class="form-control" type="text" name="billing_detail" value="{{old('billing_detail')}}">
                                            
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Pan Number <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="pan_number" value="{{old('pan_number')}}" placeholder="Ex:- DPAGA4875J">
                                            {{-- @if ($errors->has('pan_number'))
                                                <div class="error text-danger">
                                                    {{ $errors->first('pan_number') }}
                                                </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-pan_number"></p>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label>Company Logo   <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,svg "></i> <small>   </small></label>
                                            <input class="form-control" type="file" name="company_logo" id="company_logo" accept=".jpeg,.png,.jpg,.gif,.svg">
                                            {{-- @if ($errors->has('company_logo'))
                                                <div class="error text-danger">
                                                    {{ $errors->first('company_logo') }}
                                                </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-company_logo"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                            <label for="company_logo"></label>
                                            <span class="d-none btn btn-link float-right text-dark close_btn">X</span>
                                            <img id="preview_img"   width="200" height="150"/>
                                            </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-4">
                                            <div class="form-group">
                                            <label>Files (Contract files etc.)</label>
                                            <a class='btn-link clickSelectFile' add-id='1' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                                            <input type='file' class='fileupload' name='file' style='display:none'/>
                                            
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                            <div class="form-group fileResult" id='fileResult-1'>
                                            </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-2 mb-3">
                                            <div class="col-sm-12">
                                            <div class="form-check form-check-inline error-control">
                                                <input class="form-check-input gst_exempt" type="checkbox" name="gst_exempt" id="gst_exempt">
                                                <label class="form-check-label" for="gst_exempt">GST Exempt</label>
                                            </div>
                                            </div>
                                        </div>  
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>GST Number <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="gst_number" value="{{old('gst_number')}}" placeholder="Ex:- 22AAAAA4444A1Z5">
                                                {{-- @if ($errors->has('gst_number'))
                                                <div class="error text-danger">
                                                    {{ $errors->first('gst_number') }}
                                                </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gst_number"></p>
                                            </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>GST Attachment <span class="text-danger">*</span> <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,svg,pdf"></i> <small>   </small></label>
                                                <div class="custom-file error-control">
                                                    <input type="file" name="gst_attachment" class="custom-file-input gst_attachment" id="gst_attachment" data-pdf="{{url('/').'/admin/images/icon_pdf.png'}}" accept="image/*,.pdf">
                                                    <label class="custom-file-label" id="gst_label" for="gst_attachment">Choose File...</label>
                                                </div>
                                                {{-- @if ($errors->has('gst_attachment'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('gst_attachment') }}
                                                    </div>
                                                @endif --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gst_attachment"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6 w-100">
                                            <div class="form-group">
                                                <label for="gst_attachment"></label>
                                                <span class="d-none btn btn-link float-right text-dark close_gst_btn">X</span>
                                                <img id="preview_gst_img" width="200" height="150"/>
                                            </div>
                                            </div>
                                        </div>
                                   @endif
            
                                 <a href="javascript:;" class="add_spoke"><i class="fa fa-plus mb-3"></i> Add Spokeman</a>
                                 <span class="addSpokeDiv"></span><br>
                               </div> 
                               <button type="submit" name="next" class="next action-button next-2">Next</button> 
                               <input type="button" name="pre" class="pre action-button-pre" value="Prev" />  
                           </fieldset>  
                           <fieldset class="step-3">  
                               <div class="form-card">  
                                   <div class="row">  
                                       <div class="col-7">  
                                           <h2 class="fs-title"> Contact Information: </h2>  
                                       </div>  
                                       <div class="col-5">  
                                           <h2 class="steps"> Step 3 - 6 </h2>  
                                       </div> 
                                   </div>
                                   <!-- Owner/HOD Information  -->
                                   <div class="row">
                                        <div class="col-md-12">
                                        <h4 class="card-title mb-3 mt-3">Owner/HOD Information </h4> 
                                        <p>  </p>         
                                        </div>
                                        <div class="col-md-12">    
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>First Name <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text"  name="owner_first_name" value="{{old('owner_first_name')}}">
                                                    {{-- @if ($errors->has('owner_first_name'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('owner_first_name') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_first_name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Middle Name </label>
                                                    <input class="form-control" type="text"  name="owner_middle_name" value="{{old('owner_middle_name')}}">
                                                        {{-- @if ($errors->has('middle_name'))
                                                            <div class="error text-danger">
                                                                {{ $errors->first('middle_name') }}
                                                            </div>
                                                        @endif --}}
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_middle_name"></p>
                                                    </div>
                                                </div>
                                            </div>      
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Last Name </label>
                                                    <input class="form-control" type="text" name="owner_last_name" value="{{old('owner_last_name')}}">
                                                    {{-- @if ($errors->has('owner_last_name'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('owner_last_name') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_last_name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Email <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="email" name="owner_email" value="{{old('owner_email')}}">
                                                    {{-- @if ($errors->has('owner_email'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('owner_email') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_email"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Phone Number <span class="text-danger">*</span></label>
                                                    <input type="hidden" id="code3" name ="primary_phone_code3" value="91" >
                                                    <input type="hidden" id="iso3" name ="primary_phone_iso3" value="in" >
                                                    <input class="form-control number_only" id="phone3" type="text" name="owner_phone_number" value="{{old('owner_phone_number')}}">
                                                    {{-- @if ($errors->has('owner_phone_number'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('owner_phone_number') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_phone_number"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Designation <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text" name="owner_designation" value="{{old('owner_designation')}}">
                                                    {{-- @if ($errors->has('owner_designation'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('owner_designation') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_designation"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Landline Number </label>
                                                    <input class="form-control number_only owner_landline_number" type="text" maxlength="10" name="owner_landline_number" value="{{old('owner_landline_number')}}">
                                                    {{-- @if ($errors->has('owner_landline_number'))
                                                            <div class="error text-danger">
                                                                {{ $errors->first('owner_landline_number') }}
                                                            </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_landline_number"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                   </div>
                                    <!-- ./ -->
                                    <!-- Owner/HOD Information  -->
                                    <div class="row">
                                        <div class="col-md-12">
                                        <h4 class="card-title mb-3 mt-3">Dealing Officer Details</h4> 
                                        <p>  </p>         
                                        </div>
                                        <div class="col-md-12">    
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>First Name </label>
                                                    <input class="form-control" type="text"  name="dealing_first_name" value="{{old('dealing_first_name')}}">
                                                    {{-- @if ($errors->has('dealing_first_name'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('dealing_first_name') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dealing_first_name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Middle Name </label>
                                                    <input class="form-control" type="text"  name="dealing_middle_name" value="{{old('dealing_middle_name')}}">
                                                        {{-- @if ($errors->has('middle_name'))
                                                            <div class="error text-danger">
                                                                {{ $errors->first('middle_name') }}
                                                            </div>
                                                        @endif --}}
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dealing_middle_name"></p>
                                                    </div>
                                                </div>
                                            </div>      
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Last Name </label>
                                                    <input class="form-control" type="text" name="dealing_last_name" value="{{old('dealing_last_name')}}">
                                                    {{-- @if ($errors->has('dealing_last_name'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('dealing_last_name') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dealing_last_name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Email </label>
                                                    <input class="form-control" type="email" name="dealing_email" value="{{old('dealing_email')}}">
                                                    {{-- @if ($errors->has('dealing_email'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('dealing_email') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dealing_email"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Phone Number </label>
                                                    <input type="hidden" id="code4" name ="primary_phone_code4" value="91" >
                                                    <input type="hidden" id="iso4" name ="primary_phone_iso4" value="in" >
                                                    <input class="form-control number_only" type="text" id="phone4" name="dealing_phone_number" value="{{old('dealing_phone_number')}}">
                                                    {{-- @if ($errors->has('dealing_phone_number'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('dealing_phone_number') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dealing_phone_number"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Designation </label>
                                                    <input class="form-control" type="text" name="dealing_designation" value="{{old('dealing_designation')}}">
                                                    {{-- @if ($errors->has('dealing_designation'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('dealing_designation') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dealing_designation"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Landline Number </label>
                                                    <input class="form-control number_only" type="text" maxlength="10" name="dealing_landline_number" value="{{old('dealing_landline_number')}}">
                                                    {{-- @if ($errors->has('dealing_landline_number'))
                                                            <div class="error text-danger">
                                                                {{ $errors->first('dealing_landline_number') }}
                                                            </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dealing_landline_number"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ./ -->
                                    <!-- Owner/HOD Information  -->
                                    <div class="row">
                                            <div class="col-md-12">
                                            <h4 class="card-title mb-3 mt-3">Account Officer Details </h4> 
                                            <p>  </p>         
                                            </div>
                                        <div class="col-md-12">    
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>First Name</label>
                                                    <input class="form-control" type="text"  name="account_first_name" value="{{old('account_first_name')}}">
                                                    {{-- @if ($errors->has('account_first_name'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('account_first_name') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-account_first_name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Middle Name </label>
                                                    <input class="form-control" type="text"  name="account_middle_name" value="{{old('account_middle_name')}}">
                                                        {{-- @if ($errors->has('middle_name'))
                                                            <div class="error text-danger">
                                                                {{ $errors->first('middle_name') }}
                                                            </div>
                                                        @endif --}}
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-account_middle_name"></p>
                                                    </div>
                                                </div>
                                            </div>      
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Last Name </label>
                                                    <input class="form-control" type="text" name="account_last_name" value="{{old('account_last_name')}}">
                                                    {{-- @if ($errors->has('account_last_name'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('account_last_name') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-account_last_name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Email </label>
                                                    <input class="form-control" type="email" name="account_email" value="{{old('account_email')}}">
                                                    {{-- @if ($errors->has('account_email'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('account_email') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-account_email"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Phone Number</label>
                                                    <input type="hidden" id="code5" name ="primary_phone_code5" value="91" >
                                                    <input type="hidden" id="iso5" name ="primary_phone_iso5" value="in" >
                                                    <input class="form-control number_only" type="text" id="phone5" name="account_phone_number" value="{{old('account_phone_number')}}">
                                                    {{-- @if ($errors->has('account_phone_number'))
                                                    <div class="error text-danger">
                                                        {{ $errors->first('account_phone_number') }}
                                                    </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-account_phone_number"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Designation</label>
                                                    <input class="form-control" type="text" name="account_designation" value="{{old('account_designation')}}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Landline Number </label>
                                                    <input class="form-control number_only account_landline_number" type="text" maxlength="10" name="account_landline_number" value="{{old('account_landline_number')}}">
                                                    {{-- @if ($errors->has('account_landline_number'))
                                                            <div class="error text-danger">
                                                                {{ $errors->first('account_landline_number') }}
                                                            </div>
                                                    @endif --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-account_landline_number"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="addDiv"></span>
                        
                                            <a href="javascript:;" class="add">Add <i class="fa fa-plus"></i></a><br>
                                            
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-12">
                                                    <div class="form-group">
                                                    <strong>Select Primary CAM:</strong> <span class="text-danger">*</span>
                                                    
                                                    <div class="col-sm-12 col-md-12 col-12">
                                                        <div class="form-group">
                                                            <select class="select-option-field-7 user selectValue form-control" name="user" data-type="user" data-t="{{ csrf_token() }}">
                                                                <option value="">Select CAM</option>
                                                                @foreach ($users as $user)
                                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            {{-- @if ($errors->has('user'))
                                                                <div class="error text-danger">
                                                                {{ $errors->first('user') }}
                                                                </div>
                                                            @endif --}}
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-user"></p>
                                                        </div>
                                                    </div>
                                                    {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-user"></p> --}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-12">
                                                    <div class="form-group">
                                                    <strong>Select Secondary CAM:</strong> <span class="text-danger">*</span>
                                                    
                                                    <div class="col-sm-12 col-md-12 col-12">
                                                        <div class="form-group">
                                                            <select class="select-option-field-7 secondary selectValue form-control" name="secondary" data-type="secondary" data-t="{{ csrf_token() }}">
                                                                <option value="">Select CAM</option>
                                                                @foreach ($users as $user)
                                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            {{-- @if ($errors->has('secondary'))
                                                                <div class="error text-danger">
                                                                {{ $errors->first('secondary') }}
                                                                </div>
                                                            @endif --}}
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-secondary"></p>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                                    {{-- <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="form-group">
                                                            <strong>Select KAM:</strong> <span class="text-danger">*</span>
                                                        
                                                            <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        @foreach($users as $user)
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input " type="checkbox" name="kams[]" value="{{$user->id}}" id="inlineCheckbox-{{ $user->id}}" >
                                                                            <label class="form-check-label" for="inlineCheckbox-{{ $user->id}}">{{ $user->name  }}</label>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                            </div>
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                        </div>
                                    </div>
                               </div> 
                               <button type="button" name="next" class="next action-button next-3">Next</button> 
                               <input type="button" name="pre" class="pre action-button-pre" value="Prev" />  
                           </fieldset> 
                           <fieldset class="step-4">
                                <div class="form-card">  
                                    <div class="row">
                                        <div class="col-7">  
                                            <h2 class="fs-title"> SLA: </h2>  
                                        </div>  
                                        <div class="col-5">  
                                            <h2 class="steps"> Step 4 - 6 </h2>  
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                               <label>SLA Name <span class="text-danger">*</span></label>
                                               <input class="form-control" type="text" name="name" >
                                               <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-name"></p>
                                            </div>
                                         </div>
                                         <div class="col-sm-6">
                                            <div class="form-group">
                                               <label>Internal TAT <span class="text-danger">*</span></label>
                                               <input class="form-control" type="text" name="tat" >
                                               <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tat"></p>
                                               <small class="text-muted">Days in number</small>
                                            </div>
                                         </div>  
                                         <div class="col-sm-6">
                                            <div class="form-group">
                                               <label>Client TAT <span class="text-danger">*</span></label>
                                               <input class="form-control" type="text" name="client_tat" >
                                               <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-client_tat"></p>
                                               <small class="text-muted">Days in number</small>
                                            </div>
                                         </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                           <div class="form-group">
                                              <label class="pb-1" for="name">Days Type <span class="text-danger">*</span></label> <br>
                                              <label class="radio-inline pr-2">
                                                 <input type="radio" class="days_type" name="days_type" value="working"> Working Days </label> 
                                                 <label class="radio-inline"> 
                                                    <input type="radio" class="days_type" name="days_type" value="calender" > Calender Days 
                                                 </label>
                                                 <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-days_type"></p>
                                           </div>
                                        </div>   
                                    </div>
                                     <div class="row">
                                        <div class="col-sm-6">
                                           <div class="form-group">
                                              <label class="pb-1" for="name">TAT Type <span class="text-danger">*</span></label> <br>
                                              <label class="radio-inline pr-2">
                                                 <input type="radio" class="tat_type" name="tat_type" value="case"> Case-Wise </label> 
                                                 <label class="radio-inline"> 
                                                    <input type="radio" class="tat_type" name="tat_type" value="check" > Check-Wise 
                                                 </label>
                                                 <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tat_type"></p>
                                           </div>
                                        </div>   
                                     </div>
                                     <div class="tat_result">
                                        
                                     </div>
                                     <div class="row">
                                        <div class="col-md-12">
                                           <div class="form-group">
                                              <label class="pb-1" for="name">Price Type <span class="text-danger">*</span></label> <br>
                                              <label class="radio-inline pr-2">
                                                 <input type="radio" class="price_type" name="price_type" value="package"> Package-Wise </label> 
                                                 <label class="radio-inline"> 
                                                    <input type="radio" class="price_type" name="price_type" value="check" checked> Check-Wise 
                                                 </label>
                                                 <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-price_type"></p>
                                           </div>
                                        </div>
                                     </div>
                                     <div class="price_result">
                                       
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                           <div class="form-group">
                                              <label>Select Check Item <span class="text-danger">*</span></label>
                                              
                                              <div class="col-sm-12">
                                                 <div class="form-group">
                                                    @foreach($services as $service)
                                                       <div class="form-check form-check-inline">
                                                          <input class="form-check-input services_list" type="checkbox" name="services[]" value="{{ $service->id}}" data-string="{{ $service->name  }}" data-type="{{ $service->is_multiple_type }}" id="inlineCheckbox-{{ $service->id}}" data-verify={{$service->verification_type}}>
                                                          <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label>
                                                       </div>
                                                    @endforeach
                                                 </div>
                                              </div>
                                           <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
                                           </div>
                                        </div>
 
                                        <div class="col-sm-6">
                                           
                                        </div>
                                    </div>

                                    <div class="service_result" style="border: 1px solid #ddd; padding:10px;">
                                        <div class="row">
                                           <div class="col-sm-12 mt-1 mb-2">
                                              <span style="color:#dd2e2e">Configure Number of Verifications Need on each check item</span>
                                              <span style="float: right;">
                                                 <span class="pr-2"> Total Checks:- <span class="total_checks">0</span></span>
                                                 <span class="total_p"> Total Price:- <i class='fas fa-rupee-sign'></i> <span class="total_check_price">0.00</span></span>
                                              </span>
                                           </div>
                                        </div>
                                     </div>
                                </div>
                                <button type="button" name="next" class="next action-button next-4">Next</button> 
                                <input type="button" name="pre" class="pre action-button-pre" value="Prev" /> 
                           </fieldset>
                           <fieldset class="step-5">
                                <div class="form-card">  
                                    <div class="row">
                                        <div class="col-7">  
                                            <h2 class="fs-title"> Setting: </h2>  
                                        </div>  
                                        <div class="col-5">  
                                            <h2 class="steps"> Step 5 - 6 </h2>  
                                        </div> 
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="label_name">Billing Cycle Period : <span class="text-danger">*</span></label>
                                                <select class="form-control cycle_period" name="cycle_period">
                                                    <option value="">--Select--</option>
                                                    <option value="half_monthly">15 Days</option>
                                                    <option value="monthly" selected>Monthly</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <button type="button" name="next" class="next action-button next-5">Next</button> 
                                <input type="button" name="pre" class="pre action-button-pre" value="Prev" /> 
                           </fieldset> 
                           <fieldset class="step-6">  
                               <div class="form-card">  
                                   <div class="row">  
                                       <div class="col-7">  
                                           <h2 class="fs-title"> Finish: </h2>  
                                       </div>  
                                       <div class="col-5">  
                                           <h2 class="steps"> Step 6 - 6 </h2>  
                                       </div>  
                                   </div> 
                                   {{-- <br> <br>   --}}
                                   {{-- <h2 class="purple-text text-center"><strong> SUCCESS !! </strong></h2> <br>  
                                   <div class="row justify-content-center">  
                                       <div class="col-3"> <img src="{{asset('admin/images/thank_check.png')}}" class="fit-image"> </div>  
                                   </div> <br><br>  
                                   <div class="row justify-content-center">  
                                       <div class="col-7 text-center">  
                                           <h5 class="purple-text text-center"> Customer Has Been Created Successfully </h5>  
                                       </div>  
                                   </div>   --}}
                                   <div class=content>
                                    <div class="wrapper-1">
                                        <div class="wrapper-2">
                                            {{-- <img src="{{ asset('admin/images/BCD-Logo2.png')}}"> --}}
                                            <p>XYZ Pvt. Ltd</p>
                                            <h1 class="congratulation text-danger">CONGRATULATIONS</h1>
                                            <p>Customer Has Been on-boarded Successfully !! </p>
                                            <img src="{{ asset('admin/images/thank_check.png')}}" class="check">
                                            <p class="thanku">Thank You </p>
                                        </div>
                                    </div>
                                  </div>
                               </div>  
                           </fieldset>  
                        </form>
                </div>
              </div>
            
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
         
        </div>

<script>
$(function(){

    $(document).on('change','.customer_type',function(e) {
        e.preventDefault();
        var selVal = $('.customer_type option:selected').val();
        if(selVal =='with_subscription')
        {
            $('.subscription_list').removeClass('d-none');
            $('.subscription_list').addClass('d-block');
            $('.sla_list').addClass('d-none');
            $('.sla_list').removeClass('d-block');
        }

        if(selVal =='with_sla')
        {   $('.subscription_list').removeClass('d-block');
            $('.subscription_list').addClass('d-none');
            $('.sla_list').removeClass('d-none');
            $('.sla_list').addClass('d-block');
        }
        
    });

   $(document).on('submit', 'form#addContactForm', function (event) {
      event.preventDefault();
      //clearing the error msg
      $('p.error_container').html("");
      $("#coupon-error").html("");
      $("#otp_error").html("");   
      $('#guest-address-error').html("");

      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");

      $.ajax({
         type: form.attr('method'),
         url: url,
         data: data,
         cache: false,
         contentType: false,
         processData: false,      
         success: function (response) {

               console.log(response);
               if(response.success==true  ) {          
                  $("tbody.contactList").prepend("<tr><td scope='row'><input type='checkbox'></td><td><a href=''>"+response.data.name+"</a></td><td> "+response.data.email+"</td><td>"+response.data.phone+"</td><td> </td><td>"+response.data.associated_company+"</td></tr>");
               }
               //show the form validates error
               if(response.success==false ) {                              
                  for (control in response.errors) {   
                     $('#error-' + control).html(response.errors[control]);
                  }
               }
         },
         error: function (xhr, textStatus, errorThrown) {
               // alert("Error: " + errorThrown);
         }
      });
      return false;
   });

   $(document).on('change','.contract_start_date',function() {

      var from = $('.contract_start_date').datepicker('getDate');
      var to_date   = $('.contract_end_date').datepicker('getDate');

      if($('.contract_end_date').val() !=""){
         if (from > to_date) {
            alert ("Please select appropriate date range!");
            $('.contract_start_date').val("");
            $('.contract_end_date').val("");
         }

      }

      // alert(from);
      // alert(to_date);

      // alert("heello");
     
   });

   $(document).on('change','.contract_end_date',function() {

      var to_date = $('.contract_end_date').datepicker('getDate');
      var from   = $('.contract_start_date').datepicker('getDate');
      if($('.contract_start_date').val() !=""){
         if (from > to_date) {
         alert ("Please select appropriate date range!");
         $('.contract_start_date').val("");
         $('.contract_end_date').val("");
         
         }
      }


   });


});
//

</script>

<script>

   $(document).ready(function(){
        var current_fs, next_fs, pre_fs;   
            var opacity;  
            var current = 1;  
            var steps = $("fieldset").length;  
            setProgressBar(current);  

            $(document).on('submit', 'form#msform', function (event) {
                event.preventDefault();
                //clearing the error msg
                $('p.error_container').html("");
                var current_fs = $('.next-'+current).parent();  
                var next_fs = $('.next-'+current).parent().next();  

                var form = $(this);
                var data = new FormData($(this)[0]);
                data.append('step',current);
                var url = form.attr("action");
                var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
                $('.next-'+current).attr('disabled',true);
                $('.next-'+current).css({'opacity': '.5'});
                $('.form-control').attr('readonly',true);
                $('.form-control').addClass('disabled-link');
                $('.error-control').addClass('disabled-link');
                if ($('.next-'+current).html() !== loadingText) {
                    $('.next-'+current).html(loadingText);
                }
                    $.ajax({
                        type: form.attr('method'),
                        url: url,
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,      
                        success: function (response) {
                            window.setTimeout(function(){
                                $('.next-'+current).attr('disabled',false);
                                $('.form-control').attr('readonly',false);
                                $('.form-control').removeClass('disabled-link');
                                $('.error-control').removeClass('disabled-link');
                                $('.next-'+current).css({'opacity': '1'});
                                if(current==5)
                                {
                                    $('.next-'+current).html('Submit');
                                }
                                else
                                {
                                    $('.next-'+current).html('Next');
                                }
                            },2000);
                            // console.log(response);
                            if(response.success==true) {          
                            
                                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");  
                                next_fs.show();  
                                current_fs.animate({opacity: 0}, {  
                                    step: function(now) {  
                                    opacity = 1 - now;  
                                    current_fs.css({  
                                    'display': 'none',  
                                    'position': 'relative'  
                                    });  
                                    next_fs.css({'opacity': opacity});  
                                    },  
                                    duration: 500  
                                });  
                                setProgressBar(++current);
                            
                            }
                            else if(response.success==false && response.error_type=='message')
                            {
                                toastr.error(response.message);
                            }
                            else if(response.success==false && response.error_type=='validation') {                              
                                for (control in response.errors) {  
                                var error_text = control.replace('.',"_");
                                $('#error-'+error_text).html(response.errors[control]);
                                // $('#error-'+error_text).html(response.errors[error_text][0]);
                                // console.log('#error-'+error_text);
                                }
                                // console.log(response.errors);
                            }
                        },
                        error: function (response) {
                            // alert("Error: " + errorThrown);
                            console.log(response);
                        }
                    });
                    
                    event.stopImmediatePropagation();
                    return false;
            });

            // $(".next").click(function() {  
            //     current_fs = $(this).parent();  
            //     next_fs = $(this).parent().next();  
            //     $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");  
            //     next_fs.show();  
            //     current_fs.animate({opacity: 0}, {  
            //         step: function(now) {  
            //         opacity = 1 - now;  
            //         current_fs.css({  
            //         'display': 'none',  
            //         'position': 'relative'  
            //         });  
            //         next_fs.css({'opacity': opacity});  
            //         },  
            //         duration: 500  
            //     });  
            //     setProgressBar(++current);  
            // });  

            $(".pre").click(function() {  
                current_fs = $(this).parent();  
                pre_fs = $(this).parent().prev();  
                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");  
                pre_fs.show();  
                current_fs.animate({opacity: 0}, {  
                    step: function(now) {  
                        opacity = 1 - now;  
                        current_fs.css({  
                        'display': 'none',  
                        'position': 'relative'  
                        });  
                        pre_fs.css({'opacity': opacity});  
                    },  
                    duration: 500  
                });  
                setProgressBar(--current);  
            });

            function setProgressBar(curStep) {  
                var percent = parseFloat(100 / steps) * curStep;  
                percentpercent = percent.toFixed();  
                $(".pbar")  
                .css("width",percent+"%")  
            }  

            $(".services_list").change(function() {

                        var total_price = 0;

                        var total_check = 0;

                        if(this.checked)
                        {
                        
                        var id =  $(this).attr("value");
                        var text =  $(this).attr("data-string");
                        var verify =$(this).attr("data-verify");
                        var tat = 1;

                        var readonly = '';

                        var display_none = '';

                        var price_type = $('.price_type:checked').val();

                        if(price_type.toLowerCase()=='package'.toLowerCase())
                        {
                            readonly = 'readonly';

                            display_none = 'd-none';
                        }

                        if(text.toLowerCase()=='Address'.toLowerCase())
                        {
                            tat=7;
                        }
                        else if(text.toLowerCase()=='Employment'.toLowerCase())
                        {
                            tat=5;
                        }
                        else if(text.toLowerCase()=='Educational'.toLowerCase())
                        {
                            tat=7;
                        }
                        else if(text.toLowerCase()=='Criminal'.toLowerCase())
                        {
                            tat=3;
                        }
                        else if(text.toLowerCase()=='Judicial'.toLowerCase())
                        {
                            tat=2;
                        }
                        else if(text.toLowerCase()=='Reference'.toLowerCase())
                        {
                            tat=2;
                        }
                        else if(text.toLowerCase()=='Covid-19 Certificate'.toLowerCase())
                        {
                            tat=5;
                        }

                        
                        if(verify.toLowerCase()=="Auto".toLowerCase())
                        {
                            $(".service_result").append("<p class='pb-border row-"+id+"'></p><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-2'><input class='form-control no_of_check' type='text' name='service_unit-"+id+"' value='1' readonly><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='tat-"+id+"' value='"+tat+"' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-"+id+"'></p></div><div class='col-sm-3'><input class='form-control' type='text' name='notes-"+id+"' placeholder='Notes' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-notes-"+id+"'></p></div></div><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-3'></div><div class='col-sm-2 pt-2 text-right'><label>Incentive TAT</label></div><div class='col-sm-1'><input class='form-control' type='text' name='incentive-"+id+"' value='1'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-"+id+"'></p></div><div class='col-sm-2 pt-2 text-right'><label>Penalty TAT</label></div><div class='col-sm-1'><input class='form-control' type='text' name='penalty-"+id+"' value='"+tat+"'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-"+id+"'></p></div></div><div class='row price_row "+display_none+" mt-2 row-"+id+"' id='row mt-2 row-"+id+"'><div class='col-sm-2 pt-2'><label>Price (<small class='text-muted'>Per Item <i class='fas fa-rupee-sign'></i></small>)</label></div><div class='col-sm-2'><input class='form-control check_price' type='text' name='price-"+id+"' value='0' "+readonly+"><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-"+id+"'></p></div></div>");
                        }
                        else
                        {
                            $(".service_result").append("<p class='pb-border row-"+id+"'></p><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-2'><input class='form-control no_of_check' type='text' name='service_unit-"+id+"' value='1' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='tat-"+id+"' value='"+tat+"' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-"+id+"'></p></div><div class='col-sm-3'><input class='form-control' type='text' name='notes-"+id+"' placeholder='Notes' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-notes-"+id+"'></p></div></div><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-3'></div><div class='col-sm-2 pt-2 text-right'><label>Incentive TAT</label></div><div class='col-sm-1'><input class='form-control' type='text' name='incentive-"+id+"' value='1'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-"+id+"'></p></div><div class='col-sm-2 pt-2 text-right'><label>Penalty TAT</label></div><div class='col-sm-1'><input class='form-control' type='text' name='penalty-"+id+"' value='"+tat+"'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-"+id+"'></p></div></div><div class='row price_row "+display_none+" mt-2 row-"+id+"' id='row mt-2 row-"+id+"'><div class='col-sm-2 pt-2'><label>Price (<small class='text-muted'>Per Item <i class='fas fa-rupee-sign'></i></small>)</label></div><div class='col-sm-2'><input class='form-control check_price' type='text' name='price-"+id+"' value='0' "+readonly+"><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-"+id+"'></p></div></div>");
                        }
                        
                        }
                        else
                        {

                        var id =  $(this).attr("value");
                        $("div.row-"+id).remove();
                        $("p.row-"+id).remove();
                        }

                        $('.check_price').each(function () {
                        if(!isNaN(parseFloat($(this).val())))
                        {
                            total_price = total_price + parseFloat($(this).val());
                        }
                        });

                        $('.total_check_price').html(total_price.toFixed(2));

                        $('.no_of_check').each(function(){
                        var is_int = Number.isInteger(parseInt($(this).val()));
                        if(is_int)
                        {
                            total_check = total_check + parseInt($(this).val());
                        }
                        });

                        $('.total_checks').html(total_check);



            }); 

            $('.tat_type').change(function(){
                var value = $(this).val();
                $('.tat_result').html('');
                $('.tat_result').removeClass('mb-2');
                $('.tat_result').removeAttr('style');
                if(value=='case')
                {
                $('.tat_result').addClass('mb-2');
                $('.tat_result').css({'border': '1px solid #ddd','padding':'10px','width':'50%'});
                $('.tat_result').html(`<div class="row">
                                            <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Case-Wise Incentive & Penalty</div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Incentive <span class="text-danger">*</span> (<small class="text-muted">in %</small>)</label>
                                                    <input class="form-control" type="text" name="incentive" >
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-incentive"></p>
                                                </div>
                                            </div> 
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Penalty <span class="text-danger">*</span> (<small class="text-muted">in %</small>)</label>
                                                    <input class="form-control" type="text" name="penalty" >
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-penalty"></p>
                                                </div>
                                            </div>  
                                        </div>`);

                }

            });

            $('.price_type').change(function(){

                if(this.checked)
                {
                $('.price_result').html('');
                $('.price_result').removeClass('mb-2');
                $('.price_result').removeAttr('style');

                var price_type = $('.price_type:checked').val();
                
                if(price_type.toLowerCase()=='package'.toLowerCase())
                {
                    $('.price_result').addClass('mb-2');
                    $('.price_result').css({'border': '1px solid #ddd','padding':'10px','width':'50%'});
                    $('.price_result').html(`<div class="row">
                                                <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Package-Wise Price</div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Price <span class="text-danger">*</span> (<small class="text-muted">in <i class="fas fa-rupee-sign"></i></small>)</label>
                                                    <input class="form-control" type="text" name="price" value="0">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-price"></p>
                                                    </div>
                                                </div> 
                                            </div>`);
                    
                    $('.check_price').attr('readonly',true);

                    $('.price_row').addClass('d-none');

                    $('.total_p').addClass('d-none');

                }
                else
                {
                    $('.check_price').attr('readonly',false);

                    $('.price_row').removeClass('d-none');

                    $('.total_p').removeClass('d-none');
                }
                
                }
                else
                {
                alert('Select One price type');
                } 
            });

            $(document).on('change keyup','.check_price',function(){

                var total_price = 0;

                $('.check_price').each(function () {
                if(!isNaN(parseFloat($(this).val())))
                {
                    total_price = total_price + parseFloat($(this).val());
                }
                });

                $('.total_check_price').html(total_price.toFixed(2));
            });

            $(document).on('change keyup','.no_of_check',function(){

                var total_check = 0;
                $('.no_of_check').each(function(){
                var is_int = Number.isInteger(parseInt($(this).val()));
                if(is_int)
                {
                    total_check = total_check + parseInt($(this).val());
                }
                });

                $('.total_checks').html(total_check);
            });
    });

   var count=0;
   $(document).on('click','.add',function(){ 
      $(".addDiv").append(
         `<div class='projectReport' row-id='1' style='padding: 20px;margin-top:15px; border:1px solid #ddd; background:#fff;'>
         <span class="btn btn-link float-right text-danger close_div" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
         <h3 style='padding: 10px;background:#eee;'>Add a new contact </h3>
         <div class='row'>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label style='font-size: 16px;'> Contact Type </label>
         <input class='form-control' type='text' name='type[]' value=''>
         <small class='text-muted'>Add you contact title (Example: Manager)</small></div>
         <p style='margin-bottom: 2px;' class='text-danger error_container error-type' id="error-type"></p>
         </div>
         </div>
         <div class='row'>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label>First name</label>
         <input class='form-control' type='text' name='add_first_name[]' >
         <p style='margin-bottom: 2px;' class='text-danger error_container error-add_first_name' id="error-add_first_name"></p>
         </div>
         </div>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label>Middle name</label>
         <input class='form-control' type='text'  name='add_middle_name[]' >
         <p style='margin-bottom: 2px;' class='text-danger error_container error-add_middle_name' id="error-add_middle_name"></p>
         </div>
         </div>
         </div>
         <div class='row'>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label>Last name </label>
         <input class='form-control' type='text'  name='add_last_name[]'>
         <p style='margin-bottom: 2px;' class='text-danger error_container error-add_last_name' id="error-add_last_name"></p>
         </div>
         </div>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label>Email </label>
         <input class='form-control' type='text'  name='add_email[]' >
         <p style='margin-bottom: 2px;' class='text-danger error_container error-add_email' id="error-add_email"></p>
         </div>
         </div>
         </div>
         <div class='row'>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label>Phone Number </label>
         <input class='form-control number_only' maxlength='10' type='text' name='add_phone[]' >
         <p style='margin-bottom: 2px;' class='text-danger error_container error-add_phone' id="error-add_phone"></p>
         </div>
         </div>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label>Designation </label>
         <input class='form-control' type='text' name='add_designation[]' >
         <p style='margin-bottom: 2px;' class='text-danger error_container error-add_designation' id="error-add_designation"></p>
         </div>
         </div>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label> Landline number </label>
         <input class='form-control number_only' maxlength='10' type='text' name='add_landline_number[]' >
         <p style='margin-bottom: 2px;' class='text-danger error_container error-add_landline_number' id="error-add_landline_number"></p>
         </div>
         </div>
         </div>
         <div class='row'>
         <div class='col-sm-6'>
         </div>
         </div>
         </div>`
         );

         var i=0;
         $('.error-type').each(function(){
            $(this).attr('id','error-type_'+i);
            i++;
         });
         var i=0;
         $('.error-add_first_name').each(function(){
            $(this).attr('id','error-add_first_name_'+i);
            i++;
         });
         var i=0;
         $('.error-add_middle_name').each(function(){
            $(this).attr('id','error-add_middle_name_'+i);
            i++;
         });
         var i=0;
         $('.error-add_last_name').each(function(){
            $(this).attr('id','error-add_last_name_'+i);
            i++;
         });
         var i=0;
         $('.error-add_email').each(function(){
            $(this).attr('id','error-add_email_'+i);
            i++;
         });
         var i=0;
         $('.error-add_phone').each(function(){
            $(this).attr('id','error-add_phone_'+i);
            i++;
         });
         var i=0;
         $('.error-add_designation').each(function(){
            $(this).attr('id','error-add_designation_'+i);
            i++;
         });
         var i=0;
         $('.error-add_landline_number').each(function(){
            $(this).attr('id','error-add_landline_number_'+i);
            i++;
         });
   });


   $(document).on('click','.close_div',function(){
      var _this=$(this);
      _this.parent().fadeOut("slow", function(){ 
         _this.parent().remove();
            var i=0;
            $('.error-type').each(function(){
               $(this).attr('id','error-type_'+i);
               i++;
            });

            var i=0;
            $('.error-add_first_name').each(function(){
               $(this).attr('id','error-add_first_name_'+i);
               i++;
            });

            var i=0;
            $('.error-add_middle_name').each(function(){
               $(this).attr('id','error-add_middle_name_'+i);
               i++;
            });

            var i=0;
            $('.error-add_last_name').each(function(){
               $(this).attr('id','error-add_last_name_'+i);
               i++;
            });

            var i=0;
            $('.error-add_email').each(function(){
               $(this).attr('id','error-add_email_'+i);
               i++;
            });

            var i=0;
            $('.error-add_phone').each(function(){
               $(this).attr('id','error-add_phone_'+i);
               i++;
            });


            var i=0;
            $('.error-add_designation').each(function(){
               $(this).attr('id','error-add_designation_'+i);
               i++;
            });

            var i=0;
            $('.error-add_landline_number').each(function(){
               $(this).attr('id','error-add_landline_number_'+i);
               i++;
            });
      });
      
         
         
   });

   $(document).on('click','.add_spoke',function(){ 
      var s_len = $('.spokeReport').length;
      if(s_len + 1 > 5)
      {

         swal({
                  title: "You Can Include Maximum 5 Spokeman !!",
                  text: '',
                  type: 'warning',
                  buttons: true,
                  dangerMode: true,
                  confirmButtonColor:'#003473'
               });
      }
      else
      {
         $(".addSpokeDiv").append(
         `<div class='spokeReport' row-id='1'>
            <div class='form-group'>
            <div class="row">
            <div class="col-md-6">
            <label style='font-size: 16px;'> Name </label>
            <input class='form-control' type='text' name='spoke_name[]' value=''>
            <p style='margin-bottom: 2px;' class='text-danger error_container error-spoke_name' id="error-spoke_name"></p>
            </div>
            <div class="col-md-6 mt-4">
               <span class="btn btn-link text-danger close_spoke_div" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
            </div>
            </div>
            </div>
         </div>`
         );
      }
         var i=0;
         $('.error-spoke_name').each(function(){
            $(this).attr('id','error-spoke_name_'+i);
            i++;
         });
        
   });

   $(document).on('click','.close_spoke_div',function(){
      var _this=$(this);
      _this.parent().parent().parent().parent().fadeOut("slow", function(){ 
         _this.parent().parent().parent().parent().remove();
            var i=0;
            $('.error-spoke_name').each(function(){
               $(this).attr('id','error-spoke_name_'+i);
               i++;
            });
      });
      
         
         
   });
   //on change country
   $(document).on('change','.country',function(){ 
      var id = $('#country_id').val();
      $.ajax({
            type:"post",
            url:"{{route('/customers/getstate')}}", 
            data:{'country_id':id,"_token": "{{ csrf_token() }}"},
               success:function(data)
            {       
                  $("#state_id").empty();
                  $("#state_id").html('<option>Select State</option>');
                  $.each(data,function(key,value){
                  $("#state_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                  });
            }
         });
   });

   // on change state
   $(document).on('change','.state',function(){ 
      var id = $('#state_id').val();
      $.ajax({
            type:"post",
            url:"{{route('/customers/getcity')}}", 
            data:{'state_id':id,"_token": "{{ csrf_token() }}"},
            success:function(data)
            {       
                  $("#city_id").empty();
                  $("#city_id").html('<option>Select City</option>');
                  $.each(data,function(key,value){
                  $("#city_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                  }); 
            }

         });
   });

   //on change 
   $(document).on('change','#company_logo',function(){
     
      let reader = new FileReader();
      reader.onload = (e) => { 
         $('#preview_img').attr('src', e.target.result); 
         $('.close_btn').removeClass('d-none');
      }
      reader.readAsDataURL(this.files[0]); 
 
   });

   $(document).on('click','.close_btn',function(){
      $('#preview_img').removeAttr('src'); 
      $(this).addClass('d-none');
      $(this).parents().eq(2).find('#company_logo').val("");
   });

   //on change 
   $('#gst_attachment').change(function(){
         var fileTypes = ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg', 'pdf'];
         var pdf_url=$(this).attr('data-pdf');
         var file = this.files[0].name;
         var extension = file.split('.').pop().toLowerCase();
         // console.log(pdf_url);
         // console.log(file);
         // console.log(extension);
         isSuccess = fileTypes.indexOf(extension) > -1;
          if(isSuccess)
          {
            $('#preview_gst_img').attr('src','');
            let reader = new FileReader();
            if(extension=="pdf")
            {
               $('#preview_gst_img').attr('src', pdf_url); 
               $('.close_gst_btn').removeClass('d-none');
            }
            else
            {
               reader.onload = (e) => { 
                  $('.close_gst_btn').removeClass('d-none');
                  $('#preview_gst_img').attr('src', e.target.result); 
               }
               reader.readAsDataURL(this.files[0]);
            }

            $('#gst_label').html(file);
          }
          else
          {
             alert('Select Only jpg, jpeg, png, bmp, gif, svg, pdf file');
             $(this).val("");
             $('#gst_label').html('Choose File...');
             $('#preview_gst_img').removeAttr('src');
          }
           
   });

   $(document).on('click','.close_gst_btn',function(){
      $('#preview_gst_img').removeAttr('src'); 
      $(this).addClass('d-none');
      $('#gst_label').html('Choose File...');
      $(this).parents().eq(2).find('#gst_attachment').val("");
   });
    // 
    var curNum ='';
      //
      $(document).on('click','.clickSelectFile',function(){ 
         curNum = $(this).attr('add-id');
         $('.fileupload').trigger('click');
      });

      $(document).on('click','.remove-image',function(){ 
         var current = $(this);
         var file_id = $(this).attr('data-id');
         
         swal({
               // icon: "warning",
               type: "warning",
               title: "Are You Want to Remove This File?",
               text: "",
               dangerMode: true,
               showCancelButton: true,
               confirmButtonColor: "#007358",
               confirmButtonText: "YES",
               cancelButtonText: "CANCEL",
               closeOnConfirm: false,
               closeOnCancel: false
               },
               function(e){
                  if(e==true)
                  {
                     //
                     var fd = new FormData();

                     fd.append('file_id',file_id);
                     fd.append('_token', '{{csrf_token()}}');
                     //
                     $.ajax({
                           type: 'POST',
                           url: "{{ url('/customers/remove/contractFile') }}",
                           data: fd,
                           processData: false,
                           contentType: false,
                           success: function(data) {
                              // console.log(data);
                              if (data.fail == false) {
                                 //reset data
                                 $('.fileupload').val("");
                                 //append result
                                 $(current).parent('.image-area').detach();
                              } 
                              else {
                              
                              console.log("file error!");
                              
                              }
                           },
                           error: function(error) {
                              console.log(error);
                              // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
                           }
                     });
                     swal.close();
                  }
                  else
                  {
                     swal.close();
                  }
            });
         // $('#fileupload-'+curNum).val("");
         // $(this).parent('.image-area').detach();
      });

      $(document).on('change','.fileupload',function(e){          
         uploadFile(curNum);
      });

      $(document).on('click','.js-show-hide',function (e) {

         e.preventDefault();

         var _this = $(this);

         if (_this.hasClass('has-show-hide'))
         {
            _this.parent().find('input').attr('type','text');
            _this.html('<i class="fa fa-eye"></i>');
            _this.removeClass('has-show-hide');
         }
         else
         {
            _this.addClass('has-show-hide');
            _this.parent().find('input').attr('type','password');
            _this.html('<i class="fa fa-eye-slash"></i>');
         }


      });

//


function uploadFile(dynamicID){

   $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 

   var fd = new FormData();
   var file = $('.fileupload')[0].files[0];
   fd.append('file',file);
   fd.append('_token', '{{csrf_token()}}');
   //
   $.ajax({
         type: 'POST',
         url: "{{ url('/customers/upload/contractFile') }}",
         data: fd,
         processData: false,
         contentType: false,
         success: function(data) {
         console.log(data);
         if (data.fail == false) {
         //reset data
         $('.fileupload').val("");
         $("#fileUploadProcess").html("");
         //append result
         $("#fileResult-"+dynamicID).prepend("<div class='image-area'><img style='height: 110px; width: 100%; object-fit: contain; font-size: 11px; padding-top: 10px' src='"+data.filePrev+"'  alt='Preview' title='"+data.filename+"'><a class='remove-image' data-id='"+data.file_id+"' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.file_id+"'></div>");
         } else {
            $("#fileUploadProcess").html("");
            alert("Please upload valid file! allowed file type, Image, PDF, Doc, Xls and Txt etc. ");
            console.log("file error!");
            
         }
         },
         error: function(error) {
            console.log(error);
            // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
         }
   });
  
  return false;
}
  
</script>

@endsection
