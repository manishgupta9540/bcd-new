@extends('layouts.client')
<style>
   /* .table tr {
      cursor: pointer;
   } */
   .table{
      background-color: #fff !important;
   }
   .hedding h1{
      color:#fff;
      font-size:25px;
   }
   .main-section{
      margin-top: 120px;
   }
   .hiddenRow {
       padding: 0 4px !important;
       background-color: #eeeeee;
       font-size: 13px;
   }
   .accordian-body span{
      color:#a2a2a2 !important;
   }
   </style>
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">

         <!-- ============Breadcrumb ============= -->
   <div class="row">
      <div class="col-sm-11">
         <ul class="breadcrumb">
         <li>
         <a href="{{ url('/my/home') }}">Dashboard</a>
         </li>
         <li>Instant Verification</li>
         </ul>
      </div>
      <!-- ============Back Button ============= -->
      <div class="col-sm-1 back-arrow">
         <div class="text-right">
            <a href="{{ url()->previous() }}"> <i class="fas fa-arrow-circle-left fa-2x"></i></a>
         </div>
      </div>
   </div>   
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Accounts/Instant Verification </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         
            <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('clients.accounts.sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style="background:#fff">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 ">
                              
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">Instant Verification Usage</h4>
                                       <p class="pb-border"> Instant Verification Usage overview </p>
                                    </div>
                                    <div class="col-md-6 text-right">
                                       <!-- <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a> -->
                                    </div>
                                 </div>

                                 <div id="candidatesResult">
                                    @include('clients.accounts.api.user-api-ajax')
                                 </div>
                                 <!-- ./billing detail -->
                                 
                           </div>
                        </section>
                        <!-- ./section -->
                        <!--  -->
                        <!-- ./section -->
                     </div>
                  </div>
                  <!-- end right sec -->
               
        
      </div>
   </div>
</div>

{{-- Modal for download api details    --}}

<div class="modal" id="download_api">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Download API Details</h4>
            <button type="button" class="close btn-disable" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/my/api-usage/download')}}" id="downloadapiFrm">
         @csrf
           <input type="hidden" name="service_id" id="service_id">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               <div class="form-group">
                     <label for="label_name">Select Type : </label>
                     <select class="form-control type" name="type" id="type">
                        <option value="">--Select--</option>
                        <option value="excel">Excel</option>
                        <option value="pdf">PDF</option>
                     </select>
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-type"></p> 
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info btn-disable download_btn">Submit </button>
               <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

  <!-- Footer Start -->
  <div class="flex-grow-1"></div>
  
</div>
@stack('scripts')
<script type="text/javascript">
   
   $(document).ready(function() {

    $(".select").select2();
    $('.filter0search').click(function(){
            $('.search-drop-field').toggle();
    });
    $('.filter_close').click(function(){
        $('.search-drop-field').toggle();
    });

    var uriNum = location.hash;
    pageNumber = uriNum.replace("#", "");
    // alert(pageNumber);
    getData(pageNumber);

    $(document).on('change','.from_date',function() {

      var from = $('.from_date').datepicker('getDate');
      var to_date   = $('.to_date').datepicker('getDate');

      if($('.to_date').val() !=""){
         if (from > to_date) {
            alert ("Please select appropriate date range!");
            $('.from_date').val("");
            $('.to_date').val("");

         }
      }

    });
      //
      $(document).on('change','.to_date',function() {

         var to_date = $('.to_date').datepicker('getDate');
         var from   = $('.from_date').datepicker('getDate');
            if($('.from_date').val() !=""){
               if (from > to_date) {
                  alert ("Please select appropriate date range!");
                  $('.from_date').val("");
                  $('.to_date').val("");
               
               }
            }

      });

      $(document).on('click','.filterBtn', function (e){    
         $("#overlay").fadeIn(300);　
         getData(0);
         e.preventDefault();
      });

      $(document).on('change','.customer_list, .from_date, .to_date,.status', function (e){    
         $("#overlay").fadeIn(300);　
         getData(0);
         e.preventDefault();
      });

      $(document).on('click', '.pagination a,.searchBtn',function(event){
         //loader
         $("#overlay").fadeIn(300);　
         $('li').removeClass('active');
         $(this).parent('li').addClass('active');
         event.preventDefault();
         var myurl = $(this).attr('href');
         var page  = $(this).attr('href').split('page=')[1];
         getData(page);
      });

      $(document).on('click','.downloadDetails',function(){
         var service_id=$(this).attr('data-service');
         $('#service_id').val(service_id);
         $('#download_api').modal({
               backdrop: 'static',
               keyboard: false
         });
      });

      $(document).on('submit', 'form#downloadapiFrm', function (event) {
         event.preventDefault();
         //clearing the error msg
         $('p.error-container').html("");
         $('.form-control').removeClass('border-danger');
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
         $('.btn-disable').attr('disabled',true);
         if ($('.download_btn').html() !== loadingText) {
            $('.download_btn').html(loadingText);
         }
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {

                  // console.log(response);
                  window.setTimeout(() => {
                     $('.btn-disable').attr('disabled',false);
                     $('.download_btn').html('Submit');
                  }, 2000);
                  //show the form validates error
                  if(response.success==false ) {                              
                     for (control in response.errors) {  
                        $('.'+control).addClass('border-danger'); 
                        $('#error-' + control).html(response.errors[control]);
                     }
                  }
                  if(response.success==true)
                  {
                     var url = response.url;

                  // if(url.includes('pdf'))
                  // {
                        // var Url = window.URL || window.webkitURL;
                        var arr = url.split("/");


                        // // Convert the Byte Data to BLOB object.
                        // var blob = new Blob([url], { type: "application/octetstream" });

                        var fileName = arr[arr.length - 1];

                        // //Check the Browser type and download the File.
                        // var isIE = false || !!document.documentMode;
                        // if (isIE) {
                        //       window.navigator.msSaveBlob(blob, fileName);
                        // } else {
                              // link = Url.createObjectURL(blob);
                              var a = $("<a />");
                              a.attr('id','download_lnk_btn');
                              a.attr("download", fileName);
                              a.attr("href", url);
                              // a.attr("target","_blank");
                              $("body").append(a);
                              a[0].click();
                              $('#download_lnk_btn').remove();
                              // $("body").remove(a);
                              // Url.revokeObjectURL(link);
                        // }
                  // }
                  // else
                  // {
                     // window.open(url,'_blank');
                  // }
                     $('#download_api').modal('hide');
                  }
                  else
                  {
                     // window.location=response;
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  // alert("Error: " + errorThrown);
            }
         });
         return false;
      });
   });

   function getData(page){
         //set data
         var user_id     =    $(".customer_list").val();                

         var from_date   =    $(".from_date").val(); 
         var to_date     =    $(".to_date").val();      
         var status      =    $('.status').val();

               $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

               $.ajax(
               {
                  url: '?page=' + page+'&customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&status='+status,
                  type: "get",
                  datatype: "html",
               })
               .done(function(data)
               {
                  $("#candidatesResult").empty().html(data);
                  $("#overlay").fadeOut(300);
                  //debug to check page number
                  location.hash = page;
               })
               .fail(function(jqXHR, ajaxOptions, thrownError)
               {
                  alert('No response from server');

               });

   }

    function setData(){

        var user_id     =    $(".customer_list").val();                
      //   var check       =    $(".check option:selected").val();

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val(); 
        var status      =    $('.status').val();   
        
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&status='+status,
                type: "get",
                datatype: "html",
            })
            .done(function(data)
            {
            console.log(data);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                //alert('No response from server');
            });

    }
                     
</script>  
@endsection
