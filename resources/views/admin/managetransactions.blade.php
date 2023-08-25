@extends('layouts.TableHeader')
@section('title','Manage Transaction Requests')
@section('content')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Transactions List</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">Transactions List</li>
                    </ul>
                    {{-- <a href="#addkeyword" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addkeyword" title="click here for add new Transactions">
                        Add New
                    </a> --}}
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>All Transactions </h2>
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
                                <table class="table table-bordered table-hover js-basic-example dataTable table-custom" style="text-transform: capitalize;">
                                    <thead>
                                        <tr>
                                            <th>Sr #</th>
                                            <th>Userame</th>
                                            <th>Media</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @foreach($transaction as $u)
                                        
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <!--<td> {{$u->user_id}}</td>-->
                                                <td> {{ App\Models\User::where('id',$u->user_id)->first()->username}}</td>
                                                <td>
                                                       <a href="{{asset('storage/'.$u->media)}}" target="_blank" data-toggle="tooltip" data-placement="top" title="Click for view"> 
                                                       <img src="{{asset('storage/'.$u->media)}}" alt="Transaction Media" height="100" width="100">
                                                       </a>
                                                </td>
                                                <td> {{$u->amount}}</td>
                                                <td>
                                                    <a onclick="confirm('Are you sure to approve it.')" class="btn icon-menu" href="{{$u->id}}" id="approve" title="Approve It" type="button" data-id="{{$u->id}}" ><i class="fa fa-check"></i></a>
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
        $(document).on('click', '#approve', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log("Clicked Id is "+id);
            $.ajax({
                method: "POST",
                url: "{{ route('approvetransactions') }}",
                data: {id:id,'_token':"{{ csrf_token() }}"},
                success: function (data) {
                    // alert('success');
                    window.location.reload();
                },
                error: function (request, status, error){
                    console.log(status);
                    console.log(error);
                }         
            });
        });
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
            function(){
                $.ajax({
                    method: "POST",
                    url: "{{ route('delkeyword') }}",
                    data: {id:id,'_token':"{{ csrf_token() }}"},
                    success: function (data) {
                        // alert('success');
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