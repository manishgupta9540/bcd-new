@extends('layouts.client')
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
         <li>Help & Support</li>
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
                  <h3 class="page-title">Help & Support </h3>
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
                                    <div class="col-md-12">
                                       <h4 class="card-title mb-1 mt-3">Help & Support </h4>
                                       <p class="pb-border"> Your Help & Support overview </p>
                                    </div>
                                    {{-- <div class="col-md-6 text-right">
                                       <!-- <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a> -->
                                    </div> --}}
                                   @if ($helps)
                                          
                                       <div class="col-md-12 " style="padding-left: 15%;
                                       padding-right: 15%; padding-top: 5%;">
                                          {!!$helps->content!!}
                                       </div>
                                    @else
                                       <span>No Data Found !</span>
                                    @endif
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
@stack('scripts')
<script type="text/javascript">
   //
   $(document).ready(function() {
   
   
   });
                     
</script>  
@endsection
