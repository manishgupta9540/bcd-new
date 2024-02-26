@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>
                 <a href="{{ url('/settings/general') }}">Accounts</a>
             </li>
             <li>SLA</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow">
             <div class="text-right">
             <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div>
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Account / SLA </h3>
               </div>
            </div>
         </div>
      </div>  --}}
      <div class="row">
              <div class="col-md-12">   
              
              </div>
            <div class="col-md-3 content-container">
            <!-- left-sidebar -->
                @include('admin.accounts.left-sidebar') 
            </div>
                <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style=" background:#fff;">
                     <div class="col-sm-12 mt-3">
                        <?php if(isset($_GET['created']) && $_GET['created']=='true'){ ?>
                           <div class="alert alert-success" role="alert">
                                 <strong>SLA Created Successfully</strong>
                           </div>
                        <?php } ?>
                        @if ($message = Session::get('success'))
                           <div class="alert alert-success">
                              <strong>{{ $message }}</strong> 
                           </div>
                        @endif
                     </div>
                     <div class="formCover" style="height: 100vh; ;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 ">
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-5">
                                       <h4 class="card-title mb-1 mt-3">SLA </h4>
                                       <p class="pb-border"> Your customer's SLA  </p>
                                    </div>
                                       @php
                                          $ADD_ACCESS    = false;
                                          $VIEW_ACCESS   = false;
                                          $EDIT_ACCESS = false;
                                          $PDF_ACCESS   = false;
                                          $SLA_ACCESS   = false;
                                          $ADD_ACCESS    = Helper::can_access('SLA Create','');
                                          $VIEW_ACCESS   = Helper::can_access('SLA View','');
                                          $EDIT_ACCESS = Helper::can_access('SLA Edit','');
                                          $PDF_ACCESS = Helper::can_access('SLA PDF download','');
                                          $SLA_ACCESS = Helper::can_access('SLA','');
                                    
                                          
                                          // $REPORT_ACCESS   = false;
                                          // $VIEW_ACCESS   = false;SLA
                                       @endphp 
                                       
                                       
                                          <div class="col-md-6 text-right mt-3">
                                             <div class="btn-group" style="float:right">
                                                @if ($ADD_ACCESS)
                                                <a href="{{ url('/sla/create') }}" class="btn btn-sm btn-info"> <i class="fa fa-plus"></i> Create new</a>
                                                @endif
                                                <!-- <a href="#" class="filter0search"><i class="fa fa-filter"></i></a> -->
                                             </div>
                                          </div>
                                          <div class="col-md-1 mt-2">
                                             <div class="btn-group" style="float:right">
                                                <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>   
                                             </div>
                                          </div>

                                          <div class="search-drop-field" id="search-drop" style="z-index: 1">
                                             <div class="row">
                                                <div class="col-12">           
                                                   <div class="btn-group" style="float:right;font-size:24px;">   
                                                      <a href="#" class="filter_close text-danger"><i class="far fa-times-circle"></i></a>        
                                                   </div>
                                                </div>
                                          </div>
                                          <div class="row">
                                             <div class="col-md-3 form-group mb-1">
                                                <label> SLA ID </label>
                                                <input class="form-control ref" type="text" placeholder="SLA ID">
                                             </div>
                                            
                                             <div class="col-md-3 form-group mb-1 level_selector">
                                                <label>Company Name</label><br>
                                                <select class="form-control customer_list " name="customer" id="customer">
                                                   <option value=''>-Select-</option>
                                                   @foreach($customers as $item)
                                                      <option value="{{$item->id}}">{{$item->company_name}} </option>
                                                   @endforeach   
                                                </select>
                                             </div>
                                             <div class="col-md-3 form-group mb-1 level_selector">
                                                <label>SLA Name</label><br>
                                                <select class="form-control sla_name select2" name="sla_name" id="sla_name">
                                                   <option value=''>-Select-</option>
                                                </select>
                                             </div>
                                             <div class="col-md-3 form-group mb-1">
                                             <label for="picker1"><strong>Check</strong> </label>
                                                <select class="form-control check" name="check[]" id="check" data-actions-box="true" data-selected-text-format="count>1" multiple>
                                                      @foreach($services as $service)
                                                         <option value="{{ $service->id}}">{{ $service->name  }}</option>   
                                                      @endforeach
                                                </select>
                                             </div>
                                       
                                          <div class="col-md-1">
                                             <button class="btn btn-danger  resetBtn" style="padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i>  Reset </button>
                                          </div>
                                          <div class="col-md-1">
                                             <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                          </div>
                                       </div>
                                    </div>
                                    
                                 </div>
                                 <div id="slaResult">
                                    @include('admin.accounts.sla_ajax')
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


<script>
$(document).ready(function(){

   $(".select2").select2();
   $("#customer").select2();

   $('.tool').tooltip();
   $('.check').selectpicker();
    //$('.verified22').selectpicker();
   //$("#check").select2();
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

  


$(document).on('click','.filterBtn', function (e){    
   $("#overlay").fadeIn(300);　
   getData(pageNumber);
   e.preventDefault();
});

$(document).on('change','.ref,.customer_list,.sla_name,.check', function (e){    
   $("#overlay").fadeIn(300);　
   getData(pageNumber);
   e.preventDefault();
});

$(document).on('click', '.resetBtn' ,function(e){

   $("input[type=text], textarea, select").val("");
      $('.ref').val('');
      $('#customer').val(null).trigger('change');
      $('#sla_name').val(null).trigger('change');
      $('#check').val(null).trigger('change');
      getData(pageNumber);
      e.preventDefault();
});

$(document).on('change','.customer_list',function(e) {
      e.preventDefault();

      $('.sla_name').empty('');
      $('.sla_name').append("<option value=''>-Select-</option>");
      var customer_id = $('.customer_list option:selected').val();
      if(customer_id!=''){
      $.ajax({
      type:"POST",
      url: "{{ url('/customers/sla/getlist') }}",
      data: {"_token": "{{ csrf_token() }}",'customer_id':customer_id},      
      success: function (response) {
            if(response.success==true  ) {   
               $.each(response.data, function (i, item) {
               $(".sla_name").append("<option value='"+item.id+"'>" + item.title + "</option>");
               });
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
}
   return false;
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


$(document).on('click','.slaexcel',function(){
      var id = $(this).attr('data-id');
   
         $.ajax({
            type:'get',
            url: "{{ url('sla-export-data') }}/"+id,
            success: function (response) {
               if(response.success == true){
                  window.open(response.url);
               }
               else{
                  $('#loading').html(response.error);
               }
            },
            error: function (xhr, textStatus, errorThrown) {
               // alert("Error: " + errorThrown);
            }
         });
   });
});

   $(document).on('click','.slacsv',function(){
      var id = $(this).attr('data-id');
   
         $.ajax({
            type:'get',
            url: "{{ url('csv-export-data') }}/"+id,
            success: function (response) {
               if(response.success == true){
                  window.open(response.url);
               }
               else{
                  $('#loading').html(response.error);
               }
            },
            error: function (xhr, textStatus, errorThrown) {
               // alert("Error: " + errorThrown);
            }
         });
   });

  

function getData(page){
  //set data
  var ref_id      =    $(".ref").val();
  
  var user_id     =    $(".customer_list").val();             
  var sla_name    =    $(".sla_name option:selected").val()==undefined?'':$(".sla_name option:selected").val();
  var check       =    $(".check option:selected").val()==undefined?'':$(".check option:selected").val();
  
   //   var search = $('.search').val();

        var check=0;  
        var select = document.getElementById("check");

        var check_id=[];

         var j=0;
         $('.check option:selected').each(function () {
            check_id[j] = $(this).val();
            j++;
         });
         
      $('#slaResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

      $.ajax(
      {
          url: '?page=' + page+'&ref='+ref_id+'&customer_id='+user_id+'&sla_name='+sla_name+'&check='+check_id,
          type: "get",
          datatype: "html",
      })
      .done(function(data)
      {
          $("#slaResult").empty().html(data);
          $("#overlay").fadeOut(300);
          //debug to check page number
          location.hash = page;
      })
      .fail(function(jqXHR, ajaxOptions, thrownError)
      {
          //alert('No response from server');

      });

}

function setData(){

      var ref_id    =    $(".ref").val(); 
      var user_id   =    $(".customer_list").val(); 
      var sla_name  =    $(".sla_name option:selected").val(); 
      var check     =    $(".check option:selected").val();

      $.ajax(
      {
            url: "{{ url('/') }}"+'/sla/setData/?ref='+ref_id+'&customer_list='+user_id+'&sla_name='+sla_name+'&check_id='+check,
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
