<!-- Top Bar Start -->
<div class="topbar">
    <!-- LOGO -->
    <div class="topbar-left">
        <div class="text-center">
            @if(Request::is('admin*'))
                {{-- <a href="{{ url('admin') }}" class="logo"><i class="md md-terrain"></i> <span>Pharmacy </span></a> --}}
                <a href="{{ url('admin') }}" class="logo"><i class="md md-terrain"></i><span><img src="{{ Storage::disk('public')->url('settings/'.$pharmacy->ph_logo) }}" class="logo-mine" alt="logo"></span></a>
            @endif
            @if(Request::is('dispenser*'))
                {{-- <a href="{{ url('dispenser') }}" class="logo"><i class="md md-terrain"></i> <span>Pharmacy </span></a> --}}
                <a href="{{ url('admin') }}" class="logo"><i class="md md-terrain"></i><span><img src="{{ Storage::disk('public')->url('settings/'.$pharmacy->ph_logo) }}" class="logo-mine" alt="logo"></span></a>
            @endif
        </div>
    </div>
    <!-- Button mobile view to collapse sidebar menu -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <ul class="list-inline menu-left mb-0">
                <li class="float-left">
                    <a href="#" class="button-menu-mobile open-left">
                        <i class="fa fa-bars"></i>
                    </a>
                </li>
                {{-- <li class="hide-phone float-left">
                    <form role="search" class="navbar-form">
                        <input type="text" placeholder="Type here for search..." class="form-control search-bar">
                        <a href="#" class="btn btn-search"><i class="fa fa-search"></i></a>
                    </form>
                </li> --}}
            </ul>

            <ul class="nav navbar-right float-right list-inline">
                <li class="dropdown d-none d-sm-block">
                    <a href="#" role="button" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true" id="dropdown-notification">
                        <i class="md md-notifications"></i> 
                        @if($stockOut != 0 && $expired != 0 && $expiredSoon != 0)
                            <span class="badge badge-pill badge-xs badge-danger">3</span>
                        @elseif($stockOut != 0 && $expired != 0)
                            <span class="badge badge-pill badge-xs badge-danger">2</span>
                        @elseif($expired != 0 && $expiredSoon != 0)
                            <span class="badge badge-pill badge-xs badge-danger">2</span>
                        @elseif($stockOut != 0 && $expiredSoon != 0)
                            <span class="badge badge-pill badge-xs badge-danger">2</span>
                        @elseif($stockOut != 0 && $expired == 0 && $expiredSoon == 0)
                            <span class="badge badge-pill badge-xs badge-danger">1</span>
                        @elseif($stockOut == 0 && $expired != 0 && $expiredSoon == 0)
                            <span class="badge badge-pill badge-xs badge-danger">1</span>
                        @elseif($stockOut == 0 && $expired == 0 && $expiredSoon != 0)
                            <span class="badge badge-pill badge-xs badge-danger">1</span>
                        @elseif($stockOut == 0 && $expired == 0 && $expiredSoon == 0)
                            <span class="badge badge-pill badge-xs badge-success">0</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg" aria-labelledby="dropdown-notification">
                        <li class="text-center notifi-title">Notification</li>
                        <li class="list-group">
                            <!-- list item-->
                            <a href="{{url('admin/stockout')}}" class="list-group-item">
                                <div class="media">
                                    <div class="media-left pr-2">
                                        <em class="fa fa-user-plus fa-2x text-info"></em>
                                    </div>
                                    <div class="media-body clearfix">
                                        <div class="media-heading">Out of Stock Medicine</div>
                                        <p class="m-0">
                                            <small>
                                                <span class="text-primary">{{ $stockOut }}</span> medicines are not in stock
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <!-- list item-->
                            <a href="{{url('admin/stock/expired')}}" class="list-group-item">
                                <div class="media">
                                    <div class="media-left pr-2">
                                        <em class="fa fa-diamond fa-2x text-primary"></em>
                                    </div>
                                    <div class="media-body clearfix">
                                        <div class="media-heading">Expired Medicine</div>
                                        <p class="m-0">
                                            <small>
                                                <span class="text-primary">{{ $expired }}</span> medicines are expired
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <!-- list item-->
                            <a href="{{url('admin/stock/expired-soon')}}" class="list-group-item">
                                <div class="media">
                                    <div class="media-left pr-2">
                                        <em class="fa fa-bell-o fa-2x text-danger"></em>
                                    </div>
                                    <div class="media-body clearfix">
                                        <div class="media-heading">Expires within 30 Days</div>
                                        <p class="m-0">
                                            <small>
                                                <span class="text-primary">{{ $expiredSoon }}</span> medicines are going to be expired
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <!-- last list item -->
                            {{-- <a href="javascript:void(0);" class="list-group-item">
                                <small>See all notifications</small>
                            </a> --}}
                        </li>
                    </ul>
                </li>
                
                <li class="d-none d-sm-block">
                    <a href="#" id="btn-fullscreen" class="waves-effect waves-light"><i class="md md-crop-free"></i></a>
                </li>
                {{-- <li class="d-none d-sm-block">
                    <a href="#" class="right-bar-toggle waves-effect waves-light"><i class="md md-chat"></i></a>
                </li> --}}
                <li class="dropdown open">
                    <a href="#" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true">
                        @if($user->profile_photo != '')
                            <img src="{{ Storage::disk('public')->url('profile/'.$user->profile_photo) }}" alt="user-img" class="rounded-circle"> 
                        @else   
                            <img src="{{Storage::disk('public')->url('avatar.png')}}" alt="user-img" class="rounded-circle"> 
                        @endif
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('admin/profile') }}" class="dropdown-item"><i class="md md-face-unlock mr-2"></i> Profile</a></li>
                        @if(auth()->user()->role_id <= 2)
                        <li><a href="{{ url('admin/pharmacy/settings') }}" class="dropdown-item"><i class="md md-settings mr-2"></i> Settings</a></li>
                        @endif
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                          document.getElementById('logout-form').submit();">
                                <i class="md md-settings-power mr-2"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>
<!-- Top Bar End -->