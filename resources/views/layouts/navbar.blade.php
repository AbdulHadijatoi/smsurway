    <nav class="navbar navbar-fixed-top">
        <div class="container-fluid">

            <div class="navbar-left">
                <div class="navbar-btn">
                    <a href="index.php"><img src="{{ asset('assets/images/logo.jpg') }}" alt="SMS UR WAY Logo" class="img-fluid logo"></a>
                    <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
                </div>
                <a href="javascript:void(0);" class="icon-menu btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>
            </div>
            
            <div class="navbar-right">
                <div id="navbar-menu">
                    <ul class="nav navbar-nav">
                        {{-- <li><a href="{{ url('profile') }}" class="icon-menu" title="Right Menu"><i class="icon-settings"></i></a></li> --}}
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" class="icon-menu" onclick="event.preventDefault();
                                    this.closest('form').submit();">
                                    <i class="icon-power"></i>
                                </a>
                            </form>
                        </li>
                        <li>
                            
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>