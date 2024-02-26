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
                <h3 class="heading-form text-center">Your email is verified now.</h3>
                
                  <div class="form-group">
                    <label for="name"></label>
                    
            
                <p class="members"> <a href="{{ env('APP_LOGIN_URL') }}">Login Here</a></p>

            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
    
  </main>
  <!-- End #main -->

@endsection
