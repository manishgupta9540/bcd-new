@extends('layouts.admin')
@section('content')
<!-- =============== Left side End ================-->
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="card text-left">
            <div class="card-body">
               <h3 class="card-title mb-3">  Quality Check (QC) </h3>
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
                           <th> Case </th>
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
                        @if(count($qcs_data) > 0 )
                        @foreach($qcs_data as $data)                                     
                        <tr>
                           <td>{{ ++$i }}</td>
                           <td>{{ Helper::get_sla_name($data->sla_id)}}</td>
                           <td>{{ $data->total_candidates }}</td>
                           <td>0</td>
                           <td>0</td>
                           <td>0</td>
                           <td>0</td>
                           <td>0</td>
                           <td><a href="#" onclick="getjobdetails('{{$data->id}}');" data-toggle="modal" data-target="#single-person">1</a></td>
                           <td>0</td>
                           <td>0</td>
                           <td>0</td>
                           <td class="red">0</td>
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
                        @endforeach
                        @else
                        <tr> <td colspan="15" class="text-center"><h3> No record! </h3></td> </tr>
                           
                        @endif
                     </tbody>
                  </table>
               </div>
               @if(count($qcs_data)>5)							
               <div class="row mt-3">
                  <div class="col-sm-12 col-md-5">
                     <div class="dataTables_info" id="zero_configuration_table_info" role="status" aria-live="polite">Showing {{$i}} to 5 of {{count($qcs_data)}} entries</div>
                  </div>
                  {!!$qcs_data->render()!!}
               </div>
               @endif							
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
                  <div id="jobdetails"></div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   function getjobdetails(id){
     $.ajax({
        type:'GET',
        url:'/getjobdetails',
        data:{'id':id},
        success:function(data) {                  
           $("#jobdetails").html(data);
        }
     });
   }
</script>
@endsection
