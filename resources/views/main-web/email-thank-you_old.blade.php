@extends('layouts.app_old')
@section('content')

<style>
  #header{
    display:none;
}
</style>
  
<main id="main" class="thanks">
  <section class="thanku-page">
      <div class="thanku-beta">
        <div class="row">
          <div class="col-md-12 mt-50">
                {{-- <h2 class="logo mylogo text-center"><a class="btn-bcd" href="{{ url('/') }}">BCD</a></h2> --}}
            <div class="thanku-event">
              
              <h1>Congratulations</h1>
              <h3>Your email has been verified now!</h3>
              <h3>Thank you</h3>
              <p class="home-link"><a class="btn-bcd" href="{{Config::get('app.guest_url')}}/login">Click here to Login</a></p>
              <p>Having trouble ? <a href="{{url('/contact')}}">Contact us</a></p>
            </div>
          </div>
        </div>
      </div>
  </section>
</main>
<!-- End #main -->

@endsection
