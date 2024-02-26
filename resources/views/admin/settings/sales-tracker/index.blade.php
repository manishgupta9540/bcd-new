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
.excel-table-data
{
  max-height: 300px;
  overflow-y: auto;
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
          <li>Sales Tracker</li>
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
                <h3>Sales Tracker </h3>
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
                <p class="pb-border"></p>Weekly
              </div> --}}
              <div class="col-4">
                <div class="form-group">
                  <label>Duration Type : </label>
                  <select class="form-control type" name="type">
                    <option value="weekly" @if(stripos($type,'weekly')!==false) selected @endif>Weekly</option>
                    <option value="monthly" @if(stripos($type,'monthly')!==false) selected @endif>Monthly</option>
                    <option value="quaterly" @if(stripos($type,'quaterly')!==false) selected @endif>Quaterly</option>
                    <option value="yearly" @if(stripos($type,'yearly')!==false) selected @endif>Yearly</option>
                  </select> 
                </div>
                <div class="type_result">

                </div>
              </div>
              <div class="col-4">
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
              <div class="col-2 pt-4">
                <button type="button" class="btn btn-info float-right filterBtn"><i class="fas fa-filter"></i> Apply Filter</button>
              </div>
              <div class="col-2 pt-4">
                <button type="button" class="btn btn-dark" id="exportExcel"><i class="fas fa-file-excel"></i> Export Preview</button>
              </div>
            </div>
            <div id="graphResult">
              @include('admin.settings.sales-tracker.ajax')
            </div>
        </div>
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

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
  $(document).ready(function(){

    $('.customer').selectpicker({
        'liveSearch' : true,
        'liveSearchNormalize' : true,
        'liveSearchPlaceholder' : 'Select the Customer'
      });

      // $('#excel_preview').modal({
      //                   backdrop: 'static',
      //                   keyboard: false
      //                });

        var uriNum = location.hash;
        pageNumber = uriNum.replace("#", "");
        // alert(pageNumber);
        getData(pageNumber);

        $(document).on('click','.filterBtn', function (e){    
            $("#overlay").fadeIn(300);　
            getData(0);
            e.preventDefault();
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
                                                <label>Month :</label>
                                                <select class="form-control month" name="month">
                                                    @for ($i=1;$i<=date('n');$i++)
                                                      <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                                                    @endfor
                                                </select>
                                              </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                              @php
                                                $start_year = 2020;
                                                $end_year = date('Y');

                                                $diff = abs($end_year - $start_year);
                                              @endphp
                                              <label>Year : </label>
                                              <select class="form-control year" name="year">
                                                  @for ($i=0;$i<=$diff;$i++)
                                                    <option value="{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}" @if(date('Y')==date('Y',strtotime('2020-01-01'.' +'.$i.' years'))) selected @endif>{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}</option>
                                                  @endfor
                                              </select>
                                            </div>
                                            </div>
                                            </div>`);
              }
              else if(type.toLowerCase()=='quaterly'.toLowerCase())
              {
                $('.type_result').html(`<div class="row">
                                        <div class="col-sm-6">
                                        <div class="form-group">
                                          <label>Quater : </label>
                                          <select class="form-control quater" name="quater">
                                             <option value="q1">April - June</option>
                                             <option value="q2">July - September</option>
                                             <option value="q3">October - December</option>
                                             <option value="q4">January - March</option>
                                          </select>
                                        </div>
                                        </div>
                                        <div class="col-sm-6">
                                        <div class="form-group">
                                          @php
                                            $start_year = 2020;
                                            $end_year = date('Y');

                                            $diff = abs($end_year - $start_year);
                                          @endphp
                                          <label>Year : </label>
                                          <select class="form-control year" name="year">
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
                                          <label>Year : </label>
                                          <select class="form-control year" name="year">
                                              @for ($i=0;$i<=$diff;$i++)
                                                <option value="{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}" @if(date('Y')==date('Y',strtotime('2020-01-01'.' +'.$i.' years'))) selected @endif>{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}</option>
                                              @endfor
                                          </select>
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
                  $('.month').html(`
                      @for ($i=1;$i<=date('n');$i++)
                        <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                      @endfor`);
              }
              else
              {
                  $('.month').html(`
                      @for ($i=1;$i<=12;$i++)
                        <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                      @endfor
                  `);
              }
          }
        });

        $(document).on('click','#exportExcel',function(){

          var _this=$(this);

          var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';

          var type = $('.type').val();

          var month = $('.month').val();

          var quater = $('.quater').val();

          var year = $('.year').val();

          var customer_id = [];

          var i=0;
          $('.customer option:selected').each(function (key,value) {
              customer_id[key] = $(this).val();
          });

          _this.addClass('disabled-link');

          _this.attr('disabled',true);

          if(_this.html()!==loadingText)
          {
              _this.html(loadingText);
          }

          $.ajax({
                  type:'POST',
                  url: "{{ url('/') }}"+'/sales-export',
                  data: {"_token" : "{{ csrf_token() }}","type":type,"month":month,"quater":quater,"year":year,'customer_id':customer_id},        
                  success: function (response) {
                    window.setTimeout(function(){
                        _this.removeClass('disabled-link');
                        _this.attr('disabled',false);
                        _this.html('<i class="fas fa-file-excel"></i> Export Excel');
                    },2000);
                    
                    if(response.success)
                    {
                      //window.open(response.url);

                      $('.excel-table').html(response.html);
                      $('#excel_link').attr('href',response.url);
                      $('#excel_preview').modal({
                          backdrop: 'static',
                          keyboard: false
                      });
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

  function getData(page){

        //set data
        var type  =   $('.type').val();

        var month = $('.month').val()!=undefined ? $('.month').val() : '';

        var quater = $('.quater').val()!=undefined ? $('.quater').val() : '';

        var year = $('.year').val()!=undefined ? $('.year').val() : '';

        var customer_id = '';

        if($('.customer option:selected').length > 0)
        {
            var i=0;
            customer_id = [];
            $('#customer option:selected').each(function(key,value){
                customer_id[key] = $(this).val();
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
                url: '?page=' + page+'&customer_id='+customer_id+'&type='+type+'&month='+month+'&quater='+quater+'&year='+year,
                type: "get",
                datatype: "html",
            })
            .done(function(data)
            {
                $("#graphResult").empty().html(data);
                $("#overlay").fadeOut(300);
                //debug to check page number
                location.hash = page;

                // var company_name = JSON.parse('{!! json_encode($company_name) !!}');
                // var avg_case_weekly = JSON.parse('{!! json_encode($avg_week_case) !!}');
                // var avg_case_monthly = JSON.parse('{!! json_encode($avg_monthly_case) !!}');

                var company_name = JSON.parse($('.company_name').val());
                var avg_case_weekly = JSON.parse($('.avg_week_case').val());
                var avg_case_monthly = JSON.parse($('.avg_monthly_case').val());

                var main_height = 'auto';

                if(company_name.length<=1)
                {
                    main_height = 150;
                }
                else if(company_name.length>=1 && company_name.length<=6)
                {
                    main_height = 300;
                }
                else if(company_name.length>6 && company_name.length<=10)
                {
                    main_height = 400;
                }
                else if(company_name.length>10 && company_name.length<=20)
                {
                    main_height = 600;
                }
                else if(company_name.length>20 && company_name.length<=30)
                {
                    main_height = 800;
                }
                else if(company_name.length>30 && company_name.length<=40)
                {
                    main_height = 1000;
                }
                else if(company_name.length>40 && company_name.length<=50)
                {
                    main_height = 1200;
                }
                else if(company_name.length>50 && company_name.length<=60)
                {
                    main_height = 1300;
                }
                else if(company_name.length>60 && company_name.length<=70)
                {
                    main_height = 1400;
                }
                else if(company_name.length>70 && company_name.length<=100)
                {
                    main_height = 1800;
                }

                var options = {
                legend:{
                      show: true,
                      markers:{
                        fillColors: ['#003473', '#92A5DB']
                      }
                    },
                    series: [{
                    name: "Average No. of Case Weekly",
                    data: avg_case_weekly
                  }, {
                    name: "Average No. of Case Monthly",
                    data: avg_case_monthly
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
                    categories: company_name,
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
                var chart = new ApexCharts(document.querySelector("#avg_size_chart"), options);
                chart.render();

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
