<div class="row">
    <div class="col-lg-10">
        <div class="btn-group">
                {{-- <button class="btn  dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <h4> Most <span class="rm">Recent Checks <span> </h4>
                </button> --}}
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
  </div>
  
  <div class="row">
  
      <div class="col-lg-12 col-sm-12">
          <div class="card mb-4">
              <div class="card-body">
                  <div class="card-title openCheckOverview" > Checks Overview</div>
                    <div class="table-responsive" style="max-height: 300px;">
                      <table class="table table-bordered table-hover" style="position:relative">
                        <thead>
                          <tr>
                              <th style="position:sticky; top:0px" scope="col"><strong>Checks</strong></th>
                              <th style="position:sticky; top:0px" scope="col"><strong>Completed</strong></th>
                              <th style="position:sticky; top:0px" scope="col"><strong>Remaining</strong></th>
                              <th style="position:sticky; top:0px" scope="col"><strong>Insuff</strong></th>
                              <th style="position:sticky; top:0px" scope="col"><strong>Call</strong></th>
                              <th style="position:sticky; top:0px" scope="col"><strong>SMS</strong></th>
                              <th style="position:sticky; top:0px" scope="col"><strong>Link</strong></th>
                          </tr>
                      </thead>
                        <tbody style="padding-top:40px;">
                            @if (count($array_result)>0)
                              @foreach ($array_result as $result)
                                  <tr>
                                    <td><strong>{{$result['check_name']}}</strong></td>
                                    <td>
                                      <a href="{{ url('/my/candidates/?verify_status=success&service='.$result['check_id']) }}">{{$result['completed']}}</a>
                                    </td>
                                    <td><a href="{{ url('/my/candidates/?verification_status=null&service='.$result['check_id']) }}">{{$result['pending']}}</a></td>
                                    <td><a href="{{ url('/my/candidates/?insuffs=1&service='.$result['check_id'])}}">{{$result['insuff']}}</a></td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                
                                  </tr>
                              @endforeach   
                            @endif
                        </tbody>
                      </table>
                    </div>
              </div>
          </div>
      </div>
  </div>
  
  <div class="row">
      <div class="col-lg-12 col-md-12">
          <div class="card mb-4">
              <div class="card-body">
                  <div class="card-title">Checks Overview</div>
                  <div id="echartBar" style="height: 300px;"></div>
                  {{-- <div id="chart_div" style="height: 300px;"></div> --}}
              </div>
          </div>
      </div>          
  </div>
  
  <script>
  
      var echartElemBar = document.getElementById('echartBar');
      var graph_data = <?php $a=Helper::get_graph_data('1',Auth::user()->parent_id); echo json_encode($a); ?>;
    
      var check_name = [];
      var completed = [];
      var pending = [];
      // console.log(check_name);
      for (let i = 0; i < graph_data.length; i++) 
      {
          check_name.push(graph_data[i].check_name);
          completed.push(graph_data[i].completed);
          pending.push(graph_data[i].pending);
        
      }
      // console.log(check_name);
      if (echartElemBar) {
        var echartBar = echarts.init(echartElemBar);
        echartBar.setOption({
          legend: {
            borderRadius: 0,
            orient: 'horizontal',
            x: 'right',
            data: ['Remaining', 'Completed']
          },
          grid: {
            left: '8px',
            right: '8px',
            bottom: '0',
            containLabel: true
          },
          tooltip: {
            show: true,
            backgroundColor: 'rgba(0, 0, 0, .8)'
          },
          xAxis: [{
            type: 'category',
            data:check_name,
            
            axisTick: {
              alignWithLabel: true
            },
            splitLine: {
              show: false
            },
            axisLine: {
              show: false
            }
          }],
          yAxis: [{
            type: 'value',
            axisLabel: {
              formatter: '{value}'
            },
            min: 0,
            max: 1000,
            interval: 100,
            axisLine: {
              show: false
            },
            splitLine: {
              show: true,
              interval: 'auto'
            }
          }],
          
          series: [{
            name: 'Remaining',
            data: pending,
            label: {
              show: false,
              color: '#0168c1'
            },
            type: 'bar',
            barGap: 0,
            color: '#BBCDDD',
            smooth: true,
            itemStyle: {
              emphasis: {
                shadowBlur: 10,
                shadowOffsetX: 0,
                shadowOffsetY: -2,
                shadowColor: 'rgba(0, 0, 0, 0.3)'
              }
            }
          }, {
            name: 'Completed',
            data: completed,
            label: {
              show: false,
              color: '#639'
            },
            type: 'bar',
            color: '#003473',
            smooth: true,
            itemStyle: {
              emphasis: {
                shadowBlur: 10,
                shadowOffsetX: 0,
                shadowOffsetY: -2,
                shadowColor: 'rgba(0, 0, 0, 0.3)'
              }
            }
          }]
          
        });
        // console.log(check_name);
        $(window).on('resize', function () {
          setTimeout(function () {
            echartBar.resize();
          }, 500);
        });
    }
    
  </script>