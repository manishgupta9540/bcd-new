@extends('layouts.admin')
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
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>
                 <a href="{{ url('/settings/general') }}">Accounts</a>
             </li>
             <li>Check Price</li>
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
                  <div class="col-md-9 content-wrapper" style="background:#fff">
                     <div class="formCover py-2" style="height: 100vh;">
                        <!-- section -->
                        <section>
                            
                           <div class="col-sm-12">
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">Tasks</h4>
                                       <p class="pb-border">Assigned,Completed and Pending tasks details </p>
                                    </div>
                                    {{-- @if(count($items)>0) --}}
                                    <div class="col-md-6 mt-2">
                                       <div class="btn-group" style="float:right">
                                         <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>   
                                       </div>
                                    </div>
                                    {{-- @endif --}}
                                 </div>
                                 <div class="search-drop-field" id="search-drop">
                                    <div class="row">
                                       <div class="col-md-2 form-group mb-1">
                                          <label> From date </label>
                                          <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
                                       </div>
                                       <div class="col-md-2 form-group mb-1">
                                          <label> To date </label>
                                          <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
                                       </div>
                                       {{-- <div class="col-4">
                                          <div class="form-group">
                                            <label>Duration Type : </label>
                                            <select class="form-control type" name="type">
                                              <option value="daily" @if(stripos($type,'daily')!==false) selected @endif>Daily</option>
                                              <option value="weekly" @if(stripos($type,'weekly')!==false) selected @endif>Weekly</option>
                                              <option value="monthly" @if(stripos($type,'monthly')!==false) selected @endif>Monthly</option>
                                            </select> 
                                          </div>
                                       </div> --}}
                                       <div class="col-md-2 form-group mb-1 level_selector">
                                          <label>User's Name</label><br>
                                          <select class="form-control user_list select" name="user" id="user">
                                             <option value=''>-Select-</option>
                                             @foreach($users as $item)
                                             <option value="{{$item->id}}"> {{ ucfirst($item->name)}} </option>
                                             @endforeach
                                          </select>
                                       </div>
                                       <div class="col-md-2">
                                       <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                       </div>
                                   </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-12 pt-3">
                                       <div id="candidatesResult">
                                           @include('admin.accounts.task.ajax')        
                                        </div>
                                   </div>
                                 </div>
                                 <!-- ./business detail -->
                                 
                           </div>
                        </section>
                        
                     </div>
                  </div>
                  <!-- end right sec -->
         
      </div>
   </div>
</div>


@stack('scripts')
                     
<script>
   $(document).ready(function(){
      $('.filter0search').click(function(){
            $('.search-drop-field').toggle();
      });
      $('.filter_close').click(function(){
                  $('.search-drop-field').toggle();
      });
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
       // filterBtn
      $(document).on('change','.from_date, .to_date,.user_list', function (e){    
         $("#overlay").fadeIn(300);　
         getData(0);
         e.preventDefault();
      });

      $(document).on('click','.filterBtn', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
      });
      function getData(page){
         //set data
         // var type  =   $('.type').val();
         // var user_id     =    $(".customer_list").val();                
         // var check       =    $(".check option:selected").val();
         // var sla_id   =     $(".sla_list option:selected").val();
         var cus_user_id   =     $(".user_list option:selected").val();
         var from_date   =    $(".from_date").val(); 
         var to_date     =    $(".to_date").val();      
         // var candidate_id=    $(".candidate_list option:selected").val();
         // var rows = $("#rows option:selected").val();
         // var service_id = $("#service option:selected").val();
         // var task_type = $("#task_type option:selected").val();
         // var assign_status = $("#assign_status option:selected").val();
         // var complete_status = $("#complete_status option:selected").val();
         //   var mob = $('.mob').val();complete_status
         //   var ref = $('.ref').val();
         //   var email = $('.email').val();
         //   var report_status=$('.report_status').val();               
      
         $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
   
         $.ajax(
         {
               url: '?page='+ page+'&from_date='+from_date+'&to_date='+to_date+'&user_id='+cus_user_id,
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

         return false;
      
      }
   });
 </script>
@endsection
