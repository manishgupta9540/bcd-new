@extends('layouts.guest')
@section('content')
<!-- ============ Search UI Start ============= -->
<style type="text/css">
   .btn-secondary:focus, .btn-outline-secondary:focus{
   box-shadow: none!important;
   }
   span.apply1 {
   position: relative;
   top: 3px;
   font-size: 17px;
   }
   .form-control { 
   height: 52px;
   border: initial;
   outline: initial !important;
   background: #ffffff;
   border: 1px solid #dfdfdf!important;
   color: #47404f;
   }
   .input-group [type="text"].form-control {
   height: 48px;
   width:60%
   }
   .checkservices {
   font-size: 17px!important;
   font-weight: 600;
   }
   .col-md-7.order-md-1 {
   border: 3px solid #edeeed;
   position: relative;
   padding: 20px;
   }
   .col-md-7 {
   flex: 0 0 58.33333%;
   max-width: 55.33333%
   }
   .col-md-5.order-md-2.mb-4 {
   margin-left: 29px;
   }
   strong.price123 {
   font-size: 18px;
   }
   span.totalprice {
   font-size: 18px;
   font-weight: 600;
   }
   span.text-success.subtotal123 {
   font-size: 17px;
   font-weight: 600;
   }
   input.form-control.promonumber {
   background-color: white;
   }
   h6.my-0.subtotal {
   font-size: 17px;
   }
   ul.list-group.mb-3.totalamt {
   margin-top: 20px;
   }
   .row.cartone {
   padding-left: 30px;
   padding-right: 30px;
   }
   .btn-secondary:hover, .btn-outline-secondary:hover {
   background: #52495a;
   box-shadow: 0px 2px 0px -8px #52495a;
   border-color: #52495a;
   }
   .input-group-append.promonew {
   position: relative;
   left: 0px;
   border: 1px solid #dfdfdf;
   }
   .input-group.promocode12 {
   width: 100%;
   }
   .input-group-append.promonew {
   border: 1px solid #002e62;
   background-color: #002e62;
   /* padding-left: 20px;
   padding-right: 30px; */
   }
   .input-group-append.promonew button {
    background: #002e62!important;
    padding: 6px 20px!important;
    height: 46px!important;
    color:#fff!important;
}.input-group-append.promonew button:hover {
    box-shadow: none;
}
   .row.cartone {
   margin-top: 55px;
   }
   span.saveinfo {
   margin-left: 11px;
   }
   button.btn.btn-primary.btn-lg.btn-block {
   background-color: #c69632;
   padding: 10px 15px;
   color: #fff;
   border: 1px solid #c69632;
   }
   .btn-secondary {
   color: #fff;
   background-color: #c69632;
   border-color: #c69632;
   }
   .form-control:focus {
   color: #212529;
   background-color: #fff;
   border-color: #ced4da;
   outline: 0;
   box-shadow: 0 0 0 0.25rem rgb(13 110 253 / 0%);
   }
   .checkbox.keepme {
   margin-top: 12px;
   }
   h4.mb-3.billingaddress {
   margin-top: 20px;
   }
   .product1 {
   display: flex;
   }
   .list {
   margin-left: 12px;
   }
   small.text-muted {
   position: relative;
   left: 42px;
   top: 0;
   }
   .badge{
      font-size:15px;
   }
   .total-li{
      font-size:18px !important;
   }
   /* .badge {
   display: inline-block;
   padding: .35em .65em;
   font-size: .75em;
   font-weight: 700;
   line-height: 1;
   color: #fff;
   background-color: #c69632;
   border-radius: 50%;
   text-align: center;
   white-space: nowrap;
   vertical-align: baseline;
   border-radius: 50%;
   } */
   *, ::after, ::before {
   box-sizing: border-box;
   }
   /* .fa-circle-o-notch:before{
      color: #fff!important;
   } */
   .col-md-5.order-md-2.mb-4 {
   border: 1px solid #edeeed;
   box-shadow: 0px 0px 10px #ddd;
   position: relative;
   padding: 20px;
   }
   .container.shippingdetail {
   margin-top: 50px;
   }
   .text-success {
   color: #6c757d!important;
   }
   .list-group {
   border-radius:0px; 
   }
   .card {
   border-radius:0px;
   }

   .disabled-link
   {
       pointer-events: none;
   }

   /* ul.breadcrumb {
   padding: 10px 16px;
   list-style: none;
   background-color: #eee;
   }
   ul.breadcrumb li {
   display: inline;
   font-size: 18px;
   }
   ul.breadcrumb li+li:before {
   padding: 8px;
   color: black;
   content: "\203A";
   }
   ul.breadcrumb li a {
   color: #0275d8;
   text-decoration: none;
   }
   ul.breadcrumb li a:hover {
   color: #01447e;
   text-decoration: none;
   } */

   span.mypromo {
         background-color: #efecec;
         padding-left: 17px;
         
         padding-top: 10px;
         padding-bottom: 10px;
         padding-right: 130px;
      }
         p.promocodeshow {
         font-size: 17px;
         margin-top: 20px;
      }
      i.fa.fa-times {
         position: relative;
         left: -26px;
      }
      .remove_promo
      {
         cursor: pointer;
      }

      .input-group{
         justify-content:space-between!important;
      }
      .sweet-alert button.cancel {
        background: #DD6B55 !important;
    }
@media(max-width:1260px){
   .col-md-5 {
    flex: 0 0 41.66667%;
    max-width: 40.66667%;
}

}

    @media(max-width:1100px){
      .col-12 {
               flex: 0 0 100% !important;
               max-width: 100% !important;
    }
    .col-md-5.order-md-2.mb-4 {
    margin-left: 0px !important;
    margin-top: 25px;
}
.cartone{
   margin-top:0px !important;
}

    }
    
   @media (min-width: 576px) and (max-width: 767.98px) { 

   .col-md-5.order-md-2.mb-4 {
   position: relative;
   left: 0px;
   padding: 20px;
   }
   .container.shippingdetail {
   margin-top: 0px;
   }
   strong.price123 {
    font-size: 14px;
   }
   .main-content {
    margin-top: 110px;
   }
   .d-flex.align-items-center {
    padding-left: 0px!important;
   }
   }
   @media(max-width:768px)
      {
          .layout-sidebar-large .main-header .header-part-right {
         
             width: 70%;
         }
         .main-content {
    margin-top: 100px;
}

   }
   @media(max-width:576px){
      .d-flex.align-items-center {
              padding-left: 0px!important;
              width: 20%;
            }
            .layout-sidebar-large .main-header .header-part-right {
          width: 77%;
       }

   }
   @media (max-width: 650px) { 
      .main-content-wrap {
    margin-top: 20px;
}
   .col-md-5.order-md-2.mb-4 {
   position: relative;
   left: 0px;
   padding: 20px;
   }
   .container.shippingdetail {
   margin-top: 0px;
   }
   ul.breadcrumb li {
    font-size: 11px !important;
  }
  h3.card-title.mt-3.verifying1.mx-auto.text-center {
    margin: 0 !important;
}
hr {
    margin: 0 !important;
}
.row.cartone {
    margin-top: 20px;
}
.row.cartone {
    padding: 0;
}
.row.cartone .col-md-5.order-md-2.mb-4 {
    margin-left: 0px;
}
.col-12 {
    flex: 0 0 100% !important;
    max-width: 100% !important;
}
strong.price123 {
    font-size: 16px;
}
.checkservices {
    font-size: 16px!important;
    font-weight: 600;
}
span.text-success.subtotal123 {
    font-size: 15px;
    font-weight: 600;
}
.main-content {
    margin-top: 0px;
}
.main-header .d-flex.align-items-center {
   width: 18% !important;
}
.layout-sidebar-large .main-header .header-part-right {
    width: 77% !important;
}
.main-content {
    margin-top: 100px;
}

   }

   @media(max-width:375px){
      strong.price123 {
    font-size: 12px;
}
.checkservices {
    font-size: 12px!important;
    font-weight: 600;
}
span.text-success.subtotal123 {
    font-size: 12px;
    font-weight: 600;
}
.total-li{
   font-size:12px !important;
}

span.totalprice{
   font-size:14px !important;
}

   }

   span.apply1 {
    top: 0px !important;
}


.third-step-details2 .apply-block button
{
    padding: 6px 20px;
    margin-top: -6px;
    font-weight: 400;
}
.third-step-details .child
{
    display: flex;
    justify-content: space-around !important;
}
.third-step-details
{
    padding: 0px 10px;
}
.prodes
{
    text-align: right !important;
    padding-right: 15px !important;
}
@media only screen and (max-width: 768px) {
    .third-step-details
    {
        margin-bottom: 10px;
    }
}
</style>

<!-- ============ Search UI End ============= -->

<!-- =============== Left side End ================-->
{{-- <div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content"> --}}
        {{-- <div class="row carddes"> --}}
            <div class="col-md-10 col-sm-12 marg-des">
                <div class="verification-detail-1">
                <div class="logosection">
                    <p><span>Instant Verification</span><br> Order details</p>
                    <p>Government approved APIs * : <img src="{{asset('admin/microsite/images/udiai1.png')}}"></p>
                </div>
                <?php
                        $guestID=Crypt::encryptString($guest_master->id);
                    ?>
                <div class="verification-1-button">
                    <!-- <a class="verfication-top-tab" href="verification-step-1.php">Choose items</a>
                        <a class="verfication-top-tab" href="verification-step-2.php">Enter details</a>
                        <a class="verfication-top-tab activeclass" href="verification-step-3.php">Make payment</a>
                        <a class="verfication-top-tab" href="verification-step-4.php">View results</a> -->
                    <a href="javascript:void(0)"><button class="verfication-top-tab">Choose items</button></a>
                    <a href="javascript:void(0)"><button class="verfication-top-tab">Enter details</button></a>
                    <a href="{{url('verify/instant_verification/checkout/'.$guestID)}}"><button class="verfication-top-tab activeclass">Make payment</button></a>
                    <a href="javascript:void(0)"><button class="verfication-top-tab">View results</button></a>
                </div>
                @if($guest_master!=NULL)
                        <form method="post" id="verificationForm" action="{{ url('/verify/instant_verification/checkout/store') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="guest_master_id" id="guest_master_id" value="{{Crypt::encryptString($guest_master->id)}}">
                            <div class="row mt-4 rowdes">
                                <div class="col-lg-7 col-sm-12">
                                    <div class="third-step-details">
                                        <div><h5>Order Summary</h5></div>
                                        <table>
                                            <tr>
                                                <th class="prodes">Product</th>
                                                <th>No. of Verification</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                            @foreach($guest_cart as $item)
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="child">
                                                                <a href="javascript:;" class="text-right remove_btn mr-2" data-id="{{base64_encode($item->id)}}" data-service="{{base64_encode($item->service_id)}}" style="font-size:17px; margin-top:-10px; color:red !important">
                                                                    <i class="fas fa-trash-alt"></i></a>
                                                                <span class="my-0 subtotal checkservices">{{stripos(Helper::get_service_name($item->service_id),'Driving')!==false ? 'Driving License' : Helper::get_service_name($item->service_id)}}</span>
                                                            </div>
                                                        </td>
                                                        <td> <span class="">{{$item->number_of_verification}}</span></td>
                                                        <td><span class="text-success subtotal123"><i class="fas fa-rupee-sign"></i> {{$item->sub_total}}</span></td>
                                                        <td><span class="text-success subtotal123"><i class="fas fa-rupee-sign"></i> {{$item->total_price}}</span></td>
                                                    </tr>
                                                </tbody>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-sm-12">
                                    <div class="third-step-details2">
                                        <div><h5>Billing Summary</h5></div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <th>Subtotal</th>
                                                        <th class="td-data"> 
                                                            <span class="text-success subtotal123"><i class="fas fa-rupee-sign"></i> {{$guest_master->sub_total}}</span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td> Tax (GST 18%)</td>
                                                        <td class="td-data">
                                                            <input type="hidden" id="tax" name="tax" value="18">
                                                            <span class="text-success"> <i class="fas fa-rupee-sign"></i> {{number_format(($guest_master->sub_total * 18)/100,2)}}</span>
                                                        </td>
                                                    </tr>
                                                    @if($guest_master->promo_code_id!=NULL)
                                                        <?php $promocode=Helper::get_promo_code($guest_master->promo_code_id);?>
                                                        <tr>
                                                            <td class="dis_type">Discount @if($promocode->discount_type=='fixed_amount')<span>(Fixed Amount)</span>@else<span>(Percentage - {{$promocode->discount}}%)</span>@endif</td>
                                                            <?php 
                                                                $total_price_tax = 0;
                                                                $total_price_tax = number_format($guest_master->sub_total + ($guest_master->sub_total * 18)/100,2); 
                                                            ?>

                                                            <td class="text-success" id="discount">
                                                                @if($promocode->discount_type=='fixed_amount')
                                                                    <i class="fas fa-rupee-sign"></i> {{$promocode->discount}}
                                                                @else
                                                                    <i class="fas fa-rupee-sign"></i> {{number_format(($total_price_tax * $promocode->discount)/100,2)}}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr class="dis_type">
                                                            <td> Discount</td>
                                                            <td class="td-data" id="discount">₹ 0.00</td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <input type="hidden" id="total_price" name="total_price" value="{{$guest_master->total_price==$guest_master->sub_total ? number_format($guest_master->total_price + (($guest_master->total_price * 18)/100),2) : $guest_master->total_price}}">
                                                        <td> <b>Total</b></td>
                                                        <td class="td-data">
                                                            <strong class="price123 total_p" id="total"><i class="fas fa-rupee-sign"></i> {{$guest_master->total_price==$guest_master->sub_total ? number_format($guest_master->total_price + (($guest_master->total_price * 18)/100),2) : $guest_master->total_price}}</strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                           
                                        @if($guest_master->promo_code_id!=NULL)  
                                            <?php $promocode=Helper::get_promo_code($guest_master->promo_code_id);?>
                                            <div class="apply-block promo_result">
                                                <p class="promocodeshow"><span class="mypromo">{{$promocode->title}}</span><i class="fa fa-times remove_promo" aria-hidden="true"></i></p>
                                                <div class="promonew mt-4">
                                                    <span class="badge badge-success">Applied</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="apply-block promo_result">
                                                <input type="text" class="promonumber promocode" id="promocode" name="promocode" placeholder="Promo code" autocomplete="off">
                                                <button type="button" class="btn btn-info promo text-white"><span class="apply1">Apply</span></button>
                                                <br>
                                                <p style="margin-bottom: 2px;margin-top: 10px;" class="text-danger error_container" id="error-promocode"></p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="totalprice">
                                {{-- <a href="verification-step-4.php"><button>Next</button></a> --}}
                                <button type="submit" class="btn btn-danger" style="padding: 6px 10px;margin: 18px 0px;font-size:16px;">Checkout </button>
                            </div>
                        </form>
                    @endif    
                </div>
            </div>
    {{-- </div>
   </div> --}}
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
</div>
</div>
<script>
   // alert("hi");
   $(document).ready(function(){

       $(document).on('keyup','.promocode',function(){
         var value=$(this).val();
         value=value.toUpperCase();
         $(this).val(value);
       });

       $(document).on('click', '.promo', function (event) {
           $('p.error_container').html("");
           var token=$(this).attr('data-token');
           var promocode=$('#promocode').val();
           var total_price=$('#total_price').val();
           var guest_master_id=$('#guest_master_id').val();
           var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
           $('.promo').attr('disabled',true);
           if($('.promo span.apply1').html!=loadingText)
           {
               $('.promo span.apply1').html(loadingText);
           }
           $.ajax({
               type:'POST',
               url: "{{route('/verify/instant_verification/add_promocode')}}",
               data: {"_token": "{{ csrf_token() }}",'promocode':promocode,'total_price':total_price,'guest_master_id':guest_master_id},        
               success: function (response) {        
               console.log(response);
               window.setTimeout(function(){
                       $('.promo').attr('disabled',false);
                       $('.promo span.apply1').html('Apply');
                   },2000);
                   if(response.success==false ) {                              
                       for (control in response.errors) {  
                           // $('.'+control).addClass('border-danger'); 
                           $('#error-' + control).html(response.errors[control]);
                       }
                   }
                   else if(response.success==true ) {                              
                       var total_price=response.total_price;
                       var discount=response.discount;
                       var discount_value=response.discount_value;
                       var title=response.title;

                     if(response.type=='fixed_amount')
                     {
                        $('#discount').html('<i class="fas fa-rupee-sign"></i> '+discount_value);
                        $('.dis_type').html('Discount ('+response.dis_type+')');
                     }
                     else
                     {
                        $('#discount').html('<i class="fas fa-rupee-sign"></i> '+discount_value);
                        $('.dis_type').html('Discount ('+response.dis_type+' - <span>'+discount+'%</span>)');
                     }
                       $('#total_price').val(total_price);
                       $('#total').html('<i class="fas fa-rupee-sign"></i> '+total_price);

                     //   $('.promo_result').html('<td>Promocode</td><td><span class="text-success">'+title+'</span></td><td><span class="badge badge-success">Applied</span></td>');

                       $('.promo_result').html('<p class="promocodeshow"><span class="mypromo">'+title+'</span><i class="fa fa-times remove_promo" aria-hidden="true"></i></p> <div class="promonew mt-4"> <span class="badge badge-success">Applied</span> </div>');
                   }
               },
               error: function (xhr, textStatus, errorThrown) {
                  //  alert("Error: " + errorThrown);
               }
           });
       });

       $(document).on('click', '.remove_promo', function (event) {
           
           var guest_master_id=$('#guest_master_id').val();
         //   if(confirm("Are you sure want to remove this promocode ?")){
         //    $.ajax({
         //          type:'POST',
         //          url: "{{route('/verify/instant_verification/remove_promocode')}}",
         //          data: {"_token": "{{ csrf_token() }}",'guest_master_id':guest_master_id},        
         //          success: function (response) {        
         //          // console.log(response);
         //             if(response.success==true ) {                              
         //                var total_price=response.total_price;

         //                $('#discount').html('0.00');
         //                $('.dis_type').html('Discount');
         //                $('#total_price').val(total_price);
         //                $('#total').html('<i class="fas fa-rupee-sign"></i> '+total_price);

         //                $('.promo_result').html('<input type="text" class="form-control promonumber promocode" id="promocode" name="promocode" placeholder="Promo code" autocomplete="off"> <div class="input-group-append promonew"> <button type="button" class="btn btn-secondary promo"><span class="apply1">Apply</span></button> </div> <p style="margin-bottom: 2px;margin-top: 10px;" class="text-danger error_container" id="error-promocode"></p>');
         //             }
         //          },
         //          error: function (response) {
         //             // alert("Error: " + errorThrown);
         //          }
         //    });
         //   }
         //   return false;

           swal({
                  // icon: "warning",
                  type: "warning",
                  title: "Are you sure want to remove this promocode ?",
                  text: "",
                  dangerMode: true,
                  showCancelButton: true,
                  confirmButtonColor: "#007358",
                  confirmButtonText: "YES",
                  cancelButtonText: "CANCEL",
                  closeOnConfirm: false,
                  closeOnCancel: false
                  },
                  function(e){
                     if(e==true)
                     {
                        $.ajax({
                              type:'POST',
                              url: "{{route('/verify/instant_verification/remove_promocode')}}",
                              data: {"_token": "{{ csrf_token() }}",'guest_master_id':guest_master_id},        
                              success: function (response) {        
                              // console.log(response);
                                 if(response.success==true ) {                              
                                    var total_price=response.total_price;

                                    $('#discount').html('0.00');
                                    $('.dis_type').html('Discount');
                                    $('#total_price').val(total_price);
                                    $('#total').html('<i class="fas fa-rupee-sign"></i> '+total_price);

                                    $('.promo_result').html('<input type="text" class="promonumber promocode" id="promocode" name="promocode" placeholder="Promo code" autocomplete="off"><button type="button" class="btn btn-info promo text-white"><span class="apply1">Apply</span></button><br><p style="margin-bottom: 2px;margin-top: 10px;" class="text-danger error_container" id="error-promocode"></p>');
                                 }
                              },
                              error: function (response) {
                                 // alert("Error: " + errorThrown);
                              }
                        });
                        swal.close();
                     }
                     else
                     {
                        swal.close();
                     }
                  }
           );

       });

       $(document).on('click','.remove_all',function(){
            var _this=$(this);
            // var result=confirm("Are You Sure You Want to Remove the Summary?");
            var id = $('#guest_master_id').val();
            // if(result){
            //     _this.addClass('disabled-link');
            //     $.ajax({
            //          type: "POST",
            //          dataType: "json",
            //          url: '{{url('/verify/instant_verification/services/delete_all')}}',
            //          data: {"_token": "{{ csrf_token() }}",'id': id},
            //          success: function(data){
            //             console.log(data);
            //             window.setTimeout(function(){
            //             _this.removeClass('disabled-link');
            //             },2000);
                        
            //             if(data.success==true)
            //             {
            //                   toastr.success('Record Deleted Successfully');
            //                   window.setTimeout(function(){
            //                      window.location="{{url('/verify/')}}"+"/instant_verification";
            //                   },2000);
            //             }
            //          }
            //     });
            // }
            // else{
            //     return false;
            // }

            swal({
               // icon: "warning",
               type: "warning",
               title: "Are You Sure You Want to Remove the Summary?",
               text: "",
               dangerMode: true,
               showCancelButton: true,
               confirmButtonColor: "#007358",
               confirmButtonText: "YES",
               cancelButtonText: "CANCEL",
               closeOnConfirm: false,
               closeOnCancel: false
               },
               function(e){
                  if(e==true)
                  {
                     _this.addClass('disabled-link');
                     $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: '{{url('/verify/instant_verification/services/delete_all')}}',
                        data: {"_token": "{{ csrf_token() }}",'id': id},
                        success: function(data){
                           console.log(data);
                           window.setTimeout(function(){
                           _this.removeClass('disabled-link');
                           },2000);
                           
                           if(data.success==true)
                           {
                                 toastr.success('Record Deleted Successfully');
                                 window.setTimeout(function(){
                                    window.location="{{url('/verify/')}}"+"/instant_verification";
                                 },2000);
                           }
                        }
                     });
                     swal.close();
                  }
                  else
                  {
                     swal.close();
                  }
               }
            );
       });

       $(document).on('click','.remove_btn',function(){
            var _this=$(this);
            // var result=confirm("Are You Sure You Want to Remove this Check?");
            var id = $(this).data('id');
            var service_id=$(this).attr('data-service');
            var guest_master_id = $('#guest_master_id').val();

            // if(result){
            //     _this.addClass('disabled-link');
            //     $.ajax({
            //     type: "POST",
            //     dataType: "json",
            //     url: '{{url('/verify/instant_verification/services/delete_service')}}',
            //     data: {"_token": "{{ csrf_token() }}",'id': id,'service_id':service_id,'guest_master_id':guest_master_id},
            //     success: function(data){
            //         console.log(data);
            //         window.setTimeout(function(){
            //         _this.removeClass('disabled-link');
            //         },2000);
                    
            //         if(data.success==true)
            //         {
            //             toastr.success('Record Deleted Successfully');
            //             if(data.db==false)
            //             {
            //                 window.setTimeout(function(){
            //                     window.location.reload();
            //                 },2000);
            //             }
            //             else if(data.db==true)
            //             { 
            //                 window.setTimeout(function(){
            //                     window.location="{{url('/verify/')}}"+"/instant_verification";
            //                 },2000);
            //             }
            //         }
            //     }
            //     });
            // }
            // else{
            //     return false;
            // }

            swal({
            // icon: "warning",
               type: "warning",
               title: "Are You Sure You Want to Remove this Check?",
               text: "",
               dangerMode: true,
               showCancelButton: true,
               confirmButtonColor: "#007358",
               confirmButtonText: "YES",
               cancelButtonText: "CANCEL",
               closeOnConfirm: false,
               closeOnCancel: false
               },
               function(e){
                  if(e==true)
                  {
                     _this.addClass('disabled-link');
                     $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: '{{url('/verify/instant_verification/services/delete_service')}}',
                        data: {"_token": "{{ csrf_token() }}",'id': id,'service_id':service_id,'guest_master_id':guest_master_id},
                        success: function(data){
                           console.log(data);
                           window.setTimeout(function(){
                           _this.removeClass('disabled-link');
                           },2000);
                           
                           if(data.success==true)
                           {
                                 toastr.success('Record Deleted Successfully');
                                 if(data.db==false)
                                 {
                                    window.setTimeout(function(){
                                       window.location.reload();
                                    },2000);
                                 }
                                 else if(data.db==true)
                                 { 
                                    window.setTimeout(function(){
                                       window.location="{{url('/verify/')}}"+"/instant_verification";
                                    },2000);
                                 }
                           }
                        }
                     });
                     swal.close();
                  }
                  else
                  {
                     swal.close();
                  }
               }
            );
       });
      
   });
       
</script>   

@endsection
