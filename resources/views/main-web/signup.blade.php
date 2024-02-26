@extends('layouts.app')
@section('content')

<style>
  #header{
    display:none;
}
</style>
  <main id="main">
  <section class="full-page">
    <div class="side-beta-image">
    <h1 class="logo mylogo"><a href="{{ url('/') }}"> BCD </a> </h1>
      <img src="{{ asset('main-web/img/official.jpg') }} " class="img-fluida">
    </div>
    <div class="main-section">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-10 offset-1">
            <form action="{{ route('/signup/store') }}" method="post" role="form" class=" login-form signup-do">
                @csrf
                <!-- <h1 class="logo text-center">BCD</h1>  -->
                <!-- <img class="logo" src=""> -->
                <h3 class="heading-form text-center">Create An Account</h3>
                
                  <div class="form-group">
                    <label for="name">First Name</label>
                    <input type="text" class="form-control" name="first_name" id="first_name" value="{{ old('first_name') }}" >
                     @if ($errors->has('first_name'))
                        <div class="error text-danger">
                            {{ $errors->first('first_name') }}
                        </div>
                        @endif
                    </div>
                  
                  <div class="form-group">
                    <label for="name">Last Name</label>
                    <input type="text" class="form-control" name="last_name" id="last_name" value="{{ old('last_name') }}">
                    @if ($errors->has('last_name'))
                      <div class="error text-danger">
                          {{ $errors->first('last_name') }}
                      </div>
                      @endif
                  </div>

                  <div class="form-group">
                    <label for="name">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}"  >
                     @if ($errors->has('email'))
                      <div class="error text-danger">
                          {{ $errors->first('email') }}
                      </div>
                      @endif
                  </div>

                  <div class="form-group">
                    <label style="display: block;">Phone </label>
                    <input type="hidden"  id="code" name ="primary_phone_code" value="91" >
                    <input type="hidden"  id="iso" name ="primary_phone_iso" value="in" >
                    <input type="tel"     name="phone" id="phone1" value="" class="form-control" style='display:block'>
                    @if ($errors->has('phone')) <p class="text-danger">{{ $errors->first('phone') }}</p> @endif
                  </div>

                  <div class="form-group">
                    <label for="name">User Type</label>  
                    <select class="form-control" name="user_type">
                       <option value="">-Select-</option>
                          <option value="Individual" >Individual</option>
                          <option value="Business">Business</option>
                    </select>
                    @if ($errors->has('user_type'))
                      <div class="error text-danger">
                          {{ $errors->first('user_type') }}
                      </div>
                    @endif
                  </div>
                  
                  <div class="form-group">
                    <label for="name"> Country</label>  
                    <select class="form-control" name="country">
                       <option value="">Select a Country</option>
                        @foreach($country as $countries)
                         @if (old('country' ) == $countries->id)
                            <option value="{{$countries->id}}" selected>{{$countries->name}}</option>
                          @else
                            <option value="{{$countries->id}}">{{$countries->name}}</option>
                         @endif
                        @endforeach
                    </select>
                    @if ($errors->has('country'))
                      <div class="error text-danger">
                          {{ $errors->first('country') }}
                      </div>
                    @endif
                  </div>
                  
                  <div class="form-group">
                    <label for="name">Password</label>
                    <input type="password" class="form-control" name="password" id="password" value="{{ old('password') }}" >
                    @if ($errors->has('password'))
                    <div class="error text-danger">
                        {{ $errors->first('password') }}
                    </div>
                    @endif
                  </div>

                <div class="form-group">
                  <label for="name">Confirm Password</label>
                  <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" >
                   @if ($errors->has('password_confirmation'))
                    <div class="error text-danger">
                        {{ $errors->first('password_confirmation') }}
                    </div>
                    @endif
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" value="1" @if( old('terms') ) checked @endif )>
                    <label class="form-check-label"  for="terms"  style="font-size:13px;">Click here to accept 
                      <a href="#">Terms and Conditions</a>.</label>
                      @if ($errors->has('terms'))
                      <div class="error text-danger">
                          {{ $errors->first('terms') }}
                      </div>
                      @endif
                </div>
              
              <div class="text-center mt-30">
                <button type="submit" name="submit"  class="btn-submit">SignUp</button>
              </div>
              <p class="members">Already a member <a href="{{ env('APP_LOGIN_URL') }}">Login Here</a></p>

            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
    
  </main>
  <!-- End #main -->

@endsection
