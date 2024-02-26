@extends('layouts.admin')
@section('content')
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
                 <a href="{{ url('/admin/vendor') }}">Vendors</a>
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
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Account / SLA </h3>
               </div>
            </div>
         </div>
      </div>  --}}
      <div class="row">
              <div class="col-md-12">   
              
              </div>
            <div class="col-md-3 content-container">
            <!-- left-sidebar -->
                @include('admin.vendors.left-sidebar') 
            </div>
                <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style=" background:#fff;">
                     <div class="col-sm-12 mt-3">
                        
                        @if ($message = Session::get('success'))
                           <div class="alert alert-success">
                              <strong>{{ $message }}</strong> 
                           </div>
                        @endif
                     </div>
                     <div class="formCover" style="height: 100vh; ;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 ">
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">Check price  <small class="text-muted"> ( {{ Helper::user_name($profile->user_id)  }} - {{  $profile->company_name!=null?$profile->company_name: 'Individual'}}) </small></h4>
                                       <p class="pb-border"> Your Vendor's Check Price   </p>
                                    </div>
                                    <div class="col-md-6 text-right">
                                       <a href="{{ route('/admin/vendor/checkPrice/create',['id'=>base64_encode($profile->id)]) }}" class="mt-3 btn btn-sm btn-info"><i class="fa fa-plus"></i> Create new</a>
                                    </div>
                                    <div class="col-md-12">

                                    <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            
                                            <th scope="col" style="position:sticky; top:60px">Company</th>
                                            <th scope="col" style="position:sticky; top:60px">Services</th>
                                            <th scope="col" style="position:sticky; top:60px">price</th> 
                                            <th scope="col" style="position:sticky; top:60px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      
                                           
                                      
                                          @if(count($service_item) > 0 )
                                             @foreach($service_item as $item)
                                             <tr>
                                                <td> <b>{{ ucfirst($profile->company_name) }} </b></td>
                                                <td>
                                                   {{ Helper::get_service_name($item->service_id) }}
                                                   </td>
                                                <td> 
                                                   <i class="fas fa-rupee-sign"></i>  {{ $item->price }}
                                                   
                                                </td>
                                                <td>
                                                   
                                                      <a href="{{ url('/admin/vendor/checkPrice/edit',['id'=>base64_encode($profile->id),'service_item_id'=>base64_encode($item->id)]) }}" class="btn btn-info"> <i class='fa fa-edit'></i> Edit </a> 
                                                      {{--  --}}
                                                  
                                                </td>
                                             </tr>
                                             @endforeach
                                          @else
                                                <tr> <td class="text-center" colspan="4">Check Price is not created!</td> </tr>
                                          @endif
                                           
                                    </tbody>
                                </table>

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
<script type="text/javascript">
   //
   $(document).ready(function() {
      // flash auto hide
      $('.alert').not('.alert-danger, .alert-important').delay(8000).slideUp(500);
   });
                     
</script>  
@endsection
