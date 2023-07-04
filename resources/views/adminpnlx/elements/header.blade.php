<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
            <img src="assets/img/logo.png" alt="">
            <span class="d-none d-lg-block">NiceAdmin</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="POST" action="#">
            <input type="text" name="query" placeholder="Search" title="Enter search keyword">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li><!-- End Search Icon-->


            <div class="ht-right">
                @if (Auth::guard('admin'))
                    <button type="button" class="btn btn-danger"> <a href="{{ route('adminlogout') }}"
                            class="text-light">Logout </a> </button>
                    <button type="button" class="btn btn-dark">{{ Auth::guard('admin')->user()->name }}</button>
                @else
                    <button type="button" class="btn btn-danger"> <a href="{{ route('adminlogout') }}"
                            class="text-light">Logout </a> </button>
                    <button type="button" class="btn btn-dark">{{ Auth::guard('subadmins')->user()->name }}</button>
                @endif


                {{-- @else --}}
                {{-- <a href="/login" class="login-panel"><i class="fa fa-user"></i>Login </a> --}}
                {{-- <a href="/register" class="login-panel"><i class="fa fa-user"></i>Register </a> --}}
                {{-- @endif --}}

            </div>
        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->
