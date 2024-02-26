@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
            <div class="main-content">				
 
                <div class="row">
				<div class="card text-left">
               <div class="card-body" style="height: 100vh;">
               
               <div class="col-md-8 offset-md-2">
               <form class="mt-2" method="post" action="{{ route('/subscriptions/store') }}">
                @csrf
			   <div class="row">
			    <div class="col-md-8">
	              <h4 class="card-title mb-3">Create a package </h4> 
				    <p> Fill the required details </p>			
				</div>
				
			   <div class="col-md-10">			   
			
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name"  placeholder="Enter name" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                            <div class="error text-danger">
                                {{ $errors->first('name') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="phone">Descritpions</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="price" name="price" class="form-control" id="price"  placeholder="Enter price" value="{{ old('price') }}">
                            @if ($errors->has('price'))
                            <div class="error text-danger">
                                {{ $errors->first('price') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="user_limit">User Limit</label>
                            <input type="text" name="user_limit" class="form-control" id="user_limit"  placeholder="Enter user limit " value="{{ old('user_limit') }}">
                            @if ($errors->has('user_limit'))
                            <div class="error text-danger">
                                {{ $errors->first('user_limit') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="verification_limit">Verification Limit</label>
                            <input type="text" name="verification_limit" class="form-control" id="verification_limit"  placeholder="Enter limit" value="{{ old('verification_limit') }}">
                            @if ($errors->has('verification_limit'))
                            <div class="error text-danger">
                                {{ $errors->first('verification_limit') }}
                            </div>
                            @endif
                        </div>

				</div>
						
             </div>
             <!-- business details -->
             <div class="row">
			   
			   <div class="col-md-10">			   
    
                <button type="submit" class="btn btn-primary">Submit</button>
                   
			  </div>
						
             </div>
             </form>
               </div>
            </div>
				
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
			
			
		
        </div>

@endsection