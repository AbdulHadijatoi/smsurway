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
                    {{-- <a href="javascript:void(0);" class="btn btn-sm btn-primary" title="">Create New</a> --}}
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>Sent SMS History 
                            </h2>
                            <ul class="header-dropdown dropdown dropdown-animated scale-left">
                                <li><a href="javascript:void(0);" class="full-screen"><i class="icon-size-fullscreen"></i></a></li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            @if(Auth::user()->role === 'admin')
                                                <th>User</th>
                                            @endif
                                            <th>Date/Time</th>
                                            <th>Sender</th>
                                            {{-- <th>Destination</th> --}}
                                            <th>Limit Count</th>
                                            <th>SMS</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            use Carbon\Carbon;
                                            $date = "2016-09-16 11:00:00";
                                        @endphp
                                        @foreach($report->sortByDesc('id') as $r)
                                        @php
                                            $datework = Carbon::createFromDate($r->created_at);
                                            $now = Carbon::now();
                                            $testdate = $datework->diffInDays($now);
                                            $diff = Carbon::parse($r->created_at)->diffInDays(Carbon::now());
                                        @endphp
                                        <tr data-status="{{$diff==10}}">
                                            <td> {{$loop->iteration}}</td>
                                            @if(Auth::user()->role === 'admin')
                                                <td> {{$r->user?$r->user->name:'-' }}</td>
                                            @endif
                                            <td>
                                                {{$r->created_at->diffForHumans()}}
                                            </td>
                                            <td> {{$r->from}}</td>
                                            <td> {{$r->msg_count}}</td> 
                                            <td>
                                                <button type="button" class="btn btn-sm" data-toggle="popover" title="Message" data-content="{{ $r->msg }}">
                                                    {{ \Illuminate\Support\Str::limit($r->msg, 50, $end='...') }}
                                                </button> 
                                            </td>
                                            <td>
                                                @if(Auth::user()->role === 'admin')
                                                <a href="{{ route('getReportDetail', ['send_id' => $r->id, 'msg' => $r->msg, 'from' => $r->from]) }}">View Report</a>
                                                @else
                                                <a href="{{ route('reportDetail', ['send_id' => $r->id, 'msg' => $r->msg, 'from' => $r->from]) }}">View Report</a>
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
                    $('.tbody tr[data-status="' + $target + '"]').fadeIn('slow');
                } else {
                    $('.tbody tr').css('display', 'none').fadeIn('slow');
                }
            });
        });
    </script>
    @endsection 
@endsection
