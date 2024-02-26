@extends('layouts.client')
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
             <a href="{{ url('/my/home') }}">Dashboard</a>
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
                  @include('clients.accounts.sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style="background:#fff">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                            {{-- @include('admin.accounts.checkprice.menu') --}}
                           <div class="col-sm-12 ">
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">Check Price </h4>
                                       <p class="pb-border"></p>
                                    </div>
                                    <div class="col-md-6 mt-3 text-right">
                                       <div class="btn-group" style="float:right">     
                                          <a href="#" class="filter0search"><i class="fa fa-filter"></i></a> 
                                       </div>
                                    </div>
                                 </div>
                                 <div class="search-drop-field" id="search-drop">
                                    <div class="row">
                                       <div class="col-md-3 form-group mb-1 level_selector">
                                          <label>Service Name</label><br>
                                          <select class="form-control service_list select " name="service_name" id="service_name">
                                             <option> All </option>
                                             @foreach($services as $service)
                                                <option value="{{ $service->id }}"> {{ $service->name }} </option>
                                             @endforeach
                                          </select>
                                          {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                                       </div>
                                       <div class="col-md-2">
                                          <button class="btn btn-danger  resetBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i>  Reset </button>
                                       </div>
                                       <div class="col-md-2">
                                       <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                       </div>
                                   </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-12 pt-3">
                                       <div id="candidatesResult">
                                          @include('clients.accounts.checkprice.ajax')
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
{{-- <div class="modal" id="edit_custom_price">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Edit Custom Price</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('/checkprice/update')}}" id="checkpriceupdate">
          @csrf
            <input type="hidden" name="id" id="id">
             <div class="modal-body">
             
             <div class="form-group">
                <label for="label_name"> Service Name :</label>
                <span style="margin-bottom: 2px;" class="text-dark" id="service_name"></span> 
             </div>
             <div class="form-group">
                <label for="label_name"> Default Price :</label>
                <span style="margin-bottom: 2px;" class="text-dark" id="default_pr"></span> 
             </div>
                <div class="form-group">
                      <label for="label_name"> Price </label>
                      <input type="text" id="price" name="price" class="form-control price" placeholder="Enter Price"/>
                      <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-price"></p> 
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
             </div>
          </form>
       </div>
    </div>
 </div> --}}
 
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
   
</div>

@stack('scripts')
{{-- <script>
$(document).ready(function(){
    $('.editcustompricebtn').click(function(){
        var id=$(this).attr('data-id');
        var price=$(this).attr('data-price');
        var default_p=$(this).attr('data-default_p');
        var service_name=$(this).attr('data-service');
        $('#id').val(id);
        $('#price').val(price);
        $('#default_pr').html('<i class="fas fa-rupee-sign"></i> '+ default_p);
        $('#service_name').html(service_name);
        $('#edit_custom_price').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
    $(document).on('submit', 'form#checkpriceupdate', function (event) {
    
        $("#overlay").fadeIn(300);　
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var $btn = $(this);
        $('.error-container').html('');
        $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                $('.error-container').html('');
                if (data.fail && data.error_type == 'validation') {
                        
                        //$("#overlay").fadeOut(300);
                        for (control in data.errors) {
                        $('input[price=' + control + ']').addClass('is-invalid');
                        $('#error-' + control).html(data.errors[control]);
                        }
                } 
                if (data.fail && data.error == 'yes') {
                    
                    $('#error-all').html(data.message);
                }
                if (data.fail == false) {
                    toastr.success("Price Updated Successfully");
                    window.setTimeout(function(){
                        location.reload();
                    },2000);
                    
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                
                alert("Error: " + errorThrown);

            }
        });
        return false;

    });
});
</script> --}}
              
<script>
    $(document).ready(function() {
       $(".select").select2();
       $('.filter0search').click(function(){
          $('.search-drop-field').toggle();
       });

       $(document).on('click', '.resetBtn' ,function(){
         $(this).parent().parent().find('input').val('');
         $(this).parent().parent().find('select').val('All').trigger('change');
         var uriNum = location.hash;
         pageNumber = uriNum.replace("#","");
         getData(pageNumber);
       });


       var uriNum = location.hash;
       pageNumber = uriNum.replace("#", "");
       // alert(pageNumber);
       getData(pageNumber);
 
       $(document).on('click','.filterBtn', function (e){    
         $("#overlay").fadeIn(300);　
         getData(0);
         e.preventDefault();
       });
 
       $(document).on('change','.service_list', function (e){    
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
         var service_id     =    $(".service_list").val();                
 
       //   var from_date   =    $(".from_date").val(); 
       //   var to_date     =    $(".to_date").val();      
 
             $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
 
             $.ajax(
             {
                 url: '?page=' + page+'&service_id='+service_id,
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

      var service_id     =    $(".service_list").val();               
        //   var check       =    $(".check option:selected").val();

        //   var from_date   =    $(".from_date").val(); 
        //   var to_date     =    $(".to_date").val();    
        
            $.ajax(
            {
                url: "{{ url('/my') }}"+'/candidates/setData/?service_id='+service_id,
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
