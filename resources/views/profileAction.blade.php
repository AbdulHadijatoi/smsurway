@extends('layouts.TableHeader')
@section('content')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Update User</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">Update User</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2>Update User</h2>
                        </div>
                        <div class="body">
                            <div class="row">
                                <div class="col-5">
                                    @if(session()->has('success'))
                                        <div class="alert alert-success">
                                            {{ session()->get('success') }}
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
                                    <form class="form-auth-small" method="POST" action="{{ route('userAction', $user->id) }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="Username" class="control-label sr-only">User Name*</label>
                                            <input type="text" class="form-control" id="username"  name="username" value="{{ $user->username }}"  placeholder="Username" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Full-name" class="control-label sr-only">Full Name*</label>
                                            <input type="text" class="form-control" id="name"  name="name" value="{{ $user->name }}"  placeholder="Full Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Mobile" class="control-label sr-only">Mobile*</label>
                                            <!-- pattern="[+][0-9]{2} ?[0-9]{3} ?[0-9]{7}" -->
                                            <input type="tel" class="form-control" id="mobile" name="mobile" value="{{ $user->mobile }}" placeholder="Mobile Number" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Email" class="control-label sr-only">Email*</label>
                                            <input type="email" class="form-control" id="signup-email" name="email" value="{{ $user->email }}" placeholder="Your Email" required>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="country" class="control-label sr-only">Country*</label>
                                            <select class="custom-select" id="inputGroupSelect01" name="country" required>
                                                <option readonly>Choose country</option>
                                                {{-- <option value="{{ $user->country }}">{{ echo $user->country }}</option> --}}
                                                <option value="pk">Pakistan</option>
                                                <option value="ngn" selected>Nigeria</option>
                                                <option value="uk">UK</option>
                                                <option value="us">US</option>
                                            </select>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="role" class="control-label sr-only">Role*</label>
                                            <select class="custom-select" id="inputGroupSelect01" name="role" required>
                                                <option readonly>Choose Role</option>
                                                <option value="reseller" @if($user->role == 'reseller') selected @endif>Reseller</option>
                                                <option value="user" @if($user->role == 'user') selected @endif>User</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="per_sms_bill" class="control-label sr-only">Per SMS Bill</label>
                                            <input type="number" class="form-control" id="per_sms_bill" min="0.0" step="any" name="per_sms_bill" value="{{ $user->per_sms_bill }}" placeholder="Per SMS Bill">
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="status" class="control-label sr-only">Status*</label>
                                            <select class="custom-select" id="inputGroupSelect01" name="status" required>
                                                <option readonly>Choose Status</option>
                                                <option value="active" selected>Active</option>
                                                <option value="block">Block</option>
                                                <option value="deactive">Deactive</option>
                                            </select>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="senderIds" class="control-label sr-only">SenderIDs</label>
                                            <div class="w-100">
                                                <textarea class="form-control" id="senderIds" name="senderIds" rows="3" placeholder="SenderIDs">{{$senderIds}}</textarea>
                                            </div>
                                            <p class="text-muted"><b class="text-danger">Note:</b> Enter new sender_id in a new line.</p>     
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Update</button>                                
                                    </form>
                                </div>
                                <div class="col-5" style="border-left: 2px solid gray">
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection