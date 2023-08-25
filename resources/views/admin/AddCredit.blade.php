@extends('layouts.TableHeader')
@section('content')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Manage User Credit</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">Manage User Credit</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="card">
                        <div class="header">
                            <h2>Manage User Credit</h2>
                        </div>
                        <div class="body">
                            <div class="row">
                                <div class="col-8">
                                    @if(session()->has('success'))
                                        <div class="alert alert-success">
                                            {{ session()->get('success') }}
                                        </div>
                                    @endif
                                    <form class="form-auth-small" method="POST" action="{{ route('UpdateCredit', $user->id) }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="Username" class="control-label">Username*</label>
                                            <input type="text" class="form-control" id="username"  name="username" value="{{ $user ->username }}"  placeholder="Username *" required readonly>
                                        </div>
                                        {{-- <div class="form-group">
                                            <label for="Username" class="control-label">Unit Price*</label>
                                            <input type="text" class="form-control" id="username"  name="credit_price" value="{{ $user ->credit_price }}"  placeholder="Credit Price *" required>
                                        </div> --}}
                                        <div class="form-group">
                                            <label for="Full-name" class="control-label">Account Credit*</label>
                                            <input type="text" class="form-control" id="name"  name="credit" value="{{ $user ->credit }}"  placeholder="Account Credit *" required readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="Full-name" class="control-label">Add Credit*</label>
                                            <input type="text" class="form-control" id="name"  name="addcredit" value=""  placeholder="Add Credit *" required>
                                        </div>
                                        <button type="submit" class="btn btn-info btn-lg btn-block">Update Credit</button>                                
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
@endsection