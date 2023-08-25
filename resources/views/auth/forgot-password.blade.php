@extends('layouts.header')
@section('content') 
    <div class="col-lg-4 mt-5">
        <div class="card mt-5">
            <div class="header">
                <p class="lead">Enter your Email</p>
            </div>
            <div class="body">
                @if(session()->has('status'))
                    <div class="alert alert-info">
                        {{ session()->get('status') }}
                    </div>
                @endif
                <form class="form-auth-small" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group">
                        <label for="signin-email" class="control-label sr-only">Email</label>
                        <input type="email" class="form-control" name="email" id="signin-email"  placeholder="Email">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Email Password Reset Link</button>
                </form>
            </div>
        </div>
    </div>   
@endsection