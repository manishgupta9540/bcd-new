<!DOCTYPE html>
<html>
  <head>
    <title>ID Verification  </title>
  <style>
    @page {
      /* header: page-header; */
      footer: page-footer;
    }
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
                bottom: 0px; 
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
          W-77, Lane No. 10, Mandir Marg, Anupam Garden, Near Sainik Farms, New Delhi - 110068 | INDIA</p>
          <table cellpadding="0" cellspacing="2" width="100%" ><tr><td align="left" style=" font-size:14px;">Powered By: <img src="{{url('/').'/admin/images/pcil-logo.png'}}" width="110" style="vertical-align:bottom"> </td><td align="right">{PAGENO} of {nb}</td> </tr></table>
        </footer>
     </htmlpagefooter>

  <table cellpadding="0" cellspacing="1" class="" style="border-bottom: 1px solid rgb(145, 139, 139)" >
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
        <td style="padding:7px; border:1px solid #666; ">Initiated Date</td>  
        <td style="padding:7px; border: 1px solid #666; ">Completed Date </td>
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

    <table width="100%" cellpadding="0" cellspacing="0" class="main" >
    <!--  -->
      <tr>
        <td colspan="2" style="text-align: center; padding:5px;  ">
          <h3 style="font-weight:400;  padding: 5px; font-size:18px; color:#333; "><b>Aadhar Number Verification </b></h3>
        </td>
      </tr>
      <tr> <td width="50%" style="padding:7px; border:1px solid #666; ">Aadhar number</td> 
        <td width="50%" class="aadhar_number" style="padding:7px; border:1px solid #666; "><strong> {{$master_data->aadhar_number}} </strong></td> 
      </tr>
         <tr> <td style="padding:7px; border:1px solid #666; ">Aadhar Validity</td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; ">Valid <img style='width:30px; margin-bottom:-7px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> 
      </tr>
         <tr> <td style="padding:7px; border:1px solid #666; ">Verification Check</td> 
         <td class='aadhar_check' style="padding:7px; border:1px solid #666; ">Completed</td> 
      </tr>
      <tr> <td style="padding:7px; border:1px solid #666; "> Result</td> 
         <td style="padding:7px; border:1px solid #666; ">
               Aadhar Verification Completed <br>
         </td> 
      </tr>
      </tr> 
         <tr> <td style="padding:7px; border:1px solid #666; "> Aadhar number exists! </td> 
         <td class='aadhar_check' style="padding:7px; border:1px solid #666; vertical-align:middle;">  <span style="margin-top:-2px;"> <strong > {{$master_data->aadhar_number}} </strong> </span> </td> 
      </tr>
      </tr>
         <tr> <td style="padding:7px; border:1px solid #666; ">Age Bond </td> 
         <td class='aadhar_check' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->age_range}} </strong></td> 
      </tr>
      </tr>
         <tr> <td style="padding:7px; border:1px solid #666; ">Gender </td> 
         <td class='aadhar_check' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->gender}} </strong></td> 
      </tr>
      </tr>
         <tr> <td style="padding:7px; border:1px solid #666; ">State </td> 
         <td class='aadhar_check' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->state}} </strong></td> 
      </tr>
      </tr>
         <tr> <td style="padding:7px; border:1px solid #666; ">Mobile </td> 
         <td class='aadhar_check' style="padding:7px; border:1px solid #666; ">XXXXXXX<span class="aadhar_mobile"><strong>{{$master_data->last_digit}}</strong></td> 
      </tr>
      <!--  -->
    </table>

    {{-- <pagebreak/> --}}
    <table cellpadding="0" width="100%" cellspacing="0" class="main" style="margin-top:10%;">
      <!--  -->
      <tr>
        <td colspan="2" style="padding: 5px; border-left: 1px solid #333; border-bottom: 1px solid #333; background-color: #333;">
          <h3 style="text-align: left; font-weight:400;   font-size:16px;  color:#fff;  margin:0px;">Disclaimer</h3>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="border-left: 1px solid #333;border-bottom: 1px solid #333;border-right: 1px solid #333; "> There is No record that is stored in myBcd and is purged out of system after One Time Report Generation.</td> 
      </tr>
      <tr>
        <td colspan="2" style="border-left: 1px solid #333;border-bottom: 1px solid #333;border-right: 1px solid #333; ">PCIL is NSR - National Skills Registry Empanelled.</td> 
      </tr>
      
      
      <!--  -->
    </table>

    </div><footer>
    {{-- <p style="font-size:13px;">
          <b>Confidential</b>
          <br><b>Premier Consultancy & Investigation Private Limited</b><br>
          W-77, Lane No. 10, Mandir Marg, Anupam Garden, Near Sainik Farms, New Delhi - 110068 | INDIA</p>
    </footer> --}}


  </body>
  
</html>