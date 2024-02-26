@extends('layouts.client')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content"> 
    <div class="row">
        <div class="card text-left">
           <div class="card-body">            
                   <div class="row">
                   <div class="col-md-8">
                        <h4 class="card-title mb-1"> Checks overview </h4> 
                        <p> List of all Candidate's checks </p>        
                    </div>
                    <div class="col-md-4">           
                        <div class="btn-group" style="float:right">        
                                       
                        </div>
                    </div>

                        <div class="col-md-12"> 
                            <div class="table-responsive">
                                <table class="table table-bordered  table-hover candidatesTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Check Performed</th>
                                            <th scope="col">Check Completed</th>
                                            <th scope="col">Check Pending</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody class="candidateList"> 
                                   
                                </table>
                            </div>
                        </div>
                        
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" role="status" aria-live="polite"></div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                            <div class=" paging_simple_numbers" >            
                                {!! $items->render() !!}
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <!-- Footer Start -->
   <div class="flex-grow-1"></div>
    </div>
    @endsection