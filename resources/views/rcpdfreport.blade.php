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
  }
  table{width: 100%;}
  table.main{ padding: 0px; font-size: 14px; width: 100%;}
  table tr td{padding: 5px; }
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
          <b>Website Link:-</b> <a href="https://www.my-bcd.com/">https://www.my-bcd.com/</a></p>
          <table cellpadding="0" cellspacing="2" width="100%" ><tr><td align="left" style=" font-size:14px;">Powered By: <img src="{{url('/').'/admin/images/pcil-logo.png'}}" width="110" style="vertical-align:bottom"> </td><td align="right">{PAGENO} of {nb}</td> </tr></table>
        </footer>
      </htmlpagefooter>
  <table cellpadding="0" cellspacing="1" class="" style="border-bottom: 1px solid rgb(145, 139, 139)">
     <tr>
        <td colspan="" width="30%" style="text-align:left">
          <img style='height:45px; object-fit:contain; width:150px;' src="http://app.techsaga.live/microsite/images/BCD-Logo1.png" alt='BCD'>
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
          <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:18px; color:#333; "><b>RC Verification </b></h3>
        </td>
      </tr>
     
         <tr> <td style="padding:7px; border:1px solid #666; ">RC Validity</td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong>Valid</strong> <img style='width:30px; margin-bottom:-7px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> 
        </tr>
        
        <tr> <td width="50%" style="padding:7px; border:1px solid #666; ">RC Number</td> 
          <td width="50%" class="rc_number" style="padding:7px; border:1px solid #666; "> <strong> MP82AE1547 </strong> </td> 
        </tr>
        <tr> <td width="50%" style="padding:7px; border:1px solid #666; ">Registration Date</td> 
          <td width="50%" class="rc_number" style="padding:7px; border:1px solid #666; "><strong> 14-05-2019 </strong> </td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">Owner Name</td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> OM PRAKASH </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; "> Chassis Number </td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> YT3JMT35SKD1XXXXX </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; "> Engine Number </td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> U10BN82XXXXX </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; "> Maker Model </td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> MARUTI SUZUKI INDIA LTD / WAGONR VXI 1.0L </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; "> Fuel Type </td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> PETROL </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; "> Norms Type </td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> BHARAT STAGE IV </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; "> Insurance Company </td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; "> Insurance Policy Number </td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; "> Insurance upto </td> 
         <td class='insurance_upto' style="padding:7px; border:1px solid #666; "><strong> </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; "> Registered at </td> 
         <td class='registered_at' style="padding:7px; border:1px solid #666; "><strong> INDORE, MADHYA PRADESH </strong></td> 
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

    </div>

    {{-- <footer>
    <p style="font-size:13px;">
          <b>Confidential</b>
          <br><b>Premier Consultancy & Investigation Private Limited</b><br>
          W-77, Lane No. 10, Mandir Marg, Anupam Garden, Near Sainik Farms, New Delhi - 110068 | INDIA</p>
    </footer> --}}

  </body>
  
</html>