<header class="main-header">
    <!-- Logo -->
    <a class="logo" href="{{ url('/') }}">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="img/logo-tiny.png" alt="HUTCH"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>HUTCH</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav role="navigation" class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a role="button" data-toggle="offcanvas" class="sidebar-toggle" href="#">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->

                <!-- Notifications: style can be found in dropdown.less -->

                <!-- Tasks: style can be found in dropdown.less -->

                <notification-tab></notification-tab>

                <!-- User Account: style can be found in dropdown.less -->

                <!-- Control Sidebar Toggle Button -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-gears"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="#" v-link="{ path: '/settings' }"><i class="fa fa-cog"></i> Settings</a></li>
                        <li><a href="{{ url('logout') }}" id="logout"><i class="fa fa-power-off"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>