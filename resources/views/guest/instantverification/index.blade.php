@extends('layouts.guest') 
@section('content') 
<style type="text/css">
   input,
   textarea {
   border: 1px solid #eeeeee;
   box-sizing: border-box;
   margin: 0;
   outline: none;
   padding: 10px;
   }
   .add-cart {
   position: relative;
   /* left: 136px; */
   top: -2px;
   }
   .add-item {
   margin-top: 11px;
   margin-left: 5px;
   }
   .value-increase{
   position: relative;
   left: 14px;
   }
   input[type="button"] {
   -webkit-appearance: button;
   cursor: pointer;
   }
   input::-webkit-outer-spin-button,
   input::-webkit-inner-spin-button {
   -webkit-appearance: none;
   }
   .input-group {
   clear: both;
   /* margin: 15px 0; */
   position: relative;
   }
   .input-group input[type='button'] {
   background-color: #eeeeee;
   min-width: 38px;
   width: auto;
   transition: all 300ms ease;
   }
   .input-group .button-minus,
   .input-group .button-plus {
   font-weight: bold;
   height: 38px;
   padding: 0;
   width: 38px;
   position: relative;
   }
   .input-group .quantity-field {
   position: relative;
   height: 38px;
   left: -6px;
   text-align: center;
   width: 62px;
   display: inline-block;
   font-size: 13px;
   margin: 0 0 5px;
   resize: vertical;
   }
   .button-plus {
   left: -13px;
   }
   input[type="number"] {
   -moz-appearance: textfield;
   -webkit-appearance: none;
   }
   .form-group label {
   font-size: 16px;
   font-weight: 600;
   color: #002e62;
   margin-bottom: 4px;
   }
   .form-control {
   border: initial;
   outline: initial !important;
   background: #fff;
   border: 1px solid #ced4da;
   color: #47404f;
   }
   .col-md-6 {
   margin-top: 10px;
   }
   .form-control:focus {
   color: #665c70;
   background-color: #fff;
   border-color: #ced4da;
   outline: 0;
   box-shadow: 0 0 0 0.2rem rgb(255 255 255);
   }
   .disabled-link
   {
   pointer-events: none;
   }
   .button-plus
   {
      height: 35px;
      font-size: 10px;
   }
   .button-minus
   {
      font-size: 10px;
      height: 35px;
   }
   .id-det-block
   {
      height: 120px;
   }
   .id-name h6
   {
      font-size: 15px;
   }

   .btn-info {
    color: #fff;
    background-color: #003473;
    border-color: #003473;
}
   .submit_btn:hover
   {
      background-color:#e10813 !important;
   }
   .fa-rupee-sign
   {
      font-size: 20px;
   }
   .button-minus
   {
      padding: 0px 8px;
   }
   .id-det-block img
   {
      width:50px !important;
   }
   .id-name
   {
      width: 100px;
      margin-top: 10px;
   }
   .id-name p {
    font-size: 11px;
    margin-bottom: 0px;
    font-weight: 500;
}
   .mindes{
      border-radius: 10px; 
      background-color: #003473;
      border-color: none;
   }
   .plusdes{
      border-radius: 10px; 
      padding:7px; 
      background-color: #003473;
   }
</style>
<!-- =============== Left side End ================-->
      <div class="col-md-10 col-sm-12 marg-des">
         <div class="verification-detail-1">
            <div class="logosection">
               <p><span>Instant Verification</span><br> Choose Your Check Item</p>
               <p>Government approved APIs * : <img src="{{asset('admin/microsite/images/udiai1.png')}}"></p>
            </div>
            <div class="verification-1-button">
               <a href="{{url('verify/instant_verification')}}"><button class="verfication-top-tab activeclass">Choose items</button></a>
               <a href="javascript:void(0)"><button class="verfication-top-tab">Enter details</button></a>
               <a href="javascript:void(0)"><button class="verfication-top-tab">Make payment</button></a>
               <a href="javascript:void(0)"><button class="verfication-top-tab">View results</button></a>
            </div>
            <form class="mt-2" method="post" id="addCartForm" action="{{ url('/verify/instant_verification/store') }}" enctype="multipart/form-data">
               @csrf
               <div class="row">
                  @foreach ($services as $service)
                        <?php
                           $guest_check_price=Helper::get_instant_check_price(Auth::user()->parent_id,$service->id);
                           
                           $sample_url='#';
                           $download = '';
                           $label_name = '';
                           $initial_price = 0;
                           $image_url = '';
                           if(stripos($service->name,'Aadhar')!==false)
                           {
                              $sample_url=url('/verify/').'/sample/aadhar.pdf';
                              $download='download';
                              $initial_price = 75;
                              $label_name = 'Name, Aadhar Validity';
                              $image_url = asset('admin/microsite/images/aadhaar.png');
                           }
                           elseif(stripos($service->name,'PAN')!==false)
                           {
                              $sample_url=url('/verify/').'/sample/pan.pdf';
                              $download='download';
                              $initial_price = 75;
                              $label_name = 'Name, PAN Validity';
                              $image_url = asset('admin/microsite/images/pan.png');
                           }
                           elseif(stripos($service->name,'Voter ID')!==false)
                           {
                              $sample_url=url('/verify/').'/sample/voter.pdf';
                              $download='download';
                              $initial_price = 100;
                              $label_name = 'Name, Voter ID Validity';
                              $image_url = asset('admin/microsite/images/voter-id.png');
                           }
                           elseif(stripos($service->name,'RC')!==false)
                           {
                              $sample_url=url('/verify/').'/sample/rc.pdf';
                              $download='download';
                              $initial_price = 100;
                              $label_name = 'Name, RC Validity';
                              $image_url = asset('admin/microsite/images/rc.png');
                           }
                           elseif(stripos($service->name,'Passport')!==false)
                           {
                              $sample_url=url('/verify/').'/sample/passport.pdf';
                              $download='download';
                              $initial_price = 100;
                              $label_name = 'Name, Passport Validity';
                              $image_url = asset('admin/microsite/images/passport.png');
                           }
                           elseif(stripos($service->name,'Driving')!==false)
                           {
                              $sample_url=url('/verify/').'/sample/dl.pdf';
                              $download='download';
                              $initial_price = 100;
                              $label_name = 'Name, DL Validity';
                              $image_url = asset('admin/microsite/images/dl.png');
                           }
                           elseif(stripos($service->name,'Bank Verification')!==false)
                           {
                              $sample_url=url('/verify/').'/sample/bank.pdf';
                              $download='download';
                              $initial_price = 100;
                              $label_name = 'Name, Bank Account Validity';
                              $image_url = asset('admin/microsite/images/passbook.png');
                           }
                           elseif(stripos($service->name,'E-Court')!==false)
                           {
                              $sample_url=url('/verify/').'/sample/e_court.pdf';
                              $download='download';
                              $initial_price = 50;
                              $label_name = 'Name, E-Court Validity';
                              $image_url = asset('admin/microsite/images/pan.png');
                           }
                           elseif(stripos($service->name,'UPI Verification')!==false)
                           {
                              $sample_url=url('/verify/').'/sample/upi.pdf';
                              $download='download';
                              $initial_price = 100;
                              $label_name = 'UPI ID, UPI Validity';
                              $image_url = asset('admin/microsite/images/upi.png');
                           }
                           elseif(stripos($service->type_name,'cin')!==false)
                           {
                              $sample_url=url('/verify/').'/sample/cin.pdf';
                              $download='download';
                              $initial_price = 100;
                              $label_name = 'CIN Number, CIN Validity';
                              $image_url = asset('admin/microsite/images/cin.png');
                           }
                        ?>
                     @if($guest_master!=NULL)
                        <?php 
                        $guest_cart=Helper::get_instant_cart(Auth::user()->business_id,$service->id,$guest_master->id);
                        
                        $guest_cart_services_data=DB::table('guest_instant_cart_services')
                                    ->where(['giv_m_id'=>$guest_master->id,'service_id'=>$service->id])
                                    ->whereNotNULL('service_data')
                                    ->get();
                                    //dd($guest_cart_services_data);
                        ?>
                        <div class="col-md-4 col-sm-12" style="margin-top: 24px;">
                           <div class="id-det-block selected">
                              <input type="checkbox" class="services_list selectedcehckbox {{count($guest_cart_services_data)>0 ? 'disabled-link' : ''}}" name="services[]" value="{{$service->id}}" data-string="{{stripos($service->name,'Driving')!==false ? 'Driving License' : $service->name}}" id="service-{{$service->id}}" data-verify="{{$service->verification_type}}" @if($guest_cart!=NULL) checked @endif>
                              {{-- <input type="checkbox" class="services_list selectedcehckbox" name="services[]" value="{{$service->id}}" data-string="{{stripos($service->name,'Driving')!==false ? 'Driving License' : $service->name}}" data-service="{{$service->id}}" id="service-{{$service->id}}" data-verify="{{$service->verification_type}}"> --}}
                              <img src="{{$image_url}}" style="width:80px;">
                              <div class="id-name" style="width: 100px;">
                                 <h6>{{stripos($service->name,'Driving')!==false ? 'Driving License' : $service->name}}</h6>
                                 <p>({{$label_name}})</p>
                                 <h4>₹ {{$guest_check_price!=NULL ? intval($guest_check_price->price) : $initial_price}}</h4>
                              </div>
                              <div class="noofverifcation input-qty">
                                 <input type="button" value="-" class="button-minus btn btn-info minussign mindes" data-id="{{$service->id}}" data-field="count-{{$service->id}}" >
                                 <input type="number" name="count-{{$service->id}}" class="quantity-field quantity-num count count-{{$service->id}}" id="count-{{$service->id}}" @if($guest_cart!=NULL) value="{{$guest_cart->number_of_verification}}" @else value="1" readonly @endif @if(count($guest_cart_services_data)>0 && $guest_cart!=NULL) min="{{$guest_cart->number_of_verification}}" @else min="1" @endif>
                                 <input type="button" value="+" class="button-plus btn-info plusdes" data-id="{{$service->id}}" data-field="count-{{$service->id}}">
                                 <input type="hidden" id="initial-price-{{$service->id}}" name="initial-price-{{$service->id}}" value="{{$guest_check_price!=NULL ? intval($guest_check_price->price) : $initial_price }}">
                                 <input type="hidden" id="price-{{$service->id}}" name="price-{{$service->id}}" value="{{$guest_check_price!=NULL ? intval($guest_check_price->price) : $initial_price}}">
                              </div>
                           </div>
                        </div>
                     @else
                        <div class="col-md-4 col-sm-12" style=" margin-top: 24px;">
                           <div class="id-det-block selected">
                              <input type="checkbox" class="services_list selectedcehckbox" name="services[]" value="{{$service->id}}" data-string="{{stripos($service->name,'Driving')!==false ? 'Driving License' : $service->name}}" id="service-{{$service->id}}" data-verify="{{$service->verification_type}}">
                              <img src="{{$image_url}}" style="width:80px;">
                              <div class="id-name">
                                 <h6> <span>{{stripos($service->name,'Driving')!==false ? 'Driving License' : $service->name}}</h6>
                                 <p>({{$label_name}})</p>
                                 <h4>₹ {{$guest_check_price!=NULL ? intval($guest_check_price->price) : $initial_price}}</h4>
                              </div>
                              <div class="noofverifcation input-qty">
                                 <input type="button" value="-" class="button-minus btn btn-info minussign mindes" data-id="{{$service->id}}" data-field="count-{{$service->id}}">
                                 <input type="number" min="1" value="1" name="count-{{$service->id}}" class="quantity-field quantity-num count count-{{$service->id}}" id="count-{{$service->id}}" readonly>
                                 <input type="button" value="+" class="button-plus btn-info plusdes" data-id="{{$service->id}}" data-field="count-{{$service->id}}" >
                                 <input type="hidden" id="initial-price-{{$service->id}}" name="initial-price-{{$service->id}}" value="{{$guest_check_price!=NULL ? intval($guest_check_price->price) : $initial_price }}">
                                 <input type="hidden" id="price-{{$service->id}}" name="price-{{$service->id}}" value="{{$guest_check_price!=NULL ? intval($guest_check_price->price) : $initial_price}}">
                              </div>
                           </div>
                        </div>
                     @endif
                  @endforeach
               </div>
               <div class="totalprice">
                     <h4 id="total">
                        <strong>Total Price : </strong> 
                        @if($guest_master === null)
                           <b><span id="total_sum_value"><i class="fas fa-rupee-sign"></i> 0.00</span></b>
                        @else
                           <b><span id="total_sum_value"><i class="fas fa-rupee-sign"></i>{{$guest_master->sub_total}}</span></b>
                        @endif
                     </h4>
                     {{-- <p>View details <img src="{{asset('admin/microsite/images/vector.png')}}"></p> --}}
                  <div class="submit text-right">
                     <button type="submit" class="btn btn-success submit_btn" style="width: 10%;padding: 8px;margin: 18px 0px;font-size:16px;">Checkout</button>
                  </div>
               </div>
            
            </form>
         </div>
      </div>
<!-- Footer Start -->
<div class="flex-grow-1"></div>
</div>
</div><!-- ============ Search UI Start ============= -->
<!-- ============ Search UI End ============= -->
<script>
   $(document).ready(function(){
   
   	$('#addCartForm')[0].reset();
   	$('.count').css({'-moz-appearance':'textfield'});
   	$('.services_list').change(function(){
   		var _this=$(this);
   		var id=_this.val();
   		var min = $('#count-'+id+'').attr('min');
		  
   		if(this.checked)
   		{
   			$('#count-'+id+'').attr('readonly',false);
			   
   		}
   		else
   		{
   			 $('#count-'+id+'').attr('readonly',true);
   			 $('#count-'+id+'').val(min);
			 
   			 var price = $('#initial-price-'+id+'').val();
   			 var result = price * (min);
            
			
   			 $('#price-'+id+'').val(result);
   			 $('#price_result-'+id+'').html(result.toFixed(2));
             
   		}
            var totalCheck = 0;
            var result=0;

           $('input[type=checkbox]').each(function() {
               if( $(this).is(':checked') ) {
                  var checkboxId =  $(this).closest('.selected').find('.selectedcehckbox').val();
                 
                  var price = $('#initial-price-'+checkboxId+'').val();
                  
                  var qty =  $(this).closest('.selected').find('.quantity-field').val();
                 
                  result += price * (qty);
               }
            });
      
             $("#total_sum_value").html(result.toFixed(2));
   
   });
   
          
          
    //    $('.count').change(function(){
    //        var _this=$(this);
    //        var id =_this.attr('data-id');
    //        var count = _this.val();
    //        var price = $('#initial-price-'+id+'').val();
    //        var result = price * count;
   
   		
    //        $('#price-'+id+'').val(result);
    //        $('#price_result-'+id+'').html(result.toFixed(2));
   
    //    });
   
   
      $(document).on('submit', 'form#addCartForm', function (event) {
         event.preventDefault();
         if(!$('#addCartForm input[type="checkbox"]').is(':checked')){
            swal("Please check at least one Checkbox.");
            return false;
         }
         //clearing the error msg
         $('p.error_container').html("");
         // $('.form-control').removeClass('border-danger');
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
         $('.submit_btn').attr('disabled',true);
         if($('.submit_btn').html!=loadingText)
         {
            $('.submit_btn').html(loadingText);
         }
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {
               window.setTimeout(function(){
                  $('.submit_btn').attr('disabled',false);
                  $('.submit_btn').html('Checkout');
               },2000);
   
               //console.log(response);
               if(response.success==true) {          
                  // window.location = "{{ url('/')}}"+"/sla/?created=true";
                  toastr.success('Selected Service has been Added to Cart');
                  // var order_id=response.order_id;
                  var guest_master_id=response.guest_master_id;
                  window.setTimeout(function(){
                     window.location="{{url('/verify/')}}"+"/instant_verification/services/"+guest_master_id;
                  },2000);
               }
               //show the form validates error
               if(response.success==false ) {                              
                  for (control in response.errors) {  
                     // $('.'+control).addClass('border-danger'); 
                     $('#error-' + control).html(response.errors[control]);
                  }
               }
            },
            error: function (response) {
               console.log(response);
            }
         //    error: function (xhr, textStatus, errorThrown) {
         //        console.log(errorThrown);
         //    }
         });
         return false;
      });
         
      $(document).on('click','.add_cart_btn',function (event){
            var _this = $(this);
            var id = _this.attr('data-id');
            var service_name = $('#service-'+id+'').attr('data-string');
            var services=[];
            if($('#service-'+id+'').prop('checked'))
            {   
               var i=0;
               $('.services_list:checked').each(function () {
                  services[i++] = $(this).val();
               });
               
               var initial_price = $('#initial-price-'+id+'').val();
      
               var price = $('#price-'+id+'').val();
      
               var count = $('#count-'+id+'').val();
      
      
               // var count_req = 'count-'+id;
      
               var data = {
                  "_token": "{{ csrf_token() }}",
                  "service_id":id,
                  "services" : services
               };
      
               data['count-'+id] = count;
      
               data['initial-price-'+id] = initial_price;
      
               data['price-'+id] = price;
               
               $.ajax({
                  type:'POST',
                  url: "{{ url('/verify/')}}"+"/instant_verification/addcartstore",
                  data: data,        
                  success: function (response) {  
                     if(response.success==true)
                     {
                        $('.cart_count').html(response.cart_count);
      
                        //  var service_count =  response.service_data_count;
      
                        //  if(service_count > 0)
                        //  {
                        // 	 $('.service_verify-'+id).addClass('disabled-link');
                        //  }
                     }      
                     if(response.success==false ) {                              
                        for (control in response.errors) {  
                           // $('.'+control).addClass('border-danger'); 
                           $('#error-' + control).html(response.errors[control]);
                        }
                     }
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
                  }
               });
               
            }
            else
            {
               swal({
                     title: 'Check the '+service_name+' Service First!!',
                     text: '',
                     type: 'warning',
                     buttons: true,
                     dangerMode: true,
                     confirmButtonColor:'#003473'
                     });
               //alert('Check the '+service_name+' Service First!!');
            }
      });
   
});
   
// function getTotal(e){
// 	var sum = 0;
// 	var id = $(e.target).data('id');
// 	var total = document.getElemntsById($('#count-'+id+'').val();)
// 	alert(total);

// }
   function incrementValue(e) {
   	 e.preventDefault();
   	 var id = $(e.target).data('id');
   	 var fieldName = $(e.target).data('field');
   	 var parent = $(e.target).closest('div');
   	 var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);
     
       var current = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

   	 var min = parent.find('input[name=' + fieldName + ']').attr('min');
   	
   	 if(!parent.find('input[name=' + fieldName + ']').attr('readonly'))
   	 {
   		 if (!isNaN(currentVal)) {
   			 parent.find('input[name=' + fieldName + ']').val(currentVal + 1);
   			 var price = $('#initial-price-'+id+'').val();
   			 var result = price * (currentVal + 1);

   			  $('#price-'+id+'').val(result);
   			  $('#price_result-'+id+'').html(result.toFixed(2));

			   var totalCheck = 0;
            var result=0;

           $('input[type=checkbox]').each(function() {
               if( $(this).is(':checked') ) {
                  var checkboxId =  $(this).closest('.selected').find('.selectedcehckbox').val();
                  var price = $('#initial-price-'+checkboxId+'').val();
                  
                  var qty =  $(this).closest('.selected').find('.quantity-field').val();
                 
                  result += price * (qty);
               }
            });
      
             $("#total_sum_value").html(result.toFixed(2));
          

				
				
   		 } 
   		 // else {
   		 //     parent.find('input[name=' + fieldName + ']').val(1);
   		 //     var price = $('#initial-price-'+id+'').val();
   		 //     var result = price * (currentVal + 1);
   		 //     $('#price-'+id+'').val(result);
   		 //     $('#price_result-'+id+'').html(result.toFixed(2));
   		 // }
   	 }
   	 
   	 
   }
   
    function decrementValue(e) {
   	 e.preventDefault();
   	 var id = $(e.target).data('id');
   	 // alert(id);
   	 var fieldName = $(e.target).data('field');
   	 var parent = $(e.target).closest('div');
   	 var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);
   	 var min = parent.find('input[name=' + fieldName + ']').attr('min');
   
   	 if(!parent.find('input[name=' + fieldName + ']').attr('readonly'))
   	 {
   		 if (!isNaN(currentVal) && currentVal - 1 >= min) {
   				 parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
   				 var price = $('#initial-price-'+id+'').val();
   				 var result = price * (currentVal - 1);
   				 $('#price-'+id+'').val(result);
   				 $('#price_result-'+id+'').html(result.toFixed(2));

                  var totalCheck = 0;
                  var result=0;

                  $('input[type=checkbox]').each(function() {
                     if( $(this).is(':checked') ) {
                        var checkboxId =  $(this).closest('.selected').find('.selectedcehckbox').val();
                        var qty =  $(this).closest('.selected').find('.quantity-field').val();
               
                        var price = $('#initial-price-'+checkboxId+'').val();
                        result += price * (qty);
                     }
                  });
                  $("#total_sum_value").html(result.toFixed(2));
   		 } else {
   				 parent.find('input[name=' + fieldName + ']').val(min);
   				 var price = $('#initial-price-'+id+'').val();
   				 var result = price * (min);
   				 $('#price-'+id+'').val(result);
   				 $('#price_result-'+id+'').html(result.toFixed(2));

               var totalCheck = 0;
               var result=0;

               $('input[type=checkbox]').each(function() {
                     if( $(this).is(':checked') ) {
                        var checkboxId =  $(this).closest('.selected').find('.selectedcehckbox').val();
                        var qty =  $(this).closest('.selected').find('.quantity-field').val();
               
                        var price = $('#initial-price-'+checkboxId+'').val();
                        result += price * (qty);
                     }
                  });
                  $("#total_sum_value").html(result.toFixed(2));
   		 }
   	 }
    }
   
    $('.input-qty').on('click', '.button-plus', function(e) {
         incrementValue(e);
    });
   
    $('.input-qty').on('click', '.button-minus', function(e) {
         decrementValue(e);
    });
   
    
</script>
@endsection