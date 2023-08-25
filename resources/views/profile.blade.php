@extends('layouts.TableHeader')
@section('title','Edit Profile')
@section('content')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Edit Profile</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">Edit Profile</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2>Edit Profile</h2>
                        </div>
                        <div class="body">
                            <div class="row">
                                <div class="col-12">
                                    @if(session()->has('success'))
                                        <div class="alert alert-success">
                                            {{ session()->get('success') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                    @if(session()->has('error'))
                                        <div class="alert alert-danger">
                                            {{ session()->get('error') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-4">
                                    
                                    <form class="form-auth-small" method="POST" action="{{ route('profile-update') }}"  enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="Username" class="control-label sr-only">User Name*</label>
                                            <input type="text" class="form-control" id="username"  name="username" value="{{ auth()->user()->username }}"  placeholder="Username" required readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="Email" class="control-label sr-only">Email*</label>
                                            <input type="email" class="form-control" id="signup-email" name="email" value="{{ auth()->user()->email }}" placeholder="Your Email" required readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="Full-name" class="control-label sr-only">Full Name*</label>
                                            <input type="text" class="form-control" id="name"  name="name" value="{{ auth()->user()->name }}"  placeholder="Full Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Mobile" class="control-label sr-only">Mobile*</label>
                                            <input type="tel" class="form-control" id="mobile" name="mobile" value="{{ auth()->user()->mobile }}" placeholder="Mobile Number" required>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="country" class="control-label sr-only">Country*</label>
                                            <select class="custom-select" id="inputGroupSelect01" name="country" required>
                                                <option readonly>Choose country</option>
                                                <option value="pakistan" {{ (auth()->user()->country) == 'pakistan' ? 'selected' : '' }}>Pakistan</option>
                                                <option value="nigeria" {{ (auth()->user()->country) == 'nigeria' ? 'selected' : '' }}>Nigeria</option>
                                                <option value="uk" {{ (auth()->user()->country) == 'uk' ? 'selected' : '' }}>UK</option>
                                                <option value="us" {{ (auth()->user()->country) == 'us' ? 'selected' : '' }}>US</option>
                                            </select>
                                        </div>
                                        @if(auth()->user()->role=='admin')
                                            <div class="input-group mb-3">
                                                <label for="role" class="control-label sr-only">Role*</label>
                                                <select class="custom-select" id="inputGroupSelect01" name="role" required>
                                                    <option readonly>Choose Role</option>
                                                    <option value="reseller" selected>Reseller</option>
                                                    <option value="user">User</option>
                                                </select>
                                            </div>
                                        @endif
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Update</button>                                
                                    </form>
                                </div>
                                <div class="col-4" style="border-left: 2px solid gray">
                                    @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <div class='alert alert-danger'>{{$error}}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            
                                        @endforeach
                                    @endif
                                    @if(session('msg_currentpassword'))
                                        <div class="alert alert-danger">
                                            {{ session('msg_currentpassword') }}
                                        </div>
                                    @endif
                                    <form class="form-auth-small" method="POST" action="{{ route('changePassword') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="Password" class="control-label sr-only">Current Password*</label>
                                            <input type="password" class="form-control"
                                                    name="currentpassword" id="currentpassword"
                                                    autocomplete="new-password"  placeholder="Current Password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Password" class="control-label sr-only">New Password*</label>
                                            <input type="password" class="form-control"
                                                    name="password" id="password"
                                                    autocomplete="new-password"  placeholder="New Password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Retype password" class="control-label sr-only">Retype Password*</label>
                                            <input type="password" class="form-control"
                                                    name="password_confirmation" id="password_confirmation "
                                                    autocomplete="new-password"  placeholder="Retyped Password" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Update</button>                                
                                    </form>
                                </div>
                                @if (auth()->user()->role =='reseller')
                                    <div class="col-4">
                                        <form class="form-auth-small" method="POST" action="{{ route('resellerLogo') }}"  enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="Logo" class="control-label">Current Logo</label>
                                                <br>
                                                @if($resellerLogo=='default')
                                                <img src="{{ asset('assets/images/logo.jpg') }}" alt="SMS UR WAY Logo" class="img-fluid logo" style="width: 65px;">
                                                @else
                                                <img src="{{asset('storage/'.$resellerLogo->logo)}}" alt="Reseller Logo" class="img-fluid logo" style="max-width: 200px;">
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="Update-Logo" class="control-label sr-only">Current Logo</label>
                                                <input type="file" class="form-control" name="image" id="image" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-lg btn-block">Update</button>                                
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>     
            </div>  
        </div>
    </div>
@endsection