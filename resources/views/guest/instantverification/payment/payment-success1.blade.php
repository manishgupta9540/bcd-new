<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCD</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{url('/').'/admin/images/BCD-favicon.png'}}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="{{ asset('admin/fonts/font-awesome-all.css') }}" rel="stylesheet" />
  <link href="{{ asset('guest/css/responsive.css?ver=1.7')}}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.1.0/css/all.css" />
  <link href="https://fonts.googleapis.com/css?family=Kaushan+Script|Source+Sans+Pro" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
  <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <style type="text/css">
    *{
  box-sizing:border-box;
 /* outline:1px solid ;*/
}
h1.congratulation {
    margin-top: 21px;
}
button.go-home:focus {
    border-radius: 30px;
    border: 1px solid black;
    outline: 0px auto -webkit-focus-ring-color;
}
img.check {
    margin-top: 8px;
}
.wrapper-1{
  width:100%;
  height:100vh;
  display: flex;
flex-direction: column;
}
.wrapper-2{
  padding :30px;
  text-align:center;
}
h1{
  /* font-family: 'Kaushan Script', cursive; */
  /* font-size:4em; */
  letter-spacing:3px;
  /* color:#5892FF ; */
  margin:0;
  margin-bottom:20px;
}
.wrapper-2 p {
    margin: 0;
    font-size: 27px;
    color: #002e60;
    font-family: 'Source Sans Pro', sans-serif;
    letter-spacing: 1px;
}
.go-home {
    color: #002e62;
    background: #fff;
    border: none;
    padding: 10px 50px;
    margin: 30px 0;
    border-radius: 30px;
    text-transform: capitalize;
    /* box-shadow: 0px 4px 15px #666; */
    border: 1px solid #002e62;
}
.go-home:hover{
    color: #fff;
    background: #002e62;
    border: 1px solid #fff;
    animation: fadeIn 0.7s;
}
.go-report {
    color: #28a745;
    background: #fff;
    border: none;
    padding: 10px 50px;
    margin: 30px 0;
    border-radius: 30px;
    text-transform: capitalize;
    /* box-shadow: 0px 4px 15px #666; */
    border: 1px solid #28a745;
}
.go-report:hover{
    color: #fff;
    background: #28a745;
    border: 1px solid #fff;
    animation: fadeIn 0.7s;
}

.disabled-link{
  pointer-events: none;
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

.btn-info {
  color: #fff;
  background-color: #003473;
  border-color: #003473; }
  .btn-info:hover {
    color: #fff;
    background-color: #00234d;
    border-color: #001d40; }
  .btn-info:focus, .btn-info.focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 52, 115, 0.5); }
  .btn-info.disabled, .btn-info:disabled {
    color: #fff;
    background-color: #003473;
    border-color: #003473; }
  .btn-info:not(:disabled):not(.disabled):active, .btn-info:not(:disabled):not(.disabled).active,
  .show > .btn-info.dropdown-toggle {
    color: #fff;
    background-color: #001d40;
    border-color: #001733; }
    .btn-info:not(:disabled):not(.disabled):active:focus, .btn-info:not(:disabled):not(.disabled).active:focus,
    .show > .btn-info.dropdown-toggle:focus {
      box-shadow: 0 0 0 0.2rem rgba(0, 52, 115, 0.5); }

      .btn-info,
.btn-outline-info {
  border-color: #003473; }
  .btn-info .btn-spinner,
  .btn-outline-info .btn-spinner {
    animation: btn-glow-info 1s ease infinite; }
  .btn-info:hover,
  .btn-outline-info:hover {
    background: #003473;
    box-shadow: 0 8px 25px -8px #003473;
    border-color: #003473; }
  .btn-info:focus,
  .btn-outline-info:focus {
    box-shadow: none;
    box-shadow: 0 8px 25px -8px #003473; }

@keyframes fadeIn {
  0% {opacity:0;}
  100% {opacity:1;}
}
.footer-like{
  margin-top: -22px; 
  
  padding:6px;
  text-align:center;
}
.footer-like p {
    margin: 0;
    padding: 4px;
    color: #444;
    font-family: 'Source Sans Pro', sans-serif;
    letter-spacing: 1px;
}
.footer-like p a {
    text-decoration: none;
    color: #444;
    font-weight: 600;
}

@media (min-width:360px){
  /* h1{
    font-size:4.5em;
  } */
  .go-home{
    margin-bottom:20px;
  }
}

@media (min-width:600px){
  .content{
    max-width:1000px;
    margin:0 auto;
  }
  .wrapper-1{
    height: initial;
    max-width:100%;
    margin:0 auto;
    margin-top:50px;
  
  }
  
}
@media(max-width:475px){
  .wrapper-2 p {
    margin: 0;
    font-size: 17px !important;

}

}
  


  </style>
</head>
<body>
<div class="content payment-sucess">
  <div class="wrapper-1">
    <div class="wrapper-2">
      <img src="{{ asset('admin/images/BCD-Logo2.png')}}">
      <h1 class="congratulation text-danger">Awesome !</h1>
      <p>Report Generated Successfully</p>
      @php
        $order_id = '';
        $guest_master_data = Helper::get_guest_instant_master_data($guest_master_id);
        if($guest_master_data!=NULL)
        {
            $order_id = $guest_master_data->order_id;
        }
      @endphp
      <p>Order ID: {{$order_id}} </p>
      <img src="{{ asset('admin/images/thank_check.png')}}" class="check">
      <p class="thanku">Thank You  </p>
      <a href="{{url('/verify/instantverification/orders')}}">
        <button class="go-home">
            <i class="fas fa-clipboard-list"></i> View Order
        </button>
      </a>
    {{-- <a class="report" href="javascript:void(0)" data-id="{{base64_encode($guest_master_data->id)}}">
        <button class="go-report">
            <i class="fab fa-whatsapp"></i> Report on Whatsapp
        </button>
    </a> --}}
    <a href="#">
        <button class="go-report md-none">
            <i class="fab fa-whatsapp"></i> Report on Whatsapp
        </button>
    </a>
    </div>
    <div class="footer-like">
      <p>Having Trouble?
       <a href="{{url('/verify/help')}}">Contact Us</a>
      </p>
    </div>
  </div>
</div>

<div class="modal fade" id="review_modal">
  <div class="modal-dialog" style="max-width: 50% !important;">
     <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
           <h4 class="modal-title"></h4>
           <button type="button" class="close btn-disable" style="top: 12px !important; color: red;" data-dismiss="modal"><small>×</small></button>
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
                      <!-- <input type="radio" id="rating10" name="rating" value="5" /><label class="stars_1" for="rating10" title="5 stars"></label>
                      <input type="radio" id="rating9" name="rating" value="4.5" /><label class="half stars_1" for="rating9" title="4 1/2 stars"></label>
                      <input type="radio" id="rating8" name="rating" value="4" /><label class="stars_1" for="rating8" title="4 stars"></label>
                      <input type="radio" id="rating7" name="rating" value="3.5" /><label class="half stars_1" for="rating7" title="3 1/2 stars"></label>
                      <input type="radio" id="rating6" name="rating" value="3" /><label  class="stars_1" for="rating6" title="3 stars"></label> -->
                      <input type="radio" id="rating5" name="rating" value="2.5" /><label class="half stars_1" for="rating5" title="2 1/2 stars"></label>
                      <input type="radio" id="rating4" name="rating" value="2" /><label class="stars_1" for="rating4" title="2 stars"></label>
                      <input type="radio" id="rating3" name="rating" value="1.5" /><label class="half stars_1" for="rating3" title="1 1/2 stars"></label>
                      <input type="radio" id="rating2" name="rating" value="1" /><label class="stars_1" for="rating2" title="1 star"></label>
                      <input type="radio" id="rating1" name="rating" value=".5" /><label class="half stars_1" for="rating1" title="1/2 star"></label>
                  </fieldset>
                  <p style="margin-bottom: 2px;" class="text-danger error-container error-rating" id="error-rating"></p>  
                </div>
                 <div class="col-12">
                    <div class="form-group">
                        <label class="modal-title">Comments </label> <br>
                        <textarea class="form-control" type="text" name="comments" id="setcomment"></textarea>
                        <p style="margin-bottom: 2px;" class="text-danger error-container error-comments" id="error-comments"></p> 
                    </div>
                 </div>
              </div>
           </div>
           <!-- Modal footer -->
           <div class="modal-footer">
              <button type="submit" class="btn btn-info btn-disable submit_btn"><i class="fas fa-paper-plane"></i> Send</button>
           </div>
        </form>
     </div>
  </div>
</div>
<script>
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

  });
  
</script>

</body>
</html>
