@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
            <div class="main-content">				
 
                <div class="row">
				<div class="card text-left">
               <div class="card-body">
			   
			   <div class="row">
			    <div class="col-md-8">
	              <h4 class="card-title mb-3">Add new contact </h4> 
				 <p>  </p>			
				</div>
				
			   <div class="col-md-10">			   
				
                    <form class="mt-2" method="post" action="{{ route('/contacts/store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter full name" value="{{ old('name') }}">
                           @if ($errors->has('name'))
                            <div class="error text-danger">
                                {{ $errors->first('name') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Phone</label>
                            <input type="text" name="phone" class="form-control" maxlength="10" id="phone" placeholder="Enter phone" value="{{ old('phone') }}">
                            @if ($errors->has('phone'))
                            <div class="error text-danger">
                                {{ $errors->first('phone') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                            <div class="error text-danger">
                                {{ $errors->first('email') }}
                            </div>
                            @endif
                            
                        </div>

                        <div class="form-group">
                            <label for="company">Company</label>
                            <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{ old('company') }}">
                        </div>
                       
                        <button type="submit" class="btn btn-primary">Submit</button>

                    </form>

				</div>
				
				
							
			 </div>
                </div>
				
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
			
			
		
        </div>
@endsection