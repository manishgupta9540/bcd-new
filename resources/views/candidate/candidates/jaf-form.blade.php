@extends('layouts.candidate')
<style>
  .disabled-link{
    pointer-events: none;
  }
  .remove-image
    {
        padding: 0px 3px 0px !important;
    }

    .image-area img{
        height: 100px !important;
        width: 100px !important;
        padding: 8px !important;
    }

    .image-area{
        width: 90px !important;
    }

    .remove-image:hover
    {
        padding: 0px 3px 0px !important;
    }

    .kbw-signature { width: 100%; height: 250px;}
    #sig canvas{
        width: 100% !important;
        height: auto;
    }

    .data-selfie img {
          max-width: 100%;
          height: 100%;
      }

      .data-selfie {
          border: 1px solid #ccc;
          overflow: hidden;
          padding-top: 0px;
      }

      .data-selfie img {
          /* max-width: 400px; */
          height: 200px;
          width: 100%;
          padding: 10px;
          object-fit: contain;
      }

      .data-selfie .img-data {
          padding: 10px;
          /*position: absolute; */
          /* bottom: 30px; */
          text-align: center;
      }
      div#signature-area canvas#sig-canvas {
          width: 100%;
      }

      #close-sing {
            display: none;
            color: red;
            position: absolute;
            top: 3px;
            font-size: 30px;
            background-color: #fff;
            border-radius: 56%;
            right: 10px;
        }
        @media(max-width:991px){
            #signature-area{display:none;}
            #signature-area .data-selfie{    position: relative;}
            #signature-area.open-sign{
                display:flex;
                position: fixed;
                top: 0px;
                bottom: 0px;
                padding: 0px;
                height: 100vh;
                width: 100%;
                left: 0;
                overflow: hidden;
                right: 0;
                background-color:#00000096;
                z-index: 111;
                justify-content: center;
                align-items: center;
            }
            #signature-area.open-sign #sig canvas {
                width: 100% !important;
                height: 100%;
            }
            body.sign-over{overflow: hidden;}
            #signature-area.open-sign .data-selfie {
                width: 92%;
                background-color: #fff;
            }
            #signature-area.open-sign #close-sing {
                display: block;
            }
            .font-doors {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .font-doors .file input{height:0px;}
            .font-doors .right-upload label{width:auto!important;margin-bottom:0px;}
        }

        .data-selfie {
            border: 1px solid #ccc;
            overflow: hidden;
            padding-top: 0px;
        }

        .submit-btn {
            width: 25%;
            float: right;
        }

        .profile-card {
            margin-bottom: 25px;
        }

        #sig-canvas {
            border: 2px dotted #CCCCCC;
            border-radius: 15px;
            cursor: crosshair;
        }
    
</style>
@section('content')
<div class=" sidenav-open d-flex flex-column" style=" margin-left: 20px; margin-right: 17px;">
<!-- ============ Body content start ============= -->
<div class="main-content" style=" margin-top: 0px; ">
<div class="row">
   <div class="card text-left">
      <div class="card-body">
        <div class="header-part-right text-right">
          <div class="dropdown">
             <div class="user col align-self-end">
                <img src="{{ asset('admin/images/profile-default-avtar.jpg') }}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                   <div class="dropdown-header">
                      <i class="i-Lock-User mr-1"></i> {{ Auth::guard('candidate')->user()->first_name }}
                   </div>
                   <a class="dropdown-item sign_out" >Sign out</a>
                   {{-- <a class="dropdown-item" href="{{ route('candidate.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log out</a> --}}
                   <form id="logout-form" action="{{ route('candidate.logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                   </form>
                </div>
             </div> 
          </div>
       </div>
         <div class="row">
            @if ($message = Session::get('success'))
            <div class="col-md-12">   
                <div class="alert alert-success">
                <strong>{{ $message }}</strong> 
                </div> 
            </div>
            @endif 
            {{-- @if ($message = Session::get('errors'))
            <div class="col-md-12">   
                <div class="alert alert-danger">
                <strong>{{ $message }}</strong> 
                </div> 
            </div>
            @endif  --}}
            <div class="col-md-12 text-center">
               <h3 class=" mb-1 ">JAF - Job Application Form </h3>
               <p>Submit candidate JAF inputs.</p>
            </div>
            
            <div class="col-md-12">
               
               <form class="mt-2" method="post" enctype="multipart/form-data" action="{{ url('candidate/candidates/jafFormSave/') }}" id="report_form">
                @csrf
                <!-- candidate info -->
                <input type="hidden" name="case_id" value="{{ $job_item_id->id }}" >
                <input type="hidden" name="candidate_id" value="{{ $candidate->id }}" >
                <input type="hidden" name="business_id" value="{{ $candidate->business_id }}" >
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="card-title mb-1 mt-2">Profile Info</h4>
                        <p class="pb-border"></p>
                        <div class="row">
                          <div class="col-sm-4">
                              <div class="form-group">
                                <label>First name: <strong>{{ $candidate->first_name }}</strong> </label>
                              </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                            <label>Middle name: <strong>{{ $candidate->middle_name!=NULL ? $candidate->middle_name : 'N/A' }}</strong></label>
                            </div>
                        </div>
                          <div class="col-sm-4">
                              <div class="form-group">
                              <label>Last name: <strong>{{ $candidate->last_name ? $candidate->last_name : 'N/A' }}</strong></label>
                              </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                            <label>Father name: <strong>{{ $candidate->father_name }}</strong></label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                           
                          <label>DOB: <strong>{{ date('d-m-Y',strtotime($candidate->dob)) }}</strong></label>
                          {{-- <input class="form-control dob commonDatepicker" type="text" name="dob" value="{{ date('d-m-Y',strtotime($candidate->dob)) }}" readonly> --}}
                          {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dob"></p> --}}
                          </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                          <label>Gender: <strong>{{ $candidate->gender }}</strong> </label>
                          {{-- <input class="form-control " type="text" name="gender" value="{{ $candidate->gender }}" readonly> --}}
                          </div>
                        </div>
                     
                      </div>

                      <div class="row">
                          <div class="col-sm-4">
                            <div class="form-group">
                            <label>Aadhar Number: <strong>{{ $candidate->aadhar_number!=NULL ? $candidate->aadhar_number : 'N/A' }}</strong> </label>
                            {{-- <input class="form-control " type="text" name="aadhar_number" value="{{ $candidate->aadhar_number }}" readonly> --}}
                            </div>
                        </div>
                          <div class="col-sm-4">
                              <div class="form-group">
                              <label>Email: <strong>{{ $candidate->email!=NULL ? $candidate->email : 'N/A' }}</strong> </label>
                              {{-- <input class="form-control " type="text" name="email" value="{{ $candidate->email }}" readonly> --}}
                              </div>
                          </div>
                          <div class="col-sm-4">
                              <div class="form-group">
                              <label>Phone: <strong>+{{$candidate->phone_code}}-{{ $candidate->phone }}</strong></label>
                              {{-- <input class="form-control number_only" type="text" name="phone" value="{{ $candidate->phone }}" readonly> --}}
                              </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                            <label>Client emp code: <strong>{{ $candidate->client_emp_code!=NULL ? $candidate->client_emp_code : 'N/A'}}</strong>  </label>
                            {{-- <input class="form-control " type="text" name="client_emp_code" value="{{ $candidate->client_emp_code }}"> --}}
                            </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                          <label>Entity code: <strong>{{ $candidate->entity_code!=NULL ? $candidate->entity_code : 'N/A' }}</strong></label>
                          {{-- <input class="form-control " type="text" name="entity_code" value="{{ $candidate->entity_code }}"> --}}
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                {{-- <div class="row">
                     <div class="col-md-12">
                         <hr>
                     </div>
                </div>    --}}
                <!-- service item -->
                
                {{-- @if ($user == 'client') --}}
        
                @if( count($jaf_items) >0  )
                    @php
                      $cogent_criminal_result = Helper::cogentCriminalSLA($candidate->business_id,$candidate->id);
                    @endphp
                    @foreach($jaf_items as $item)
                      <?php
                        $j=1;
                        $num ="";
                      ?>
                    
                      <div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                        <div class="col-md-6">

                            <h3 class=" mb-2 mt-2">Verification - {{$item->service_name.'- '.$item->check_item_number}}</h3>
                            <p>Provide the inputs data</p>

                            <?php
                              $serviceId = $item->service_id;
                          
                              $disclaimers = DB::table('disclaimers')->select('service_id','disclaimer')
                                              ->where('status','1')
                                              ->where(['service_id'=>$serviceId])
                                              ->first();           
                            ?>
                            @if($disclaimers) 
                            <div class="form-group">
                              <label ><span class="text-danger font-weight-bold"> Disclaimer :-  </span> <strong>{{$disclaimers->disclaimer}}</strong></label>
                            </div>
                              
                            @endif

                            <!-- if check type is address  -->
                            @if($item->service_id == '1')
                            <div class="row" >
                              <div class="col-sm-10">
                                  <div class="form-group">
                                  <label>Address Type <span class="text-danger">*</span></label>
                                    <select class="form-control address-type-{{$item->id}}" name="address-type-{{$item->id}}" >
                                      <option value="">- Select Type -</option>
                                      <option value="current" @if($item->address_type !=null) @if($item->address_type=='current') selected @endif @endif>Current</option>
                                      <option value="permanent" @if($item->address_type !=null) @if($item->address_type=='permanent') selected @endif @endif>Permanent</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-address-type-{{$item->id}}"></p>
                                  </div>
                              </div>
                            </div>
                            @endif
                            <!--  -->
                            <?php
                              $i=0; 
                              if($item->service_id==17)
                              {
                                $form_items = Helper::get_check_item_inputs($item->service_id);
                              } 
                              else
                              {
                                $form_items= Helper::get_sla_item_inputs($item->service_id); 
                              }
                              $input_item_data = $item->form_data;
                              $input_item_data_array =  json_decode($input_item_data, true); 
                            ?>
                            @if ($input_item_data_array != null)
                              @foreach($input_item_data_array as $key => $input)
                                  <div class="row">
                                      <div class="col-sm-12">
                                            <div class="form-group">
                                              <?php 
                                                    $key_val = array_keys($input); $input_val = array_values($input); 
            
                                                    $university_board =  $readonly= "";
                                                    $required = "";
                                                    $university_board_id="";
                                                    $date_calss='';
                                                    $show_calender='';
                                                    $input_class='error-control';
                                                    $edu_auto_cls = '';
                                                    $emp_auto_cls = '';

                                                    if($key_val[0] =='University Name / Board Name'){ 
                                                      $university_board_id = "#searchUniversity_board";
                                                      $university_board = "searchUniversity_board";

                                                      $edu_auto_cls = 'edu_auto_cls';
                                                    }
                                                //name
                                                if($key_val[0]=='First Name' || $key_val[0]=='First name' || $key_val[0]=='first name'){ 
                                                    $name = $candidate->first_name;
                                                    $readonly ="readonly";
                                                    $input_class='';
                                                }
                                                if($key_val[0]=='Last Name' || $key_val[0]=='Last name' || $key_val[0]=='last name'){ 
                                                    $name = $candidate->last_name;
                                                    $readonly ="readonly";
                                                    $input_class='';
                                                }
                                                if($key_val[0]=='Date of Birth' || $key_val[0]=='DOB' || $key_val[0]=='dob'){ 
                                                  // $dob = $candidate->dob;
                                                  // if($dob !=NULL){
                                                  //   $name = date('d-m-Y',strtotime($candidate->dob));
                                                  // }
                                                  $date_calss = 'commonDatepicker';
                                                  $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';

                                                }
                                                if(stripos($key_val[0],'Employee Tenure')!==false)
                                                {
                                                  $date_calss = 'commonDatepicker';
                                                  $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                                }

                                                if(stripos($key_val[0],'Date of Joining (Employee Tenure)')!==false)
                                                {
                                                  $date_calss = 'commonDatepicker';
                                                  $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                                  $required = '';
                                                }

                                                if(stripos($key_val[0],'Date of Relieving (Employee Tenure)')!==false)
                                                {
                                                  $date_calss = 'commonDatepicker';
                                                  $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                                  $required = '';
                                                }

                                                if($item->service_id==10 && $key_val[0]=='Company name')
                                                {
                                                  $emp_auto_cls='emp_auto_cls';
                                                }

                                                $check_input=Helper::check_item_input_name($item->service_id,$item->business_id,$key_val[0]);
                                              ?>
                                              <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @elseif($required!='') <span class="text-danger">*</span> @endif</label><br>
                                              <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                              @if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                                  <select class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}">
                                                    <option value="">--Select--</option>
                                                    <option @if(stripos($input_val[0],'personal')!==false) selected @endif value="personal">Personal</option>
                                                    <option @if(stripos($input_val[0],'professional')!==false) selected @endif value="professional">Professional</option>
                                                  </select>
                                              @elseif(stripos($item->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_6')!==false || stripos($item->type_name,'drug_test_7')!==false || stripos($item->type_name,'drug_test_8')!==false || stripos($item->type_name,'drug_test_9')!==false || stripos($item->type_name,'drug_test_10')!==false)
                                                  @if(stripos($key_val[0],'Test Name')!==false)
                                                    <input class="form-control service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{$input_val[0]}}">
                                                    @php
                                                      $drug_test_name = Helper::drugTestName($item->service_id);
                                                    @endphp
                                                    @if(count($drug_test_name)>0)
                                                      @foreach ($drug_test_name as $d_item)
                                                        <div class="form-check form-check-inline disabled-link-1">
                                                            <input class="form-check-input test-name-{{$item->id.'-'.$i}}" type="checkbox" name="test-name-{{$item->id.'-'.$i}}[]" value="{{$d_item->test_name}}" checked readonly>
                                                            <label class="form-check-label" for="inlineCheckbox-1">{{$d_item->test_name}}</label>
                                                        </div>
                                                      @endforeach
                                                    @endif
                                                  @elseif(stripos($key_val[0],'Result')!==false)
                                                    <select class="form-control {{$date_calss.' '.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" name="service-input-value-{{ $item->id.'-'.$i }}" >
                                                        <option value="">--Select--</option>
                                                        <option @if(stripos($input_val[0],'positive')!==false) selected @endif value="positive">Positive</option>
                                                        <option @if(stripos($input_val[0],'negative')!==false) selected  @endif value="negative">Negative</option>
                                                    </select> 
                                                  @else
                                                    <input class="form-control {{$date_calss.' '.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}" {{$readonly}}>
                                                  @endif
                                              @else
                                                <input class="form-control {{ $date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} {{$edu_auto_cls}} {{$emp_auto_cls}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                                {!! $show_calender !!}
                                              @endif
                                              <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>
                                            </div>
                                      </div>
                                  </div>
                                  <?php $i++; ?>
                              @endforeach 
                            @else
                                @foreach($form_items as $input)
                                    @php
                                      $check_input= Helper::check_item_input($input->id,$candidate->business_id);
                                      $required='';
                                    @endphp

                                    @if($item->service_id==17)
                                      @if($input->reference_type==NULL && !(stripos($input->label_name,'Mode of Verification')!==false || stripos($input->label_name,'Remarks')!==false))
                                        <div class="row" >
                                          <div class="col-sm-10">
                                              <div class="form-group">
                                                <label> {{ $input->label_name }} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $input->label_name }}">
                                                <input type="hidden" name="jaf_id" id="jaf_id" value="{{$item->id}}">
                                                <?php 
                                                  $input_type=""; $input_type = Helper::get_sla_item_input_type($input->form_input_type_id);
                                                  $date_calss = '';
                                                  $input_class='error-control' ;
                                                  $show_calender='';
                                                  // if($input_type == 'date'){
                                                  //   $date_calss = 'commonDatepicker';
                                                  // }
                                                  $name =$lname = $father_name= $dob= "";
                                                  $readonly ="";
                                                  $placeholder ="";
                                                  //name
                                                  if($input->label_name=='First Name' || $input->label_name=='First name' || $input->label_name=='first name'){ 
                                                    $name = $candidate->first_name;
                                                    $readonly ="readonly";
                                                    $input_class='';
                                                  }
                                                  if($input->label_name=='Last Name' || $input->label_name=='Last name' || $input->label_name=='last name'){ 
                                                    $name = $candidate->last_name;
                                                    $readonly ="readonly";
                                                    $input_class='';
                                                  }
                                                  //fateher name
                                                  if($input->label_name=='Father Name' || $input->label_name=='father name' || $input->label_name=='Father name'){ 
                                                    $name = $candidate->father_name;
                                                  }
                                                  //dob
                                                  if($input->label_name=='Date of Birth' || $input->label_name=='DOB' || $input->label_name=='dob'){ 
                                                      $dob = $candidate->dob;
                                                      if($dob !=NULL){
                                                        $name = date('d-m-Y',strtotime($candidate->dob));
                                                      }
                                                      $date_calss = 'commonDatepicker';

                                                      $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';

                                                    }
                                                    if($input->label_name=='Employee Tenure'){ 
                                                   
                                                        $date_calss = 'commonDatepicker';
                                                        $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                                    }

                                                    if($input->label_name=='Date of Joining (Employee Tenure)')
                                                    {
                                                      
                                                      $date_calss = 'commonDatepicker';
                                                      $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                                    }

                                                    if($input->label_name=='Date of Relieving (Employee Tenure)')
                                                    {
                                                      $date_calss = 'commonDatepicker';
                                                      $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                                    }


                                                  if($input->label_name=='Period of Stay' || $input->label_name=='Period of stay' || $input->label_name=='period of stay'){
                                                      $placeholder ="ex- No of days ";
                                                    
                                                  }

                                                  $university_board_name = "";
                                                  if($input->label_name=='University Name / Board Name'){ 
                                                    $university_board_name = "searchUniversity_board";
                                                  }
                                                ?>
                                                  @if(stripos($input->label_name,'Reference Type (Personal / Professional)')!==false)
                                                    <select {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" placeholder="{{ $placeholder }}">
                                                        <option value="">--Select--</option>
                                                        <option @if(stripos($name,'personal')!==false) selected @endif value="personal">Personal</option>
                                                        <option @if(stripos($name,'professional')!==false) selected  @endif value="professional">Professional</option>
                                                    </select>
                                                  @else
                                                    <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}" placeholder="{{ $placeholder }}">
                                                  @endif
                                                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>
                                              </div>
                                          </div>
                                        </div>
                                      @endif
                                    @else
                                      <div class="row" >
                                          <div class="col-sm-10">
                                              <div class="form-group">
                                                  <?php 
                                                    $input_type=""; $input_type = Helper::get_sla_item_input_type($input->form_input_type_id);
                                                    $date_calss = '';
                                                    $input_class='error-control' ;
                                                    $show_calender = '';
                                                    $required = '';
                                                    // if($input_type == 'date'){
                                                    //   $date_calss = 'commonDatepicker';
                                                    // }
                                                    $name =$lname = $father_name= $dob= "";
                                                    $readonly ="";
                                                    $edu_auto_cls = '';
                                                    $emp_auto_cls = '';
                                                    
                                                    //name
                                                    if($input->label_name=='First Name' || $input->label_name=='First name' || $input->label_name=='first name'){ 
                                                      $name = $candidate->first_name;
                                                      $readonly ="readonly";
                                                      $input_class='';
                                                    }
                                                    if($input->label_name=='Last Name' || $input->label_name=='Last name' || $input->label_name=='last name'){ 
                                                      $name = $candidate->last_name;
                                                      //$readonly ="readonly";
                                                      $input_class='';
                                                    }
                                                    //father name
                                                    if($input->label_name=='Father Name' || $input->label_name=='father name' || $input->label_name=='Father name'){ 
                                                      $name = $candidate->father_name;
                                                    }
                                                    //dob
                                                    if($input->label_name=='Date of Birth' || $input->label_name=='DOB' || $input->label_name=='dob'){ 
                                                      $dob = $candidate->dob;
                                                      if($dob !=NULL){
                                                        $name = date('d-m-Y',strtotime($candidate->dob));
                                                      }
                                                      $date_calss = 'commonDatepicker';

                                                      $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';

                                                    }
                                                    if($input->label_name=='Employee Tenure'){ 
                                                   
                                                        $date_calss = 'commonDatepicker';
                                                        $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                                    }

                                                    if($input->label_name=='Date of Joining (Employee Tenure)')
                                                    {
                                                      
                                                      $date_calss = 'commonDatepicker';
                                                      $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                                      $required = '';
                                                    }

                                                    if($input->label_name=='Date of Relieving (Employee Tenure)')
                                                    {
                                                      $date_calss = 'commonDatepicker';
                                                      $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                                      $required = '';
                                                    }

                                                    $university_board_name = "";
                                                    if($input->label_name=='University Name / Board Name'){ 
                                                      $university_board_name = "searchUniversity_board";
                                                      $edu_auto_cls = 'edu_auto_cls';
                                                    }

                                                    if($input->service_id==10 && $input->label_name=='Company name'){ 

                                                      $emp_auto_cls = 'emp_auto_cls';
                                                    }

                                                    if($cogent_criminal_result==true && stripos($input->type_name,'criminal')!==false)
                                                    {
                                                        if($input->label_name=='Address' || $input->label_name=='Address ')
                                                        {
                                                            $name = $candidate->address;
                                                        }
                                                        else if($input->label_name=='Address Type' || $input->label_name=='Address Type ')
                                                        {
                                                            $name = $candidate->address_type;
                                                        }
                                                    }


                                                  ?>
                                                  <label> {{ $input->label_name }} @if($check_input!=NULL) <span class="text-danger">*</span> @elseif($required!='') <span class="text-danger">*</span> @endif</label><br>
                                                  <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $input->label_name }}">
                                                  <input type="hidden" name="jaf_id" id="jaf_id" value="{{$item->id}}">
                                                  @if(stripos($input->type_name,'drug_test_5')!==false || stripos($input->type_name,'drug_test_6')!==false || stripos($input->type_name,'drug_test_7')!==false || stripos($input->type_name,'drug_test_8')!==false || stripos($input->type_name,'drug_test_9')!==false || stripos($input->type_name,'drug_test_10')!==false)
                                                    @if(stripos($input->label_name,'Test Name')!==false)
                                                      <input class="form-control service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}">
                                                      @php
                                                        $drug_test_name = Helper::drugTestName($input->service_id);
                                                      @endphp
                                                      @if(count($drug_test_name)>0)
                                                        @foreach ($drug_test_name as $d_item)
                                                          <div class="form-check form-check-inline disabled-link-1">
                                                              <input class="form-check-input test-name-{{$item->id.'-'.$i}}" type="checkbox" name="test-name-{{$item->id.'-'.$i}}[]" value="{{$d_item->test_name}}" checked readonly>
                                                              <label class="form-check-label" for="inlineCheckbox-1">{{$d_item->test_name}}</label>
                                                          </div>
                                                        @endforeach
                                                      @endif
                                                    @elseif(stripos($input->label_name,'Result')!==false)
                                                      <select {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" name="service-input-value-{{ $item->id.'-'.$i }}">
                                                          <option value="">--Select--</option>
                                                          <option @if(stripos($name,'positive')!==false) selected @endif value="positive">Positive</option>
                                                          <option @if(stripos($name,'negative')!==false) selected  @endif value="negative">Negative</option>
                                                      </select> 
                                                    @else
                                                      <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}" >
                                                    @endif
                                                  @else
                                                    <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} {{$edu_auto_cls}} {{$emp_auto_cls}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}">
                                                    {!! $show_calender !!}
                                                  @endif
                                                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>
                                              </div>
                                          </div>
                                      </div>
                                    @endif
                                    <?php $i++; ?>
                                @endforeach
                            @endif
                          <!-- insufficiency -->
                                {{-- <div class="row">
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                          <div class="form-check">
                                            <label class="form-check-label">
                                              <input style="margin-top: 1px;" type="checkbox" class="form-check-input" name="insufficiency-{{ $item->id }}" >Mark as insufficiency
                                            </label>
                                          </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            <label>insufficiency Notes</label>
                                            <input type="text" class="form-control" name="insufficiency-notes-{{ $item->id }}" >
                                        </div>
                                    </div>
                                    <!-- ./ -->
                                </div> --}}
                          <!-- ./insufficiency -->
                        </div>
                        <!-- attachment  -->
                        
                        <div class="col-md-6">
                          
                            <p>Attachments</p>
                            <a class='btn-link clickSelectFile' add-id="{{$item->id}}" data-number='1' data-result='fileResult1' data-type='main' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                            <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file1-{{$item->id}}' multiple="multiple" style='display:none'/>
                            <p class="text-danger error-container" id="error-file-{{$item->id}}"></p>
                            <div class="bcd_loading"></div>
                            <div class='row fileResult' id="fileResult1-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>  
                            <?php $item_files = Helper::getJAFAttachFiles($item->id); //print_r($item_files); ?>
                            @foreach($item_files as $file)
                                @if($file['attachment_type'] == 'main')
                                <div class="image-area">
                                  @if(stripos($file['file_name'],'pdf')!==false)
                                        <img src="{{url('/').'/admin/images/icon_pdf.png'}}" alt="Preview" title="{{$file['file_name']}}">
                                  @else
                                        <img src="{{ $file['fileIcon'] }}" alt="Preview" title="{{$file['file_name']}}">
                                  @endif
                                    <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a>
                                    <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                                </div>
                                @endif
                            @endforeach
                            </div>
                            
                        </div>
                        
                      </div>
                      
                      <!-- row close -->
                    
                    @endforeach
                @endif
                {{-- <div class="col-md-12">
                  
                </div> --}}
                <div class="row pt-3">
                  <div class="col-lg-12 digital_sign_lg">
                    <label class="pt-2" for="">Digital Signature:</label>
                    <br/>
                    <div id="sig" > </div>
                    <br/>
                    <div class="text-right">
                      <button type="button" id="clear" class="btn btn-danger btn-sm mt-2">Clear Signature</button>
                    </div>
                    <textarea id="signature64" name="signed" class="error-control" style="display: none"></textarea>
                  </div>
                  {{-- <div class="col-12 digital_sign_sm">
                    <span>Digital Signature</span>
                    <label class="btn btn-sm btn-light text-dark border-dark error-control" style="inline-block; float:right;">
                        Open <input type="button" id="btn-signature" name="sign-open" class="d-none text-right error-btn" value="Open" style="float: right;">
                    </label>
                  </div> --}}
                </div>
                  {{-- <div class="profile-card" id="signature-area">
                                        
                      <div class="data-selfie">
                          
                          <p class="label">Signature <span class="text-danger">*</span></p>

                          <div class="doc-upld">
                              <div class="row p-0 m-0">

                                  <div class="col-md-12 right-upload">
                                      <h5>
                                        Digital Signature 
                                      </h5>
                                      <canvas id="sig-canvas" width="400px" height="160" class="error-control">
                                          Get a better browser, bro.
                                      </canvas>
                                  </div>
                                 
                                  
                                  <div class="col-md-12">
                                      <textarea id="sig-dataUrl" name="signature" class="form-control" rows="5" style="display: none;"></textarea>
                                  </div>
                                  <div class="col-md-12  mt-2 mb-2 d-none">
                                      <img id="sig-image" class="d-none border" src="" alt="Your signature will go here!"/>
                                  </div>
                                  <div class="col-md-6">
                                      <button type="button" class="btn btn-info mt-2 mb-4 error-btn d-none" id="sig-submitBtn" style="background-color: #003473;">Submit Signature</button>
                                  </div>
                                  <div class="col-md-6">
                                      <button type="button" class="btn btn-danger mt-2 mb-4 error-btn" id="sig-clearBtn">Clear Signature</button>
                                  </div>
                                <div class="col-12">
                                  <p style="margin-bottom: 2px;" class="text-danger error-container error-signature" id="error-signature"></p>
                                </div>
                              </div>
                              
                          </div>


                      </div>
                  </div> --}}
                  <div class="text-center">
                    <button type="button" class="btn btn-link declare">Declaration & Authorization</button>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary mt-3 jaf_submit">Save</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal" id="declare_modal">
  <div class="modal-dialog modal-lg" style="max-width: 80% !important;">
     <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
           <h4 class="modal-title">Declaration & Authorization</h4>
           <button type="button" class="close" style="top: 12px;!important; color: red;" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
           <div class="modal-body">
              <div class="form-group">
                  <p class="text-justify"> I certify that the statements made in this application are valid and complete to the best of my knowledge. I
                    understand that false or misleading information may result in termination of employment.</p>
                  <p class="text-justify">If upon investigations, any of this information is found to be incomplete or inaccurate, I understand that I will
                    be subject to dismissal at any time during my employment.</p>
                  <p class="text-justify">I hereby authorize Premier Shield and/or any of its subsidiaries or affiliates and any persons or organizations
                    acting on its behalf {{Helper::company_name($candidate->parent_id)}}, to verify the information presented on this application form and to
                    procure an investigative report or consumer report for that purpose.:</p>
                  <p class="text-justify">I hereby grant authority for the bearer of this letter to access or be provided with full details of my previous
                    records. In addition, please provide any other pertinent information requested by the individual presenting this
                    authority.
                  </p>
                  <p class="text-justify">I hereby release from liability all persons or entities requesting or supplying such information.</p>
              </div>
           </div>
           <!-- Modal footer -->
           <div class="modal-footer">
              <button type="button" class="btn btn-danger back" data-dismiss="modal">Close</button>
           </div>
     </div>
  </div>
</div>
<div class="modal" id="sign_modal">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
              <h4 class="modal-title" id="name" style="color: #000;">Digital Signature</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
              <div class="row">
                  <div class="col-12">
                      <div class="form-group">
                        {{-- <label class="pt-2" for="">Digital Signature:</label>
                        <br/> --}}
                        <div id="sig_mdl" > </div>
                        <br/>
                        <div class="text-right">
                          <button type="button" id="clearsign" class="btn btn-danger btn-sm mt-2">Clear Signature</button>
                        </div>
                        <textarea id="signature64mdl" name="signed" class="error-control" style="display: none"></textarea>
                      </div>
                  </div>
              </div>
          </div>
          <!-- Modal footer -->
          <div class="modal-footer">
              {{-- <button type="button" class="btn btn-info" data-dismiss="modal">Save </button> --}}
              <button type="button" id="clear" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div> 
{{-- @stack('scripts') --}}
<style>
  .kbw-signature { width: 100%; height: 250px;}
  #sig canvas{
      width: 100% !important;
      height: auto;
  }
  #sig_mdl canvas{
      width: 100% !important;
      /* height: auto; */
  }
</style>
{{-- <script src="{{asset('js/digital-signature.js?ver=1.1')}}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
<script type="text/javascript">

    
  $(document).on('click','.sign_out',function(event){
      event.preventDefault();
      $.ajax({
        type: 'Get',
        url: "{{ url('/signout') }}",
        data:{"_token" : "{{ csrf_token() }}"}, 
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            console.log(response);
            // return false;
            if(response.success==true  ) {
              
              document.getElementById('logout-form').submit();
            }
        },
    });
  });
//
$(document).ready(function() {

      // var s_width = screen.width;
      // var s_height = screen.height;

      // if(s_width<991)
      // {
      //   $('.digital_sign_lg').addClass('d-none');
      //   $('.digital_sign_sm').removeClass('d-none');
      // }
      // else
      // {
      //     $('.digital_sign_lg').removeClass('d-none');
      //     $('.digital_sign_sm').addClass('d-none');
      // }

      var curNum ='';
      var fileResult='fileResult1';
      var type = 'main';
      var number = '1';
      $(document).on('click','.clickSelectFile',function(){ 
         curNum     = $(this).attr('add-id');
         fileResult = $(this).attr('data-result');
         type = $(this).attr('data-type');
         number = $(this).attr('data-number');
        //  alert(fileResult);
         $(this).next('input[type="file"]').trigger('click');
      });
      //
      $(document).on('change','.fileupload',function(e){        
        uploadFile(curNum,fileResult,type,number);
      });

      //remove file
      $(document).on('click','.remove-image',function(){ 

          // var r = confirm("Are you want to remove?");
          // if (r == true) {
          //   $('#fileupload-'+curNum).val("");
          //   var current = $(this);
          //   var file_id = $(this).attr('data-id');
          //   //
          //   var fd = new FormData();

          // fd.append('file_id',file_id);
          // fd.append('_token', '{{csrf_token()}}');
          // //
          // $.ajax({
          //     type: 'POST',
          //     url: "{{ url('candidate/jaf/remove/file') }}",
          //     data: fd,
          //     processData: false,
          //     contentType: false,
          //     success: function(data) {
          //         console.log(data);
          //         if (data.fail == false) {
          //         //reset data
          //         $('.fileupload').val("");
          //         //append result
          //         $(current).parent('.image-area').detach();
          //         } else {
                  
          //         console.log("file error!");
                  
          //         }
          //     },
          //     error: function(error) {
          //         console.log(error);
          //         // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
          //     }
          // });

          //   return false;

          // }

          var current = $(this);
          var file_id = $(this).attr('data-id');
          swal({
                // icon: "warning",
                type: "warning",
                title: "Are You Want to Remove?",
                text: "",
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "YES",
                cancelButtonText: "CANCEL",
                closeOnConfirm: false,
                closeOnCancel: false
                },
                function(e){
                  if(e==true)
                  {
                      var fd = new FormData();

                      fd.append('file_id',file_id);
                      fd.append('_token', '{{csrf_token()}}');

                      $.ajax({
                            type: 'POST',
                            url: "{{ url('candidate/jaf/remove/file') }}",
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
                              } else {
                              
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
                }
          );

      });

        $(document).on('submit', 'form#report_form', function (event) {
                        
            $("#overlay").fadeIn(300);　
            event.preventDefault();
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var btn = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            $('.error-container').html('');
            $('.form-control').removeClass('border-danger');
            $('.jaf_submit').attr('disabled',true);
            $('.error-control').attr('readonly',true);
            $('.error-control').addClass('disabled-link');
            if ($('.jaf_submit').html() !== loadingText) {
              $('.jaf_submit').html(loadingText);
            }
            $.ajax({
                  type: form.attr('method'),
                  url: url,
                  data: data,
                  cache: false,
                  contentType: false,
                  processData: false,
                  success: function (data) {
                      console.log(data);
                      window.setTimeout(function(){
                        $('.jaf_submit').attr('disabled',false);
                        $('.jaf_submit').html('Save'); 
                        $('.error-control').attr('readonly',false);
                        $('.error-control').removeClass('disabled-link');
                      },2000);
                      $('.error-container').html('');
    
                      if (data.fail && data.error_type == 'validation') {
                        //$("#overlay").fadeOut(300);
                        for (control in data.errors) {
                        // $('textarea[comments=' + control + ']').addClass('is-invalid');
                        $('.'+control).addClass('border-danger');
                        $('#error-' + control).html(data.errors[control]);
                        $('input[name='+control+']').focus();
                        $('select[name='+control+']').focus();
                        $('textarea[name='+control+']').focus();
                        }
                      } 
                      //  if (data.fail && data.error == 'yes') {
                        
                      //      $('#error-all').html(data.message);
                      //  }
                      if (data.fail == false) {
                        // $('#send_otp').modal('hide');
                        // alert(data.id);
                        if(data.success){
                            toastr.success("JAF Filled Successfully");
                            // redirect to google after 5 seconds
                            var candidate_id=data.candidate_id;
                            window.setTimeout(function() {
                              window.location="{{url('/candidate/')}}"+'/thank-you';    
                            }, 2000);
                        // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                        //  location.reload();
                        }
                        else
                        {
                            toastr.error("Something Went Wrong!!");
                        } 
                      }
                  },
                  error: function (data) {
                      
                      // alert("Error: " + errorThrown);
                      console.log(data)
    
                  }
            });
            return false;
                                                 
        });

        $(document).on('click','.declare',function(){
            $('#declare_modal').modal({
                  backdrop: 'static',
                  keyboard: false
            });
        });

        // $(window).resize(function(){
        //     var s_width = screen.width;
        //     var s_height = screen.height;

        //     if(s_width<991)
        //     {
        //       $('.digital_sign_lg').addClass('d-none');
        //       $('.digital_sign_sm').removeClass('d-none');
        //     }
        //     else
        //     {
        //         $('.digital_sign_lg').removeClass('d-none');
        //         $('.digital_sign_sm').addClass('d-none');
        //     }

        //     // $('input[name=s_width]').val(s_width);
        //     // $('input[name=s_height]').val(s_height);
        // });

        // $(document).on('click','#btn-signature',function(){
        //     // $("body").addClass("sign-over");
        //     // $("#signature-area").addClass("open-sign");

        //     $('#sign_modal').modal({
        //         backdrop: 'static',
        //         keyboard: false
        //     });
        // });

    });


  function uploadFile(dynamicID,fileResult,type,number){

      $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 
      $('.bcd_loading').css('display', 'block');
      var fd = new FormData();

      var jaf_id=$('#jaf_id').val();

      // alert(fd);
      var ins = document.getElementById("file"+number+"-"+dynamicID).files.length;
      // alert(ins);
      for (var x = 0; x < ins; x++) {
          fd.append("files[]", document.getElementById("file"+number+"-"+dynamicID).files[x]);
      }

      fd.append('candidate_id',"{{ base64_encode($candidate->id) }}");
      fd.append('business_id',"{{ $candidate->business_id }}")
      fd.append('jaf_id',dynamicID);
      fd.append('type',type);
      fd.append('_token', '{{csrf_token()}}');
      //
      $.ajax({
            type: 'POST',
            url: "{{ url('candidate/jaf/upload/file') }}",
            data: fd,
            processData: false,
            contentType: false,
            success: function(data) {
              window.setTimeout(function(){
                    $('.bcd_loading').css('display', 'none');
                      },2000);
              console.log(data);
              if (data.fail == false) {
              //reset data
              $('.fileupload').val("");
              $("#fileUploadProcess").html("");
              //append result

              var count = Object.keys(data.data).length;

              for(var i=0; i < count; i++)
              {
                if(data.data[i]['file_type']=='pdf')
                {
                    $.each(data.data[i]['file_id'],function(key,value){
                          // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+value+"'><img src='"+data.data[i]['filePrev'][key]+"'  alt='Preview' title='"+data.data[i]['file_name'][key]+"'><a class='remove-image' href='javascript:;' data-id='"+value+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+value+"'></div>");
                          $("#"+fileResult+"-"+dynamicID).append("<div class='image-area' data-id='"+value+"'><img src='"+data.data[i]['filePrev'][key]+"'  alt='Preview' title='"+data.data[i]['file_name'][key]+"'><a class='remove-image' href='javascript:;' data-id='"+value+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+value+"'></div>");
                    });
                }
                else
                {
                    // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                    $("#"+fileResult+"-"+dynamicID).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                }
              }
              
              // $.each(data.data, function(key, value) {
              //     $("#"+fileResult+"-"+dynamicID).append("<div class='image-area'><img src='"+value.filePrev+"'  alt='Preview'><a class='remove-image' data-id='"+value.file_id+"' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+value.file_id+"'></div>");
              // });
                  
              } else {
                $("#fileUploadProcess").html("");
                //alert("Please upload valid file! allowed file type, Image JPG, PNG, PDF etc. ");
                swal({
                  title: "Oh no!",
                  text: 'Please upload valid file! allowed file type, Image JPG, PNG, PDF etc.',
                  type: 'error',
                  buttons: true,
                  dangerMode: true,
                  confirmButtonColor:'#003473'
               });
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
<script>  
    function autoSave()  
    {  
      var form = $(this);
      // var data = new FormData($(this));
      // alert(data);
      var formData = document.getElementById('report_form');
      var data = new FormData(formData);
      data.append('type','formtype');
      // var url = form.attr("action");
          // if(post_title != '' && post_description != '')  
          // {  
                $.ajax({  
                    url:"{{ url('/candidate/candidates/jafFormSave') }}",  
                    type:"POST",  
                    data:data,  
                    cache: false,
                    contentType: false,
                    processData: false,  
                    success:function(response)  
                    {  
                      if(response.success==true  && response.status=='yes') 
                      {  
                              // $('#post_id').val(data);
                              // toastr.success("Data save successfully"); 
                              //  setInterval(function(){  
                              //   toastr.success("Data save successfully");   
                              //  }, 10000);

                              // var candidate_id = response.candidate_id;
                              var filled = response.filled_by;
                              // alert(filled);
                              toastr.success("JAF has already been filled by " + filled);
                              window.setTimeout(function(){
                                window.location="{{url('/candidate/')}}"+'/thank-you';
                              },2000);   
                      }  
                      if(response.success==true  && response.status=='first') 
                      {  
                        // $('#post_id').val(data);
                        // toastr.success("Data save successfully"); 
                        //  setInterval(function(){  
                        //   toastr.success("Data save successfully");   
                        //  }, 10000);

                        // var candidate_id = response.candidate_id;
                        // var filled = response.filled_by;
                        // alert(filled);
                        toastr.success("JAF has been filled successfully");
                        window.setTimeout(function(){
                          window.location="{{url('/candidate/')}}"+'/thank-you';
                        },2000);
                      }
                    }  
                });  
          // }            
    }  
    setInterval(function(){   
        autoSave();   
        }, 10000);    
  </script>
 <script type="text/javascript">
   var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
   //var sig_mdl = $('#sig_mdl').signature({syncField: '#signature64mdl', syncFormat: 'PNG'});
   $('#clear').click(function(e) {
      e.preventDefault();
      sig.signature('clear');
      // sig_mdl.signature('clear');
      $("#signature64").val('');
      //$("#signature64mdl").val('');
  });

  
  //  $('#clearsign').click(function(e) {
  //     e.preventDefault();
  //     sig_mdl.signature('clear');
  //     sig.signature('clear');
  //     $("#signature64mdl").val('');
  //     $("#signature64").val('');
  // });

  var edu_path = "{{ url('/candidate/candidates/fake_gen_edu_list/autocomplete') }}";
    $('.edu_auto_cls').typeahead({
          source:  function (search, process) {
          return $.get(edu_path, { search: search }, function (data) {
          // alert(data);
                return process(data);
              });
          }
    });

  var emp_path = "{{ url('/candidate/candidates/fake_gen_emp_list/autocomplete') }}";
  $('.emp_auto_cls').typeahead({
        source:  function (search, process) {
        return $.get(emp_path, { search: search }, function (data) {
        // alert(data);
              return process(data);
            });
        }
  });
</script>


@endsection
