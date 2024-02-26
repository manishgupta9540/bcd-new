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
.card-icon-bg .fas{
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
                    $TOTAL_ALLOTTED_CASE  = false;
                    $TOTAL_ALLOTTED_CASE  = Helper::can_access('Total Allotted Case - Normal User','');

                    $TOTAL_CASE_DONE     = false;
                    $TOTAL_CASE_DONE     = Helper::can_access('Total Case Done - Normal User','');

                    $TOTAL_PENDING_CASE  = false;
                    $TOTAL_PENDING_CASE  = Helper::can_access('Total Pending Case - Normal User','');

                    $INSUFF_RAISED_CASE  = false;
                    $INSUFF_RAISED_CASE  = Helper::can_access('Insuff Raised Case - Normal User','');
                @endphp
                <div class="row">
                    <div class="col-6">
                        <h3 class="mr-2"> Dashboard </h3>
                    </div>
                    <div class="col-2 form-group mb-1">
                        <label> From date </label>
                        <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
                    </div>
                    <div class="col-2 form-group mb-1">
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
                
                <div class="row">
                    @if($TOTAL_ALLOTTED_CASE)
                        <div class="col-lg-3 col-sm-6 allottedTask">
                            
                        </div>
                    @endif
                    @if($TOTAL_CASE_DONE)
                        <div class="col-lg-3 col-sm-6 completedTask">
                            
                        </div>
                    @endif
                    @if($TOTAL_PENDING_CASE)
                        <div class="col-lg-3 col-sm-6 pendingTask">
                            
                        </div>
                    @endif
                    @if($INSUFF_RAISED_CASE)
                        <div class="col-lg-3 col-sm-6 insuffTask">
                            
                        </div>
                    @endif
                </div>
                
                @php
                    $jaf_action_data = Helper::role_permission_action(Auth::user()->id,'','Team Lead JAF Filling Dashboard');
                    $ver_action_data = Helper::role_permission_action(Auth::user()->id,'','Team Lead Task for Verification Dashboard');
                    $report_action_data = Helper::role_permission_action(Auth::user()->id,'','Team Lead Report Writing Dashboard');
                @endphp

                @if($jaf_action_data!=NULL || $ver_action_data!=NULL || $report_action_data!=NULL)
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
                @endif
            </div>
        </div>
        

       
 
  </div>
</div>

<script>
  $(document).ready(function(){
    var teamleadjafresult=@php 
                            $a=Helper::role_permission_action(Auth::user()->id,'','Team Lead JAF Filling Dashboard'); 
                            if($a!=NULL)
                            {
                                echo json_encode(true);
                            }
                            else
                            {
                                echo json_encode(false);
                            }
                        @endphp;


    var teamleadverresult=@php 
                            $a=Helper::role_permission_action(Auth::user()->id,'','Team Lead Task for Verification Dashboard'); 
                            if($a!=NULL)
                            {
                                echo json_encode(true);
                            }
                            else
                            {
                                echo json_encode(false);
                            }
                        @endphp;

    var teamleadreportresult=@php 
                            $a=Helper::role_permission_action(Auth::user()->id,'','Team Lead Report Writing Dashboard'); 
                            if($a!=NULL)
                            {
                                echo json_encode(true);
                            }
                            else
                            {
                                echo json_encode(false);
                            }
                        @endphp;
                        
    setTimeout(()=>{
      allottedCardAjax();
      completedCardAjax();
      pendingCardAjax();
      insuffCardAjax();
    //   if(teamleadjafresult==true || teamleadverresult==true || teamleadreportresult==true)
    //   {
    //     teamLeadAjax(teamleadjafresult,teamleadverresult,teamleadreportresult);
    //   }

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

        allottedCardAjax();
        completedCardAjax();
        pendingCardAjax();
        insuffCardAjax();
        // if(teamleadjafresult==true || teamleadverresult==true || teamleadreportresult==true)
        // {
        //     teamLeadAjax(teamleadjafresult,teamleadverresult,teamleadreportresult);
        // }

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

        allottedCardAjax();
        completedCardAjax();
        pendingCardAjax();
        insuffCardAjax();
        // if(teamleadjafresult==true || teamleadverresult==true || teamleadreportresult==true)
        // {
        //     teamLeadAjax(teamleadjafresult,teamleadverresult,teamleadreportresult);
        // }

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

        allottedCardAjax();
        completedCardAjax();
        pendingCardAjax();
        insuffCardAjax();
        // if(teamleadjafresult==true || teamleadverresult==true || teamleadreportresult==true)
        // {
        //     teamLeadAjax(teamleadjafresult,teamleadverresult,teamleadreportresult);
        // }

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
  });

  function allottedCardAjax()
  {
    var _token = "{{ csrf_token() }}";

    var from_date   =    $(".from_date").val()==undefined?'':$(".from_date").val(); 
    var to_date     =    $(".to_date").val()==undefined?'':$(".to_date").val();   

    $.ajax({
            url: "{{ route('/home/dashboard-allotted-task-card') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token,'from_date':from_date,'to_date':to_date},
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.allottedTask').html(cardLoaderHtml()).fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.allottedTask').html(data.html);

                if(from_date!='')
                {
                    //alert(from_date);
                    var size = $('.card_link').length;

                    if(size > 0){
                        $('.card_link').each(function(i,v){
                            var href = $(this).attr('href');

                            $(this).attr('href',href+'&task_start_date='+from_date);
                        });
                    }
                    
                }

                if(to_date!='')
                {
                    //alert(to_date);
                    var size = $('.card_link').length;

                    if(size > 0){
                        $('.card_link').each(function(i,v){
                            var href = $(this).attr('href');

                            $(this).attr('href',href+'&task_end_date='+to_date);
                        });
                    }
                }
            }
        });
  }

  function completedCardAjax()
  {
    var _token = "{{ csrf_token() }}";
    var from_date   =    $(".from_date").val()==undefined?'':$(".from_date").val(); 
    var to_date     =    $(".to_date").val()==undefined?'':$(".to_date").val();   
    $.ajax({
            url: "{{ route('/home/dashboard-completed-task-card') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token,'from_date':from_date,'to_date':to_date},
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.completedTask').html(cardLoaderHtml()).fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.completedTask').html(data.html);

                if(from_date!='')
                {
                    var size = $('.complete_card_link').length;

                    if(size > 0){
                        $('.complete_card_link').each(function(i,v){
                                //alert(from_date);
                            var href = $(this).attr('href');

                            $(this).attr('href',href+'&task_start_date='+from_date);
                        });
                    }
                    
                }

                if(to_date!='')
                {
                    //alert(to_date);
                    var size = $('.complete_card_link').length;

                    if(size > 0){
                        $('.complete_card_link').each(function(i,v){
                            var href = $(this).attr('href');

                            $(this).attr('href',href+'&task_end_date='+to_date);
                        });
                    }
                }
            }
        });
  }

  function pendingCardAjax()
  {
    var _token = "{{ csrf_token() }}";
    var from_date   =    $(".from_date").val()==undefined?'':$(".from_date").val(); 
    var to_date     =    $(".to_date").val()==undefined?'':$(".to_date").val();   
    $.ajax({
            url: "{{ route('/home/dashboard-pending-task-card') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token,'from_date':from_date,'to_date':to_date},
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.pendingTask').html(cardLoaderHtml()).fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.pendingTask').html(data.html);

                if(from_date!='')
                {
                    //alert(from_date);
                    var size = $('.pending_card_link').length;

                    if(size > 0){
                        $('.pending_card_link').each(function(i,v){

                            var href = $(this).attr('href');

                            $(this).attr('href',href+'&task_start_date='+from_date);
                        });
                        
                    }
                    
                }

                if(to_date!='')
                {
                    //alert(to_date);
                    var size = $('.pending_card_link').length;

                    if(size > 0){
                        $('.pending_card_link').each(function(i,v){

                            var href = $(this).attr('href');

                            $(this).attr('href',href+'&task_end_date='+to_date);
                        });
                    }
                }
            }
        });
  }

  function insuffCardAjax()
  {
    var _token = "{{ csrf_token() }}";
    var from_date   =    $(".from_date").val()==undefined?'':$(".from_date").val(); 
    var to_date     =    $(".to_date").val()==undefined?'':$(".to_date").val();   
    $.ajax({
            url: "{{ route('/home/dashboard-insuff-task-card') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token,'from_date':from_date,'to_date':to_date},
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.insuffTask').html(cardLoaderHtml()).fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.insuffTask').html(data.html);

                if(from_date!='')
                {
                    var size= $('.insuff_card_link').length;

                    if(size > 0)
                    {
                        //alert(from_date);
                        $('.insuff_card_link').each(function(i,v){
                            var href = $(this).attr('href');

                            $(this).attr('href',href+'&task_start_date='+from_date);
                        });
                    }

                }

                if(to_date!='')
                {
                    //alert(to_date);
                    var size= $('.insuff_card_link').length;

                    if(size > 0){

                        $('.insuff_card_link').each(function(i,v){

                            var href = $(this).attr('href');

                            $(this).attr('href',href+'&task_end_date='+to_date);
                        });
                    }
                    
                }

            }
        });
  }

  function teamLeadAjax(teamleadjafresult,teamleadverresult,teamleadreportresult)
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

                if(teamleadjafresult==true)
                {
                    $('#jaf-fill-task-card').removeClass('d-none');
                }

                if(teamleadverresult==true)
                {
                    $('#verify-task-card').removeClass('d-none');
                }

                if(teamleadreportresult==true)
                {
                    $('#report-write-task-card').removeClass('d-none');
                }

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

                    var ver_task_link = $('.verify_task_link').length;

                    if(ver_task_link > 0)
                    {
                        $('.verify_task_link').each(function(i,v){

                            var href = $(this).attr('href');

                            $(this).attr('href',href+'&task_start_date='+from_date);
                        });
                    }

                    var report_size = $('.report_write_link').length;

                    if(report_size > 0)
                    {
                        $('.report_write_link').each(function(i,v){

                            var href = $(this).attr('href');

                            $(this).attr('href',href+'&task_start_date='+from_date);
                        });
                    }
                    
                }

                if(to_date!='')
                {
                    //alert(to_date);
                    var jaf_size = $('.jaf_fill_link').length;

                    if(jaf_size > 0)
                    {
                        $('.jaf_fill_link').each(function(i,v){

                            var href = $(this).attr('href');

                            $(this).attr('href',href+'&task_end_date='+to_date);

                        });
                    }

                    var ver_task_link = $('.verify_task_link').length;

                    if(ver_task_link > 0)
                    {
                        $('.verify_task_link').each(function(i,v){

                            var href = $(this).attr('href');

                            $(this).attr('href',href+'&task_end_date='+to_date);

                        });
                    }

                    var report_size = $('.report_write_link').length;

                    if(report_size > 0)
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

  function cardLoaderHtml()
  {
    return "<div class='fa-3x' style='min-height:200px;display: flex;align-items: center;justify-content: center;'><i class='fas fa-spinner fa-pulse'></i></div>";
  }
  
</script>
@endsection
