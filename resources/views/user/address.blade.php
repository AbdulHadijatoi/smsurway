@extends('layouts.TableHeader')
@section('title','Address Book')
@section('content')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Contact Groups</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">Contact Groups</li>
                    </ul>
                    {{-- <a href="{{ route('addgroup') }}" class="btn btn-sm btn-primary" title="">Add Group</a> --}}
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addConference" href="{{ route('addgroup') }}">
                        Add Group
                    </button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>Contact Groups 
                            </h2>
                            <ul class="header-dropdown dropdown dropdown-animated scale-left">
                                <li> <a href="javascript:void(0);" data-toggle="cardloading" data-loading-effect="pulse"><i class="icon-refresh"></i></a></li>
                                <li><a href="javascript:void(0);" class="full-screen"><i class="icon-size-fullscreen"></i></a></li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover js-basic-example dataTable table-custom">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Create Date</th>
                                            <th>Group Name</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-capitalize">
                                        @foreach($address as $u)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ $u->created_at->format('d-M-Y')}} </td>
                                                <td> {{ $u->name }}      </td> 
                                                <td> {{ $u->description }}</td>
                                                <td> 
                                                    {{-- <a href="{{url('profileAction/'.$u->id)}}" class="icon-menu" title="Manage Profile"><i class="icon-arrow-right"></i></a> --}}
                                                    {{-- <a href="{{route('edit',$u->id)}}" data-hover="tooltip" data-placement="top" data-target="#edit-group " data-toggle="modal" id="modal-edit" title="Edit">
                                                        <i class="icon-arrow-right"></i>
                                                    </a> --}}
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit" href="{{ route('addgroup') }}" disabled style="cursor: not-allowed">
                                                        Edit
                                                   </button>
                                                   
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
</div>
    <!-- Modal For Add Group-->
    <div class="modal fade" id="addConference" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">ADD ADDRESS GROUP</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('addgroup') }}">
                    @csrf
                    <div class="row">
                    <div class="col-6">
                        <label for="formGroupExampleInput">GROUP NAME:</label>
                        <input type="text" class="form-control" name="name" placeholder="Group Name">
                    </div>
                    <div class="col-6">
                        <label for="formGroupExampleInput">GROUP DESCRIPTION:</label>
                        <input type="text" class="form-control" name="description" placeholder="Group Description">
                    </div>
                    <div class="col-12">
                            <div class="form-group">
                                <label for="formGroupExampleInput">GROUP NUMBERS:</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name='numbers' placeholder="0803300......,                                                      
0803300......,                                                      
0803300......" required></textarea>
    <sub for="Note text-primary"><b>Note:</b> Must use comma(,) as sparater after every contact.</sub>
                            </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" >Add Group</button>
                        {{-- href="{{ route('addgroup') }}" --}}
                    </div>
                </form>
            </div>
        </div>
    </div>              
            {{-- Modal For Edit Group --}}        
@endsection