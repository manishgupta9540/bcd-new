@extends('layouts.app')
@section('content')

<style>
  #header{
    display:none;
}
</style>
  
<main id="main" class="">
  
<div class="container">
      <div class="py-5 text-center">
        <a href="{{ url('/')}}"><h1> BCD </h1></a>
        <p class="lead"></p>
      </div>

      <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Order Summary</span>
            <!-- <span class="badge badge-secondary badge-pill">3</span> -->
          </h4>
          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Package</h6>
                <small class="text-muted">{{ $package->name }}</small>
              </div>
              <span class="text-muted">{{ $package->price }}</span>
            </li>
           
            <!-- <li class="list-group-item d-flex justify-content-between bg-light">
              <div class="text-success">
                <h6 class="my-0">Promo code</h6>
                <small>EXAMPLECODE</small>
              </div>
              <span class="text-success">-5</span>
            </li> -->

            <li class="list-group-item d-flex justify-content-between">
              <span> Total (INR) </span>
              <strong>{{ $package->price }}</strong>
            </li>
          </ul>

          <form class="card p-2">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Promo code">
              <div class="input-group-append">
                <button type="submit" class="btn btn-secondary">Redeem</button>
              </div>
            </div>
            <div class="form-group">
              <button type="button" class="btn btn-success">Proceed to pay</button>
            </div>
          </form>

        </div>
        <div class="col-md-8 order-md-1">
          <h4 class="mb-3">Billing Information</h4>
          <form action="{{ route('payment') }}" method="POST" >
          @csrf
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="firstName">First name</label>
                <input type="text" class="form-control" id="firstName" placeholder="" value="" required="">
                <div class="invalid-feedback">
                  Valid first name is required.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastName">Last name</label>
                <input type="text" class="form-control" id="lastName" placeholder="" value="" required="">
                <div class="invalid-feedback">
                  Valid last name is required.
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="phone1">Phone </label>
              <input type="text" id="phone1" class="form-control" placeholder="">
              <div class="invalid-feedback">
              </div>
            </div>

            <div class="mb-3">
              <label for="email">Email </label>
              <input type="email" class="form-control" id="email" placeholder="">
              <div class="invalid-feedback">
                Please enter a valid email address for shipping updates.
              </div>
            </div>

            <div class="mb-3">
              <label for="address">Address</label>
              <input type="text" class="form-control" id="address" placeholder="" required="">
              <div class="invalid-feedback">
                Please enter your billing address.
              </div>
            </div>

            <div class="mb-3">
              <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
              <input type="text" class="form-control" id="address2" placeholder="Apartment or suite">
            </div>

            <div class="row">
              <div class="col-md-5 mb-3">
                <label for="country">Country</label>
                <select class="custom-select d-block w-100" id="country" required="">
                  <option value="">Choose...</option>
                  @foreach($countries as $country) 
                  <option>{{ $country->name }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">
                  Please select a valid country.
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <label for="state">State</label>
                <input type="text" class="form-control" name="state" id="state" placeholder="" required="">
                <div class="invalid-feedback">
                  Please provide a valid state.
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <label for="zip">Zip</label>
                <input type="text" class="form-control" id="zip" placeholder="" required="">
                <div class="invalid-feedback">
                  Zip code required.
                </div>
              </div>
            </div>
          
            <hr class="mb-4">
           

          </form>
          <button id="rzp-button">Authenticate</button>
          <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
          <script>
            var options = {
              "key": "rzp_test_MZOe2gmaqBb32K",
              "subscription_id": "sub_G8uvHqCQUG98Vd",
              "name": "My Billing Label",
              "description": "Auth txn for sub_G8uvHqCQUG98Vd",
              "handler": function (response){
                alert(response.razorpay_payment_id);
              }
            };
            var rzp1 = new Razorpay(options);
            document.getElementById('rzp-button').onclick = function(e){
              rzp1.open();
            }
          </script>
          
        </div>
      </div>

      <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">© 2020 BCD</p>
        <ul class="list-inline">
          <li class="list-inline-item"><a href="#">Privacy</a></li>
          <li class="list-inline-item"><a href="#">Terms</a></li>
          <li class="list-inline-item"><a href="#">Support</a></li>
        </ul>
      </footer>
    </div>


</main>
<!-- End #main -->

@endsection
