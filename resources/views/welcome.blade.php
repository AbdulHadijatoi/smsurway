<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>SMS Your Way </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="all,follow">

        <!-- Bootstrap CSS-->
        <link rel="stylesheet" href="{{ asset('front/vendor/bootstrap/css/bootstrap.min.css') }}">
        <!-- Font Awesome CSS-->
        <link rel="stylesheet" href="{{ asset('front/vendor/font-awesome/css/font-awesome.min.css') }}">
        <!-- Custom Font Icons CSS-->
        <link rel="stylesheet" href="{{ asset('front/css/landy-iconfont.css') }}">
        <!-- Google fonts - Open Sans-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800">
        <!-- owl carousel-->
        <link rel="stylesheet" href="{{ asset('front/vendor/owl.carousel/assets/owl.carousel.css') }}">
        <link rel="stylesheet" href="{{ asset('front/vendor/owl.carousel/assets/owl.theme.default.css') }}">
        <!-- theme stylesheet-->
        <link rel="stylesheet" href="{{ asset('front/css/style.default.css') }}" id="theme-stylesheet">
        <!-- Custom stylesheet - for your changes-->
        <link rel="stylesheet" href="{{ asset('front/css/custom.css') }}">
        <link rel="icon" href="{{ asset('assets/images/logo.jpg') }}" type="image/x-icon">
        <!-- Tweaks for older IEs--><!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
      </head>
    <body class="antialiased">
        
         <!-- navbar-->
    <header class="header">
        <nav class="navbar navbar-expand-lg fixed-top">
            <a href="{{ route('logout') }}" class="navbar-brand">
                <img src="{{ asset('front/img/logo.png') }}" alt="Site Logo" class="img-fluid" style="width: 90px;">
            </a>
            <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler navbar-toggler-right">
              <span></span><span></span><span></span>
            </button>
            <div id="navbarSupportedContent" class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto align-items-start align-items-lg-center">
                <li class="nav-item"><a href="#about-us" class="nav-link link-scroll">About Us</a></li>
                <li class="nav-item"><a href="#features" class="nav-link link-scroll">Features</a></li>
                <li class="nav-item"><a href="#testimonials" class="nav-link link-scroll">Testimonials</a></li>
                <li class="nav-item">
                  <div class="navbar-text">   
                    @if (Route::has('login'))
                        @auth
                            @if(auth()->user()->role == 'admin')
                            <a href="{{ url('/home') }}" class="btn btn-primary navbar-btn btn-shadow btn-gradient">Dashboard</a>
                            @else
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary navbar-btn btn-shadow btn-gradient">Dashboard</a>
                            @endif
                            
                                {{--  --}}
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary navbar-btn btn-shadow btn-gradient">Log in</a>

                            @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary navbar-btn btn-shadow btn-gradient">
                                Sign Up
                            </a>
                        @endif
                        @endauth
                    @endif
                </div>
                </li>
                @auth
                  @if(auth()->user()->id)
                    <li class="nav-item">
                      <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="nav-link link-scroll" onclick="event.preventDefault();
                            this.closest('form').submit();">
                            <i class="icon-power"></i>Logout
                        </a>
                      </form>
                    </li>
                  @endif
                @endauth
                </ul>
                
            </div>
        </nav>
      </header>
      
      <section id="hero" class="hero hero-home bg-gray">
        <div class="container">
          <div class="row d-flex">
            <div class="col-lg-6 text order-2 order-lg-1">
                <h1>Sms Your Way &mdash; Landing page</h1>
                <p class="hero-text">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour</p>
                <div class="CTA">
                    <a href="#features" class="btn btn-primary btn-shadow btn-gradient link-scroll">Discover More</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">Sign Up Now</a>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-2">
                <img src="{{ asset('front/img/Macbook.png') }}" alt="..." class="img-fluid">
            </div>
          </div>
        </div>
      </section>
      <section id="browser" class="browser">
        <div class="container">
          <div class="row d-flex justify-content-center"> 
            <div class="col-lg-8 text-center">
              <h2 class="h3 mb-5">How it works</h2>
              <div class="browser-mockup">
                <div id="nav-tabContent" class="tab-content">
                  <div id="nav-first" role="tabpanel" aria-labelledby="nav-first-tab" class="tab-pane fade show active"><img src="{{ asset('front/img/preview-3.png') }}" alt="..." class="img-fluid"></div>
                  <div id="nav-second" role="tabpanel" aria-labelledby="nav-second-tab" class="tab-pane fade"><img src="{{ asset('front/img/preview-2.png') }}" alt="..." class="img-fluid"></div>
                  <div id="nav-third" role="tabpanel" aria-labelledby="nav-third-tab" class="tab-pane fade"><img src="{{ asset('front/img/preview-1.png') }}" alt="..." class="img-fluid"></div>
                </div>
              </div>
            </div>
          </div>
          <div id="myTab" role="tablist" class="nav nav-tabs">
            <div class="row">
              <div class="col-md-4"><a id="nav-first-tab" data-toggle="tab" href="#nav-first" role="tab" aria-controls="nav-first" aria-expanded="true" class="nav-item nav-link active"> <span class="number">1</span>Choose any website to turn into an interactive pinboard for feedback</a></div>
              <div class="col-md-4"><a id="nav-second-tab" data-toggle="tab" href="#nav-second" role="tab" aria-controls="nav-second" class="nav-item nav-link"> <span class="number">2</span>Choose any website to turn into an interactive pinboard for feedback</a></div>
              <div class="col-md-4"><a id="nav-third-tab" data-toggle="tab" href="#nav-third" role="tab" aria-controls="nav-third" class="nav-item nav-link"> <span class="number">3</span>Choose any website to turn into an interactive pinboard for feedback</a></div>
            </div>
          </div>
        </div>
      </section>
      <section id="about-us" class="about-us bg-gray">
        <div class="container">
          <h2>About Us</h2>
          <div class="row">
            <p class="lead col-lg-10">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. or randomised words which don't look even slightly believable. </p>
          </div><a href="#" class="btn btn-primary btn-shadow btn-gradient">Discover More</a>
        </div>
      </section>
      <section id="features" class="features">
        <div class="container">
          <div class="row d-flex align-items-center">
            <div class="text col-lg-6 order-2 order-lg-1">
              <div class="icon"><img src="{{ asset('front/img/medal.svg') }}" alt="..." class="img-fluid"></div>
              <h4>Your peace of mind is our business</h4>
              <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. </p><a href="#" class="btn btn-primary btn-shadow btn-gradient">View More</a>
            </div>
            <div class="image col-lg-6 order-1 order-lg-2"><img src="{{ asset('front/img/feature-1.png') }}" alt="..." class="img-fluid"></div>
          </div>
          <div class="row d-flex align-items-center">
            <div class="image col-lg-6"><img src="{{ asset('front/img/feature-2.png') }}" alt="..." class="img-fluid"></div>
            <div class="text col-lg-6">
              <div class="icon"><img src="{{ asset('front/img/hourglass.svg') }}" alt="..." class="img-fluid"></div>
              <h4>Your peace of mind is our business</h4>
              <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. </p><a href="#" class="btn btn-primary btn-shadow btn-gradient">View More</a>
            </div>
          </div>
          <div class="row d-flex align-items-center">
            <div class="text col-lg-6 order-2 order-lg-1">
              <div class="icon"><img src="{{ asset('front/img/cup.svg') }}" alt="..." class="img-fluid"></div>
              <h4>Your peace of mind is our business</h4>
              <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. </p><a href="#" class="btn btn-primary btn-shadow btn-gradient">View More</a>
            </div>
            <div class="image col-lg-6 order-1 order-lg-2"><img src="{{ asset('front/img/feature-3.png') }}" alt="..." class="img-fluid"></div>
          </div>
        </div>
      </section>
      <section id="extra-features" class="extra-features bg-primary">
        <div class="container text-center">
          <header>
            <h2>More great features             </h2>
            <div class="row">
              <p class="lead col-lg-8 mx-auto">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</p>
            </div>
          </header>
          <div class="grid row">
            <div class="item col-lg-4 col-md-6">
              <div class="icon"> <i class="icon-diploma"></i></div>
              <h3 class="h5">Lorem Ipsum Dolor</h3>
              <p>Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
            </div>
            <div class="item col-lg-4 col-md-6">
              <div class="icon"> <i class="icon-folder-1"></i></div>
              <h3 class="h5">Lorem Ipsum Dolor</h3>
              <p>Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
            </div>
            <div class="item col-lg-4 col-md-6">
              <div class="icon"> <i class="icon-gears"></i></div>
              <h3 class="h5">Lorem Ipsum Dolor</h3>
              <p>Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
            </div>
            <div class="item col-lg-4 col-md-6">
              <div class="icon"> <i class="icon-management"></i></div>
              <h3 class="h5">Lorem Ipsum Dolor</h3>
              <p>Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
            </div>
            <div class="item col-lg-4 col-md-6">
              <div class="icon"> <i class="icon-pie-chart"></i></div>
              <h3 class="h5">Lorem Ipsum Dolor</h3>
              <p>Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
            </div>
            <div class="item col-lg-4 col-md-6">
              <div class="icon"> <i class="icon-quality"></i></div>
              <h3 class="h5">Lorem Ipsum Dolor</h3>
              <p>Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
            </div>
          </div>
        </div>
      </section>
      <section id="testimonials" class="testimonials">
        <div class="container">
          <header class="text-center no-margin-bottom">   
            <h2>Happy Clients</h2>
            <p class="lead">There are many variations of passages of Lorem Ipsum available, but the majority have</p>
          </header>
          <div class="owl-carousel owl-theme testimonials-slider"> 
            <div class="item-holder">
              <div class="item">
                <div class="avatar"><img src="{{ asset('front/img/avatar-3.jpg') }}" alt="..." class="img-fluid"></div>
                <div class="text">
                  <div class="quote"><img src="{{ asset('front/img/quote.svg') }}" alt="..." class="img-fluid"></div>
                  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever</p><strong class="name">Jessica Watson</strong>
                </div>
              </div>
            </div>
            <div class="item-holder">
              <div class="item">
                <div class="avatar"><img src="{{ asset('front/img/avatar-5.jpg') }}" alt="..." class="img-fluid"></div>
                <div class="text">
                  <div class="quote"><img src="{{ asset('front/img/quote.svg') }}" alt="..." class="img-fluid"></div>
                  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever</p><strong class="name">Sarrah Wood</strong>
                </div>
              </div>
            </div>
            <div class="item-holder">
              <div class="item">
                <div class="avatar"><img src="{{ asset('front/img/avatar-3.jpg') }}" alt="..." class="img-fluid"></div>
                <div class="text">
                  <div class="quote"><img src="{{ asset('front/img/quote.svg') }}" alt="..." class="img-fluid"></div>
                  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever</p><strong class="name">Jessica Watson</strong>
                </div>
              </div>
            </div>
            <div class="item-holder">
              <div class="item">
                <div class="avatar"><img src="{{ asset('front/img/avatar-5.jpg') }}" alt="..." class="img-fluid"></div>
                <div class="text">
                  <div class="quote"><img src="{{ asset('front/img/quote.svg') }}" alt="..." class="img-fluid"></div>
                  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever</p><strong class="name">Sarrah Wood</strong>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section id="newsletter" class="newsletter bg-gray">
        <div class="container text-center">
          <h2>Subscribe to Newsletter</h2>
          <p class="lead">There are many variation passages of lorem ipsum, but the majority have</p>
          <div class="form-holder">
            <form id="newsletterForm" action="#">
              <div class="form-group">
                <input type="email" name="email" id="email" placeholder="Enter Your Email Address">
                <button type="submit" class="btn btn-primary btn-gradient submit">Subscribe</button>
              </div>
            </form>
          </div>
        </div>
      </section>
      <div id="scrollTop">
        <div class="d-flex align-items-center justify-content-end"><i class="fa fa-long-arrow-up"></i>To Top</div>
      </div>
      <footer class="main-footer">
        <div class="container">
          <div class="row">
            <div class="col-lg-3 col-md-6"><a href="#" class="brand">Sms Your Way</a>
              <ul class="contact-info list-unstyled">
                <li><a href="mailto:sales@landy.com">Sales@smsyourway.com</a></li>
                <li><a href="tel:123456789">+00 123 456 789</a></li>
              </ul>
              <ul class="social-icons list-inline">
                <li class="list-inline-item"><a href="#" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a></li>
                <li class="list-inline-item"><a href="#" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a></li>
                <li class="list-inline-item"><a href="#" target="_blank" title="Instagram"><i class="fa fa-instagram"></i></a></li>
                <li class="list-inline-item"><a href="#" target="_blank" title="Pinterest"><i class="fa fa-pinterest"></i></a></li>
              </ul>
            </div>
            <div class="col-lg-3 col-md-6">
              <h5>Selected Cases</h5>
              <ul class="links list-unstyled">
                <li> <a href="#">Guess Conntect</a></li>
                <li> <a href="#">Postly</a></li>
                <li> <a href="#">Iris Vor Arnim</a></li>
                <li> <a href="#">Yapital</a></li>
              </ul>
            </div>
            <div class="col-lg-3 col-md-6">
              <h5>Selected Cases</h5>
              <ul class="links list-unstyled">
                <li> <a href="#">Guess Conntect</a></li>
                <li> <a href="#">Postly</a></li>
                <li> <a href="#">Iris Vor Arnim</a></li>
                <li> <a href="#">Yapital</a></li>
              </ul>
            </div>
            <div class="col-lg-3 col-md-6">
              <h5>Selected Cases</h5>
              <ul class="links list-unstyled">
                <li> <a href="#">Guess Conntect</a></li>
                <li> <a href="#">Postly</a></li>
                <li> <a href="#">Iris Vor Arnim</a></li>
                <li> <a href="#">Yapital</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="copyrights">
          <div class="container">
            <div class="row">
              <div class="col-md-7">
                <p>&copy; 2022 smsyourway.com. All rights reserved.                        </p>
              </div>
              <div class="col-md-5 text-right">
                <p>Powered By <a href="#" class="external">Cyborg Solutions</a>  </p>
              </div>
            </div>
          </div>
        </div>
      </footer>
      <!-- Javascript files-->
      <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"> </script>
      <script src="{{ asset('front/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
      <script src="{{ asset('front/vendor/jquery.cookie/jquery.cookie.js') }}"> </script>
      <script src="{{ asset('front/vendor/owl.carousel/owl.carousel.min.js') }}"></script>
      <script src="{{ asset('front/js/front.js') }}"></script>
      <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
      <!---->
      <script>
        (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
        function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
        e=o.createElement(i);r=o.getElementsByTagName(i)[0];
        e.src='//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
        ga('create','UA-XXXXX-X');ga('send','pageview');
      </script>
    </body>
</html>
