@extends('layouts.app')
@section('content')
<style>

    .fw-600{
        font-weight: 600;
    }
    
    .text-blue{color:#142550;}
    .text-para{color:#474747;}
    .para-custom{
        line-height: 29px;
        color:#444444;
    }
    .mt-80{margin-top:80px;}
    
   
    .first-container{
        /*background-image: url('images/verification_banner_no_text.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        height: 590px;
        box-shadow: 0px 5px 10px #ddd;*/
        box-shadow: 0px 5px 10px #999;
        position: sticky;
        top: 0px;
        background: #fff;
        z-index: 4;
    
    }
    
    .registration-menu{
        list-style-type: none;
        padding: 0px;
        /*position: absolute;
        right:130px;
        top:20px;
        z-index:2;*/
    }
    .registration-menu li{
        display: inline;
        margin:5px 10px;
    }
    .registration-menu li a{
        font-weight: 500;
        line-height: 1;
        font-size: 17px;
        font-family: 'Ruda', sans-serif;
        color:#002e62!important;
        text-decoration: none;
    
    }
    .registration-menu li a:hover{
        color:#ff0000!important;
    }
    
   
    .registration-nav{
        position: relative;
        top: 0px;
        left: 0px;
        padding: 0px;
    
    }
    .verification-footer{
        /* background-color: #002E62; */
        background-color: #ACACAC;
        text-align: center;
        color: #fff!important;
        padding: 10px ;
        
    }
    
    .btn-opacity
    {
        opacity: .65;
    }
    @media only screen and (min-width: 320px) and (max-width: 767px){
            /*.first-container{
            background-image: url('images/verification_banner_no_text.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: 0% 100%;
            height: 310px;
        }*/
        
        .registration-menu {
            
            background-color: #eacccc;
            top: 0px;
            position: relative;
        
        }
        
        
        #qrcode, #qrcode img {
            margin: 20px auto 0px auto !important;
        }
        
    
    
    }
    
    .btn1 {
        border-radius: 26px;
    }
    /*.ban-data{position: absolute;
    top:80px;left:0px;}*/

   #qrcode, #qrcode img {
        margin: 50px auto 0px auto !important;
        text-align: center;
    }

    .btn-blue {
        background-color:#002e62 /*#1A237E*/;
        width: 150px;
        color: #fff;
        border-radius: 2px
    }

    .btn-blue:hover {
        background-color: #03136b;
        color: white;
        cursor: pointer;
    }
    footer.verification-footer {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
    }
    @media(max-width:491px)
    {
    #qrcode, #qrcode img{
         margin: 99px auto 0px auto !important;
        }
    .qr-section button#downloadBtn {
    width: 50% !important;
      }
      .verification-logo {
    max-width: 90px !important;
}
    }
    </style>
<section class="qr-section">
    <div class="container qr-container">
        <div id="qrcode" class="">
            
        </div>
    </div>
</section>
<script>
    function showQr() {
        $('#qrcode').html('');
        var qrcode=new QRCode(document.getElementById("qrcode"), {
            text: "https://my-bcd.com/startverification", 
            width: 350, 
            height: 350, 
            colorDark: "#000000", 
            colorLight: "#ffffff", 

            // quietZone: 5,
            // quietZoneColor: "#002e62",
            

            logo:"{{asset('admin/images/BCD-favicon.png')}}", // LOGO
            //	logo:"http://127.0.0.1:8020/easy-qrcodejs/demo/logo.png",
            	logoWidth:80, //
            	logoHeight:80,
            //logoBgColor:'#ffffff', // Logo backgroud color, Invalid when `logBgTransparent` is true; default is '#ffffff',
            logoBackgroundTransparent:true, // Whether use transparent image, default is false

            
            //tooltip: true,

            //drawer: 'svg',

            correctLevel: QRCode.CorrectLevel.H //  L, M, Q, H
        });

        $('#qrcode').find('canvas').attr('id','canv');

       // console.log(Object.entries(qrcode._oDrawing));

        //var dataUrl=qrcode.toDataURL();
        
        $('.qr-container').append(`<div class="text-center mb-5">
                                        <button class="btn btn-sm btn-blue" style="width: 20%" id="downloadBtn"><i class="fas fa-download"></i> Download</button>
                                  </div>`);
    }

    $('#qrcode').html(cardLoaderHtml()).fadeIn(300);

    function cardLoaderHtml()
    {
        return "<div class='fa-3x' style='color: #002e62;'><i class='fas fa-circle-notch fa-spin'></i></div>";
    }



    window.setTimeout(function(){
        showQr();
    },2500);

    $(document).on('click','#downloadBtn',function(){
        var canvas=document.getElementById("canv");
        var anchor = document.createElement("a");
        anchor.href = canvas.toDataURL("image/jpeg");
        anchor.download = "image.jpg";
        anchor.click();
        anchor.remove();
    });
    
</script>
@endsection