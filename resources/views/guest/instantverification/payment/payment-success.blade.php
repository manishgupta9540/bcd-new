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
    
        
    .rate {
        display: inline-block;
        border: 0;
    }

    fieldset {
        min-width: 0;
        padding: 0;
        margin: 0;
        border: 0;
    }
    .rate > input {
        display: none;
    }
    .rate > label {
        float: right;
    }
    .rate > label:before {
        display: inline-block;
        font-size: 1.1rem;
        padding: .3rem .2rem;
        margin: 0;
        cursor: pointer;
        font-family: FontAwesome;
        content: "\f005 "; /* full star */
    }
    /* Zero stars rating */
    .rate > label:last-child:before {
        content: "\f006 "; /* empty star outline */
    }
    /* Half star trick */
    .rate .half:before {
        content: "\f089 "; /* half star no outline */
        position: absolute;
        padding-right: 0;
    }
    /* Click + hover color */
input:checked ~ .stars_1, /* color current and previous stars on checked */
.stars_1:hover, .stars_1:hover ~ .stars_1 { color: #ffd000d9;  } /* color previous stars on hover */

/* Hover highlights */
input:checked + .stars_1:hover, input:checked ~ .stars_1:hover, /* highlight current and previous stars */
input:checked ~ .stars_1:hover ~ .stars_1, /* highlight previous selected stars for new rating */
.stars_1:hover ~ input:checked ~ .stars_1 /* highlight previous selected stars */ { color: #fad817e6;  } 

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
.checked {
    color: orange;
}

   }



   .textareades
   {
    height: 100px;
   }
   .sendbtn
   {
    background-color: #003473;
    border-color: #003473;
    color:#fff;
   }


   .model-width
   {
    width:35%;
   }
   @media only screen and (max-width: 768px) {
   .model-width
   {
    width:90%;
    margin-left:20px;
   }
}
</style>

<!-- ============ Search UI End ============= -->

<!-- =============== Left side End ================-->
{{-- <div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content"> --}}
        <div class="col-md-10 col-sm-12 marg-des">
            <div class="verification-detail-1">
            <div class="logosection">
                <p><span>Instant Verification</span><br> Find your report</p>
                <p>Government approved APIs * : <img src="{{asset('admin/microsite/images/udiai1.png')}}"></p>
            </div>
                <?php
                    $guest_id=Crypt::encryptString($guest_master_id);
                ?>
            <div class="verification-1-button">
                <a href="{{url('verify/instant_verification')}}"><button class="verfication-top-tab">Choose items</button></a>
                <a href="javascript:void(0)"><button class="verfication-top-tab">Enter details</button></a>
                <a href="javascript:void(0)"><button class="verfication-top-tab">Make payment</button></a>
                <a href="{{url('verify/instant_verification/payment-success/'.$guest_id)}}"><button class="verfication-top-tab activeclass">View results</button></a>
            </div>
            <div class="col-md-8 col-sm-12 verification-step-4 offset-md-2">
                <div>
                    <div class="block-first">
                        <img src="{{asset('admin/microsite/images/checkimg.png')}}">
                        <h5>Order Successfully Placed</h5>
                        @php
                            $order_id = '';
                            $guest_master_data = Helper::get_guest_instant_master_data($guest_master_id);
                            if($guest_master_data!=NULL)
                            {
                                $order_id = $guest_master_data->order_id;
                            }
                        @endphp
                    </div>
                    <table>
                        @foreach ($data_order as $key => $data)
                        <tbody>
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{Helper::get_service_name($data->service_id)}}</td>
                                <td>
                                    @if ($data->status == 'success')
                                        <span class="verifiedbtn">VERIFIED</span>
                                    @else
                                        <span class="verifiedbtn">NOT VERIFIED</span>
                                    @endif
                                </td>
                                <td><span><a href="javascript:void(0)" data-id="{{base64_encode($data->id)}}" class="viewReportBox verifiedbtn">VIEW REPORT</span></a></td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>

                    <div class="rating-block">
                        <span>Rate our services :</span>
                        @foreach ($ratingReviews as $item)
                            <?php
                                for ($i = 0; $i < 5; $i++) {
                                    if($i<=$item->stars && $item->stars > 0)
                                    {
                                        echo '<i class="fa fa-star' ,
                                        ($item->stars == $i + .5 ? '-half' : '') ,
                                        ($item->stars <= $i ? '-o' : '') ,
                                        '" aria-hidden="true" style="color: #ff9800;"></i>';
                                    }
                                }
                            ?>
                        @endforeach
                        {{-- <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star"></span> --}}
                    </div>
                </div>
            </div>
            </div>
        </div>
    {{-- </div> --}}
{{-- </div> --}}



 {{-- Modal for Report preview --}}
 <div class="modal"  id="preview_report">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Report Preview</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          
             <div class="modal-body">
             <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                <iframe src="" style="width:100%; height:600px;" frameborder="0" id="preview_pdf"></iframe>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="button" class="btn btn-danger back" data-dismiss="modal">Close</button>
             </div>
       </div>
    </div>
</div>
 {{-- Modal for Report preview --}}
<div class="modal fade" id="review_modal">
    <div class="modal-dialog model-width">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title"></h4>
             <button type="button" class="close btn-disable" style="top: 12px !important; color: red;" data-dismiss="modal"><large>×</large></button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{route('/verify/feedback/store')}}" id="review_frm" enctype="multipart/form-data">
          @csrf
            <input type="hidden" name="id" class="id" id="id" value="{{base64_encode($guest_master_data->id)}}">
             <div class="modal-body">
                <div class="row">
                  <div class="col-12">
                    <label for="label_name">Rating <span class="text-danger">*</span></label><br>
                    <fieldset class="rate">
                         <input type="radio" id="rating10" name="rating" value="5" /><label class="stars_1" for="rating10" title="5 stars"></label>
                        <input type="radio" id="rating9" name="rating" value="4.5" /><label class="half stars_1" for="rating9" title="4 1/2 stars"></label>
                        <input type="radio" id="rating8" name="rating" value="4" /><label class="stars_1" for="rating8" title="4 stars"></label>
                        <input type="radio" id="rating7" name="rating" value="3.5" /><label class="half stars_1" for="rating7" title="3 1/2 stars"></label>
                        <input type="radio" id="rating6" name="rating" value="3" /><label  class="stars_1" for="rating6" title="3 stars"></label>
                        <input type="radio" id="rating5" name="rating" value="2.5" /><label  class="half stars_1" for="rating5" title="2 1/2 stars"></label>
                        <input type="radio" id="rating4" name="rating" value="2" /><label  class="stars_1" for="rating4" title="2 stars"></label>
                        <input type="radio" id="rating3" name="rating" value="1.5" /><label  class="half stars_1" for="rating3" title="1 1/2 stars"></label>
                        <input type="radio" id="rating2" name="rating" value="1" /><label  class="stars_1" for="rating2" title="1 stars"></label>
                        <input type="radio" id="rating1" name="rating" value="0.5" /><label  class="half stars_1" for="rating1" title="0 1/2 stars"></label>
                        {{-- <input type="radio" id="rating0" name="rating" value="0.0" /><label  class="stars_1" for="rating0" title="0 1/2 stars"></label> --}}
                         {{-- <input type="radio" id="rating5" name="rating" value="2.5" /><label class="half stars_1" for="rating5" title="2 1/2 stars"></label>
                        <input type="radio" id="rating4" name="rating" value="2" /><label class="stars_1" for="rating4" title="2 stars"></label>
                        <input type="radio" id="rating3" name="rating" value="1.5" /><label class="half stars_1" for="rating3" title="1 1/2 stars"></label>
                        <input type="radio" id="rating2" name="rating" value="1" /><label class="stars_1" for="rating2" title="1 star"></label>
                        <input type="radio" id="rating1" name="rating" value=".5" /><label class="half stars_1" for="rating1" title="1/2 star"></label> --}}

                    </fieldset>
                    <p style="margin-bottom: 2px;" class="text-danger error-container error-rating" id="error-rating"></p>  
                  </div>
                   <div class="col-12">
                      <div class="form-group">
                          <label class="modal-title">Comments </label> <br>
                          <textarea class="form-control textareades" type="text" name="comments" id="setcomment"></textarea>
                          <p style="margin-bottom: 2px;" class="text-danger error-container error-comments" id="error-comments"></p> 
                      </div>
                   </div>
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-disable submit_btn sendbtn"><i class="fas fa-paper-plane"></i> Send</button>
             </div>
          </form>
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

    var is_review = '{{$guest_master_data->is_review}}';

    $(document).on('click','.report',function(){
        var _this =  $(this);
        var id = _this.attr('data-id');
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
        _this.addClass('disabled-link');
        _this.attr('disabled',true);
        $('.go-report').attr('disabled',true);
        if($('.go-report').html!=loadingText)
        {
            $('.go-report').html(loadingText);
        }
        $.ajax
        ({
                type:'POST',
                url: "{{ url('/verify/')}}"+"/instant_verification/whatsapp_report",
                data: {"_token": "{{ csrf_token() }}",'id':id},        
                success: function (response) {        
                    window.setTimeout(function(){
                        _this.removeClass('disabled-link');
                        _this.attr('disabled',false);
                        $('.go-report').attr('disabled',false);
                        $('.go-report').html('<i class="fab fa-whatsapp"></i> Report on Whatsapp');
                    },2000);

                    if (response.status) { 

                    toastr.success("Report Details Has Been Sent Successfully to your Whatsapp Number");

                    } 
                    else {
                        toastr.error("Something Went Wrong !!");
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
        });


    });

    if(is_review=='0')
    {
        setTimeout(function(){
        $('#review_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        },2000);
    }

$(document).on('submit', 'form#review_frm', function (event) {
    event.preventDefault();
    //clearing the error msg
    $('p.error-container').html("");

    var form = $(this);
    var data = new FormData($(this)[0]);
    var url = form.attr("action");

        $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {

                if(response.success==true) {          
                
                    //notify
                toastr.success("Feedback Submitted Successfully");
                    // redirect to google after 5 seconds
                    window.setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                
                }
                //show the form validates error
                if(response.success==false ) {                              
                    for (control in response.errors) {   
                        $('#error-' + control).html(response.errors[control]);
                    }
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                // alert("Error: " + errorThrown);
            }
        });
        event.stopImmediatePropagation();
        return false;
    }); 

    // $('.viewReportBox').click(function(){
    //     var report_id = $(this).attr('data-id');
    //      document.getElementById('preview_pdf').src="{{ url('/') }}"+"/verify/instant/preview/"+report_id;
    //      $('#preview').toggle();
    // });

    $(document).on('click','.viewReportBox',function(){
        var report_id = $(this).attr('data-id');
        
        $('#preview_report').modal({
            backdrop: 'static',
            keyboard: false
        });

        $.ajax({
               type: 'GET', 
               url:"{{ url('/verify/instant/preview') }}",
               data: {'report_id':report_id},  
               success: function (response) {
                //console.log(response)
                if(response.success == true){
                    if(response !='null')
                    {             
                        $('#preview_pdf').attr('src', response.url+'#toolbar=0')
                    }
                }
               //show the form validates error
               if(response.success==false ) {                              
                     for (control in response.errors) {   
                        $('#error-'+control).html(response.errors[control]);
                     }
               }
            },
            
        });
        
        // $('#preview').toggle();
      });

    $('.close').click(function(){
        $('#preview').hide();
    });
    $('.back').click(function(){
        $('#preview').hide();
    });
      
   });
       
</script>   

@endsection
