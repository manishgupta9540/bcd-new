@extends('layouts.admin')
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
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>
                 <a href="{{ url('/settings/general') }}">Accounts</a>
             </li>
             <li>Feedback</li>
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
            @if ($message = Session::get('success'))
            <div class="col-md-12">   
               <div class="alert alert-success">
               <strong>{{ $message }}</strong> 
               </div>
            </div>
            @endif
               <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('admin.accounts.left-sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style="background:#fff">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 py-2">
                              
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title my-3">Feedback  </h4>
                                       {{-- <p class="pb-border"> Your billing overview/history  </p> --}}
                                    </div>
                                    {{-- <div class="col-md-6 text-right">
                                       <a href="{{route('/faq/create')}}" class="mt-3 btn btn-sm btn-primary"><i class="fa fa-plus"></i> Create FAQ</a>
                                    </div> --}}

                                    <div class="col-md-12">

                                    <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            
                                            <th>Company Name</th>
                                            <th style="width: 60%;">Feedback</th>
                                            <th>Feedback Given By</th>
                                            <th>Given Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @if(count($feedback)>0)
                                       @foreach ($feedback as $item)
                                        <tr>
                                            <td><b>{{Helper::company_name($item->business_id)}}</b></td>
                                            <td>{!! $item->feedback !!}</td>
                                            <td>{{Helper::user_name($item->created_by)}}</td>
                                           <td>{{ date('d-m-Y',strtotime($item->created_at))}}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                          <tr class="text-center">
                                             <td colspan="4">No Data Available</td>
                                          </tr>
                                        @endif   
                                    </tbody>
                                </table>

                                    </div>
                                 </div>
                                 <!-- ./business detail -->
                                 
                           </div>
                           {{-- <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" role="status" aria-live="polite"></div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                              <div class=" paging_simple_numbers" >            
                                  {!! $feedback->render() !!}
                              </div>
                            </div>
                        </div> --}}
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


@endsection
