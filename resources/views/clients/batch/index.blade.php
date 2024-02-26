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
       <li>Batches</li>
       </ul>
   </div>
   <!-- ============Back Button ============= -->
   <div class="col-sm-1 back-arrow">
       <div class="text-right">
         <a href="{{ url()->previous() }}"> <i class="fas fa-arrow-circle-left fa-2x"></i></a>
       </div>
   </div>
 </div>    
      <div class="row">
      <div class="col-md-12">
         <div class="card text-left">
            <div class="card-body">
               <div class="row">
               @if ($message = Session::get('success'))
               <div class="col-md-12">   
                  <div class="alert alert-success">
                  <strong>{{ $message }}</strong> 
                  </div>
               </div>
               @endif
               @php
               $ADD_ACCESS    = false;
               $DELETE_ACCESS   = false;
               $VIEW_ACCESS   = false;
               $DOWNLOAD_ACCESS   = false;
               $ADD_ACCESS    = Helper::can_access('Add Batches','/my');//passing action title and route group name
               $DELETE_ACCESS    = Helper::can_access('Delete Batches','/my');//passing action title and route group name
               $VIEW_ACCESS   = Helper::can_access('View Batches','/my');//passing action title and route group name
               $DOWNLOAD_ACCESS    = Helper::can_access('Download Batches','/my');//passing action title and route group name
               @endphp
                  <div class="col-md-8">
                     <h4 class="card-title mb-1"> Batches</h4>
                     <p> List of all Batches </p>
                  </div>
                  <div class="col-md-4">
                     <div class="btn-group" style="float:right">
                         {{-- <input type="file" name="file" class="form-control"> --}}
                           
                            
                        @if ($ADD_ACCESS)
                        <a class="btn btn-success " href="{{url('my/batches/create')}}" > <i class="fa fa-plus"></i> Add New </a>             
                        @endif
                     </div>
                  </div>
               </div> 
               <div class="row">
                  <div class="col-md-12">
                     <div class="table-responsive">
                        @if ($VIEW_ACCESS)
                        <table class="table table-bordered batchTable">
                           <thead>
                              <tr>
                                 {{-- <th scope="col">#</th> --}}
                                 <th scope="col">Batch Name</th>
                                 <th scope="col">No. of Candidates </th>
                                 <th scope="col">TAT </th>
                                 
                                 <th scope="col">Upload Date</th>
                                 {{-- <th scope="col">Status</th> --}}
                                 <th scope="col">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              @if (count($batches) > 0 )
                                 @foreach ($batches as $batch)
                                          <?php 
                                          $status ='';
                                          $status = Helper::get_batch_data($batch->id)
                                          ?>  
                                    <tr>
                                          <td>{{$batch->batch_name}}</td>
                                          <td>{{$batch->no_of_candidates}}</td>
                                          <td>{{$batch->tat}} Days</td>
                                          <td>{{ date('d-m-Y',strtotime($batch->created_at))}}</td>
                                          <td>
                                             @if ($DOWNLOAD_ACCESS)
                                                <a href="{{ url('/my/batches/zip',['id'=>base64_encode($batch->id)]) }}">
                                                   <button class="btn btn-sm btn-info"  type="button"> <i class="fa fa-download"></i> Download</button>
                                                </a>
                                             @endif
                                             @if ($DELETE_ACCESS)
                                                @if($status)
                                                   <button class="btn btn-info btn-sm " type="button" data-id="{{ base64_encode($batch->id) }}" > <i class='fa fa-trash'></i> Request Sent</button>
                                                   {{-- <span class="d-none" data-can_id="{{ base64_encode($batch->id)}}" >Delete</span> --}}
                                                @else
                                                   
                                                      <button class="btn btn-danger btn-sm deleteRow" type="button" data-id="{{ base64_encode($batch->id) }}" > <i class='fa fa-trash'></i> Delete</button>
                                             
                                                      <button class="btn btn-info btn-sm  d-none" type="button" data-batch_id="{{ base64_encode($batch->id) }}" > <i class='fa fa-trash'></i> Request Sent</button>
                                                   
                                                @endif
                                             @endif
                                          
                                          </td>
                                       
                                    </tr>
                                @endforeach 
                              @else
                              <tr class="text-center">
                                 <td colspan="5">No Data Found</td>
                             </tr>
                              @endif
                           </tbody>
                        </table>
                        @else
                        <span><h3 class="text-center">You have no access to View Batches lists</h3></span>
                         @endif
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   </div>
</div>
<!-- Script -->
<script>
$(document).on('click', '.deleteRow', function (event) {
        
   var batch_id = $(this).attr('data-id');
   if(confirm("Are you sure want to delete?")){
   $.ajax({
       type:'GET',
       url: "{{route('/my/batches/delete')}}",
       data: {'batch_id':batch_id},        
       success: function (response) {        
       console.log(response);
       
           if (response.status=='ok') {            
           
               $('table.batchTable tr').find("[data-id='" + batch_id + "']").fadeOut("slow");
               $('table.batchTable tr').find("[data-batch_id='" + batch_id + "']").removeClass("d-none").show();
               // $('table.candidatesTable tbody').find("[candidate-d_id='" + candidate_id + "']").fadeOut("slow");

           } else {
               
           }
       },
       error: function (response) {
           console.log(response);
       }
   });

   }
   return false;

});
</script>
<!--end Script-->
@endsection
