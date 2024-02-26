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
      <img style='height:45px; object-fit:contain; width:150px;' src="http://app.techsaga.live/microsite/images/BCD-Logo1.png" alt='BCD'>
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
         <td class='' style="padding:7px; border:1px solid #666; "><strong> {{ $master_data->passport_number }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">Name</td> 
         <td class='' style="padding:7px; border:1px solid #666; "><strong> {{ $master_data->full_name }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">DOB</td> 
              <td class='' style="padding:7px; border:1px solid #666; "><strong> {{ date('d-m-Y', strtotime($master_data->dob)) }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">File No.</td> 
             <td class='' style="padding:7px; border:1px solid #666; "><strong> {{ $master_data->file_number }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">Date Of Application</td> 
            <td class='' style="padding:7px; border:1px solid #666; "><strong> {{ date('d-m-Y', strtotime($master_data->date_of_application)) }} </strong></td> 
        </tr>

      <!--  -->
    </table>
    </div>

    <footer>
    <p style="font-size:13px;">
          <b>Confidential</b>
          <br><b>Premier Consultancy & Investigation Private Limited</b><br>
          W-77, Lane No. 10, Mandir Marg, Anupam Garden, Near Sainik Farms, New Delhi - 110068 | INDIA</p>
    </footer>
            
  </body>
  
</html>