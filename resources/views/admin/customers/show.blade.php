
@extends('layouts.admin')
@section('content')

    <div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
        <div class="main-content">        
            <div class="row">
                <div class="col-sm-11">
                    <ul class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ url('/customers') }}">Customer</a></li>
                    <li>Detail</li>
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
                                <li><strong>Company Name :</strong> {{ ucfirst($item->company_name) }}</li>
                                <li><strong>Contact Person :</strong> {{$item->name}}</li>
                                <li><strong>Email :</strong> {{$item->email}}</li>
                                <li><strong>Phone :</strong> {{$item->phone}}</li>
                                <li><strong>Address :</strong> {{$item->address_line1.', '.$item->zipcode.' '.$item->city_name}}</li>
                            </ul>
                        </div>
                        <div class="table-box mt-40">
                        <!-- include menu -->
                        @include('admin.customers.menu')
                        <!-- include menu -->

                    <div class="tab-content" id="myIconTabContent">
                        <div class="tab-pane fade active show" id="candidatetb1" role="tabpanel" aria-labelledby="candidatetab">
                            
                         <div class="row" style="margin-bottom:15px">
                            <div class="col-12 pb-3">           
                                <div class="btn-group" style="float:right">   
                                    <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>        
                                </div>
                            </div>
                            <div class="search-drop-field">
                                <div class="row">
                                    <div class="col-12">           
                                        <div class="btn-group" style="float:right;font-size:24px;">   
                                            <a href="#" class="filter_close text-danger"><i class="far fa-times-circle"></i></a>        
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-1">
                                        <label> From Date </label>
                                        <input class="form-control from_date commonDatePicker" type="text" placeholder="From Date">
                                    </div>
                                    <div class="col-md-2 form-group mb-1">
                                        <label> To Date </label>
                                        <input class="form-control to_date commonDatePicker" type="text" placeholder="To Date">
                                    </div>
                                    <div class="col-md-2 form-group mb-1">
                                        <label>Phone Number </label>
                                        <input class="form-control mob" type="text" placeholder="Phone">
                                    </div>
                                    <div class="col-md-2 form-group mb-1">
                                        <label>Reference Number </label>
                                        <input class="form-control ref" type="text" placeholder="Reference Number">
                                    </div>
                                    <div class="col-md-2 form-group mb-1">
                                        <label>Email Id</label>
                                        <input class="form-control email" type="email" placeholder="Email">
                                    </div>
                                    <div class="col-md-2 form-group mb-1 level_selector">
                                        <label>Candidate Name</label><br>
                                        <select class="form-control candidate_list select" name="candidate" id="candidate">
                                            <option> All </option>
                                            @foreach($candidates as $candidate)
                                            <option value="{{$candidate->id}}"> {{ $candidate->name}} </option>
                                            @endforeach
                                        </select>
                                        
                                        {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                                    </div>
                                </div>
                                <div class="text-right">
                                <button class="btn btn-info search filterBtn" style="width:15%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                </div>
                                {{-- </div> --}}
                            </div>
                        </div>
                            <div id="candidatesResult">
                                @include('admin.customers.candidate_ajax')
                            </div>
                            {{-- <div class="table-responsive tableFixHead" style="height: 300px;">
                               <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Phone</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
 
                                            @if(count($candidates)>0)
                                            @foreach($candidates as $candidate)

                                            <tr>
                                                <th scope="row">BCD-{{$candidate->id}}</th>
                                                <td>{{$candidate->name}}</td>
                                                <td>{{$candidate->email}}</td>
                                                <td>{{$candidate->phone}}</td>
                                                <td></td>
                                                <td>
                                                <a href="{{ route('/candidates/show',['id'=>  base64_encode($candidate->id)]) }}"><button class="btn btn-success" type="button">View</button></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                             <tr>
                                                <td colspan="5"><h3>Record not available!</h3></td>
                                             </tr>
                                            @endif
                                                                
                                        </tbody>
                                    </table>
                                </div>
                            </div> --}}
                                    <!-- 1st Tab Has Been End Here -->
                                <!-- 2nd Tab Starts From Here -->
                               
                                    {{-- <div class="tab-pane fade" id="jobtb1" role="tabpanel" aria-labelledby="jobtab">
                                        <div class="table-responsive">
                                        <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Job Name</th>
                                                <th scope="col">Verification type</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">view details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>Watch Store</td>
                                                <td>Face recongnition</td>
                                                <td><span class="badge badge-success">Delivered</span></td>
                                                <td>
                                                <a href="#"><button class="btn btn-success" type="button">View</button></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">2</th>
                                                <td>Watch Store</td>
                                                <td>Face recongnition</td>
                                                <td><span class="badge badge-info">Pending</span></td>
                                                <td>
                                                <a href="#"><button class="btn btn-success" type="button">View</button></a>
                                                </td>
                                            </tr><tr>
                                                <th scope="row">3</th>
                                                <td>Watch Store</td>
                                                <td>Face recongnition</td>
                                                <td><span class="badge badge-warning">Not Delivered</span></td>
                                                <td>
                                                <a href="#"><button class="btn btn-success" type="button">View</button></a>
                                                </td>
                                            </tr><tr>
                                                <th scope="row">4</th>
                                                <td>Watch Store</td>
                                                <td>Face recongnition</td>
                                                <td><span class="badge badge-success">Delivered</span></td>
                                                <td>
                                                <a href="#"><button class="btn btn-success" type="button">View</button></a>
                                                </td>
                                            </tr>                     
                                        </tbody>
                                    </table>
                                        </div>
                                    </div> --}}

                                    <!-- 2nd Tab Has Been End Here -->
                                    <!-- 3rd Tab Starts From Here -->
                                    
{{-- 
                                    <div class="tab-pane fade" id="qctb1" role="tabpanel" aria-labelledby="qctab">
                                    <table class="table table-striped table-bordered qcs_table" data-resizable-columns-id="demo-table">
                                        <thead>
                                            <tr>
                                                <th data-resizable-column-id="1" colspan="5" style="width: 31.37%;"> </th>
                                                <th data-resizable-column-id="2" colspan="5" style="width: 39.95%;"> Candidate </th>
                                                <th data-resizable-column-id="3" colspan="5" style="width: 29.13%;"> QC </th>
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

                                    </div> --}}

                                    <!-- 3rd Tab Has Been End Here -->
                                    <!-- 4th Tab Starts From Here -->
                                    
                                    {{-- <div class="tab-pane fade" id="paymenttb1" role="tabpanel" aria-labelledby="paymenttab">
                                    <div class="table-responsive tableFixHead" style="height: 300px;">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Invoice No.</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">view details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">1</th>
                                                    <td>07/11/2019</td>
                                                    <td>22007</td>
                                                    <td>Sanjeev Shukla</td>
                                                    <td><span class="badge badge-success">Delivered</span></td></td>
                                                    <td>
                                                    <a href="#"><button class="btn btn-success" type="button">View</button></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">1</th>
                                                    <td>07/11/2019</td>
                                                    <td>22007</td>
                                                    <td>Sanjeev Shukla</td>
                                                    <td><span class="badge badge-info">Pending</span></td></td>
                                                    <td>
                                                    <a href="#"><button class="btn btn-success" type="button">View</button></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">1</th>
                                                    <td>07/11/2019</td>
                                                    <td>22007</td>
                                                    <td>Sanjeev Shukla</td>
                                                    <td><span class="badge badge-danger">Not delivered</span></td></td>
                                                    <td>
                                                    <a href="#"><button class="btn btn-success" type="button">View</button></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">1</th>
                                                    <td>07/11/2019</td>
                                                    <td>22007</td>
                                                    <td>Sanjeev Shukla</td>
                                                    <td><span class="badge badge-success">Delivered</span></td></td>
                                                    <td>
                                                    <a href="#"><button class="btn btn-success" type="button">View</button></a>
                                                    </td>
                                                </tr>                     
                                            </tbody>
                                        </table>
                                    </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
      </div>
        </div>
        
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
      
      
      
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
        
      <div id="customes_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer"><div class="row"><div class="col-sm-12 col-md-6"><div class="dataTables_length" id="customes_length"><label>Show <select name="customes_length" aria-controls="customes" class="custom-select custom-select-sm form-control form-control-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label></div></div><div class="col-sm-12 col-md-6"><div id="customes_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="customes"></label></div></div></div><div class="row"><div class="col-sm-12"><table id="customes" class="table table-striped table-bordered dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="customes_info">
        <thead>
            <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Sr. No.: activate to sort column descending" style="width: 0px;">Sr. No.</th><th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Job Name: activate to sort column ascending" style="width: 0px;">Job Name</th><th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending" style="width: 0px;">ID</th><th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" style="width: 0px;">Name</th><th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Mobile: activate to sort column ascending" style="width: 0px;">Mobile</th><th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 0px;">Email</th><th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" style="width: 0px;">Address</th><th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 0px;">Date</th><th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Call sent: activate to sort column ascending" style="width: 0px;">Call sent</th><th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="SMS sent: activate to sort column ascending" style="width: 0px;">SMS sent</th><th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Email sent: activate to sort column ascending" style="width: 0px;">Email sent</th><th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="SMS link clicked: activate to sort column ascending" style="width: 0px;">SMS link clicked</th><th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Form Filled: activate to sort column ascending" style="width: 0px;">Form Filled</th><th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="QC Status: activate to sort column ascending" style="width: 0px;">QC Status</th><th class="sorting" tabindex="0" aria-controls="customes" rowspan="1" colspan="1" aria-label="Report: activate to sort column ascending" style="width: 0px;">Report</th></tr>
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
            </tr><tr role="row" class="even">
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
            </tr><tr role="row" class="odd">
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
            </tr></tbody>
    </table></div></div><div class="row"><div class="col-sm-12 col-md-5"><div class="dataTables_info" id="customes_info" role="status" aria-live="polite">Showing 1 to 3 of 3 entries</div></div><div class="col-sm-12 col-md-7"><div class="dataTables_paginate paging_simple_numbers" id="customes_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="customes_previous"><a href="#" aria-controls="customes" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="customes" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item next disabled" id="customes_next"><a href="#" aria-controls="customes" data-dt-idx="2" tabindex="0" class="page-link">Next</a></li></ul></div></div></div></div>
        </div>

      </div>
      
    </div>
  </div>
</div>
      
</div>
<script type="text/javascript">

    $(document).ready(function(){
        $(".select").select2();
        $('.filter0search').click(function(){
            $('.search-drop-field').toggle();
        });
        $('.filter_close').click(function(){
            $('.search-drop-field').toggle();
        });
    });

    $(document).on('change','.from_date',function() {
    
    var from = $('.from_date').datepicker('getDate');
    var to_date   = $('.to_date').datepicker('getDate');
    
    if($('.to_date').val() !=""){
    if (from > to_date) {
      alert ("Please select appropriate date range!");
      $('.from_date').val("");
      $('.to_date').val("");
      
     }
    }
    
    });
    //
    $(document).on('change','.to_date',function() {
    
    var to_date = $('.to_date').datepicker('getDate');
    var from   = $('.from_date').datepicker('getDate');
        if($('.from_date').val() !=""){
        if (from > to_date) {
          alert ("Please select appropriate date range!");
          $('.from_date').val("");
          $('.to_date').val("");
          
         }
        }
    
    });

    $(document).on('change','.customer_list, .candidate_list, .from_date, .to_date,.mob,.ref,.email', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
    });
    
    $(document).on('click','.filterBtn', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
    });
    
    function getData(page){
        //set data
        var user_id     =    $(".customer_list").val();                
        // var check       =    $(".check option:selected").val();
        
    
        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();      
        var candidate_id=    $(".candidate_list option:selected").val();
        var mob = $('.mob').val();
        var ref = $('.ref').val();
        var email = $('.email').val();
                              
    
            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
    
            $.ajax(
            {
                url: '?page=' + page+'&customer_id='+user_id+'&status='+status+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&mob='+mob+'&ref='+ref+'&email='+email,
                type: "get",
                datatype: "html",
            })
            .done(function(data)
            {
                $("#candidatesResult").empty().html(data);
                $("#overlay").fadeOut(300);
                //debug to check page number
                location.hash = page;
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                alert('No response from server');
    
            });
    
    }
    
    function setData(){
    
        var user_id     =    $(".customer_list").val();                
        // var check       =    $(".check option:selected").val();
    
        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();    
        var candidate_id=    $(".candidate_list option:selected").val();                            
        var mob = $('.mob').val();
        var ref = $('.ref').val();
        var email = $('.email').val();
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&mob='+mob+'&ref='+ref+'&email='+email,
                type: "get",
                datatype: "html",
            })
            .done(function(data)
            {
               console.log(data);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                //alert('No response from server');
            });
    
    }
   
//

    </script>
@endsection

   