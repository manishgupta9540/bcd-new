<!DOCTYPE html>
<html>
  <head>
    <title>Employee Verification Form </title>
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
  <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:16px; background-color: #c4e3ee; color:#226277; border: 1px solid #226277;">Employee Verification Form</h3>
  <br>

  <table width="100%" cellpadding="0" cellspacing="1" class="" >

      <tr>
        <td colspan="" width="80%" >
          <p style="color: #333">Please fill in the details with utmost attention, as these shall be verified by the Company and/ or by its authorized representatives. </p>
          <p> <b>All details are compulsory.</b> </p>
        </td>
        <td colspan="" width="20%">
          <div style="float:right; font-size:14px; height:100px; width:90px; border: 1px solid #226277;">
            <p style=" padding:5px; text-align:center; font-size:12px; vertical-align:middle;"> Please Affix Your Passport Size Photograph </p>
        </div>
        </td>
      </tr>
      
  </table>

    <table cellpadding="0" cellspacing="0" class="main" >
    <!--  -->
      <tr>
        <td colspan="2" style="padding: 0; border-bottom: 1px solid #226277;">
          <h3 style="text-align: left; font-weight:400;  padding: 5px; font-size:16px; background-color: #c4e3ee; color:#226277;  margin:0px;">Personal Details</h3>
        </td>
      </tr>
      <tr>
        <td style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; ">First Name:</td>  <td style="border-bottom: 1px solid #226277; ">Last Name:</td>
      </tr>
      <tr>
        <td style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; ">Gender:</td>  <td style="border-bottom: 1px solid #226277; ">Nationality:</td>
      </tr>
      <tr>
        <td style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; ">DOB:</td>  <td style="border-bottom: 1px solid #226277; ">Birth Place:</td>
      </tr>
      <tr>
        <td style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; ">Mobile:</td>  <td style="border-bottom: 1px solid #226277; ">Father Name:</td>
      </tr>
      
  
      <tr>
        <td colspan="2" ><br></td>
      </tr>
      <!--  -->
    </table>
    <br><br>
    @foreach($sla_service_items as $item)
    
    <table cellpadding="0" cellspacing="0" class="main" >
    <!--  -->
      <tr>
        <td colspan="2" style="padding: 0; border-bottom: 1px solid #226277;">
          <h3 style="text-align: left; font-weight:400;  padding: 5px; font-size:16px; background-color: #c4e3ee; color:#226277;  margin:0px;">  {{ Helper::get_single_data('services','name','id',$item->service_id) }}</h3>
        </td>
      </tr>
      <!-- get form elements -->
      <?php $sla_service_inputs = Helper::get_sla_item_inputs($item->service_id); 
          
      ?>
      @if( count($sla_service_inputs) > 0 )
        @foreach($sla_service_inputs as $item)   
           
        <tr>
          <td colspan="2" style="border-bottom: 1px solid #226277; ">{{ $item->label_name}}:</td>  
        </tr>
       
        @endforeach
      @endif
      <!-- end form elements -->
      
      <!--  -->
    </table>
    <br><br>
    @endforeach

    <table cellpadding="0" cellspacing="0" class="main" >
    <!--  -->
      <tr>
        <td colspan="2" style="padding: 0; border-bottom: 1px solid #226277;">
          <h3 style="text-align: left; font-weight:400;  padding: 5px; font-size:16px; background-color: #c4e3ee; color:#226277;  margin:0px;">Declaration & Authorization</h3>
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
            Signature:<br>
            Name <span style="font-size: 12px;">(In Block Letters)</span>:
        </td>  
        <td style="border-bottom: 1px solid #226277; ">Date:</td>
      </tr>
      <!--  -->
    </table>

    </div>

  </body>
  
</html>