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
             <li>FAQ</li>
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
                           <div class="col-sm-12 ">
                              
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-12">
                                       <h4 class="card-title mb-1 mt-3">FAQs </h4>
                                       <p class="pb-border" style="wi"></p>
                                    </div>
                                    {{-- <div class="col-md-6 text-right">
                                       <!-- <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a> -->
                                    </div> --}}

                                    @if ($message = Session::get('success'))
                                       <div class="col-md-12">   
                                          <div class="alert alert-success">
                                          <strong>{{ $message }}</strong> 
                                          </div>
                                       </div>
                                    @endif

                                    <div class="col-md-12">

                                    {{-- <table class="table table-bordered">
                                       <thead class="thead-light">
                                          <tr>
                                             <th>#</th>
                                             <th>Question</th>
                                             <th>Answer</th>
                                             <th>Action</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          @if(count($faq)>0)
                                          @foreach ($faq as $key=>$f)
                                          <tr>
                                             <td>{{$key+1}}</td>
                                             <td>{{ Str::limit($f->question,20) }}</td>
                                             <td>{!! Str::limit(strip_tags($f->answer),20) !!}</td>
                                             <td class=""> 
                                                   <a href="{{url('/my/faq/show',['id'=>base64_encode($f->id)])}}" title="Preview" class="text-info"><i class="far fa-eye"></i></a>
                                             </td>
                                          </tr>
                                          @endforeach
                                          @else
                                             <tr class="text-center">
                                                <td colspan="4">No Data Available</td>
                                             </tr>
                                          @endif   
                                       </tbody>
                                    </table> --}}

                                    @if(count($faq)>0)
                                       @foreach ($faq as $key=>$f)
                                          <div class="faq pb-2" id="accordion">
                                             <div class="card">
                                                <div class="card-header" id="faqHeading-1">
                                                   <div class="mb-0">
                                                      <h5 class="faq-title" data-toggle="collapse" data-target="#faqCollapse-{{$key+1}}" data-aria-expanded="true" data-aria-controls="faqCollapse-1">
                                                            <span class="badge">{{$key + 1}}</span> {{ $f->question }}
                                                      </h5>
                                                   </div>
                                                </div>
                                                <div id="faqCollapse-{{$key+1}}" class="collapse" aria-labelledby="faqHeading-1" data-parent="#accordion">
                                                   <div class="card-body py-1">
                                                      <label id="answer" class="form-control answer " style="height: auto" name="answer" placeholder="e.g. Answer">{!!$f->answer!!}</label>
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
               
            
         
      </div>
   </div>
</div>
@stack('scripts')
<script>
   // $(document).ready(function() {
   //    $('.answer').summernote(
   //       // placeholder: 'e.g. Answer',
   //       // height: 100
   //    );
   // });
   </script>
{{-- <script>
   function del()
   {
      var result=confirm("Are You Sure You Want to Delete?");
      if(result){
         return true;
      }
      else{
         return false;
      }
   }
</script> --}}
{{-- <script type="text/javascript">
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
