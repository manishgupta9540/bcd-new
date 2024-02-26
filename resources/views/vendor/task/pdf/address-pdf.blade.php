<!DOCTYPE html>
<html>
  <head>
    <title>Verification Data </title>
  <style>
    @page {
      header: page-header;
     
    }
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
    <htmlpageheader name="page-header">
        <!--logo top table -->
        <table style="width:100%; border-bottom:1px solid #ddd;">
            <tbody>
                <tr>
                    <td style="padding:7px; width:50%; text-align: left;"> {!! Helper::company_logo($candidate->parent_id) !!} </td>
                    <td style="padding:7px; width:50%; text-align: right; font-size:12px;"> {{ Helper::report_company_name($candidate->parent_id) }} </td>
                    {{-- <td style="padding:7px; width:50%; text-align: right;"> {{ Helper::company_name($candidate->business_id) }} </td> --}}
                </tr>
            </tbody>
        </table>
        <!--logo top table ends -->
    </htmlpageheader> 
        {{-- <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:16px; background-color: #c4e3ee; color:#226277; border: 1px solid #226277;">Verification Data</h3> --}}
        <br>
        <table autosize="1" style="width:100%; text-align: left;  border-collapse: collapse; margin-top: 8px; float:right">
           
           <tr >
                <td style="width:60%;"></td>  
                <td style="padding:7px; width:10%; border: 1px solid #226277;">
                    Ref No. 
                </td>
                <td style="padding:7px;  border: 1px solid #226277;">{{ Helper::candidate_reference_id($candidate->id)}}</td>
            </tr>
        </table>
        {{-- <table style="width:40%; text-align: left;  border:1px solid  #226277; border-collapse: collapse; margin-top: 1px; float:right " autosize="1">
           
            <tr style="width:40%; border-bottom: 1px solid #226277; border-right: 1px solid #226277;" >
                
                <td style="padding:7px; width:40%; border-bottom: 1px solid #226277; border-right: 1px solid #226277;">
                    Ref No.
                </td>
                <td style="padding:7px; "></td>
            </tr>
        </table> --}}
    <table style="width:100%; border:4px solid  #fff; border-collapse: collapse; margin-top: 1px;" autosize="1">
        <tr>
            <td style="padding:7px;">
                Dear Sir/Madam,
            </td>
        </tr>
    </table>

    <table style="width:100%; text-align: right; border:4px solid  #fff; border-collapse: collapse; margin-top: 1px;" autosize="1">
        <tr>
            <td style="padding:5px; border:1px solid #666; text-align:center;">
            <b><u>Employee’s Address Verification</u></b>
            </td>
        </tr>
    </table>
    <table style="width:100%;  border:4px solid  #fff; border-collapse: collapse; margin-top: 1px;" autosize="1">
        <tr>
            <td style="padding:5px;">
                Premier Shield is acting on behalf of our client in respect on provision of Employment Screening Services, Premier Shield has
                authorized field associates to conduct address verification on behalf of our Clients.
            </td>
        </tr>
    </table>
    <table width="100%" cellpadding="3" cellspacing="0" class="main" style="margin-top:10px;">
        <!--  -->
        <?php 
        $address="";
            $input_item_data = $jaf_items->form_data;
            
            $input_item_data_array =  json_decode($input_item_data, true); 
        ?>
            
        @if( count($input_item_data_array) > 0 )
            @foreach($input_item_data_array  as $key => $input)   
            <?php $key_val = array_keys($input); $input_val = array_values($input); 
                   
                // $university_board =  $readonly= "";
                // $address="";
                if($key_val[0] =='Address'){ 
                    
                  $address =$input_val[0] ;
                //   dd($address);
                }
             //name
            ?>
            @endforeach
            @endif
        {{-- <tr>
            <td colspan="2" style=" padding: 5px; border-bottom: 1px solid #226277;background-color: #c4e3ee;">
            <h3 style="text-align: left; font-weight:400;  font-size:16px;  color:#226277;  margin:0px;">Personal Details</h3>
            </td>
        </tr> --}}
        <tr>
            <td style="border-bottom: 1px solid #226277;border-right: 1px solid #226277; width: 30%;"><b>Candidate’s Name</b></td> <td colspan="2" style="border-bottom: 1px solid #226277; "> {{$candidate->name}}</td> <td style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;" ></td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;"><b>Father Name</b> </td><td  colspan="2" style="border-bottom: 1px solid #226277; ">{{$candidate->father_name}}</td><td style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;"></td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;"><b>Address </b></td> <td  colspan="2" style="border-bottom: 1px solid #226277; ">{{$address}}</td><td style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;"></td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #226277;border-right: 1px solid #226277;"><b>Date of Birth ( DOB ) </b></td> <td  colspan="2" style="border-bottom: 1px solid #226277; ">{{$candidate->dob==NULL?'N/A':date('d/m/Y',strtotime($candidate->dob))}}</td><td style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;"></td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #226277;border-right: 1px solid #226277;"><b>Contact Number</b> </td>  <td  colspan="2" style="border-bottom: 1px solid #226277;">+91-{{$candidate->phone}}</td><td style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;"></td>
        </tr>
    </table>
    <br>
    <table style="width:100%; border:4px solid  #fff; border-collapse: collapse; margin-top: 1px;" autosize="1">
        <tr>
            <td style=" width:60%; vertical-align: top;">
                We have also instructed the field associates to carry out this activity within the framework of the India regulatory environment and always ensure total compliance with the same.I would be grateful if you could take a couple of minutes to complete this form and return it back to us immediately.
            </td>
            <td style="padding:5px; width:40%; ">
                <div style="border:solid 1px #333; text-align:center; margin-top:1px; ">
                    <img src="{{ asset('admin/images/Money.jpg') }}" style='width:90%; height: 40%; margin:10px 0;'>
                </div>
            </td>
        </tr>
    </table>
    <table style="width:100%; text-align: right; border:4px solid  #fff; border-collapse: collapse; margin-top: 1px;" autosize="1">
        <tr>
            <td style="padding:5px; border:1px solid #666; text-align:center;">
              <b> <u> Verified Details</u></b>
            </td>
        </tr>
    </table>
    
    <table width="100%" cellpadding="0" cellspacing="0" class="main" >
         
          <!-- get form elements -->
         
            <tr>
                <td  style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;  width: 30%;">Date and Time at visit time</td>
                <td colspan="2" style="border-bottom: 1px solid #226277; "></td>   
            </tr>
            <tr>
                <td  style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;">Name of Respondent</td>
                <td colspan="2" style="border-bottom: 1px solid #226277; "></td>   
            </tr>
            <tr>
                <td  style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;">Relation</td>
                <td colspan="2" style="border-bottom: 1px solid #226277; "></td>   
            </tr>
            <tr>
                <td  style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;">Landmark</td>
                <td colspan="2" style="border-bottom: 1px solid #226277; "></td>   
            </tr>
            <tr>
                <td  style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;">Property Type</td>
                <td colspan="2" style="border-bottom: 1px solid #226277; "><ul style="list-style-type:square;"><li>Owned :-</li>
                    <li>Rented :-</li>
                    <li>Other :-</li></ul>
                </td>   
            </tr>
            <tr>
                <td  style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;">Address Type</td>
                <td colspan="2" style="border-bottom: 1px solid #226277; "><ul style="list-style-type:square;"><li>Present :-</li>
                    <li>Permanent :-</li>
                    <li>Other :-</li></ul></td>   
            </tr>
            
            <tr>
                <td style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;">Period of Stay</td>
                <td style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;">From: </td> 
                <td style="border-bottom: 1px solid #226277; ">To: </td>  
            </tr>
            <tr>
                <td  style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;">Contact Number</td>
                <td colspan="2" style="border-bottom: 1px solid #226277; "></td>   
            </tr>
            <tr>
                <td  style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;">No. Of Family Member</td>
                <td colspan="2" style="border-bottom: 1px solid #226277; "></td>   
            </tr>
            <tr>
                <td  style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;">Signature</td>
                <td colspan="2" style="border-bottom: 1px solid #226277; "></td>   
            </tr>
            <tr>
                <td  style="border-bottom: 1px solid #226277; border-right: 1px solid #226277;">Remarks</td>
                <td colspan="2" style="border-bottom: 1px solid #226277; "></td>   
            </tr>
          <!-- end form elements -->
          
        </table>
        <table style="width:100%;">
            <tr>
                <td ><b> Note :- If Our Associate Misbehave Or Ask Money, Please Contact on - 7303495235 </b></td>    
            </tr>
        </table>
    </div>

  </body>
  
</html>