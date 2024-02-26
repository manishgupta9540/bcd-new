@extends('layouts.app_old')
@section('content')
<style>
    .disabled-link {
        pointer-events: none;
    }
    #header{
        display:none;
    }
    .thanku-event h1 {
        font-size: 30px;
    }
    @media only screen and (max-width:767px){
        .thanku-event {
        width: 100%!important;
        }
        .thanku-page-font{
        font-size: 25px!important;
        }
        .text-success {
            font-size: 20px;
        }
    }

   
</style>
<main id="main" class="thanks">
    <section class="thanku-page">
        <div class="thanku-beta">
          <div class="row">
            <div class="col-md-12 mt-50">
              {{-- <h2 class="logo mylogo text-center"><a class="btn-bcd" href="{{ url('/') }}">MyBCD</a></h2> --}}
              <div class="thanku-event">
                
                <h1 class="thanku-page-font">Thank You for Submitting the Address Verification Form</h1><br>
                <h4 class="text-success">You did an awesome job!</h4><br>
                <h3 class="text-danger">Have a nice day!</h3>
                @auth
                    <a class="btn btn-info mt-3" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Click Here To Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                @endauth
              </div>
            </div>
          </div>
        </div>
    </section>
</main>
  <!-- End #main -->
@endsection
