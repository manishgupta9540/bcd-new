@extends('layouts.admin')
@section('content')
<style type="text/css">
   ul,li
   {
     list-style-type: none;
   }
   .disabled-link{
      pointer-events: none;
   }
   </style>
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
             <li>Profile</li>
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
                  <h3 class="page-title">Account / Profile </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
               <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('admin.accounts.left-sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper bg-white">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 pt-2">
                              
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-7">
                                       <h4 class="card-title mb-1 mt-3">Profile Information </h4>
                                       <p class="pb-border"> Your primary account info  </p>
                                    </div>
                                    <div class="col-md-5 float-right text-right">
                                       <?php
                                          $display_id =NULL;
                                          if($profile->display_id!=NULL)
                                          {
                                             $display_id = $profile->display_id;
                                          }
                                          else {
                                             $u_id = str_pad($profile->id, 10, "0", STR_PAD_LEFT);
                                             $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr(Helper::company_name($profile->business_id),0,4)))).'-'.$u_id;
                                          }
                                       ?>
                                       <p class="mb-1 mt-3"><strong> Company Name : </strong> {{Helper::company_name($profile->business_id)}}</p>
                                       <p><strong> Reference No : </strong>{{$display_id}}</p>
                                    </div>
                                    @if ($message = Session::get('success'))
                                       <div class="col-md-12">   
                                          <div class="alert alert-success">
                                          <strong>{{ $message }}</strong> 
                                          </div>
                                       </div>
                                    @endif
                                    <div class="col-md-12">
                                       <form class="mt-2" method="post" enctype="multipart/form-data" action="{{ url('/profile/update') }}">
                                         @csrf
                                          <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>First name <span class="text-danger">*</span></label>
                                                   <input class="form-control " type="text" name="first_name" value="{{ $profile->first_name }}">
                                                   @if ($errors->has('first_name'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('first_name') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Last name</label>
                                                   <input class="form-control number_only" type="text" name="last_name" value="{{ $profile->last_name }}">
                                                   @if ($errors->has('last_name'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('last_name') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                          </div>
                                       
                                          <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Phone <span class="text-danger">*</span></label>
                                                   <input class="form-control" type="text" id="phone1" name="phone" value="{{ $profile->phone }}">
                                                   @if ($errors->has('phone'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('phone') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label> Email </label>
                                                   <input class="form-control" type="email" name="email" value="{{ $profile->email }}" readonly>
                                                   @if ($errors->has('email'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('email') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                          </div>
                                       
                                          <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   
                                                </div>
                                             </div>
                                          
                                          </div>
                                          <div class="text-center">
                                             <button type="submit" class="btn btn-md btn-info">Update</button>
                                          </div>
                                       </form>
                                    </div>
                                 </div>
                                 <!-- ./business detail -->
                                 @if(Auth::user()->user_type=='user')
                                    <div class="row">
                                       <div class="col-md-12">
                                          <h4 class="card-title mb-1 mt-3">Permission </h4>
                                          <p class="pb-border"> Your Account Permissions  </p>
                                       </div>
                                       <div class="col-md-12">
                                          @if(count($permission)>0)
                                             <div class="row">
                                                @foreach($permission as $data)
                                                   <?php
                                                      $action = DB::table('action_masters')->where(['route_group'=>'','status'=>'1','parent_id'=>$data->id])->orderBy('display_order','ASC')->get();
                                                      $action_count=count($action);
                                                   ?>
                                                   @php
                                                      if($action_route_count==0){
                                                         $checked = '';
                                                      }else{
                                                         $route_link = json_decode($action_route->permission_id);
                                                         $checked = in_array($data->id,$route_link)  ? 'checked' : '';
                                                      }
                                                   @endphp
                                                   @if(in_array($data->id,$route_link))
                                                      <div class="col-sm-12">
                                                         <li>
                                                            <input type="checkbox" class="disabled-link" name="permissions[]" value="{{$data->id}}" {{$checked}} readonly> <b>{{$data->action_title}}</b>
                                                            <ul>
                                                               @if(count($action)>0)
                                                                  <?php $i=0; ?>
                                                                  @foreach($action as $premission)
                                                                     @php
                                                                     if($action_route_count==0){
                                                                        $checked = '';
                                                                     }else{
                                                                     $route_link = json_decode($action_route->permission_id);
                                                                     //  dd($permission->id);
                                                                     $checked = in_array($premission->id,$route_link)  ? 'checked' : '';
                                                                     }
                                                                     @endphp
                                                                     @if(in_array($premission->id,$route_link))
                                                                        @if(++$i==$action_count)
                                                                           <span> <b>{{$premission->action_title}}</b> </span>
                                                                        @else
                                                                           <span> <b>{{$premission->action_title}}, </b> </span>
                                                                        @endif
                                                                     @endif
                                                                  @endforeach
                                                               @endif
                                                            </ul>
                                                         </li>
                                                      </div>
                                                      
                                                   @endif
                                                @endforeach
                                             </div>
                                          @endif
                                       </div>
                                    </div>
                                 @endif
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
      </div>

      
   </div>
</div>
@stack('scripts')
<script type="text/javascript">
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
                     
</script>  
@endsection
