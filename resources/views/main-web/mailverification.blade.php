<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCD</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{url('/').'/admin/images/BCD-favicon.png'}}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="{{ asset('admin/fonts/font-awesome-all.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
  <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<style type="text/css">

  body {
    color: #000;
    overflow-x: hidden;
    height: 100%;
    background-color: #ffffff;
    background-repeat: no-repeat
}
.col-lg-6.imageblock {
    /* background-color: rgb(91, 99, 254); */
    background-color: #002e62;
    position: absolute;
    /*left: 50%;*/
    top: 0;
    bottom: 0;

}
.col-lg-6.login {
    position: absolute;
    left:50%;
    
}
.container-fluid.px-1.px-md-5.px-lg-1.px-xl-5.py-5.mx-auto {
    padding: 0px!important;
}
.card0 {
    /*box-shadow: 0px 4px 8px 0px #757575;*/
    border-radius: 0px
}
.card2.card.border-0.px-4.py-5 {
    padding-bottom: 0px!important;
}
.card1.pb-5{
    padding-bottom:0px!important;
}
.card2 {
    margin: 40px 40px
}
/* .text-danger {
    color: #03136b!important;
}
.text-danger:hover {
    color: #03136b!important;
} */
.logo {
    /* width: 150px; */
    height: 100px;
    margin-top: 20px;
    
}

img.image {
    width: 100%;
    height: 600px;
}
.border-line {
    border-right: 1px solid #EEEEEE
}

.facebook {
    background-color: #3b5998;
    color: #fff;
    font-size: 18px;
    padding-top: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    cursor: pointer
}

.twitter {
    background-color: #1DA1F2;
    color: #fff;
    font-size: 18px;
    padding-top: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    cursor: pointer
}

.linkedin {
    background-color: #2867B2;
    color: #fff;
    font-size: 18px;
    padding-top: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    cursor: pointer
}

.line {
    height: 1px;
    width: 45%;
    background-color: #E0E0E0;
    margin-top: 10px
}

.or {
    width: 10%;
    font-weight: bold
}

.text-sm {
    font-size: 14px !important
}

::placeholder {
    color: #BDBDBD;
    opacity: 1;
    font-weight: 300
}

:-ms-input-placeholder {
    color: #BDBDBD;
    font-weight: 300
}

::-ms-input-placeholder {
    color: #BDBDBD;
    font-weight: 300
}

input,
textarea {
    padding: 10px 12px 10px 12px;
    border: 1px solid lightgrey;
    border-radius: 2px;
    margin-bottom: 5px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    color: #2C3E50;
    font-size: 14px;
    letter-spacing: 1px
}

input:focus,
textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid #304FFE;
    outline-width: 0
}

button:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    outline-width: 0
}

a {
    color: inherit;
    cursor: pointer
}

.btn-blue {
    background-color: #1A237E;
    width: 150px;
    color: #fff;
    border-radius: 2px
}

.btn-blue:hover {
    background-color: #03136b;
    color: white;
    cursor: pointer
}

.bg-blue {
    color: #fff;
    background-color: #1A237E
}
h1.count {
    position: relative;
    top: 33px;
    left: 55px;
    border: 1px solid #e2ecfe;
    width: 34px;
    height: 43px;
    border-radius: 50%;
    padding: 7px;
    font-size: 29px;
    color: #213b7e;
    background-color: #e2ecfe;
}
@media screen and (max-width: 991px) {
    .col-lg-6.login {
    position: relative;
     left: 0%; 
}
    .col-lg-6.imageblock {
    /* background-color: rgb(91, 99, 254); */
    background-color: #002e62;
    position: relative;
    left: 0%;
    bottom: 0;
    top: 0px;
}
    .logo {
        margin-left: 0px
    }

    .image {
        width: 300px;
        height: 220px
    }

    .border-line {
        border-right: none
    }

    .card2 {
        border-top: 1px solid #EEEEEE !important;
        margin: 0px 15px
    }
}
</style>
<body>
<div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 login">
                <div class="card1 pb-5">
                    <div class="row"> 
                         <div class="col-lg-12">
                        <center><a href="{{ url('/') }}"><img src="{{ asset('admin/images/BCD-Logo1.png')}}" class="logo text-center"> </a></center>
                     </div>
                    </div>
                    <div class="card2 card border-0 px-4 py-5">
                        <h1 class="count">1</h1>
                    <i class="fa fa-envelope" style="font-size: 70px;color: #2b4691;border-radius: 50%;border: 1px solid #e2ecfe;width: 90px;padding: 7px;background-color: #e2ecfe;"></i>
                    <h1 style="color: #304ca8;
                    font-weight: 600;">Please check your email to confirm your account</h1>
                    <p style="color:#304ca8;">Please check your <a href="" style="color:#304ca8;font-weight: 600">{{$user->email}}</a> email to confirm your account before proceeding to the next step.If you dont't see in it your inbox , <a href="" style="color:#304ca8;font-weight: 600">Please check your spam folder</a></p>
                    
                </div>
                    <!-- <div class="row px-3 justify-content-center mt-4 mb-5 border-line"> <img src="https://i.imgur.com/uNGdWHi.png" class="image"> </div> -->
                </div>
            </div>
            <div class="col-lg-6 imageblock">
                <div class="row px-3 justify-content-center mt-4 mb-5 border-line"><img src="{{ asset('admin/images/login-vector-img.png')}}" class="image"> </div>
            </div>
        </div>    
</div>

</body>
</html>
