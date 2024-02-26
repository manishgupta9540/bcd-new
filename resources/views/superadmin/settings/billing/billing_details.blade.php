@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Account / Billing </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/app/home') }}">Dashboard</a>
             </li>
             {{-- <li>
                 <a href="{{ url('/settings/general') }}">Accounts</a>
             </li> --}}
             <li>
                <a href="{{ url('/app/settings/billing') }}">Billing</a>
            </li>
             <li>Summary</li>
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
                  @include('superadmin.settings.left-sidebar') 
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
                                       <h4 class="card-title mb-1 mt-3">Billing Summary</h4>
                                       <p class="pb-border"> Billing summary & history  </p>
                                    </div>
                                    {{-- <div class="col-md-6 text-right">
                                       <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a>
                                    </div> --}}
                                    <div class="col-md-6 pt-3">
                                       <div class="btn-group" style="float:right">     
                                          <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="search-drop-field pb-3" id="search-drop">
                                    <div class="row">
                                        <div class="col-md-3 form-group mb-1">
                                            <label> From date </label>
                                            <input class="form-control from_date commonDatepicker" type="text" placeholder="From date">
                                        </div>
                                        <div class="col-md-3 form-group mb-1">
                                            <label> To date </label>
                                            <input class="form-control to_date commonDatepicker" type="text" placeholder="To date">
                                        </div>
                                        <div class="col-md-3 form-group mb-1 level_selector">
                                          <label>Check Name</label><br>
                                          <select class="form-control customer_list select " name="customer" id="customer">
                                              <option> All </option>
                                              @if(count($services)>0)
                                                @foreach ($services as $item)
                                                <option value="{{ $item->id }}"> {{$item->name}} </option>
                                                @endforeach
                                              @endif
                                          </select>
                                      </div>
                                        <div class="col-md-2">
                                        <button class="btn btn-primary search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                          <label style="font-size: 16px;"> Customer : <strong>{{Helper::user_name($billing->business_id)}} ({{Helper::company_name($billing->business_id)}})</strong></label>
                                          {{-- <label style="font-size: 16px;"> <b> </b></label> --}}
                                          {{-- <input type="hidden" name="customer" value="{{ $sla->business_id }}"> --}}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                          <label style="font-size: 16px;"> Duration : <strong>({{date('d F',strtotime($billing->start_date))}} - {{date('d F',strtotime($billing->end_date))}}) {{date('Y',strtotime($billing->start_date))}}</strong></label>
                                          {{-- <label style="font-size: 16px;"> <b> </b></label> --}}
                                          {{-- <input type="hidden" name="customer" value="{{ $sla->business_id }}"> --}}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                          <label style="font-size: 16px;"> Total Price : <i class="fas fa-rupee-sign"></i> <strong>{{$billing->total_amount}} </strong></label>
                                          {{-- <label style="font-size: 16px;"> <b> </b></label> --}}
                                          {{-- <input type="hidden" name="customer" value="{{ $sla->business_id }}"> --}}
                                        </div>
                                    </div>
                                </div>
                                <div id="candidatesResult">
                                    @include('superadmin.settings.billing.billing_details_ajax')
                                </div>
                                 <!-- ./business detail -->
                                 
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
@stack('scripts')
<script type="text/javascript">
   //
   $(document).ready(function() {
   //
//    $(document).on('click','#clickSelectFile',function(){ 
   
//        $('#fileupload').trigger('click');
       
//    });
   
//    $(document).on('click','.remove-image',function(){ 
       
//        $('#fileupload').val("");
//        $(this).parent('.image-area').detach();
   
//    });
   
//    $(document).on('change','#fileupload',function(e){ 
//       // alert('test');
//       //show process 
//       // $("").html("Uploading...");
//       $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 
   
//       var fd = new FormData();
//       var inputFile = $('#fileupload')[0].files[0];
//       fd.append('file',inputFile);
//       fd.append('_token', '{{csrf_token()}}');
//       //
      
//       $.ajax({
//                type: 'POST',
//                url: "{{ url('/company/upload/logo') }}",
//                data: fd,
//                processData: false,
//                contentType: false,
//                success: function(data) {
//                   console.log(data);
//                   if (data.fail == false) {
                  
//                   //reset data
//                   $('#fileupload').val("");
//                   $("#fileUploadProcess").html("");
//                   //append result
//                   $("#fileResult").html("<div class='image-area'><img src='"+data.filePrev+"'  alt='Preview'><a class='remove-image' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.file_id+"'></div>");
      
//                   } else {
      
//                   $("#fileUploadProcess").html("");
//                   alert("please upload valida file! allowed file type , Image, PDF, Doc, Xls and txt ");
//                   console.log("file error!");
                  
//                   }
//                },
//                error: function(error) {
//                   console.log(error);
//                   // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
//                }
//             }); 
//          return false;
      
//       });

    $(".select").select2();
    $('.filter0search').click(function(){
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

    $(document).on('change','.customer_list, .from_date, .to_date', function (e){    
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
});
    function getData(page){
        //set data
        var user_id     =    $(".customer_list").val();                

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();      

            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

            $.ajax(
            {
                url: '?page=' + page+'&customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date,
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
        
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date,
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
