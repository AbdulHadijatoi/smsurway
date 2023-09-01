    <div id="left-sidebar" class="sidebar">
        <div class="navbar-brand" style="text-align: center;">
            @php
                $chk= \App\Models\ResellerLogo::where('reseller_id',auth()->user()->reseller_id)->count();
            @endphp
            {{-- @dd(auth()->user()->reseller_id) --}}
            @if ((auth()->user()->role =='reseller' && $chk > 0) || (auth()->user()->role =='user' && auth()->user()->reseller_id !=null))
            @php
                $reseller_logo = \App\Models\ResellerLogo::where('reseller_id',auth()->user()->reseller_id)->first('logo');
            @endphp
                <img src="{{asset('storage/'.$reseller_logo->logo)}}" alt="Reseller Logo" class="img-fluid logo" style="max-width: 65px;">
            @else
                <a href="index.php"><img src="{{ asset('assets/images/logo.jpg') }}" alt="SMS UR WAY Logo" class="img-fluid logo" style="width: 65px;"><span></span></a>
            @endif
            <button type="button" class="btn-toggle-offcanvas btn btn-sm btn-default float-right"><i class="lnr lnr-menu fa fa-chevron-circle-left"></i></button>
        </div>
        <div class="sidebar-scroll">
            <div class="user-account">
                <h3>
                    @if(auth()->user()->role == 'admin')
                        Admin
                    @elseif((auth()->user()->role == 'reseller'))
                        Reseller
                    @elseif((auth()->user()->role == 'user'))
                        User
                    @endif
                        Panel
                </h3>
                <hr>
                <h5>
                    <p id="blnc">
                        @if (auth()->user()->role != 'admin')
                            ₦  
                            {{number_format(auth()->user()->credit, 2)}}
                            <a href="{{route('buy')}}" class="user-name border border-secondary m-1 credit"><strong>Add Credit</strong></a>
                        @endif
                        
                    </p>
                </h5>
            </div>
            <nav id="left-sidebar-nav" class="sidebar-nav">
                <ul id="main-menu" class="metismenu">
                    @if(auth()->user()->role =='user')
                        <li class="{{ request()->is('dashboard') ? 'active' : ''}}"><a href="{{ route('dashboard') }}"><i class="icon-home"></i><span>Dashboard</span></a></li>
                        <li class="{{ request()->is('send') ? 'active' : ''}}"><a href="{{ route('send') }}"><i class="icon-envelope"></i><span>Send SMS Campaign</span></a></li>
                        <li class=""><a href="#"><i class="fa fa-file-sound-o"></i><span>Send VOICE Broadcast</span></a></li>
                        <li class="{{ request()->is('report') ? 'active' : ''}} {{ request()->is('reportDetail') ? 'active' : ''}}"><a href="{{ route('report') }}"><i class="fa fa-file-excel-o"></i><span>SMS Report</span></a></li>
                        <li class="{{ request()->is('address') ? 'active' : ''}}"><a href="{{ route('address') }}"><i class="icon-social-dribbble"></i><span>Address Book</span></a></li>
                        <li><a href="https://smsurway.com.ng/gsm" target="_blank"><i class="fa fa-shopping-cart"></i><span>Buy GSM</span></a></li>
                        <li class="{{ request()->is('buy') ? 'active' : ''}}"><a href="{{ route('buy') }}"><i class="icon-list"></i><span>Buy Credit</span></a></li>
                        <li class="{{ request()->is('contact') ? 'active' : ''}}"><a href="{{ route('contact') }}"><i class="icon-support"></i><span>Contact/Support</span></a></li>
                        <li class="{{ request()->is('profile') ? 'active' : ''}}"><a href="{{ route('profile') }}"><i class="fa fa-gear"></i><span>Profile/Setting</span></a></li>               
                    @elseif(auth()->user()->role =='admin')
                        <li class="{{ request()->is('home') ? 'active' : ''}}"><a href="{{ route('home') }}"><i class="icon-home"></i><span>Dashboard</span></a></li>
                        <li class="{{ request()->is('usersList') ? 'active' : ''}}"><a href="{{ route('usersList') }}"><i class="icon-users"></i><span>Manage Users</span></a></li>
                        <li class="{{ request()->is('userStatus') ? 'active' : ''}}"><a href="{{ route('userStatus') }}"><i class="fa fa-check-circle"></i><span>Verify Email</span></a></li>
                        <li class="{{ request()->is('ViewCredit') ? 'active' : ''}} {{ request()->is('managetransactions') ? 'active' : '' }}"><a href="{{ route('ViewCredit') }}"><i class="icon-wallet"></i><span>Manage Credit</span></a></li>
                        <li class="{{ request()->is('gsm_networks') ? 'active' : ''}}"><a href="{{ route('gsm_networks') }}"><i class="icon-social-dribbble"></i><span>GSM Network</span></a></li>
                        <li class="{{ request()->is('gsmPrefix') ? 'active' : ''}}"><a href="{{ route('gsmPrefix') }}"><i class="icon-screen-tablet"></i><span>GSM Prefixes</span></a></li>
                        <li class="{{ request()->is('keyword') ? 'active' : ''}}"><a href="{{ route('keyword') }}"><i class="fa fa-key" aria-hidden="true"></i><span>Manage Keywords</span></a></li>
                        <li class="{{ request()->is('newsletter') ? 'active' : ''}}"><a href="{{ route('newsletter') }}"><i class="fa fa-newspaper-o"></i><span>News Letter</span></a></li>
                        <li class="{{ request()->is('contactFeeds') ? 'active' : ''}}"><a href="{{ route('contactFeeds') }}"><i class="icon-users"></i><span>Contact Feeds</span></a></li>
                        <li class="{{ request()->is('profile') ? 'active' : ''}}"><a href="{{ route('profile') }}"><i class="fa fa-gear"></i><span>Profile/Setting</span></a></li>
                        <li class="{{ request()->is('setting') ? 'active' : ''}}"><a href="{{ route('setting') }}"><i class="fa fa-info"></i><span>Website Setting</span></a></li>
                    @elseif(auth()->user()->role =='reseller')
                        <li class="{{ request()->is('home1') ? 'active' : ''}}"><a href="{{ route('home1') }}"><i class="icon-home"></i><span>Dashboard</span></a></li>
                        <li class="{{ request()->is('usersList1') ? 'active' : ''}}"><a href="{{ route('usersList1') }}"><i class="icon-users"></i><span>Manage Users</span></a></li>
                        <li class="{{ request()->is('userStatus1') ? 'active' : ''}}"><a href="{{ route('userStatus1') }}"><i class="fa fa-check-circle"></i><span>Verify Email</span></a></li>
                        <li class="{{ request()->is('ViewCredit1') ? 'active' : ''}}"><a href="{{ route('ViewCredit1') }}"><i class="icon-wallet"></i><span>Manage Credit</span></a></li>
                        <li class="{{ request()->is('buy') ? 'active' : ''}}"><a href="{{ route('buy') }}"><i class="icon-list"></i><span>Buy Credit</span></a></li>
                        <li class="{{ request()->is('gsm_networks') ? 'active' : ''}}"><a href="{{ route('gsm_networks') }}"><i class="icon-social-dribbble"></i><span>GSM Network</span></a></li>
                        <li class="{{ request()->is('profile') ? 'active' : ''}}"><a href="{{ route('profile') }}"><i class="fa fa-gear"></i><span>Profile/Setting</span></a></li>
                        <li class="{{ request()->is('setting') ? 'active' : ''}}"><a href="{{ route('setting') }}"><i class="fa fa-gear"></i><span>Dashboard Setting</span></a></li>
                    @endif   
                </ul>
            </nav>            
            @section('JavaScript')
                @if(auth()->user()->role == 'admin')  
                    <script>
                        $(document).ready(function() {
                            var settings = {
                            "url": "{{ route('credit') }}",
                            "method": "POST",
                            data: {'_token':"{{ csrf_token() }}"},
                            };
                            $.ajax(settings).done(function (response) {
                                console.log(response);
                                $('#blnc').html('₦ '+response);
                                $('#adminBlnc').html('₦ '+response);
                            });
                        });
                    </script>
                @endif
            @endsection
        </div>
    </div>