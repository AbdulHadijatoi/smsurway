@extends('layouts.TableHeader')
@section('title','User List')
@section('content')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Users List</h2>
                </div>     
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">Users List</li>
                    </ul>
                    <a href="#addUser" data-toggle="modal" data-target="#addUser" class="btn btn-sm btn-primary" title="">Add New User</a>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>All Users 
                            </h2>
                            <ul class="header-dropdown dropdown dropdown-animated scale-left">
                                <li> <a href="javascript:void(0);" data-toggle="cardloading" data-loading-effect="pulse"><i class="icon-refresh"></i></a></li>
                                <li><a href="javascript:void(0);" class="full-screen"><i class="icon-size-fullscreen"></i></a></li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="row">
                                @if (auth()->user()->role == 'admin')
                                    <div class="col-8">
                                        <button type="button" class="btn btn-simple btn-sm mb-1 btn-default btn-filter" data-target="all">All</button>
                                        <button type="button" class="btn btn-simple btn-sm mb-1 btn-info btn-filter" data-target="reseller">Reseller</button>
                                        <button type="button" class="btn btn-simple btn-sm mb-1 btn-primary btn-filter" data-target="user">Users</button>
                                    </div>
                                    <div class="col-4"></div>
                                @endif
                            </div>
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
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable" style="text-transform: capitalize;">
                                    <thead>
                                        <tr>
                                            <th>Sr #</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Credit</th>
                                            <th>Mobile</th>
                                            <th>Per SMS Bill</th>
                                            <th>Role/ Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @foreach($user as $u)
                                            <tr data-status="{{$u->role}}" >
                                                <td> {{$loop->iteration}}</td>
                                                <td> {{$u->name}}</td>
                                                <td style="text-transform: none;"> {{$u->email}} </td> 
                                                <td> {{$u->credit}} </td>
                                                <td> {{$u->mobile}} </td>
                                                <td> {{$u->per_sms_bill}} </td>
                                                <td class="text-capitalize">
                                                    @if($u->role=="reseller")
                                                        <span class="badge badge-info">{{$u->role}}</span>
                                                    @else
                                                        <span class="badge badge-primary">{{$u->role}}</span>
                                                    @endif
                                                    @if($u->status=="active")
                                                    <span class="badge badge-success">{{$u->status}}</span>
                                                    @else
                                                    <span class="badge badge-danger">{{$u->status}}</span>
                                                    @endif
                                                </td>
                                                <td> 
                                                    <a href="{{url('profileAction/'.$u->id)}}" class="icon-menu" title="Manage Profile"><i class="fa fa-edit"></i></a>
                                                    <a class="btn icon-menu" id="del" title="Delete" type="button" data-id="{{$u->id}}"><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add addUser Payment Modal -->
        <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="gsmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="gsmModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('addUser') }}">
                            @csrf
                            <input type="hidden" name="reseller" value="@if(auth()->user()->role=='reseller'){{auth()->user()->id}}@endif">
                            <div class="form-group">
                                <label for="Full-name" class="control-label sr-only">Full Name*</label>
                                <input type="text" class="form-control" id="name"  name="name" value="{{old('name')}}" placeholder="Full Name" required>
                            </div>
                            <div class="form-group">
                                <label for="Username" class="control-label sr-only">User Name*</label>
                                <input type="text" class="form-control" id="email" name="username" value="{{old('username')}}" placeholder="Username" required>
                            </div>
                            <div class="form-group">
                                <label for="Email" class="control-label sr-only">Email*</label>
                                <input type="email" class="form-control" id="signup-email" name="email" value="{{old('email')}}" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <label for="Mobile" class="control-label sr-only">Mobile</label>
                                <!-- pattern="[+][0-9]{2} ?[0-9]{3} ?[0-9]{7}" -->
                                <input type="tel" class="form-control" id="mobile" name="mobile" value="{{old('mobile')}}" placeholder="Mobile Number" required>
                            </div>
                            <div class="form-group">
                                <label for="Password" class="control-label sr-only">Password*</label>
                                <input type="password" class="form-control" type="password" name="password" id="password" autocomplete="new-password"  placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <label for="Retype password" class="control-label sr-only">Retype Password*</label>
                                <input type="password" class="form-control" type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"  placeholder="Retyped Password" required>
                            </div>
                            <div class="input-group mb-3">
                                <label for="country" class="control-label sr-only">Country</label>
                                <select class="custom-select" id="inputGroupSelect01" name="country" required>
                                    <option readonly>Choose country</option>
                                    <option value="pakistan">Pakistan</option>
                                    <option value="nigeria" selected>Nigeria</option>
                                    <option value="uk">UK</option>
                                    <option value="us">US</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Add New</button>                                
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('JavaScript')
        <script>
            // Add JS For Delete User
            $(document).on('click', '#del', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                console.log("Clicked Id is "+id);
                var token = $('meta[name="csrf-token"]').attr('content');
                var url = $(this).attr("data-url");
                swal({
                    title: "Are you sure!",
                    type: "error",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes!",
                    showCancelButton: true,
                },
                function() {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('delUser') }}",
                        data: {id:id,'_token':"{{ csrf_token() }}"},
                        success: function (data) {
                            // console.log('success');
                            location.reload();
                        },
                        error: function (request, status, error){
                            console.log(status);
                            console.log(error);
                        }         
                    });
                });
                
            });
            
        </script>
        <script>
            // ADD JS for filter Data.
            $(document).ready(function () {
                $('.star').on('click', function () {
                    $(this).toggleClass('star-checked');
                });

                $('.ckbox label').on('click', function () {
                    $(this).parents('tr').toggleClass('selected');
                });

                $('.btn-filter').on('click', function () {
                    var $target = $(this).data('target');
                    if ($target != 'all') {
                        $('.tbody tr').css('display', 'none');
                        $('.tbody tr[data-status="' + $target + '"]').fadeIn('slow');
                    } else {
                        $('.tbody tr').css('display', 'none').fadeIn('slow');
                    }
                });
            });
        </script> 
    @endsection
@endsection