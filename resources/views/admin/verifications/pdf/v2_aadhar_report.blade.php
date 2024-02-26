<!DOCTYPE html>
<html>
  <head>
    <title>ID Verification  </title>
  <style>
  body{
  font-family: Arial, Helvetica Neue, Helvetica, sans-serif; 
  color:#333;
  margin:0px;
  padding:0px;
  }
  table{width: 100%;}
  table.main{ padding: 0px; font-size: 16px; width: 100%;}
  table tr td{padding: 5px; vertical-align: middle;}
  table table.appropriate-answer tr td{width:; text-align: center;}
  footer {
                position: fixed; 
                bottom: -10px; 
                left: -50px; 
                right: -50px;
                height: 80px;
                padding: 0px 30px;
                /** Extra personal styles **/
                /*background-color: #03a9f4;*/
                color: #000000;
                text-align: center;
                line-height: 20px;
            }
  </style>
  </head>
  <body>
  <div class="cover" >

  <table cellpadding="0" cellspacing="1" class="" >
     <tr>
        <td colspan="" width="30%" style="text-align:left">
          {!! Helper::company_logo(Auth::user()->business_id) !!}
        </td>
        <td colspan="" width="30%" style="text-align:right;">
          <img style='width:190px;' src="{{ asset('admin/images/pcil-logo.png') }}" alt="">
        </td>
      </tr>
  </table>

    <table width="100%" cellpadding="0" cellspacing="0" class="main" >
    <!--  -->
    <tr>
        <td colspan="4" style=" ">
          <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:18px; color:#333; "> &nbsp; </h3>
        </td>
      </tr>
      <tr>
        <td colspan="4" style=" padding: 5px; text-align: center;">
          <h3 style=" font-weight:400;font-size:18px; color:#333; "><b>ID Verification</b> </h3>
        </td>
      </tr>
      <tr>
        <td style="padding:7px;border: 1px solid #dee2e6; ">Initiated Date</td>  <td style="padding:7px;border: 1px solid #dee2e6; ">Completed Date </td>
        <td style="padding:7px;border: 1px solid #dee2e6; ">Insufficiency Raise Date  </td><td style="padding:7px; border: 1px solid #dee2e6; ">Insufficiency Cleared Date </td>
      </tr>
      <tr>
        <td style="padding:7px;border: 1px solid #dee2e6; ">{{ date('d-M-Y') }}</td>
        <td style="padding:7px;border: 1px solid #dee2e6; ">{{ date('d-M-Y') }}</td>
        <td style="padding:7px;border: 1px solid #dee2e6; ">N/A</td>
        <td style="padding:7px;border: 1px solid #dee2e6; ">N/A</td>
      </tr>
  
      <tr>
        <td colspan="4" ><br></td>
      </tr>
      <!--  -->
    </table>

    <table width="100%" cellpadding="0" cellspacing="0" class="main" >
    <!--  -->
      <tr>
        <td colspan="2" style="text-align: center;  ">
          <h3 style="font-weight:400;  padding: 5px; font-size:18px; color:#333; "><b>Aadhar Number Verification </b></h3>
        </td>
      </tr>
      <tr> <td style="border: 1px solid #dee2e6; padding:7px;">Profile Image</td> <td class='aadhar_image' style="padding:7px;border: 1px solid #dee2e6; "><img src="{{ asset('/uploads/profile_images/'.'test.png') }}" style="height:60px; width:100px"/></td></tr>
      
         <tr> <td style="border: 1px solid #dee2e6; padding:7px;">Aadhar Validity</td> 
         <td class='aadhar_validity' style="padding:7px;border: 1px solid #dee2e6; ">Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> 
        </tr>
         <tr> <td style="border: 1px solid #dee2e6;padding:7px; ">Verification Check</td> 
         <td class='aadhar_check' style="border: 1px solid #dee2e6; padding:7px;">Completed</td> 
         </tr>
         <tr> <td style="border: 1px solid #dee2e6; padding:7px;"> Result</td> 
         <td style="border: 1px solid #dee2e6;padding:7px; ">
               Aadhar Verification Completed <br>
         </td> 
      </tr>
      </tr>
         <tr> <td style="border: 1px solid #dee2e6; padding:7px;"> Aadhar number exists! </td> 
         <td class='aadhar_check' style="border: 1px solid #dee2e6; vertical-align:middle;padding:7px;">  <span style="margin-top:-2px;"> <strong > {{$data->aadhar_number}} </strong> </span> </td> 
      </tr>
    </tr>
    <tr> <td style="border: 1px solid #dee2e6; padding:7px;">Name  </td> 
    <td class='aadhar_check' style="border: 1px solid #dee2e6; padding:7px;"><strong> {{$data->full_name}} </strong><br><strong> {{$data->care_of}} </strong></td> 
    </tr>
      </tr>
      
         <tr> <td style="border: 1px solid #dee2e6; padding:7px;">DOB  </td> 
         <td class='aadhar_check' style="border: 1px solid #dee2e6; padding:7px;"><strong>{{date("d-m-Y", strtotime($data->dob))}} </strong></td> 
      </tr>
      </tr>
      @if ($data->gender == 'M')
      <?php $gender = 'Male'; ?>
  @endif
  @if($data->gender == 'F')
  <?php $gender = 'Female'; ?>

  @endif
  @if($data->gender == '')
  <?php $gender = '--'; ?>

  @endif
         <tr> <td style="border: 1px solid #dee2e6; padding:7px;">Gender </td> 
         <td class='aadhar_check' style="border: 1px solid #dee2e6; padding:7px;"><strong> {{$gender}} </strong></td> 
      </tr>
      </tr>
         <tr> <td style="border: 1px solid #dee2e6; padding:7px;">Address </td> 
         <td class='aadhar_check' style="border: 1px solid #dee2e6; padding:7px;"><strong> {{$data->address}} </strong></td> 
      </tr>
      </tr>
         <tr> <td style="border: 1px solid #dee2e6; padding:7px;">Pin Code </td> 
         <td class='aadhar_check' style="border: 1px solid #dee2e6;padding:7px; "><span class="aadhar_mobile"><strong>{{$data->zip}}</strong></td> 
      </tr>
      <!--  -->
    </table>

    </div>
    @php 
      $business_user = Helper::user_details(Auth::user()->business_id);
      $business_id = Auth::user()->business_id;
      $parent_id = Auth::user()->parent_id;
      if($business_user->user_type=='customer')
      {
        $parent_id = Auth::user()->business_id;
      }
      $pdfaddress = Helper::get_footer_address($business_id);
      $defaultaddress = Helper::get_default_address($parent_id);
    @endphp
    <footer>
    <b>Confidential</b>
         <br><b>{!! Helper::company_name(Auth::user()->business_id) !!}</b><br>
          @if($pdfaddress!=NULL){{ $pdfaddress }}@else{{ $defaultaddress->address_line1 }} {{ $defaultaddress->city_name }}-{{ $defaultaddress->zipcode }}@endif</p>
  </footer>

  </body>
  
</html>