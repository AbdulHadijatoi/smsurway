<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="description" content="SMS UR WAY Panel">
        <link rel="icon" href="{{ asset('assets/images/logo.jpg') }}" type="image/x-icon">
    
        <!-- VENDOR CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}">

        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}">

        <!-- MAIN CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

    </head>
    <body class="theme-orange">
        <div class="overlay"></div>
        <style>
            #toast-container{
                display:none;
            }
            .btn-bg{
                background:#F1B815;color:#fff
            }
        </style>
        <div id="wrapper">
            @include('layouts.navbar')
            @include('layouts.rightbar')
            @include('layouts.leftbar')
            
            @yield('content')
         
            <!-- Footer -->
            {{-- <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; HollaTags Website 2022</span>
                    </div>
                </div>
            </footer> --}}
            <!-- End of Footer -->
        </div>
        
        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <!-- Core plugin JavaScript-->
        {{-- <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script> --}}

        <!-- Custom scripts for all pages-->
        {{-- <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script> --}}

        <!-- Page level plugins -->
        {{-- <script src="{{ asset('assets/vendor/chart.js/Chart.min.js') }}"></script> --}}

        <!-- Page level custom scripts -->
        {{-- <script src="{{ asset('assets/js/demo/chart-area-demo.js') }}"></script>
        <script src="{{ asset('assets/js/demo/chart-pie-demo.js') }}"></script> --}}
        @yield('JavaScript') 
    </body>
</html>