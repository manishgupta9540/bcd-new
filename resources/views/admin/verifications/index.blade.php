@extends('layouts.admin')
@section('content')
<style>
   .select2-container--default .select2-selection--multiple .select2-selection__rendered{
      height: 100%;
   }
</style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>Instant Verification</li>
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
         <div class="col-md-12">
            <div class="card text-left"> 
               <div class="card-body">
                  <div class="row">
                     <div class="col-md-12">
                        @include('admin.verifications.menu')
                     </div>

                     @if ($message = Session::get('success'))
                     <div class="col-md-12">
                        <div class="alert alert-success">
                           <strong>{{ $message }}</strong> 
                        </div>
                     </div>
                     @endif
                     <div class="col-md-12 text-center mt-3">
                        <h4 class="card-title mt-2 mb-1"> Instant Verification </h4>
                        <p> Available ID Checks</p>
                     </div>
                     {{-- <div class="col-md-4">
                        <div class="btn-group" style="float:right">
                          
                        </div>
                     </div> --}}
                  </div>
                  <div class="row align-items-center justify-content-center">
                     <div class="col-md-4">
                        <div class="form-group">
                           <label>Select the Check Type</label>
                           <select class="form-control service_list" name="services">
                              <option value="">--Select--</option>
                              @if(count($services)>0)
                                 @foreach ($services as $item)
                                    <option value="{{$item->id}}">{{ $item->name }}</option>
                                 @endforeach
                              @endif
                           </select>
                        </div>
                     </div>
                  </div>

                 

                  <!-- row start -->
                  <div class="row">
                     <div class="col-md-12">
                        {{-- <div class="table-responsive"> --}}
                           <p class="text-danger text-center error-data"></p>
                           <table class="table table-bordered table-hover service_table">
                              <thead>
                                 <tr>
                                    {{-- <th scope="col">#</th> --}}
                                    <th scope="col" style="position:sticky; top:60px"> Check </th>
                                    <th scope="col" style="position:sticky; top:60px"> ID Number </th>
                                    <th scope="col" style="position:sticky; top:60px"> Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td scope="row" colspan="7">
                                       <h3 class="text-center">No record!</h3>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        {{-- </div> --}}
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
                                 <tr> <td>Aadhar Validity</td> <td class='aadhar_validity'> Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
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
                   <!-- row report Financial-->
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
                  <!-- ./report Financial end -->
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
                                 <tr> <td>Name </td> <td class='bank_ac_name'></td> </tr>
                                 <tr> <td width="50%">Bank Account Number</td> <td width="50%" class="bank_ac_number"></td> </tr>
                                 <tr> <td>IFSC Code</td> <td class='ifsc_code'></td> </tr>
                                
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
                   <!-- row report -->
                  <div class="row reportBox reportBoxCIN d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report - <a id="cinReportExport" target="_blank" href="" download="">Download PDF</a></p>
                     <div class="table-responsive">
                        
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>CIN Verification</b></h3>

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
                           <h3 class="text-center"><b>CIN Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td>CIN Validity</td> <td class='phone_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='phone_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td class="cin_result">
                                       CIN Verification Completed <br>
                                       {{-- Phone number exist <span class="t_phone_number"></span> <br> --}}
                                       CIN Number : <span class="c_id"></span> <br>
                                       Company Name : <span class="c_name"></span> <br>
                                       Registration No. : <span class="c_reg_no"></span> <br>
                                       Address : <span class="c_addr"></span> <br>
                                       Date of Incorporation : <span class="c_doi"></span> <br>
                                       Email ID : <span class="c_email"></span> <br>
                                       Paid Up Capital In Rupees : <span class="c_prs"></span> <br>
                                       Authorised Capital : <span class="c_ac"></span> <br>
                                       Category : <span class="c_cat"></span> <br>
                                       Sub Category : <span class="c_sub_cat"></span> <br>
                                       Class : <span class="c_class"></span> <br>
                                       E-Filling Status : <span class="c_efill"></span> <br>
                                       Date of Last AGM : <span class="c_doa"></span> <br>
                                       Date of Balance Sheet : <span class="c_bs"></span> <br>
                                       
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
                  <div class="row reportBox reportBoxCourtV1 d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report - <a id="courtV1ReportExport" target="_blank" href="" download="">Download PDF</a></p>
                     <div class="table-responsive">
                        
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>Court Check V1 Verification</b></h3>

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
                           <h3 class="text-center"><b>Court Check V1 Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td>Court Check V1 Validity</td> <td class='phone_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='phone_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td class="court_v1_result">
                                       Court Check V1 Verification Completed <br>
                                       {{-- Phone number exist <span class="t_phone_number"></span> <br> --}}
                                       Court Check V1 Number : <span class="c_id"></span> <br>
                                       Name : <span class="ct_v1_name"></span> <br>
                                       Address : <span class="ct_v1_addr"></span> <br>
                                       Father Name : <span class="ct_v1_father_name"></span> <br>
                                       State : <span class="ct_v1_state"></span> <br>
                                       Type : <span class="ct_v1_type"></span> <br>
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
                   <div class="row reportBox reportBoxVital d-none">
                     <div class="col-md-10 offset-1">
                        <p style="font-size: 16px;">Report -  <a id="vitalReportExport" class="vitalreport" href="" download="">Download PDF</a></p>
                     <div class="table-responsive">
                        
                        <div class="col-md-10">
                        {!! Helper::company_logo(Auth::user()->business_id) !!}
                        </div>
                        <h3 class="text-center"> <b>Vital Verification</b></h3>

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
                           <h3 class="text-center"><b>Vital Verification </b></h3>
                           <table class="table table-bordered">
                              <tbody>
                                 <tr> <td>Vital Validity</td> <td class='phone_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                                 <tr> <td>Verification Check</td> <td class='phone_check'>Completed</td> </tr>
                                 <tr> <td>Result</td> 
                                 <td>
                                       Vital Verification Completed <br>
                                       {{-- Phone number exist <span class="t_phone_number"></span> <br> --}}
                                       Name : <span class="user_name"></span> <br>
                                       User Id : <span class="id_number"></span> <br>
                                       Country  : <span class="country"></span> <br>
                                       State  : <span class="state"></span> <br>
                                       City  : <span class="city"></span> <br>
                                       Date Of Birth  : <span class="date_of_birth"></span> <br>
                                       Address  : <span class="address"></span> <br>
                                       Postal Code : <span class="postal_code"></span> <br>
                                                    <span class="vital_report"></span>
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
            <form method="post" action="{{ url('/idAdvanceCheck/aadhar') }}" id="advanced_check">
            @csrf
            <input type="hidden" name="service_id" id="service_id" class="service_id">
               <div class="modal-body">
                  <div class="form-group">
                        <label for="label_name"> Aadhaar Id <span class="text-danger">*</span></label>
                        <input type="text" id="aadhaar_id" name="aadhaar_id" class="form-control aadhaar_id" placeholder="Enter Id"/>
                        <p style="margin-bottom: 2px;" class="text-danger error-container error-aadhaar_id" id="error-aadhaar_id"></p> 
                  </div>
                  <p style="margin-bottom: 2px;" class="text-danger error-container error-wrong_aadhar" id="error-wrong_aadhar"> </p> 
               </div>
               <!-- Modal footer -->
               <div class="modal-footer">
                  
                  <button type="submit" class="btn btn-info aadhar_submit btn_d" >Send Otp </button>
                  <button type="button" class="btn btn-danger btn_d" data-dismiss="modal">Close</button>
               </div>
            </form>
         </div>
      </div>
   </div>

{{-- Modal for otp verification    --}}

<div class="modal" id="send_otp_modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">OTP Verification for Aadhar</h4>
            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
         <form method="post" action="{{ url('/idAdvanceCheckOtp/aadharOtp') }}" id="send_otp">
         @csrf
         <input type="hidden" name="serv_id" id="serv_id" class="serv_id">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            <div class="form-group">
               <label for="label_name"> Mobile Number  </label>
               <input type="text" id="mob" name="mob" class="form-control mob" placeholder="Enter Phone number"/>
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
                            <input name="otp[]" class="digit text-center otp" type="text" id="fifth_otp" size="1" maxlength="1"  tabindex="2">
                            <input name="otp[]" class="digit text-center otp" type="text" id="sixth_otp" size="1" maxlength="1" tabindex="3">
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
               <button type="submit" class="btn btn-info aadhar_otp btn_d" >Submit</button>
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
         <form method="post" action="{{ url('/idAdvanceCheck/telecom') }}" id="telecom_check">
         @csrf
            <input type="hidden" name="client_id" id="client_id">
            <input type="hidden" name="ser_id" id="ser_id" class="ser_id">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            <div class="form-group">
               <label for="label_name"> Mobile Number  </label>
               <input type="text" id="mob_t" name="mob_t" class="form-control mob_t" placeholder="Enter Phone number" readonly>
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
               <button type="submit" class="btn btn-info telecom_otp btn_d">Submit</button>
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
            {{-- <button type="button" class="close btn_disable" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
         <form method="post" action="{{ url('/idAdvanceCheck/covid19') }}" id="covid19_check">
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
               <button type="submit" class="btn btn-info btn_disable submit" >Submit </button>
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
            {{-- <button type="button" class="close btn_disable" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
         <form method="post" action="{{ url('/idAdvanceCheck/covid19ref') }}" id="covid19_ref_check">
         @csrf
            <input type="hidden" name="mob_c" class="mob_c">
            <input type="hidden" name="otp_id" id="otp_id" class="otp_id">
            <input type="hidden" name="ser_id" id="ser_id" class="ser_id">
            <div class="modal-body"> 
               <div class="form-group">
                  <label for="label_name"> Reference ID <span class="text-danger">*</span></label>
                  <input type="text" id="reference_id" name="reference_id" class="form-control reference_id" placeholder="Enter Reference ID"/>
                  <p style="margin-bottom: 2px;" class="text-danger error-container error-reference_id" id="error-reference_id"></p> 
               </div>
            </div>
            <p style="margin-bottom: 2px;" class="text-danger error-container error-all" id="error-all"> </p>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info submit btn_disable">Submit </button>
               <button type="button" class="btn btn-danger btn_disable" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>


</div>
<!--  -->

<script>
$(document).ready(function() {
   $('.service_list').select2();
   // $(".vital_list").select2();

      $(document).on('click','#aadhaar_validation',function() {
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
         if(inputData !="" && finalInputID.length == 12 ){
         //
         $.ajax({
            url:"{{ url('/idCheck/aadhar') }}",
            method:"GET",
            data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'service_id':service_id},      
            success:function(data)
            {
               // console.log(data);
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
                  
                  $('#aadharReportExport').attr('href',"/IDcheck/aadhar/pdf/"+data.data.id);

                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');

                  $(currentBtn).parent('td').prev('td').find('input').val('');
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
      $(document).on('click','#pan',function() {
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
               url:"{{ url('/idCheck/pan') }}",
               method:"GET",
               data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'service_id':service_id},      
               success:function(data)
               {
                  // console.log(data);
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
                     $('.pan_dob').html("<strong>"+data.data.dob+"</strong>");
                     $('.pan_full_name').html("<strong>"+data.data.full_name+"</strong>");
                     $('#panReportExport').attr('href',"/IDcheck/pan/pdf/"+data.data.id);

                     $(currentBtn).attr("check-type",checkType);
                     $(currentBtn).prop("disabled", false);
                     $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');

                     $(currentBtn).parent('td').prev('td').find('input').val('');
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
      $(document).on('click','#voter_id',function() {
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
               url:"{{ url('/idCheck/voterID') }}",
               method:"GET",
               data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'service_id':service_id},      
               success:function(data)
               {
                  // console.log(data);
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
                     $('#voterIDReportExport').attr('href',"/IDcheck/voterID/pdf/"+data.data.id);

                     $(currentBtn).attr("check-type",checkType);
                     $(currentBtn).prop("disabled", false);
                     $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');

                     $(currentBtn).parent('td').prev('td').find('input').val('');
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
      $(document).on('click','#rc',function() {
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
                  url:"{{ url('/idCheck/RC') }}",
                  method:"GET",
                  data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'service_id':service_id},      
                  success:function(data)
                  {
                     // console.log(data);
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
                        
                        $('#RCReportExport').attr('href',"/IDcheck/rc/pdf/"+data.data.id);

                        $(currentBtn).attr("check-type",checkType);
                        $(currentBtn).prop("disabled", false);
                        $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');

                        $(currentBtn).parent('td').prev('td').find('input').val('');
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
      $(document).on('click','#passport',function() {
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

            var inputData = $(currentBtn).parent('td').prev('td').find('input.passportNumber').val();
            var inputDataDOB = $(currentBtn).parent('td').prev('td').find('input.passportDOB').val();
            var service_id=$(this).attr('data-service');
            var finalInputID = inputData.trim();
            if(inputData !="" && finalInputID.length > 8){
            //
               $.ajax({
                  url:"{{ url('/idCheck/passport') }}",
                  method:"GET",
                  data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'dob':inputDataDOB,'service_id':service_id},      
                  success:function(data)
                  {
                     // console.log(data);
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
                        $('#passportReportExport').attr('href',"/IDcheck/passport/pdf/"+data.data.id);

                        $(currentBtn).attr("check-type",checkType);
                        $(currentBtn).prop("disabled", false);
                        $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');

                        $(currentBtn).parent('td').prev('td').find('input').val('');
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
      
      //ID check - Dl
      $(document).on('click','#driving_license',function() {
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
            //var inputData = $(currentBtn).parent('td').prev('td').find('input').val();
            var inputData = $(currentBtn).parent('td').prev('td').find('input.drivingNumber').val();
            var inputDataDOB = $(currentBtn).parent('td').prev('td').find('input.drivingDOB').val();
            var finalInputID = inputData.trim();
            if(inputData !="" && finalInputID.length > 10){
            //
               $.ajax({
                  url:"{{ url('/idCheck/DL') }}",
                  method:"GET",
                  data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'dob':inputDataDOB,'service_id':service_id},      
                  success:function(data)
                  {
                     // console.log(data);
                     if(data.fail && data.error_type=='validation')
                     {
                        for (control in data.errors) {   
                           $('.error-' + control).html(data.errors[control]);
                        }
                     }
                     else if(data.fail)
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
                        $('#DLReportExport').attr('href',"/IDcheck/dl/pdf/"+data.data.id);

                        $(currentBtn).attr("check-type",checkType);
                        $(currentBtn).prop("disabled", false);
                        $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');

                        $(currentBtn).parent('td').prev('td').find('input').val('');
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

         //ID check - GST
      
      $(document).on('click',"#gstin",function() {
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
                  url:"{{ url('/idCheck/gstin') }}",
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


                        $('#GSTReportExport').attr('href',"/IDcheck/gstin/pdf/"+data.data.id);

                        $(currentBtn).attr("check-type",checkType);
                        $(currentBtn).prop("disabled", false);
                        $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');

                        $(currentBtn).parent('td').prev('td').find('input').val('');

                        $(currentBtn).parent('td').prev('td').find('select').prop('selectedIndex',0);
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
      
      $(document).on('click',".advance_check",function(){
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
                  window.setTimeout(function(){
                     $('.btn_d').attr('disabled',false);
                     $('.aadhar_submit').html('Send Otp');
                  },2000);
                  if (data.fail && data.error_type == 'validation') {
                        
                        //$("#overlay").fadeOut(300);
                        for (control in data.errors) {
                           $('.' + control).addClass('border-danger');
                           $('#error-' + control).html(data.errors[control]);
                        }
                  } 
                  if (data.fail && data.error == 'yes') {
                     $('#error-wrong_aadhar').html('Sorry, Unable to get the record, Please try again later');
                  }
                  if (data.fail == false) {
                     $('#serv_id').val(data.service_id);
                     $("#advance_check").modal("hide");
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

      // });


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
                  window.setTimeout(function(){
                     $('.btn_d').attr('disabled',false);
                     $('.aadhar_otp').html('Submit');
                  },2000);
                  if (data.fail && data.error_type == 'validation') {
                        
                        //$("#overlay").fadeOut(300);
                        for (control in data.errors) {
                           $('.' + control).addClass('border-danger');
                           $('.error-' + control).html(data.errors[control]);
                        }
                  } 
                  if (data.fail && data.error == 'yes') {
                     
                     // $('#error-all').html(data.message);
                     $('#error-wrong_otp').html('Sorry, Unable to get the record, Please try again later');
                  }
                  if (data.fail == false) {
                     // $('#send_otp_modal').modal('hide');
                     var client_id=data.client_id;
                     // $path='{{ Config::get('app.admin_url')}}/aadharchecks/show/'+client_id;
                     window.location.href="{{ Config::get('app.admin_url')}}/aadharchecks/show/"+client_id;
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


      //insta finance 

      $(document).on('click',"#instaFinance",function() {
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


      //bank info

      $(document).on('click',"#bank_verification",function() {
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
            url:"{{ route('/idCheck/banking') }}",
            method:"GET",
            data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'ifsc':inputDataIFSC,'service_id':service_id},      
            success:function(data)
            {
               // console.log(data);
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
                  toastr.success("Verification Done");
                  $('.reportBoxBank').removeClass('d-none');
                  $('.reportBoxbank').addClass('d-block');
                  
                  //set the output data
                  $('.bank_ac_number').html("<strong>"+data.data.account_number+"</strong>"); 
                  $('.bank_ac_name').html("<strong>"+data.data.full_name+"</strong>"); 
                  $('.ifsc_code').html("<strong>"+data.data.ifsc_code+"</strong>"); 

                  $('#BankReportExport').attr('href',"/IDcheck/bank/pdf/"+data.data.id);

                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');

                  $(currentBtn).parent('td').prev('td').find('input').val('');
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

      $(document).on('click','#telecom',function(){
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
        
         // alert(finalInputID);
         var service_id=$(this).attr('data-service');
         // alert(service_id);
         if(inputData !="" && finalInputID.length == 10){
         //
         // $('#mob_t').val(finalInputID);
         // $('#send_otp_telecom').modal({
         //    backdrop: 'static',
         //    keyboard: false
         // });
            $.ajax({
               url:"{{ url('/idCheck/telecom') }}",
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

                        $('#telecomReportExport').attr('href',"/IDcheck/telecom/pdf/"+data.id);
                        $(currentBtn).attr("check-type",checkType);
                        $(currentBtn).prop("disabled", false);
                        $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');

                        $(currentBtn).parent('td').prev('td').find('input').val('');
                     }
                     else{
                        $('#telecom_check')[0].reset();
                        $('#mob_t').val(inputData);
                        $('#client_id').val(data.client_id);
                        $('#ser_id').val(service_id);
                        //notify
                        $('.otp').removeClass('border-danger');
                        $('.form-control').removeClass('border-danger');
                        $('.error-container').html('');

                        $('#send_otp_telecom').modal({
                           backdrop: 'static',
                           keyboard: false
                        });
                        $(currentBtn).attr("check-type",checkType);
                        $(currentBtn).prop("disabled", false);
                        $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                        $(currentBtn).parent('td').prev('td').find('input').val('');

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
                  window.setTimeout(function(){
                     $('.btn_d').attr('disabled',false);
                     $('.telecom_otp').html('Submit');
                  },2000);
                  if (data.fail && data.error_type == 'validation') {
                        
                        //$("#overlay").fadeOut(300);
                        for (control in data.errors) {
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
                     $('#telecomReportExport').attr('href',"/IDcheck/telecom/pdf/"+data.id);

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

      // Covid 19 Certifcate
      $(document).on('click','#covid_19_certificate',function(){
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
         $('form-control').removeClass('border-danger');
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
               url:"{{ url('/idCheck/covid19') }}",
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
                     $(currentBtn).parent('td').prev('td').find('input').val('');

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
                        // $('input[name=' + control + ']').addClass('is-invalid');
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
               // console.log(data);
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

      $(document).on('click',"#e_court",function() {
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
            url:"{{ route('/idCheck/ecourt') }}",
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

                  $('#ecourtReportExport').attr('href',"/IDcheck/ecourt/pdf/"+data.data.id);

                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                  $(currentBtn).parent('td').prev('td').find('input').val('');
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

      $(document).on('click',"#upi",function() {
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
            url:"{{ route('/idCheck/upi') }}",
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

                  $('#upiReportExport').attr('href',"/IDcheck/upi/pdf/"+data.data.id);

                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                  $(currentBtn).parent('td').prev('td').find('input').val('');
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

      // cin

      $(document).on('click',"#cin",function() {
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
            url:"{{ route('/idCheck/cin') }}",
            method:"POST",
            data:{"_token": "{{ csrf_token() }}",'id_number':inputData,'service_id':service_id},      
            success:function(data)
            {
               // console.log(data);
               if(data.fail)
               {
                  $(currentBtn).parent('td').prev('td').find('span').html(data.error_msg);
                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }
               if(data.fail == false)
               {
                  //notify
                  toastr.success("Verification Done");
                  $('.reportBoxCIN').removeClass('d-none');
                  $('.reportBoxCIN').addClass('d-block');
                  
                  //set the output data
                  $('.c_id').html("<strong>"+data.data.cin_number+"</strong>");
                  $('.c_name').html("<strong>"+data.data.company_name+"</strong>");
                  $('.c_reg_no').html("<strong>"+data.data.registration_number+"</strong>"); 
                  $('.c_addr').html("<strong>"+data.data.registered_address+"</strong>");
                  $('.c_doi').html("<strong>"+data.data.date_of_incorporation+"</strong>"); 
                  $('.c_email').html("<strong>"+data.data.email_id+"</strong>");
                  $('.c_prs').html("<strong>"+data.data.paid_up_capital_in_rupees+"</strong>");
                  $('.c_ac').html("<strong>"+data.data.authorised_capital+"</strong>"); 
                  $('.c_cat').html("<strong>"+data.data.company_category+"</strong>");
                  $('.c_sub_cat').html("<strong>"+data.data.company_subcategory+"</strong>");
                  $('.c_class').html("<strong>"+data.data.company_class+"</strong>"); 
                  $('.c_efill').html("<strong>"+data.data.company_efilling_status+"</strong>"); 
                  $('.c_doa').html("<strong>"+data.data.date_of_last_AGM+"</strong>"); 
                  $('.c_bs').html("<strong>"+data.data.date_of_balance_sheet+"</strong>"); 

                  $('#cinReportExport').attr('href',"/IDcheck/cin/pdf/"+data.data.id);

                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                  $(currentBtn).parent('td').prev('td').find('input').val('');

                  var dir = data.data.directors;

                  if(dir!=null && dir!='')
                  {
                     const myArr = JSON.parse(dir);

                     if(myArr.length > 0)
                     {
                        var column = '';
                        $('.cin_result').append('<p class="pt-1 pb-border"></p><strong>Directors</strong>');
                           $(myArr).each(function(key,value){
                              column+='<div class="col-6 col-md-4 py-1">Name : <span><strong>'+value.name+'</strong></span><br><span> DIN : <strong>'+value.DIN+'</strong></span></div>';
                           });
                        $('.cin_result').append('<div class="row">'+column+'</div>');
                     }
                  }
                  
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

      // Court Check V1

      $(document).on('click',"#court_check_v1",function() {
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
         var inputDataState        = $(currentBtn).parent('td').prev('td').find('input.state').val();
         var inputDataType        = $(currentBtn).parent('td').prev('td').find('select.type option:selected').val();

         var finalInputID = inputData.trim();
         if(inputData !="" && finalInputID.length >= 3 && inputDataType!=''){
         //
         $.ajax({
            url:"{{ route('/idCheck/court_check_v1') }}",
            method:"POST",
            data:{"_token": "{{ csrf_token() }}",'name':inputData,'fathername':inputDataFather,'address':inputDataAddress,'type':inputDataType,'state':inputDataState,'service_id':service_id},      
            success:function(data)
            {
               // console.log(data);
               if(data.fail)
               {
                  $(currentBtn).parent('td').prev('td').find('span').html(data.error_msg);
                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
               }
               if(data.fail == false)
               {
                  //notify
                  toastr.success("Verification Done");
                  $('.reportBoxCourtV1').removeClass('d-none');
                  $('.reportBoxCourtV1').addClass('d-block');
                  
                  //set the output data
                  $('.ct_v1_name').html("<strong>"+data.data.name+"</strong>");
                  $('.ct_v1_addr').html("<strong>"+data.data.address+"</strong>");
                  $('.ct_v1_father_name').html("<strong>"+data.data.father_name+"</strong>");
                  $('.ct_v1_type').html("<strong>"+data.data.type+"</strong>");
                  $('.ct_v1_state').html("<strong>"+data.data.state+"</strong>");

                  $('#courtv1ReportExport').attr('href',"/IDcheck/court_check_v1/pdf/"+data.data.id);

                  $(currentBtn).attr("check-type",checkType);
                  $(currentBtn).prop("disabled", false);
                  $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                  $(currentBtn).parent('td').prev('td').find('input').val('');
                  
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
      

      $(document).on('change','.service_list',function(event){

         var _this = $(this);

         if(_this.val()!='')
         {
            var id = _this.val();

            $.ajax({
                     type:'POST',
                     url: "{{route('/idChecksFrm')}}",
                     data: {"_token": "{{ csrf_token() }}","id":id},        
                     success: function (response) {        
                     // console.log(response);
                     
                     $('table.service_table tbody').html(response.form);
               },
               error: function (data) {
                     // alert("Error: " + errorThrown);
               }
            });
         }
         else
         {
            swal({
                  title: "Please Select The Check Type First !!",
                  text: '',
                  type: 'warning',
                  buttons: true,
                  dangerMode: true,
                  confirmButtonColor:'#003473'
               });

               $('table.service_table tbody').html(`<tr><td scope="row" colspan="7">
                                       <h3 class="text-center">No record!</h3>
                                    </td></tr>`);
         }
         
         $('.reportBox').addClass('d-none');
         $('.reportBox').removeClass('d-block');
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
                     $("#state_id").html('<option value="">Select State</option>');
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
                     $("#city_id").html('<option value="">Select City</option>');
                     $.each(data,function(key,value){
                     $("#city_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                     }); 
               }

            });
      });

      

       //vital 4 check
      // $(document).on('change','.vital',function(e) {
      //   e.preventDefault();
        
      //   var vital_id = $('.vital option:selected').val();
         
      //   $.ajax({ 
      //       type:"POST",
      //       url: "{{ url('/instatntverification/vitallist') }}",
      //       data: {"_token": "{{ csrf_token() }}",'vital_id':vital_id},      
      //       success: function (response) {
      //           console.log(response.data);
      //             if(response.success==true  ) {
      //                $('.vital_list').html('<option value="all">All</option>');
      //                $.each(response.data, function (i, item) {
      //                      var name_vital = item.vital_name;
      //                      $(".vital_list").append('<option value='+item.vital_name+'> '+name_vital+' </option>');
      //                });

      //                setTimeout(function(){
      //                   $(".vital_list").select2();
      //                },200);
      //             }
      //             //show the form validates error
      //             if(response.success==false ) {                              
      //                for (control in response.errors) {   
      //                      $('#error-' + control).html(response.errors[control]);
      //                }
      //             }
      //          },
      //          error: function (xhr, textStatus, errorThrown) {
      //             // alert("Error: " + errorThrown);
      //          }
      //   });
      //   return false;
      // });


      
      //vital 4 verification
      $(document).on('click',"#vital_4",function(e) {
         //reset error data 
         var name          = $('.name').val();
         var record_check    = $('.record_check option:selected').val()==undefined?'':$(".record_check option:selected").val();
         var country_id    = $('.country option:selected').val()==undefined?'':$(".country option:selected").val();
         var state_name    = $('.state option:selected').text()==undefined?'':$(".state option:selected").text();
         var city_name     = $('.city_id option:selected').text()==undefined?'':$(".city_id option:selected").text();
         // var vital_id   = $('.vital option:selected').val()==undefined?'':$(".vital option:selected").val();
         // var vital_name = $('.vital option:selected').text()==undefined?'':$(".vital option:selected").text();
         var Id_number     = $('.subjectId').val()==undefined?'':$(".subjectId").val();
         var dateOfBirth   = $('.dateOfBirth').val()==undefined?'':$(".dateOfBirth").val();
         var address_line_1 = $('.address_line_1').val()==undefined?'':$(".address_line_1").val();
         var postal_code    = $('.postal_code').val()==undefined?'':$(".postal_code").val();
         var relevancyScore    = $('.relevancyScore').val()==undefined?'':$(".relevancyScore").val();
         
         var vital_name=[];
         var i=0;
         $('.vital_list option:selected').each(function () {
            vital_name[i++] = $(this).val()==undefined?'':$(this).val();
         });
         
         $(".error-data, span.error").html("");
         $('.reportBox').addClass('d-none');
         $('.reportBox').removeClass('d-block');

         var currentBtn = $(this); 
         //disable button
         $(this).prop("disabled", true);
         // add spinner to button
         $(this).html(
            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...`
         );

         var service_id=$(this).attr('data-service');

         $('p.error_container').html("");
         // var subject        = $(currentBtn).parent('td').prev('td').find('input').val();
         // alert(subject);
         // var finalInputID = subject.trim();
         // if(subject !="" && finalInputID.length >= 3){
         //
            $.ajax({
               url:"{{ route('/idCheck/vital') }}",
               method:"GET",
               data:{
                  "_token": "{{ csrf_token() }}",
                  'name'         :name,
                  'record_check' :record_check,
                  'service_id'   :service_id,
                  'country_id'   :country_id,
                  'city_name'    : city_name,
                  'state_name'   : state_name,
                  // 'vital_id'     : vital_id,
                  // 'vital_name'   : vital_name,
                  'Id_number'    : Id_number,
                  'dateOfBirth'  : dateOfBirth,
                  'address_line_1':address_line_1,
                  'postal_code'  : postal_code,
                  'vital_name'   : vital_name,
                  'relevancyScore':relevancyScore,
               },      
               success:function(data){
                  if(data.success == false)
                  {
                     $(currentBtn).prop("disabled", false);
                     $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                     //$('#vitalReportExport').html('Download PDF');
                     
                   }
                  if(data.success==false) {  
                     var i=0;                            
                     for (control in data.errors) {   
                           $('#error-' + control).html(data.errors[control]);
                           if(i==0)
                           {
                              $('select[name='+control+']').focus();
                              $('input[name='+control+']').focus(); 
                              $('textarea[name='+control+']').focus();
                           }
                           i++;  
                     }
                     
                    }
                  if(data.fail == false)
                  {
                     //notify
                     toastr.success("Verification Done");
                     $('.reportBoxVital').removeClass('d-none');
                     $('.reportBoxVital').addClass('d-block');
                     
                     //set the output data
                     // $('.user_name').html("<strong>"+data.data.subject+"</strong>");
                     // $('.id_number').html("<strong>"+data.data.subjectId+"</strong>");
                     // $('.country').html("<strong>"+data.data.country+"</strong>");
                     // $('.state').html("<strong>"+data.data.state+"</strong>");
                     // $('.city').html("<strong>"+data.data.city_district+"</strong>");
                     // $('.date_of_birth').html("<strong>"+data.data.dateOfBirth+"</strong>");
                     // $('.address').html("<strong>"+data.data.address_line_1+"</strong>");
                     // $('.postal_code').html("<strong>"+data.data.postal_code+"</strong>");

                     // $('.vital_report').html(data.form);

                     // $('#vitalReportExport').attr('href',"/IDcheck/vital/pdf/"+data.data.id);
                  
                     //set the output data
                     $('.user_name').html("<strong>"+data.data.subject!=null ? data.data.subject : ''+"</strong>");
                     $('.id_number').html("<strong>"+data.data.subjectId!=null ? data.data.subjectId : ''+"</strong>");
                     $('.country').html("<strong>"+data.data.country!=null && data.data.country!='-select-' && data.data.country!='-Select-' ? data.data.country : ''+"</strong>");
                     $('.state').html("<strong>"+data.data.state!=null && data.data.state!='-select-' && data.data.state!='-Select-' ? data.data.state : ''+"</strong>");
                     $('.city').html("<strong>"+data.data.city_district!=null && data.data.city_district!='-select-' && data.data.city_district!='-Select-' ? data.data.city_district : ''+"</strong>");
                     $('.date_of_birth').html("<strong>"+data.data.dateOfBirth!=null ? data.data.dateOfBirth : ''+"</strong>");
                     $('.address').html("<strong>"+data.data.address_line_1!=null ? data.data.address_line_1 : ''+"</strong>");
                     $('.postal_code').html("<strong>"+data.data.postal_code!=null ? data.data.postal_code : ''+"</strong>");

                     $('.vital_report').html(data.form);

                     $('#vitalReportExport').attr('href',"/IDcheck/vital/pdf/"+data.data.id);
                  
               
                     $(currentBtn).prop("disabled", false);
                     $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
                     //$('#vitalReportExport').html('Download PDF');

                     //$('#vitalReportExport').html('Download PDF');
                     //$(currentBtn).parent('td').prev('td').find('input').val('');
                  }
                  
               },
               error : function(data)
               {
                  // console.log(data);
               }
            });
         
         // }
         // else{
         //       $(currentBtn).parent('td').prev('td').find('error').html("Please fill the required fields!");
         //       setTimeout(function(){ 
         //          $(currentBtn).attr("check-type",checkType);
         //          $(currentBtn).prop("disabled", false);
         //          $(currentBtn).html('<i class="fa fa-hand-point-right"></i> Go');
         //       }, 1000);
         // }

      });
});

$(document).on('click',"#vitalReportExport",function(e) {
   var hrefValue = $('#vitalReportExport').attr('href');
  
   var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
   $('#vitalReportExport').attr('disabled',true);
   if ($('#vitalReportExport').html() !== loadingText) {
         $('#vitalReportExport').html(loadingText);
   }
   $.ajax({
         url: hrefValue,
         method:"GET",
         success:function(data){
            if(data.success == true)
            {
               var url_data = data.url_d;
               console.log(url_data);
               
               //window.open(url_data);
               var arr = url_data.split("/");

               var fileName = arr[arr.length - 1];

               var a = $("<a />");
                     a.attr('id','download_lnk_btn');
                     a.attr("download", fileName);
                     a.attr("href", url_data);    
                     a[0].click();
                     $('#download_lnk_btn').remove();

               $('#vitalReportExport').attr('disabled',false);
               $('#vitalReportExport').html('Download PDF');
            }
            
            
         },
        
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
