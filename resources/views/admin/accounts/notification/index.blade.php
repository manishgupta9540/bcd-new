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
             <li>Notification</li>
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
                            @include('admin.accounts.notification.menu')
                           <div class="col-sm-12">
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">Notification </h4>
                                       <p class="pb-border">Notification for Excel Export (OPS & Sales Tracker)</p>
                                    </div>
                                    @if(count($items)>0)
                                       <div class="col-md-6 mt-3 text-right">
                                          <div class="btn-group" style="float:right">     
                                             <a href="#" class="filter0search"><i class="fa fa-filter"></i></a> 
                                          </div>
                                       </div>
                                    @endif
                                 </div>
                                 <div class="search-drop-field" id="search-drop">
                                    <div class="row">
                                       <div class="col-md-3 form-group mb-1 level_selector">
                                          <label>User Name</label><br>
                                          <select class="form-control user_list select" name="user_name" id="user_name">
                                             <option> All </option>
                                             @foreach($users as $user)
                                                <option value="{{ $user->id }}"> {{ $user->name }} </option>
                                             @endforeach
                                          </select>
                                          {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                                       </div>
                                       <div class="col-md-2">
                                       <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                       </div>
                                   </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-12 pt-3">
                                       <div id="candidatesResult">
                                           @include('admin.accounts.notification.ajax')        
                                        </div>
                                   </div>
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
    $(document).ready(function() {
       $(".select").select2();
       $('.filter0search').click(function(){
          $('.search-drop-field').toggle();
       });

      
       var uriNum = location.hash;
       pageNumber = uriNum.replace("#", "");
       // alert(pageNumber);
       getData(pageNumber);

       $(document).on('change','.user_list', function (e){    
            $("#overlay").fadeIn(300);　
            getData(0);
            e.preventDefault();
      });
 
       $(document).on('click','.filterBtn', function (e){    
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

       $(document).on('click', '.status', function (event) {
    
            var id = $(this).attr('data-id');
            var type =$(this).attr('data-type');
            //  alert(user_id);

            swal({
            // icon: "warning",
            type: "warning",
            title: "Are you Change status of this user?",
            text: "",
            dangerMode: true,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "YES",
            cancelButtonText: "CANCEL",
            closeOnConfirm: false,
            closeOnCancel: false,
            },
            function(e){
               if(e==true)
               {
                    $.ajax({
                        type:'POST',
                        url: "{{ url('/')}}"+"/notification/default/status",
                        data: {"_token" : "{{ csrf_token() }}",'id':id,'type':type},        
                        success: function (response) {        
                        
                            if (response.status=='ok') { 
                                // window.setTimeout(function(){
                                //    location.reload();
                                // },2000);
                                // toastr.success("Status Changed Successfully");

                                if(response.type=='enable')
                                {
                                    $('table.notifyTable tr').find("[data-ac='" + id + "']").fadeIn("slow");
                                    $('table.notifyTable tr').find("[data-ac='" + id + "']").removeClass("d-none");

                                    $('table.notifyTable tr').find("[data-dc='" + id + "']").fadeOut("slow");

                                    $('table.notifyTable tr').find("[data-dc='" + id + "']").addClass("d-none");

                                    $('table.notifyTable tr').find("[data-a='" + id + "']").fadeOut("slow");
                                    $('table.notifyTable tr').find("[data-a='" + id + "']").addClass("d-none");

                                    $('table.notifyTable tr').find("[data-d='" + id + "']").fadeIn("slow");

                                    $('table.notifyTable tr').find("[data-d='" + id + "']").removeClass("d-none");

                                    
                                }
                                else if(response.type=='disable')
                                {
                                    $('table.notifyTable tr').find("[data-dc='" + id + "']").fadeIn("slow");
                                    $('table.notifyTable tr').find("[data-dc='" + id + "']").removeClass("d-none");

                                    $('table.notifyTable tr').find("[data-ac='" + id + "']").fadeOut("slow");

                                    $('table.notifyTable tr').find("[data-ac='" + id + "']").addClass("d-none");

                                    $('table.notifyTable tr').find("[data-d='" + id + "']").fadeOut("slow");
                                    $('table.notifyTable tr').find("[data-d='" + id + "']").addClass("d-none");

                                    $('table.notifyTable tr').find("[data-a='" + id + "']").fadeIn("slow");

                                    $('table.notifyTable tr').find("[data-a='" + id + "']").removeClass("d-none");
                                }
                            } 
                            else {
                                
                            }

                            swal.close();
                            
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            // alert("Error: " + errorThrown);
                        }
                        
                    });
               }
               else
               {
                    swal.close();
               }
            });

       });
    });
    
    function getData(page){
         //set data
         // var user_id     =    $(".customer_list").val();                
         var user_id     =    $(".user_list").val();
       //   var from_date   =    $(".from_date").val(); 
       //   var to_date     =    $(".to_date").val();      
 
             $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
 
             $.ajax(
             {
                 url: '?page=' + page+'&user_id='+user_id,
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

      //   var user_id     =    $(".customer_list").val();   
        var user_id     =    $(".user_list").val();             
        //   var check       =    $(".check option:selected").val();

        //   var from_date   =    $(".from_date").val(); 
        //   var to_date     =    $(".to_date").val();    
        
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?user_id='+user_id,
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
