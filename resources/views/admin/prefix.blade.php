@extends('layouts.TableHeader')
@section('title','GSM Prefix')
@section('content')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>GSM Prefix List</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">GSM Prefix List</li>
                    </ul>
                    <a href="#addPrefix" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addPrefix" title="click here for add new GSM Network">
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
                            <h2>All GSM Prefix 
                            </h2>
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
                            <ul class="header-dropdown dropdown dropdown-animated scale-left">
                                <li> <a href="javascript:void(0);" data-toggle="cardloading" data-loading-effect="pulse"><i class="icon-refresh"></i></a></li>
                                <li><a href="javascript:void(0);" class="full-screen"><i class="icon-size-fullscreen"></i></a></li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="row">
                                <div class="col-6">
                                    {{-- <button type="button" class="btn btn-simple btn-sm mb-1 btn-default btn-filter" data-target="all">All</button>
                                    <button type="button" class="btn btn-simple btn-sm mb-1 btn-info btn-filter" data-target="reseller">Reseller</button>
                                    <button type="button" class="btn btn-simple btn-sm mb-1 btn-primary btn-filter" data-target="user">GSM Prefix</button> --}}
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover js-basic-example dataTable table-custom" style="text-transform: capitalize;">
                                    <thead>
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Network Name</th>
                                            <th>Prefix</th>
                                            <th>Action</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @foreach($prefix as $u)
                                            <tr data-status="{{$u->network_name}}">
                                                <td> {{ $loop->iteration }}</td> 
                                                <td> {{$u->network_name}}</td> 
                                                <td> {{$u->network_prefix}} </td>
                                                <td> 
                                                    <a href="#updatePrefix{{$u->id}}" class="icon-menu updateNetwork" data-toggle="modal" data-target="#updatePrefix{{$u->id}}" data-id="{{$u->id}}" title="Edit Network"><i class="fa fa-edit"></i></a>
                                                    <a class="btn icon-menu" id="del" title="Delete" type="button" data-id="{{$u->id}}"><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                            {{-- Update Prefix Modal --}}
                                                <div class="modal fade" id="updatePrefix{{$u->id}}" tabindex="-1" role="dialog" aria-labelledby="gsmModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="gsmModalLabel">Update GSM Network</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body" id="updateModel">
                                                            <form method="POST" action="{{ route('updatePrefix') }}">
                                                                @csrf
                                                                <input type="hidden" name="prefix_id" id="prefix_id" value="{{$u->id}}" required/>
                                                                <div class="form-group row">
                                                                <label for="inputEmail3" class="col-sm-4 col-form-label">Network Name</label>
                                                                <div class="col-sm-8">
                                                                    {{-- {{$prefix->network()->get()}} --}}
                                                                    <input type="text" class="form-control" name="network_name" id="network_name" value="{{$u->network_name}}" required>
                                                                </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                <label for="inputPassword3" class="col-sm-4 col-form-label">Network Prefix</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" name="network_prefix" id="network_prefix" value="{{$u->network_prefix}}" required>
                                                                </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                <div class="col-sm-12 align-right">
                                                                    <button type="submit" class="btn btn-lg btn-success">Update Prefix</button>
                                                                </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        {{-- <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <a href="" type="button" class="btn btn-primary">Save changes</a>
                                                        </div> --}}
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
    
  
    <!-- Add Prefix Modal -->
        <div class="modal fade" id="addPrefix" tabindex="-1" role="dialog" aria-labelledby="gsmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="gsmModalLabel">Add New GSM Network</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('addPrefix') }}">
                        @csrf
                        <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Network Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="network_name" id="network_name" required>
                        </div>
                        </div>
                        <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-4 col-form-label">Network Prefix</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="network_prefix" id="network_prefix" required>
                        </div>
                        </div>
                        <div class="form-group row">
                        <div class="col-sm-12 align-right" >
                            <button type="submit" class="btn btn-lg btn-primary">Add Prefix</button>
                        </div>
                        </div>
                    </form>
                </div>
                {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="" type="button" class="btn btn-primary">Save changes</a>
                </div> --}}
            </div>
            </div>
        </div>
    
    @section('JavaScript')
    <script>
        // Add Code For Get Data Target
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
                url: "{{ route('delPrefix') }}",
                data: {id:id,'_token':"{{ csrf_token() }}"},
                success: function (data) {
                    console.log('success');
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