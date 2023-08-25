@extends('layouts.header')
    <!-- WRAPPER -->
    <div id="wrapper" class="auth-main">
        @include('layouts.logNav')
        <div class="container">
                <div class="row clearfix">
                    <div class="col-12">
                        <nav class="navbar navbar-expand-lg">
                            <a class="navbar-brand" href="javascript:void(0);"><img src="{{ asset('assets/images/logo.jpg') }}" width="80" height="80" class="d-inline-block align-top mr-2" alt=""></a>
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="nav-link" href="javascript:void(0);">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Sign In</a></li>
                            </ul>
                        </nav>                    
                    </div>
                    <div class="col-lg-8">
                        <div class="auth_detail">
                            <h2 class="text-monospace">
                                SMS UR WAY<br> Reset Password
                                <div id="carouselExampleControls" class="carousel vert slide" data-ride="carousel" data-interval="1500">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">Admin</div>
                                        <div class="carousel-item">Reseller</div>
                                        <div class="carousel-item">User</div>
                                    </div>
                                </div>
                            </h2>
                            <p>Welcome to SMS UR WAY corporate SMS platform. Only approved Sender IDs can send messages here. Click here to send promotional messages without Sender ID registration.</p>
                            <ul class="social-links list-unstyled">
                                <li><a class="btn btn-default" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="facebook"><i class="fa fa-facebook"></i></a></li>
                                <li><a class="btn btn-default" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="twitter"><i class="fa fa-twitter"></i></a></li>
                                <li><a class="btn btn-default" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="instagram"><i class="fa fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4" style="z-index:999999">
                        <div class="card">
                            <div class="header">
                                <h3 class="lead" style="font-size:20px">Reset Password</h3>
                            </div>
                            <div class="body">
                                @if(session()->has('success'))
                                    <div class="alert alert-info">
                                        {{ session()->get('success') }}
                                    </div>
                                @endif  
                                <form class="form-auth-small" method="POST" action="{{ route('password.update') }}">
                                    @csrf
                                     <!-- Password Reset Token -->
                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                    <input type="hidden" name="email" value="{{ $request->email }}">
                                    
                                    <div class="form-group">
                                        <label for="signin-password" class="control-label sr-only">Password</label>
                                        <input type="password" class="form-control" name="password" id="password" required placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <label for="signin-password" class="control-label sr-only">Password</label>
                                        <input type="password" class="form-control" name="password_confirmation" required id="password_confirmation"  placeholder="Password Confirmation">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __('Reset Password') }}</button>
                                </form>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <!-- END WRAPPER -->
    @extends('layouts.footer')