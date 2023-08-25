@extends('layouts.header')
@section('title',@get_setting('login_title')->value)
@section('meta_name',@get_setting('login_meta_title')->value)
@section('meta_description',@get_setting('login_meta_description')->value)
@section('canonical', url('login'))
@section('content') 
    <div class="col-lg-4 mt-5">
        <div class="card">
            <div class="header">
                <p class="lead">Login to your account</p>
            </div>
            <div class="body">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class='alert alert-danger'>{{$error}}</div>
                    @endforeach
                @endif
                <form class="form-auth-small" method="post" action="{{ route('login-post') }}">
                    @csrf
                    <div class="form-group">
                        <label for="signin-email" class="control-label sr-only">Email</label>
                        <input type="email" class="form-control" name="email" id="signin-email" value="{{old('email')}}" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="signin-password" class="control-label sr-only">Password</label>
                        <input type="password" class="form-control" name="password" id="signin-password"  placeholder="Password">
                    </div>
                    <div class="form-group clearfix">
                        <label class="fancy-checkbox element-left">
                            <input type="checkbox">
                            <span>Remember me</span>
                        </label>								
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">LOGIN</button>
                    <div class="bottom">
                        <span class="helper-text m-b-10"><i class="fa fa-lock mr-1"></i><a href="{{ route('password.request') }}">Forgot password?</a></span>
                        <span>Don't have an account? <a href="{{ route('register') }}">Signup</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection