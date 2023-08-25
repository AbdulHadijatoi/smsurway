@extends('layouts.TableHeader')
@section('title','Verify Email Manually')
@section('content')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Emails List</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">Emails List</li>
                    </ul>
                    {{-- <a href="javascript:void(0);" class="btn btn-sm btn-primary" title="">Create New</a> --}}
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>All Emails 
                            </h2>
                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <ul class="header-dropdown dropdown dropdown-animated scale-left">
                                <li> <a href="javascript:void(0);" data-toggle="cardloading" data-loading-effect="pulse"><i class="icon-refresh"></i></a></li>
                                <li><a href="javascript:void(0);" class="full-screen"><i class="icon-size-fullscreen"></i></a></li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="row">
                                {{-- <div class="col-8">
                                    <button type="button" class="btn btn-simple btn-sm mb-1 btn-default btn-filter" data-target="all">All</button>
                                    <button type="button" class="btn btn-simple btn-sm mb-1 btn-info btn-filter" data-target="reseller">Reseller</button>
                                    <button type="button" class="btn btn-simple btn-sm mb-1 btn-primary btn-filter" data-target="user">Users</button>
                            
                                </div>
                                <div class="col-4"></div> --}}
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable" style="text-transform: capitalize;">
                                    <thead>
                                        <tr>
                                            <th>Sr #</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Email Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @foreach($user as $u)
                                            <tr data-status="{{$u->role}}" >
                                                <td> {{$loop->iteration}}</td>
                                                <td> {{$u->name}}</td>
                                                <td style="text-transform: none;"> {{$u->email}} </td> 
                                                <td class="text-capitalize">
                                                    @if($u->email_verified_at==null)
                                                        <span class="badge badge-danger">Unverified</span>
                                                    @else
                                                        <span class="badge badge-primary">Verified</span>
                                                    @endif
                                                </td>
                                                <td> 
                                                    <a onclick="return  confirm('Are you sure to approve it.')" href="{{url('verifyEmail/'.$u->id)}}" class="icon-menu" title="Click to Verify"><i class="fa fa-check"></i></a>
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