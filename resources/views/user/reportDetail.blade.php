@extends('layouts.TableHeader')
@section('content')
@section('title','SMS Report')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Sent SMS History</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">Sent SMS History</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <ul class="header-dropdown dropdown dropdown-animated scale-left">
                                <li><a href="javascript:void(0);" class="full-screen"><i class="icon-size-fullscreen"></i></a></li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="row">
                                <div class="col-6">
                                    <h2 class="text-primary">
                                        From:
                                    </h2>
                                    <p>
                                        {{ $from }}
                                    </p>
                                </div>
                                <div class="col-6">
                                    <h2 class="text-primary">
                                        Delivery Report for:
                                    </h2>
                                    <p>
                                        {{ $msg }}
                                    </p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date/Time</th>
                                            <th>Destination</th>
                                            <th>Units</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            use Carbon\Carbon;
                                        @endphp
                                        @foreach($report as $r)
                                            @php
                                                $datework = Carbon::createFromDate($r->created_at);
                                                $now = Carbon::now();
                                                $testdate = $datework->diffInDays($now);
                                                $diff = Carbon::parse($r->created_at)->diffInDays(Carbon::now());
                                            @endphp
                                        
                                            <tr data-status="{{$diff==10}}">
                                                <td> {{$loop->iteration}}</td>
                                                <td>
                                                    {{$r->created_at->diffForHumans()}}
                                                </td>
                                                <td> {{$r->to}}</td> 
                                                <td> {{$r->msg_price}}</td> 
                                                <td>
                                                    @if ($r->delivery_status==null)
                                                        <span class="badge badge-info">N/A</span>
                                                    @else                                                        
                                                        @if($r->delivery_status=="DELIVRD")
                                                            <span class="badge badge-success">{{$r->delivery_status}}</span>
                                                        @elseif($r->delivery_status=="UNDELIV")
                                                            <span class="badge badge-danger">Undelivered</span>
                                                        @else
                                                            <span class="badge badge-danger">{{$r->delivery_status}}</span>
                                                        @endif
                                                    @endif
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
