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
    <htmlpagefooter name="page-footer">
      <footer style="border-top: 1px solid rgb(145, 139, 139)">
        <p style="font-size:13px;">
          <b>Confidential</b>
          <br><b>Premier Consultancy & Investigation Private Limited</b><br>
          <b>Website Link:-</b> <a href="https://premier-consultancy.com/">https://premier-consultancy.com/</a></p>
          <table cellpadding="0" cellspacing="2" width="100%" ><tr><td align="left" style=" font-size:14px;">Powered By: <img src="{{url('/').'/admin/images/pcil-logo.png'}}" width="110" style="vertical-align:bottom"> </td><td align="right">{PAGENO} of {nb}</td> </tr></table>
        </footer>
      </htmlpagefooter>
  <table cellpadding="0" cellspacing="1" class=""  style="border-bottom: 1px solid rgb(145, 139, 139)" >
     <tr>
      <td colspan="" width="30%" style="text-align:left">
        <img style='height:45px; object-fit:contain; width:150px;' src="{{url('/').'/admin/images/BCD-Logo3.png'}}" alt='BCD'>
      </td>
      <td colspan="" width="70%" style="text-align:right;">
        <img style='height:30px; object-fit:contain; width:150px;' src="{{url('/').'/admin/images/Nasscom.png'}}" alt=''>
        <img style='height:45px; object-fit:contain; width:150px;' src="{{url('/').'/admin/images/NSR_logo.png'}}" alt=''>
        <img style='height:45px; object-fit:contain; width:150px;' src="{{url('/').'/admin/images/NSDL_logo.png'}}" alt=''>
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
        <td style="padding:7px; border:1px solid #666;  ">Initiated Date</td>  <td style="padding:7px; border:1px solid #666;  ">Completed Date </td>
        <td style="padding:7px; border:1px solid #666;  ">Insufficiency Raise Date  </td><td style="padding:7px; border:1px solid #666;  ">Insufficiency Cleared Date </td>
      </tr>
      <tr>
        <td style="padding:7px; border:1px solid #666;  ">{{ date('d-M-Y') }}</td>
        <td style="padding:7px; border:1px solid #666;  ">{{ date('d-M-Y') }}</td>
        <td style="padding:7px; border:1px solid #666;  ">N/A</td>
        <td style="padding:7px; border:1px solid #666;  ">N/A</td>
      </tr>
  
      <tr>
        <td colspan="4" ><br></td>
      </tr>
      <!--  -->
    </table>

    <table cellpadding="0" cellspacing="0" class="main" >
    <!--  -->
      <tr>
        <td colspan="2" style="padding: 5px; text-align:center; ">
          <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:18px; color:#333; "><b>GSTIN Number Verification </b></h3>
        </td>
      </tr>
        <tr> <td width="50%" style="padding:7px; border:1px solid #666;  ">GSTIN Validity</td> 
            <td width="50%" class='' style="padding:7px; border:1px solid #666;  ">Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666;  ">GSTIN Number</td> 
         <td class='' style="padding:7px; border:1px solid #666;  "><strong> {{ $master_data->gst_number }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666;  ">Business Name</td> 
            <td class='' style="padding:7px; border:1px solid #666;  "><strong> {{ $master_data->business_name }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666;  ">Address</td> 
            <td class='' style="padding:7px; border:1px solid #666;  "><strong> {{ $master_data->address }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666;  ">Center Jurisdiction</td> 
            <td class='' style="padding:7px; border:1px solid #666;  "><strong> {{ $master_data->center_jurisdiction }} </strong></td> 
        </tr><tr> <td style="padding:7px; border:1px solid #666;  ">Date Of Registration</td> 
            <td class='' style="padding:7px; border:1px solid #666;  "><strong> {{ date('d-M-Y',strtotime($master_data->date_of_registration)) }} </strong></td> 
        </tr><tr> <td style="padding:7px; border:1px solid #666;  ">Constitution Of Business</td> 
            <td class='' style="padding:7px; border:1px solid #666;  "><strong> {{ $master_data->constitution_of_business }} </strong></td> 
        </tr><tr> <td style="padding:7px; border:1px solid #666;  ">Taxpayer Type</td> 
            <td class='' style="padding:7px; border:1px solid #666;  "><strong> {{ $master_data->taxpayer_type }} </strong></td> 
        </tr><tr> <td style="padding:7px; border:1px solid #666;  ">GSTIN Status</td> 
            <td class='' style="padding:7px; border:1px solid #666;  "><strong> {{ $master_data->gstin_status }} </strong></td> 
        </tr>
        
      <!--  -->
    </table>
    </div>
    {{-- <footer>
    <p style="font-size:13px;">
          <b>Confidential</b>
          <br><b>Premier Consultancy & Investigation Private Limited</b><br>
          W-77, Lane No. 10, Mandir Marg, Anupam Garden, Near Sainik Farms, New Delhi - 110068 | INDIA</p>
    </footer> --}}
  </body>
  
</html>