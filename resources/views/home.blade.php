@php
    $is_low_balance = get_setting('oneroute_low_balance')?get_setting('oneroute_low_balance')->value:0;
@endphp
@extends('layouts.TableHeader')
@if(auth()->user()->role=='admin')
@section('title','Admin Dashboard')
@else
@section('title','Reseller Dashboard')
@endif
    @section('content')
        <div id="main-content">
            <div class="block-header @if (auth()->user()->role == 'admin' && $is_low_balance == 1) mb-0 @endif">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h2>Dashboards and Analytics</h2>
                    </div>            
                    <div class="col-md-6 col-sm-12 text-right">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                            <li class="breadcrumb-item active">Dashboards and Analytics</li>
                            
                        </ul>
                        <!-- <a href="javascript:void(0);" class="btn btn-sm btn-primary" title="">Create New</a> -->
                    </div>
                </div>
            </div>
            
            @if (auth()->user()->role == 'admin' && $is_low_balance == 1)
                <form class="alert alert-danger mt-0" action="{{route('oneroute.low_balance')}}" method="POST">
                    @csrf
                    Low Admin Balance Notification
                    <button type="submit" class="close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </form>
            @endif

            <div class="container-fluid">           

                <div class="row clearfix">

                    <div class="col-lg-12 col-md-12">
                        <div class="card planned_task">
                            <div class="header">
                                <h2>Dashboards and Analytics</h2>
                                <ul class="header-dropdown dropdown dropdown-animated scale-left">
                                    <li> <a href="javascript:void(0);" data-toggle="cardloading" data-loading-effect="pulse"><i class="icon-refresh"></i></a></li>
                                    <li><a href="javascript:void(0);" class="full-screen"><i class="icon-size-fullscreen"></i></a></li>
                                    <li class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="javascript:void(0);">Action</a></li>
                                            <li><a href="javascript:void(0);">Another Action</a></li>
                                            <li><a href="javascript:void(0);">Something else</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="body">
                                @if(auth()->user()->role == 'user')
                                    <h5><span style="font-weight: 600;">Welcome </span> User</h5>
                                @elseif(auth()->user()->role == 'reseller')
                                <h5><span style="font-weight: 600;">Welcome </span> Reseller</h5>
                                @else
                                <h5><span style="font-weight: 600;">Welcome </span> Admin</h5>
                                @endif
                                    
                                <div class="row clearfix">
                                    @if (auth()->user()->role != 'admin')
                                        <div class="col-lg-3 col-md-6">
                                            <div class="card top_counter">
                                                <div class="body">
                                                    <div class="icon text-info"><i class="fa fa-money"></i> </div>
                                                    <div class="content">
                                                        <div class="text">Credit</div>
                                                        <h5 class="number">
                                                            ₦ 
                                                            {{number_format(session('balance'), 2)}}
                                                        </h5>
                                                    </div>
                                                </div>                        
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card top_counter">
                                            <div class="body">
                                                <div class="icon text-warning"><i class="fa fa-file"></i> </div>
                                                <div class="content">
                                                    <div class="text">Today Sent SMS</div>
                                                    <h5 class="number">
                                                        @if(Auth::user()->role === 'admin')
                                                            <a href="{{url('get-report/today')}}">{{$count['day1']}}</a>
                                                        @else
                                                            <a href="{{url('report/today')}}">{{$count['day1']}}</a>
                                                        @endif
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card top_counter">
                                            <div class="body">
                                                <div class="icon text-danger"><i class="fa fa-calendar"></i> </div>
                                                <div class="content">
                                                    <div class="text">Sent This Month</div>
                                                    <h5 class="number">
                                                        @if(Auth::user()->role === 'admin')
                                                            <a href="{{url('get-report/month')}}">{{$count['day30']}}</a>
                                                        @else
                                                            <a href="{{url('report/month')}}">{{$count['day30']}}</a>
                                                        @endif
                                                        
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-12">
                                        <div class="card top_counter">
                                            <div class="body">
                                                <div class="icon"><i class="fa fa-users"></i> </div>
                                                <div class="content">
                                                    <div class="text">Contact Groups</div>
                                                    <h5 class="number">
                                                        <a href="#">{{$count['address']}}</a>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection