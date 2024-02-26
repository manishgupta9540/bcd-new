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
    <htmlpagefooter name="page-footer">
        <footer>
          <p style="font-size:13px;border-top:1px solid #666;padding-top:20px;">
            <b>Confidential</b>
            <br><b>Premier Consultancy & Investigation Private Limited</b><br>
            <b>Website Link:-</b> <a href="https://www.my-bcd.com/">https://www.my-bcd.com/</a></p>
            <table cellpadding="0" cellspacing="2" width="100%" ><tr><td align="left" style=" font-size:14px;">Powered By: <img src="{{url('/').'/admin/images/pcil-logo.png'}}" width="110" style="vertical-align:bottom"> </td><td align="right">{PAGENO} of {nb}</td> </tr></table>
          </footer>
        </htmlpagefooter>
  <div class="cover" >
    
    <table cellpadding="0" cellspacing="1" class="" style="border-bottom: 1px solid rgb(145, 139, 139)">
      <tr>
        <td colspan="" width="30%" style="text-align:left">
          <img style='height:45px; object-fit:contain; width:150px;' src="{{url('/').'/admin/images/BCD-Logo1.png'}}" alt='BCD'>
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
        <td colspan="4" style="padding: 5; text-align:center ">
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

    <table cellpadding="0" cellspacing="0" class="main" style="margin-bottom: 2%">
        <!--  -->
      <tr>
        <td colspan="2" style="padding: 5; text-align:center ">
          <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:18px; color:#333; "><b>CIN Verification </b></h3>
        </td>
      </tr>
      
        <tr> <td style="padding:7px; border:1px solid #666; width:30%">CIN Validity</td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; ">Valid <img style='width:30px; margin-bottom:-7px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> 
        </tr>
         <tr> <td style="padding:7px; border:1px solid #666; ">Verification Check</td> 
         <td class='aadhar_check' style="padding:7px; border:1px solid #666; ">Completed</td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">CIN Number</td> 
            <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->cin_number}} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">Company Name</td> 
            <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->company_name}} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">Address</td> 
          <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->registered_address}} </strong></td> 
      </tr>
      <tr> <td style="padding:7px; border:1px solid #666; ">Registration No.</td> 
          <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->registration_number}} </strong></td> 
      </tr>
      <tr> <td style="padding:7px; border:1px solid #666; ">Date of Incorporation</td> 
        <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->date_of_incorporation!=NULL ? date('Y-m-d',strtotime($master_data->date_of_incorporation)) : 'N/A'}} </strong></td> 
    </tr>
    <tr> <td style="padding:7px; border:1px solid #666; ">Email ID</td> 
      <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->email_id}} </strong></td> 
  </tr>
  <tr> <td style="padding:7px; border:1px solid #666; ">Paid Up Capital In Rupees</td> 
    <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->paid_up_capital_in_rupees}} </strong></td> 
</tr>
<tr> <td style="padding:7px; border:1px solid #666; ">Authorised Capital</td> 
  <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->authorised_capital}} </strong></td> 
</tr>
<tr> <td style="padding:7px; border:1px solid #666; ">Category</td> 
  <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->company_category}} </strong></td> 
</tr>
<tr> <td style="padding:7px; border:1px solid #666; ">Sub Category</td> 
  <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->company_subcategory}} </strong></td> 
</tr>
<tr> <td style="padding:7px; border:1px solid #666; ">Class</td> 
  <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->company_class}} </strong></td> 
</tr>
<tr> <td style="padding:7px; border:1px solid #666; ">E-Filling Status</td> 
  <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->company_efilling_status}} </strong></td> 
</tr>
<tr> <td style="padding:7px; border:1px solid #666; ">Date of Last AGM</td> 
  <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->date_of_last_AGM!=NULL ? date('Y-m-d',strtotime($master_data->date_of_last_AGM)) : 'N/A'}} </strong></td> 
</tr>
<tr> <td style="padding:7px; border:1px solid #666; ">Date of Balance Sheet</td> 
  <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->date_of_balance_sheet!=NULL ? date('Y-m-d',strtotime($master_data->date_of_balance_sheet)) : 'N/A'}} </strong></td> 
</tr>
      <!--  -->
    </table>
    
    @php
      $director_arr = [];
      if($master_data->directors!=NULL)
      {
        $director_arr = json_decode($master_data->directors,true);
      }
    @endphp

    @if(count($director_arr) > 0)
      <table cellpadding="0" cellspacing="0" class="main" style="margin-bottom: 2%">
        <!--  -->
      <tr>
        <td colspan="2" style="padding: 5; text-align:center ">
          <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:18px; color:#333; "><b>Directors </b></h3>
        </td>
      </tr>
      @foreach ($director_arr as $key => $value)
          <tr> <td style="padding:7px; border:1px solid #666; ">Name : <strong>{{$value['name']}}</strong></td> 
            <td class='aadhar_validity' style="padding:7px; border:1px solid #666; ">DIN : <strong>{{$value['DIN']}}</strong></td> 
        </tr>
      @endforeach
        
      <!--  -->
    </table>
  @endif
    
  @if(count($director_arr) > 1)
    <pagebreak/>
  @endif
    <table cellpadding="0" width="100%" cellspacing="0" class="main" style="margin-top:5%;">
      <!--  -->
      <tr>
        <td colspan="2" style="padding: 5px; border-left: 1px solid #333; border-bottom: 1px solid #333; background-color: #333;">
          <h3 style="text-align: left; font-weight:400;   font-size:16px;  color:#fff;  margin:0px;">Disclaimer</h3>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="border-left: 1px solid #333;border-bottom: 1px solid #333;border-right: 1px solid #333; "> There is No record that is stored in MyBcd and is purged out of system after One Time Report Generation.</td> 
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