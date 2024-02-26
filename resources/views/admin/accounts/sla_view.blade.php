@extends('layouts.admin')
@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
            <div class="main-content">          
              <div class="row">
                <div class="col-sm-11">
                    <ul class="breadcrumb">
                    <li>
                    <a href="{{ url('/home') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('/settings/general') }}">Accounts</a>
                    </li>
                    <li>
                      <a href="{{ url('/sla') }}">SLA</a>
                    </li>
                    <li>Details</li>
                    </ul>
                </div>
                <!-- ============Back Button ============= -->
                <div class="col-sm-1 back-arrow">
                    <div class="text-right">
                    <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                    </div>
                </div>
              </div>
            <div class="row">
              <div class="col-md-3 content-container">
                <!-- left-sidebar -->
               @include('admin.accounts.left-sidebar') 
              </div>
              <!-- start right sec -->
              <div class="col-md-9 content-wrapper">
                  <div class="formCover" style="height: 100vh; background:#fff">
                    <!-- section -->
                    
                        <div class="col-sm-12 ">
                          
                              <!-- row -->
                              <div class="row">
                                <div class="col-md-12">
                                    <h4 class="card-title mb-1 mt-3">SLA Details </h4>
                                    <p class="pb-border"> SLA Summary Details with Selected Checks </p>
                                </div>
                                <div class="col-md-12">
                                {{-- <form method="post" action="{{ url('/settings/sla/update') }}">
                                      @csrf --}}
                                    <div class="row">
                                      <div class="col-sm-6">
                                          <div class="form-group">
                                            <label style="font-size: 14px;"> ID :</label>
                                            <label style="font-size: 14px;"> <b> {{ $sla->display_id!=NULL ? $sla->display_id : '--'}}  </b></label>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <div class="form-group">
                                            <label style="font-size: 14px;"> Customer :</label>
                                            <label style="font-size: 14px;"> <b> {{ ucfirst($sla->company_name) }}  </b></label>
                                            {{-- <input type="hidden" name="customer" value="{{ $sla->business_id }}"> --}}
                                          </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-sm-6">
                                          <div class="form-group">
                                            <label style="font-size: 14px;">SLA Name : <b>{{ $sla->title}}</b></label>
                                          </div>
                                      </div>   
                                      <div class="col-sm-6">
                                        <div class="form-group">
                                          <label style="font-size: 14px;">Internal TAT : <b>{{ $sla->tat}} @if($sla->tat > 1) days @else day @endif</b></label>
                                        </div>
                                      </div>   
                                    </div>
                                   

                                    <div class="row">
                                      <div class="col-sm-6">
                                          <div class="form-group">
                                            <label style="font-size: 14px;">Client TAT : <b>{{ $sla->client_tat}} @if($sla->client_tat > 1) days @else day @endif</b></label>
                                          </div>
                                      </div> 
                                      <div class="col-sm-6">
                                        <div class="form-group">
                                           <label style="font-size: 14px;"> Days Type :</label>
                                           <label style="font-size: 14px;"> <b>{{ ucfirst($sla->days_type.' Days') }} </b></label>
                                        </div>
                                      </div>  
                                    </div>
                                   <div class="row">
                                      <div class="col-sm-6">
                                         <div class="form-group">
                                            <label style="font-size: 14px;"> TAT Type :</label>
                                            <label style="font-size: 14px;"> <b>{{ ucfirst($sla->tat_type.'-'.'Wise') }} </b></label>
                                         </div>
                                      </div>
                                      <div class="col-sm-6">
                                        <div class="form-group">
                                          <label style="font-size: 14px;"> Price Type :</label>
                                          <label style="font-size: 14px;"> <b>{{ ucfirst($sla->price_type.'-'.'Wise') }} </b></label>
                                        </div>
                                      </div>
                                   </div>

                                   

                                   @if(stripos($sla->tat_type,'case')!==false)
                                       <div class="tat_result mb-2" style="border:1px solid #ddd;padding:10px;width:50%">
                                          <div class="row">
                                             <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Case-Wise Incentive & Penalty</div>
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Incentive (<small class="text-muted">in %</small>)</label>
                                                   <input class="form-control" type="text" name="incentive" value="{{$sla->incentive}}" disabled>
                                                   {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-incentive"></p> --}}
                                                </div>
                                             </div> 
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Penalty (<small class="text-muted">in %</small>)</label>
                                                   <input class="form-control" type="text" name="penalty" value="{{$sla->penalty}}" disabled>
                                                   {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-penalty"></p> --}}
                                                </div>
                                             </div>  
                                          </div>
                                       </div>
                                    @endif
                                   
                                      <div class="price_result @if(stripos($sla->price_type,'package')!==false) mb-2 @endif" @if(stripos($sla->price_type,'package')!==false) style="border:1px solid #ddd;padding:10px;width:50%" @endif>
                                         @if(stripos($sla->price_type,'package')!==false)
                                            <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Package Wise Price</div>
                                            <div class="col-sm-6">
                                               <div class="form-group">
                                                  <label>Price <span class="text-danger">*</span> (<small class="text-muted">in <i class="fas fa-rupee-sign"></i></small>)</label>
                                                  <input class="form-control" type="text" name="price" value="{{$sla->package_price}}" disabled>
                                                  <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-price"></p>
                                               </div>
                                            </div> 
                                         @endif
                                      </div>

                                    <div class="row">
                                      <div class="col-sm-6">
                                          <div class="form-group">
                                            <label>Services :</label>
                                            <div class="col-sm-12">
                                            <div class="form-group">
                                            @foreach($services as $service)

                                            <div class="form-check form-check-inline">
                                            <input class="form-check-input services_list" type="checkbox" name="services[]" value="{{ $service->id}}" data-string="{{ $service->name  }}" id="inlineCheckbox-{{ $service->id}}" data-type="{{ $service->is_multiple_type }}" data-verify="{{$service->verification_type}}"
                                            {{in_array($service->id, $selected_services_id) ? 'checked' : '' }} disabled>
                                            <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label>
                                                </div>
                                            
                                            @endforeach
                                            </div>
                                          </div>
                                            @if ($errors->has('services'))
                                            <div class="error text-danger">
                                                {{ $errors->first('services') }}
                                            </div>
                                            @endif
                                          </div>
                                      </div>

                                      <div class="col-sm-6">
                                          
                                      </div>
                                    </div>

                                    <div class="service_result" style="border: 1px solid #ddd; padding:10px;">
                                      <div class="row">
                                          <div class="col-sm-12 mt-1 mb-2">
                                            <span style="color:#dd2e2e"> Number of Verifications Need on each check item</span>
                                             <span style="float: right;">
                                                <span class="pr-2"> Total Checks:- <span class="total_checks">{{$total_checks}}</span></span>
                                                <span class="total_p @if(stripos($sla->price_type,'package')!==false) d-none @endif"> Total Price:- <i class='fas fa-rupee-sign'></i> <span class="total_check_price">{{$total_check_price}}</span></span>
                                             </span>
                                          </div>
                                      </div>
                                          @foreach($sla_items as $item)
                                            <p class="pb-border row-{{ $item->id}}"></p>
                                            <div class='row mt-2 row-{{ $item->id}}' id="row-{{ $item->id}}">
                                                  <div class='col-sm-2'><label>{{ $item->name}}</label></div>
                                                  <div class='col-sm-2'><input class='form-control' type='text' name='service_unit-{{$item->id}}' value="{{ $item->number_of_verifications }}" disabled></div>
                                                  <div class='col-sm-1'><label>TAT</label></div>
                                                  <div class='col-sm-3'><input class='form-control' type='text' name='tat-{{$item->id}}' value="{{ $item->check_tat }}" disabled></div>
                                                  <div class='col-sm-3'><input class='form-control' type='text' name='notes-{{$item->id}}' value="{{ $item->notes }}" placeholder="Notes" disabled></div>
                                            </div>

                                            <div class='row mt-2 row-{{ $item->id}}' id="row-{{ $item->id}}">
                                              <div class='col-sm-3'></div>
                                              <div class='col-sm-2 pt-2 text-right'><label>Incentive TAT</label></div>
                                              <div class='col-sm-1'>
                                                 <input class='form-control' type='text' name='incentive-{{$item->id}}' value="{{ $item->incentive_tat }}" placeholder="Incentive TAT" disabled>
                                              </div>
                                              <div class='col-sm-2 pt-2 text-right'><label>Penalty TAT</label></div>
                                                <div class='col-sm-1'>
                                                   <input class='form-control' type='text' name='penalty-{{$item->id}}' value="{{ $item->penalty_tat }}" placeholder="Penalty TAT" disabled>
                                                </div>
                                           </div>
                                           <div class='row price_row @if(stripos($sla->price_type,'package')!==false) d-none @endif mt-2 row-{{$item->id}}' id='row mt-2 row-{{$item->id}}'>
                                                <div class='col-sm-2 pt-2'>
                                                  <label>Price (<small class='text-muted'><i class='fas fa-rupee-sign'></i></small>)</label>
                                                </div>
                                                <div class='col-sm-2'>
                                                  <input class='form-control' type='text' name='price-{{$item->id}}' value='{{$item->price}}' disabled>
                                                  <p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-{{$item->id}}'></p>
                                                </div>
                                          </div>
                                            
                                          @endforeach
                                    </div>
                                      
                                    {{-- </form> --}}
                                </div>
                              </div>
                              <!-- ./form section -->
                              
                        </div>
                    
                    <!--  -->
                    <!-- ./section -->
                  </div>
              </div>
              <!-- end right sec -->
            </div>
            <div class="flex-grow-1"></div>
         
        </div>

@endsection