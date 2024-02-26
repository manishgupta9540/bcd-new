@extends('layouts.client')
<style>
  
  .card-icon-bg .fa{
  font-size: 21px!important;
    background: #0C396F!important;
    padding: 12px!important;
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
    position: relative!important;
    top: -16%!important;
    font-size: 15px!important;
}
.below-heading-dash {
    display: flex!important;
    justify-content: space-between!important;
    align-items: baseline!important;
    font-size:12px!important;
}
.below-heading-dash .text-18 {
    font-size: 12px!important;
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
@section('content')

 <div class="main-content-wrap sidenav-open d-flex flex-column">
    <div class="main-content">         

            <div class="row">
                <div class="col-lg-10">
                    <h3 class="mr-2"> Dashboard  </h3>
                </div>
                <div class="col-lg-2 pt-2">
                  <div class="" style="float: right;">
                    {{-- <button class="btn btn-link progress_excel" type="button">
                      <i class="fas fa-file-excel"></i> Progress Tracker
                    </button><br> --}}
                    <a href="{{url('/my/progress-export')}}" target="_blank" class="btn btn-link">
                      <i class="fas fa-file-excel"></i> Progress Tracker
                    </a><br>
                    {{-- <p class="text-danger text-center mis_load"></p> --}}
                  </div>
                </div>

                {{-- <div class="col-lg-2">
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
                </div> --}}
            </div>
            
            <div class="row dashboardResult">
              <div class="col-lg-3 col-md-6 col-sm-6 candidateCard">
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 jafCard">
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 checkCard">
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 reportCard">
              </div>
            </div>
    
            <div class="dashboardCheckResult">
    
            </div>
    </div>
          
      <!--  -->
 
 </div>

</div>

<!-- modal -->
<!-- The Modal -->
  <div class="modal" id="checksModal">
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
  </div>
<!-- ./modal -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
$(document).ready(function(){
  
  // $(document).on('click','.openCheckOverview', function(){
  //   $('#checksModal').modal();
  // });

  setTimeout(()=>{
       
       candidateCardAjax();
       jafCardAjax();
       checkCardAjax();
       reportCardAjax();
       checkAjax();
      },500);
});

function candidateCardAjax()
{
    var _token = "{{ csrf_token() }}";
    $.ajax({
            url: "{{ route('/my/home/dashboard-candidate-card') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token },
            datatype: 'html',
            beforeSend: function() {
                //something before send
                $('.candidateCard').html(cardLoaderHtml()).fadeIn(300);
            },
            success: function(data) {
                //console.log(data);
                $('.candidateCard').html(data.html);
            }
        });
}

function jafCardAjax()
{
  var _token = "{{ csrf_token() }}";
  $.ajax({
          url: "{{ route('/my/home/dashboard-jaf-card') }}",
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
          url: "{{ route('/my/home/dashboard-check-card') }}",
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
          url: "{{ route('/my/home/dashboard-report-card') }}",
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
            url: "{{ route('/my/home/check/render') }}",
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


function loaderHtml() {
    
    return "<div style='background-color:#ddd; min-height:1000px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>";
}

function cardLoaderHtml()
{
  return "<div class='fa-3x' style='min-height:200px;display: flex;align-items: center;justify-content: center;'><i class='fas fa-spinner fa-pulse'></i></div>";
}

</script>
@endsection
