@extends('layouts.superadmin')
@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column">
    <!-- ============ Body content start ============= -->
    <div class="main-content">				

        <div class="row">
            <div class="card text-left">
                <div class="card-body">
        
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="card-title mb-3"> Create Job </h3> 
                            <h4 class="card-title"> Instructions for fresh upload </h4>
                            <div class="data-create">
                                <ol>
                                    <li>Download the template <a href="{{ asset('job-denmo.csv') }}" download>Click Here</a></li>
                                    <li>Enter text only, Do not chnage the header sequence or column name</li>
                                    <li>If button is disable that means some fields might be invalidly filled</li>
                                    <li>Upload (.csv only)</li>
                                </ol>
                            </div>
                            <div class="uploader">
                            <div class="col-md-8">
                            <form method="post" enctype="multipart/form-data" action="{{ route('/job/store/excel') }}">
                                @csrf
                                <!-- select customer  -->
                                    <div class="form-group">
                                        <label for="service">Customer</label>
                                        <select class="form-control" name="customer">
                                            <option value="">-Select-</option>
                                            <option value="{{ Auth::user()->business_id }}">Your Own</option>
                                            @if( count($customers) > 0 )
                                                @foreach($customers as $item)
                                                <option value="{{ $item->id }}">{{ $item->company_name.' '.'('.$item->name.')' }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('customer'))
                                        <div class="error text-danger">
                                            {{ $errors->first('customer') }}
                                        </div>
                                        @endif
                                    </div>
                                <div class="form-group">
                                    <label for="service">Verifiction Type</label>
                                    <select class="form-control" name="verification_type">
                                        <option value="">-Select-</option>
                                        <option value="1">Address Verification</option>
                                    </select>
                                    @if ($errors->has('verification_type'))
                                    <div class="error text-danger">
                                        {{ $errors->first('verification_type') }}
                                    </div>
                                    @endif
                                </div>
                                
                                
                                <div class="form-group">
                                <label for="service">Select Excel CSV </label>
                                <input class="form-control" type="file" id="csv_file" name="csv_file">
                                @if ($errors->has('csv_file'))
                                <div class="error text-danger">
                                    {{ $errors->first('csv_file') }}
                                </div>
                                @endif
                                </div>
                                
                                
                                <div class="form-group">
                                <label for="service">Job Name</label>
                                    <input type="text" class="form-control" name="job_name" placeholder="Please Enter job Name" value="{{ old('job_name') }}">
                                    <small class="form-text text-muted">(e.g Job-023, Job-Aadhar-21)</small>
                                    @if ($errors->has('job_name'))
                                    <div class="error text-danger">
                                        {{ $errors->first('job_name') }}
                                    </div>
                                    @endif
                                </div>
                                
                                    <div class="form-group">
                                    <button type="submit" class="btn btn-success">Upload</button>
                                    </div>
                                
                            </form>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div><!-- Footer Start -->
    <div class="flex-grow-1"></div>
    
</div>
@endsection