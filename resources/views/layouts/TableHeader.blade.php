<!doctype html>
<html lang="en">
    <head>
        <title>@yield('title')</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="meta_name" content="@yield('meta_name')">
        <meta name="meta_description" content="@yield('meta_description')">
        <link rel="canonical" href="@yield('canonical')" />
        <link rel="alternate" hreflang="x-default" href="@yield('canonical')" />
        <link rel="alternate" hreflang="en" href="@yield('canonical')" />
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <link rel="icon" href="{{ asset('assets/images/logo.jpg') }}" type="image/x-icon">
        
        <!-- VENDOR CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}">

        <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert/sweetalert.css') }}"/>

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
            .credit{
                border-radius: 25px;
                width: 165px;
                margin: auto !important;
                text-transform: uppercase;
                font-size: 17px;
                padding: 5px;
                margin-top: 10px;
            }
            #start-payment-button{
                cursor: pointer;
                position: relative;
                background-color: blueviolet;
                color: #fff;
                max-width: 30%;
                padding: 7px 15px 7px;
                font-weight: 600;
                font-size: 14px;
                font-family: Sans-Serif;
                border-radius: 10px;
                border: none;
                transition: all .1s ease-in;
            }
        </style>
    </head>
    <body class="theme-orange">
        <!-- Overlay For Sidebars -->
        <div class="overlay"></div>
        <div id="wrapper">
            @include('layouts.navbar')
            @include('layouts.rightbar')
            @include('layouts.leftbar')
            @yield('content')
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
        <script src="{{ asset('assets/vendor/sweetalert/sweetalert.min.js') }}"></script>
        <script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/pages/ui/dialogs.js') }}"></script>
        <script src="{{ asset('assets/js/pages/tables/jquery-datatable.js') }}"></script>
        @yield('JavaScript') 
    </body>
</html>