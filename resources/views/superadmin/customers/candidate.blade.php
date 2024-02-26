@extends('layouts.superadmin')
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
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item "><a href="{{ url('/app/customers')}}" class="text-dark">Customer</a></li>
                       
                        <li class="breadcrumb-item "><a href="{{ url('/app/customers/show',['id'=>Request::segment(5)])}}"   class="text-dark">Client</a></li>

                       
                        {{-- <li class="breadcrumb-item "><a href="{{ url('/app/customers/show',['id'=>base64_encode($item->id)])}}" class="text-dark">Client</a></li> --}}
                        <li class="breadcrumb-item active_text">Candidate</li>
                    </ol>
                     <h4 class="card-title mb-3"> Client </h4>
                     <p> Details of Client </p>
                  </div>
                  <div class="col-md-4">        
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <div class="details-box">
                        <ul>
                           <li><strong>Comapny Name :</strong> {{$item->company_name}}</li>
                           <li><strong>Contact Person :</strong> {{$item->contact_person}}</li>
                           <li><strong>Email :</strong> {{$item->email}}</li>
                           <li><strong>Phone :</strong> {{$item->phone}}</li>
                           <li><strong>Address :</strong> {{$item->address_line1.', '.$item->zipcode.' '.$item->city_name}}</li>
                        </ul>
                     </div>
                     <div class="table-box mt-40">
                        <!-- menu -->
                        @include('superadmin.customers.tab-menu-item')
                        <!-- ./menu -->
                        <div class="tab-content" id="myIconTabContent">
                           <div class="tab-pane fade active show" id="candidatetb1" role="tabpanel" aria-labelledby="candidatetab">
                              {{-- <div class="row" style="margin-bottom:15px"> --}}
                                 <div class="row" style="margin-bottom:15px">
                                    <div class="col-md-3 form-group mb-1">
                                        <label for="picker1"> Customer </label>
                                        <select class="form-control customer_list" name="customer" id="customer">
                                           <option>-Select-</option>
                                           @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}"> {{ $customer->first_name.'-'.$customer->company_name}} </option>
                                            @endforeach
                                        </select>
                                     </div>
                                     <div class="col-md-2 form-group mb-1">
                                        <label for="from_date"> From date </label>
                                        <input class="form-control from_date commonDatePicker" id="from_date" type="text" placeholder="From date">
                                     </div>
                                     <div class="col-md-2 form-group mb-1">
                                        <label for="to_date"> To date </label>
                                        <input class="form-control to_date commonDatePicker" id="to_date" type="text" placeholder="To date">
                                     </div>
                                     <div class="col-md-3 form-group mb-1">
                                        <label for="picker1"> Candidate </label>
                                        <select class="form-control candidate_list" name="candidate" id="candidate_list">
                                           <option value="">-Select-</option>
                                           
                                        </select>
                                     </div>
                                     <div class="col-md-2">
                                        <button class="btn btn-primary search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                     </div>
                                </div>
                                <!-- export data -->
                                <div class="row">
                                    
                                    <div class="col-md-4 form-group mb-3">
                                    <label for="picker1"> Check </label>
                                    <select class="form-control check" name="customer" id="customer">
                                        <option value="">-Select-</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id}}">{{ $service->name  }}</option>   
                                        @endforeach
                                        </select>
                                    
                                     </div>
                                    <div class="col-md-6 form-group mt-4">
                                        <a class="btn-link " id="exportExcel" href="javascript:;"> <i class="fa fa-file-excel-o"></i> Export Excel</a> 
                                    </div>
                                     
                                </div>
                                <!-- ./export data -->
                              {{-- </div> --}}
                              
                              <div class="table-responsive tableFixHead" style="height: 300px;">
                                 <table class="table table-bordered">
                                    <thead>
                                       <tr>
                                          <th scope="col">#</th>
                                          {{-- <th scope="col">Company Name</th> --}}
                                          <th scope="col">Name</th>
                                          <th scope="col">Email</th>
                                          <th scope="col">Phone</th>
                                          <th scope="col">Status</th>
                                          <th scope="col">Action</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @if(count($candidates)>0)
                                          @foreach($candidates as $candidate)
                                          <tr>
                                             <th scope="row">BCD-{{$candidate->id}}</th>
                                             {{-- <td><b>{{$candidate->company_name}}</b></td> --}}
                                             <td>{{$candidate->name}}</td>
                                             <td>{{$candidate->email}}</td>
                                             <td>{{$candidate->phone}}</td>
                                             <td></td>
                                             <td>
                                                {{-- <a href="{{ route('/candidate/show',['id'=>base64_encode($candidate->id)]) }}"><button class="btn btn-success btn-sm" type="button"> <i class="fa fa-eye"></i> View</button></a> --}}

                                                <a href="{{ url('/app/candidates/show',['id'=>  base64_encode($candidate->id)]) }}"><button class="btn btn-success" type="button">View</button></a>
                                             </td>
                                          </tr>
                                          @endforeach
                                       @else 
                                       <tr>
                                          <td colspan="7">
                                             <h3 class="text-center">Record is not available!</h3>
                                          </td>
                                       </tr>
                                       @endif
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <!-- 1st Tab Has Been End Here -->
                           
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
   <!-- Script -->
<script type="text/javascript">

   $(document).ready(function(){
   //
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
   //
   var uriNum = location.hash;
   pageNumber = uriNum.replace("#", "");
   // alert(pageNumber);
   getData(pageNumber);
   //
   $('.customer_list').on('select2:select', function (e){
       var data = e.params.data.id;
       //loader
       $("#overlay").fadeIn(300);　
       getData(0);
       setData();
       event.preventDefault();
   });
   
   // filterBtn
   $(document).on('change','.customer_list, .candidate_list, .from_date, .to_date', function (e){    
       $("#overlay").fadeIn(300);　
       getData(0);
       e.preventDefault();
   });
   
   $(document).on('click','.filterBtn', function (e){    
       $("#overlay").fadeIn(300);　
       getData(0);
       e.preventDefault();
   });
   
   //
   $(document).on('change','.customer_list',function(e) {
           e.preventDefault();
           $('.candidate_list').empty();
           $('.candidate_list').append("<option value=''>-Select-</option>");
           var customer_id = $('.customer_list option:selected').val();
           $.ajax({
           type:"POST",
           url: "{{ url('/app/customers/candidates/getlist') }}",
           data: {"_token": "{{ csrf_token() }}",'customer_id':customer_id},      
           success: function (response) {
               console.log(response);
               if(response.success==true  ) {   
                   $.each(response.data, function (i, item) {
                     $(".candidate_list").append("<option value='"+item.id+"'> "+item.id+"-" + item.first_name +' '+item.last_name+ "</option>");
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
       return false;
       });
   
   // 
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
   
   
   // print visits  
   $(document).on('click','#exportExcel',function(){
   setData();
   var check = $(".check option:selected").val();
     if(check !=''){
       //
           var user_id     =    $(".customer_list").val();                
           var check       =    $(".check option:selected").val();
           var from_date   =    $(".from_date").val(); 
           var to_date     =    $(".to_date").val();    
           var candidate_id=    $(".candidate_list option:selected").val();                            
   
           $.ajax(
           {
               url: "{{ url('/') }}"+'/app/customers/reports/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&check_id='+check+'&candidate_id='+candidate_id,
               type: "get",
               datatype: "html",
           })
           .done(function(data)
           {
              console.log(data);
              var path = "{{ route('/jaf-export')}}";
               window.open(path);
           })
           .fail(function(jqXHR, ajaxOptions, thrownError)
           {
               //alert('No response from server');
           });
       //
      
     }else{
         alert('Please select a check to export! ');
        }
     });
   
   });
   
   function getData(page){
       //set data
       var user_id     =    $(".customer_list").val();                
       var check       =    $(".check option:selected").val();
       var from_date   =    $(".from_date").val(); 
       var to_date     =    $(".to_date").val();      
       var candidate_id=    $(".candidate_list option:selected").val();                          
   
           $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' /></div>").fadeIn(300);
   
           $.ajax(
           {
               url: '?page=' + page+'&customer_id='+user_id+'&status='+status+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&check_id='+check,
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
       var check       =    $(".check option:selected").val();
       var from_date   =    $(".from_date").val(); 
       var to_date     =    $(".to_date").val();    
       var candidate_id=    $(".candidate_list option:selected").val();                            
   
           $.ajax(
           {
               url: "{{ url('/') }}"+'/app/customers/reports/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&check_id='+check+'&candidate_id='+candidate_id,
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
</div>
</div>
@endsection
