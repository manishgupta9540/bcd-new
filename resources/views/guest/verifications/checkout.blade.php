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
   border: 1px solid #663399;
   background-color: #663399;
   /* padding-left: 20px;
   padding-right: 30px; */
   }
   .input-group-append.promonew button {
    background: #663399!important;
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
   @media (min-width: 576px) and (max-width: 767.98px) { 
   .col-md-5.order-md-2.mb-4 {
   position: relative;
   left: 0px;
   padding: 20px;
   }
   .container.shippingdetail {
   margin-top: 0px;
   }
   }
   @media (max-width: 575.98px) { 
   .col-md-5.order-md-2.mb-4 {
   position: relative;
   left: 0px;
   padding: 20px;
   }
   .container.shippingdetail {
   margin-top: 0px;
   }
   }
</style>

<!-- ============ Search UI End ============= -->

<!-- =============== Left side End ================-->
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
            <li><a href="{{ url('/guest/home') }}">Dashboard</a></li>
            <li><a href="{{ url('/guest/candidates') }}">Candidate</a></li>
            <li><a href="{{ route('/guest/candidates/verification',['id'=>base64_encode($guest_v->candidate_id)]) }}">Verification</a></li>
            <li>Checkout</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow">
             <div class="text-right">
             <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div>
      <div class="row">
         <div class="card text-left">
            <div class="card-body">
               <h3 class="card-title mb-3 verifying1 mx-auto" style="font-size: 19px"> Shopping Bag </h3>
               <h3 class="card-title mb-3 verifying1 mx-auto" style="font-size: 16px"> Candidate Details: </h3>
               <div class="row requestrow1 mx-auto">
                  <div class="col-md-4">
                     <div class="form-group ">
                        <label>Name :</label>
                        <input type="text" class="form-control" value="{{$candidate->name}}" readonly>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Email :</label>
                        <input type="email" class="form-control" value="{{$candidate->email}}" readonly>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Contact Number :</label>
                        <input type="text" class="form-control" value="{{$candidate->phone}}" readonly>
                     </div>
                  </div>
               </div>
               <hr>
               <?php $guest_v_s=Helper::get_guest_verification_services($guest_v->id) ?>
               @if($guest_v_s!=NULL && count($guest_v_s)>0)
                  <form method="post" id="verificationForm" action="{{ url('/guest/candidates/verification/checkout/store') }}" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" name="gv_id" id="gv_id" value="{{base64_encode($guest_v->id)}}">
                     <input type="hidden" name="candidate_id" value="{{base64_encode($guest_v->candidate_id)}}">
                     <div class="row cartone">
                        <div class="col-md-5 order-md-2 mb-4">
                           <h4 class="d-flex justify-content-between mb-3">
                              <span class="text-muted">Summary</span>
                           </h4>
                           <ul class="list-group mb-3">
                              <li class="list-group-item d-flex justify-content-between">
                                 <div class="text-success">
                                    <h6 class="my-0 subtotal">Subtotal</h6>
                                 </div>
                                 <span class="text-success subtotal123"><i class="fas fa-rupee-sign"></i> {{$guest_v->sub_total}}</span>
                              </li>
                              <li class="list-group-item d-flex justify-content-between">
                                 <div class="text-success">
                                    <h6 class="my-0 shipping">Tax</h6>
                                 </div>
                                 <span class="text-success">0</span>
                              </li>
                              @if($guest_v->promo_code_id!=NULL)
                                 <?php $promocode=Helper::get_promo_code($guest_v->promo_code_id);?>
                                 <li class="list-group-item d-flex justify-content-between">
                                    <div class="text-success">
                                       <h6 class="my-0 shipping">Discount</h6>
                                    </div>
                                    <span class="text-success" id="discount">
                                       @if($promocode->discount_type=='fixed_amount')
                                          <i class="fas fa-rupee-sign"></i> {{$promocode->discount}}
                                       @else
                                          {{$promocode->discount}} %
                                       @endif
                                    </span>
                                 </li>
                              @else
                                 <li class="list-group-item d-flex justify-content-between">
                                    <div class="text-success">
                                       <h6 class="my-0 shipping">Discount</h6>
                                    </div>
                                    <span class="text-success" id="discount">0</span>
                                 </li>
                              @endif
                           </ul>
                           @if($guest_v->promo_code_id!=NULL)
                              <?php $promocode=Helper::get_promo_code($guest_v->promo_code_id);?>
                              <div class="input-group promocode12 promo_result">
                                 <p class="promocodeshow"><span class="mypromo">{{$promocode->title}}</span><i class="fa fa-times remove_promo" aria-hidden="true"></i></p>
                                 
                                 <div class="promonew mt-4">
                                    <span class="badge badge-success">Applied</span>
                                 </div>
                              </div>
                           @else
                              <div class="input-group promocode12 promo_result">
                                 <input type="text" class="form-control promonumber promocode" id="promocode" name="promocode" placeholder="Promo code" autocomplete="off">
                                 <div class="input-group-append promonew">
                                    <button type="button" class="btn btn-secondary promo text-white"><span class="apply1">Apply</span></button>
                                 </div><br>
                                 <p style="margin-bottom: 2px;margin-top: 10px;" class="text-danger error_container" id="error-promocode"></p>
                              </div>
                           @endif
                           <ul class="list-group mb-3 totalamt">
                              <li class="list-group-item d-flex justify-content-between">
                                 <input type="hidden" id="total_price" name="total_price" value="{{$guest_v->total_price}}">
                                 <span class="totalprice">Total </span>
                                 <strong class="price123" id="total"><i class="fas fa-rupee-sign"></i> {{$guest_v->total_price}}</strong>
                              </li>
                           </ul>
                           <button type="submit" class="btn btn-primary" style="width: 100%;padding: 14px;margin: 18px 0px;font-size:16px;">Checkout </button>
                        </div>
                        <div class="col-md-7 order-md-1">
                           <h4 class="d-flex justify-content-between mb-3">
                              <span class="text-muted">Summary</span>
                           </h4>
                           <ul class="list-group mb-3 totalamt">
                              <li class="list-group-item d-flex justify-content-between">
                                 <strong class="price123">Checks</strong>
                                 <strong class="price123">Price</strong>
                              </li>
                              @foreach($guest_v_s as $guest_item)
                                 <li class="list-group-item d-flex justify-content-between">
                                    <div class="text-success">
                                       <h6 class="my-0 subtotal checkservices">{{Helper::get_service_name($guest_item->service_id)}}</h6>
                                       <?php
                                             $service_data_array=json_decode($guest_item->service_number,true);
                                       ?>
                                       @foreach($service_data_array as $key => $value)
                                          <?php
                                             $k_arr=explode('_',$key);
                                             $keys=implode(' ',$k_arr);
                                          ?>
                                          <span>{{ucwords($keys)}} : {{$value}}</span><br>
                                       @endforeach
                                    </div>
                                    <span class="text-success subtotal123"><i class="fas fa-rupee-sign"></i> {{$guest_item->price}}</span>
                                 </li>
                              @endforeach
                           </ul>
                        </div>
                     </div>
                  </form>
               @endif
            </div>
         </div>
      </div>
   </div>
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
           var data=$('#promocode').val();
           var total_price=$('#total_price').val();
           var guest_id=$('#gv_id').val();
           var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
           $('.promo').attr('disabled',true);
           if($('.promo span.apply1').html!=loadingText)
           {
               $('.promo span.apply1').html(loadingText);
           }
           $.ajax({
               type:'POST',
               url: "{{route('/guest/candidates/verification/promocode')}}",
               data: {"_token": "{{ csrf_token() }}",'promocode':data,'total_price':total_price,'gv_id':guest_id},        
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
                       var title=response.title;

                        if(response.type=='fixed_amount')
                        {
                           $('#discount').html('<i class="fas fa-rupee-sign"></i> '+discount);
                        }
                        else
                        {
                           $('#discount').html(discount+' %');
                        }

                       $('#total_price').val(total_price);
                       $('#total').html('<i class="fas fa-rupee-sign"></i> '+total_price);

                     //   $('.promo_result').html('<td>Promocode</td><td><span class="text-success">'+title+'</span></td><td><span class="badge badge-success">Applied</span></td>');

                       $('.promo_result').html('<p class="promocodeshow"><span class="mypromo">'+title+'</span><i class="fa fa-times remove_promo" aria-hidden="true"></i></p> <div class="promonew mt-4"> <span class="badge badge-success">Applied</span> </div>');
                   }
               },
               error: function (xhr, textStatus, errorThrown) {
                   alert("Error: " + errorThrown);
               }
           });
       });

       $(document).on('click', '.remove_promo', function (event) {
           
           var guest_id=$('#gv_id').val();
           if(confirm("Are you sure want to remove this promocode ?")){
            $.ajax({
                  type:'POST',
                  url: "{{route('/guest/candidates/verification/remove_promocode')}}",
                  data: {"_token": "{{ csrf_token() }}",'gv_id':guest_id},        
                  success: function (response) {        
                  console.log(response);
                     if(response.success==true ) {                              
                        var total_price=response.total_price;

                        $('#discount').html('0');
                        $('#total_price').val(total_price);
                        $('#total').html('<i class="fas fa-rupee-sign"></i> '+total_price);

                        $('.promo_result').html('<input type="text" class="form-control promonumber promocode" id="promocode" name="promocode" placeholder="Promo code" autocomplete="off"> <div class="input-group-append promonew"> <button type="button" class="btn btn-secondary promo"><span class="apply1">Apply</span></button> </div> <p style="margin-bottom: 2px;margin-top: 10px;" class="text-danger error_container" id="error-promocode"></p>');
                     }
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     alert("Error: " + errorThrown);
                  }
            });
           }
           return false;

       });
      
   });
       
</script>   

@endsection
