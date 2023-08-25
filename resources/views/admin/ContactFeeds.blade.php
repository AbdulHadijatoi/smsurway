
@extends('layouts.TableHeader')
@section('title','Contact Feeds')
@section('content')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Contact Feeds</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">Contact Feeds</li>
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
                            <h2>All Feeds 
                            </h2>
                            <ul class="header-dropdown dropdown dropdown-animated scale-left">
                                <li> <a href="javascript:void(0);" data-toggle="cardloading" data-loading-effect="pulse"><i class="icon-refresh"></i></a></li>
                                <li><a href="javascript:void(0);" class="full-screen"><i class="icon-size-fullscreen"></i></a></li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover js-basic-example dataTable table-custom" style="text-transform: capitalize;">
                                    <thead>
                                        <tr>
                                            <th>Sr #</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Company</th>
                                            <th>Contact</th>
                                            <th>Comment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($feeds as $feed)
                                            <tr>
                                                <td> {{$loop->iteration}} </td>
                                                <td> {{$feed->name}} </td>
                                                <td> {{$feed->email}} </td>
                                                <td> {{$feed->company}} </td>
                                                <td> {{$feed->contact}} </td>
                                                <td> {{$feed->comment}} </td>
                                                <td>
                                                    {{-- <a href="{{url('AddCredit/'.$feed->id)}}" class="icon-menu" title="Manage Profile"><i class="icon-arrow-right"></i></a> --}}
                                                    <a class="btn icon-menu" id="del" title="Delete" type="button" data-id="{{$feed->id}}"><i class="fa fa-trash-o"></i></a>
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
                        url: "{{ route('delFeed') }}",
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
    @endsection
@endsection