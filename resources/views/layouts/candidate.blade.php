<html lang="{{ app()->getLocale() }}"> 
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>{{ config('app.name', 'BCD System') }}</title>
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="shortcut icon" type="image/x-icon" href="{{url('/').'/admin/images/BCD-favicon.png'}}">
      <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet" />

      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
      <link href="{{ asset('admin/gull/dist-assets/css/themes/lite-purple.min.css') }}" rel="stylesheet" />
      <link href="{{ asset('admin/css/perfect-scrollbar.min.css?ver=1.0') }}" rel="stylesheet" />
      {{-- <link href="{{ asset('admin/fonts/font-awesome-all.css') }}" rel="stylesheet" /> --}}
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.1.0/css/all.css" />

      {{-- <link href="{{ asset('admin/resized/jquery.resizableColumns.css') }}" rel="stylesheet"> --}}
      <link href="{{ asset('css/signature/signature.css') }}" rel="stylesheet">
      <link href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
      <link href="{{ asset('admin/css/style.css?ver=1.7') }}" rel="stylesheet" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
      <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
      {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
      {{-- <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script> --}}
      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>

      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
      <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet"> 
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
      <script src="{{ asset('js/signature/signature.js') }}"></script>
      <script src="https://cdn.jsdelivr.net/npm/jquery.ui.widget@1.10.3/jquery.ui.widget.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.4/jquery.ui.mouse.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js" ></script>

      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
      <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet"/>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js"></script>
      {{-- <script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>
    
      <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css"> --}}
  
      <script>
         $(document).ready(function(){
          $(".commonDatepicker").datepicker({
                   changeMonth: true,
                   changeYear: true,
                   firstDay: 1,
                   autoclose:true,
                   todayHighlight: true,
                   format: 'dd-mm-yyyy',
                });
    
                //start from today
                $( ".datepicker_start_today" ).datepicker({
                   changeMonth: true,
                   changeYear: true,
                   firstDay: 1,
                   autoclose:true,
                   todayHighlight: true,
                   format: 'dd-mm-yyyy',
                   startDate:'today'
                });
         });
         </script>
      
      <!-- jQuery UI -->
      <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>

      <!-- phone input -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/css/intlTelInput.css" rel="stylesheet" />
     <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/intlTelInput.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>

     <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

     <script src="{{ asset('admin/js/bootstrap.bundle.min.js') }}"></script>

     <style type="text/css">
        .intl-tel-input{width:100%;}
     </style>

      <style>
         .pb-border{border-bottom: 1px solid #ddd; padding-top: 0px;padding-bottom: 6px;}

         .user.col{
            margin-right: 0rem;
            padding: 2px;
         }
         .user.col img{
            width: 30px;
            height: 30px;
            border-radius: 50%;
         }

              /* Start by setting display:none to make this hidden.
   Then we position it in relation to the viewport window
   with position:fixed. Width, height, top and left speak
   for themselves. Background we set to 80% white with
   our animation centered, and no-repeating */

   .bcd_loading {
    display: none;
    position: fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 )  
                url(/loader/loaderblue.gif) 
                  50% 50%
                  no-repeat;

}

/* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden */
   body.loading .bcd_loading {
    overflow: hidden;   
}

/* Anytime the body has the loading class, our
   modal element will be visible */
body.loading .bcd_loading {
    display: block;
}
span.show-calender-icon {
        position: relative;
        top: -28px;
        right: -94%;
        font-size: 16px;
        color: #748a9c;
        cursor: pointer;
        z-index: 1;
    }

      </style>

  <script type="text/javascript">
   var loaderPath = "https://f52.in/admin/assets/images/preload.gif";
  </script>

{{-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-V9FYLJ3VPD"></script> --}}
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-252123846-1"></script>
<script>
   // window.dataLayer = window.dataLayer || [];
   // function gtag(){dataLayer.push(arguments);}
   // gtag('js', new Date());

   // gtag('config', 'G-V9FYLJ3VPD');
   window.dataLayer = window.dataLayer || [];
   function gtag(){dataLayer.push(arguments);}
   gtag('js', new Date());

   gtag('config', 'UA-252123846-1');
</script>
  
   </head>
   <body>
      @yield('content')
   </body>
</html>