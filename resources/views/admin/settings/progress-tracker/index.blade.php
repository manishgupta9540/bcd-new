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

</style>
 <div class="main-content-wrap sidenav-open d-flex flex-column">
  <div class="main-content"> 
    <div class="row">
      <div class="col-sm-11">
          <ul class="breadcrumb">
          <li>
          <a href="{{ url('/home') }}">Dashboard</a>
          </li>
          <li>Progress Tracker</li>
          </ul>
      </div>
      <!-- ============Back Button ============= -->
      <div class="col-sm-1 back-arrow">
          <div class="text-right">
          <a href="{{url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
          </div>
      </div>
  </div>
      <div class="card">  
        <div class="card-body">
            <div class="row">
              <div class="col-12">
                <h3>Progress Tracker </h3>
                <p class="pb-border"></p>
              </div>
                <div class="col-4">
                    <label style="font-size: 14px;"><strong>Duration:</strong> <span class="duration_lbl">{{date('d M Y',strtotime($from_date))}} - {{date('d M Y',strtotime($to_date))}}</span></label>
                </div>
                <div class="col-4">
                    {{-- <label style="font-size: 14px;"><strong>Duration Type:</strong> <span class="duration_type_lbl">{{ucwords($type)}}</span></label> --}}
                </div>
                <div class="col-4">
                    <span class="float-right" style="font-size: 12px;">
                      {{-- <a href="#" class="filter0search"><i class="fa fa-filter"></i></a> --}}
                      {{-- <a class="btn-link" id="exportExcel" href="javascript:;"> <i class="fa fa-file-excel-o"></i> Export Excel</a> 
                      <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p> --}}
                      {{-- <a href="{{$url}}" target="_blank"><i class="fa fa-file-excel-o"></i> Export Excel</a> --}}
                    </span>
                </div>
            </div>

            <div class="row pt-2">
              {{-- <div class="col-12">
                <h5 class="text-muted">Filter:</h5>
                <p class="pb-border"></p>
              </div> --}}
              <div class="col-4">
                <div class="form-group">
                  <label>Duration Type : </label>
                  <select class="form-control type" name="type">
                    <option value="daily" @if(stripos($type,'daily')!==false) selected @endif>Daily</option>
                    <option value="weekly" @if(stripos($type,'weekly')!==false) selected @endif>Weekly</option>
                    <option value="monthly" @if(stripos($type,'monthly')!==false) selected @endif>Monthly</option>
                  </select> 
                </div>
                <div class="type_result">
                    <div class="row">
                        @php
                            $start_year = 2020;
                            $end_year = date('Y');

                            $diff = abs($end_year - $start_year);
                        @endphp
                        <div class="col-6">
                            <div class="form-group">
                                <label>Month : </label>
                                <select class="form-control p_month" name="p_month[]" id="p_month" data-actions-box="true" data-live-search="true" data-live-search-normalize="true" data-live-search-placeholder="Select the Month" data-selected-text-format="count>1" multiple>
                                {{-- <option value="">--Select--</option> --}}
                                    @for ($i=1;$i<=date('n');$i++)
                                        <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}" @if(count($month)>0 && in_array($i, $month)) selected @endif>{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                                    @endfor
                                </select>
                                <p style="margin-bottom: 2px;" class="text-danger error-container error-p_month" id="error-p_month"></p> 
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Year : </label>
                                <select class="form-control p_year" name="p_year">
                                {{-- <option value="">--Select--</option> --}}
                                    @for ($i=0;$i<=$diff;$i++)
                                        <option value="{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}" @if(date('Y')==date('Y',strtotime('2020-01-01'.' +'.$i.' years'))) selected @endif>{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}</option>
                                    @endfor
                                </select>
                                <p style="margin-bottom: 2px;" class="text-danger error-container error-year" id="error-year"></p> 
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label>Customer : </label>
                  <select class="form-control customer" name="customer[]" id="customer" data-actions-box="true" data-selected-text-format="count>1" multiple>
                    {{-- <option value="">-Select-</option> --}}
                      @foreach($customers as $cust)
                          <option value="{{$cust->id}}">{{$cust->company_name.' - '.$cust->name}}</option>   
                      @endforeach
                  </select>
                </div>
              </div>
              <div class="col-2">
                  <div class="form-group">
                        <label>Report Type : </label><br>
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
              </div>
              <div class="col-3 pt-4">
                <button type="button" class="btn btn-info filterBtn"><i class="fas fa-filter"></i> Apply Filter</button>

                {{-- <button type="button" class="btn btn-dark" id="exportExcel"><i class="fas fa-file-excel"></i> Export Excel</button> --}}

                {{-- <button type="button" class="btn btn-dark" id="sendProgressExport"><i class="fas fa-paper-plane"></i> Send Progress Export</button> --}}

                <button type="button" class="btn btn-dark" id="excelPreviewBtn"><i class="fas fa-file-excel"></i> Export Preview</button>
                <p id="loading" class="text-danger"></p>
              </div>
            </div>
            <div id="graphResult">
              @include('admin.settings.progress-tracker.ajax')
            </div>
        </div>
      </div>
 
  </div>
</div>
<div class="modal"  id="excel_preview_mdl">
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
              <button type="button" class="btn btn-info" id="sendProgressExport"><i class="fas fa-file-excel"></i> Send Progress Export</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
  $(document).ready(function(){
    setTimeout(()=>{
      totalHoursCard();
      jafTaskCard();
      verificationTaskCard();
      insuffCard();
     
    },500);

    $('.customer').selectpicker({
        'liveSearch' : true,
        'liveSearchNormalize' : true,
        'liveSearchPlaceholder' : 'Select the Customer'
      });

      $('.p_month').selectpicker();

        var uriNum = location.hash;
        pageNumber = uriNum.replace("#", "");
        // alert(pageNumber);
        getData(pageNumber);
        // totalHoursCard();
        // jafTaskCard();
        // verificationTaskCard();
        // insuffCard();

        $(document).on('click','.filterBtn', function (e){    
            $("#overlay").fadeIn(300);　
            getData(0);
            totalHoursCard();
            jafTaskCard();
            verificationTaskCard();
            insuffCard();
            e.preventDefault();
           

            $('#loading').html('');
        });

        $(document).on('change','.type',function(){
          var _this = $(this);

          $('.type_result').html('');
            if(_this.val()!='')
            {
                var type = _this.val();

                if(type.toLowerCase()=='monthly'.toLowerCase())
                {
                    $('.p_month').selectpicker('destroy');
                    $(".p_month").empty();
                    $('.type_result').html(`<div class="row">
                                                    @php
                                                        $start_year = 2020;
                                                        $end_year = date('Y');

                                                        $diff = abs($end_year - $start_year);
                                                    @endphp
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Month : </label>
                                                            <select class="form-control p_month" name="p_month[]" id="p_month" data-actions-box="true" data-live-search="true" data-live-search-normalize="true" data-live-search-placeholder="Select the Month" data-selected-text-format="count>1" multiple>
                                                            {{-- <option value="">--Select--</option> --}}
                                                                @for ($i=1;$i<=date('n');$i++)
                                                                    <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}" @if(date('n')==$i) selected @endif>{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                                                                @endfor
                                                            </select>
                                                            <p style="margin-bottom: 2px;" class="text-danger error-container error-p_month" id="error-p_month"></p> 
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Year : </label>
                                                            <select class="form-control p_year" name="p_year">
                                                            {{--<option value="">--Select--</option>--}}
                                                                @for ($i=0;$i<=$diff;$i++)
                                                                <option value="{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}" @if(date('Y')==date('Y',strtotime('2020-01-01'.' +'.$i.' years'))) selected @endif>{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}</option>
                                                                @endfor
                                                            </select>
                                                            <p style="margin-bottom: 2px;" class="text-danger error-container error-year" id="error-year"></p> 
                                                        </div>
                                                    </div>
                                                </div>`);

                    $('.p_month').selectpicker();
                }
            }
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
                        <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}" @if(date('n')==$i) selected @endif>{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
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
        });

        $(document).on('click','#exportExcel',function(){

          var _this=$(this);

          var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';

          var type = $('.type').val();

          var month = [];

          var year = $('.p_year').val();

          var customer_id = [];

          var report_type = [];

          var i=0;
          $('.customer option:selected').each(function (key,value) {
              customer_id[key] = $(this).val();
          });

          var i=0;
          $('.p_month option:selected').each(function (key,value) {
               month[key] = $(this).val();
          });

          var i=0;
          $('.report_type:checked').each(function(key,value){
            report_type[key] = $(this).val();
          });

          _this.addClass('disabled-link');

          _this.attr('disabled',true);

          if(_this.html()!==loadingText)
          {
              _this.html(loadingText);
          }

          $('#loading').html("");

          $.ajax({
                  type:'POST',
                  url: "{{ url('/') }}"+'/progress-data-export',
                  data: {"_token" : "{{ csrf_token() }}","type":type,"month":month,"year":year,"customer_id":customer_id,"report_type":report_type},        
                  success: function (response) {
                    window.setTimeout(function(){
                        _this.removeClass('disabled-link');
                        _this.attr('disabled',false);
                        _this.html('<i class="fas fa-file-excel"></i> Export Excel');
                    },2000);
                    
                    if(response.success)
                    {
                      window.open(response.url);
                    }
                    else if(response.success==false)
                    {
                        $('#loading').html(response.message);
                    }
                    // else
                    // {
                    //   $('#loading').html(response.error);
                    // }
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
                  }
             });
        });

        $(document).on('click','#sendProgressExport',function(e){

            var _this=$(this);

            swal({
              // icon: "warning",
              type: "warning",
              title: "Are You Sure Want To Send the Progress Export ?",
              text: "",
              dangerMode: true,
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "YES",
              cancelButtonText: "CANCEL",
              closeOnConfirm: false,
              closeOnCancel: false,
              },
              function(e){
                if(e==true)
                { 
                      var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';

                      var type = $('.type').val();

                      var month = [];

                      var year = $('.p_year').val();

                      var customer_id = [];

                      var report_type = [];

                      var i=0;
                      $('.customer option:selected').each(function (key,value) {
                          customer_id[key] = $(this).val();
                      });

                      var i=0;
                      $('.p_month option:selected').each(function (key,value) {
                          month[key] = $(this).val();
                      });

                      var i=0;
                      $('.report_type:checked').each(function(key,value){
                        report_type[key] = $(this).val();
                      });

                      _this.addClass('disabled-link');

                      _this.attr('disabled',true);

                      if(_this.html()!==loadingText)
                      {
                          _this.html(loadingText);
                      }

                      $('#loading').html("");

                      $.ajax({
                            type:'POST',
                            url: "{{ url('/') }}"+'/progress-data-export',
                            data: {"_token" : "{{ csrf_token() }}","type":type,"month":month,"year":year,"customer_id":customer_id,"report_type":report_type},        
                            success: function (response) {
                              window.setTimeout(function(){
                                  _this.removeClass('disabled-link');
                                  _this.attr('disabled',false);
                                  _this.html('<i class="fas fa-paper-plane"></i> Send Progress Export');
                              },2000);
                              
                              if(response.success)
                              {
                                  toastr.success('Progress Export Request Has Been Sent Successfully');
                              }
                              else if(response.success==false)
                              {
                                  // $('#loading').html(response.message);
                                  toastr.success(response.message);
                              }
                              // else
                              // {
                              //   $('#loading').html(response.error);
                              // }
                            },
                            error: function (xhr, textStatus, errorThrown) {
                              // alert("Error: " + errorThrown);
                            }
                      });

                      swal.close();
                }
                else
                {
                    swal.close();
                }
              }
            );

            


        });

        $(document).on('click','#excelPreviewBtn',function(){

          var _this=$(this);

          var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';

          var type = $('.type').val();

          var month = [];

          var year = $('.p_year').val();

          var customer_id = [];

          var report_type = [];

          var i=0;
          $('.customer option:selected').each(function (key,value) {
              customer_id[key] = $(this).val();
          });

          var i=0;
          $('.p_month option:selected').each(function (key,value) {
              month[key] = $(this).val();
          });

          var i=0;
          $('.report_type:checked').each(function(key,value){
            report_type[key] = $(this).val();
          });

          _this.addClass('disabled-link');

          _this.attr('disabled',true);

          if(_this.html()!==loadingText)
          {
              _this.html(loadingText);
          }

          $('#loading').html("");

          $('#sendProgressExport').addClass('d-none');

          $.ajax({
                  type:'POST',
                  url: "{{ url('/') }}"+'/progress/preview',
                  data: {"_token" : "{{ csrf_token() }}","type":type,"month":month,"year":year,"customer_id":customer_id,"report_type":report_type},        
                  success: function (response) {
                    window.setTimeout(function(){
                        _this.removeClass('disabled-link');
                        _this.attr('disabled',false);
                        _this.html('<i class="fas fa-file-excel"></i> Export Preview');
                    },2000);
                    
                    if(response.success)
                    {
                      //window.open(response.url);
                        $('#sendProgressExport').removeClass('d-none');
                        $('.excel-table').html(response.html);
                        $('#excel_preview_mdl').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    }
                    else if(response.success==false)
                    {
                        $('#loading').html(response.message);
                    }
                    // else
                    // {
                    //   $('#loading').html(response.error);
                    // }
                  },
                  error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                  }
            });
        });
     
  });
  function totalHoursCard()
  {
    var _token = "{{ csrf_token() }}";
    var type  =   $('.type').val();

    var year = $('.p_year').val();

    var month = '';

    var customer_id = '';

    var report_type = '';

    if($('.p_month option:selected').length  > 0)
    {
        var i=0;
        month = [];
        $('#p_month option:selected').each(function(key,value){
            month[key] = $(this).val();
            i++;
        });
    }

    if($('.customer option:selected').length > 0)
    {
        var i=0;
        customer_id = [];
        $('#customer option:selected').each(function(key,value){
            customer_id[key] = $(this).val();
            i++;
        });
    }

    if($('.report_type option:selected').length > 0)
    {
        var i=0;
        report_type = [];
        $('.report_type option:selected').each(function(key,value){
            report_type[key] = $(this).val();
            i++;
        });
    } 
    $.ajax({
            url: "{{ route('/progress-dashboard/total-hours-card') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token,'customer_id':customer_id,'type':type,'month':month,'year':year},
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.total_hours').html(cardLoaderHtml()).fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.total_hours').html(data.html);
                var user_name = JSON.parse('{!! json_encode($user_name_arr) !!}');
                
                var total_hrs = JSON.parse($('.total_hrs').val());

                var main_height = 'auto';

                if(user_name.length<=1)
                {
                    main_height = 150;
                }
                else if(user_name.length>=1 && user_name.length<=6)
                {
                    main_height = 300;
                }
                else if(user_name.length>6 && user_name.length<=10)
                {
                    main_height = 400;
                } 
                else if(user_name.length>10 && user_name.length<=20)
                {
                    main_height = 600;
                }
                else if(user_name.length>20 && user_name.length<=30)
                {
                    main_height = 800;
                }
                else if(user_name.length>30 && user_name.length<=40)
                {
                    main_height = 1000;
                }
                else if(user_name.length>40 && user_name.length<=50)
                {
                    main_height = 1200;
                }
                else if(user_name.length>50 && user_name.length<=60)
                {
                    main_height = 1300;
                }
                else if(user_name.length>60 && user_name.length<=70)
                {
                    main_height = 1400;
                }
                else if(user_name.length>70 && user_name.length<=100)
                {
                    main_height = 1800;
                }

                // Employee Login Chart

                var options = {
                legend:{
                      show: true,
                      markers:{
                        fillColors: ['#003473']
                      }
                    },
                    series: [{
                    name: "Total Login Hrs",
                    data: total_hrs
                    }],
                    chart: {
                    type: 'bar',
                    height: main_height,
                    toolbar: {
                      show: true,
                      tools:{
                        download:false // <== line to add
                      }
                    }
                  },
                  plotOptions: {
                    bar: {
                      horizontal: true,
                      dataLabels: {
                        position: 'top',
                      },
                      
                    }
                  },
                  dataLabels: {
                    enabled: false,
                    offsetX: -6,
                    style: {
                      fontSize: '12px',
                      colors: ['#fff']
                    }
                  },
                  stroke: {
                    show: true,
                    width: 1,
                    colors: ['#fff']
                  },
                  tooltip: {
                    shared: false,
                    intersect: false,
                    marker: {
                      show: true,
                      fillColors: ['#003473']
                    },
                  },
                  xaxis: {
                    categories: user_name,
                    tickAmount: 10,
                  },
                  yaxis:{
                      min: 0,
                      max: 100,
                  },
                  fill: {
                    colors: ['#003473']
                  }
                };
                var chart = new ApexCharts(document.querySelector("#emp_login_chart"), options);
                chart.render();
                // var from_date = $('.duration_from').val();

                // var to_date = $('.duration_to').val();

                // // var duration_type = $('.duration_type').val();

                // // $('.duration_type_lbl').html(ucwords(duration_type));

                // $('.duration_lbl').html(from_date+' - '+to_date);

            }
        });
  }
  function jafTaskCard()
  {
    var _token = "{{ csrf_token() }}";
    var type  =   $('.type').val();

    var year = $('.p_year').val();

    var month = '';

    var customer_id = '';

    var report_type = '';

    if($('.p_month option:selected').length  > 0)
    {
        var i=0;
        month = [];
        $('#p_month option:selected').each(function(key,value){
            month[key] = $(this).val();
            i++;
        });
    }

    if($('.customer option:selected').length > 0)
    {
        var i=0;
        customer_id = [];
        $('#customer option:selected').each(function(key,value){
            customer_id[key] = $(this).val();
            i++;
        });
    }

    if($('.report_type option:selected').length > 0)
    {
        var i=0;
        report_type = [];
        $('.report_type option:selected').each(function(key,value){
            report_type[key] = $(this).val();
            i++;
        });
    } 
    $.ajax({
            url: "{{ route('/progress-dashboard/jaf-task-card') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token,'customer_id':customer_id,'type':type,'month':month,'year':year},
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.jaf_task').html(cardLoaderHtml()).fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.jaf_task').html(data.html);
                var user_name = JSON.parse('{!! json_encode($user_name_arr) !!}');
                
                // var total_hrs = JSON.parse($('.total_hrs').val());

                var main_height = 'auto';

                if(user_name.length<=1)
                {
                    main_height = 150;
                }
                else if(user_name.length>=1 && user_name.length<=6)
                {
                    main_height = 300;
                }
                else if(user_name.length>6 && user_name.length<=10)
                {
                    main_height = 400;
                } 
                else if(user_name.length>10 && user_name.length<=20)
                {
                    main_height = 600;
                }
                else if(user_name.length>20 && user_name.length<=30)
                {
                    main_height = 800;
                }
                else if(user_name.length>30 && user_name.length<=40)
                {
                    main_height = 1000;
                }
                else if(user_name.length>40 && user_name.length<=50)
                {
                    main_height = 1200;
                }
                else if(user_name.length>50 && user_name.length<=60)
                {
                    main_height = 1300;
                }
                else if(user_name.length>60 && user_name.length<=70)
                {
                    main_height = 1400;
                }
                else if(user_name.length>70 && user_name.length<=100)
                {
                    main_height = 1800;
                }

                var jaf_assign = JSON.parse($('.jaf_assign').val());

                var jaf_completed = JSON.parse($('.jaf_completed').val());

                var options = {
                legend:{
                      show: true,
                      markers:{
                        fillColors: ['#003473', '#92A5DB']
                      }
                    },
                    series: [{
                    name: "Assigned Task",
                    data: jaf_assign
                  }, {
                    name: "Completed Task",
                    data: jaf_completed
                  }],
                    chart: {
                    type: 'bar',
                    height: main_height,
                    toolbar: {
                      show: true,
                      tools:{
                        download:false // <== line to add
                      }
                    }
                  },
                  plotOptions: {
                    bar: {
                      horizontal: true,
                      dataLabels: {
                        position: 'top',
                      },
                      
                    }
                  },
                  dataLabels: {
                    enabled: false,
                    offsetX: -6,
                    style: {
                      fontSize: '12px',
                      colors: ['#fff']
                    }
                  },
                  stroke: {
                    show: true,
                    width: 1,
                    colors: ['#fff']
                  },
                  tooltip: {
                    shared: true,
                    intersect: false,
                    marker: {
                      show: true,
                      fillColors: ['#003473', '#92A5DB']
                    },
                  },
                  xaxis: {
                    categories: user_name,
                    tickAmount: 10,
                  },
                  yaxis:{
                      min: 0,
                      max: 100,
                  },
                  fill: {
                    colors: ['#003473', '#92A5DB']
                  }
                };
                var chart = new ApexCharts(document.querySelector("#jaf_task_chart"), options);
                chart.render();
                // var from_date = $('.duration_from').val();

                // var to_date = $('.duration_to').val();

                // var duration_type = $('.duration_type').val();

                // $('.duration_type_lbl').html(ucwords(duration_type));

                // $('.duration_lbl').html(from_date+' - '+to_date);
            }
        });
  }
  function verificationTaskCard()
  {
    var _token = "{{ csrf_token() }}";
    var type  =   $('.type').val();

    var year = $('.p_year').val();

    var month = '';

    var customer_id = '';

    var report_type = '';

    if($('.p_month option:selected').length  > 0)
    {
        var i=0;
        month = [];
        $('#p_month option:selected').each(function(key,value){
            month[key] = $(this).val();
            i++;
        });
    }

    if($('.customer option:selected').length > 0)
    {
        var i=0;
        customer_id = [];
        $('#customer option:selected').each(function(key,value){
            customer_id[key] = $(this).val();
            i++;
        });
    }

    if($('.report_type option:selected').length > 0)
    {
        var i=0;
        report_type = [];
        $('.report_type option:selected').each(function(key,value){
            report_type[key] = $(this).val();
            i++;
        });
    } 
    $.ajax({
            url: "{{ route('/progress-dashboard/verification-task-card') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token,'customer_id':customer_id,'type':type,'month':month,'year':year},
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.task_verification').html(cardLoaderHtml()).fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.task_verification').html(data.html);
                var user_name = JSON.parse('{!! json_encode($user_name_arr) !!}');
                
                // var total_hrs = JSON.parse($('.total_hrs').val());

                var main_height = 'auto';

                if(user_name.length<=1)
                {
                    main_height = 150;
                }
                else if(user_name.length>=1 && user_name.length<=6)
                {
                    main_height = 300;
                }
                else if(user_name.length>6 && user_name.length<=10)
                {
                    main_height = 400;
                } 
                else if(user_name.length>10 && user_name.length<=20)
                {
                    main_height = 600;
                }
                else if(user_name.length>20 && user_name.length<=30)
                {
                    main_height = 800;
                }
                else if(user_name.length>30 && user_name.length<=40)
                {
                    main_height = 1000;
                }
                else if(user_name.length>40 && user_name.length<=50)
                {
                    main_height = 1200;
                }
                else if(user_name.length>50 && user_name.length<=60)
                {
                    main_height = 1300;
                }
                else if(user_name.length>60 && user_name.length<=70)
                {
                    main_height = 1400;
                }
                else if(user_name.length>70 && user_name.length<=100)
                {
                    main_height = 1800;
                }

                var ver_assign = JSON.parse($('.ver_assign').val());

                var ver_completed = JSON.parse($('.ver_completed').val());

                var options = {
                legend:{
                      show: true,
                      markers:{
                        fillColors: ['#003473', '#92A5DB']
                      }
                    },
                    series: [{
                    name: "Assigned Task",
                    data: ver_assign
                  }, {
                    name: "Completed Task",
                    data: ver_completed
                  }],
                    chart: {
                    type: 'bar',
                    height: main_height,
                    toolbar: {
                      show: true,
                      tools:{
                        download:false // <== line to add
                      }
                    }
                  },
                  plotOptions: {
                    bar: {
                      horizontal: true,
                      dataLabels: {
                        position: 'top',
                      },
                      
                    }
                  },
                  dataLabels: {
                    enabled: false,
                    offsetX: -6,
                    style: {
                      fontSize: '12px',
                      colors: ['#fff']
                    }
                  },
                  stroke: {
                    show: true,
                    width: 1,
                    colors: ['#fff']
                  },
                  tooltip: {
                    shared: true,
                    intersect: false,
                    marker: {
                      show: true,
                      fillColors: ['#003473', '#92A5DB']
                    },
                  },
                  xaxis: {
                    categories: user_name,
                    tickAmount: 10,
                  },
                  yaxis:{
                      min: 0,
                      max: 100,
                  },
                  fill: {
                    colors: ['#003473', '#92A5DB']
                  }
                };
                var chart = new ApexCharts(document.querySelector("#verification_task_chart"), options);
                chart.render();
                // var from_date = $('.duration_from').val();

                // var to_date = $('.duration_to').val();

                // // var duration_type = $('.duration_type').val();

                // // $('.duration_type_lbl').html(ucwords(duration_type));

                // $('.duration_lbl').html(from_date+' - '+to_date);

            }
        });
  }
  function insuffCard()
  {
    var _token = "{{ csrf_token() }}";
    var type  =   $('.type').val();

    var year = $('.p_year').val();

    var month = '';

    var customer_id = '';

    var report_type = '';

    if($('.p_month option:selected').length  > 0)
    {
        var i=0;
        month = [];
        $('#p_month option:selected').each(function(key,value){
            month[key] = $(this).val();
            i++;
        });
    }

    if($('.customer option:selected').length > 0)
    {
        var i=0;
        customer_id = [];
        $('#customer option:selected').each(function(key,value){
            customer_id[key] = $(this).val();
            i++;
        });
    }

    if($('.report_type option:selected').length > 0)
    {
        var i=0;
        report_type = [];
        $('.report_type option:selected').each(function(key,value){
            report_type[key] = $(this).val();
            i++;
        });
    } 
    $.ajax({
            url: "{{ route('/progress-dashboard/insuff-card') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token,'customer_id':customer_id,'type':type,'month':month,'year':year},
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.insuff_card').html(cardLoaderHtml()).fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.insuff_card').html(data.html);
                var user_name = JSON.parse('{!! json_encode($user_name_arr) !!}');
               
                // var total_hrs = JSON.parse($('.total_hrs').val());

                var main_height = 'auto';

                if(user_name.length<=1)
                {
                    main_height = 150;
                }
                else if(user_name.length>=1 && user_name.length<=6)
                {
                    main_height = 300;
                }
                else if(user_name.length>6 && user_name.length<=10)
                {
                    main_height = 400;
                } 
                else if(user_name.length>10 && user_name.length<=20)
                {
                    main_height = 600;
                }
                else if(user_name.length>20 && user_name.length<=30)
                {
                    main_height = 800;
                }
                else if(user_name.length>30 && user_name.length<=40)
                {
                    main_height = 1000;
                }
                else if(user_name.length>40 && user_name.length<=50)
                {
                    main_height = 1200;
                }
                else if(user_name.length>50 && user_name.length<=60)
                {
                    main_height = 1300;
                }
                else if(user_name.length>60 && user_name.length<=70)
                {
                    main_height = 1400;
                }
                else if(user_name.length>70 && user_name.length<=100)
                {
                    main_height = 1800;
                }

                var raise_insuff = JSON.parse($('.raise_insuff').val());

                var clear_insuff = JSON.parse($('.clear_insuff').val());

                // Insufficiency Chart
                var options = {
                legend:{
                      show: true,
                      markers:{
                        fillColors: ['#E6513A', '#4D9510']
                      }
                    },
                    series: [{
                    name: "Raised Insufficiency",
                    data: raise_insuff
                  }, {
                    name: "Cleared Insufficiency",
                    data: clear_insuff
                  }],
                    chart: {
                    type: 'bar',
                    height: main_height,
                    toolbar: {
                      show: true,
                      tools:{
                        download:false // <== line to add
                      }
                    }
                  },
                  plotOptions: {
                    bar: {
                      horizontal: true,
                      dataLabels: {
                        position: 'top',
                      },
                      
                    }
                  },
                  dataLabels: {
                    enabled: false,
                    offsetX: -6,
                    style: {
                      fontSize: '12px',
                      colors: ['#fff']
                    }
                  },
                  stroke: {
                    show: true,
                    width: 1,
                    colors: ['#fff']
                  },
                  tooltip: {
                    shared: true,
                    intersect: false,
                    marker: {
                      show: true,
                      fillColors: ['#E6513A', '#4D9510']
                    },
                  },
                  xaxis: {
                    categories: user_name,
                    tickAmount: 10,
                  },
                  yaxis:{
                      min: 0,
                      max: 100,
                  },
                  fill: {
                    colors: ['#E6513A', '#4D9510']
                  }
                };
                var chart = new ApexCharts(document.querySelector("#insuff_chart"), options);
                chart.render();

            }
        });
  }
  
  function getData(page){

        //set data
        var type  =   $('.type').val();

        var year = $('.p_year').val();

        var month = '';

        var customer_id = '';

        var report_type = '';

        if($('.p_month option:selected').length  > 0)
        {
            var i=0;
            month = [];
            $('#p_month option:selected').each(function(key,value){
                month[key] = $(this).val();
                i++;
            });
        }

        if($('.customer option:selected').length > 0)
        {
            var i=0;
            customer_id = [];
            $('#customer option:selected').each(function(key,value){
                customer_id[key] = $(this).val();
                i++;
            });
        }

        if($('.report_type option:selected').length > 0)
        {
            var i=0;
            report_type = [];
            $('.report_type option:selected').each(function(key,value){
                report_type[key] = $(this).val();
                i++;
            });
        }
        // var candidate_arr = [];
        // var i = 0;
        

        // $('.check option:selected').each(function () {
        //     // if($(this).val()!='')
        //     candidate_arr[i++] = $(this).val();
        // });    

            $('#graphResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

            $.ajax(
            {
                url: '?page=' + page+'&customer_id='+customer_id+'&type='+type+'&month='+month+'&year='+year,
                type: "get",
                datatype: "html",
            })
            .done(function(data)
            {
                $("#graphResult").empty().html(data);
                $("#overlay").fadeOut(300);
                //debug to check page number
                location.hash = page;

                // var user_name = JSON.parse('{!! json_encode($user_name_arr) !!}');
               
                // var total_hrs = JSON.parse($('.total_hrs').val());

                // var main_height = 'auto';

                // if(user_name.length<=1)
                // {
                //     main_height = 150;
                // }
                // else if(user_name.length>=1 && user_name.length<=6)
                // {
                //     main_height = 300;
                // }
                // else if(user_name.length>6 && user_name.length<=10)
                // {
                //     main_height = 400;
                // } 
                // else if(user_name.length>10 && user_name.length<=20)
                // {
                //     main_height = 600;
                // }
                // else if(user_name.length>20 && user_name.length<=30)
                // {
                //     main_height = 800;
                // }
                // else if(user_name.length>30 && user_name.length<=40)
                // {
                //     main_height = 1000;
                // }
                // else if(user_name.length>40 && user_name.length<=50)
                // {
                //     main_height = 1200;
                // }
                // else if(user_name.length>50 && user_name.length<=60)
                // {
                //     main_height = 1300;
                // }
                // else if(user_name.length>60 && user_name.length<=70)
                // {
                //     main_height = 1400;
                // }
                // else if(user_name.length>70 && user_name.length<=100)
                // {
                //     main_height = 1800;
                // }

                // // Employee Login Chart

                // var options = {
                // legend:{
                //       show: true,
                //       markers:{
                //         fillColors: ['#003473']
                //       }
                //     },
                //     series: [{
                //     name: "Total Login Hrs",
                //     data: total_hrs
                //     }],
                //     chart: {
                //     type: 'bar',
                //     height: main_height,
                //     toolbar: {
                //       show: true,
                //       tools:{
                //         download:false // <== line to add
                //       }
                //     }
                //   },
                //   plotOptions: {
                //     bar: {
                //       horizontal: true,
                //       dataLabels: {
                //         position: 'top',
                //       },
                      
                //     }
                //   },
                //   dataLabels: {
                //     enabled: false,
                //     offsetX: -6,
                //     style: {
                //       fontSize: '12px',
                //       colors: ['#fff']
                //     }
                //   },
                //   stroke: {
                //     show: true,
                //     width: 1,
                //     colors: ['#fff']
                //   },
                //   tooltip: {
                //     shared: false,
                //     intersect: false,
                //     marker: {
                //       show: true,
                //       fillColors: ['#003473']
                //     },
                //   },
                //   xaxis: {
                //     categories: user_name,
                //     tickAmount: 10,
                //   },
                //   yaxis:{
                //       min: 0,
                //       max: 100,
                //   },
                //   fill: {
                //     colors: ['#003473']
                //   }
                // };
                // var chart = new ApexCharts(document.querySelector("#emp_login_chart"), options);
                // chart.render();

                // // JAF Filling Chart

                // var jaf_assign = JSON.parse($('.jaf_assign').val());

                // var jaf_completed = JSON.parse($('.jaf_completed').val());

                // var options = {
                // legend:{
                //       show: true,
                //       markers:{
                //         fillColors: ['#003473', '#92A5DB']
                //       }
                //     },
                //     series: [{
                //     name: "Assigned Task",
                //     data: jaf_assign
                //   }, {
                //     name: "Completed Task",
                //     data: jaf_completed
                //   }],
                //     chart: {
                //     type: 'bar',
                //     height: main_height,
                //     toolbar: {
                //       show: true,
                //       tools:{
                //         download:false // <== line to add
                //       }
                //     }
                //   },
                //   plotOptions: {
                //     bar: {
                //       horizontal: true,
                //       dataLabels: {
                //         position: 'top',
                //       },
                      
                //     }
                //   },
                //   dataLabels: {
                //     enabled: false,
                //     offsetX: -6,
                //     style: {
                //       fontSize: '12px',
                //       colors: ['#fff']
                //     }
                //   },
                //   stroke: {
                //     show: true,
                //     width: 1,
                //     colors: ['#fff']
                //   },
                //   tooltip: {
                //     shared: true,
                //     intersect: false,
                //     marker: {
                //       show: true,
                //       fillColors: ['#003473', '#92A5DB']
                //     },
                //   },
                //   xaxis: {
                //     categories: user_name,
                //     tickAmount: 10,
                //   },
                //   yaxis:{
                //       min: 0,
                //       max: 100,
                //   },
                //   fill: {
                //     colors: ['#003473', '#92A5DB']
                //   }
                // };
                // var chart = new ApexCharts(document.querySelector("#jaf_task_chart"), options);
                // chart.render();

                // // Verification Task

                // var ver_assign = JSON.parse($('.ver_assign').val());

                // var ver_completed = JSON.parse($('.ver_completed').val());

                // var options = {
                // legend:{
                //       show: true,
                //       markers:{
                //         fillColors: ['#003473', '#92A5DB']
                //       }
                //     },
                //     series: [{
                //     name: "Assigned Task",
                //     data: ver_assign
                //   }, {
                //     name: "Completed Task",
                //     data: ver_completed
                //   }],
                //     chart: {
                //     type: 'bar',
                //     height: main_height,
                //     toolbar: {
                //       show: true,
                //       tools:{
                //         download:false // <== line to add
                //       }
                //     }
                //   },
                //   plotOptions: {
                //     bar: {
                //       horizontal: true,
                //       dataLabels: {
                //         position: 'top',
                //       },
                      
                //     }
                //   },
                //   dataLabels: {
                //     enabled: false,
                //     offsetX: -6,
                //     style: {
                //       fontSize: '12px',
                //       colors: ['#fff']
                //     }
                //   },
                //   stroke: {
                //     show: true,
                //     width: 1,
                //     colors: ['#fff']
                //   },
                //   tooltip: {
                //     shared: true,
                //     intersect: false,
                //     marker: {
                //       show: true,
                //       fillColors: ['#003473', '#92A5DB']
                //     },
                //   },
                //   xaxis: {
                //     categories: user_name,
                //     tickAmount: 10,
                //   },
                //   yaxis:{
                //       min: 0,
                //       max: 100,
                //   },
                //   fill: {
                //     colors: ['#003473', '#92A5DB']
                //   }
                // };
                // var chart = new ApexCharts(document.querySelector("#verification_task_chart"), options);
                // chart.render();

                // var raise_insuff = JSON.parse($('.raise_insuff').val());

                // var clear_insuff = JSON.parse($('.clear_insuff').val());

                // // Insufficiency Chart
                // var options = {
                // legend:{
                //       show: true,
                //       markers:{
                //         fillColors: ['#E6513A', '#4D9510']
                //       }
                //     },
                //     series: [{
                //     name: "Raised Insufficiency",
                //     data: raise_insuff
                //   }, {
                //     name: "Cleared Insufficiency",
                //     data: clear_insuff
                //   }],
                //     chart: {
                //     type: 'bar',
                //     height: main_height,
                //     toolbar: {
                //       show: true,
                //       tools:{
                //         download:false // <== line to add
                //       }
                //     }
                //   },
                //   plotOptions: {
                //     bar: {
                //       horizontal: true,
                //       dataLabels: {
                //         position: 'top',
                //       },
                      
                //     }
                //   },
                //   dataLabels: {
                //     enabled: false,
                //     offsetX: -6,
                //     style: {
                //       fontSize: '12px',
                //       colors: ['#fff']
                //     }
                //   },
                //   stroke: {
                //     show: true,
                //     width: 1,
                //     colors: ['#fff']
                //   },
                //   tooltip: {
                //     shared: true,
                //     intersect: false,
                //     marker: {
                //       show: true,
                //       fillColors: ['#E6513A', '#4D9510']
                //     },
                //   },
                //   xaxis: {
                //     categories: user_name,
                //     tickAmount: 10,
                //   },
                //   yaxis:{
                //       min: 0,
                //       max: 100,
                //   },
                //   fill: {
                //     colors: ['#E6513A', '#4D9510']
                //   }
                // };
                // var chart = new ApexCharts(document.querySelector("#insuff_chart"), options);
                // chart.render();

                var from_date = $('.duration_from').val();

                var to_date = $('.duration_to').val();

                // var duration_type = $('.duration_type').val();

                // $('.duration_type_lbl').html(ucwords(duration_type));

                $('.duration_lbl').html(from_date+' - '+to_date);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                alert('No response from server');

            });

  }
  function cardLoaderHtml()
  {
    return "<div class='fa-3x' style='min-height:200px;display: flex;align-items: center;justify-content: center;'><i class='fas fa-spinner fa-pulse'></i></div>";
  }
    // function setData(){

    //     var user_id     =    $(".customer_list").val();                
    //     var check       =    $(".check option:selected").val();

    //     var from_date   =    $(".from_date").val(); 
    //     var to_date     =    $(".to_date").val();    
    //     var candidate_id=    $(".candidate_list option:selected").val();                            
    //     var rows = $("#rows option:selected").val();
    //     var mob = $('.mob').val();
    //     var ref = $('.ref').val();

    //     var email = $('.email').val(); 

    //     var remain = $('#remain').val();  

    //     var active_case =  $('#active_case').val();   

    //     var insuff_raised = $('#insuff_raised').val();       

    //     var status = 'pending'; 

    //     var insuff_status = '1';

    //     var search = $('.search').val();

    //     var insuffs = $('#insuffs').val();
    //     var service = $('#service').val();

    //     var sendto = $('#sendto').val();
    //     var jafstatus = $('#jafstatus').val();
    //     var insuff = $('#insuff').val();
    //     var verification_status = $('#verification_status').val();
    //     var verify_status = $('#verify_status').val();
    //     var candidates_id = $('#candidates_id').val();

    //     var jafstatus1 = $('#jafstatus1').val();
    //     var jafstatus2 = $('#jafstatus2').val();
    //     // var candidate_arr = [];
    //     // var i = 0;
        

    //     // $('.check option:selected').each(function () {
    //     //     // if($(this).val()!='')
    //     //     candidate_arr[i++] = $(this).val();
    //     // });

    //     // alert(candidate_arr);
        
    //         $.ajax(
    //         {
    //             url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&check_id='+check+'&candidate_id='+candidate_id+'&mob='+mob+'&ref='+ref+'&email='+email+'&remain='+remain+'&status='+status+'&active_case='+active_case+'&insuff_raised='+insuff_raised+'&insuff_status='+insuff_status+'&search='+search+'&insuffs='+insuffs+'&service='+service+'&sendto='+sendto+'&jafstatus='+jafstatus+'&insuff='+insuff+'&verification_status='+verification_status+'&verify_status='+verify_status+'&candidate='+candidates_id+'&jafstatus1='+jafstatus1+'&jafstatus2='+jafstatus2+'&rows='+rows,
    //             type: "get",
    //             datatype: "html",
    //         })
    //         .done(function(data)
    //         {
    //         console.log(data);
    //         })
    //         .fail(function(jqXHR, ajaxOptions, thrownError)
    //         {
    //             //alert('No response from server');
    //         });

    // }

    function ucwords (str) {
        return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
            return $1.toUpperCase();
        });
    }


</script>

@endsection
