@extends('layouts.admin')
@section('content')

        <div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
            <div class="main-content">				
 
                <div class="row">
				<div class="card text-left">
               <div class="card-body">
	

                        <div class="col-md-12">
                        <div class="row">

                                    <div class="col-md-4">
                                        <div class="card card-profile-1 mb-4">
                                            <div class="card-body text-center"  style="padding:0px">
                                                <h2 class="m-0" style="padding-top: 15px;"> Our Plans </h2>
                                                <p class="mt-0"><b>{{ $active_plan->name }}</b></p>
                                                <p style="padding: 25px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae cumque.Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae cumque.Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae cumque.</p>
												<table class="table table-striped" style="margin-bottom: 122px;">
                                                <tbody>
                                                    <tr>
                                                        <td> <span> <strong> Total Vrifications </strong></span>
                                                        <br> {{ $active_plan->verifications_allowed }} </td>
                                                    </tr>
                                                    <tr>
                                                        <td> <span> <strong> Total Candidates  </strong></span>
                                                        <br> {{ $active_plan->candiates_allowed }} </td>
                                                    </tr>
													<tr>
                                                        <td> <span> <strong> Services </strong></span>
                                                        <br> Address Verification </td>
                                                    </tr>
                                                    <tr>
                                                    <td> <span> <strong> Billing </strong></span>
                                                        <br> {{ $active_plan->currency }} {{ $active_plan->price }}/{{ $active_plan->billing_cycle_type }} </td>
                                                    </tr>
													<tr>
                                                        <td> <label class="text-success"> Active </label> </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
												  
                                            </div>
                                        </div>
                                    </div>
									
                                      <div class="col-md-2">
                                        <div class="card card-profile-1 mb-4">
                                            <div class="card-body text-center" style="padding:0px">
											<div class="pricetop">
											 <i class="fa fa-tasks" aria-hidden="true" style="font-size: 36px;"></i>
											  <p class="mt-0"> Starter </p>
											  </div>
											  
                                                <div class="avatar box-shadow-2 mb-3" style="margin-top: -40px;background: #fff;">
												$<span class="pris">9</span>
												<br> months
												</div>
                                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
												
												  <table class="table table-striped">
 
                                                 <tbody>
 
                                                    <tr>
                                                     <td> <span> <strong> 25k </strong></span><br> Lorem ipsum </td>
                                                     </tr>
                                                          <tr>
                                                         <td> <span> <strong> 25k </strong></span><br> Lorem ipsum </td>
                                                         </tr>
													   <tr>
                                                      <td> <span> <strong> 25k </strong></span><br> Lorem ipsum </td>
                                                         </tr>
														 <tr>
                                                         <td>  - </td>
                                                         </tr>
                                                    </tbody>
                                                  </table>
												 
                                                <button class="btn  btn-rounded" style="border: 2px solid #b1f1b4; background: transparent;border-radius: 0px!important;"> SELECT PLAN </button>
	                                            <p class="fottab">Lorem ipsum dolor sit amet </p>
                                               
                                            </div>
                                        </div>
                                    </div>
									
									
                                      <div class="col-md-2">
                                        <div class="card card-profile-1 mb-4">
                                            <div class="card-body text-center" style="padding:0px">
											<div class="pricetop" style="background: #dbc1f5;">
											 <i class="fa fa-cogs" aria-hidden="true" style="font-size: 36px;"></i>
											  <p class="mt-0"> Business </p>
											  </div>
											  
                                                <div class="avatar box-shadow-2 mb-3" style="margin-top: -40px;background: #fff;">
												$<span class="pris">21</span>
												<br> months
												</div>
                                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
												
												  <table class="table table-striped">
 
                                                 <tbody>
 
                                                    <tr>
                                                     <td> <span> <strong> 25k </strong></span><br> Lorem ipsum </td>
                                                     </tr>
                                                          <tr>
                                                         <td> <span> <strong> 25k </strong></span><br> Lorem ipsum </td>
                                                         </tr>
													   <tr>
                                                      <td> <span> <strong> 25k </strong></span><br> Lorem ipsum </td>
                                                         </tr>
														 <tr>
                                                         <td>  - </td>
                                                         </tr>
                                                    </tbody>
                                                  </table>
												 
                                                <button class="btn  btn-rounded" style="border: 2px solid #dbc1f5;
    background: transparent;border-radius: 0px!important;"> SELECT PLAN </button>
	                                           <p class="fottab">Lorem ipsum dolor sit amet </p>
                                               
                                            </div>
                                        </div>
                                    </div>



                                      <div class="col-md-2">
                                        <div class="card card-profile-1 mb-4">
                                            <div class="card-body text-center" style="padding:0px">
											<div class="pricetop" style="background: #c3cbf7;">
											 <i class="fa fa-id-card" aria-hidden="true" style="font-size: 36px;"></i>
											  <p class="mt-0"> Professional </p>
											  </div>
											  
                                                <div class="avatar box-shadow-2 mb-3" style="margin-top: -40px;background: #fff;">
												$<span class="pris">42</span>
												<br> months
												</div>
                                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
												
												  <table class="table table-striped">
 
                                                 <tbody>
 
                                                    <tr>
                                                     <td> <span> <strong> 25k </strong></span><br> Lorem ipsum </td>
                                                     </tr>
                                                          <tr>
                                                         <td> <span> <strong> 25k </strong></span><br> Lorem ipsum </td>
                                                         </tr>
													   <tr>
                                                      <td> <span> <strong> 25k </strong></span><br> Lorem ipsum </td>
                                                         </tr>
														 <tr>
                                                         <td>  - </td>
                                                         </tr>
                                                    </tbody>
                                                  </table>
												 
                                                <button class="btn  btn-rounded" style="border:2px solid #c3cbf7;
    background: transparent;border-radius: 0px!important;"> SELECT PLAN </button>
	<p class="fottab">Lorem ipsum dolor sit amet </p>
                                               
                                            </div>
                                        </div>
                                    </div>



									
                                      <div class="col-md-2">
                                        <div class="card card-profile-1 mb-4">
                                            <div class="card-body text-center" style="padding:0px">
											<div class="pricetop" style="background: #ffa29b;">
											<i class="fa fa-id-card" aria-hidden="true" style="font-size: 36px;"></i>
											  
											  <p class="mt-0"> Premium </p>
											  </div>
											  
                                                <div class="avatar box-shadow-2 mb-3" style="margin-top: -40px;background: #fff;">
												$<span class="pris">81</span>
												<br> months
												</div>
                                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
												
												  <table class="table table-striped">
 
                                                 <tbody>
 
                                                    <tr>
                                                     <td> <span> <strong> 25k </strong></span><br> Lorem ipsum </td>
                                                     </tr>
                                                          <tr>
                                                         <td> <span> <strong> 25k </strong></span><br> Lorem ipsum </td>
                                                         </tr>
													   <tr>
                                                      <td> <span> <strong> 25k </strong></span><br> Lorem ipsum </td>
                                                         </tr>
														 <tr>
                                                         <td>  - </td>
                                                         </tr>
                                                    </tbody>
                                                  </table>
												 
                                                <button class="btn  btn-rounded" style="border:2px solid #ffa29b;
    background: transparent;border-radius: 0px!important;"> SELECT PLAN </button>
	<p class="fottab">Lorem ipsum dolor sit amet </p>
                                               
                                            </div>
                                        </div>
                                    </div>
	
	 
</div>			 
</div>								
								
                             
                </div>
				 </div>
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
			
			
			
			
			
			
            <!-- <div class="app-footer">
                <div class="footer-bottom border-top pt-3 d-flex flex-column flex-sm-row align-items-center">
                     
					 <p><strong> 2020 &copy; Admin ! All rights reserved</strong></p>
					 
                    <span class="flex-grow-1"></span>
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="m-0"> design by techsaga </p>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- fotter end -->
        </div>
    </div>
@endsection