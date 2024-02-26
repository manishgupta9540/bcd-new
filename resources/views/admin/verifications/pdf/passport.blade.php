<!DOCTYPE html>
<html>
  <head>
    <title>ID Verification  </title>
  <style>
  body{
  font-family: Arial, Helvetica Neue, Helvetica, sans-serif; 
  color:#333;
  }
  table{width: 100%;}
  table.main{ padding: 0px; font-size: 14px; width: 100%;}
  table tr td{padding: 5px; }
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
          
        </td>
      </tr>
  </table>

    <table cellpadding="0" cellspacing="0" class="main" >
    <!--  -->
    <tr>
        <td colspan="4" style="padding: 0; ">
          <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:18px; color:#333; "> &nbsp; </h3>
        </td>
      </tr>
      <tr>
        <td colspan="4" style="padding: 5px; text-align:center ">
          <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:18px; color:#333; "><b>ID Verification</b> </h3>
        </td>
      </tr>
      <tr>
        <td style="padding:7px; border:1px solid #666; ">Initiated Date</td>  <td style="padding:7px; border:1px solid #666; ">Completed Date </td>
        <td style="padding:7px; border:1px solid #666; ">Insufficiency Raise Date  </td><td style="padding:7px; border:1px solid #666; ">Insufficiency Cleared Date </td>
      </tr>
      <tr>
        <td style="padding:7px; border:1px solid #666; ">{{ date('d-M-Y') }}</td>
        <td style="padding:7px; border:1px solid #666; ">{{ date('d-M-Y') }}</td>
        <td style="padding:7px; border:1px solid #666; ">N/A</td>
        <td style="padding:7px; border:1px solid #666; ">N/A</td>
      </tr>
  
      <tr>
        <td colspan="4" ><br></td>
      </tr>
      <!--  -->
    </table>

    <table cellpadding="0" cellspacing="0" class="main" >
    <!--  -->
      <tr>
        <td colspan="2" style="padding: 5px; text-align:center ">
          <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:18px; color:#333; "><b>Passport Verification </b></h3>
        </td>
      </tr>
      
         <tr> <td style="padding:7px; border:1px solid #666; ">Passport Validity</td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; ">Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> 
         </tr>
         <tr> <td style="padding:7px; border:1px solid #666; ">Verification Check</td> 
              <td class='aadhar_check' style="padding:7px; border:1px solid #666; ">Completed</td> 
         </tr>
         
        <tr> <td style="padding:7px; border:1px solid #666; ">Passport Number</td> 
         <td class='' style="padding:7px; border:1px solid #666; "><strong> {{ $data->passport_number }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">Name</td> 
         <td class='' style="padding:7px; border:1px solid #666; "><strong> {{ $data->full_name }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">DOB</td> 
              <td class='' style="padding:7px; border:1px solid #666; "><strong> {{ date('d-m-Y', strtotime($data->dob)) }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">File No.</td> 
             <td class='' style="padding:7px; border:1px solid #666; "><strong> {{ $data->file_number }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">Date Of Application</td> 
            <td class='' style="padding:7px; border:1px solid #666; "><strong> {{ date('d-m-Y', strtotime($data->date_of_application)) }} </strong></td> 
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
    <p style="font-size:13px;">
          <b>Confidential</b>
          <br><b>{!! Helper::company_name(Auth::user()->business_id) !!}</b><br>
          @if($pdfaddress!=NULL){{ $pdfaddress }}@else{{ $defaultaddress->address_line1 }} {{ $defaultaddress->city_name }}-{{ $defaultaddress->zipcode }}@endif</p>
  </footer>
            
  </body>
  
</html>