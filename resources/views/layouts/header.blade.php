<!doctype html>
<html lang="en">
    <head>
        <title>@yield('title')</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="meta_name" content="@yield('meta_name')">
        <meta name="meta_description" content="@yield('meta_description')">
        <link rel="canonical" href="@yield('canonical')" />
        <link rel="alternate" hreflang="x-default" href="@yield('canonical')" />
        <link rel="alternate" hreflang="en" href="@yield('canonical')" />
        <!-- VENDOR CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}">

        <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert/sweetalert.css') }}"/>
        <link rel="icon" href="{{ asset('assets/images/logo.jpg') }}" type="image/x-icon">
        <!-- MAIN CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
        <style>
            td.details-control {
                background: url("{{ asset('assets/images/details_open.png')}}") no-repeat center center;
                cursor: pointer;
            }
            tr.shown td.details-control {
                background: url("{{ asset('assets/images/details_close.png')}}") no-repeat center center;
            }
        </style>
        <style>
            .auth-main{
                background-color: #fffffff7;margin:0;
            }
            .theme-orange .auth-main:before{
                background: #22196a !important;
            }
            .theme-orange .auth-main:after{
                background-size:cover;
                background-image: url({{asset('assets/images/background.jpg')}});
               
            }
            .text-monospace{
                color: #22196a !important;
                max-width:60%; font-size: 18px;
                font-family: poppins !important; line-height: 1.6; margin-top: 30px;
                font-weight: 400; 
                    text-align: justify;
            }
            @media screen and (max-width: 640px){
                .text-monospace{
                    color: #fff !important;
                    max-width: 100%;
                    text-align: justify;
                    font-size: 15px;
                    font-family: poppins !important;
                    line-height: 1.6;
                    font-weight: 400;
                }
                
            }
            @media screen (min-width:640px) and (max-width: 940px){
                .theme-orange .auth-main:after{
                    /* background: #ffffff !important; */
                } 
            }
    </style>
    </head>
    <body class="theme-orange">
        <!-- Overlay For Sidebars -->
        <div class="overlay"></div>
        <div id="wrapper" class="auth-main">
            <div class="container py-5">
                <div class="row py-5">
                    @include('layouts.logNav')
                    @yield('content')
                </div>
            </div>
        </div>
        <!-- Javascript -->
        <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>    
        <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/bundles/datatablescripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.print.min.js') }}"></script>
        <script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/pages/tables/jquery-datatable.js') }}"></script>
        @yield('JavaScript')
    </body>
</html>