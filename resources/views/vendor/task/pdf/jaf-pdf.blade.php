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
    
  <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:16px; background-color: #c4e3ee; color:#226277; border: 1px solid #226277;">Verification Data</h3>
  <br>

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
    @if($jaf_items)
        {{-- @foreach($jaf_items as $item) --}}
        
        <table width="100%" cellpadding="0" cellspacing="0" class="main" >
          <tr>
            <td colspan="2" style="padding: 5px; background-color: #c4e3ee; border-bottom: 1px solid #226277;">
              <h3 style="text-align: left; font-weight:400;   font-size:16px; color:#226277;  margin:0px;">  @if($jaf_items->verification_type=='auto' || $jaf_items->verification_type=='Auto'){{ $jaf_items->service_name }} @else {{ $jaf_items->service_name }} @endif</h3>
            </td>
          </tr>
          <!-- get form elements -->
          <?php 
                $input_item_data = $jaf_items->form_data;
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
              <td colspan="2" style="border-bottom: 1px solid #226277; ">{{ $key_val[0]}} : {{ $input_val[0] }}</td>  
            </tr>
          
            @endforeach
          @endif
          <!-- end form elements -->
        
        </table>
        <br><br>
        {{-- @endforeach --}}
    @endif
   

    </div>

  </body>
  
</html>