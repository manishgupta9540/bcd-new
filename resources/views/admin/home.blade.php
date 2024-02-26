@extends('layouts.admin')

@section('content')
<style>
  .disabled-link{
      pointer-events: none;
  }
  .dropdown-menu.inner.show {
    top: 0vh!important;
    padding: 15px 10px;
    max-height: 350px!important;
    overflow-y: auto!important;
    overflow-x: hidden;
    min-height: unset!important;
}
  .btn-group.btn-group-sm.btn-block {
    z-index: 99;
}
.customer > .dropdown-menu.show {
    transform: translate3d(0px, 32px, 0px)!important;
}
.customer >  div#bs-select-2 {
    overflow: hidden!important;
}
.customer > div#bs-select-2 {
    margin-top: -27px;
}
.customer > div#bs-select-1 {
    overflow: hidden!important;
}
.hiddenRow {
    padding: 0 4px !important;
    background-color: #eeeeee;
    font-size: 13px;
}
.accordian-body span{
    color:#a2a2a2 !important;
}
.action-data
{
  max-height: 300px;
  overflow-y: auto;
}
.card-icon-bg .fa{
  font-size: 21px;
    background: #0C396F!important;
    padding: 12px;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    color: #fff;
    display: flex;
    justify-content: center;
}
.text-24 {
    font-size: 35px;
    font-weight: 900;
    padding-top: 15px;
    color: #000!important;
}
.pd-10 {
    padding-left: 24px!important;
    padding-right: 24px!important;
}
.sort_desc {
    position: relative;
    top: -16%;
    font-size: 15px;
}
.below-heading-dash {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    font-size:12px;
}
.below-heading-dash .text-18 {
    font-size: 12px;
    color: #fff!important;
    font-weight: 600;
}
.counting {
    background-color: #DC4B45;
    padding: 3px;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 13px;
    border-radius: 2px;
}
.card-icon-bg {
    min-height: 291px;
}
</style>
 <div class="main-content-wrap sidenav-open d-flex flex-column">
  <div class="main-content">         
    <div class="card">
      <div class="card-body">
        @php
            $jaf_action_data = Helper::role_permission_action(Auth::user()->id,'','Team Lead JAF Filling Dashboard');

            $ver_action_data = Helper::role_permission_action(Auth::user()->id,'','Team Lead Task for Verification Dashboard');

            $report_action_data = Helper::role_permission_action(Auth::user()->id,'','Team Lead Report Writing Dashboard');

            $TOTAL_CUSTOMER_CAM  = false;
            $TOTAL_CUSTOMER_CAM  = Helper::can_access('Total Customer - CAM','');

            $TOTAL_CANDIADTE_CAM = false;
            $TOTAL_CANDIADTE_CAM =  Helper::can_access('Total Candidate - CAM','');

            $COMPLETED_JAF_CAM   = false;
            $COMPLETED_JAF_CAM   = Helper::can_access('Completed JAF - CAM','');

            $TOTAL_CEHECK_CAM   = false;
            $TOTAL_CEHECK_CAM   = Helper::can_access('Total Checks - CAM','');

            $TOTAL_REPORTS_CAM   = false;
            $TOTAL_REPORTS_CAM   = Helper::can_access('Total Reports - CAM','');

           // dd($jaf_action_data);
        @endphp
        <div class="row">
          <div class="col-md-12 text-center">
            <h2 class="text-center font-weight-bolder">[Confidential]</h2>   
        </div>
            <div class="col-lg-3">
                <h3 class="mr-2"> Dashboard </h3>
            </div>
            <div class="col-lg-2 pt-2">
              <div class="" style="float: right;">
                <button class="btn btn-link ops_excel" type="button" title="Click to Download OPS Tracker">
                  <i class="fas fa-file-excel"></i> OPS Tracker
                </button><br>
                {{-- <p class="text-danger text-center mis_load"></p> --}}
              </div>
            </div>
            <div class="col-lg-2 pt-2">
              <div class="" style="float: right;">
                <button class="btn btn-link mis_excel" type="button" title="Click to Download Master Tracker">
                  <i class="fas fa-file-excel"></i> Master Tracker
                </button><br>
                {{-- <p class="text-danger text-center mis_load"></p> --}}
              </div>
                {{-- <div class="btn-group" style="float: right;">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Last 30 days
                    </button>

                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px,27px, 0px);">
                        <a class="dropdown-item" href="#">Today Only</a>
                        <a class="dropdown-item" href="#">This Week</a>
                        <a class="dropdown-item" href="#">This Month</a>
                        <a class="dropdown-item" href="#">This Year</a>
                    </div>
                </div>       --}}
            </div>
            <div class="col-lg-2 pt-2">
              <div class="" style="float: right;">
                {{-- <button class="btn btn-link sales_excel">
                  <i class="fas fa-chart-line"></i> Sales Tracker
                </button><br> --}}
                <a href="{{url('/sales-dashboard')}}" class="btn btn-link" title="Click to View Sales Tracker">
                  <i class="fas fa-chart-line"></i> Sales Tracker
                </a><br>
                {{-- <p class="text-danger text-center mis_load"></p> --}}
              </div>
            </div>
            <div class="col-lg-2 pt-2">
              <div class="" style="float: right;">
                {{-- <button class="btn btn-link progress_excel" type="button">
                  <i class="fas fa-file-excel"></i> Progress Tracker
                </button><br> --}}
                <a href="{{url('/progress-dashboard')}}" class="btn btn-link" title="Click to View Progress Tracker">
                  <i class="fas fa-chart-line"></i> Progress Tracker
                </a><br>
                {{-- <p class="text-danger text-center mis_load"></p> --}}
              </div>
            </div>
            @if(stripos(Auth::user()->user_type,'user')!==false && ($jaf_action_data!=NULL || $ver_action_data!=NULL || $report_action_data!=NULL))
              <div class="col-lg-1">
                <div class="btn-group" style="float:right">     
                    <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>
                </div>
              </div>
            @endif
        </div>
        @if(stripos(Auth::user()->user_type,'user')!==false && ($jaf_action_data!=NULL || $ver_action_data!=NULL || $report_action_data!=NULL))
          <div class="search-drop-field pb-3" id="search-drop">
            <div class="row">
              <div class="col-12">           
                    <div class="btn-group" style="float:right;font-size:24px;">   
                        <a href="#" class="filter_close text-danger"><i class="far fa-times-circle"></i></a>        
                    </div>
                </div>
            </div>
            <div class="row">
              <div class="col-3 form-group mb-1">
                  <label> From date </label>
                  <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
              </div>
              <div class="col-3 form-group mb-1">
                  <label> To date </label>
                  <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
              </div>
              <div class="col-1">
                  <button class="btn btn-danger resetBtn" style="width:100%;padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i>  Reset </button>
              </div>
              <div class="col-1">
                  <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
              </div>
            </div>
          </div>
        @endif
        <div class="row dashboardResult">
          @if($TOTAL_CUSTOMER_CAM)
            <div class="col-lg-3 col-sm-6 customerCard">
            </div>
          @endif
          @if($TOTAL_CANDIADTE_CAM)
            <div class="col-lg-3 col-sm-6 candidateCard">
            </div>
          @endif
          @if($COMPLETED_JAF_CAM)
            <div class="col-lg-3 col-sm-6 jafCard">
            </div>
          @endif
          @if($TOTAL_CEHECK_CAM)
            <div class="col-lg-3 col-sm-6 checkCard">
            </div>
          @endif 
          @if($TOTAL_REPORTS_CAM) 
            <div class="col-lg-3 col-sm-6 reportCard">
            </div>
          @endif  
        </div>

        {{-- @if(count($kams)>0)
          <div class="row dashboardResult">
            @if($TOTAL_CUSTOMER_CAM)
              <div class="col-lg-3 col-sm-6 customerCard">

              </div>
            @endif
          </div>
        @endif --}}


        @if(count($kams)==0)
          <div class="dashboardCheckResult">

          </div>
        @endif

        

        @if(stripos(Auth::user()->user_type,'user')!==false)
            <div class="dashboardTaskResult">
              <div class="row">
                @if($jaf_action_data!=NULL)
                  <div class="col-lg-12 col-sm-12 dashboardTaskJafResult">

                  </div>
                @endif
                @if($ver_action_data!=NULL)
                  <div class="col-lg-12 col-sm-12 dashboardTaskVerResult">

                  </div>
                @endif
                @if($report_action_data!=NULL)
                  <div class="col-lg-12 col-sm-12 dashboardTaskReportResult">

                  </div>
                @endif
              </div>
            </div>
          @else
          <div class="dashboardTaskResult">
            <div class="row">
                <div class="col-lg-12 col-sm-12 dashboardTaskJafResult">

                </div>
                <div class="col-lg-12 col-sm-12 dashboardTaskVerResult">

                </div>
                <div class="col-lg-12 col-sm-12 dashboardTaskReportResult">

                </div>
            </div>
          </div>
        @endif
        
      </div>
    </div>
 
  </div>
</div>

{{-- modal for sales excel export --}}
<div class="modal" id="sales_modal">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
           <h4 class="modal-title" id="name">Sales Tracker Report</h4>
           {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
        </div>
        <!-- Modal body -->
        <form method="post" action="{{url('/sales-tracker')}}" enctype="multipart/form-data" id="sales_data_form">
        @csrf
            @php
              $start_year = 2020;
              $end_year = date('Y');

              $diff = abs($end_year - $start_year);
            @endphp
           <div class="modal-body">
            <div class="form-group">
              <label>Duration Type : <span class="text-danger">*</span></label>
              <select class="form-control type" name="type">
                <option value="">--Select--</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="quaterly">Quaterly</option>
                <option value="yearly">Yearly</option>
              </select>
              <p style="margin-bottom: 2px;" class="text-danger error-container error-type" id="error-type"></p> 
            </div>
            <div class="type_result">

            </div>
              {{-- <div class="form-group">
                <label>Year : <span class="text-danger">*</span></label>
                <select class="form-control year" name="year">
                  <option value="">--Select--</option>
                    @for ($i=0;$i<=$diff;$i++)
                      <option value="{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}" @if(date('Y')==date('Y',strtotime('2020-01-01'.' +'.$i.' years'))) selected @endif>{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}</option>
                    @endfor
                </select>
                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-year"></p> 
              </div>
              <div class="form-group">
                <label>Month : <span class="text-danger">*</span></label>
                <select class="form-control month" name="month">
                  <option value="">--Select--</option>
                    @for ($i=1;$i<=date('n');$i++)
                      <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                    @endfor
                </select>
                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-month"></p> 
              </div> --}}
              <div class="form-group">
                <label>Customer : </label>
                <select class="form-control customer" name="customer[]" id="customer" data-actions-box="true" data-selected-text-format="count>1" multiple>
                  {{-- <option value="">-Select-</option> --}}
                    @foreach($customers as $cust)
                        <option value="{{$cust->id}}">{{$cust->company_name.' - '.$cust->name}}</option>   
                    @endforeach
                  </select>
              </div>
              <p style="margin-bottom: 2px;" class="text-danger error-container error-all"></p>
            </div>
           <!-- Modal footer -->
           <div class="modal-footer">
              <button type="submit" class="btn btn-info sale_submit btn-disable">Submit </button>
              <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
           </div>
        </form>
     </div>
  </div>
</div>

<div class="modal" id="ops_modal">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
           <h4 class="modal-title" id="name">OPS Tracker Report</h4>
           {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
        </div>
        <!-- Modal body -->
        <form method="post" action="{{url('/ops-export')}}" enctype="multipart/form-data" id="ops_data_form">
        @csrf
           <div class="modal-body">
              <div class="form-group">
                <label>Duration Type : <span class="text-danger">*</span></label>
                <select class="form-control type" name="type">
                  <option value="">--Select--</option>
                  <option value="daily">Daily</option>
                  <option value="weekly">Weekly</option>
                  <option value="monthly">Monthly</option>
                  <option value="quaterly">Quaterly</option>
                  <option value="yearly">Yearly</option>
                </select>
                <p style="margin-bottom: 2px;" class="text-danger error-container error-type" id="error-type"></p> 
              </div>
              <div class="type_result">

              </div>
              <div class="form-group">
                <label>Customer : </label>
                <select class="form-control customer" name="customer[]" id="customer" data-actions-box="true" data-selected-text-format="count>1" multiple>
                  {{-- <option value="">-Select-</option> --}}
                    @foreach($customers as $cust)
                        <option value="{{$cust->id}}">{{$cust->company_name.' - '.$cust->name}}</option>   
                    @endforeach
                  </select>
              </div>
              <div class="form-group">
                <label>User : </label>
                <select class="form-control user" name="user[]" id="user" data-actions-box="true" data-selected-text-format="count>1" multiple>
                  {{-- <option value="">-Select-</option> --}}
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>   
                    @endforeach
                  </select>
              </div>
              <p style="margin-bottom: 2px;" class="text-danger error-container error-all"></p>
            </div>
           <!-- Modal footer -->
           <div class="modal-footer">
              <button type="submit" class="btn btn-info ops_submit btn-disable">Submit </button>
              <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
           </div>
        </form>
     </div>
  </div>
</div>

<div class="modal" id="progress_modal">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
           <h4 class="modal-title" id="name">Progress Tracker Report</h4>
           {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
        </div>
        <!-- Modal body -->
        <form method="post" action="{{url('/progress-export')}}" enctype="multipart/form-data" id="progress_data_form">
        @csrf
            @php
              $start_year = 2020;
              $end_year = date('Y');

              $diff = abs($end_year - $start_year);
            @endphp
           <div class="modal-body">
            <div class="form-group">
              <label>Month : <span class="text-danger">*</span></label>
              <select class="form-control p_month" name="p_month[]" data-actions-box="true" data-live-search="true" data-live-search-normalize="true" data-live-search-placeholder="Select the Month" data-selected-text-format="count>1" multiple>
                {{-- <option value="">--Select--</option> --}}
                  @for ($i=1;$i<=date('n');$i++)
                    <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                  @endfor
              </select>
              <p style="margin-bottom: 2px;" class="text-danger error-container error-p_month" id="error-p_month"></p> 
            </div>
              <div class="form-group">
                <label>Year : <span class="text-danger">*</span></label>
                <select class="form-control p_year" name="year">
                  <option value="">--Select--</option>
                    @for ($i=0;$i<=$diff;$i++)
                      <option value="{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}" @if(date('Y')==date('Y',strtotime('2020-01-01'.' +'.$i.' years'))) selected @endif>{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}</option>
                    @endfor
                </select>
                <p style="margin-bottom: 2px;" class="text-danger error-container error-year" id="error-year"></p> 
              </div>

              <div class="form-group">
                <label>Report Type : <span class="text-danger">*</span></label><br>
                  <div class="form-check form-check-inline">
                      <input class="form-check-input report_type" type="checkbox" name="report_type[]" value="wip" checked>
                      <label class="form-check-label" for="report_type">WIP</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input report_type" type="checkbox" name="report_type[]" value="close" checked>
                    <label class="form-check-label" for="report_type">Close</label>
                  </div>
                  <p style="margin-bottom: 2px;" class="text-danger error-container error-report_type" id="error-report_type"></p> 
              </div>
              <div class="form-group">
                <label>Customer : </label>
                <select class="form-control customer" name="customer[]" id="customer" data-actions-box="true" data-selected-text-format="count>1" multiple>
                  {{-- <option value="">-Select-</option> --}}
                    @foreach($customers as $cust)
                        <option value="{{$cust->id}}">{{$cust->company_name.' - '.$cust->name}}</option>   
                    @endforeach
                  </select>
              </div>
              <p style="margin-bottom: 2px;" class="text-danger error-container error-all"></p>
            </div>
           <!-- Modal footer -->
           <div class="modal-footer">
              <button type="submit" class="btn btn-info progress_submit btn-disable">Submit </button>
              <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
           </div>
        </form>
     </div>
  </div>
</div>

<div class="modal"  id="excel_preview">
  <div class="modal-dialog" style="max-width: 96%;">
  <div class="modal-content" style=" max-width: 80%;">
      <!-- Modal Header -->
      <div class="modal-header">
          <h4 class="modal-title">Excel Data Preview</h4>
      </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 excel-table">
                 
                </div>
            </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <a href="" id="excel_link" target="_blank"><button type="button" class="btn btn-info"><i class="fas fa-download"></i> Download</button></a>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
  </div>
  </div>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>

  $('.filter0search').click(function(){
      $('.search-drop-field').toggle();
  });
  $('.filter_close').click(function(){
      $('.search-drop-field').toggle();
  });
  $(document).ready(function(){

      var kam_arr = [];

      kam_arr = JSON.parse('{!! json_encode($kams->pluck("business_id")) !!}');

      var user_type = @php echo json_encode(Auth::user()->user_type) @endphp;
      
      var teamleadjafresult=@php 
                            $user = Auth::user()->user_type;
                            if($user == 'customer')
                            {
                              echo json_encode(true);
                            }
                            else
                            {
                              $a=Helper::role_permission_action(Auth::user()->id,'','Team Lead JAF Filling Dashboard'); 
                              if($a!=NULL)
                              {
                                  echo json_encode(true);
                              }
                              else
                              {
                                  echo json_encode(false);
                              }
                            }
                        @endphp;

      var teamleadverresult=@php
                            $user = Auth::user()->user_type;
                            if($user == 'customer')
                            {
                              echo json_encode(true);
                            }
                            else
                            {
                              $a=Helper::role_permission_action(Auth::user()->id,'','Team Lead Task for Verification Dashboard'); 
                              if($a!=NULL)
                              {
                                  echo json_encode(true);
                              }
                              else
                              {
                                  echo json_encode(false);
                              }
                            }
                        @endphp;

      var teamleadreportresult=@php 
                            $user = Auth::user()->user_type;
                            if($user == 'customer')
                            {
                              echo json_encode(true);
                            }
                            else
                            {
                              $a=Helper::role_permission_action(Auth::user()->id,'','Team Lead Report Writing Dashboard'); 
                              if($a!=NULL)
                              {
                                  echo json_encode(true);
                              }
                              else
                              {
                                  echo json_encode(false);
                              }
                            }
                        @endphp;

      //console.log(kam_arr);

      $('.customer').selectpicker({
        'liveSearch' : true,
        'liveSearchNormalize' : true,
        'liveSearchPlaceholder' : 'Select the Customer'
      });

      $('.user').selectpicker({
        'liveSearch' : true,
        'liveSearchNormalize' : true,
        'liveSearchPlaceholder' : 'Select the User'
      });

      $('.p_month').selectpicker();

      setTimeout(()=>{
        //cardAjax();
        customerCardAjax();
        candidateCardAjax();
        jafCardAjax();
        checkCardAjax();
        reportCardAjax();
        if(kam_arr.length<=0)
        {
          checkAjax();
        }

        if(teamleadjafresult==true)
        {
          teamLeadJafAjax();
        }
        if(teamleadverresult==true)
        {
          teamLeadVerAjax();
        }
        if(teamleadreportresult==true)
        {
          teamLeadReportAjax();
        }
      },500);

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

      $(document).on('change','.from_date, .to_date',function(e){
        e.preventDefault();
        console.log(teamleadjafresult);
        if(teamleadjafresult==true)
        {
          teamLeadJafAjax();
        }

        if(teamleadverresult==true)
        {
          teamLeadVerAjax();
        }

        if(teamleadreportresult==true)
        {
          teamLeadReportAjax();
        }

      });

      $(document).on('click','.filterBtn', function (e){    
          e.preventDefault();

          if(teamleadjafresult==true)
          {
            teamLeadJafAjax();
          }

          if(teamleadverresult==true)
          {
            teamLeadVerAjax();
          }

          if(teamleadreportresult==true)
          {
            teamLeadReportAjax();
          }
          
      });

      $(document).on('click', '.resetBtn' ,function(){
          
          $("input[type=text], textarea").val("");

          if(teamleadjafresult==true)
          {
            teamLeadJafAjax();
          }

          if(teamleadverresult==true)
          {
            teamLeadVerAjax();
          }

          if(teamleadreportresult==true)
          {
            teamLeadReportAjax();
          }
          
      });
      

      $(document).on('click','.ops_excel',function(){
                var _this=$(this);
                // // var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';

                // _this.addClass('disabled-link');
                // // $('.mis_load').html(loadingText);
                // // var user_id     =    $(".customer_list").val();                
                // // var from_date   =    $(".from_date").val(); 
                // // var to_date     =    $(".to_date").val();  

                // $.ajax(
                // {
                    
                //     url: "{{ url('/') }}"+'/candidates/setData/',
                //     type: "get",
                //     data: {},
                //     datatype: "html",

                // })
                // .done(function(data)
                // {
                //     window.setTimeout(function(){
                //         _this.removeClass('disabled-link');
                //         // $('.mis_load').html("");
                //         // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                //     },2000);
                    
                //     console.log(data);
                //     var path = "{{ route('/ops-export')}}";
                //     window.open(path);
                // })
                // .fail(function(jqXHR, ajaxOptions, thrownError)
                // {
                //     //alert('No response from server');
                // });

              $('#ops_data_form')[0].reset();
              $('.form-control').removeClass('border-danger');
              $('p.error-container').html("");
              $('.customer').selectpicker('refresh');
              $('.user').selectpicker('refresh');
              $('.type_result').html('');
              $('#ops_modal').modal({
                backdrop: 'static',
                keyboard: false
              });

      });

      $(document).on('submit', 'form#ops_data_form', function (event) {
         event.preventDefault();
         //clearing the error msg
         $('p.error-container').html("");
         $('.form-control').removeClass('border-danger');
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('.btn-disable').attr('disabled',true);
         if($('.ops_submit').html()!== loadingText)
         {
            $('.ops_submit').html(loadingText);
         }
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {
                  window.setTimeout(function(){
                    $('.btn-disable').attr('disabled',false);
                    $('.ops_submit').html('Submit');
                  },2000);
                  // console.log(response);
                  //show the form validates error
                  if(response.success==false ) {                              
                     for (control in response.errors) {  
                        $('.'+control).addClass('border-danger'); 
                        $('.error-' + control).html(response.errors[control]);
                     }
                  }
                  if(response.success==true)
                  {
                    //  window.open(response.url);
                     $('#ops_modal').modal('hide');

                     $('.excel-table').html(response.html);
                     $('#excel_link').attr('href',response.url);
                     $('#excel_preview').modal({
                        backdrop: 'static',
                        keyboard: false
                     });

                  }
                  else if(response.success==false)
                  {
                     $('.error-all').html(response.message);
                  }
                  else
                  {
                     $('.error-all').html('Something Went Wrong !!');
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  // alert("Error: " + errorThrown);
            }
         });
         return false;
      });

      $(document).on('click','.mis_excel',function(){
          var _this=$(this);
          var _token = "{{csrf_token()}}";
           var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';

                //_this.addClass('disabled-link');
                // $('.mis_load').html(loadingText);
                // var user_id     =    $(".customer_list").val();                
                // var from_date   =    $(".from_date").val(); 
                // var to_date     =    $(".to_date").val();  

                // $.ajax(
                // {
                    
                //     url: "{{ url('/') }}"+'/candidates/setData/',
                //     type: "get",
                //     data: {},
                //     datatype: "html",

                // })
                // .done(function(data)
                // {
                //     window.setTimeout(function(){
                //         _this.removeClass('disabled-link');
                //         // $('.mis_load').html("");
                //         // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                //     },2000);
                    
                //     console.log(data);
                //     var path = "{{ route('/mis-export')}}";
                //     window.open(path);
                // })
                // .fail(function(jqXHR, ajaxOptions, thrownError)
                // {
                //     //alert('No response from server');
                // });

                $.ajax({
                    url: "{{ route('/mis-export') }}",
                    type: 'POST',
                    cache: false,
                    data: {'_token': _token },
                    datatype: 'html',
                    beforeSend: function() {
                        //something before send
                        _this.addClass('disabled-link');
                        _this.html(loadingText);
                        //$('.mis_load').html("");
                    },
                    success: function(data) {
                        //console.log(data);

                        window.setTimeout(function(){
                            _this.removeClass('disabled-link');
                            _this.html("<i class='fas fa-file-excel'></i> Master Tracker");
                            // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                        },2000);

                        $('.excel-table').html(data.html);
                        $('#excel_link').attr('href',data.url);
                        $('#excel_preview').modal({
                            backdrop: 'static',
                            keyboard: false
                        });

                        
                    }
                });

      });

      $(document).on('click','.sales_excel',function(){
          var _this=$(this);
          // var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';

                // _this.addClass('disabled-link');
                // $('.mis_load').html(loadingText);
                // var user_id     =    $(".customer_list").val();                
                // var from_date   =    $(".from_date").val(); 
                // var to_date     =    $(".to_date").val();  

                // $.ajax(
                // {
                    
                //     url: "{{ url('/') }}"+'/candidates/setData/',
                //     type: "get",
                //     data: {},
                //     datatype: "html",

                // })
                // .done(function(data)
                // {
                //     window.setTimeout(function(){
                //         _this.removeClass('disabled-link');
                //         // $('.mis_load').html("");
                //         // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                //     },2000);
                    
                //     console.log(data);
                //     var path = "{{ route('/sales-tracker')}}";
                //     window.open(path);
                // })
                // .fail(function(jqXHR, ajaxOptions, thrownError)
                // {
                //     //alert('No response from server');
                // });
              $('#sales_data_form')[0].reset();
              $('.form-control').removeClass('border-danger');
              $('p.error-container').html("");
              $('.customer').selectpicker('refresh');
              $('.type_result').html('');
              $('#sales_modal').modal({
                backdrop: 'static',
                keyboard: false
              });

      });

      $(document).on('click','.progress_excel',function(){
          var _this=$(this);
          // var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';

                // _this.addClass('disabled-link');
                // $('.mis_load').html(loadingText);
                // var user_id     =    $(".customer_list").val();                
                // var from_date   =    $(".from_date").val(); 
                // var to_date     =    $(".to_date").val();  

                // $.ajax(
                // {
                    
                //     url: "{{ url('/') }}"+'/candidates/setData/',
                //     type: "get",
                //     data: {},
                //     datatype: "html",

                // })
                // .done(function(data)
                // {
                //     window.setTimeout(function(){
                //         _this.removeClass('disabled-link');
                //         // $('.mis_load').html("");
                //         // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                //     },2000);
                    
                //     console.log(data);
                //     var path = "{{ route('/sales-tracker')}}";
                //     window.open(path);
                // })
                // .fail(function(jqXHR, ajaxOptions, thrownError)
                // {
                //     //alert('No response from server');
                // });
              $('#progress_data_form')[0].reset();
              $('.form-control').removeClass('border-danger');
              $('p.error-container').html("");
              $('.p_month').selectpicker('refresh');
              $('.customer').selectpicker('refresh');
              $('#progress_modal').modal({
                backdrop: 'static',
                keyboard: false
              });

      });

      $(document).on('submit', 'form#sales_data_form', function (event) {
         event.preventDefault();
         //clearing the error msg
         $('p.error-container').html("");
         $('.form-control').removeClass('border-danger');
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('.btn-disable').attr('disabled',true);
         if($('.sale_submit').html()!== loadingText)
         {
            $('.sale_submit').html(loadingText);
         }
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {
                  window.setTimeout(function(){
                    $('.btn-disable').attr('disabled',false);
                    $('.sale_submit').html('Submit');
                  },2000);
                  // console.log(response);
                  //show the form validates error
                  if(response.success==false ) {                              
                     for (control in response.errors) {  
                        $('.'+control).addClass('border-danger'); 
                        $('.error-' + control).html(response.errors[control]);
                     }
                  }
                  if(response.success==true)
                  {
                     window.open(response.url);
                      // var path = "{{url('/sales-dashboard')}}";
                      // window.open(path);
                     $('#sales_modal').modal('hide');
                  }
                  else if(response.success==false)
                  {
                     $('.error-all').html(response.message);
                  }
                  else
                  {
                     $('.error-all').html('Something Went Wrong !!');
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  // alert("Error: " + errorThrown);
            }
         });
         return false;
      });

      $(document).on('submit', 'form#progress_data_form', function (event) {
         event.preventDefault();
         //clearing the error msg
         $('p.error-container').html("");
         $('.form-control').removeClass('border-danger');
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('.btn-disable').attr('disabled',true);
         if($('.progress_submit').html()!== loadingText)
         {
            $('.progress_submit').html(loadingText);
         }
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {
                  window.setTimeout(function(){
                    $('.btn-disable').attr('disabled',false);
                    $('.progress_submit').html('Submit');
                  },2000);
                  // console.log(response);
                  //show the form validates error
                  if(response.success==false ) {                              
                     for (control in response.errors) {  
                        $('.'+control).addClass('border-danger'); 
                        $('.error-' + control).html(response.errors[control]);
                     }
                  }
                  if(response.success==true)
                  {
                     window.open(response.url);
                     $('#progress_modal').modal('hide');
                  }
                  else if(response.success==false)
                  {
                     $('.error-all').html(response.message);
                  }
                  else
                  {
                     $('.error-all').html('Something Went Wrong !!');
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  // alert("Error: " + errorThrown);
            }
         });
         return false;
      });

      $(document).on('change','.type',function(){
          var _this = $(this);

          $('.type_result').html('');
          if(_this.val()!='')
          {
              var type = _this.val();

              if(type.toLowerCase()=='monthly'.toLowerCase())
              {
                  $('.type_result').html(`<div class="row">
                                            <div class="col-sm-6">
                                              <div class="form-group">
                                                <label>Month : <span class="text-danger">*</span></label>
                                                <select class="form-control month" name="month">
                                                  <option value="">--Select--</option>
                                                    @for ($i=1;$i<=date('n');$i++)
                                                      <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                                                    @endfor
                                                </select>
                                                <p style="margin-bottom: 2px;" class="text-danger error-container error-month" id="error-month"></p> 
                                              </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                              @php
                                                $start_year = 2020;
                                                $end_year = date('Y');

                                                $diff = abs($end_year - $start_year);
                                              @endphp
                                              <label>Year : <span class="text-danger">*</span></label>
                                              <select class="form-control year" name="year">
                                                <option value="">--Select--</option>
                                                  @for ($i=0;$i<=$diff;$i++)
                                                    <option value="{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}" @if(date('Y')==date('Y',strtotime('2020-01-01'.' +'.$i.' years'))) selected @endif>{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}</option>
                                                  @endfor
                                              </select>
                                              <p style="margin-bottom: 2px;" class="text-danger error-container error-year" id="error-year"></p> 
                                            </div>
                                            </div>
                                            </div>`);
              }
              else if(type.toLowerCase()=='quaterly'.toLowerCase())
              {
                $('.type_result').html(`<div class="row">
                                        <div class="col-sm-6">
                                        <div class="form-group">
                                          <label>Quater : <span class="text-danger">*</span></label>
                                          <select class="form-control quater" name="quater">
                                            <option value="">--Select--</option>
                                             <option value="q1">April - June</option>
                                             <option value="q2">July - September</option>
                                             <option value="q3">October - December</option>
                                             <option value="q4">January - March</option>
                                          </select>
                                          <p style="margin-bottom: 2px;" class="text-danger error-container error-quater" id="error-quater"></p> 
                                        </div>
                                        </div>
                                        <div class="col-sm-6">
                                        <div class="form-group">
                                          @php
                                            $start_year = 2020;
                                            $end_year = date('Y');

                                            $diff = abs($end_year - $start_year);
                                          @endphp
                                          <label>Year : <span class="text-danger">*</span></label>
                                          <select class="form-control year" name="year">
                                            <option value="">--Select--</option>
                                              @for ($i=0;$i<=$diff;$i++)
                                                <option value="{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}" @if(date('Y')==date('Y',strtotime('2020-01-01'.' +'.$i.' years'))) selected @endif>{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}</option>
                                              @endfor
                                          </select>
                                          <p style="margin-bottom: 2px;" class="text-danger error-container error-year" id="error-year"></p> 
                                        </div>
                                        </div>
                                        </div>`);
              }
              else if(type.toLowerCase()=='yearly'.toLowerCase())
              {
                $('.type_result').html(`<div class="form-group">
                                          @php
                                            $start_year = 2020;
                                            $end_year = date('Y');
                                            $diff = abs($end_year - $start_year);
                                          @endphp
                                          <label>Year : <span class="text-danger">*</span></label>
                                          <select class="form-control year" name="year">
                                            <option value="">--Select--</option>
                                              @for ($i=0;$i<=$diff;$i++)
                                                <option value="{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}" @if(date('Y')==date('Y',strtotime('2020-01-01'.' +'.$i.' years'))) selected @endif>{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}</option>
                                              @endfor
                                          </select>
                                          <p style="margin-bottom: 2px;" class="text-danger error-container error-year" id="error-year"></p> 
                                        </div>`);
              }
          }
      });

      $(document).on('change','.year',function(){
        var _this = $(this);
        var year = new Date().getFullYear();
        if(_this.val()!='')
        {
            if(_this.val()==year)
            {
                $('.month').html(` <option value="">--Select--</option>
                    @for ($i=1;$i<=date('n');$i++)
                      <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                    @endfor`);
            }
            else
            {
                $('.month').html(`
                    <option value="">--Select--</option>
                    @for ($i=1;$i<=12;$i++)
                      <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                    @endfor
                `);
            }
        }
        // else
        // {
        //     alert('Select the specific year');

        //     $('.year option[value="2021"]').attr('selected', 'selected').change();

        //     $('.month').html(` <option value="">--Select--</option>
        //             @for ($i=1;$i<=date('n');$i++)
        //               <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
        //             @endfor`);
        // }
      });

      $(document).on('change','.p_year',function(){
        var _this = $(this);
        var year = new Date().getFullYear();
        if(_this.val()!='')
        {
            if(_this.val()==year)
            {
              $('.p_month').selectpicker('destroy');
              $(".p_month").empty();

                $('.p_month').html(`
                    @for ($i=1;$i<=date('n');$i++)
                      <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                    @endfor`);

                $('.p_month').selectpicker();
            }
            else
            {
                $('.p_month').selectpicker('destroy');
                $(".p_month").empty();

                $('.p_month').html(`
                    @for ($i=1;$i<=12;$i++)
                      <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                    @endfor
                `);

                $('.p_month').selectpicker();
            }
        }
        // else
        // {
        //     alert('Select the specific year');

        //     $('.year option[value="2021"]').attr('selected', 'selected').change();

        //     $('.month').html(` <option value="">--Select--</option>
        //             @for ($i=1;$i<=date('n');$i++)
        //               <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
        //             @endfor`);
        // }
      });
  });

  // function cardAjax()
  // {
  //   var _token = "{{ csrf_token() }}";
  //   $.ajax({
  //           url: "{{ url('/home/render') }}",
  //           type: 'POST',
  //           cache: false,
  //           data: {'_token': _token },
  //           datatype: 'html',
  //           beforeSend: function() {
  //               //something before send
  //               $('.dashboardResult').html("<div class='col-12' style='background-color:#ddd; min-height:600px; line-height:600px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
  //           },
  //           success: function(data) {
  //               //console.log(data);
  //               $('.dashboardResult').html(data.html);
  //           }
  //       });
  // }

  function customerCardAjax()
  {
    var _token = "{{ csrf_token() }}";

    $.ajax({
            url: "{{ route('/home/dashboard-customer-card') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token},
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.customerCard').html(cardLoaderHtml()).fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.customerCard').html(data.html);
            }
        });
  }

  function candidateCardAjax()
  {
    // var _token = "{{ csrf_token() }}";
    // $.ajax({
    //         url: "{{ url('/home/dashboard-candidate-card') }}",
    //         type: 'POST',
    //         cache: false,
    //         data: {'_token': _token },
    //         datatype: 'html',
    //         beforeSend: function() {
    //             //something before send
    //             $('.candidateCard').html(cardLoaderHtml()).fadeIn(300);
    //         },
    //         success: function(data) {
    //             //console.log(data);
    //             $('.candidateCard').html(data.html);
    //         }
    //     });

      $('.candidateCard').html(cardLoaderHtml()).fadeIn(300);

      setTimeout(()=>{
        $('.candidateCard').html(`<div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4 " style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
                                  <div class="card-body pd-10 ">
                                    <i class="fa fa-users"></i>
                                      <div class="data-content">
                                          <div class="row">
                                            @php
                                              $candidate_kams_url = '';
                                              if(count($kams)>0)
                                              {
                                                  $candidate_kams_url = '?candidate_kams_url=1';
                                              }
                                            @endphp
                                            @if($candidate_kams_url)
                                              <div class="col-lg-12 top-heading-dash">
                                                <a class="text-24 line-height-2 mb-2 candidate_count" href="{{url('/candidates'.$candidate_kams_url)}}"></a><br>
                                                <a class="mt-2 mb-0 sort_desc" href="{{url('/candidates'.$candidate_kams_url)}}"> <strong>Total Candidate</strong> </a>
                                              </div>
                                            @else
                                              <div class="col-lg-12 top-heading-dash">
                                                <a class="text-24 line-height-2 mb-2 candidate_count" href="{{url('/candidates')}}"></a><br>
                                                <a class="mt-2 mb-0 sort_desc" href="{{url('/candidates')}}"> <strong>Total Candidate</strong> </a>
                                              </div>
                                            @endif
                                          </div>

                                          @if($candidate_kams_url)
                                            <div class="row mt-20">
                                              <div class="col-lg-12 below-heading-dash">
                                                <a href="{{ url('/candidates'.$candidate_kams_url.'&sendto=customer') }}" class="mt-2 mb-0"><strong> JAF send to customer </strong></a>
                                                <a href="{{ url('/candidates'.$candidate_kams_url.'&sendto=customer') }}" class="text-18 line-height-2  jaf_customer_count counting"></a>
                                              </div>
                                              <div class="col-lg-12 below-heading-dash">
                                                <a href="{{ url('/candidates'.$candidate_kams_url.'&sendto=coc') }}" class="mt-2 mb-0"><strong> JAF send to COC </strong></a>
                                                <a href="{{ url('/candidates'.$candidate_kams_url.'&sendto=coc') }}" class="text-wh text-18 line-height-2  jaf_coc_count counting"></a>
                                              </div>
                                              
                                              <div class="col-lg-12 below-heading-dash">
                                                <a href="{{ url('/candidates'.$candidate_kams_url.'&sendto=candidate') }}" class="mt-2 mb-0"><strong> JAF send to candidate </strong></a>
                                                <a href="{{ url('/candidates'.$candidate_kams_url.'&sendto=candidate') }}" class="text-wh text-18 line-height-2 mb-2 jaf_candidate_count counting"><div class='fa-3x' style="display: flex;align-items: center;justify-content: center;"><i class='fas fa-spinner fa-pulse' style="background:transparent;font-size:24px;"></i></div> </a>
                                              </div>
                                            </div>
                                          @else
                                            <div class="row mt-20">
                                                <div class="col-lg-12 below-heading-dash">
                                                  <a href="{{ url('/candidates/?sendto=customer') }}" class="mt-2 mb-0"><strong> JAF send to customer </strong></a>
                                                  <a href="{{ url('/candidates/?sendto=customer') }}" class="text-18 line-height-2  jaf_customer_count counting"></a>
                                                </div>
                                                <div class="col-lg-12 below-heading-dash">
                                                  <a href="{{ url('/candidates/?sendto=coc') }}" class="mt-2 mb-0"><strong> JAF send to COC </strong></a>
                                                  <a href="{{ url('/candidates/?sendto=coc') }}" class="text-wh text-18 line-height-2  jaf_coc_count counting"></a>
                                                </div>
                                                
                                                <div class="col-lg-12 below-heading-dash">
                                                  <a href="{{ url('/candidates/?sendto=candidate') }}" class="mt-2 mb-0"><strong> JAF send to candidate </strong></a>
                                                  <a href="{{ url('/candidates/?sendto=candidate') }}" class="text-wh text-18 line-height-2 mb-2 jaf_candidate_count counting"><div class='fa-3x' style="display: flex;align-items: center;justify-content: center;"><i class='fas fa-spinner fa-pulse' style="background:transparent;font-size:24px;"></i></div> </a>
                                                </div>
                                              </div>
                                          @endif
                                      </div>
                                  </div>
                              </div>`);

          candidateCardCountAjax();
          candidateCardJafCustomerCountAjax();
          candidateCardJafCocCountAjax();
          candidateCardJafCandidateCountAjax();
      },500);

      
  }

  function jafCardAjax()
  {
    var _token = "{{ csrf_token() }}";
    $.ajax({
            url: "{{ route('/home/dashboard-jaf-card') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token },
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.jafCard').html(cardLoaderHtml()).fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.jafCard').html(data.html);
            }
        });
  }

  function checkCardAjax()
  {
    var _token = "{{ csrf_token() }}";
        $.ajax({
            url: "{{ route('/home/dashboard-check-card') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token },
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.checkCard').html(cardLoaderHtml()).fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.checkCard').html(data.html);
            }
        });
  }

  function reportCardAjax()
  {
    var _token = "{{ csrf_token() }}";
        $.ajax({
            url: "{{ route('/home/dashboard-report-card') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token },
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.reportCard').html(cardLoaderHtml()).fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.reportCard').html(data.html);
            }
        });
  }

  function checkAjax()
  {
    var _token = "{{ csrf_token() }}";
    $.ajax({
            url: "{{ route('/home/check/render') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token },
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.dashboardCheckResult').html("<div style='background-color:#ddd; min-height:700px; line-height:700px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.dashboardCheckResult').html(data.html);
            }
        });
  }

  function teamLeadAjax()
  {
    var _token = "{{ csrf_token() }}";

    var from_date   =    $(".from_date").val()==undefined?'':$(".from_date").val(); 
    var to_date     =    $(".to_date").val()==undefined?'':$(".to_date").val(); 

    $.ajax({
            url: "{{ route('/home/team-lead/render') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token,'from_date':from_date,'to_date':to_date},
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.dashboardTaskResult').html("<div style='background-color:#ddd; min-height:700px; line-height:700px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.dashboardTaskResult').html(data.html);
            }
        });
  }

  function teamLeadJafAjax()
  {
    var _token = "{{ csrf_token() }}";

    var from_date   =    $(".from_date").val()==undefined?'':$(".from_date").val();
    var to_date     =    $(".to_date").val()==undefined?'':$(".to_date").val(); 

    $.ajax({
            url: "{{ route('/home/team-lead-jaf-fill/render') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token,'from_date':from_date,'to_date':to_date},
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.dashboardTaskJafResult').html(cardLoaderHtml()).fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.dashboardTaskJafResult').html(data.html);

                if(from_date!='')
                {
                    //alert(from_date);
                    var jaf_size = $('.jaf_fill_link').length;

                    if(jaf_size > 0)
                    {
                        $('.jaf_fill_link').each(function(i,v){

                            var href = $(this).attr('href');

                            $(this).attr('href',href+'&task_start_date='+from_date);
                        });
                    }
                }

                if(to_date!='')
                {
                    var jaf_size = $('.jaf_fill_link').length;

                    if(jaf_size > 0)
                    {
                        $('.jaf_fill_link').each(function(i,v){

                            var href = $(this).attr('href');

                            $(this).attr('href',href+'&task_end_date='+to_date);

                        });
                    }
                }
            }
        });
  }

  function teamLeadVerAjax()
  {
      var _token = "{{ csrf_token() }}";

      var from_date   =    $(".from_date").val()==undefined?'':$(".from_date").val(); 
      var to_date     =    $(".to_date").val()==undefined?'':$(".to_date").val(); 

      $.ajax({
              url: "{{ route('/home/team-lead-ver/render') }}",
              type: 'POST',
              cache: false,
              data: {'_token': _token,'from_date':from_date,'to_date':to_date},
              datatype: 'html',
              beforeSend: function() {
                  //something before send
                  $('.dashboardTaskVerResult').html(cardLoaderHtml()).fadeIn(300);
              },
              success: function(data) {
                  //console.log(data);
                  $('.dashboardTaskVerResult').html(data.html);

                  if(from_date!='')
                  {
                      //alert(from_date);
                      var jaf_size = $('.verify_task_link').length;

                      if(jaf_size > 0)
                      {
                          $('.verify_task_link').each(function(i,v){

                              var href = $(this).attr('href');

                              $(this).attr('href',href+'&task_start_date='+from_date);
                          });
                      }
                  }

                  if(to_date!='')
                  {
                      var jaf_size = $('.verify_task_link').length;

                      if(jaf_size > 0)
                      {
                          $('.verify_task_link').each(function(i,v){

                              var href = $(this).attr('href');

                              $(this).attr('href',href+'&task_end_date='+to_date);

                          });
                      }
                  }
              }
          });
  }

  function teamLeadReportAjax()
  {
      var _token = "{{ csrf_token() }}";

      var from_date   =    $(".from_date").val()==undefined?'':$(".from_date").val(); 
      var to_date     =    $(".to_date").val()==undefined?'':$(".to_date").val(); 

      $.ajax({
              url: "{{ route('/home/team-lead-report/render') }}",
              type: 'POST',
              cache: false,
              data: {'_token': _token,'from_date':from_date,'to_date':to_date},
              datatype: 'html',
              beforeSend: function() {
                  //something before send
                  $('.dashboardTaskReportResult').html(cardLoaderHtml()).fadeIn(300);
              },
              success: function(data) {
                  //console.log(data);
                  $('.dashboardTaskReportResult').html(data.html);

                  if(from_date!='')
                  {
                      //alert(from_date);
                      var jaf_size = $('.report_write_link').length;

                      if(jaf_size > 0)
                      {
                          $('.report_write_link').each(function(i,v){

                              var href = $(this).attr('href');

                              $(this).attr('href',href+'&task_start_date='+from_date);
                          });
                      }
                  }

                  if(to_date!='')
                  {
                      var jaf_size = $('.report_write_link').length;

                      if(jaf_size > 0)
                      {
                          $('.report_write_link').each(function(i,v){

                              var href = $(this).attr('href');

                              $(this).attr('href',href+'&task_end_date='+to_date);

                          });
                      }
                  }
                }
          });
  }

  function candidateCardCountAjax()
  {
      var _token = "{{ csrf_token() }}";
      $.ajax({
              url: "{{ route('/home/dashboard-candidate-count-card') }}",
              type: 'POST',
              cache: false,
              data: {'_token': _token },
              datatype: 'html',
              beforeSend: function() {
                  //something before send
                  $('.candidate_count').html(contentLoaderHtml()).fadeIn(300);
              },
              success: function(data) {
                  //console.log(data);
                  $('.candidate_count').html(data.html);
              }
          });
  }

  function candidateCardJafCustomerCountAjax()
  {
      var _token = "{{ csrf_token() }}";
      $.ajax({
              url: "{{ route('/home/dashboard-candidate-jaf-customer-count-card') }}",
              type: 'POST',
              cache: false,
              data: {'_token': _token },
              datatype: 'html',
              beforeSend: function() {
                  //something before send
                  $('.jaf_customer_count').html(contentLoaderHtml()).fadeIn(300);
              },
              success: function(data) {
                  //console.log(data);
                  $('.jaf_customer_count').html(data.html);
              }
          });
  }

  function candidateCardJafCocCountAjax()
  {
      var _token = "{{ csrf_token() }}";
      $.ajax({
              url: "{{ route('/home/dashboard-candidate-jaf-coc-count-card') }}",
              type: 'POST',
              cache: false,
              data: {'_token': _token },
              datatype: 'html',
              beforeSend: function() {
                  //something before send
                  $('.jaf_coc_count').html(contentLoaderHtml()).fadeIn(300);
              },
              success: function(data) {
                  //console.log(data);
                  $('.jaf_coc_count').html(data.html);
              }
          });
  }

  function candidateCardJafCandidateCountAjax()
  {
      var _token = "{{ csrf_token() }}";
      $.ajax({
              url: "{{ route('/home/dashboard-candidate-jaf-candidate-count-card') }}",
              type: 'POST',
              cache: false,
              data: {'_token': _token },
              datatype: 'html',
              beforeSend: function() {
                  //something before send
                  $('.jaf_candidate_count').html(contentLoaderHtml()).fadeIn(300);
              },
              success: function(data) {
                  //console.log(data);
                  $('.jaf_candidate_count').html(data.html);
              }
          });
  }

  function loaderHtml() {
    
    return "<div style='background-color:#ddd; min-height:1000px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>";
  }

  function cardLoaderHtml()
  {
    return "<div class='fa-3x' style='min-height:200px;display: flex;align-items: center;justify-content: center;'><i class='fas fa-spinner fa-pulse'></i></div>";
  }

  function contentLoaderHtml()
  {
      return "<div class='fa-3x' style='display: flex;align-items: center;justify-content: center;'><i class='fas fa-spinner fa-pulse' style='background:transparent;font-size:24px;'></i></div>";
  }
  
</script>

@endsection
