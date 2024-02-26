<!DOCTYPE html>
<html lang="en">
<head>
  <title>Thank You Page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{url('/').'/admin/images/BCD-favicon.png'}}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Kaushan+Script|Source+Sans+Pro" rel="stylesheet">
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
    color: #fff;
    background: #002e62;
    border: none;
    padding: 10px 50px;
    margin: 30px 0;
    border-radius: 30px;
    text-transform: capitalize;
    box-shadow: 0px 4px 15px #666;
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
  
}

  </style>
</head>
<body>
<div class=content>
  <div class="wrapper-1">
    <div class="wrapper-2">
      <img src="{{ asset('admin/images/BCD-Logo2.png')}}">
      <h1 class="congratulation text-danger">CONGRATULATIONS</h1>
      <p>Your account has been verified now!  </p>
      <img src="{{ asset('admin/images/thank_check.png')}}" class="check">
      <p class="thanku">Thank You  </p>
      <a href="{{Config::get('app.admin_url')}}/login">
        <button class="go-home">
      CLICK HERE TO LOGIN 
      </button></a>
    </div>
    <div class="footer-like">
      <p>Having Trouble?
       <a href="{{url('/contact')}}">Contact Us</a>
      </p>
    </div>
</div>
</div>

  

</body>
</html>
