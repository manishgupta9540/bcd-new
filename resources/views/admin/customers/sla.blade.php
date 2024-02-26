@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
      <div class="col-md-12">
         <div class="card text-left">
            <div class="card-body">
               <div class="row">
                  <div class="col-md-8">
                     <h4 class="card-title mb-1"> Customer </h4>
                     <p> Details of customer </p>
                  </div>
                  <div class="col-md-4">        
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <div class="details-box">
                        <ul>
                           <li><strong>Company Name :</strong> {{ ucfirst($item->company_name) }}</li>
                           <li><strong>Contact Person :</strong> {{$item->name}}</li>
                           <li><strong>Email :</strong> {{$item->email}}</li>
                           <li><strong>Phone :</strong> {{$item->phone}}</li>
                           <li><strong>Address :</strong> {{$item->address_line1.', '.$item->zipcode.' '.$item->city_name}}</li>
                        </ul>
                     </div>
                     <div class="table-box mt-40">
                         <!-- include menu -->
                         @include('admin.customers.menu')
                        <!-- include menu -->
                        <div class="tab-content" id="myIconTabContent">
                           <div class="tab-pane fade " id="candidatetb1" role="tabpanel" aria-labelledby="candidatetab">
                              <div class="row" style="margin-bottom:15px">
                                 {{-- <div class="col-md-2">
                                    <div class="search-bar">
                                       <input type="text" placeholder="Search" autocomplete="off" style="padding: 5px;border-radius: 4px;background: #f6f8fc;">
                                    </div>
                                 </div> --}}
                              </div>
                              <div class="table-responsive tableFixHead" style="height: 300px;">
                              </div>
                           </div>
                           <!-- 1st Tab Has Been End Here -->
                           <!-- 2nd Tab Starts From Here -->
                           <div class="row" style="margin-bottom:15px">
                              <div class="col-md-2">
                                 <div class="search-bar">
                                    <input type="text" class="search_list" placeholder="Search" autocomplete="off" style="padding: 5px;border-radius: 4px;background: #f6f8fc;">
                                 </div>
                              </div>
                           </div>
                           <div class="tab-pane active show fade" id="jobtb1" role="tabpanel" aria-labelledby="jobtab">
                              <div id="candidatesResult">
                                 @include('admin.customers.sla_ajax')
                             </div>
                           </div>
                           <!-- 2nd Tab Has Been End Here -->
                          
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
  
</div>

<script type="text/javascript">

   $(document).on('change keyup','.search_list', function (e){    
           $("#overlay").fadeIn(300);　
           getData(0);
           e.preventDefault();
   });

   function getData(page){
       //set data
       // var user_id     =    $(".customer_list").val();                
       // var check       =    $(".check option:selected").val();
       
   
       // var from_date   =    $(".from_date").val(); 
       // var to_date     =    $(".to_date").val();      
       // var candidate_id=    $(".candidate_list option:selected").val();
       // var mob = $('.mob').val();
       // var ref = $('.ref').val();
       // var email = $('.email').val();
       
       var search=$('.search_list').val()
   
           $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
   
           $.ajax(
           {
               url: '?page=' + page+'&search='+search,
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
   
       // var user_id     =    $(".customer_list").val();                
       // // var check       =    $(".check option:selected").val();
   
       // var from_date   =    $(".from_date").val(); 
       // var to_date     =    $(".to_date").val();    
       // var candidate_id=    $(".candidate_list option:selected").val();                            
       // var mob = $('.mob').val();
       // var ref = $('.ref').val();
       // var email = $('.email').val();

       var search=$('.search_list').val();
           $.ajax(
           {
               url: "{{ url('/') }}"+'/candidates/setData/?search='+search,
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
