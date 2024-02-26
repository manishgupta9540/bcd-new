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
                <li>Quicksbook</li>
                </ul>
            </div>
            <!-- ============Back Button ============= -->
            <div class="col-sm-1 back-arrow">
                <div class="text-right">
                <a href="{{url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card text-left">
               <div class="card-body">
            
            <div class="row">
                <div class="col-md-12">
                    @include('admin.quickbook.menu')
                 </div>
              
              
             <div class="col-md-8">
                 <h4 class="card-title mb-1"> Customers </h4> 
                <p> List of all customers </p>        
            </div>
            @if ($message = Session::get('success'))
            <div class="col-md-12">   
                <div class="alert alert-success">
                    <strong>{{ $message }}</strong> 
                </div>
            </div>
        @endif
               
        </div>
          
            <div id="candidatesResult">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    {{-- <th scope="col">#</th> --}}
                                    <th scope="col" style="position:sticky; top:60px">Company Name</th>
                                    <th scope="col" style="position:sticky; top:60px">Contact Person</th>
                                    <th scope="col" style="position:sticky; top:60px">Email</th>
                                    <th scope="col" style="position:sticky; top:60px" width="15%">Phone</th>
                                    <th scope="col" style="position:sticky; top:60px" width="10%">Created at</th>
                                    <th scope="col" style="position:sticky; top:60px">Status</th>
                                    <th scope="col" style="position:sticky; top:60px" width="15%">Action</th>
                                </tr>
                            </thead> 
                            <tbody>
                                @if( count($items) > 0 )
                                    @foreach($items as $item)
                                        <?php
                                            $display_id =NULL;
                                            if($item->display_id!=NULL)
                                            {
                                                $display_id = $item->display_id;
                                            }
                                            else {
                                                $u_id = str_pad($item->id, 10, "0", STR_PAD_LEFT);
                                                $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr($item->company_name,0,4)))).'-'.$u_id;
                                            }
                                            $sent_to_quickbook =Helper::quickbook_customer($item->id);
                                        ?>
                                        <tr>
                                            {{-- <th scope="row">{{ $item->id }}</th> --}}
                                            <td> <b>{{ ucfirst($item->company_name) }} </b><br>
                                                <small class="text-muted">Customer ID:- <b>{{$display_id }} </b></small>
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email}}</td>
                                            <td>{{ "+".$item->phone_code."-".str_replace(' ','',$item->phone) }}</td>
                                            <td>{{ date('d-m-Y',strtotime($item->created_at)) }}</td>
                                            <td>
                                                @if ($sent_to_quickbook!=null)
                                                    <span class="badge badge-success">
                                                        Sent
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning">Waiting...</span>
                                                @endif
                                               
                                            </td>
                                            <td class="text-center">
                                                @if ($sent_to_quickbook!=null)
                                                    <span>--</span> 
                                                @else
                                                    <a href="{{ url('/quickbook/customer/add',['id'=>base64_encode($item->id)]) }}"><button class="btn btn-info btn-sm" type="button"><i class="fa fa-share"></i> Send to Quicksbook</button></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td scope="row" colspan="7"><h3 class="text-center">No record!</h3></td>
                                    </tr>
                                @endif
                            
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" role="status" aria-live="polite"></div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class=" paging_simple_numbers" >            
                            {!! $items->render() !!}
                        </div>
                    </div>
                </div> --}}
            </div>
            
    </div>
            {{-- <input type="hidden" name="active_case" id="active_case" value="{{$active_case}}"> --}}
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
  
</div>

@endsection