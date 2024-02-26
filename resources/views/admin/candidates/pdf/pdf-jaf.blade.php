<!DOCTYPE html>
<html>
  <head>
    <title>Job Application Form </title>
  <style>
    
  body{
  font-family: Arial, Helvetica Neue, Helvetica, sans-serif; 
  color:#333;
  }
  table{width: 100%;}
  table.main{border:1px solid #226277; padding: 0px; font-size: 14px; width: 100%;}
  table tr td{padding: 5px; }
  table table.appropriate-answer tr td{text-align: center;}
  </style>
 
  </head>
  <body>

  <div class="cover" >
    
  <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:16px; background-color: #c4e3ee; color:#226277; border: 1px solid #226277;">Job Application Form</h3>
  <br>

  <table width="100%" cellpadding="0" cellspacing="1" class="" >

      <tr>
        <td colspan="" width="80%" >
          {{-- <p style="color: #333">Please fill in the details with utmost attention, as these shall be verified by the Company and/ or by its authorized representatives. </p>
          <p> <b>All details are compulsory.</b> </p> --}}
        </td>
        <td colspan="" width="20%" style="text-align:center; height:100px; width:100px;border: 1px solid #226277;">
          <div style="font-size:12px; text-align:center">
            <p style=" padding:5px; text-align:center; font-size:12px; vertical-align:middle;"> Please Affix Your Passport Size Photograph </p>
        </div>
        </td>
      </tr>
      
  </table>

    <table width="100%" cellpadding="2" cellspacing="0" class="main" style="margin-top:10px;">
    <!--  -->
      <tr>
        <td colspan="2" style=" padding: 5px; border-bottom: 1px solid #226277;background-color: #c4e3ee;">
          <h3 style="text-align: left; font-weight:400;  font-size:16px;  color:#226277;  margin:0px;">Personal Details</h3>
        </td>
      </tr>
      <tr>
        <td style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; ">First Name : {{$candidate->first_name}}</td>  <td style="border-bottom: 1px solid #226277; ">Last Name : {{$candidate->last_name}}</td>
      </tr>
      <tr>
        <td style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;">Father Name : {{$candidate->father_name}}</td> <td style="border-bottom: 1px solid #226277; ">Gender : {{$candidate->gender}}</td>
      </tr>
      <tr>
         <td style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; ">DOB : {{$candidate->dob==NULL?'N/A':date('d/m/Y',strtotime($candidate->dob))}}</td> <td style="border-bottom: 1px solid #226277;">Mobile : {{$candidate->phone}}</td>
      </tr>
      <tr>
        <td  colspan="2" style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; ">Email: {{$candidate->email}}</td>  
      </tr>
    </table>
    <br><br>
    @if( count($jaf_items) >0)
        @foreach($jaf_items as $item)
        
        <table width="100%" cellpadding="0" cellspacing="0" class="main" >
          <tr>
            <td colspan="2" style="padding: 5px; background-color: #c4e3ee; border-bottom: 1px solid #226277;">
              <h3 style="text-align: left; font-weight:400;   font-size:16px; color:#226277;  margin:0px;">  @if($item->verification_type=='auto' || $item->verification_type=='Auto'){{ $item->service_name }} @else {{ $item->service_name }} - {{$item->check_item_number}}@endif</h3>
            </td>
          </tr>
          <!-- get form elements -->
          <?php 
                $input_item_data = $item->form_data;
                $input_item_data_array =  json_decode($input_item_data, true); 
          ?>
              
          @if( count($input_item_data_array) > 0 )
            @foreach($input_item_data_array  as $key => $input)   
              <?php $key_val = array_keys($input); $input_val = array_values($input); 

              // $university_board =  $readonly= "";
              // $university_board_id="";
              // if($key_val[0] =='University Name / Board Name'){ 
              //   $university_board_id = "#searchUniversity_board";
              //   $university_board = "searchUniversity_board";
              // }
          //name
            ?>
            <tr>
              @if(stripos($item->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_6')!==false || stripos($item->type_name,'drug_test_7')!==false || stripos($item->type_name,'drug_test_8')!==false || stripos($item->type_name,'drug_test_9')!==false || stripos($item->type_name,'drug_test_10')!==false)
                @if(stripos($key_val[0],'Test Name')!==false)
                  @php
                      $drug_test_name = Helper::drugTestName($item->service_id);
                      $test_name = '';
                      //$d_count=count($drug_test_name);
                  @endphp
                  @if(count($drug_test_name)>0)
                    {{-- @foreach ($drug_test_name as $key => $d_item)
                      <?php
                        if($d_count != $key+1)
                            $test_name .= $d_item->test_name.', ';
                        else
                            $test_name .= $d_item->test_name;
                      ?>
                    @endforeach --}}
                    @php
                      $arr = $drug_test_name->pluck('test_name')->all();
                      $test_name = implode(', ',$arr);
                    @endphp
                  @endif
                  <td colspan="2" style="border-bottom: 1px solid #226277; ">{{ $key_val[0]}} : {{ $test_name }}</td> 
                @else
                  <td colspan="2" style="border-bottom: 1px solid #226277; ">{{ $key_val[0]}} : {{ $input_val[0] }}</td> 
                @endif
              @else
                <td colspan="2" style="border-bottom: 1px solid #226277; ">{{ $key_val[0]}} : {{ $input_val[0] }}</td> 
              @endif 
            </tr>
          
            @endforeach
          @endif
          <!-- end form elements -->
        
        </table>
        <br><br>
        @endforeach
    @endif
    <pagebreak/>
    <table cellpadding="0" width="100%" cellspacing="0" class="main" >
      <!--  -->
      <tr>
        <td colspan="2" style="padding: 5px; border-bottom: 1px solid #226277; background-color: #c4e3ee;">
          <h3 style="text-align: left; font-weight:400;   font-size:16px;  color:#226277;  margin:0px;">Declaration & Authorization</h3>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; "> I certify that the statements made in this application are valid and complete to the best of my knowledge. I understand that false or misleading information may result in termination of employment.</td> 
      </tr>
      <tr>
        <td colspan="2" style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; ">If upon investigations, any of this information is found to be incomplete or inaccurate, I understand that I will be subject to dismissal at any time during my employment.</td> 
      </tr>
      <tr>
        <td colspan="2" style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; ">I hereby authorize ​Premier Shield and/or any of its subsidiaries or affiliates and any persons or organizations acting on its behalf (​--------------------------.​), to verify the information presented on this application form and to procure an investigative report or consumer report for that purpose.:</td>  
      </tr>
      <tr>
        <td colspan="2" style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; ">I hereby grant authority for the bearer of this letter to access or be provided with full details of my previous records. In addition, please provide any other pertinent information requested by the individual presenting this authority.</td> 
      </tr>
      <tr>
        <td colspan="2" style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; "> I hereby release from liability all persons or entities requesting or supplying such information. </td>  
      </tr>
      <tr>
        <td colspan="2" style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; "> I authorize ​Premier Shield​ to contact my present employer. ​☐ Yes ☐ No</td>  
      </tr>
      <tr>
        <td colspan="2" style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; ">I have read, understand, and by my signature consent to these statements.</td>  
      </tr>
      <tr>
        <td style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; ">
            <div class="row">
            <div class="col-lg-9 col-9">Signature: </div> 
            @if($candidate->digital_signature!=NULL || $candidate->digital_signature!='') 
            <div class="col-lg-3 col-3">
              <img id="preview_ds"  class=""  src="{{url('uploads/signatures/')}}/{{$candidate->digital_signature}}" width="100" height="100"/>
            </div> 
            @endif
            </div> 
            <br>
            Name <span style="font-size: 12px;">(In Block Letters)</span>: {{strtoupper($candidate->name)}}
        </td>  
        <td style="border-bottom: 1px solid #226277; ">Date: <span>{{date('d-F-Y')}}</span></td>
      </tr>
      <!--  -->
    </table>
    </div>
    @php 
    $pdfaddress = Helper::get_footer_address($candidate->business_id);
    $defaultaddress = Helper::get_default_address($candidate->parent_id);
    @endphp
    <footer>
      <p style="font-size:13px;">
        <br><b>{{ Helper::company_name($candidate->business_id) }}</b><br>
        @if($pdfaddress!=NULL){{$pdfaddress}}@else{{ $defaultaddress->address_line1 }} {{ $defaultaddress->city_name }}-{{ $defaultaddress->zipcode }}@endif
        </p>
      <table cellpadding="0" cellspacing="2" width="100%" ><tr><td align="left" style=" font-size:14px;">Powered By: <img src="{{ Helper::company_logo_path($candidate->business_id) }}" width="110" style="vertical-align:bottom"> </td><td align="right">{PAGENO} of {nb}</td> </tr></table>
    </footer>
  </body>
  
</html>