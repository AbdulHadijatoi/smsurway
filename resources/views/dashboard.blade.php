@extends('layouts.TableHeader')
@section('title','User Dashboard')
    @section('content')
        <div id="main-content">
            <div class="block-header">
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
                                <h5><span style="font-weight: 600;">Welcome <span style="text-transform: capitalize;">{{auth()->user()->name}}</span></h5>
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card top_counter">
                                            <div class="body">
                                                <div class="icon text-info"><i class="fa fa-money"></i> </div>
                                                <div class="content">
                                                    <div class="text">SMS Credit Balance</div>
                                                    <h5 class="number" id="">
                                                        <a href="#">₦  {{number_format(auth()->user()->credit, 2)}}</a>
                                                    </h5>
                                                </div>
                                            </div>                        
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card top_counter">
                                            <div class="body">
                                                <div class="icon text-warning"><i class="fa fa-file"></i> </div>
                                                <div class="content">
                                                    <div class="text">Today Sent SMS</div>
                                                    <h5 class="number">
                                                       <a href="{{route('report')}}">{{$count['day1']}}</a> 
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
                                                        <a href="{{route('report')}}">{{$count['day30']}}</a>
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
                                                        <a href="{{route('address')}}">{{$count['address']}}</a>
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
    @section('JavaScript')