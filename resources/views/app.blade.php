@extends('layouts.default')
@section('content')
        <!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="sidebar-mini skin-green">
<div id="vue-app" class="wrapper">

    @include('layouts.partials.navbar')
    @include('layouts.partials.sidebar')
    @include('layouts.users.users')
    @include('layouts.users.user')
    @include('layouts.dashboard.dashboard')
    @include('layouts.breeders.breedlist')
    @include('layouts.profile.profile')
    @include('layouts.profile.pedigree')
    @include('layouts.litter.littersList')
    @include('layouts.litter.litterprofile')

    @include('components.image-upload')
    @include('components.sex-select')
    @include('components.task-form')
    @include('components.breeder-form')
    @include('components.pedigree-form')
    @include('components.litter-form')
    @include('components.litter-box')
    @include('components.litter-weight')
    @include('components.litter-butcher')
    @include('components.schedule')
    @include('components.kit-form')
    @include('components.settings')
    @include('components.upcomming-tasks')
    @include('components.schedule-calendar')
    @include('components.notification-tab')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <div id="vue-content">
            <router-view></router-view>
        </div>
    </div>

    @include('layouts.partials.footer')
</div><!-- ./wrapper -->

@endsection


@section('scripts')
    <script>
        $(function () {
            App.init();
        });
    </script>
@endsection