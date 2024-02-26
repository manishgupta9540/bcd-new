@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">SLA / Edit </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>
                 <a href="{{ url('/settings/general') }}">Accounts</a>
             </li>
             <li>
               <a href="{{ url('/sla') }}">SLA</a>
             </li>
             <li>Edit</li>
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
         
               <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                 @include('admin.accounts.left-sidebar') 
               </div>
               <!-- start right sec -->
               <div class="col-md-9 content-wrapper">
                  <div class="formCover" style="height: 100vh; background:#fff">
                     <!-- section -->
                     
                        <div class="col-sm-12 ">
                           
                              <!-- row -->
                              <div class="row">
                                 <div class="col-md-12">
                                    <h4 class="card-title mb-1 mt-3">Edit SLA </h4>
                                    <p class="pb-border"> Edit the SLA with Multiple Checks  </p>
                                 </div>
                                 <div class="col-md-12">
                                 <form method="post" action="{{ url('/settings/sla/update') }}" id="editSLAForm">
                                       @csrf
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label style="font-size: 14px;"> ID :</label>
                                             <label style="font-size: 14px;"> <b> {{ $sla->display_id!=NULL ? $sla->display_id : '--'}}  </b></label>
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label style="font-size: 14px;"> Customer :</label>
                                             <label style="font-size: 14px;"> <b> {{ ucfirst($sla->company_name) }}  </b></label>
                                             <input type="hidden" name="customer" value="{{ $sla->business_id }}">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label style="font-size: 14px;"> Days Type :</label>
                                             <label style="font-size: 14px;"> <b> {{ ucfirst($sla->days_type.' Days') }}  </b></label>
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label style="font-size: 14px;"> TAT Type :</label>
                                             <label style="font-size: 14px;"> <b> {{ ucfirst($sla->tat_type.'-'.'Wise') }}  </b></label>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label>SLA Name <span class="text-danger">*</span></label>
                                             <input class="form-control name" type="text" name="name" value="{{ $sla->title}}">
                                             {{-- @if ($errors->has('name'))
                                             <div class="error text-danger">
                                                {{ $errors->first('name') }}
                                             </div>
                                             @endif --}}
                                             <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-name"></p>
                                          </div>
                                       </div>   
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label>Internal TAT <span class="text-danger">*</span></label>
                                             <input class="form-control tat" type="text" name="tat" value="{{ $sla->tat}}">
                                             {{-- @if ($errors->has('tat'))
                                             <div class="error text-danger">
                                                {{ $errors->first('tat') }}
                                             </div>
                                             @endif --}}
                                             <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tat"></p>
                                             <small class="text-muted">Days in number</small>
                                          </div>
                                       </div>  
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label>Client TAT<span class="text-danger">*</span></label>
                                             <input class="form-control client_tat" type="text" name="client_tat" value="{{ $sla->client_tat}}">
                                             {{-- @if ($errors->has('client_tat'))
                                             <div class="error text-danger">
                                                {{ $errors->first('client_tat') }}
                                             </div>
                                             @endif --}}
                                             <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-client_tat"></p>
                                             <small class="text-muted">Days in number</small>
                                          </div>
                                       </div>    
                                    </div>

                                    @if(stripos($sla->tat_type,'case')!==false)
                                       <div class="tat_result mb-2" style="border:1px solid #ddd;padding:10px;width:50%">
                                          <div class="row">
                                             <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Case-Wise Incentive & Penalty</div>
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Incentive <span class="text-danger">*</span> (<small class="text-muted">in %</small>)</label>
                                                   <input class="form-control" type="text" name="incentive" value="{{$sla->incentive}}">
                                                   <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-incentive"></p>
                                                </div>
                                             </div> 
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Penalty <span class="text-danger">*</span> (<small class="text-muted">in %</small>)</label>
                                                   <input class="form-control" type="text" name="penalty" value="{{$sla->penalty}}">
                                                   <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-penalty"></p>
                                                </div>
                                             </div>  
                                          </div>
                                       </div>
                                    @endif

                                    <div class="row">
                                       <div class="col-md-12">
                                          <div class="form-group">
                                             <label class="pb-1" for="name">Price Type <span class="text-danger">*</span></label> <br>
                                             <label class="radio-inline pr-2">
                                                <input type="radio" class="price_type" name="price_type" value="package" @if(stripos($sla->price_type,'package')!==false) checked data-status="1" @else data-status="0" @endif > Package-Wise </label> 
                                                <label class="radio-inline"> 
                                                   <input type="radio" class="price_type" name="price_type" value="check" @if(stripos($sla->price_type,'check')!==false) checked data-status="1" @else data-status="0" @endif> Check-Wise 
                                                </label>
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-price_type"></p>
                                          </div>
                                       </div>
                                    </div>
                                    
                                       <div class="price_result @if(stripos($sla->price_type,'package')!==false) mb-2 @endif" @if(stripos($sla->price_type,'package')!==false) style="border:1px solid #ddd;padding:10px;width:50%" @endif>
                                          @if(stripos($sla->price_type,'package')!==false)
                                             <div class="row">
                                                <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Package Wise Price</div>
                                                <div class="col-sm-6">
                                                   <div class="form-group">
                                                      <label>Price <span class="text-danger">*</span> (<small class="text-muted"><i class="fas fa-rupee-sign"></i></small>)</label>
                                                      <input class="form-control" type="text" name="price" value="{{$sla->package_price}}">
                                                      <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-price"></p>
                                                   </div>
                                                </div>
                                             </div> 
                                          @endif
                                       </div>
                                   

                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label>Select Services <span class="text-danger">*</span></label>
                                             <div class="col-sm-12">
                                                <div class="form-group">
                                                   @foreach($services as $service)
                                                      <div class="form-check form-check-inline">
                                                         <input class="form-check-input services_list" type="checkbox" name="services[]" value="{{ $service->id}}" data-string="{{ $service->name  }}" id="inlineCheckbox-{{ $service->id}}" data-type="{{ $service->is_multiple_type }}" data-verify="{{$service->verification_type}}"
                                                         {{in_array($service->id, $selected_services_id) ? 'checked' : '' }} >
                                                         <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label>
                                                      </div>
                                                   @endforeach
                                                </div>
                                             </div>
                                          <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
                                             {{-- @if ($errors->has('services'))
                                             <div class="error text-danger">
                                                {{ $errors->first('services') }}
                                             </div>
                                             @endif --}}
                                          </div>
                                       </div>

                                       <div class="col-sm-6">
                                          
                                       </div>
                                    </div>

                                    <div class="service_result" style="border: 1px solid #ddd; padding:10px;">
                                       <div class="row">
                                          <div class="col-sm-12 mt-1 mb-2">
                                             <span style="color:#dd2e2e">Configure Number of Verifications Need on each check item</span>
                                             <span style="float: right;">
                                                <span class="pr-2"> Total Checks:- <span class="total_checks">{{$total_checks}}</span></span>
                                                <span class="total_p @if(stripos($sla->price_type,'package')!==false) d-none @endif"> Total Price:- <i class='fas fa-rupee-sign'></i> <span class="total_check_price">{{$total_check_price}}</span></span>
                                             </span>
                                          </div>
                                       </div>
                                          @foreach($sla_items as $item)
                                             <p class="pb-border row-{{ $item->id}}"></p>
                                             <div class='row mt-2 row-{{ $item->id}}' id="row-{{ $item->id}}">
                                                   <div class='col-sm-2'><label>{{ $item->name}}</label></div>
                                                   <div class='col-sm-2'><input class='form-control no_of_check' type='text' name='service_unit-{{$item->id}}' value="{{ $item->number_of_verifications }}" @if($item->verification_type=='Auto' || $item->verification_type=='auto') readonly @endif><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-{{$item->id}}'></p></div>
                                                   <div class='col-sm-1'>
                                                      <label>TAT</label>
                                                   </div>
                                                   <div class='col-sm-3'>
                                                      <input class='form-control' type='text' name='tat-{{$item->id}}' value="{{ $item->check_tat }}" placeholder='TAT' >
                                                      <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-{{$item->id}}'></p>
                                                   </div>
                                                   <div class='col-sm-3'><input class='form-control' type='text' name='notes-{{$item->id}}' value="{{ $item->notes }}" placeholder="Notes" ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-notes-{{$item->id}}'></p></div>
                                             </div>
                                             <div class='row mt-2 row-{{ $item->id}}' id="row-{{ $item->id}}">
                                                <div class='col-sm-3'></div>
                                                <div class='col-sm-2 pt-2 text-right'><label>Incentive TAT</label></div>
                                                <div class='col-sm-1'>
                                                   <input class='form-control' type='text' name='incentive-{{$item->id}}' value="{{ $item->incentive_tat }}" placeholder="Incentive TAT">
                                                   <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-{{$item->id}}'></p>
                                                </div>
                                                <div class='col-sm-2 pt-2 text-right'><label>Penalty TAT</label></div>
                                                <div class='col-sm-1'>
                                                   <input class='form-control' type='text' name='penalty-{{$item->id}}' value="{{ $item->penalty_tat }}" placeholder="Penalty TAT">
                                                   <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-{{$item->id}}'></p>
                                                </div>
                                             </div>
                                             <div class='row price_row @if(stripos($sla->price_type,'package')!==false) d-none @endif mt-2 row-{{$item->id}}' id='row mt-2 row-{{$item->id}}'>
                                                   <div class='col-sm-2 pt-2'>
                                                      <label>Price (<small class='text-muted'>Per Item in <i class='fas fa-rupee-sign'></i></small>)</label>
                                                   </div>
                                                   <div class='col-sm-2'>
                                                      <input class='form-control check_price' type='text' name='price-{{$item->id}}' value='{{$item->price}}' @if(stripos($sla->price_type,'package')!==false) readonly @endif>
                                                      <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-{{$item->id}}'></p>
                                                   </div>
                                             </div>
                                             
                                          @endforeach
                                    </div>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tat_type"></p>
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <input type="hidden" name="sla_id" value="{{ $sla->id }}">
                                             <input type="hidden" name="business_id" value="{{ $sla->business_id }}">
                                             <input type="hidden" name="sla_name"  value="{{$sla->title}}">
                                             <input type="hidden" name="tat_type" id="tat_type" value="{{$sla->tat_type}}">
                                             <button type="submit" class="btn btn-info mt-3 submit">Update</button>
                                          </div>
                                       </div>
                                    </div>
                                    </form>
                                 </div>
                              </div>
                              <!-- ./form section -->
                              
                        </div>
                     
                     <!--  -->
                     <!-- ./section -->
                  </div>
               </div>
               <!-- end right sec -->
         </div>
      </div>
   </div>
</div>
@stack('scripts')
<script type="text/javascript">
   //
   $(document).ready(function() {
     $(".services_list").change(function() {

         var total_price = 0;

         var total_check = 0;

         if(this.checked)
         {
         
            var id =  $(this).attr("value");
            var text =  $(this).attr("data-string");
            var verify=$(this).attr("data-verify");

            var tat = 1;

            var price_type = $('.price_type:checked').val();

            var readonly = '';

            var display_none = '';

            if(price_type.toLowerCase()=='package'.toLowerCase())
            {
               readonly = 'readonly';

               display_none = 'd-none';
            }

            if(text.toLowerCase()=='Address'.toLowerCase())
            {
               tat=7;
            }
            else if(text.toLowerCase()=='Employment'.toLowerCase())
            {
               tat=5;
            }
            else if(text.toLowerCase()=='Educational'.toLowerCase())
            {
               tat=7;
            }
            else if(text.toLowerCase()=='Criminal'.toLowerCase())
            {
               tat=3;
            }
            else if(text.toLowerCase()=='Judicial'.toLowerCase())
            {
               tat=2;
            }
            else if(text.toLowerCase()=='Reference'.toLowerCase())
            {
               tat=2;
            }
            else if(text.toLowerCase()=='Covid-19 Certificate'.toLowerCase())
            {
               tat=5;
            }

            if(verify.toLowerCase()=="Auto".toLowerCase())
               $(".service_result").append("<p class='pb-border row-"+id+"'></p><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-2'><input class='form-control no_of_check' type='text' name='service_unit-"+id+"' value='1' readonly><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='tat-"+id+"' value='"+tat+"' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-"+id+"'></p></div><div class='col-sm-3'><input class='form-control' type='text' name='notes-"+id+"' placeholder='Notes' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-notes-"+id+"'></p></div></div><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-3'></div><div class='col-sm-2 pt-2 text-right'><label>Incentive TAT</label></div><div class='col-sm-1'><input class='form-control' type='text' name='incentive-"+id+"' value='1'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-"+id+"'></p></div><div class='col-sm-2 pt-2 text-right'><label>Penalty TAT</label></div><div class='col-sm-1'><input class='form-control' type='text' name='penalty-"+id+"' value='"+tat+"'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-"+id+"'></p></div></div><div class='row price_row "+display_none+" mt-2 row-"+id+"' id='row mt-2 row-"+id+"'><div class='col-sm-2 pt-2'><label>Price (<small class='text-muted'>Per Item <i class='fas fa-rupee-sign'></i></small>)</label></div><div class='col-sm-2'><input class='form-control check_price' type='text' name='price-"+id+"' value='0' "+readonly+"><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-"+id+"'></p></div></div>");
            else
               $(".service_result").append("<p class='pb-border row-"+id+"'></p><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-2'><label>"+text+"</label></div><div class='col-sm-2'><input class='form-control no_of_check' type='text' name='service_unit-"+id+"' value='1' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-"+id+"'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='tat-"+id+"' value='"+tat+"' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-"+id+"'></p></div><div class='col-sm-3'><input class='form-control' type='text' name='notes-"+id+"' placeholder='Notes' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-notes-"+id+"'></p></div></div><div class='row mt-2 row-"+id+"' id='row-"+id+"'><div class='col-sm-3'></div><div class='col-sm-2 pt-2 text-right'><label>Incentive TAT</label></div><div class='col-sm-1'><input class='form-control' type='text' name='incentive-"+id+"' value='1'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-"+id+"'></p></div><div class='col-sm-2 pt-2 text-right'><label>Penalty TAT</label></div><div class='col-sm-1'><input class='form-control' type='text' name='penalty-"+id+"' value='"+tat+"'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-"+id+"'></p></div></div><div class='row price_row "+display_none+" mt-2 row-"+id+"' id='row mt-2 row-"+id+"'><div class='col-sm-2 pt-2'><label>Price (<small class='text-muted'>Per Item <i class='fas fa-rupee-sign'></i></small>)</label></div><div class='col-sm-2'><input class='form-control check_price' type='text' name='price-"+id+"' value='0' "+readonly+"><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-"+id+"'></p></div></div>");

               
         }
         else
         {
            var id =  $(this).attr("value");
            $("div.row-"+id).remove();
            $("p.row-"+id).remove();
         }

         $('.check_price').each(function () {
            if(!isNaN(parseFloat($(this).val())))
            {
               total_price = total_price + parseFloat($(this).val());
            }
         });

         $('.total_check_price').html(total_price.toFixed(2));

         $('.no_of_check').each(function(){
            var is_int = Number.isInteger(parseInt($(this).val()));
            if(is_int)
            {
               total_check = total_check + parseInt($(this).val());
            }
         });

         $('.total_checks').html(total_check);
      
     });

     $('.price_type').change(function(){
         if(this.checked)
         {
            
            $('.price_result').html('');
            $('.price_result').removeClass('mb-2');
            $('.price_result').removeAttr('style');

            var price_type = $('.price_type:checked').val();
            
            if(price_type.toLowerCase()=='package'.toLowerCase())
            {
               var status = $(this).attr('data-status');
               var price = '0';
               $('.price_result').addClass('mb-2');
               $('.price_result').css({'border': '1px solid #ddd','padding':'10px','width':'50%'});
               if(status==1)
               {
                   price = '{{$sla->package_price}}';
               }
               $('.price_result').html("<div class='row'> <div class='col-sm-12 mt-1 mb-2' style='color:#dd2e2e'>Package-Wise Price</div> <div class='col-sm-6'> <div class='form-group'> <label>Price <span class='text-danger'>*</span> (<small class='text-muted'><i class='fas fa-rupee-sign'></i></small>)</label> <input class='form-control' type='text' name='price' value='"+price+"'> <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price'></p> </div> </div> </div>");

               $('.check_price').attr('readonly',true);

               $('.price_row').addClass('d-none');

               $('.total_p').addClass('d-none');

            }
            else
            {
               $('.check_price').attr('readonly',false);

               $('.price_row').removeClass('d-none');

               $('.total_p').removeClass('d-none');
            }
            
         }
         else
         {
            alert('Select One price type');
         } 
     });

      $(document).on('change keyup','.check_price',function(){

         var total_price = 0;

         $('.check_price').each(function () {
            if(!isNaN(parseFloat($(this).val())))
            {
               total_price = total_price + parseFloat($(this).val());
            }
         });

         $('.total_check_price').html(total_price.toFixed(2));
      });

      $(document).on('change keyup','.no_of_check',function(){

         var total_check = 0;
         $('.no_of_check').each(function(){
            var is_int = Number.isInteger(parseInt($(this).val()));
            if(is_int)
            {
               total_check = total_check + parseInt($(this).val());
            }
         });

         $('.total_checks').html(total_check);
      });

     $('#editSLABtn').click(function(e) {
        e.preventDefault();
        $("#editSLAForm").submit();
   });

   $(document).on('submit', 'form#editSLAForm', function (event) {
      event.preventDefault();
      //clearing the error msg
      $('p.error_container').html("");
      $('.form-control').removeClass('border-danger');
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
      $('.submit').attr('disabled',true);
      if ($('.submit').html() !== loadingText) {
            $('.submit').html(loadingText);
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
                  $('.submit').attr('disabled',false);
                  $('.submit').html('Update');
               },2000);

               console.log(response);
               if(response.success==true) {          
                  // window.location = "{{ url('/')}}"+"/sla/?created=true";
                  toastr.success('SLA Updated Successfully');
                  window.setTimeout(function(){
                     window.location = "{{ url('/')}}"+"/sla/";
                  },2000);
               }
               //show the form validates error
               if(response.success==false ) {                              
                  for (control in response.errors) {   
                     $('.'+control).addClass('border-danger');
                     $('#error-' + control).html(response.errors[control]);
                  }
               }
         },
         error: function (response) {
               console.log(response);
         }
         // error: function (xhr, textStatus, errorThrown) {
         //       // alert("Error: " + errorThrown);
         // }
      });
      return false;
   });
      
   });


      
</script>  
@endsection
