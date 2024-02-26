<?php include "header.php" ?>
<style>
  #header{
    display:none;
  }
</style>
  <main id="main">
  <section class="full-page">
    <div class="side-beta-image">
    <h1 class="logo mylogo">BCD</h1>
      <img src="assets/img/official.jpg" class="img-fluida">
    </div>
    <div class="main-section">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-10 offset-1">
            <form action="forms/contact.php" method="post" role="form" class="php-email-form login-form login-do">
                <!-- <h1 class="logo text-center">BCD</h1>  -->
            <!-- <img class="logo" src=""> -->
                <h3 class="heading-form text-center">Login Your Account</h3>
                
            
                  <div class="form-group">
                    <label for="name">Your Email</label>
                    <input type="email" class="form-control" name="email" id="email" data-rule="email" placeholder="Your Email" data-msg="Please enter a valid email" />
                    <div class="validate"></div>
                  </div>
                 
                  
                  <div class="form-group">
                    <label for="name">Password</label>
                    <input type="password" class="form-control" name="subject" id="subject" data-rule="minlen:4" placeholder="Password" data-msg="Please enter at least 8 chars of subject" />
                    <div class="validate"></div>
                  </div>
                  
                 
              <div class="text-center mt-30"><button type="submit" class="btn-submit">Login</button></div>
              
              <ul class="listingers">
                <li><p class="members">New Member <a href="signup.php">Signup Here</a></p></li>
                <li class="righty"><p><a href="#">Forgot Password</a></p></li>
              </ul>
            
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->

  <?php include "footer.php" ?>
