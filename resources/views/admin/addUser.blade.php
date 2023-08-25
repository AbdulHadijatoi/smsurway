@extends('layouts.header')
@section('title',@get_setting('signup_title')->value)
@section('meta_name',@get_setting('signup_meta_title')->value)
@section('meta_description',@get_setting('signup_meta_description')->value)
@section('canonical', url('register'))
@section('content')     
    <div class="col-lg-4">                            
        <div class="card">
            <div class="header lead text-primary">
                Add New User
            </div>
            <div class="body">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class='alert alert-danger'>{{$error}}</div>
                    @endforeach
                @endif
                @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                <form class="form-auth-small" method="POST" action="{{ route('register-post') }}">
                    @csrf
                    <div class="form-group">
                        <label for="Full-name" class="control-label sr-only">Full Name*</label>
                        <input type="text" class="form-control" id="name"  name="name" value="{{old('name')}}"  placeholder="Full Name" required>
                    </div>
                    <div class="form-group">
                        <label for="Username" class="control-label sr-only">User Name*</label>
                        <input type="text" class="form-control" id="email"  name="username" value="{{old('username')}}"  placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <label for="Email" class="control-label sr-only">Email*</label>
                        <input type="email" class="form-control" id="signup-email" name="email" value="{{old('email')}}" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <label for="Mobile" class="control-label sr-only">Mobile*</label>
                        <!-- pattern="[+][0-9]{2} ?[0-9]{3} ?[0-9]{7}" -->
                        <input type="tel" class="form-control" id="mobile" name="mobile" value="{{old('mobile')}}" placeholder="Mobile Number" required>
                    </div>
                    <div class="form-group">
                        <label for="Password" class="control-label sr-only">Password*</label>
                        <input type="password" class="form-control" type="password"
                                name="password" id="password"
                                autocomplete="new-password"  placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <label for="Retype password" class="control-label sr-only">Retype Password*</label>
                        <input type="password" class="form-control" type="password"
                                name="password_confirmation" id="password_confirmation "
                                autocomplete="new-password"  placeholder="Retyped Password" required>
                    </div>
                    <div class="input-group mb-3">
                        <label for="country" class="control-label sr-only">Country*</label>
                        <select class="custom-select" id="inputGroupSelect01" name="country" required>
                            <option readonly>Choose country</option>
                            <option value="pakistan">Pakistan</option>
                            <option value="nigeria" selected>Nigeria</option>
                            <option value="uk">UK</option>
                            <option value="us">US</option>
                        </select>
                    </div>
                    <label class="fancy-checkbox">
                        <input type="checkbox" name="checkbox" required data-parsley-errors-container="#error-checkbox" checked required>
                        <span>By creating an account, you agree to the &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#"> Terms of Service </a></span>
                    </label>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">REGISTER</button>                                
                </form>
            </div>
        </div>
    </div>
    @section('JavaScript')
        <!-- Meta Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '1722568754747138');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1722568754747138&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->
    @endsection
@endsection
