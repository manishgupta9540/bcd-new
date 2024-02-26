@extends('layouts.client')
@section('content')
    <div class="main-content-wrap sidenav-open d-flex flex-column">
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">
                     <div class="card text-left">
                        <div class="card-body">
                            
                            <div class="row reportBox reportBoxAadhaar ">
                                <div class="col-md-10 offset-1">
                                        <p style="font-size: 16px;">Report -  <a id="advanceAadharReportExport" target="_blank" href={{ url('my/IDcheck/advanceaadhar/pdf/'.$aadhar->id) }}>Download PDF</a></p>
                                    <div class="table-responsive">
                            
                                        <div class="col-md-10">
                                        {{-- {!! Helper::company_logo(Auth::user()->business_id) !!} --}}
                                        </div>
                                        <h3 class="text-center"> <b>ID Verification</b></h3>
                                        <?php   $image = $aadhar->profile_image; //your base64 encoded data

                                        // $image = $request->image;  // your base64 encoded
                                        $image = str_replace('data:image/png;base64,', '', $image);
                                        $image = str_replace(' ', '+', $image);
                                        $imageName = 'test'.'.'.'png';
                                        \File::put(public_path().'/uploads/profile_images/' . $imageName, base64_decode($image)); ?>
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
                                                <tr> <td>Profile Image</td> <td><img src="{{ asset('/uploads/profile_images/'.'test.png') }}" style="height:60px; width:100px"/></td></tr>
                                                <tr> <td width="50%">Aadhar number</td> <td width="50%" class="aadhar_number">{{$aadhar->aadhar_number}}</td> </tr>
                                                <tr> <td>Aadhar Validity</td> <td class='aadhar_validity'>Valid</td> </tr>
                                                <tr> <td>Verification Check</td> <td class='aadhar_check'>Completed</td> </tr>
                                                <tr> <td>Result</td> 
                                                <td>
                                                    @if ($aadhar->gender == 'M')
                                                        <?php $gender = 'Male'; ?>
                                                    @endif
                                                    @if($aadhar->gender == 'F')
                                                    <?php $gender = 'Female'; ?>
                                                
                                                    @endif
                                                    @if($aadhar->gender == '')
                                                    <?php $gender = '--'; ?>
                                                
                                                    @endif
                                                    Aadhar Verification Completed <br>
                                                 

                                                   
                                                    
                                               
                                                    Aadhar number exist <span class="aadhar_number"></span> <br>
                                                    Name:<span class="aadhar_name">{{$aadhar->full_name}}</span><br>
                                                     <span class="care_of">{{$aadhar->care_of}}</span><br>
                                                    Dob : <span class="aadhar_age_bond">{{date("d-m-Y", strtotime($aadhar->dob))}}</span> <br>
                                                    Gender: <span class="aadhar_gender">{{$gender}}</span> <br>
                                                    Address: <span class="aadhar_address">{{$aadhar->address}}</span><br>
                                                    Pin Code: <span class="aadhar_pin">{{$aadhar->zip}}</span><br>
                                                    
                                                </td> 
                                            </tr>
                                            </tbody>
                                        </table>
                                            <!-- d detail -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        



@endsection