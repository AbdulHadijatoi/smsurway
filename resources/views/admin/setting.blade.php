@extends('layouts.TableHeader')
@section('title','Manage Setting')
@section('content')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Setting List</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">Setting List</li>
                    </ul>
                    <a href="#addkeyword" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addkeyword" title="click here for add new Setting">
                        Add New
                    </a>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>All Setting </h2>
                            <ul class="header-dropdown dropdown dropdown-animated scale-left">
                                <li> <a href="javascript:void(0);" data-toggle="cardloading" data-loading-effect="pulse"><i class="icon-refresh"></i></a></li>
                                <li><a href="javascript:void(0);" class="full-screen"><i class="icon-size-fullscreen"></i></a></li>
                            </ul>
                        </div>
                        <div class="body">
                            @if(session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session()->get('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    {{ session()->get('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-6">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover js-basic-example dataTable table-custom">
                                    <thead>
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Name</th>
                                            <th>Value</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @foreach($setting as $u)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td> 
                                                <td> {{$u->name}}</td> 
                                                <td> {{$u->value}}</td> 
                                                <td> 
                                                    <a href="#updateSetting{{$u->id}}" class="icon-menu updateNetwork" data-toggle="modal" data-target="#updateSetting{{$u->id}}" data-id="{{$u->id}}" title="Edit Setting"><i class="fa fa-edit"></i></a>
                                                </td>
                                            </tr>
                                            {{-- Update Setting Modal --}}
                                                <div class="modal fade" id="updateSetting{{$u->id}}" tabindex="-1" role="dialog" aria-labelledby="gsmModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="gsmModalLabel">Update Setting  </h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body" id="updateModel">
                                                                <form method="POST" action="{{ route('updateSetting') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="id" id="id" value="{{$u->id}}" required/>
                                                                    <div class="form-group row">
                                                                    <label for="inputEmail3" class="col-sm-4 col-form-label">  Name</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="name" id="name" value="{{$u->name}}" required>
                                                                    </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                    <label for="inputPassword3" class="col-sm-4 col-form-label">  Value</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="value" id="value" value="{{$u->value}}" required>
                                                                    </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                    <div class="col-sm-12 align-right">
                                                                        <button type="submit" class="btn btn-lg btn-success">Update</button>
                                                                    </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
  
    <!-- Add Setting Modal -->
        <div class="modal fade" id="addkeyword" tabindex="-1" role="dialog" aria-labelledby="gsmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="gsmModalLabel">Add Setting</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('addsetting') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" required>
                                </div>
                            </div>
                            @if (auth()->user()->role =='reseller')
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label">Parameter</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="key" id="key" required>
                                            <option value="" readonly>--Select --</option>
                                            <option value="flutterwave_public_key">Flutterwave Public Key</option>
                                            <option value="flutterwave_secret_key">Flutterwave Secret Key</option>
                                        </select>
                                    </div>
                                </div>
                            @else
                            <input type="hidden" class="form-control" name="key" id="key" required>
                                
                            @endif
                            
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Value</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="value" id="value" placeholder="Enter Value" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12 align-right">
                                    <button type="submit" class="btn btn-lg btn-primary">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    
    @section('JavaScript')

    @endsection
@endsection