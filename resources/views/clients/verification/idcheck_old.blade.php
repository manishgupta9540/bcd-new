@extends('layouts.client')
<style>
   
.btn-accept {
    font-weight: 400;
    color: #ec7070;
    background-color: transparent;
}
.btn-accept:hover {
    color: #fd5b5b;
    text-decoration: underline;
    background-color: transparent;
    border-color: transparent;
}
</style>
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
         <!-- ============Breadcrumb ============= -->
   <div class="row">
      <div class="col-sm-11">
          <ul class="breadcrumb">
          <li>
          <a href="{{ url('/my/home') }}">Dashboard</a>
          </li>
          <li>Verification</li>
         
          </ul>
      </div>
      <!-- ============Back Button ============= -->
      <div class="col-sm-1 back-arrow">
          <div class="text-right">
          <a href="{{ url()->previous() }}"> <i class="fas fa-arrow-circle-left fa-2x"></i></a>
          </div>
      </div>
  </div>   
      <div class="row">
         <div class="col-md-12">
            <div class="card text-left">
               <div class="card-body">
                  <div class="row">
                     {{-- <div class="col-md-12">
                        <ul class="nav nav-tabs nav-tabs-bottom">
                           <li class="nav-item"><a href="" class="nav-link active">ID Checks</a></li>
                           <li class="nav-item"><a href="{{ route('/verifications') }}" class="nav-link">Verifications</a></li>
                        </ul>
                     </div> --}}

                     @if ($message = Session::get('success'))
                     <div class="col-md-12">
                        <div class="alert alert-success">
                           <strong>{{ $message }}</strong> 
                        </div>
                     </div>
                     @endif
                     <div class="col-md-8">
                        <h4 class="card-title mt-2 mb-1"> Verifications </h4>
                        <p> Available ID Checks</p>
                     </div>
                     <div class="col-md-4">
                        <div class="btn-group" style="float:right">
                           <!-- <button class="btn btn-secondary btn-lg dropdown-toggle" id="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  Actions  </button>
                              <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#"> Else Here</a></div> -->
                           <!-- <a class="btn btn-success btn-lg" href="{!! url('/app/customers/create') !!} " > Add New </a>             -->
                        </div>
                     </div>
                  </div>
                  <?php $term_status=Helper::get_verification_terms(Auth::user()->business_id)?>
                  @if($term_status==NULL)
                     <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>For this Verification</strong> additional charges would apply. Would you like to accept the condition?
                        <span class="mx-auto"></span>
                        <button type="button" class="btn btn-accept pb-2 accept" data-dismiss="alert" aria-label="Close" style="">
                           <span class="" style="font-size: 14px" aria-hidden="true"><i class="fas fa-check"></i></span> 
                        </button>
                        <button type="button" class="btn btn-accept pb-2" data-dismiss="alert" aria-label="Close" style="">
                           <span class="" style="font-size: 14px" aria-hidden="true"><i class="fas fa-times"></i></span>
                        </button>
                     </div>
                   @endif
                  <!-- row start -->
                  <div class="row">
                     <div class="col-md-12">
                        <div class="table-responsive">
                           <p class="text-danger text-center error-data"></p>
                           <table class="table table-bordered ">
                              <thead>
                                 <tr>
                                    {{-- <th scope="col">#</th> --}}
                                    <th scope="col"> Check </th>
                                    <th scope="col"> ID Number </th>
                                    <th scope="col"> Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @php
                                    $hide = Helper::verification_show(Auth::user()->business_id); 
                                 @endphp
                                 @if( count($services) > 0 && $hide==NULL)
                                 @foreach($services as $item)
                                    @php
                                       $verification_hide=Helper::verification_service_show(Auth::user()->business_id,$item->id);
                                       $placeholder='';
                                  
                                       if($item->type_name=='covid_19_certificate')
                                       {
                                          $placeholder = 'Enter The Mobile No.';
                                       }
                                       else if($item->type_name=='upi')
                                       {
                                          $placeholder = 'Enter the UPI ID';
                                       }
                                    @endphp
                                    @if($verification_hide==NULL)
                                       <tr class="">
                                          {{-- <th scope="row">{{ $item->id }}</th> --}}
                                          <td> <b> {{ $item->name }} </b>&nbsp;&nbsp; 
                                             @if($item->type_name =='aadhaar_validation')

                                             <button type="button" class="btn btn-sm btn-info advance_check" data-service="{{base64_encode($item->id)}}"><i class="fa fa-hand-point-right"></i> Advance</button>
                                             
                                             @endif
                                             <br>
                                             <small class="text-muted">  </small>
                                          
                                          </td>
                                          <td>
                                       
                                          @if($item->type_name == 'passport')
                                          <div class="row">
                                             <div class="col-md-6">
                                                <input type="text" name="" class="form-control passportNumber" placeholder="File No."> 
                                                <small class="text-muted" style="font-size:10px;">File No.</small>
                                             </div>
                                             <div class="col-md-6">
                                                <input type="text" name="" class="form-control commonDatepicker passportDOB" placeholder="DOB"> 
                                                <small class="text-muted" style="font-size:10px;">DOB (DD-M-YYYY)</small>
                                             </div>
                                          </div>
                                          @elseif($item->type_name == 'bank_verification')
                                          <div class="row">
                                             <div class="col-md-6">
                                                <input type="text" name="" class="form-control bankACNumber" placeholder="Bank AC No."> 
                                                <small class="text-muted" style="font-size:10px;">A/C Number</small>
                                             </div>
                                             <div class="col-md-6">
                                                <input type="text" name="" class="form-control  bankIFSC" placeholder="IFSC"> 
                                                <small class="text-muted" style="font-size:10px;">IFSC Code</small>
                                             </div>
                                          </div>

                                          {{-- @elseif($item->type_name == 'electricity')
                                          <div class="row">
                                             <div class="col-md-6">
                                                <input type="text" name="" class="form-control electricNo" placeholder="Account No."> 
                                                <small class="text-muted" style="font-size:10px;">Account Number</small>
                                             </div>
                                             <div class="col-md-6">
                                                <input type="text" name="" class="form-control  electricOPCode" placeholder="Operator Code"> 
                                                <small class="text-muted" style="font-size:10px;">Operator Code</small>
                                             </div>
                                          </div> --}}

                                          @elseif($item->type_name == 'gstin' && $verification_hide==NULL)
                                          <div class="row">
                                             <div class="col-md-6">
                                                <input type="text" name="" class="form-control gstNumber" placeholder="GSTIN Number"> 
                                                <small class="text-muted" style="font-size:10px;">GSTIN Number</small>
                                             </div>
                                             <div class="col-md-6">
                                                <select class="form-control  gstFilling" > 
                                                   <option value="false">-Select-</option>
                                                   <option value="false">No</option>
                                                   <option value="true">Yes</option>
                                                </select>
                                                <small class="text-muted" style="font-size:10px;">Filling Record Needed?</small>
                                             </div>
                                          </div>
                                          @elseif($item->type_name=='e_court')
                                             <div class="row">
                                                <div class="col-md-6">
                                                   <input type="text" name="" class="form-control name" placeholder="Enter Your name"> 
                                                   <small class="text-muted" style="font-size:10px;">Name</small>
                                                </div>
                                                <div class="col-md-6">
                                                   <input type="text" name="" class="form-control fathername" placeholder="Enter Your father name"> 
                                                   <small class="text-muted" style="font-size:10px;">Father Name</small>
                                                </div>
                                                <div class="col-md-12 pt-2">
                                                   <input type="text" name="" class="form-control address" placeholder="Enter Your address"> 
                                                   <small class="text-muted" style="font-size:10px;">Address</small>
                                                </div>
                                             </div>
                                          @else
                                          <input type="text" name="" class="form-control IdNumber" placeholder="{{$placeholder}}"> 
                                          @endif
                                          <span class="error" style="font-size:12px;color:red"></span>
                                          </td>
                                          <td>
                                             <button type="button" class="btn btn-sm btn-info checkButton" id="{{ $item->type_name }}" data-service="{{base64_encode($item->id)}}" check-type="aadhar" ><i class="fa fa-hand-point-right"></i> Go</button>
                                          </td>
                                       </tr>
                                    @endif
                                 @endforeach
                                 @else
                                 <tr>
                                    <td scope="row" colspan="7">
                                       <h3 class="text-center">No record!</h3>
                                    </td>
                                 </tr>
                                 @endif
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>

                  <!-- ./row end -->

                  <!-- row report -->
                  <div class="row reportBox reportBoxAadhaar d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="aadharReportExport" target="_blank" href="">Download PDF</a></p>
                     <div class="table-responsive">
                        
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>ID Verification</b></h3>

                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">Initiated Date</th>
                                    <th scope="col"> Completed Date </th>
                                    <th scope="col"> Insufficiency Raise Date </th>
                                    <th scope="col"> Insufficiency Cleared Date</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row"><span class="initiated_dt">{{ date('d-M-Y')}}</span></td>
                                    <td><span class="completed_dt">{{ date('d-M-Y')}}</span></td>
                                    <td>NA</td>
                                    <td>NA</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- id detail -->
                           <h3 class="text-center"><b>Aadhar Number Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td width="50%">Aadhar number</td> <td width="50%" class="aadhar_number"></td> </tr>
                                 <tr> <td>Aadhar Validity</td> <td class='aadhar_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='aadhar_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td>
                                       Aadhar Verification Completed <br>
                                       Aadhar number exist <span class="aadhar_number"></span> <br>
                                       Age Bond: <span class="aadhar_age_bond"></span> <br>
                                       Gender: <span class="aadhar_gender"></span> <br>
                                       State: <span class="aadhar_state"></span><br>
                                       Mobile : XXXXXXX<span class="aadhar_mobile"></span>
                                 </td> 
                              </tr>
                              </tbody>
                           </table>
                           <!-- d detail -->
                        </div>
                     </div>
                  </div>
                  <!-- ./report row end -->
                  <!-- row report -->
                   <div class="row reportBox reportBoxPan d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="panReportExport" target="_blank" href="">Download PDF</a></p>
                     <div class="table-responsive">
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>ID Verification</b></h3>

                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">Initiated Date</th>
                                    <th scope="col"> Completed Date </th>
                                    <th scope="col"> Insufficiency Raise Date </th>
                                    <th scope="col"> Insufficiency Cleared Date</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row"><span class="initiated_dt">{{ date('d-M-Y')}}</span></td>
                                    <td><span class="completed_dt">{{ date('d-M-Y')}}</span></td>
                                    <td>NA</td>
                                    <td>NA</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- id detail -->
                           <h3 class="text-center"><b>Pan Number Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td width="50%">Pan number</td> <td width="50%" class="pan_number"></td> </tr>
                                 <tr> <td>Pan Validity</td> <td class='pan_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='pan_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td>
                                 Pan Verification Completed <br>
                                 Pan number exist <span class="pan_number"></span> <br>
                                 Full Name : <span class="pan_full_name"></span><br>
                                 {{-- DOB: <span class="pan_dob"></span> <br> --}}
                                 </td> 
                              </tr>
                              </tbody>
                           </table>
                           <!-- d detail -->
                        </div>
                     </div>
                  </div>
                  <!-- ./report row end -->
                  <!-- row report voter id-->
                  <div class="row reportBox reportBoxVoterID d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="voterIDReportExport" target="_blank" href="">Download PDF</a></p>
                     <div class="table-responsive">
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>ID Verification</b></h3>

                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">Initiated Date</th>
                                    <th scope="col"> Completed Date </th>
                                    <th scope="col"> Insufficiency Raise Date </th>
                                    <th scope="col"> Insufficiency Cleared Date</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row"><span class="initiated_dt">{{ date('d-M-Y')}}</span></td>
                                    <td><span class="completed_dt">{{ date('d-M-Y')}}</span></td>
                                    <td>NA</td>
                                    <td>NA</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- id detail -->
                           <h3 class="text-center"><b>Voter ID Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td width="50%">Voter ID number</td> <td width="50%" class="voter_id_number"></td> </tr>
                                 <tr> <td>Voter ID Validity</td> <td class='pan_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='pan_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td>
                                 Voter ID Verification Completed <br>
                                 Voter ID number exist <span class="voter_id_number"></span> <br>
                                 Full Name : <span class="voter_id_full_name"></span><br>
                                 Age: <span class="voter_id_age"></span> <br>
                                 DOB: <span class="voter_id_dob"></span> <br>
                                 Gender: <span class="voter_id_gender"></span> <br>
                                 Relation Type: <span class="voter_id_relation_type"></span> <br>
                                 Relation Name: <span class="voter_id_relation_name"></span> <br>
                                 House No : <span class="voter_id_house_no"></span> <br>
                                 Area : <span class="voter_id_area"></span> <br>
                                 State : <span class="voter_id_state"></span> <br>
                                 </td> 
                              </tr>
                              </tbody>
                           </table>
                           <!-- d detail -->
                        </div>
                     </div>
                  </div>
                  <!-- ./report Voter IDrow end -->
                  <!-- row report RC-->
                   <div class="row reportBox reportBoxRC d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="RCReportExport" target="_blank" href="">Download PDF</a></p>
                     <div class="table-responsive">
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>ID Verification</b></h3>

                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">Initiated Date</th>
                                    <th scope="col"> Completed Date </th>
                                    <th scope="col"> Insufficiency Raise Date </th>
                                    <th scope="col"> Insufficiency Cleared Date</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row"><span class="initiated_dt">{{ date('d-M-Y')}}</span></td>
                                    <td><span class="completed_dt">{{ date('d-M-Y')}}</span></td>
                                    <td>NA</td>
                                    <td>NA</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- id detail -->
                           <h3 class="text-center"><b>RC Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td width="50%">RC Number</td> <td width="50%" class="rc_number"></td> </tr>
                                 <tr> <td>RC Validity</td> <td class='rc_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='rc_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td>
                                 RC Verification Completed <br>
                                 RC Number exist <span class="rc_number"></span> <br>
                                 Registration Date: <span class="rc_registration_date"></span><br>
                                 Owner Name: <span class="rc_owner_name"></span> <br>
                                 Chassis Number: <span class="rc_chasis_number"></span> <br>
                                 Engine Number: <span class="rc_engine_number"></span> <br>
                                 Maker Model: <span class="rc_maker_model"></span> <br>
                                 Fuel Type: <span class="rc_fuel_type"></span> <br>
                                 Norms Type: <span class="rc_norms_type"></span> <br>
                                 Insurance Company: <span class="rc_insurance_company"></span> <br>
                                 Insurance Policy Number: <span class="rc_insurance_policy_number"></span> <br>
                                 Insurance Upto: <span class="rc_insurance_upto"></span> <br>
                                 Registered at: <span class="rc_registered_at"></span> <br>
                                 </td> 
                              </tr>
                              </tbody>
                           </table>
                           <!-- rc detail -->
                        </div>
                     </div>
                  </div>
                  <!-- ./report RC end -->
                  <!-- row report DL-->
                  <div class="row reportBox reportBoxDL d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="DLReportExport" target="_blank" href="">Download PDF</a></p>
                     <div class="table-responsive">
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>ID Verification</b></h3>

                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">Initiated Date</th>
                                    <th scope="col"> Completed Date </th>
                                    <th scope="col"> Insufficiency Raise Date </th>
                                    <th scope="col"> Insufficiency Cleared Date</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row"><span class="initiated_dt">{{ date('d-M-Y')}}</span></td>
                                    <td><span class="completed_dt">{{ date('d-M-Y')}}</span></td>
                                    <td>NA</td>
                                    <td>NA</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- id detail -->
                           <h3 class="text-center"><b>DL Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td width="50%">DL Number</td> <td width="50%" class="dl_number"></td> </tr>
                                 <tr> <td>DL Validity</td> <td class='dl_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='dl_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td>
                                 DL Verification Completed <br>
                                 DL Number: <span class="dl_number"></span> <br>
                                 Name: <span class="dl_name"></span><br>
                                 Gender: <span class="dl_gender"></span><br>
                                 DOB: <span class="dl_dob"></span><br>
                                 Spouse Name: <span class="dl_spouse"></span><br>
                                 Address: <span class="dl_address"></span><br>
                                 State: <span class="dl_state"></span><br>
                                 Country: <span class="dl_country"></span><br>
                                 DTO: <span class="dl_ola_name"></span><br>
                                 Expiry of Date: <span class="dl_doe"></span><br>

                                 </td> 
                              </tr>
                              </tbody>
                           </table>
                           <!-- DL detail -->
                        </div>
                     </div>
                  </div>
                  <!-- ./report DL end -->
                  <!-- row report Passport-->
                  <div class="row reportBox reportBoxPassport d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="passportReportExport" target="_blank" href="">Download PDF</a></p>
                     <div class="table-responsive">
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>ID Verification</b></h3>

                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">Initiated Date</th>
                                    <th scope="col"> Completed Date </th>
                                    <th scope="col"> Insufficiency Raise Date </th>
                                    <th scope="col"> Insufficiency Cleared Date</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row"><span class="initiated_dt">{{ date('d-M-Y')}}</span></td>
                                    <td><span class="completed_dt">{{ date('d-M-Y')}}</span></td>
                                    <td>NA</td>
                                    <td>NA</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- id detail -->
                           <h3 class="text-center"><b>Passport Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td width="50%">Passport Number</td> <td width="50%" class="passport_number"></td> </tr>
                                 <tr> <td>Passport Validity</td> <td class='passport_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='passport_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td>
                                 Passport Verification Completed <br>
                                 Passport Number: <span class="passport_number"></span> <br>
                                 Name: <span class="passport_name"></span><br>
                                 DOB.: <span class="passport_dob"></span><br>
                                 File No.: <span class="passport_file_no"></span><br>
                                 Date Of Application: <span class="passport_dop"></span><br>

                                 </td> 
                              </tr>
                              </tbody>
                           </table>
                           <!-- rc detail -->
                        </div>
                     </div>
                  </div>
                  <!-- ./report Passport end -->

                   <!-- row report GST-->
                   <div class="row reportBox reportBoxGST d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="GSTReportExport" target="_blank" href="">Download PDF</a></p>
                     <div class="table-responsive">
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>ID Verification</b></h3>

                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">Initiated Date</th>
                                    <th scope="col"> Completed Date </th>
                                    <th scope="col"> Insufficiency Raise Date </th>
                                    <th scope="col"> Insufficiency Cleared Date</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row"><span class="initiated_dt">{{ date('d-M-Y')}}</span></td>
                                    <td><span class="completed_dt">{{ date('d-M-Y')}}</span></td>
                                    <td>NA</td>
                                    <td>NA</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- id detail -->
                           <h3 class="text-center"><b>GSTIN Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td width="50%">GSTIN Number</td> <td width="50%" class="gst_number"></td> </tr>
                                 <tr> <td>GSTIN Validity</td> <td class='gst_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='gst_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td>
                                 GSTIN Verification Completed <br>
                                 GSTIN Number: <span class="gst_number"></span> <br>
                                 Business Name: <span class="gst_business_name"></span><br>
                                 Address: <span class="gst_address"></span><br>
                                 Center Jurisdiction: <span class="gst_center_jurisdiction"></span><br>
                                 Date Of Registration: <span class="gst_date_of_registration"></span><br>
                                 Constitution Of Business: <span class="gst_constitution_of_business"></span><br>
                                 Taxpayer Type: <span class="gst_taxpayer_type"></span><br>
                                 GSTIN Status: <span class="gst_gstin_status"></span><br>
                                 
                                 </td> 
                              </tr>
                              </tbody>
                           </table>
                           <!-- rc detail -->
                        </div>
                     </div>
                  </div>
                  <!-- ./report GST end -->
                   <!-- row report GST-->
                   <div class="row reportBox reportBoxInsta d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="InstaReportExport" target="_blank" href="">Download PDF</a></p>
                     <div class="table-responsive">
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>Financial Verification</b></h3>

                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">Initiated Date</th>
                                    <th scope="col"> Completed Date </th>
                                    <th scope="col"> Insufficiency Raise Date </th>
                                    <th scope="col"> Insufficiency Cleared Date</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row"><span class="initiated_dt">{{ date('d-M-Y')}}</span></td>
                                    <td><span class="completed_dt">{{ date('d-M-Y')}}</span></td>
                                    <td>NA</td>
                                    <td>NA</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- id detail -->
                           <h3 class="text-center"><b>Financial Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td width="50%">CIN Number</td> <td width="50%" class="cin_number"></td> </tr>
                                 <tr> <td>Validity</td> <td class='gst_validity'>-</td> </tr>
                                 <tr> <td>Verification Check</td> <td class='gst_check'>Under process</td> </tr>
                                
                              </tbody>
                           </table>
                           <!-- rc detail -->
                        </div>
                     </div>
                  </div>
                  <!-- ./report GST end -->
                  <!-- row report banking info-->
                  <div class="row reportBox reportBoxBank d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="BankReportExport" target="_blank" href="">Download PDF</a></p>
                     <div class="table-responsive">
                        <div class="col-md-10">
                           {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>Bank Account Verification</b></h3>

                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col"> Initiated Date </th>
                                    <th scope="col"> Completed Date </th>
                                    <th scope="col"> Insufficiency Raise Date </th>
                                    <th scope="col"> Insufficiency Cleared Date </th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row"><span class="initiated_dt">{{ date('d-M-Y')}}</span></td>
                                    <td><span class="completed_dt">{{ date('d-M-Y')}}</span></td>
                                    <td>NA</td>
                                    <td>NA</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- id detail -->
                           <h3 class="text-center"><b>Bank Account Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 
                                 <tr> <td>Validity</td> <td class='bank_ac_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='bank_ac_check'>Completed</td> </tr>
                                 <tr> <td>Name </td> <td class='bank_ac_name' id="bank_ac_name"></td> </tr>
                                 <tr> <td width="50%">Bank Account Number</td> <td width="50%" class="bank_ac_number" id="bank_ac_number"></td> </tr>
                                 <tr> <td>IFSC Code</td> <td class='ifsc_code' id="ifsc_code"></td> </tr>
                                
                              </tbody>
                           </table>
                           <!-- bank detail -->
                        </div>
                     </div>
                  </div>
                  <!-- ./report bank end -->
                  <!--  -->
                  <!-- row report -->
                  <div class="row reportBox reportBoxTelecom d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="telecomReportExport" target="_blank" href="">Download PDF</a></p>
                     <div class="table-responsive">
                        
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>Telecom Verification</b></h3>

                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">Initiated Date</th>
                                    <th scope="col"> Completed Date </th>
                                    <th scope="col"> Insufficiency Raise Date </th>
                                    <th scope="col"> Insufficiency Cleared Date</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row"><span class="initiated_dt">{{ date('d-M-Y')}}</span></td>
                                    <td><span class="completed_dt">{{ date('d-M-Y')}}</span></td>
                                    <td>NA</td>
                                    <td>NA</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- id detail -->
                           <h3 class="text-center"><b>Telecom Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td width="50%">Phone number</td> <td width="50%" class="t_phone_number"></td> </tr>
                                 <tr> <td width="50%">Operator</td> <td width="50%" class="t_operator"></td> </tr>
                                 <tr> <td width="50%">Billing Type</td> <td width="50%" class="t_bill"></td> </tr>
                                 <tr> <td>Phone Validity</td> <td class='phone_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='phone_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td>
                                       Telecom Verification Completed <br>
                                       {{-- Phone number exist <span class="t_phone_number"></span> <br> --}}
                                       Name <span class="t_name"></span> <br>
                                       DOB: <span class="t_dob"></span> <br>
                                       Address: <span class="t_address"></span> <br>
                                       City: <span class="t_city"></span> <br>
                                       State: <span class="t_state"></span> <br>
                                       Pin Code: <span class="t_pin"></span> <br>
                                       Email: <span class="t_email"></span><br>
                                       Alternative Mobile : <span class="t_alternative"></span>
                                 </td> 
                              </tr>
                              </tbody>
                           </table>
                           <!-- d detail -->
                        </div>
                     </div>
                  </div>
                  <!-- ./report row end -->
                   <!-- row report -->
                   <div class="row reportBox reportBoxCovid19 d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="covid19ReportExport" target="_blank" href="" download="">Download PDF</a></p>
                     <div class="table-responsive">
                        
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>Covid-19 Certificate Verification</b></h3>

                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">Initiated Date</th>
                                    <th scope="col"> Completed Date </th>
                                    <th scope="col"> Insufficiency Raise Date </th>
                                    <th scope="col"> Insufficiency Cleared Date</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row"><span class="initiated_dt">{{ date('d-M-Y')}}</span></td>
                                    <td><span class="completed_dt">{{ date('d-M-Y')}}</span></td>
                                    <td>NA</td>
                                    <td>NA</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- id detail -->
                           <h3 class="text-center"><b>Covid 19 Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td width="50%">Reference ID</td> <td width="50%" class="c_reference_id"></td> </tr>
                                 <tr> <td>Reference Validity</td> <td class='phone_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='phone_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td>
                                       Covid 19 Verification Completed <br>
                                       {{-- Phone number exist <span class="t_phone_number"></span> <br> --}}
                                       Mobile Number : <span class="c_mobile_number"></span> <br>
                                       Reference ID : <span class="c_reference_id"></span> <br>
                                 </td> 
                              </tr>
                              </tbody>
                           </table>
                           <!-- d detail -->
                        </div>
                     </div>
                  </div>
                  <!-- ./report row end -->
                   <!-- row report -->
                   <div class="row reportBox reportBoxEcourt d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="ecourtReportExport" target="_blank" href="" download="">Download PDF</a></p>
                     <div class="table-responsive">
                        
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>E-Court Verification</b></h3>

                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">Initiated Date</th>
                                    <th scope="col"> Completed Date </th>
                                    <th scope="col"> Insufficiency Raise Date </th>
                                    <th scope="col"> Insufficiency Cleared Date</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row"><span class="initiated_dt">{{ date('d-M-Y')}}</span></td>
                                    <td><span class="completed_dt">{{ date('d-M-Y')}}</span></td>
                                    <td>NA</td>
                                    <td>NA</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- id detail -->
                           <h3 class="text-center"><b>E-Court Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td>E-court Validity</td> <td class='phone_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='phone_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td>
                                       E-Court Verification Completed <br>
                                       {{-- Phone number exist <span class="t_phone_number"></span> <br> --}}
                                       Name : <span class="e_name"></span> <br>
                                       Father Name : <span class="e_father_name"></span> <br>
                                       Address : <span class="e_address"></span> <br>
                                 </td> 
                              </tr>
                              </tbody>
                           </table>
                           <!-- d detail -->
                        </div>
                     </div>
                  </div>
                  <!-- ./report row end -->
                   <!-- row report -->
                   <div class="row reportBox reportBoxUpi d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="upiReportExport" target="_blank" href="" download="">Download PDF</a></p>
                     <div class="table-responsive">
                        
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>UPI Verification</b></h3>

                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th scope="col">Initiated Date</th>
                                    <th scope="col"> Completed Date </th>
                                    <th scope="col"> Insufficiency Raise Date </th>
                                    <th scope="col"> Insufficiency Cleared Date</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row"><span class="initiated_dt">{{ date('d-M-Y')}}</span></td>
                                    <td><span class="completed_dt">{{ date('d-M-Y')}}</span></td>
                                    <td>NA</td>
                                    <td>NA</td>
                                 </tr>
                              </tbody>
                           </table>
                           <!-- id detail -->
                           <h3 class="text-center"><b>UPI Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td>UPI Validity</td> <td class='phone_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='phone_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td>
                                       UPI Verification Completed <br>
                                       {{-- Phone number exist <span class="t_phone_number"></span> <br> --}}
                                       UPI ID : <span class="u_id"></span> <br>
                                       Name : <span class="u_name"></span> <br>
                                 </td> 
                              </tr>
                              </tbody>
                           </table>
                           <!-- d detail -->
                        </div>
                     </div>
                  </div>
                  <!-- ./report row end -->
               </div>
            </div>
         </div>
      </div>
   </div>
   
    <div class="modal"  id="advance_check">
      <div class="modal-dialog">
         <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
               <h4 class="modal-title">Aadhaar Verification</h4>
               {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
            </div>
            <!-- Modal body  -->
            <form method="post" action="{{ url('my/idAdvanceCheck/aadhar') }}" id="advanced_check">
            @csrf
               <input type="hidden" name="service_id" id="service_id" class="service_id">
               <div class="modal-body">
               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                  <div class="form-group">
                        <label for="label_name"> Aadhaar Id </label>
                        <input type="text" id="aadhaar_id " name="aadhaar_id" class="form-control aadhaar_id" placeholder="Enter Id"/>
                        <p style="margin-bottom: 2px;" class="text-danger error-container error-aadhaar_id" id="error-aadhaar_id"></p> 
                  </div>
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-wrong_aadhar"> </p>
                
               </div>
               <!-- Modal footer -->
               <div class="modal-footer">
                  <button type="submit" class="btn btn-primary aadhar_submit btn_d" >Send Otp </button>
                  <button type="button" class="btn btn-danger btn_d" data-dismiss="modal">Close</button>
               </div>
            </form>
         </div>
      </div>
   </div>

{{-- Modal for otp verification    --}}

<div class="modal"  id="send_otp_modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">OTP Verification for Aadhar</h4>
            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
         <form method="post" action="{{ url('my/idAdvanceCheckOtp/aadharOtp') }}" id="send_otp">
         @csrf
            <input type="hidden" name="serv_id" id="serv_id" class="serv_id">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            <div class="form-group">
                  <label for="label_name"> Mobile Number  </label>
                  <input type="text" id="mob " name="mob" class="form-control mob" placeholder="Enter Phone number"/>
                  <p style="margin-bottom: 2px;" class="text-danger error-container error-mob" id="error-mob"></p> 
            </div>
               <div class="form-group">
                     {{-- <label for="label_name"> OTP </label>
                     <input type="text" id="otp " name="otp" class="form-control otp" placeholder="Enter OTP"/>
                     <p style="margin-bottom: 2px;" class="text-danger" id="error-otp"></p>  --}}
                     <div class="row justify-content-center align-items-center">
                        <div class="col-sm-5 text-center">
                            <label for="label_name"> OTP </label>
                        </div>
                    </div>
                    <div class="row justify-content-center align-items-center">
                        <div class="col-sm-8 text-center">
                            <input name="otp[]" class="digit text-center otp" type="text" id="first_otp" size="1" maxlength="1" tabindex="0" >
                            <input name="otp[]" class="digit text-center otp" type="text" id="second_otp" size="1" maxlength="1" tabindex="1">
                            <input name="otp[]" class="digit text-center otp" type="text" id="third_otp" size="1" maxlength="1"  tabindex="2">
                            <input name="otp[]" class="digit text-center otp" type="text" id="fourth_otp" size="1" maxlength="1" tabindex="3">
                            <input name="otp[]" class="digit text-center otp" type="text" id="fifth_otp" size="1" maxlength="1"  tabindex="4">
                            <input name="otp[]" class="digit text-center otp" type="text" id="sixth_otp" size="1" maxlength="1" tabindex="5">
                        </div>
                    </div>
                    <div class="row justify-content-center align-items-center">
                        <div class="col-sm-6 text-center">
                            <p style="margin-bottom: 2px;" class="text-danger error-container error-otp pt-2" id="error-otp"></p>
                            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-wrong_otp"> </p>  
                        </div>
                    </div>
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary aadhar_otp btn_d" >Submit </button>
               <button type="button" class="btn btn-danger btn_d" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

{{-- Modal for otp verification    --}}

<div class="modal"  id="send_otp_telecom">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">OTP Verification for Telecom</h4>
            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
         <form method="post" action="{{ url('my/idAdvanceCheck/telecom') }}" id="telecom_check">
         @csrf
            <input type="hidden" name="client_id" id="client_id">
            <input type="hidden" name="ser_id" id="ser_id" class="ser_id">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            <div class="form-group">
               <label for="label_name"> Mobile Number  </label>
               <input type="text" id="mob_t" name="mob_t" class="form-control mob_t" placeholder="Enter Phone number" readonly/>
               {{-- <p style="margin-bottom: 2px;" class="text-danger" id="error-mob"></p>  --}}
            </div>
               <div class="form-group">
                     {{-- <label for="label_name"> OTP </label>
                     <input type="text" id="otp " name="otp" class="form-control otp" placeholder="Enter OTP"/>
                     <p style="margin-bottom: 2px;" class="text-danger" id="error-otp"></p>  --}}
                     <div class="row justify-content-center align-items-center">
                        <div class="col-sm-5 text-center">
                            <label for="label_name"> OTP </label>
                        </div>
                    </div>
                    <div class="row justify-content-center align-items-center">
                        <div class="col-sm-8 text-center">
                            <input name="otp[]" class="digit text-center otp" type="text" id="first_otp" size="1" maxlength="1" tabindex="0" >
                            <input name="otp[]" class="digit text-center otp" type="text" id="second_otp" size="1" maxlength="1" tabindex="1">
                            <input name="otp[]" class="digit text-center otp" type="text" id="third_otp" size="1" maxlength="1"  tabindex="2">
                            <input name="otp[]" class="digit text-center otp" type="text" id="fourth_otp" size="1" maxlength="1" tabindex="3">
                        </div>
                    </div>
                    <div class="row justify-content-center align-items-center">
                        <div class="col-sm-6 text-center">
                            <p style="margin-bottom: 2px;" class="text-danger error-container error-otp pt-2" id="error-otp"></p>
                            {{-- <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-wrong_otp"> </p>   --}}
                        </div>
                    </div>
               </div>

            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary telecom_otp btn_d" >Submit </button>
               <button type="button" class="btn btn-danger btn_d" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

{{-- Modal for otp verification  --}}
<div class="modal"  id="send_otp_covid">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Covid 19 OTP Verification</h4>
            <button type="button" class="close btn_disable" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{ url('/my/idAdvanceCheck/covid19') }}" id="covid19_check">
         @csrf
            <input type="hidden" name="otp_id" id="otp_id">
            <input type="hidden" name="txnId" id="txnId">
            <input type="hidden" name="ser_id" id="ser_id" class="ser_id">
            <div class="modal-body">
            <div class="form-group">
               <label for="label_name"> Mobile Number  </label>
               <input type="text" id="mob_c" class="form-control mob_c" placeholder="Enter Phone number" readonly/>
               {{-- <p style="margin-bottom: 2px;" class="text-danger" id="error-mob"></p>  --}}
            </div>
               <div class="form-group">
                  {{-- <label for="label_name"> OTP </label>
                  <input type="text" id="otp" name="otp" class="form-control otp" placeholder="Enter OTP"/>
                  <p style="margin-bottom: 2px;" class="text-danger error-container error-otp" id="error-otp"></p>  --}}
                  <div class="row justify-content-center align-items-center">
                     <div class="col-sm-5 text-center">
                         <label for="label_name"> OTP </label>
                     </div>
                 </div>
                 <div class="row justify-content-center align-items-center">
                     <div class="col-sm-8 text-center">
                         <input name="otp[]" class="digit text-center otp" type="text" id="first_otp" size="1" maxlength="1" tabindex="0" >
                         <input name="otp[]" class="digit text-center otp" type="text" id="second_otp" size="1" maxlength="1" tabindex="1">
                         <input name="otp[]" class="digit text-center otp" type="text" id="third_otp" size="1" maxlength="1"  tabindex="2">
                         <input name="otp[]" class="digit text-center otp" type="text" id="fourth_otp" size="1" maxlength="1" tabindex="3">
                         <input name="otp[]" class="digit text-center otp" type="text" id="fifth_otp" size="1" maxlength="1"  tabindex="4">
                         <input name="otp[]" class="digit text-center otp" type="text" id="sixth_otp" size="1" maxlength="1" tabindex="5">
                     </div>
                 </div>
                 <div class="row justify-content-center align-items-center">
                     <div class="col-sm-6 text-center">
                         <p style="margin-bottom: 2px;" class="text-danger error-container error-otp pt-2" id="error-otp"></p>
                         {{-- <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-wrong_otp"> </p>   --}}
                         <p style="margin-bottom: 2px;" class="text-danger error-container error-all" id="error-all"> </p> 
                     </div>
                 </div>
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary btn_disable submit" >Submit </button>
               <button type="button" class="btn btn-danger btn_disable" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

{{-- Modal for Covid Reference check --}}
<div class="modal" id="covid19_ref">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Covid 19 Verification</h4>
            <button type="button" class="close btn_disable" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{ url('/my/idAdvanceCheck/covid19ref') }}" id="covid19_ref_check">
         @csrf
            <input type="hidden" name="mob_c" class="mob_c">
            <input type="hidden" name="otp_id" id="otp_id" class="otp_id">
            <input type="hidden" name="ser_id" id="ser_id" class="ser_id">
            <div class="modal-body"> 
               <div class="form-group">
                  <label for="label_name"> Reference ID </label>
                  <input type="text" id="reference_id" name="reference_id" class="form-control reference_id" placeholder="Enter Reference ID"/>
                  <p style="margin-bottom: 2px;" class="text-danger error-container error-reference_id" id="error-reference_id"></p> 
               </div>
            </div>
            <p style="margin-bottom: 2px;" class="text-danger error-container error-all" id="error-all"> </p>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary submit btn_disable">Submit </button>
               <button type="button" class="btn btn-danger btn_disable" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>


   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
</div>

<script>
$(document).ready(function() {

    $("#aadhaar_validation").click(function() {
      //reset error data 
      $(".error-data, span.error").html("");
      $('.reportBox').addClass('d-none');
      $('.reportBox').removeClass('d-block');

      var currentBtn = $(this); 
      //disable button
      $(this).prop("disabled", true);
      var checkType = $(this).attr("check-type");
      // add spinner to button
      $(this).html(
        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
      );
      var service_id=$(this).attr('data-service');
      var inputData = $(currentBtn).parent('td').prev('td').find('input').val();
      var finalInputID = inputData.trim();
      if(inputData !="" && finalInputID.length == 12){
      //
      $.ajax({
         url:"{{ url('my/idCheck/aadhar') }}",
         method:"GET",
         data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'service_id':service_id},      
         success:function(data)
         {
            console.log(data);
            if(data.fail)
            {
               $(currentBtn).parent('td').prev('td').find('span').html("It seems like ID number is not valid!");
               $(currentBtn).attr("check-type",checkType);
               $(currentBtn).prop("disabled", false);
               $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
            }
            if(data.fail == false)
            {
               //notify
               toastr.success("Verification done successfully");
               $('.reportBoxAadhaar').removeClass('d-none');
               $('.reportBoxAadhaar').addClass('d-block');
               
               //set the output data
               $('.aadhar_number').html("<strong>"+data.data.aadhar_number+"</strong>");
               $('.aadhar_gender').html("<strong>"+data.data.gender+"</strong>");
               $('.aadhar_age_bond').html("<strong>"+data.data.age_range+"</strong>");
               $('.aadhar_state').html("<strong>"+data.data.state)+"</strong>";
               $('.aadhar_mobile').html("<strong>"+data.data.last_digit+"</strong>");
               
               $('#aadharReportExport').attr('href',"my/IDcheck/aadhar/pdf/"+data.data.id);

               $(currentBtn).attr("check-type",checkType);
               $(currentBtn).prop("disabled", false);
               $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
            }
         },
         error : function(data)
         {
            console.log(data);
         }
      });

      }else{

            $(currentBtn).parent('td').prev('td').find('span').html("Please enter the valid ID number!");
           
            setTimeout(function(){ 
               $(currentBtn).attr("check-type",checkType);
               $(currentBtn).prop("disabled", false);
               $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
            }, 1000);
      }

    });

    //pan check
    $("#pan").click(function() {
      //reset error data 
      $(".error-data, span.error").html("");
      $('.reportBox').addClass('d-none');
      $('.reportBox').removeClass('d-block');
      
      var currentBtn = $(this); 
      //disable button
      $(this).prop("disabled", true);
      var checkType = $(this).attr("check-type");
      // add spinner to button
      $(this).html(
        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
      );
      var service_id=$(this).attr('data-service');
      var inputData = $(currentBtn).parent('td').prev('td').find('input').val();
      var finalInputID = inputData.trim();
      if(inputData !="" && finalInputID.length == 10){
      //
      $.ajax({
         url:"{{ url('my/idCheck/pan') }}",
         method:"GET",
         data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'service_id':service_id},      
         success:function(data)
         {
            console.log(data);
            if(data.fail)
            {
               $(currentBtn).parent('td').prev('td').find('span').html("It seems like ID number is not valid!");
               $(currentBtn).attr("check-type",checkType);
               $(currentBtn).prop("disabled", false);
               $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
            }
            if(data.fail == false)
            {
               //notify
               toastr.success("Verification done successfully");

               $('.reportBoxPan').removeClass('d-none');
               $('.reportBoxPan').addClass('d-block');

               
               //set the output data
               $('.pan_number').html("<strong>"+data.data.pan_number+"</strong>");
               // $('.pan_dob').html("<strong>"+data.data.dob+"</strong>");
               $('.pan_full_name').html("<strong>"+data.data.full_name+"</strong>");
               $('#panReportExport').attr('href',"my/IDcheck/pan/pdf/"+data.data.id);

               $(currentBtn).attr("check-type",checkType);
               $(currentBtn).prop("disabled", false);
               $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
            }
            
         },
         error : function(data)
         {
            console.log(data);
         }
      });

      }else{

            $(currentBtn).parent('td').prev('td').find('span').html("Please enter the valid ID number!");
            setTimeout(function(){ 
               $(currentBtn).attr("check-type",checkType);
               $(currentBtn).prop("disabled", false);
               $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
            }, 1000);
      }

    });
   // 
   //Voter ID check
   $("#voter_id").click(function() {
      //reset error data 
      $(".error-data, span.error").html("");

      $('.reportBox').addClass('d-none');
      $('.reportBox').removeClass('d-block');

      var currentBtn = $(this); 
      //disable button
      $(this).prop("disabled", true);
      var checkType = $(this).attr("check-type");
      // add spinner to button
      $(this).html(
        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
      );
      var service_id=$(this).attr('data-service');
      var inputData = $(currentBtn).parent('td').prev('td').find('input').val();
      var finalInputID = inputData.trim();
      if(inputData !="" && finalInputID.length == 10){
      //
         $.ajax({
            url:"{{ url('my/idCheck/voterID') }}",
            method:"GET",
            data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'service_id':service_id},      
            success:function(data)
            {
               console.log(data);
               if(data.fail)
               {
                  $(currentBtn).parent('td').prev('td').find('span').html("It seems like ID number is not valid!");
                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }
               if(data.fail == false)
               {
                  //notify
                  toastr.success("Verification done successfully");

                  $('.reportBoxVoterID').removeClass('d-none');
                  $('.reportBoxVoterID').addClass('d-block');

                  $dob=data.data.dob!=null?data.data.dob:'N/A';
                  //set the output data
                  $('.voter_id_number').html("<strong>"+data.data.voter_id_number+"</strong>");
                  $('.voter_id_dob').html("<strong>"+$dob+"</strong>");
                  $('.voter_id_age').html("<strong>"+data.data.age+"</strong>");
                  $('.voter_id_gender').html("<strong>"+data.data.gender+"</strong>");
                  $('.voter_id_full_name').html("<strong>"+data.data.full_name+"</strong>");
                  $('.voter_id_relation_name').html("<strong>"+data.data.relation_name+"</strong>");
                  $('.voter_id_relation_type').html("<strong>"+data.data.relation_type+"</strong>");
                  $('.voter_id_house_no').html("<strong>"+data.data.house_no+"</strong>");
                  $('.voter_id_area').html("<strong>"+data.data.area+"</strong>");
                  $('.voter_id_state').html("<strong>"+data.data.state+"</strong>");
                  $('#voterIDReportExport').attr('href',"my/IDcheck/voterID/pdf/"+data.data.id);

                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }
               
            },
            error : function(data)
            {
               console.log(data);
            }
         });
      }else{
            $(currentBtn).parent('td').prev('td').find('span').html("Please enter the valid ID number!");
            setTimeout(function(){ 
               $(currentBtn).attr("check-type",checkType);
               $(currentBtn).prop("disabled", false);
               $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
            }, 1000);
      }

   });

   //ID check - RC
   $("#rc").click(function() {
         //reset error data 
         $(".error-data, span.error").html("");

         $('.reportBox').addClass('d-none');
         $('.reportBox').removeClass('d-block');

         var currentBtn = $(this); 
         //disable button
         $(this).prop("disabled", true);
         var checkType = $(this).attr("check-type");
         // add spinner to button
         $(this).html(
         `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
         );
         var service_id=$(this).attr('data-service');
         var inputData = $(currentBtn).parent('td').prev('td').find('input').val();
         var finalInputID = inputData.trim();
         if(inputData !="" && finalInputID.length > 8){
         //
            $.ajax({
               url:"{{ url('my/idCheck/RC') }}",
               method:"GET",
               data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'service_id':service_id},      
               success:function(data)
               {
                  console.log(data);
                  if(data.fail)
                  {
                     $(currentBtn).parent('td').prev('td').find('span').html("It seems like ID number is not valid!");
                     $(currentBtn).attr("check-type",checkType);
                     $(currentBtn).prop("disabled", false);
                     $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                  }
                  if(data.fail == false)
                  {
                     //notify
                     toastr.success("Verification done successfully");

                     $('.reportBoxRC').removeClass('d-none');
                     $('.reportBoxRC').addClass('d-block');
                     
                     //set the output data
                     $('.rc_number').html("<strong>"+data.data.rc_number+"</strong>");
                     $('.rc_registration_date').html("<strong>"+data.data.registration_date+"</strong>");
                     $('.rc_owner_name').html("<strong>"+data.data.owner_name+"</strong>");
                     $('.rc_chasis_number').html("<strong>"+data.data.vehicle_chasis_number+"</strong>");
                     $('.rc_engine_number').html("<strong>"+data.data.vehicle_engine_number+"</strong>");
                     $('.rc_maker_model').html("<strong>"+data.data.maker_model+"</strong>");
                     $('.rc_fuel_type').html("<strong>"+data.data.fuel_type+"</strong>");
                     $('.rc_norms_type').html("<strong>"+data.data.norms_type+"</strong>");
                     $('.rc_insurance_company').html("<strong>"+data.data.insurance_company+"</strong>");
                     $('.rc_insurance_policy_number').html("<strong>"+data.data.insurance_policy_number+"</strong>");
                     $('.rc_insurance_upto').html("<strong>"+data.data.insurance_upto+"</strong>");
                     $('.rc_registered_at').html("<strong>"+data.data.registered_at+"</strong>");
                     
                     $('#RCReportExport').attr('href',"my/IDcheck/rc/pdf/"+data.data.id);

                     $(currentBtn).attr("check-type",checkType);
                     $(currentBtn).prop("disabled", false);
                     $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                  } 
               },
               error : function(data)
               {
                  console.log(data);
               }
            });
         }else{
               $(currentBtn).parent('td').prev('td').find('span').html("Please enter the valid ID number!");
               setTimeout(function(){ 
                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }, 1000);
         }

   });
// 

   //ID check - passport
   $("#passport").click(function() {
      //reset error data 
      $(".error-data, span.error").html("");

      $('.reportBox').addClass('d-none');
      $('.reportBox').removeClass('d-block');

      var currentBtn = $(this); 
      //disable button
      $(this).prop("disabled", true);
      var checkType = $(this).attr("check-type");
      // add spinner to button
      $(this).html(
        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
      );
      var service_id=$(this).attr('data-service');
      var inputData = $(currentBtn).parent('td').prev('td').find('input.passportNumber').val();
      var inputDataDOB = $(currentBtn).parent('td').prev('td').find('input.passportDOB').val();

      var finalInputID = inputData.trim();
      if(inputData !="" && finalInputID.length > 8){
      //
      $.ajax({
         url:"{{ url('my/idCheck/passport') }}",
         method:"GET",
         data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'dob':inputDataDOB,'service_id':service_id},      
         success:function(data)
         {
            console.log(data);
            if(data.fail)
            {
               $(currentBtn).parent('td').prev('td').find('span').html("It seems like ID number is not valid!");
               $(currentBtn).attr("check-type",checkType);
               $(currentBtn).prop("disabled", false);
               $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
            }
            if(data.fail == false)
            {
               //notify
               toastr.success("Verification done successfully");
               $('.reportBoxPassport').removeClass('d-none');
               $('.reportBoxPassport').addClass('d-block');
               
               //set the output data
               $('.passport_number').html("<strong>"+data.data.passport_number+"</strong>");
               $('.passport_name').html("<strong>"+data.data.full_name+"</strong>");
               $('.passport_dob').html("<strong>"+data.data.dob+"</strong>");
               $('.passport_file_no').html("<strong>"+data.data.file_number+"</strong>");
               $('.passport_dop').html("<strong>"+data.data.date_of_application+"</strong>");
               $('#passportReportExport').attr('href',"my/IDcheck/passport/pdf/"+data.data.id);

               $(currentBtn).attr("check-type",checkType);
               $(currentBtn).prop("disabled", false);
               $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
            }
            
         },
         error : function(data)
         {
            console.log(data);
         }
      });
      }else{
            $(currentBtn).parent('td').prev('td').find('span').html("Please enter the valid ID number! and DOB");
            setTimeout(function(){ 
               $(currentBtn).attr("check-type",checkType);
               $(currentBtn).prop("disabled", false);
               $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
            }, 1000);
      }

    });
// 
   //ID check - DL      
   $("#driving_license").click(function() {
         //reset error data 
         $(".error-data, span.error").html("");

         $('.reportBox').addClass('d-none');
         $('.reportBox').removeClass('d-block');

         var currentBtn = $(this); 
         //disable button
         $(this).prop("disabled", true);
         var checkType = $(this).attr("check-type");
         // add spinner to button
         $(this).html(
         `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
         );
         var service_id=$(this).attr('data-service');
         var inputData = $(currentBtn).parent('td').prev('td').find('input').val();
         var finalInputID = inputData.trim();
         if(inputData !="" && finalInputID.length > 10){
         //
            $.ajax({
               url:"{{ url('my/idCheck/DL') }}",
               method:"GET",
               data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'service_id':service_id},      
               success:function(data)
               {
                  console.log(data);
                  if(data.fail)
                  {
                     $(currentBtn).parent('td').prev('td').find('span').html("It seems like ID number is not valid!");
                     $(currentBtn).attr("check-type",checkType);
                     $(currentBtn).prop("disabled", false);
                     $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                  }
                  if(data.fail == false)
                  {
                     //notify
                     toastr.success("Verification done successfully");
                     $('.reportBoxDL').removeClass('d-none');
                     $('.reportBoxDL').addClass('d-block');
                     
                     //set the output data
                     $('.dl_number').html("<strong>"+data.data.dl_number+"</strong>");
                     $('.dl_name').html("<strong>"+data.data.name+"</strong>");
                     $('.dl_gender').html("<strong>"+data.data.gender+"</strong>");
                     $('.dl_dob').html("<strong>"+data.data.dob+"</strong>");
                     $('.dl_address').html("<strong>"+data.data.permanent_address+"</strong>");
                     $('.dl_state').html("<strong>"+data.data.state+"</strong>");
                     $('.dl_country').html("<strong>"+data.data.citizenship+"</strong>");
                     $('.dl_spouse').html("<strong>"+data.data.father_or_husband_name+"</strong>");
                     $('.dl_ola_name').html("<strong>"+data.data.ola_name+"</strong>");
                     $('.dl_doe').html("<strong>"+data.data.doe+"</strong>");
                     $('#DLReportExport').attr('href',"my/IDcheck/dl/pdf/"+data.data.id);

                     $(currentBtn).attr("check-type",checkType);
                     $(currentBtn).prop("disabled", false);
                     $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                  }
                  
               },
               error : function(data)
               {
                  console.log(data);
               }
            });
         }else{
               $(currentBtn).parent('td').prev('td').find('span').html("Please enter the valid ID number!");
               setTimeout(function(){ 
                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }, 1000);
         }

   });

      //ID check - passport
   
   $("#gstin").click(function() {
      //reset error data 
      $(".error-data, span.error").html("");

      $('.reportBox').addClass('d-none');
      $('.reportBox').removeClass('d-block');

      var currentBtn = $(this); 
      //disable button
      $(this).prop("disabled", true);
      var checkType = $(this).attr("check-type");
      // add spinner to button
      $(this).html(
        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
      );
      var service_id=$(this).attr('data-service');
      var inputData = $(currentBtn).parent('td').prev('td').find('input.gstNumber').val();
      var inputDataFilling = $(currentBtn).parent('td').prev('td').find('input.gstFilling').val();

      var finalInputID = inputData.trim();
      if(inputData !="" && finalInputID.length > 14){
      //
         $.ajax({
            url:"{{ url('my/idCheck/gstin') }}",
            method:"GET",
            data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'filing_status_get':inputDataFilling,'service_id':service_id},      
            success:function(data)
            {
               console.log(data);
               if(data.fail)
               {
                  $(currentBtn).parent('td').prev('td').find('span').html("It seems like ID number is not valid!");
                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }
               if(data.fail == false)
               {
                  // console.log(data.data);
                  //notify
                  toastr.success("Verification done successfully");
                  $('.reportBoxGST').removeClass('d-none');
                  $('.reportBoxGST').addClass('d-block');
                  
                  //set the output data
                  $('.gst_number').html("<strong>"+data.data.gst_number+"</strong>");
                  $('.gst_business_name').html("<strong>"+data.data.business_name+"</strong>");
                  $('.gst_address').html("<strong>"+data.data.address+"</strong>");
                  $('.gst_center_jurisdiction').html("<strong>"+data.data.center_jurisdiction+"</strong>");
                  $('.gst_date_of_registration').html("<strong>"+data.data.date_of_registration+"</strong>");
                  $('.gst_constitution_of_business').html("<strong>"+data.data.constitution_of_business+"</strong>");
                  $('.gst_taxpayer_type').html("<strong>"+data.data.taxpayer_type+"</strong>");
                  $('.gst_gstin_status').html("<strong>"+data.data.gstin_status+"</strong>");


                  $('#GSTReportExport').attr('href',"my/IDcheck/gstin/pdf/"+data.data.id);

                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }
               
            },
            error : function(data)
            {
               console.log(data);
            }
         });
      }else{
            $(currentBtn).parent('td').prev('td').find('span').html("Please enter the valid ID number!");
            setTimeout(function(){ 
               $(currentBtn).attr("check-type",checkType);
               $(currentBtn).prop("disabled", false);
               $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
            }, 1000);
      }

   });




   // Advance Aadhar Check
   $(".advance_check").click(function(){
      var service_id=$(this).attr('data-service');
      $('#service_id').val(service_id);
      $('#advanced_check')[0].reset();
      $('#send_otp')[0].reset();
      $('.otp').removeClass('border-danger');
      $('.form-control').removeClass('border-danger');
      $('.error-container').html('');
      $('#advance_check').modal({
               backdrop: 'static',
               keyboard: false
      });
   });

   $(document).on('submit', 'form#advanced_check', function (event) {

         $("#overlay").fadeIn(300);　
         event.preventDefault();
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var $btn = $(this);
         $('.form-control').removeClass('border-danger');
         $('.error-container').html('');
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('.btn_d').attr('disabled',true);
         if($('.aadhar_submit').html()!=loadingText)
         {
            $('.aadhar_submit').html(loadingText);
         }
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
               // console.log(data);
               // $('.error-container').html('');
               window.setTimeout(function(){
                     $('.btn_d').attr('disabled',false);
                     $('.aadhar_submit').html('Send Otp');
                  },2000);
               if (data.fail && data.error_type == 'validation') {
                     
                     //$("#overlay").fadeOut(300);
                     for (control in data.errors) {
                        // $('input[otp=' + control + ']').addClass('is-invalid');
                        $('.' + control).addClass('border-danger');
                        $('#error-' + control).html(data.errors[control]);
                     }
               } 
               if (data.fail && data.error == 'yes') {
                  
                  // $('#error-all').html(data.message);
                  $('#error-wrong_aadhar').html('Sorry, Unable to get the record, Please try again later');
               }
               if (data.fail == false) {
                  $('#advance_check').modal('hide');
                  $('#serv_id').val(data.service_id);
                  $('#send_otp_modal').modal({
                     backdrop: 'static',
                        keyboard: false
               });
               // $("#send_otp").on('show.bs.modal', function (e) {
               //    $("#advance_check").modal("hide");
               // });
                  //  location.reload(); 
               }
            },
            error : function(data)
            {
               console.log(data);
            }
         });
         return false;

   });

   $(document).on('submit', 'form#send_otp', function (event) {

      $("#overlay").fadeIn(300);　
      event.preventDefault();
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var $btn = $(this);
         $('.otp').removeClass('border-danger');
         $('.form-control').removeClass('border-danger');
         $('.error-container').html('');
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            $('.btn_d').attr('disabled',true);
            if($('.aadhar_otp').html()!=loadingText)
            {
               $('.aadhar_otp').html(loadingText);
            }
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
               // console.log(data);
               // $('.error-container').html('');
               window.setTimeout(function(){
                     $('.btn_d').attr('disabled',false);
                     $('.aadhar_otp').html('Submit');
                  },2000);
               if (data.fail && data.error_type == 'validation') {
                     
                     //$("#overlay").fadeOut(300);
                     for (control in data.errors) {
                        // $('input[otp=' + control + ']').addClass('is-invalid');
                        $('.' + control).addClass('border-danger');
                        $('.error-' + control).html(data.errors[control]);
                     }
               } 
               if (data.fail && data.error == 'yes') {
                  
                  // $('#error-all').html(data.message);
                  $('#error-wrong_otp').html('Sorry, Unable to get the record, Please try again later');
               }
               if (data.fail == false) {
                  // $('#send_otp').modal('hide');
                  // var path="{{url('/').'/my/aadharchecks/show'}}";
                  var client_id=data.client_id;
                  window.location.href="{{url('/')}}"+"/my/aadharchecks/show/"+client_id;

                  // window.open(path);
                  //  location.reload(); 
               }
            },
            error : function(data)
            {
               console.log(data);
            }
         });
         return false;

   });

   //   
   //insta finance 

   $("#instaFinance").click(function() {
      //reset error data 
      $(".error-data, span.error").html("");

      $('.reportBox').addClass('d-none');
      $('.reportBox').removeClass('d-block');

      var currentBtn = $(this); 
      //disable button
      $(this).prop("disabled", true);
      var checkType = $(this).attr("check-type");
      // add spinner to button
      $(this).html(
      `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
      );

      var inputData = $(currentBtn).parent('td').prev('td').find('input').val();
      var finalInputID = inputData.trim();
      if(inputData !="" && finalInputID.length > 3){
      //
         $.ajax({
            url:"{{ route('/verifications/instaFinance') }}",
            method:"GET",
            data:{"_token": "{{ csrf_token() }}",'cin_number':inputData},      
            success:function(data)
            {
               console.log(data);
               if(data.fail)
               {
                  $(currentBtn).parent('td').prev('td').find('span').html("It seems like ID number is not valid!");
                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }
               if(data.fail == false)
               {
                  //notify
                  toastr.success("Verification Ordered");
                  $('.reportBoxInsta').removeClass('d-none');
                  $('.reportBoxInsta').addClass('d-block');
                  
                  //set the output data
                  $('.cin_number').html("<strong>"+data.data.input_number+"</strong>"); 

                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }
               
            }
         });
      }else{
            $(currentBtn).parent('td').prev('td').find('span').html("Please enter the valid ID number!");
            setTimeout(function(){ 
               $(currentBtn).attr("check-type",checkType);
               $(currentBtn).prop("disabled", false);
               $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
            }, 1000);
      }

   });

   $('#telecom').click(function(){
         //reset error data 
         $(".error-data, span.error").html("");
         $('.reportBox').addClass('d-none');
         $('.reportBox').removeClass('d-block');
         var currentBtn = $(this); 
         //disable button
         $(this).prop("disabled", true);
         var checkType = $(this).attr("check-type");
         // add spinner to button
         $(this).html(
         `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
         );
         var service_id=$(this).attr('data-service');
         var inputData = $(currentBtn).parent('td').prev('td').find('input').val();
         var finalInputID = inputData.trim();
         if(inputData !="" && finalInputID.length == 10){
         //
            $.ajax({
               url:"{{ url('my/idCheck/telecom') }}",
               method:"GET",
               data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'service_id':service_id},      
               success:function(data)
               {
                  console.log(data);
                  if(data.fail)
                  {
                     $(currentBtn).parent('td').prev('td').find('span').html(data.message);
                     $(currentBtn).attr("check-type",checkType);
                     $(currentBtn).prop("disabled", false);
                     $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                  }
                  if(data.fail == false)
                  {
                     if(data.db)
                     {
                        toastr.success("Verification done successfully");
                        $('.reportBoxTelecom').removeClass('d-none');
                        $('.reportBoxTelecom').addClass('d-block');
                        var name=data.name;
                        var dob =data.dob;
                        var addr=data.address;
                        var city=data.city;
                        var state=data.state;
                        var pin_code=data.pin_code;
                        var mobile=data.mobile;
                        var alter=data.alternative;
                        var op=data.operator;
                        var bill=data.billing_type;
                        var email=data.email;

                           //set the output data
                        $('.t_name').html("<strong>"+name+"</strong>");
                        $('.t_dob').html("<strong>"+dob+"</strong>");
                        $('.t_address').html("<strong>"+addr+"</strong>");
                        $('.t_city').html("<strong>"+city+"</strong>");
                        $('.t_state').html("<strong>"+state+"</strong>");
                        $('.t_pin').html("<strong>"+pin_code+"</strong>");
                        $('.t_phone_number').html("<strong>"+mobile+"</strong>");
                        $('.t_alternative').html("<strong>"+alter+"</strong>");
                        $('.t_operator').html("<strong>"+op+"</strong>");
                        $('.t_bill').html("<strong>"+bill+"</strong>");
                        $('.t_email').html("<strong>"+email+"</strong>");

                        $('#telecomReportExport').attr('href',"my/IDcheck/telecom/pdf/"+data.id);
                        $(currentBtn).attr("check-type",checkType);
                        $(currentBtn).prop("disabled", false);
                        $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                     }
                     else{
                        $('#telecom_check')[0].reset();
                        $('#mob_t').val(finalInputID);
                        $('#client_id').val(data.client_id);

                        $('.otp').removeClass('border-danger');
                        $('.form-control').removeClass('border-danger');
                        $('.error-container').html('');
                        //notify
                        $('#ser_id').val(service_id);
                        $('#send_otp_telecom').modal({
                           backdrop: 'static',
                           keyboard: false
                        });
                        $(currentBtn).attr("check-type",checkType);
                        $(currentBtn).prop("disabled", false);
                        $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');

                     }
                  }
               },
               error : function(data)
               {
                  console.log(data);
               }
            });

         }else{

               $(currentBtn).parent('td').prev('td').find('span').html("Please enter the valid Phone number!");
            
               setTimeout(function(){ 
                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }, 1000);
         }

   });

   $(document).on('submit','form#telecom_check',function(){

         $('.reportBox').addClass('d-none');
         $('.reportBox').removeClass('d-block');
         $("#overlay").fadeIn(300);　
         event.preventDefault();
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var $btn = $(this);
            $('.error-container').html('');
            $('.otp').removeClass('border-danger');
            $('.form-control').removeClass('border-danger');
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            $('.btn_d').attr('disabled',true);
            if($('.telecom_otp').html()!=loadingText)
            {
               $('.telecom_otp').html(loadingText);
            }
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
               // console.log(data);
               // $('.error-container').html('');
               window.setTimeout(function(){
                     $('.btn_d').attr('disabled',false);
                     $('.telecom_otp').html('Submit');
                  },2000);
               if (data.fail && data.error_type == 'validation') {
                     
                     //$("#overlay").fadeOut(300);
                     for (control in data.errors) {
                        // $('input[otp=' + control + ']').addClass('is-invalid');
                        $('.' + control).addClass('border-danger');
                        $('.error-' + control).html(data.errors[control]);
                     }
               } 
               if (data.fail && data.error == 'yes') {
                  
                  $('#error-all').html(data.message);
               }
               if (data.fail == false) {
                  // $('#advance_check').modal('hide');
                  console.log(data);
                  $('#send_otp_telecom').modal('hide');
                  toastr.success("Verification done successfully");
                  $('.reportBoxTelecom').removeClass('d-none');
                  $('.reportBoxTelecom').addClass('d-block');
                  var name=data.name;
                  var dob =data.dob;
                  var addr=data.address;
                  var city=data.city;
                  var state=data.state;
                  var pin_code=data.pin_code;
                  var mobile=data.mobile;
                  var alter=data.alternative;
                  var op=data.operator;
                  var bill=data.billing_type;
                  var email=data.email;

                     //set the output data
                  $('.t_name').html("<strong>"+name+"</strong>");
                  $('.t_dob').html("<strong>"+dob+"</strong>");
                  $('.t_address').html("<strong>"+addr+"</strong>");
                  $('.t_city').html("<strong>"+city+"</strong>");
                  $('.t_state').html("<strong>"+state+"</strong>");
                  $('.t_pin').html("<strong>"+pin_code+"</strong>");
                  $('.t_phone_number').html("<strong>"+mobile+"</strong>");
                  $('.t_alternative').html("<strong>"+alter+"</strong>");
                  $('.t_operator').html("<strong>"+op+"</strong>");
                  $('.t_bill').html("<strong>"+bill+"</strong>");
                  $('.t_email').html("<strong>"+email+"</strong>");
                  // //  location.reload(); 
                  // alert('success');
                  $('#telecomReportExport').attr('href',"my/IDcheck/telecom/pdf/"+data.id);

                  // $('#telecom').attr("check-type",checkType);
                  // $('#telecom').prop("disabled", false);
                  // $('#telecom').html('<i class="fa fa-hand-point-right"></i> Go');

               }
            },
            error : function(data)
            {
               console.log(data);
            }
         });
         return false;
   });

   //bank info

   $("#bank_verification").click(function() {
         //reset error data 
         $(".error-data, span.error").html("");

         $('.reportBox').addClass('d-none');
         $('.reportBox').removeClass('d-block');

         var currentBtn = $(this); 
         //disable button
         $(this).prop("disabled", true);
         var checkType = $(this).attr("check-type");
         // add spinner to button
         $(this).html(
         `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
         );
         var service_id=$(this).attr('data-service');
         var inputData        = $(currentBtn).parent('td').prev('td').find('input.bankACNumber').val();
         var inputDataIFSC    = $(currentBtn).parent('td').prev('td').find('input.bankIFSC').val();

         var finalInputID = inputData.trim();
         if(inputData !="" && finalInputID.length > 3 && inputDataIFSC !=""){
         //
            $.ajax({
               url:"{{ route('/my/idCheck/banking') }}",
               method:"GET",
               data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'ifsc':inputDataIFSC,'service_id':service_id},      
               success:function(data)
               {
                  // console.log(data.data.account_number);
                  if(data.fail)
                  {
                     $(currentBtn).parent('td').prev('td').find('span').html("It seems like ID number is not valid!");
                     $(currentBtn).attr("check-type",checkType);
                     $(currentBtn).prop("disabled", false);
                     $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                  }
                  if(data.fail == false)
                  {
                     console.log(data);
                     //notify
                     toastr.success("Verification Done");
                     $('.reportBoxBank').removeClass('d-none');
                     // $('.reportBoxbalert(service_id)').addClass('d-block');
                     
                     //set the output data
                     $('#bank_ac_number').html("<strong>"+data.data.account_number+"</strong>"); 
                     $('#bank_ac_name').html("<strong>"+data.data.full_name+"</strong>"); 
                     $('#ifsc_code').html("<strong>"+data.data.ifsc_code+"</strong>"); 

                     $('#BankReportExport').attr('href',"/my/IDcheck/bank/pdf/"+data.data.id);

                     $(currentBtn).attr("check-type",checkType);
                     $(currentBtn).prop("disabled", false);
                     $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                  }
                  
               },
               error : function(data)
               {
                  console.log(data);
               }
            });
         }else{
               $(currentBtn).parent('td').prev('td').find('span').html("Please fill the required fields!");
               setTimeout(function(){ 
                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }, 1000);
         }

   });

   //when click on Accept button
   $(document).on('click', '.accept', function (event) {
        
      //   if(confirm("Are you sure want to Accept the terms & Conditions ?")){
        $.ajax({
            type:'GET',
            url: "{{route('/my/verifications/term_accept')}}",
            data: {},        
            success: function (response) {        
               console.log(response);
            },
            error: function (xhr, textStatus, errorThrown) {
                alert("Error: " + errorThrown);
            }
        });

      //   }
        return false;

   });

      // Covid 19 Certifcate
      $('#covid_19_certificate').click(function(){
            //reset error data 
            $(".error-data, span.error").html("");
            $('.reportBox').addClass('d-none');
            $('.reportBox').removeClass('d-block');
            var currentBtn = $(this); 
            //disable button
            $(this).prop("disabled", true);
            var checkType = $(this).attr("check-type");

            $('#covid19_check')[0].reset();
            $('#covid19_ref_check')[0].reset();
            $('.error-container').html('');
            $('.otp').removeClass('border-danger');
            $('form-control').removeClass('is-invalid');
            $('.btn_disable').attr('disabled',false);
            // add spinner to button
            $(this).html(
            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
            );

            var inputData = $(currentBtn).parent('td').prev('td').find('input').val();
            var finalInputID = inputData.trim();
            var service_id=$(this).attr('data-service');
            // alert(service_id);
            if(inputData !="" && finalInputID.length == 10){
               //
               $.ajax({
                  url:"{{ url('/my/idCheck/covid19') }}",
                  method:"GET",
                  data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'service_id':service_id},      
                  success:function(data)
                  {
                     window.setTimeout(function(){
                        $(currentBtn).prop("disabled", false);
                        $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                     },1000);
                     if(data.fail)
                     {
                        $(currentBtn).parent('td').prev('td').find('span').html(data.message);
                        $(currentBtn).attr("check-type",checkType);
                        $(currentBtn).prop("disabled", false);
                        $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                     }
                     if(data.fail == false)
                     {
                        $('#mob_c').val(finalInputID);
                        $('#txnId').val(data.txnId);
                        $('#otp_id').val(data.otp_id);
                        $('.ser_id').val(service_id);
                        //notify
                        $('#send_otp_covid').modal({
                           backdrop: 'static',
                           keyboard: false
                        });

                        $(currentBtn).attr("check-type",checkType);
                        $(currentBtn).prop("disabled", false);
                        $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');

                     }
                  },
                  error : function(data)
                  {
                     console.log(data);
                  }
               });

            }else{

                  $(currentBtn).parent('td').prev('td').find('span').html("Please enter the valid Phone number!");
               
                  setTimeout(function(){ 
                     $(currentBtn).attr("check-type",checkType);
                     $(currentBtn).prop("disabled", false);
                     $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                  }, 1000);
            }

      });

      $(document).on('submit','form#covid19_check',function(event){

         $('.reportBox').addClass('d-none');
         $('.reportBox').removeClass('d-block');
         $("#overlay").fadeIn(300);　
         event.preventDefault();
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var $btn = $(this);
         $('.error-container').html('');
         $('.otp').removeClass('border-danger');
         $('form-control').removeClass('border-danger');
         $('.btn_disable').attr('disabled',true);
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
               console.log(data);
               // $('.error-container').html('');
               window.setTimeout(function(){
                  $('.btn_disable').attr('disabled',false);
               },2000);
               if (data.fail && data.error_type == 'validation') {
                     //$("#overlay").fadeOut(300);
                     for (control in data.errors) {
                        $('.' + control).addClass('border-danger');
                        $('.error-' + control).html(data.errors[control]);
                     }
               } 
               if (data.fail && data.error == 'yes') {
                  
                  $('.error-all').html(data.message);
               }
               if (data.fail == false) {
                  // $('#advance_check').modal('hide');
                  // console.log(data);

                  $('#send_otp_covid').modal('hide');

                  $('.otp_id').val(data.id);
                  $('.ser_id').val(data.service_id);
                  $('.mob_c').val(data.mobile_no);
                  $('#covid19_ref').modal({
                     backdrop: 'static',
                     keyboard: false
                  });

               }
            },
            error : function(data)
            {
               console.log(data);
            }
         });
         return false;
      });

      $(document).on('submit','form#covid19_ref_check',function(event){

         $('.reportBox').addClass('d-none');
         $('.reportBox').removeClass('d-block');
         $("#overlay").fadeIn(300);　
         event.preventDefault();
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var $btn = $(this);
         $('.error-container').html('');
         $('form-control').removeClass('border-danger');

         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
               console.log(data);
               $('.error-container').html('');
               if (data.fail && data.error_type == 'validation') {
                     //$("#overlay").fadeOut(300);
                     for (control in data.errors) {
                        $('input[name=' + control + ']').addClass('border-danger');
                        $('.error-' + control).html(data.errors[control]);
                     }
               } 
               if (data.fail && data.error == 'yes') {
                  
                  $('.error-all').html(data.message);
               }
               if (data.fail == false) {
                  $('#covid19_ref').modal('hide');
                  // console.log(data);
                  toastr.success("Verification Done");
                  $('.reportBoxCovid19').removeClass('d-none');
                  $('.reportBoxCovid19').addClass('d-block');
                  
                  //set the output data
                  $('.c_mobile_number').html("<strong>"+data.data.mobile_no+"</strong>"); 
                  $('.c_reference_id').html("<strong>"+data.data.reference_id+"</strong>"); 

                  $('#covid19ReportExport').attr('href',data.url);

                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }
            },
            error : function(data)
            {
               console.log(data);
            }
         });
         return false;
      });

      //e_court

      $("#e_court").click(function() {
         //reset error data 
         $(".error-data, span.error").html("");

         $('.reportBox').addClass('d-none');
         $('.reportBox').removeClass('d-block');

         var currentBtn = $(this); 
         //disable button
         $(this).prop("disabled", true);
         var checkType = $(this).attr("check-type");
         // add spinner to button
         $(this).html(
         `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
         );
         var service_id=$(this).attr('data-service');
         var inputData        = $(currentBtn).parent('td').prev('td').find('input.name').val();
         var inputDataFather        = $(currentBtn).parent('td').prev('td').find('input.fathername').val();
         var inputDataAddress        = $(currentBtn).parent('td').prev('td').find('input.address').val();

         var finalInputID = inputData.trim();
         if(inputData !="" && finalInputID.length >= 3 && inputDataFather!='' && inputDataAddress!=''){
         //
         $.ajax({
            url:"{{ route('/my/idCheck/ecourt') }}",
            method:"GET",
            data:{"_token": "{{ csrf_token() }}",'name':inputData,'fathername':inputDataFather,'address':inputDataAddress,'service_id':service_id},      
            success:function(data)
            {
               // console.log(data);
               if(data.fail)
               {
                  $(currentBtn).parent('td').prev('td').find('span').html(data.error);
                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }
               if(data.fail == false)
               {
                  //notify
                  toastr.success("Verification Done");
                  $('.reportBoxEcourt').removeClass('d-none');
                  $('.reportBoxEcourt').addClass('d-block');
                  
                  //set the output data
                  $('.e_name').html("<strong>"+data.data.name+"</strong>"); 
                  $('.e_father_name').html("<strong>"+data.data.father_name+"</strong>");
                  $('.e_address').html("<strong>"+data.data.address+"</strong>");

                  $('#ecourtReportExport').attr('href',"/my/IDcheck/ecourt/pdf/"+data.data.id);

                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }
               
            },
            error : function(data)
            {
               // console.log(data);
            }
         });
         }else{
               $(currentBtn).parent('td').prev('td').find('span').html("Please fill the required fields!");
               setTimeout(function(){ 
                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }, 1000);
         }

      });

      //upi

      $("#upi").click(function() {
         //reset error data 
         $(".error-data, span.error").html("");

         $('.reportBox').addClass('d-none');
         $('.reportBox').removeClass('d-block');

         var currentBtn = $(this); 
         //disable button
         $(this).prop("disabled", true);
         var checkType = $(this).attr("check-type");
         // add spinner to button
         $(this).html(
         `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
         );
         var service_id=$(this).attr('data-service');
         var inputData        = $(currentBtn).parent('td').prev('td').find('input').val();

         var finalInputID = inputData.trim();
         if(inputData !="" && finalInputID.length >= 3){
         //
         $.ajax({
            url:"{{ route('/my/idCheck/upi') }}",
            method:"GET",
            data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'service_id':service_id},      
            success:function(data)
            {
               // console.log(data);
               if(data.fail)
               {
                  $(currentBtn).parent('td').prev('td').find('span').html(data.error);
                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }
               if(data.fail == false)
               {
                  //notify
                  toastr.success("Verification Done");
                  $('.reportBoxUpi').removeClass('d-none');
                  $('.reportBoxUpi').addClass('d-block');
                  
                  //set the output data
                  $('.u_id').html("<strong>"+data.data.upi_id+"</strong>");
                  $('.u_name').html("<strong>"+data.data.name+"</strong>"); 

                  $('#upiReportExport').attr('href',"/my/IDcheck/upi/pdf/"+data.data.id);

                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }
               
            },
            error : function(data)
            {
               // console.log(data);
            }
         });
         }else{
               $(currentBtn).parent('td').prev('td').find('span').html("Please fill the required fields!");
               setTimeout(function(){ 
                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }, 1000);
         }

      });

});

function OTPInput() {
      const inputs = document.querySelectorAll('.otp');
      // alert(inputs.length);
      for (let i = 0; i < inputs.length; i++) 
      { 
         inputs[i].addEventListener('keyup', function(event) 
         { 
            if (event.key==="Backspace" ) 
            { 
                  inputs[i].value='' ; 
                  if (i !==0) inputs[i - 1].focus();
                  
            } 
            else { 
                  if (i===inputs.length - 1 && inputs[i].value !=='' ) 
                  { return true; } 
                  else if (event.keyCode> 47 && event.keyCode < 58) 
                  { 
                     inputs[i].value=event.key; 
                     if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); 
                     
                  } 
                  else if (event.keyCode> 95 && event.keyCode < 106) 
                  { 
                     inputs[i].value=event.key; 
                     if (i !==inputs.length - 1) 
                     inputs[i + 1].focus(); event.preventDefault(); 
                     
                  }
            } 
            
         }); 
         
      } 
      
} 
OTPInput(); 

</script>

@endsection
