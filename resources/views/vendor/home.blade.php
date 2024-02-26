@extends('layouts.vendor')

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
        <div class="row">
            <div class="col-lg-10">
                <h3 class="mr-2"> Dashboard  </h3>

                <div class="row dashboardResult">
                  <div class="col-lg-4 col-sm-6 tatCard">
                  </div>
              
                  <div class="col-lg-4 col-sm-6 taskCard">
                  </div>

                  <div class="col-lg-4 col-sm-6 insuffCard">
                  </div>
                </div>

            </div>
            <div class="col-lg-2">
                <div class="btn-group" style="float: right;">
                 
                    <!-- <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Last 30 days
                    </button> -->

                    <!-- <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px,27px, 0px);">
                        <a class="dropdown-item" href="#">Today Only</a>
                        <a class="dropdown-item" href="#">This Week</a>
                        <a class="dropdown-item" href="#">This Month</a>
                        <a class="dropdown-item" href="#">This Year</a>
                    </div> -->
                </div>      
            </div>
        </div>
                {{-- <div class="row">
                    <!-- ICON BG-->
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4 " style="background-color: #c2c2c2; border-radius: 13px;">
                            <div class="card-body text-center pd-10 ">
                              <i class="fa fa-user"></i>
                                <div class="data-content">
                                    <div class="row">
                                      <div class="col-lg-12 top-heading-dash">
                                        <a class="text-wh text-24 line-height-2 mb-2" href=""> 55 </a><br>
                                        <a class="text-20 mt-2 mb-0 text-wh"  href=" "> <strong>Candidates</strong> </a>
                                      </div>
                                    </div>
                                    <div class="row mt-60">
                                      <div class="col-lg-6 below-heading-dash">
                                        <a class="text-wh text-18 line-height-2 mb-2" href=""> 30 </a> <br>
                                        <a class="mt-2 mb-0 text-wh" href="" ><strong>Active Cases</strong>  </a>
                                      </div>
                                      <div class="col-lg-6 below-heading-dash">
                                        <a class="text-wh text-18 line-height-2 mb-2"  href=""> 10</a> <br>
                                        <a class="mt-2 mb-0 text-wh"  href=""><strong>Inactive</strong>  </a>
                                      </div>
                                      <div class="col-lg-4 below-heading-dash">
                                        <a class="text-wh text-18 line-height-2 mb-2"  href=" {{ url('/my/reports') }}"> {{$reports}} </a><br>
                                        <a class=" mt-2 mb-0 text-wh"  href=" {{ url('/my/reports') }}"><strong>Completed</strong>  </a>
                                      </div>
                                    </div> 
                                </div> 
                            </div>
                        </div>
                    </div> --}}
                    <!-- 2nd box -->
                    {{-- <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4 " style="background-color: #c2c2c2; border-radius: 13px;">
                            <div class="card-body text-center pd-10 ">
                              <i class="fa fa-check"></i>
                                <div class="data-content">
                                    <div class="row">
                                      <div class="col-lg-12 top-heading-dash">
                                        <a class="text-wh text-24 line-height-2 mb-2" href=""> 50 </a><br>
                                        <a class="text-wh text-20 mt-2 mb-0" href=""> <strong>JAF</strong> </a>
                                      </div>
                                    </div>
                                    <div class="row mt-60">
                                      <div class="col-lg-4 below-heading-dash">
                                        <a class="text-wh text-18 line-height-2 mb-2" href=""> 10 </a><br>
                                        <a class="mt-2 mb-0 text-wh"  href=""> <strong>COC</strong>  </a>
                                      </div>
                                      <div class="col-lg-4 below-heading-dash">
                                        <a class="text-wh text-18 line-height-2 mb-2" href="">5 </a><br>
                                        <a class="mt-2 mb-0 text-wh" href=""><strong>Customer</strong> </a>
                                      </div>
                                      <div class="col-lg-4 below-heading-dash no-pd-data">
                                        <a class="text-wh text-18 line-height-2 mb-2" href="" > 3</a><br>
                                        <a class="mt-2 mb-0 text-wh" href=""> <strong>Candidate</strong> </a>
                                      </div> 
                                      <div class="col-lg-3 below-heading-dash no-pd-data">
                                        <p class="text-primary text-18 line-height-1 mb-2"> 1 </p>
                                        <p class="mt-2 mb-0 text-wh"> <strong>Insufficiency</strong>  </p>
                                      </div> 
                                    </div>
                                   
                                </div>  
                            </div>
                        </div>
                    </div> --}}

                    <!-- 3rd box -->
                    {{-- <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4 " style="background-color: #c2c2c2; border-radius: 13px;">
                            <div class="card-body text-center pd-10 ">
                              <i class="fa fa-paper-plane"></i>
                                <div class="data-content">
                                    <div class="row">
                                      <div class="col-lg-12 top-heading-dash">
                                     
                                        <a class="text-wh text-24 line-height-2 mb-0"  href=" "> 100 </a><br>
                                        <a class="text-wh text-20 mt-2 mb-0"  href=" "> <strong>Checks</strong> </a>
                                      </a>
                                      </div>
                                    </div>
                                    <div class="row mt-60">
                                      <div class="col-lg-4 below-heading-dash">
                                        <a class="text-wh text-18 line-height-2 mb-2"  href=" "> 80 </a><br>
                                        <a class="mt-2 mb-0 text-wh"  href=" "><strong>Completed</strong>  </a>
                                      </div>
                                      <div class="col-lg-4 below-heading-dash">
                                        <a class="text-wh text-18 line-height-2 mb-2"  href=""> 5 </a><br>
                                        <a class="mt-2 mb-0 text-wh" href=""><strong>Insuff Raised</strong>  </a>
                                      </div>
                                      <div class="col-lg-4 below-heading-dash">
                                        <a class="text-wh text-18 line-height-2 mb-2" href="">15</a><br>
                                        <a class="mt-2 mb-0 text-wh"  href=" "> <strong>Remaining</strong>  </a>
                                      </div>
                                      
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <!-- 4th box -->
                    {{-- <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #c2c2c2; border-radius: 13px;">
                            <div class="card-body text-center pd-10 ">
                              <i class="fa fa-book"></i>
                                <div class="data-content">
                                    <div class="row">
                                      <div class="col-lg-12 top-heading-dash">
                                        <a class="text-wh text-24 line-height-2 mb-2 " href=" "> 30 </a><br>
                                        <a class="text-20 mt-2 mb-0 text-wh"  href=" "><strong>Reports Received</strong> </p>
                                      </div>
                                    </div>
                                    <!-- <div class="row mt-30">
                                      <div class="col-lg-4 below-heading-dash">
                                        <p class="text-primary text-18 line-height-1 mb-2"> 12 </p>
                                        <p class="mt-2 mb-0 text-wh"> COC </p>
                                      </div>
                                      <div class="col-lg-4 below-heading-dash">
                                        <p class="text-primary text-18 line-height-1 mb-2"> 10 </p>
                                        <p class="mt-2 mb-0 text-wh"> BCD </p>
                                      </div>
                                      <div class="col-lg-4 below-heading-dash">
                                        <p class="text-primary text-18 line-height-1 mb-2"> 5 </p>
                                        <p class="mt-2 mb-0 text-wh"> Candidate </p>
                                      </div>
                                    </div> -->
                                   
                                </div>
                            </div>
                        </div>
                    </div> --}}



                    <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-icon-bg card-icon-bg-2 o-hidden mb-4">
                            <div class="card-body text-center">
                                <i class="fa fa-check"></i>
                                <div class="content">
                                    <p class="text-primary text-24 line-height-1 mb-2</p>
                                    <p class="text-muted mt-2 mb-0"> Checks </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-icon-bg card-icon-bg-3 o-hidden mb-4">
                            <div class="card-body text-center">
                                <i class="fa fa-book"></i>
                                <div class="content"> 
                                    <p class="text-primary text-24 line-height-1 mb-2">  </p>
                   <p class="text-muted mt-2 mb-0"> Verifications done </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-icon-bg card-icon-bg-4 o-hidden mb-4">
                            <div class="card-body text-center">
                                <i class="fa fa-paper-plane"></i>
                                <div class="content">
                                    <p class="text-primary text-24 line-height-1 mb-2"> /p>
                   <p class="text-muted mt-2 mb-0"> Reports  </p>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
        
      <!--  -->
{{-- 
      <div class="row">
        <div class="col-lg-10">
            <div class="btn-group">
                    <button class="btn  dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <h4> Most <span class="rm">Recent Checks <span> </h4>
                    </button>
                    <!-- <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 27px, 0px);">
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another Action</a>
                      <a class="dropdown-item" href="#">Something Else Here</a>
                    </div> -->
            </div>
        </div>

        <div class="col-lg-2">
            <!-- <div class="btn-group" style="float: right;">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  Show <span class="alm"> all Checks </span>
                    </button>
                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 27px, 0px);"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
            </div>        -->
        </div>
      </div> --}}
    
           
            {{-- <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">Checks Overview</div>
                            <div id="echartBar" style="height: 300px;"></div>
                            <div id="chart_div" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>          
              </div>
            </div> --}}
          
      <!--  -->
 
      </div>

  </div>

<!-- modal -->
<!-- The Modal -->
  {{-- <div class="modal" id="checksModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Checks OverView</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          
          <div class="form-group">  HE </div>

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div> --}}
<!-- ./modal -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
$(document).ready(function(){

  setTimeout(()=>{
        //cardAjax();
      tatCardAjax();
      taskCardAjax();
      insuffCardAjax();
  },500);


  function tatCardAjax()
  {
    
    var _token = "{{ csrf_token() }}";

    $.ajax({
        url: "{{ route('/vendor/dashboard-tat-card') }}",
        type: 'POST',
        cache: false,
        data: {'_token': _token},
        datatype: 'html',
        beforeSend: function() {
            //something before send
            $('.tatCard').html(cardLoaderHtml()).fadeIn(300);
        },
        success: function(data) {
            console.log(data);
            $('.tatCard').html(data.html);
        }
    });
  }  
  
  function taskCardAjax()
  {
    
    var _token = "{{ csrf_token() }}";

    $.ajax({
        url: "{{ route('/vendor/dashboard-task-card') }}",
        type: 'POST',
        cache: false,
        data: {'_token': _token},
        datatype: 'html',
        beforeSend: function() {
            //something before send
            $('.taskCard').html(cardLoaderHtml()).fadeIn(300);
        },
        success: function(data) {
            console.log(data);
            $('.taskCard').html(data.html);
        }
    });
  }    

  function insuffCardAjax()
  {
    
    var _token = "{{ csrf_token() }}";

    $.ajax({
        url: "{{ route('/vendor/dashboard-insuff-card') }}",
        type: 'POST',
        cache: false,
        data: {'_token': _token},
        datatype: 'html',
        beforeSend: function() {
            //something before send
            $('.insuffCard').html(cardLoaderHtml()).fadeIn(300);
        },
        success: function(data) {
            console.log(data);
            $('.insuffCard').html(data.html);
        }
    });
  } 
  function cardLoaderHtml()
  {
    return "<div class='fa-3x' style='min-height:200px;display: flex;align-items: center;justify-content: center;'><i class='fas fa-spinner fa-pulse'></i></div>";
  }

  $(document).on('click','.openCheckOverview', function(){
    $('#checksModal').modal();
  });
});
</script>
@endsection
