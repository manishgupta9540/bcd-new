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
                     <h4 class="card-title mb-1"> Customer </h4>
                     <p> Details of customer </p>
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
                           <div class="tab-pane fade " id="candidatetb1" role="tabpanel" aria-labelledby="candidatetab">
                              <div class="row" style="margin-bottom:15px">
                                 <div class="col-md-2">
                                    <div class="search-bar">
                                       <input type="text" placeholder="Search" autocomplete="off" style="padding: 5px;border-radius: 4px;background: #f6f8fc;">
                                    </div>
                                 </div>
                              </div>
                              <div class="table-responsive tableFixHead" style="height: 300px;">
                              </div>
                           </div>
                           <!-- 1st Tab Has Been End Here -->
                           <!-- 2nd Tab Starts From Here -->
                           <div class="row" style="margin-bottom:15px">
                              <div class="col-md-2">
                                 <div class="search-bar">
                                    <input type="text" placeholder="Search" autocomplete="off" style="padding: 5px;border-radius: 4px;background: #f6f8fc;">
                                 </div>
                              </div>
                           </div>
                           <div class="tab-pane active show fade" id="jobtb1" role="tabpanel" aria-labelledby="jobtab">
                              <div class="table-responsive">
                                 <table class="table table-bordered">
                                    <thead>
                                       <tr>
                                          <th scope="col">#</th>
                                          <th scope="col">Job Name</th>
                                          <th scope="col">Verification type</th>
                                          <th scope="col">Status</th>
                                          <th scope="col">Action</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @if(count($jobs)>0)
                                       @foreach($jobs as $item)
                                       <tr>
                                          <th scope="row">1</th>
                                          <td>{{$item->title}}</td>
                                          <td>{{ $item->verification_type }}</td>
                                          <td><span class="badge badge-info">Pending</span></td>
                                          <td>
                                             <a href="#"><button class="btn btn-success" type="button">View</button></a>
                                          </td>
                                       </tr>
                                       @endforeach
                                       @else
                                       <tr>
                                          <td colspan="5">
                                             <h3>Record not available!</h3>
                                          </td>
                                       </tr>
                                       @endif
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <!-- 2nd Tab Has Been End Here -->
                           
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
   </div>
</div>

<!-- modal -->
<div class="modal fade" id="single-person" tabindex="-1" role="dialog" aria-labelledby="single-person" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="single-person">Job Name : Demo version 2.0.1</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body" style="padding:25px;">
            <div class="table-custom table-responsive">
               <div id="customes_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                  <div class="row">
                     <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="customes_length">
                           <label>
                              Show 
                              <select name="customes_length" aria-controls="customes" class="custom-select custom-select-sm form-control form-control-sm">
                                 <option value="10">10</option>
                                 <option value="25">25</option>
                                 <option value="50">50</option>
                                 <option value="100">100</option>
                              </select>
                              entries
                           </label>
                        </div>
                     </div>
                     <div class="col-sm-12 col-md-6">
                        <div id="customes_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="customes"></label></div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-sm-12">
                        <table id="customes" class="table table-striped table-bordered dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="customes_info">
                           <thead>
                              <tr role="row">
                                 <th class="sorting_asc" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Sr. No.: activate to sort column descending" style="width: 0px;">Sr. No.</th>
                                 <th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Job Name: activate to sort column ascending" style="width: 0px;">Job Name</th>
                                 <th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending" style="width: 0px;">ID</th>
                                 <th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" style="width: 0px;">Name</th>
                                 <th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Mobile: activate to sort column ascending" style="width: 0px;">Mobile</th>
                                 <th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 0px;">Email</th>
                                 <th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" style="width: 0px;">Address</th>
                                 <th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 0px;">Date</th>
                                 <th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Call sent: activate to sort column ascending" style="width: 0px;">Call sent</th>
                                 <th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="SMS sent: activate to sort column ascending" style="width: 0px;">SMS sent</th>
                                 <th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Email sent: activate to sort column ascending" style="width: 0px;">Email sent</th>
                                 <th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="SMS link clicked: activate to sort column ascending" style="width: 0px;">SMS link clicked</th>
                                 <th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Form Filled: activate to sort column ascending" style="width: 0px;">Form Filled</th>
                                 <th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="QC Status: activate to sort column ascending" style="width: 0px;">QC Status</th>
                                 <th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Report: activate to sort column ascending" style="width: 0px;">Report</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr role="row" class="odd">
                                 <td class="sorting_1">1</td>
                                 <td>Demo version 1.0.7</td>
                                 <td>1</td>
                                 <td>John Doe</td>
                                 <td>8899889900</td>
                                 <td>singh@gmail.com</td>
                                 <td>New Ahok Nagar , near metro 15 station</td>
                                 <td>21/12/2020</td>
                                 <td>yes</td>
                                 <td>yes(0) delivered</td>
                                 <td>yes</td>
                                 <td>yes</td>
                                 <td>yes</td>
                                 <td><span class="pending">Pending</span></td>
                                 <td><a href="#" class="qc1">Link</a></td>
                              </tr>
                              <tr role="row" class="even">
                                 <td class="sorting_1">1</td>
                                 <td>Demo version 1.0.7</td>
                                 <td>1</td>
                                 <td>John Doe</td>
                                 <td>8899889900</td>
                                 <td>singh@gmail.com</td>
                                 <td>New Ahok Nagar , near metro 15 station</td>
                                 <td>21/12/2020</td>
                                 <td>yes</td>
                                 <td>yes(0) delivered</td>
                                 <td>yes</td>
                                 <td>yes</td>
                                 <td>yes</td>
                                 <td><span class="progressing">In progress</span></td>
                                 <td><a href="#" class="qc1">Link</a></td>
                              </tr>
                              <tr role="row" class="odd">
                                 <td class="sorting_1">1</td>
                                 <td>Demo version 1.0.7</td>
                                 <td>1</td>
                                 <td>John Doe</td>
                                 <td>8899889900</td>
                                 <td>singh@gmail.com</td>
                                 <td>New Ahok Nagar , near metro 15 station</td>
                                 <td>21/12/2020</td>
                                 <td>yes</td>
                                 <td>yes(0) delivered</td>
                                 <td>yes</td>
                                 <td>yes</td>
                                 <td>yes</td>
                                 <td>Done <a href="#" class="qc1">QC</a></td>
                                 <td><a href="#" class="qc1">Link</a></td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="customes_info" role="status" aria-live="polite">Showing 1 to 3 of 3 entries</div>
                     </div>
                     <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="customes_paginate">
                           <ul class="pagination">
                              <li class="paginate_button page-item previous disabled" id="customes_previous"><a href="#" aria-controls="customes" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li>
                              <li class="paginate_button page-item active"><a href="#" aria-controls="customes" data-dt-idx="1" tabindex="0" class="page-link">1</a></li>
                              <li class="paginate_button page-item next disabled" id="customes_next"><a href="#" aria-controls="customes" data-dt-idx="2" tabindex="0" class="page-link">Next</a></li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </div>
   </div>
   <!-- ./modal close -->

@endsection
