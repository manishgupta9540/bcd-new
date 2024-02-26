@extends('layouts.app')
@section('content')

<!-- ======= Hero Section ======= -->
  <main id="main">

    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing mt-100">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Pricing</h2>
          <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
        </div>

        <div class="row">

        @foreach($package as $item)

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="box">
              <h3>{{ $item->name }}</h3>
              <h4><sup>{{ $item->currency }}</sup>{{ $item->price }}<span>per month</span></h4>
              <ul>
                <li><i class="bx bx-check"></i> Quam adipiscing vitae proin</li>
                <li><i class="bx bx-check"></i> Nec feugiat nisl pretium</li>
                <li><i class="bx bx-check"></i> Nulla at volutpat diam uteera</li>
                <li class="na"><i class="bx bx-x"></i> <span>Pharetra massa massa ultricies</span></li>
                <li class="na"><i class="bx bx-x"></i> <span>Massa ultricies mi quis hendrerit</span></li>
              </ul>
              <a href="{{ url('/checkout/?id='.$item->id) }}" class="buy-btn">Get Started</a>
            </div>
          </div>

        @endforeach

        </div>

      </div>
    </section><!-- End Pricing Section -->
   
  </main><!-- End #main -->

  @endsection