<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>BCD-Invoice</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
          
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

</head>
<style>
body {
    font-family: 'Poppins', sans-serif;
    font-size: small;
}
table {
    caption-side: bottom;
    border-collapse: collapse;
    width: 1000px; 
    margin: auto;
}
tbody, td, tfoot, th, thead, tr {
    border-color: inherit;
    border-style: solid;
    border-width: 0;
    font-size: 18px;
}

</style>
@php
    $invoice_date = date('d-M-y');
    // $word_amount = '';
    $total_price = $master_data->total_price;
    // $amount = '511110.57';
    //dd(Helper::numberTowords(57));

    list($number,$decimal) = explode('.',strval($total_price));
    $paise = $decimal == 0 ? '' : ' and '.Helper::numberTowords(intval($decimal)).' Paise';
    $word_amount=Helper::numberTowords($number).' Rupees'.$paise.' Only';


    $tax = $master_data->tax;

    $half_tax = number_format($tax/2,2);
    // $word_amount = '';
    $total_tax_amount = number_format(($master_data->sub_total * $master_data->tax) / 100,2);
    // $amount = '511110.57';
    //dd(Helper::numberTowords(57));

    list($tax_number,$tax_decimal) = explode('.',strval($total_tax_amount));
    $tax_paise = $tax_decimal == 0 ? '' : ' and '.Helper::numberTowords(intval($tax_decimal)).' Paise';
    $total_tax_amount_word=Helper::numberTowords($tax_number).' Rupees'.$tax_paise.' Only';

    $guest_carts=DB::table('guest_instant_carts as gc')
                                ->select('gc.*','s.name as service_name')
                                ->join('services as s','s.id','=','gc.service_id')
                                ->where(['gc.giv_m_id'=>$master_data->id])
                                ->orderBy('gc.service_id','asc')
                                ->get();
    $total_qty = 0;
@endphp
<body>
      <table style="border-right: 1px solid rgb(40, 39, 39); border-left: 1px solid rgb(40, 39, 39); border-top: 1px solid rgb(40, 39, 39);">
        <thead>
          <tr style="border-left: 1px solid rgb(40, 39, 39); border-top: 1px solid rgb(40, 39, 39);">
            <th colspan="7" style="width: 400px; text-align: center;"><h3>Tax Invoice</h3></th>
          </tr>
          <tr><th colspan="12"><hr></th></tr>
        </thead>
        <tbody>
            <tr style="border: 1px solid   rgb(40, 39, 39);">
                <td rowspan="3" colspan="3" style="border: 1px solid rgb(40, 39, 39); padding: 5px; width: 485px;"><b>PREMIER CONSULTANCY & INVESTIGATION PRIVATE LIMITED</b><br>
                  II ND, C-147, LAJPAT NAGAR-1,<br>
                  Lajpat Nagar 1, New Delhi<br>
                  South East Delhi, Delhi, 110024<br>
                  GSTIN/UIN: 07AAICP6146K1Z1<br>
                  State Name : Delhi, Code : 07<br>
                </td>
                <td colspan="2" style="border: 1px solid   rgb(40, 39, 39);">Invoice No.<br>
                  <b>BCDPCIL/{{date('my',strtotime($master_data->created_at))}}/{{$master_data->id}}</b></td>
                <td colspan="2" style="border: 1px solid   rgb(40, 39, 39);">Dated<br>
                  <b>{{$invoice_date}}</b></td>
            </tr>
            <tr style="border: 1px solid   rgb(40, 39, 39);">
                {{-- <td colspan="2" style="border: 1px solid rgb(40, 39, 39); padding: 5px;"></td> --}}
                <td colspan="4" style="border: 1px solid rgb(40, 39, 39); padding: 5px;">Mode/Terms of Payment<br>
                  <b>Online</b></td>
              </tr>
              <tr style="border: 1px solid   rgb(40, 39, 39);">
                <td colspan="4" rowspan="2" style="border: 1px solid rgb(40, 39, 39); padding: 5px; align-items: start;">Terms of Delivery</td>
              </tr>
              <tr style="border: 1px solid   rgb(40, 39, 39);">
                <td colspan="3" style="border: 1px solid rgb(40, 39, 39); padding: 5px;">Buyer (Bill to) <br>
                  <b>{{$master_data->name}}</b><br>
                  {{-- Mandi Road<br>
                  Sultanpur state<br>
                  khanna faram<br>
                  New Delhi-110030<br>
                  State Name : Delhi, Code : 07 --}}
                  {{$master_data->email}}<br>
                  {{$master_data->contactNumber}}
                </td>
              </tr>
              <tr style="border: 1px solid   rgb(40, 39, 39);">
                <th style="border: 1px solid rgb(40, 39, 39); padding: 5px;">S.No</th>
                <th style="border: 1px solid rgb(40, 39, 39);  padding: 5px;">Description of Goods</th>
                <th style="border: 1px solid rgb(40, 39, 39); padding: 5px;">HSN/SAC</th>
                <th style="border: 1px solid rgb(40, 39, 39); padding: 5px;">Quantity</th>
                <th style="border: 1px solid rgb(40, 39, 39); padding: 5px;">Rate</th>
                <th style="border: 1px solid rgb(40, 39, 39); padding: 5px;">Per</th>
                <th style="border: 1px solid rgb(40, 39, 39); padding: 5px;">Amount</th>
              </tr>
              
              @if(count($guest_carts)>0)
                @foreach ($guest_carts as $key => $gc)
                    <tr>
                        <td style="border-right: 1px solid rgb(40, 39, 39);text-align: center;">{{$key+1}}</td>
                        <td style="border-right: 1px solid rgb(40, 39, 39);">{{$gc->service_name}}</td>
                        <td style="border-right: 1px solid rgb(40, 39, 39);">998522</td>
                        <td style="border-right: 1px solid rgb(40, 39, 39);"><b>{{$gc->number_of_verification}} Nos</b></td>
                        <td style="border-right: 1px solid rgb(40, 39, 39);">{{number_format($gc->total_price/$gc->number_of_verification,2)}}</td>
                        <td style="border-right: 1px solid rgb(40, 39, 39);">Nos</td>
                        <td style="border-right: 1px solid rgb(40, 39, 39);"><b>{{$gc->total_price}}</b></td>
                    </tr>
                    @php
                        $total_qty+=$gc->number_of_verification;
                    @endphp
                @endforeach
              @endif

              {{-- <tr>
                <td style="border-right: 1px solid rgb(40, 39, 39);text-align: center;">1</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">Address</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">998522</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"><b>1 Nos</b></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">300.00</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">Nos</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"><b>300.00</b></td>
              </tr>
  
              <tr>
                <td style="border-right: 1px solid rgb(40, 39, 39);text-align: center;">2</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">LAW FIRM</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">998345</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"><b>2 Nos</b></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">350.00</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">Nos</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"><b>700.00</b></td>
              </tr>
  
              <tr>
                <td style="border-right: 1px solid rgb(40, 39, 39);text-align: center;">3</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">Identity Check</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">998345</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"><b>2 Nos</b></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">50.00</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">Nos</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"><b>100.00</b></td>
              </tr> --}}
              <tr>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"><b>{{$master_data->sub_total}}</b></td>
              </tr>
  
              <tr>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39); text-align: end;"><b>Other Current Liabilities:GST Payable:Output CGST</b></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">{{$half_tax}}</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">%</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"><b>{{number_format($total_tax_amount/2,2)}}</b></td>
              </tr>
              <tr>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39); text-align: end;"><b>Other Current Liabilities:GST Payable:Output CGST</b></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">{{$half_tax}}</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">%</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"><b>{{number_format($total_tax_amount/2,2)}}</b></td>
              </tr>
              {{-- <tr>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"><b>Bill Details</b></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
              </tr> --}}
              {{-- <tr>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);">New Ref BCDPCIL/0723/116 15 Days 1,298.00 Dr</td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
                <td style="border-right: 1px solid rgb(40, 39, 39);"></td>
              </tr> --}}
              <tr style="border: 1px solid   rgb(40, 39, 39);">
                <td style="border: 1px solid rgb(40, 39, 39);"></td>
                <td style="border: 1px solid rgb(40, 39, 39);">Total</td>
                <td style="border: 1px solid rgb(40, 39, 39);"></td>
                <td style="border: 1px solid rgb(40, 39, 39);"><b>{{$total_qty}} Nos</b></td>
                <td style="border: 1px solid rgb(40, 39, 39);"></td>
                <td style="border: 1px solid rgb(40, 39, 39);"></td>
                <td style="border: 1px solid rgb(40, 39, 39);"><b>{{$total_price}}</b></td>
              </tr>
              <tr>
                <td colspan="7">Amount Chargeable (in words)</td>
              </tr>
              
              <tr>
                <td colspan="7"><b>INR {{$word_amount}}</b></td>
              </tr>
  
              <tr style="border: 1px solid   rgb(40, 39, 39);">
                {{-- <td rowspan="2" style="border: 1px solid rgb(40, 39, 39);">HSN/SAC</td>
                <td rowspan="2" style="border: 1px solid rgb(40, 39, 39);">Taxable Value</td> --}}
                <td colspan="2" style="border: 1px solid rgb(40, 39, 39);">CGST</td>
                <td colspan="2" style="border: 1px solid rgb(40, 39, 39);">SGST/UTGST</td>
                <td rowspan="2" colspan="3" style="border: 1px solid rgb(40, 39, 39);">Total Tax Amount</td>
              </tr>
              <tr style="border: 1px solid   rgb(40, 39, 39);">
                <td style="border: 1px solid rgb(40, 39, 39);">Rate</td>
                <td style="border: 1px solid rgb(40, 39, 39);">Amount</td>
                <td style="border: 1px solid rgb(40, 39, 39);">Rate</td>
                <td style="border: 1px solid rgb(40, 39, 39);">Amount</td>
              </tr>
  
              <tr style="border: 1px solid   rgb(40, 39, 39);">
                {{-- <td style="border: 1px solid rgb(40, 39, 39);">998522</td>
                <td style="border: 1px solid rgb(40, 39, 39);">400.00</td> --}}
                <td style="border: 1px solid rgb(40, 39, 39);">{{$half_tax}}%</td>
                <td style="border: 1px solid rgb(40, 39, 39);">{{number_format($total_tax_amount/2,2)}}</td>
                <td style="border: 1px solid rgb(40, 39, 39);">{{$half_tax}}%</td>
                <td style="border: 1px solid rgb(40, 39, 39);">{{number_format($total_tax_amount/2,2)}}</td>
                <td colspan="3" style="border: 1px solid rgb(40, 39, 39);">{{$total_tax_amount}}</td>
              </tr>
              <!--<tr style="border: 1px solid   rgb(40, 39, 39);">
                {{-- <td style="border: 1px solid rgb(40, 39, 39);"><b>Total</b></td>
                <td style="border: 1px solid rgb(40, 39, 39);"><b>400.00</b></td> --}}
                <td style="border: 1px solid rgb(40, 39, 39);"></td>
                <td style="border: 1px solid rgb(40, 39, 39);"><b>{{number_format($total_tax_amount/2,2)}}</b></td>
                <td style="border: 1px solid rgb(40, 39, 39);"></td>
                <td style="border: 1px solid rgb(40, 39, 39);"><b>{{number_format($total_tax_amount/2,2)}}</b></td>
                <td style="border: 1px solid rgb(40, 39, 39);"><b>{{$total_tax_amount}}</b></td>
              </tr>-->

              <tr style="border: 1px solid   rgb(40, 39, 39);">
                <td colspan="7" style="border-right: 1px solid   rgb(40, 39, 39);">Tax Amount (in words) : <b>INR {{$total_tax_amount_word}}</b></td>
              </tr>
              <tr style="border: 1px solid   rgb(40, 39, 39);">
                <td colspan="2" style="border-right: 1px solid   rgb(40, 39, 39);width: 350px;">Remarks:<br>
                  {{$master_data->name}} {{date('M',strtotime($invoice_date))}} Month Invoice</td>
                <td colspan="5">Company's Bank Details <br>
                  A/c Holder's Name: <b>PREMIER CONSULTANCY AND INVESTIGATION PL</b><br>
                  Bank Name : <b>HDFC BANK A/C-50200008640971</b><br>
                  A/c No. : <b>50200008640971</b><br>
                  Branch & IFS Code: <b>OKHLA IND AREA PH- II & HDFC0000337</b></td>
              </tr>
              <tr style="border: 1px solid rgb(40, 39, 39);">
                <td colspan="2" style="width: 450px;">Declaration <br>
                  To encourage timely payments, we kindly inform you that <br>
                  an additional interest of 1% per month will be applied for <br>
                  late payments. We appreciate your prompt attention to <br>
                  this matter and thank you for your cooperation.</td>
                <td colspan="5" style="border: 1px solid rgb(40, 39, 39);"><b>for PREMIER CONSULTANCY & INVESTIGATION PRIVATE LIMITED</b><br><br><br>
                  <b>Authorised Signatory</b>
                </td>
              </tr>

        </tbody>
  </table>       
</body>
</html>