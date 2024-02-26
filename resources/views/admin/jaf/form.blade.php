@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
            <div class="main-content">				
 
                <div class="row">
				<div class="card text-left">
               <div class="card-body">
			   
			   <div class="row">
			    <div class="col-md-8">
	              <h4 class="card-title mb-3">Employee Varification Form </h4> 
			  		
				</div>
				
			   <div class="col-md-12">			   
				
                   <!-- <form> -->
                    <table class="table image_uploader">
                        <tbody>
                            <tr>
                                <td>
                                <p>Please fill in the details with utmost attention, as these shall be verified by the Company and/ or by its authorized representatives</p>
                                <p><strong>All details are compulsory.</strong></p>
                                </td>
                                <td>
                                    <div class="box">
                                    <input type="file" name="fileToUpload" id="fileToUpload"></div>
                                    <input type="submit" value="Upload Image" name="submit">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!--  <form class="mt-2" method="post" action="{{ route('/jaf/store') }}">

                        @csrf -->

                    <table class="table table-bordered table-jaf">
                        <thead>
                            <tr>
                                <th colspan="4">Personal Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>Name of Applicant</label>
                                      
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Surname</label>
                                        <input type="text" class="form-input" name="last_name" id="last_name" value="{{old('last_name')}}">
                                    </div>
                                 <!--   @if ($errors->has('last_name'))
                                  <div class="error text-danger">
                                     {{ $errors->first('last_name') }}
                                  </div>
                                  @endif -->
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Middle</label>
                                        <input type="text" class="form-input" name="middle_name" id="middle_name" value="{{old('middle_name')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>First</label>
                                        <input type="text" class="form-input" name="first_name" id="first_name" value="{{old('first_name')}}">
                                    </div>
                                   <!--  @if ($errors->has('first_name'))
                                  <div class="error text-danger">
                                     {{ $errors->first('first_name') }}
                                  </div>
                                  @endif -->
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Date of Birth (dd/mm/yy):</label>
                                        <input type="date" class="form-input" name="dob" id="dob" value="{{old('dob')}}">
                                    </div>
                                   <!--  @if ($errors->has('dob'))
                                  <div class="error text-danger">
                                     {{ $errors->first('dob') }}
                                  </div>
                                  @endif -->
                                </td>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Place of Birth :</label>
                                        <input type="text" class="form-input" name="birth_place" id="birth_place" value="{{old('birth_place')}}">
                                    </div>
                                    <!-- @if ($errors->has('birth_place'))
                                  <div class="error text-danger">
                                     {{ $errors->first('birth_place') }}
                                  </div>
                                  @endif -->
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Sex</label>
                                        <input type="text" class="form-input" name="sex" id="sex" value="{{old('sex')}}">
                                    </div>
                                    <!--  @if ($errors->has('sex'))
                                  <div class="error text-danger">
                                     {{ $errors->first('sex') }}
                                  </div>
                                  @endif -->
                                </td>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Nationality</label>
                                        <input type="text" class="form-input" name="nationality" id="nationality" value="{{old('nationality')}}">
                                    </div>
                                    <!-- @if ($errors->has('nationality'))
                                  <div class="error text-danger">
                                     {{ $errors->first('nationality') }}
                                  </div>
                                  @endif -->
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Father's Name</label>
                                        <input type="text" class="form-input" name="father_name" id="father_name" value="{{old('father_name')}}">
                                    </div>
                                  <!--   @if ($errors->has('father_name'))
                                  <div class="error text-danger">
                                     {{ $errors->first('father_name') }}
                                  </div>
                                  @endif -->
                                </td>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Passport name</label>
                                        <input type="text" class="form-input" name="passport_no" id="passport_no" value="{{old('passport_no')}}">
                                    </div>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>Home phone</label>
                                        <input type="text" class="form-input" name="home_phone" id="home_phone" value="{{old('home_phone')}}">
                                    </div>
                                     <!-- @if ($errors->has('home_phone'))
                                  <div class="error text-danger">
                                     {{ $errors->first('home_phone') }}
                                  </div>
                                  @endif -->

                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Office phone</label>
                                        <input type="text" class="form-input" name="office_phone" id="office_phone" value="{{old('office_phone')}}">
                                    </div>
                                     <!-- @if ($errors->has('office_phone'))
                                  <div class="error text-danger">
                                     {{ $errors->first('office_phone') }}
                                  </div>
                                  @endif -->
                                </td>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Mobile</label>
                                        <input type="text" class="form-input" name="phone" id="phone" value="{{old('phone')}}">
                                    </div>
                                    <!--  @if ($errors->has('phone'))
                                  <div class="error text-danger">
                                     {{ $errors->first('phone') }}
                                  </div>
                                  @endif -->
                                </td>
                                <!--  <td colspan="2">
                                    <div class="form-group">
                                        <input type="submit" class="form-input" name="save" value="Submit">
                                    </div>
                                </td> -->
                            </tr>
                        </tbody>
                    </table>
               <!--  </form>
 -->                
                    <!--  <form class="mt-2" method="post" action="{{ route('/jaf/store') }}">

                        @csrf -->

                    <table class="table table-bordered table-jaf">
                        <thead>
                            <tr>
                                <th colspan="4">Residential addresses</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">
                                    <div class="form-group">
                                        <label>Permanent address :</label>
                                        <input type="text" class="form-input" name="r_full_address" id="r_full_address" value="{{old('r_full_address')}}">
                                    </div>
                                     <!-- @if ($errors->has('r_full_address'))
                                  <div class="error text-danger">
                                     {{ $errors->first('r_full_address') }}
                                  </div>
                                  @endif -->
                                </td>
                                <input type="hidden" class="form-input" name="r_address_type" id="r_address_type" value="residential_address">
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>City :</label>
                                        <input type="text" class="form-input" name="r_city_name" id="r_city_name" value="{{old('r_city_name')}}">
                                    </div>
                                    <!--  @if ($errors->has('r_city_name'))
                                  <div class="error text-danger">
                                     {{ $errors->first('r_city_name') }}
                                  </div>
                                  @endif -->
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>State :</label>
                                        <input type="text" class="form-input" name="r_state_name" id="r_state_name" value="{{old('r_state_name')}}">
                                    </div>
                                    <!--  @if ($errors->has('r_state_name'))
                                  <div class="error text-danger">
                                     {{ $errors->first('r_state_name') }}
                                  </div>
                                  @endif -->
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Pin code :</label>
                                        <input type="text" class="form-input" name="r_zipcode" id="r_zipcode" value="{{old('r_zipcode')}}">
                                    </div>
                                    <!--  @if ($errors->has('r_zipcode'))
                                  <div class="error text-danger">
                                     {{ $errors->first('r_zipcode') }}
                                  </div>
                                  @endif -->
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>phone no :</label>
                                        <input type="text" class="form-input" name="r_phone" id="r_phone" value="{{old('r_phone')}}">
                                    </div>
                                    <!-- @if ($errors->has('r_phone'))
                                  <div class="error text-danger">
                                     {{ $errors->first('r_phone') }}
                                  </div>
                                  @endif -->
                                </td>
                            </tr>
                            <tr>
                            <td colspan="2">
                                    <h5>Duration of stay</h5>
                                    <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>From​ (mm/yy) :</label>
                                        <input type="text" class="form-input" name="r_address_from" id="r_address_from" value="{{old('r_address_from')}}">
                                    </div>
                                 <!--    @if ($errors->has('r_address_from'))
                                  <div class="error text-danger">
                                     {{ $errors->first('r_address_from') }}
                                  </div>
                                  @endif -->

                                    <div class="form-group col-md-6">
                                        <label>To​ (mm/yy) :</label>
                                        <input type="text" class="form-input" name="r_address_to" id="r_address_to" value="{{old('r_address_to')}}">
                                    </div>
                                  <!--   @if ($errors->has('r_address_to'))
                                  <div class="error text-danger">
                                     {{ $errors->first('r_address_to') }}
                                  </div>
                                  @endif -->
                                    </div>

                                </td>
                                <td colspan="2">
                                    <h5>Nature of Location :</h5>
                                    <label class="radio-inline">
                                        <input type="radio" name="r_nature_location"> Rented
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="r_nature_location"> Own
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="r_nature_location"> Other (specify)
                                    </label>

                                    <!--  @if ($errors->has('r_nature_location'))
                                  <div class="error text-danger">
                                     {{ $errors->first('r_nature_location') }}
                                  </div>
                                  @endif -->
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered table-jaf">
                        <thead>
                            <tr>
                                <th colspan="4">Current addresses</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">
                                    <div class="form-group">
                                        <label>Permanent address :</label>
                                        <input type="text" class="form-input" name="c_full_address" id="c_full_address" value="{{old('c_full_address')}}">
                                    </div>
                                   <!--   @if ($errors->has('c_full_address'))
                                  <div class="error text-danger">
                                     {{ $errors->first('c_full_address') }}
                                  </div>
                                  @endif -->
                                </td>

                                <input type="hidden" class="form-input" name="c_address_type" id="c_address_type" value="current_address">
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>City :</label>
                                        <input type="text" class="form-input" name="c_city_name" id="c_city_name" value="{{old('c_city_name')}}">
                                    </div>
                                     <!-- @if ($errors->has('c_city_name'))
                                  <div class="error text-danger">
                                     {{ $errors->first('c_city_name') }}
                                  </div>
                                  @endif -->
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>State :</label>
                                        <input type="text" class="form-input" name="c_state_name" id="c_state_name" value="{{old('c_state_name')}}">
                                    </div>
                                    <!-- @if ($errors->has('c_state_name'))
                                  <div class="error text-danger">
                                     {{ $errors->first('c_state_name') }}
                                  </div>
                                  @endif -->
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Pin code :</label>
                                        <input type="text" class="form-input" name="c_zipcode" id="c_zipcode" value="{{old('c_zipcode')}}">
                                    </div>
                                   <!--  @if ($errors->has('c_zipcode'))
                                  <div class="error text-danger">
                                     {{ $errors->first('c_zipcode') }}
                                  </div>
                                  @endif -->
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>phone no :</label>
                                        <input type="text" class="form-input" name="c_phone" id="c_phone" value="{{old('c_phone')}}">
                                    </div>
                                  <!--   @if ($errors->has('c_phone'))
                                  <div class="error text-danger">
                                     {{ $errors->first('c_phone') }}
                                  </div>
                                  @endif -->
                                </td>
                            </tr>
                            <tr>
                            <td colspan="2">
                                    <h5>Duration of stay</h5>
                                    <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>From​ (mm/yy) :</label>
                                        <input type="text" class="form-input" name="c_address_from" id="c_address_from" value="{{old('c_address_from')}}">
                                    </div>
                                   <!--  @if ($errors->has('c_address_from'))
                                  <div class="error text-danger">
                                     {{ $errors->first('c_address_from') }}
                                  </div>
                                  @endif -->
                                    <div class="form-group col-md-6">
                                        <label>To​ (mm/yy) :</label>
                                        <input type="text" class="form-input" name="c_address_to" id="c_address_to" value="{{old('c_address_to')}}">
                                    </div>
                                  <!--   @if ($errors->has('c_address_to'))
                                  <div class="error text-danger">
                                     {{ $errors->first('c_address_to') }}
                                  </div>
                                  @endif -->
                                    </div>
                                </td>
                                <td colspan="2">
                                    <h5>Nature of Location :</h5>
                                    <label class="radio-inline">
                                        <input type="radio" name="c_nature_location"> Rented
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="c_nature_location"> Own
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="c_nature_location"> Other (specify)
                                    </label>
                                  <!--   @if ($errors->has('c_nature_location'))
                                  <div class="error text-danger">
                                     {{ $errors->first('c_nature_location') }}
                                  </div>
                                  @endif -->
                                </td>
                            </tr>
                              <!-- <td colspan="2">
                                    <div class="form-group">
                                        <input type="submit" class="form-input" name="save" value="Submit">
                                    </div>
                                </td> -->
                        </tbody>
                    </table>
                  <!--   <form> -->
                     <form class="mt-2" method="post" action="{{ route('/jaf/store') }}">

                        @csrf

                    <table class="table table-bordered table-jaf">
                        <thead>
                            <tr>
                                <th colspan="8">Education Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td rowspan="2"><label>QUALIFICATION</label></td>
                                <td rowspan="2"><label>NAME & ADDRESS OF SCHOOL / COLLEGE/ INSTITUTE</label></td>
                                <td rowspan="2"><label>NAME & ADDRESS OF BOARD / UNIVERSITY TO WHICH THE SCHOOL / COLLEGE / INSTITUTE IS AFFILIATED TO</label></td>
                                <td rowspan="2"><label>COURSE ATTENDED (MORNING/EVENING / CORRESPONDENCE</label></td>
                                <td rowspan="2"><label>MARKS (%) CGPA & CLASS</label></td>
                                <td colspan="2"><label>DATES ATTENDED</label></td>
                                <td rowspan="2"><label>ROLL NUMBER/ REGISTRATION NUMBER/EXAM SEAT NUMBER</label></td>
                            </tr>
                            <tr>
                                <td><label>YEAR OF ENROLMENT (​MM​/​YY​)</label></td>
                                <td><label>YEAR PASSED (​MM​/​YY​)</label></td>
                            </tr>
                            <tr>
                                <td><label>10th</label></td>
                                <input type="hidden" class="form-input" name="qualification_type[]" id="qualification_type" value="10th">
                                <td><input type="text" class="form-input" name="college_name[]">  
                                @if ($errors->has('college_name[]'))
                                <div class="error text-danger">
                                     {{ $errors->first('college_name[]') }}
                                </div>
                                @endif
                                </td>
                                <td><input type="text" class="form-input" name="affilated_university[]" > 
                                @if ($errors->has('affilated_university[]'))
                                <div class="error text-danger">
                                {{ $errors->first('affilated_university[]') }}
                                </div>
                                @endif
                               </td>
                                <td><input type="text" class="form-input" name="course_attended[]" >
                                @if ($errors->has('course_attended[]'))
                                <div class="error text-danger">
                                {{ $errors->first('course_attended[]') }}
                                </div>
                                @endif
                                </td>
                                <td><input type="text" class="form-input" name="percentage[]" >
                                 @if ($errors->has('percentage[]'))
                                <div class="error text-danger">
                                {{ $errors->first('percentage[]') }}
                                </div>
                                @endif
                                </td>
                                 <td><input type="text" class="form-input" name="year_of_enrolment[]">
                                 @if ($errors->has('year_of_enrolment[]'))
                                <div class="error text-danger">
                                {{ $errors->first('year_of_enrolment[]') }}
                                </div>
                                @endif
                                </td>
                                <td><input type="text" class="form-input" name="year_of_passing[]">
                                 @if ($errors->has('year_of_passing[]'))
                                <div class="error text-danger">
                                {{ $errors->first('year_of_passing[]') }}
                                </div>
                                @endif
                                </td>
                                <td><input type="text" class="form-input" name="roll_no[]">
                                 @if ($errors->has('roll_no[]'))
                                <div class="error text-danger">
                                {{ $errors->first('roll_no[]') }}
                                </div>
                                @endif
                                </td>
                                
                            </tr>
                            <tr>
                                <td><label>12th</label></td>
                                <input type="hidden" class="form-input" name="qualification_type[]" id="qualification_type" value="12th">
                                <td><input type="text" class="form-input" name="college_name[]" >
                                 @if ($errors->has('college_name[]'))
                                <div class="error text-danger">
                                     {{ $errors->first('college_name[]') }}
                                </div>
                                @endif
                                </td>
                                <td><input type="text" class="form-input" name="affilated_university[]" >
                                 @if ($errors->has('affilated_university[]'))
                                <div class="error text-danger">
                                     {{ $errors->first('affilated_university[]') }}
                                </div>
                                @endif
                                </td>
                                <td><input type="text" class="form-input" name="course_attended[]" > @if ($errors->has('course_attended[]'))
                                <div class="error text-danger">
                                     {{ $errors->first('course_attended[]') }}
                                </div>
                                @endif
                                </td>
                                <td><input type="text" class="form-input" name="percentage[]" >
                                 @if ($errors->has('percentage[]'))
                                <div class="error text-danger">
                                     {{ $errors->first('percentage[]') }}
                                </div>
                                @endif
                                </td>
                                <td><input type="text" class="form-input" name="year_of_enrolment[]">
                                 @if ($errors->has('year_of_enrolment[]'))
                                <div class="error text-danger">
                                {{ $errors->first('year_of_enrolment[]') }}
                                </div>
                                @endif
                                </td>
                                <td><input type="text" class="form-input" name="year_of_passing[]">
                                 @if ($errors->has('year_of_passing[]'))
                                <div class="error text-danger">
                                {{ $errors->first('year_of_passing[]') }}
                                </div>
                                @endif
                                </td>
                                <td><input type="text" class="form-input" name="roll_no[]">
                                 @if ($errors->has('roll_no[]'))
                                <div class="error text-danger">
                                {{ $errors->first('roll_no[]') }}
                                </div>
                                @endif
                                </td>
                                
                            </tr>

                            <tr>
                                <td colspan="8"><h5><strong>Graduation</strong></h5></td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>Degree</h5>
                                    <label>Discipline</label>
                                    <div class="">
                                    <label> <input type="radio" name="descipline[]"> Full time</label>
                                    </div>
                                    <div class="">
                                        <label><input type="radio" name="descipline[]"> part time</label>
                                    </div>
                                    <div class="">
                                        <label><input type="radio" name="descipline[]"> Distance learning course</label>
                                    </div>
                                </td>
                                 <input type="hidden" class="form-input" name="qualification_type[]" id="qualification_type" value="graduation">
                               <td><input type="text" class="form-input" name="college_name[]" ></td>
                                <td><input type="text" class="form-input" name="affilated_university[]" ></td>
                                <td><input type="text" class="form-input" name="course_attended[]" ></td>
                                <td><input type="text" class="form-input" name="percentage[]" ></td>
                                <td><input type="text" class="form-input" name="year_of_enrolment[]"></td>
                                <td><input type="text" class="form-input" name="year_of_passing[]"></td>
                                <td><input type="text" class="form-input" name="roll_no[]"></td>
                             </tr>
                            <tr>
                                <td colspan="8"><h5><strong>Post Graduation</strong></h5></td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>Degree</h5>
                                    <label>Discipline</label>
                                    <div class="">
                                    <label> <input type="radio" name=""> Full time</label>
                                    </div>
                                    <div class="">
                                        <label><input type="radio" name=""> part time</label>
                                    </div>
                                    <div class="">
                                        <label><input type="radio" name=""> Distance learning course</label>
                                    </div>
                                </td>
                                 <input type="hidden" class="form-input" name="qualification_type[]" id="qualification_type" value="post_graduation">
                                <td><input type="text" class="form-input" name="college_name[]" ></td>
                                <td><input type="text" class="form-input" name="affilated_university[]" ></td>
                                <td><input type="text" class="form-input" name="course_attended[]" ></td>
                                <td><input type="text" class="form-input" name="percentage[]" ></td>
                               <td><input type="text" class="form-input" name="year_of_enrolment[]"></td>
                                <td><input type="text" class="form-input" name="year_of_passing[]"></td>
                                <td><input type="text" class="form-input" name="roll_no[]"></td>
                            </tr>
                            <tr>
                                <td><h5><strong>Any Other Diploma 1</strong></h5></td>
                                 <input type="hidden" class="form-input" name="qualification_type[]" id="qualification_type" value="diploma1">
                                <td><input type="text" class="form-input" name="college_name[]" ></td>
                                <td><input type="text" class="form-input" name="affilated_university[]" ></td>
                                <td><input type="text" class="form-input" name="course_attended[]" ></td>
                                <td><input type="text" class="form-input" name="percentage[]" ></td>
                                <td><input type="text" class="form-input" name="year_of_enrolment[]"></td>
                                <td><input type="text" class="form-input" name="year_of_passing[]"></td>
                                <td><input type="text" class="form-input" name="roll_no[]"></td>
                            </tr>
                            <tr>
                                <td><h5><strong>Any Other Diploma 2</strong></h5></td>
                               <input type="hidden" class="form-input" name="qualification_type[]" id="qualification_type" value="diploma2">
                               <td><input type="text" class="form-input" name="college_name[]" ></td>
                                <td><input type="text" class="form-input" name="affilated_university[]" ></td>
                                <td><input type="text" class="form-input" name="course_attended[]" ></td>
                                <td><input type="text" class="form-input" name="percentage[]" ></td>
                                <td><input type="text" class="form-input" name="year_of_enrolment[]"></td>
                                <td><input type="text" class="form-input" name="year_of_passing[]"></td>
                                <td><input type="text" class="form-input" name="roll_no[]"></td>
                            </tr>
                             <td colspan="2">
                                    <div class="form-group">
                                        <input type="submit" class="form-input" name="save" value="Submit">
                                    </div>
                                </td>
                        </tbody>
                    </table>
                </form>

                    <table class="table table-bordered table-jaf">
                        <thead>
                            <tr>
                                <th colspan="4">Employement Record</th>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <ul>
                                        <li>Starting with your present or most recent employer, please list last 2 employments.</li>
                                        <li>When listing consulting or temporary assignments under “Employer”, state the name of the consulting or temporary agency that placed you at the client site.</li>
                                        <li>Complete and accurate dates (month/year) must be provided.</li>
                                    </ul>
                                </td>
                            </tr>
                            
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>EMPLOYER 1 (Current) :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Employee ID :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>From (mm/yy) :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>To (mm/yy) :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Street Address :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Employer’s Phone No :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Remuneration/Salary :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>City :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>State :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Country :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Postal Code :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Job title :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Reason for leaving :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                
                            </tr>
                            <tr>
                                <td colspan="2" rowspan="8">
                                    <p><strong>Employment Status:</strong> (Please check the relevant box)</p>
                                    <label><input type="radio" name="optradio2"> Full Time</label><br>
                                    <label><input type="radio" name="optradio2"> Contract /Through Outsourcing Agency</label><br>
                                    <p><strong>Outsourcing Agency Details:</strong></p>
                                    <div class="form-group">
                                        <label>Name :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                    <div class="form-group">
                                        <label>Address :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                    <div class="form-group">
                                        <label>Tel No :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>
                                <tr>
                                    <td colspan="2" class="small-headings">Supervisors-Details</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Name :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Title :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Phone No :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Email Id :</label>
                                            <input type="email" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="small-headings">HR ​Manager’s Details:</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Name :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" rowspan="2">
                                        <div class="form-group">
                                            <label>Description of Duties :</label>
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Phone No :</label>
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Email Id : <i>(Preferably official)</i></label>
                                            <input type="email" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Current Employment Authority Provided, If No When</label>
                                            <input type="email" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <label class="radio-inline">
                                            <input type="radio" name="nature1"> Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="nature1"> No
                                        </label>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                            
                        </tbody>
                        </table>



                        <!--  -->

                        <table class="table table-bordered table-jaf">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>EMPLOYER 2 (Previous) :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Employee ID :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>From (mm/yy) :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>To (mm/yy) :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Street Address :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Employer’s Phone No :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Remuneration/Salary :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>City :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>State :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Country :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Postal Code :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Job title :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Reason for leaving :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                
                            </tr>
                            <tr>
                                <td colspan="2" rowspan="8">
                                    <p><strong>Employment Status:</strong> (Please check the relevant box)</p>
                                    <label><input type="radio" name="optradio2"> Full Time</label><br>
                                    <label><input type="radio" name="optradio2"> Contract /Through Outsourcing Agency</label><br>
                                    <p><strong>Outsourcing Agency Details:</strong></p>
                                    <div class="form-group">
                                        <label>Name :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                    <div class="form-group">
                                        <label>Address :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                    <div class="form-group">
                                        <label>Tel No :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>
                                <tr>
                                    <td colspan="2" class="small-headings">Supervisors-Details</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Name :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Title :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Phone No :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Email Id :</label>
                                            <input type="email" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="small-headings">HR ​Manager’s Details:</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Name :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" rowspan="2">
                                        <div class="form-group">
                                            <label>Description of Duties :</label>
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Phone No :</label>
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Email Id : <i>(Preferably official)</i></label>
                                            <input type="email" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                
                            
                        </tbody>
                        </table>

                        <!--  -->

                        <table class="table table-bordered table-jaf">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>EMPLOYER 3 (Previous) :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Employee ID :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>From (mm/yy) :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>To (mm/yy) :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Street Address :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Employer’s Phone No :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Remuneration/Salary :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>City :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>State :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Country :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Postal Code :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Job title :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Reason for leaving :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                
                            </tr>
                            <tr>
                                <td colspan="2" rowspan="8">
                                    <p><strong>Employment Status:</strong> (Please check the relevant box)</p>
                                    <label><input type="radio" name="optradio2"> Full Time</label><br>
                                    <label><input type="radio" name="optradio2"> Contract /Through Outsourcing Agency</label><br>
                                    <p><strong>Outsourcing Agency Details:</strong></p>
                                    <div class="form-group">
                                        <label>Name :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                    <div class="form-group">
                                        <label>Address :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                    <div class="form-group">
                                        <label>Tel No :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>
                                <tr>
                                    <td colspan="2" class="small-headings">Supervisors-Details</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Name :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Title :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Phone No :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Email Id :</label>
                                            <input type="email" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="small-headings">HR ​Manager’s Details:</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Name :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" rowspan="2">
                                        <div class="form-group">
                                            <label>Description of Duties :</label>
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Phone No :</label>
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Email Id : <i>(Preferably official)</i></label>
                                            <input type="email" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                        </tbody>
                        </table>

                        <table class="table table-bordered table-jaf">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>EMPLOYER 4 (Previous) :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Employee ID :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>From (mm/yy) :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>To (mm/yy) :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Street Address :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Employer’s Phone No :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Remuneration/Salary :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>City :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>State :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Country :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>Postal Code :</label>
                                        <input type="date" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Job title :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>Reason for leaving :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                                
                            </tr>
                            <tr>
                                <td colspan="2" rowspan="8">
                                    <p><strong>Employment Status:</strong> (Please check the relevant box)</p>
                                    <label><input type="radio" name="optradio2"> Full Time</label><br>
                                    <label><input type="radio" name="optradio2"> Contract /Through Outsourcing Agency</label><br>
                                    <p><strong>Outsourcing Agency Details:</strong></p>
                                    <div class="form-group">
                                        <label>Name :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                    <div class="form-group">
                                        <label>Address :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                    <div class="form-group">
                                        <label>Tel No :</label>
                                        <input type="text" class="form-input" name="" id="">
                                    </div>
                                </td>
                            </tr>
                                <tr>
                                    <td colspan="2" class="small-headings">Supervisors-Details</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Name :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Title :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Phone No :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Email Id :</label>
                                            <input type="email" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="small-headings">HR ​Manager’s Details:</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Name :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" rowspan="2">
                                        <div class="form-group">
                                            <label>Description of Duties :</label>
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Phone No :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label>Email Id : <i>(Preferably official)</i></label>
                                            <input type="email" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                        </tbody>
                        </table>


                        <table class="table table-bordered table-jaf">
                            <thead>
                                <tr>
                                    <th colspan="5">Personal References</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><label>Name</label></td>
                                    <td><label>Contact No.</label></td>
                                    <td><label>Company</label></td>
                                    <td><label>Designation</label></td>
                                    <td><label>Relationship with the referee</label></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-input" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                        <table class="table table-bordered table-jaf">
                            <thead>export
                                <tr>
                                    <th colspan="2">Declaration & Authorization</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <ul>
                                            <li>I certify that the statements made in this application are valid and complete to the best of my knowledge. I
                                            understand that false or misleading information may result in termination of employment.</li>
                                            <li>If upon investigations, any of this information is found to be incomplete or inaccurate, I understand that I will be
                                            subject to dismissal at any time during my employment.</li>
                                            <li>I hereby authorize ​Premier Shield and/or any of its subsidiaries or affiliates and any persons or organizations
                                            acting on its behalf (<input type="text" class="form-input-small col-md-2" name="" id="">), to verify the information presented on this application form and to
                                            procure an investigative report or consumer report for that purpose.</li>
                                            <li>I hereby grant authority for the bearer of this letter to access or be provided with full details of my previous
                                            records. In addition, please provide any other pertinent information requested by the individual presenting this
                                            authority.</li>
                                            <li>I hereby release from liability all persons or entities requesting or supplying such information.</li>
                                            <li>I authorize ​Premier Shield​ to contact my present employer. <label class="radio-inline">
                                            <input type="radio" name="nature1"> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="nature1"> No</label></li>
                                            <li>I have read, understand, and by my signature consent to these statements.</li>
                                        </ul>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label>Signature :</br>N​AME​ ​(I​N​ B​LOCK​ L​ETTERS​):</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label>Date :</label>
                                            <input type="text" class="form-input-small" name="" id="">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered table-jaf">
                            <thead>
                                <tr>
                                    <th>Document Required (Compulsory)</th>
                                    <th>Attached Yes or No</th>
                                    
                                </tr>
                                
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Completed & Signed Application Form</td>
                                    <td><input type="text" class="form-input-small" name="" id=""></td>
                                </tr>
                                <tr>
                                    <td>Copy of Relevant Education Certificates</td>
                                    <td><input type="text" class="form-input-small" name="" id=""></td>
                                </tr>
                                <tr>
                                    <td>One Passport Size Photograph</td>
                                    <td><input type="text" class="form-input-small" name="" id=""></td>
                                </tr>
                                <tr>
                                    <td><label>Current Address Proof</label>
                                        <ul>
                                            <li>If staying at current address for > 6 months ​else Longest Stay Address Proof.</li>
                                            <li>Note: Your name should be mentioned on the address proof.</li>
                                            <li>Accepted address proofs: MTNL Bill / Electricity Bill/ Copy of Rent Agreement/ Passport/ Voter ID/ Driving License</li>
                                        </ul> 
                                        
                                        </td>
                                    <td><input type="text" class="form-input-small" name="" id=""></td>
                                </tr>

                                <tr>
                                    <td>
                                        <label>Copy of current & past Employment :</label>
                                        <ul>
                                            <li>Appointment Letters.</li>
                                            <li>Relieving Letters.</li>
                                            <li>Salary Slips with employee code.</li>
                                        </ul> 
                                        </td>
                                    <td><input type="text" class="form-input-small" name="" id=""></td>
                                </tr>
                                
                            </tbody>
                        </table>
                   <!--  </form> -->
				</div>			
			</div>
        </div>
    </div>		
</div>
@endsection