@extends('layouts.header')
@section('content')
    <div class="col-lg-4 mt-5">
        <div class="card mt-5">
            <div class="header">
                <p class="lead">Verify your Email</p>
            </div>
            <div class="body">
                <div class="mb-4 text-sm text-gray-600">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </div>
                <h5>Note: If You donot get mail please check your spam folder.</h5>
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
    
                    <div>
                        <button class="btn btn-primary btn-lg btn-block">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </div>
                </form>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
    
                    <button type="submit" class="btn btn-danger btn-block hover:text-gray-900 mt-2">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection