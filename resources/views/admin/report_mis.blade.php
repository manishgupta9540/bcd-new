@extends('layout.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="card text-left">
            <div class="card-body">
               <h3 class="card-title mb-3"> Address Verification Quality Check (QC) </h3>
               <form>
                  <div class="row">
                     <div class="col-md-3 form-group mb-3">
                        <label for="firstName1"> start date </label>
                        <input class="form-control" id="firstName1" type="date" placeholder="Enter your first name">
                     </div>
                     <div class="col-md-3 form-group mb-3">
                        <label for="lastName1"> end date </label>
                        <input class="form-control" id="lastName1" type="date" placeholder="Enter your last name">
                     </div>
                     <div class="col-md-3 form-group mb-3">
                        <label for="picker1">Select user </label>
                        <select class="form-control">
                           <option>Option 1</option>
                           <option>Option 1</option>
                           <option>Option 1</option>
                        </select>
                     </div>
                     <div class="col-md-3">
                        <button class="btn btn-primary" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                     </div>
                  </div>
               </form>
               <form>
                  <div class="row">
                     <div class="col-md-3 form-group mb-3">
                        <label for="firstName1"> User Name </label>
                        <input class="form-control" type="text" placeholder="Enter your first name">
                     </div>
                     <div class="col-md-3 form-group mb-3">
                        <label for="picker1">Select user </label>
                        <select class="form-control">
                           <option>Option 1</option>
                           <option>Option 1</option>
                           <option>Option 1</option>
                        </select>
                     </div>
                     <div class="col-md-3">
                        <button class="btn btn-primary" style="width: 100%;padding: 7px;margin: 18px 0px;"> search </button>
                     </div>
                     <div class="col-md-3">
                        <button class="btn btn-primary" style="width: 100%;padding: 7px;margin: 18px 0px;"> Clear </button>
                     </div>
                  </div>
               </form>
            </div>
            <div class="card-body" style="position:relative">
               <h3 class="card-title mb-3"> QC Table </h3>
               <label style="float: right; margin-bottom:20px;">
               <input type="search" class="form-control form-control-sm" placeholder="Filter" aria-controls="zero_configuration_table" style="border: none;background: none;border-bottom: 2px solid #dee0e4;border-radius: inherit;font-size: 14px;"></label>
               <a class="addition" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Add Column <i class="fa fa-plus"></i> </a>
               <div class="dropdown-menu">
                  <a class="dropdown-item" id="vendor1" >Add Vendor</a>
                  <a class="dropdown-item" id="client1">Add Client Name</a>
                  <a class="dropdown-item" id="whatsapp1">Add Whatsapp</a>
               </div>
               <div class="table-responsive table-head-fix">
                  <table class="table table-striped table-bordered qcs_table" data-resizable-columns-id="demo-table">
                     <thead>
                        <tr>
                           <th data-resizable-column-id="1" colspan="5"> </th>
                           <th data-resizable-column-id="2" colspan="5"> Candidate </th>
                           <th data-resizable-column-id="3" colspan="5"> QC </th>
                           <th data-resizable-column-id="4" class="vendor-col" colspan="">Vendor</th>
                           <th data-resizable-column-id="5" class="client-col" colspan="">Clients</th>
                           <th data-resizable-column-id="6" class="wh-col" colspan="">Whatsapp</th>
                        </tr>
                        <tr class="second">
                           <th>#</th>
                           <th> Job Name </th>
                           <th> Total <br> Varification</th>
                           <th> calls </th>
                           <th> SMS </th>
                           <th> Email </th>
                           <th> Link <br> Clicked </th>
                           <th> Link did <br> not Clicked </th>
                           <th> Submited  </th>
                           <th> Link Clicked  <br> not Submited  </th>
                           <th> Done  </th>
                           <th> Pending </th>
                           <th class="red">  Red  </th>
                           <th class="green">  Green  </th>
                           <th class="amber"> Hold </th>
                           <th class="vendor-col">Vendors</th>
                           <th class="client-col">Client Name</th>
                           <th class="wh-col">Connect to Whatsapp</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td> 1</td>
                           <td>Tiger Nixon</td>
                           <td>2</td>
                           <td>2</td>
                           <td>0</td>
                           <td>2</td>
                           <td>2</td>
                           <td>0</td>
                           <td>0</td>
                           <td>2</td>
                           <td>0</td>
                           <td>2</td>
                           <td class="red">2</td>
                           <td class="green">0</td>
                           <td class="amber">0</td>
                           <td class="vendor-col">John</td>
                           <td class="client-col">Jai Mathur</td>
                           <td class="wh-col">
                              <a href="#">
                              <img src="images/whatsapp.png">
                              </a>
                           </td>
                        </tr>
                        <tr>
                           <td> 2 </td>
                           <td>Tiger Nixon</td>
                           <td>2</td>
                           <td>2</td>
                           <td>0</td>
                           <td>2</td>
                           <td>2</td>
                           <td>0</td>
                           <td><a href="#" data-toggle="modal" data-target="#single-person">1</a></td>
                           <td>2</td>
                           <td>0</td>
                           <td>2</td>
                           <td class="red">2</td>
                           <td class="green">0</td>
                           <td class="amber">0</td>
                           <td class="vendor-col">Juliet</td>
                           <td class="client-col">Vishal</td>
                           <td class="wh-col">
                              <a href="#">
                              <img src="images/whatsapp.png">
                              </a>
                           </td>
                        </tr>
                        <tr>
                           <td> 3</td>
                           <td>Tiger Nixon</td>
                           <td>2</td>
                           <td>2</td>
                           <td>0</td>
                           <td>2</td>
                           <td>2</td>
                           <td>0</td>
                           <td><a href="#" data-toggle="modal" data-target="#single-person">1</a></td>
                           <td>2</td>
                           <td>0</td>
                           <td>2</td>
                           <td class="red">2</td>
                           <td class="green">0</td>
                           <td class="amber">0</td>
                           <td class="vendor-col">Macgraw</td>
                           <td class="client-col">Priyanka</td>
                           <td class="wh-col">
                              <a href="#">
                              <img src="images/whatsapp.png">
                              </a>
                           </td>
                        </tr>
                        <tr>
                           <td> 3</td>
                           <td>Tiger Nixon</td>
                           <td>2</td>
                           <td>2</td>
                           <td>0</td>
                           <td>2</td>
                           <td>2</td>
                           <td>0</td>
                           <td>0</td>
                           <td>2</td>
                           <td>0</td>
                           <td>2</td>
                           <td class="red">2</td>
                           <td class="green">0</td>
                           <td class="amber">0</td>
                           <td class="vendor-col">Raman</td>
                           <td class="client-col">Shurya</td>
                           <td class="wh-col">
                              <a href="#">
                              <img src="images/whatsapp.png">
                              </a>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <div class="row mt-3">
                  <div class="col-sm-12 col-md-5">
                     <div class="dataTables_info" id="zero_configuration_table_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div>
                  </div>
                  <div class="col-sm-12 col-md-7">
                     <div class="dataTables_paginate paging_simple_numbers" id="zero_configuration_table_paginate">
                        <ul class="pagination">
                           <li class="paginate_button page-item previous disabled" id="zero_configuration_table_previous"><a href="#" aria-controls="zero_configuration_table" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li>
                           <li class="paginate_button page-item active"><a href="#" aria-controls="zero_configuration_table" data-dt-idx="1" tabindex="0" class="page-link">1</a></li>
                           <li class="paginate_button page-item "><a href="#" aria-controls="zero_configuration_table" data-dt-idx="2" tabindex="0" class="page-link">2</a></li>
                           <li class="paginate_button page-item "><a href="#" aria-controls="zero_configuration_table" data-dt-idx="3" tabindex="0" class="page-link">3</a></li>
                           <li class="paginate_button page-item "><a href="#" aria-controls="zero_configuration_table" data-dt-idx="4" tabindex="0" class="page-link">4</a></li>
                           <li class="paginate_button page-item "><a href="#" aria-controls="zero_configuration_table" data-dt-idx="5" tabindex="0" class="page-link">5</a></li>
                           <li class="paginate_button page-item "><a href="#" aria-controls="zero_configuration_table" data-dt-idx="6" tabindex="0" class="page-link">6</a></li>
                           <li class="paginate_button page-item next" id="zero_configuration_table_next"><a href="#" aria-controls="zero_configuration_table" data-dt-idx="7" tabindex="0" class="page-link">Next</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
   <div class="modal fade" id="single-person" tabindex="-1" role="dialog" aria-labelledby="single-person" aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="single-person">Job Name : Demo version 2.0.1</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body" style="padding:25px;">
               <div class="table-custom table-responsive">
                  <table id="customes" class="table table-striped table-bordered" style="width:100%">
                     <thead>
                        <tr>
                           <th>Sr. No.</th>
                           <th>Job Name</th>
                           <th>ID</th>
                           <th>Name</th>
                           <th>Mobile</th>
                           <th>Email</th>
                           <th>Address</th>
                           <th>Date</th>
                           <th>Call sent</th>
                           <th>SMS sent</th>
                           <th>Email sent</th>
                           <th>SMS link clicked</th>
                           <th>Form Filled</th>
                           <th>QC Status</th>
                           <th>Report</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>1</td>
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
                        <tr>
                           <td>1</td>
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
                        <tr>
                           <td>1</td>
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
                           <td>Done <a href="confirmation.php" class="qc1">QC</a></td>
                           <td><a href="#" class="qc1">Link</a></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
