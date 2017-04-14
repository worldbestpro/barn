<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <!-- Optionally, you can add icons to the links -->
            <li v-link-active>
                <a href="#" v-link="{ path: '/', activeClass: 'active', exact: true }">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#" v-link="{ path: '/breeders' }"><i class="fa fa-venus-mars"></i> <span>Breeders</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li v-link-active><a href="#" id="sidebar-new-breeder">Add New</a></li>
                    <li v-link-active><a v-link="{ path: '/breeders', activeClass: 'active', exact: true }" href="#">All</a></li>
                    <li v-link-active><a v-link="{ path: '/breeders/does', activeClass: 'active' }" href="#">Does</a></li>
                    <li v-link-active><a v-link="{ path: '/breeders/bucks', activeClass: 'active' }" href="#">Bucks</a></li>
                    <li v-link-active><a v-link="{ path: '/breeders/archive', activeClass: 'active' }" href="#">Archive</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#" v-link="{ path: '/litters', activeClass: 'active' }"><i class="fa fa-th"></i> <span>Litters</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="#" id="sidebar-new-litter">Add New</a></li>
                    <li v-link-active><a href="#" v-link="{ path: '/litters', activeClass: 'active', exact: true }">All</a></li>
                    <li v-link-active><a href="#" v-link="{ path: '/litters/archive', activeClass: 'active' }">Archive</a></li>
                </ul>
            </li>
            <li v-link-active>
                <a href="#" v-link="{ path: '/schedule', activeClass: 'active' }">
                    <i class="fa fa-calendar"></i> <span>Schedule</span>
                    {{--<small class="label pull-right bg-red">3</small>--}}
                </a>
            </li>
            @if($currentUser->can('manageUsers'))
                <li v-link-active>
                    <a href="#" v-link="{ path: '/users', activeClass: 'active' }">
                        <i class="fa fa-user"></i> <span>Users</span>
                    </a>
                </li>
            @endif
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>