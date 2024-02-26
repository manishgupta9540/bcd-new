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
         <li>Feedback</li>
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
                  <h3 class="page-title">Accounts/Feedback </h3>
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
                            @if ($message = Session::get('success'))
                            <div class="col-md-12">   
                            <div class="alert alert-success">
                            <strong>{{ $message }}</strong> 
                            </div>
                            </div>
                            @endif
                            <form action="{{url('/my/feedback/store')}}"  method="POST" enctype="multipart/form-data">
                                @csrf
                                   <div class="form-body">
                                       <div class="card radius shadow-sm">
                                           <div class="card-body">
                                               <div class="row">
                                                <div class="col-md-12">
                                                   <h4 class="card-title mb-1 mt-3">Feedback </h4>
                                                   <p class="pb-border"> Give Your Feedback </p>
                                                </div>
                                                {{-- <div class="col-md-6 text-right">
                                                   <!-- <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a> -->
                                                </div> --}}
                                                   <div class="col-12">
                                                      <div class="form-group">
                                                          <label for="feedback" class="form-control-label font-weight-300">Feedback <span class="text-danger">*</span></label>
                                                          <textarea id="feedback" class="form-control feedback " name="feedback" placeholder="e.g. Feedback"></textarea>
                                                          {{-- <input type="text" id="answer" class="form-control answer  @error('answer') is-invalid @enderror" name="answer" placeholder="e.g. Answer"> --}}
                                                          @error('feedback')
                                                              <div class="text-danger">{{ $message }}</div>
                                                          @enderror
                                                      </div>
                                                  </div>
                                               </div>
                                           </div>
                                           <div class="card-footer">
                                               <div class="text-right">
                                                   <button class="btn btn-success" type="submit">Send</button>
                                               </div>
                                               <div class="text-center">
                                                   <div class="error"></div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                   
                               </form>
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
      $('.feedback').summernote(
        {
            height: 200,
        } 
      );
   });
                     
</script>  
@endsection