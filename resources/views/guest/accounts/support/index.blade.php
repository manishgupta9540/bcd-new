@extends('layouts.guest')
@section('content')

{{-- <div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   {{-- <div class="main-content"> --}}
      {{-- <div class="row">
         <div class="col-sm-11 col-12">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/verify/home') }}">Dashboard</a>
             </li>
             <li>
               <a href="{{ url('/verify/profile') }}">Accounts</a>
             </li>
             <li>Help & Support11</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow col-2">
             <div class="text-right">
             <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div> --}}
      
      {{-- <div class="row"> --}}
           
         <div class="col-md-3 content-container mt-5">
            <!-- left-sidebar -->
            @include('guest.accounts.left-sidebar') 
         </div>
            <!-- start right sec -->
            <div class="col-md-8 content-wrapper">
               <div class="formCover" style="height: 100vh;">
                  <!-- section -->
                  <section>
                     <div class="col-sm-10 offset-md-1">
                           <!-- row -->
                           <div class="row profile-block">
                              <div class="col-md-6">
                                 <h4 class="card-title mb-1 mt-3">Help & Support </h4>
                              </div>
                              <div class="col-md-6 text-right">
                                    {{-- @if ($ADD_ACCESS) --}}
                                    <a href="{{url('/verify/help/create')}}" class="mt-3 btn btn-sm btn-info updatebtn"><i class="fa fa-plus"></i> Ask Question</a> 
                                    {{-- @endif --}}
                                 </div>
                                 <div class="col-12">
                                 <p class="pb-border">  </p>
                                 </div>
                              @if ($message = Session::get('success'))
                                 <div class="col-md-12">   
                                    <div class="alert alert-success">
                                    <strong>{{ $message }}</strong> 
                                    </div>
                                 </div>
                              @endif
                              <div class="col-md-12 pt-4">
                                    @if(count($helps)>0)
                                       @foreach ($helps as $key=> $help)
                                          <div class="faq pb-2" id="accordion">
                                                <div class="card">
                                                   <div class="card-header" id="faqHeading-1">
                                                      <div class="mb-0">
                                                      <div class="float-right pt-3 pr-3">
                                                            
                                                            {{-- @if ($DELETE_ACCESS) --}}
                                                            {{-- <a href="{{url('/my/question/delete',['id'=>base64_encode($help->id)])}}" style="font-size: 16px;" title="delete" onclick="return del()"><i class="fa fa-trash text-danger"></i></a>    --}}
                                                            {{-- @endif --}}
                                                      </div>
                                                      <h5 class="faq-title" data-toggle="collapse" data-target="#faqCollapse-{{$key+1}}" data-aria-expanded="true" data-aria-controls="faqCollapse-1">
                                                               <span class="badge">{{$key + 1}}</span>Subject:- {{ $help->subject }} 
                                                               <p class="text-muted"> <small> Created at: {{ date('d-m-Y',strtotime($help->created_at))}}</small></p>  
                                                      </h5>
                                                      </div>
                                                   </div>
                                                   @php
                                                   $help_response='';
                                                   $help_response= Helper::get_guest_help_response_data($help->id,$help->business_id);
                                                   @endphp
                                                   <div id="faqCollapse-{{$key+1}}" class="collapse" aria-labelledby="faqHeading-1" data-parent="#accordion">
                                                      <div class="card-body py-2">
                                                            <label  class="form-control answer" style="height: auto" name="answer" placeholder="e.g. Answer" >Subject:- {{ $help->subject }} <br>Comments:- {!! $help->content !!} Reply:-{!! $help_response !!}</label>
                                                      </div>
                                                   </div>
                                                </div>
                                          </div>
                                       @endforeach
                                    @else
                                       <p class="text-muted">No data Found</p>
                                    @endif
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
      {{-- </div> --}}
            {{-- </div>
         </div>
      </div>
   </div>
</div> --}}
@stack('scripts')
<script>
   $(document).ready(function(){
      $('#phone1').parent().css({'width':'100%'});
   });
</script>
{{--<script type="text/javascript">
   //
   $(document).ready(function() {
   //
   $(document).on('click','#clickSelectFile',function(){ 
   
       $('#fileupload').trigger('click');
       
   });
   
   $(document).on('click','.remove-image',function(){ 
       
       $('#fileupload').val("");
       $(this).parent('.image-area').detach();
   
   });
   
   $(document).on('change','#fileupload',function(e){ 
   // alert('test');
   //show process 
   // $("").html("Uploading...");
   $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 
   
   var fd = new FormData();
   var inputFile = $('#fileupload')[0].files[0];
   fd.append('file',inputFile);
   fd.append('_token', '{{csrf_token()}}');
   //
   
     $.ajax({
             type: 'POST',
             url: "{{ url('/company/upload/logo') }}",
             data: fd,
             processData: false,
             contentType: false,
             success: function(data) {
               console.log(data);
               if (data.fail == false) {
               
               //reset data
               $('#fileupload').val("");
               $("#fileUploadProcess").html("");
               //append result
               $("#fileResult").html("<div class='image-area'><img src='"+data.filePrev+"'  alt='Preview'><a class='remove-image' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.file_id+"'></div>");
   
               } else {
   
                 $("#fileUploadProcess").html("");
                 alert("please upload valida file! allowed file type , Image, PDF, Doc, Xls and txt ");
                 console.log("file error!");
                 
               }
             },
             error: function(error) {
                 console.log(error);
                 // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
             }
         }); 
       return false;
   
   });
   
   
   });
                     
</script>   --}}
@endsection
