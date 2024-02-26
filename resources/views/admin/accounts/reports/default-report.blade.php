@extends('layouts.admin')
@section('content')
<style>

#preview{
        /* overflow-x: hidden; */
        /* overflow-y: hidden; */
        z-index: 999;
        padding-top: 0px;
        /* margin:auto; */
    }
#preview .modal-dialog.modal-lg{
  max-width: 90% !important;
  width: 100%;
  padding: 0px;
  left: 3.5%;
}
</style>
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
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>
                 <a href="{{ url('/settings/general') }}">Accounts</a>
             </li>
             <li>Report</li>
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
                  @include('admin.accounts.left-sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style="background:#fff">
                     <div class="formCover py-2" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           @include('admin.accounts.reports.menu') 
                           <div class="col-sm-12 ">
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">Default template</h4>
                                       <p class="pb-border"> When Template-2 or Template-3 is not enable,Template-1 (default) will be enable automatically for the report output.</p>
                                    </div>
                                    <div class="col-md-6 mt-3 text-right">
                                        <button type="button" class="btn btn-dark reportPreviewBox" data-id=""><i class="fas fa-eye"></i> Preview</button>

                                       {{-- <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a> --}}

                                       {{-- <div class="btn-group" style="float:right">     
                                          <a href="#" class="filter0search"><i class="fa fa-filter"></i></a> 
                                       </div> --}}
                                    </div>
                                 </div>
                                
                                 
                                 <div class="row">
                                    <div class="col-md-12 pt-3">
                                       <div id="candidatesResult">
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
<div class="modal" id="preview">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Report Preview</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red;" data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
             <div class="modal-body">
                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                <iframe 
                    src="{{url('/').'/report_template/default_report.pdf/'}}" 
                    style="width:100%; height:600px;" 
                    frameborder="0" id="preview_pdf">
                </iframe>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="button" class="btn btn-danger back" data-dismiss="modal">Close</button>
             </div>
       </div>
    </div>
</div>
  <!-- Footer Start -->
  <div class="flex-grow-1"></div>
  
</div>
@stack('scripts')
<script>
      $(document).on('click','.reportPreviewBox',function(){
        $('#preview').modal({
                    backdrop: 'static',
                    keyboard: false
                });
        // $('#preview').toggle();
      });
</script>
 
@endsection
