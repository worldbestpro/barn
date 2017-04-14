@extends('layouts.default')
@section('content')

    <body class="sidebar-mini skin-green">

    <!--<body class="skin-green sidebar-mini modal-open">-->

    <div class="wrapper">

        @include('layouts.partials.navbar')
        @include('layouts.partials.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="min-height: 523px;">
            <!-- Content Header (Page header) -->


            <!-- Main content -->
            <section class="content">

                <!-- Your Page Content Here -->

                <div class="row">
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">

                            <div class="inner">
                                <h3>Breed</h3>
                                <p>Add new breeding event</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-venus-mars"></i>
                            </div>
                            <a data-toggle="modal" role="button" href="#breed" class="small-box-footer">Start <i
                                        class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div><!-- ./col -->
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>Birth</h3>
                                <p>Record a birth</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-birthday-cake"></i>
                            </div>
                            <a data-toggle="modal" role="button" href="#birth" class="small-box-footer">Record <i
                                        class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div><!-- ./col -->
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3>Weigh</h3>
                                <p>Enter litter performance</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-balance-scale"></i>
                            </div>
                            <a data-toggle="modal" role="button" href="#weigh" class="small-box-footer">Enter Data <i
                                        class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div><!-- ./col -->
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3>Butcher</h3>
                                <p>Dispatch litters</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cutlery"></i>
                            </div>
                            <a data-toggle="modal" role="button" href="#butcher" class="small-box-footer">Record event
                                <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div><!-- ./col -->
                </div>


                <div class="row">
                    <section class="col-md-4 connectedSortable ui-sortable">
                        <div class="box box-primary">
                            <div class="box-header ui-sortable-handle" style="cursor: move;">
                                <i class="ion ion-clipboard"></i>
                                <h3 class="box-title">Upcoming Tasks</h3>
                                <div class="box-tools pull-right">
                                    <ul class="pagination pagination-sm inline">
                                        <li><a href="#">«</a></li>
                                        <li><a href="#">1</a></li>
                                        <li><a href="#">2</a></li>
                                        <li><a href="#">3</a></li>
                                        <li><a href="#">»</a></li>
                                    </ul>
                                </div>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <ul class="todo-list ui-sortable">
                                    <li>
                                        <!-- drag handle -->
                      <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                                        <!-- checkbox -->
                                        <input type="checkbox" name="" value="">
                                        <!-- todo text -->
                                        <span class="text">Weight Litter 103</span>
                                        <!-- Emphasis label -->
                                        <small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
                                        <!-- General tools such as edit or delete-->
                                        <div class="tools">
                                            <i class="fa fa-edit"></i>
                                            <i class="fa fa-trash-o"></i>
                                        </div>
                                    </li>
                                    <li>
                      <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                                        <input type="checkbox" name="" value="">
                                        <span class="text">Breed Sally with Fred</span>
                                        <small class="label label-info"><i class="fa fa-clock-o"></i> 4 hours</small>
                                        <div class="tools">
                                            <i class="fa fa-edit"></i>
                                            <i class="fa fa-trash-o"></i>
                                        </div>
                                    </li>
                                    <li>
                      <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                                        <input type="checkbox" name="" value="">
                                        <span class="text">Butcher Litter 101</span>
                                        <small class="label label-warning"><i class="fa fa-clock-o"></i> 1 day</small>
                                        <div class="tools">
                                            <i class="fa fa-edit"></i>
                                            <i class="fa fa-trash-o"></i>
                                        </div>
                                    </li>
                                    <li>
                      <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                                        <input type="checkbox" name="" value="">
                                        <span class="text">Buffy is Due in 5 days</span>
                                        <small class="label label-success"><i class="fa fa-clock-o"></i> 3 days</small>
                                        <div class="tools">
                                            <i class="fa fa-edit"></i>
                                            <i class="fa fa-trash-o"></i>
                                        </div>
                                    </li>
                                    <li>
                      <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                                        <input type="checkbox" name="" value="">
                                        <span class="text">Add doe box to Sara</span>
                                        <small class="label label-primary"><i class="fa fa-clock-o"></i> 1 week</small>
                                        <div class="tools">
                                            <i class="fa fa-edit"></i>
                                            <i class="fa fa-trash-o"></i>
                                        </div>
                                    </li>
                                    <li>
                      <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                                        <input type="checkbox" name="" value="">
                                        <span class="text">Trim Fred's nails</span>
                                        <small class="label label-default"><i class="fa fa-clock-o"></i> 1 month</small>
                                        <div class="tools">
                                            <i class="fa fa-edit"></i>
                                            <i class="fa fa-trash-o"></i>
                                        </div>
                                    </li>
                                </ul>
                            </div><!-- /.box-body -->
                            <div class="box-footer clearfix no-border">
                                <button class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add item</button>
                            </div>
                        </div>

                    </section>

                    <section class="col-lg-8 connectedSortable ui-sortable">
                        <div class="box box-solid box-primary " style="">
                            <div class="box-header ui-sortable-handle" style="cursor: move;">
                                <i class="fa fa-calendar"></i>
                                <h3 class="box-title">Schedule</h3>
                                <!-- tools box -->
                                <!-- /. tools -->
                            </div><!-- /.box-header -->
                            <div style="display: block;" class="box-body">
                                <div class="row">
                                    <div class="col-xs-3 col-md-1">
                                        <!-- The timeline -->
                                        <ul class="timeline timeline-inverse">
                                            <li style="margin-bottom:30px;"><img
                                                        style="max-width: 50px; margin-left:8px;" src="img/rabbit2.jpg"
                                                        class="img-circle"></li>

                                            <!-- timeline item -->


                                            <li>
                                                <i class="fa fa-venus-mars bg-blue"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-check bg-maroon"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-birthday-cake bg-green"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-cutlery bg-red"></i>

                                            </li>
                                            <!-- END timeline item -->

                                        </ul>
                                    </div>
                                    <div class="col-xs-3 col-sm-1">
                                        <!-- The timeline -->
                                        <ul class="timeline timeline-inverse">
                                            <li style="margin-bottom:30px;"><img class="img-circle"
                                                                                 src="img/rabbit2.jpg"
                                                                                 style="max-width: 50px; margin-left:8px;">
                                            </li>

                                            <!-- timeline item -->


                                            <li>
                                                <i class="fa fa-venus-mars bg-blue"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-check bg-maroon"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-birthday-cake bg-green"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-cutlery bg-red"></i>

                                            </li>
                                            <!-- END timeline item -->

                                        </ul>
                                    </div>
                                    <div class="col-xs-3 col-sm-1">
                                        <!-- The timeline -->
                                        <ul class="timeline timeline-inverse">
                                            <li style="margin-bottom:30px;"><img class="img-circle"
                                                                                 src="img/rabbit2.jpg"
                                                                                 style="max-width: 50px; margin-left:8px;">
                                            </li>

                                            <!-- timeline item -->


                                            <li>
                                                <i class="fa fa-venus-mars bg-blue"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-check bg-maroon"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-birthday-cake bg-green"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-cutlery bg-red"></i>

                                            </li>
                                            <!-- END timeline item -->

                                        </ul>
                                    </div>
                                    <div class="col-xs-3 col-sm-1">
                                        <!-- The timeline -->
                                        <ul class="timeline timeline-inverse">
                                            <li style="margin-bottom:30px;"><img class="img-circle"
                                                                                 src="img/rabbit2.jpg"
                                                                                 style="max-width: 50px; margin-left:8px;">
                                            </li>

                                            <!-- timeline item -->


                                            <li>
                                                <i class="fa fa-venus-mars bg-blue"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-check bg-maroon"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-birthday-cake bg-green"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-cutlery bg-red"></i>

                                            </li>
                                            <!-- END timeline item -->

                                        </ul>
                                    </div>
                                    <div class="col-xs-3 col-sm-1">
                                        <!-- The timeline -->
                                        <ul class="timeline timeline-inverse">
                                            <li style="margin-bottom:30px;"><img class="img-circle"
                                                                                 src="img/rabbit2.jpg"
                                                                                 style="max-width: 50px; margin-left:8px;">
                                            </li>

                                            <!-- timeline item -->


                                            <li>
                                                <i class="fa fa-venus-mars bg-blue"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-check bg-maroon"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-birthday-cake bg-green"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-cutlery bg-red"></i>

                                            </li>
                                            <!-- END timeline item -->

                                        </ul>
                                    </div>
                                    <div class="col-xs-2 col-sm-1">
                                        <!-- The timeline -->
                                        <ul class="timeline timeline-inverse">
                                            <li style="margin-bottom:30px;"><img
                                                        style="max-width: 50px; margin-left:8px;" src="img/rabbit2.jpg"
                                                        class="img-circle"></li>

                                            <!-- timeline item -->


                                            <li>
                                                <i class="fa fa-venus-mars bg-blue"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-check bg-maroon"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-birthday-cake bg-green"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-cutlery bg-red"></i>

                                            </li>
                                            <!-- END timeline item -->

                                        </ul>
                                    </div>
                                    <div class="col-xs-2 col-sm-1">
                                        <!-- The timeline -->
                                        <ul class="timeline timeline-inverse">
                                            <li style="margin-bottom:30px;"><img
                                                        style="max-width: 50px; margin-left:8px;" src="img/rabbit2.jpg"
                                                        class="img-circle"></li>

                                            <!-- timeline item -->


                                            <li>
                                                <i class="fa fa-venus-mars bg-blue"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-check bg-maroon"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-birthday-cake bg-green"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-cutlery bg-red"></i>

                                            </li>
                                            <!-- END timeline item -->

                                        </ul>
                                    </div>
                                    <div class="col-xs-2 col-sm-1">
                                        <!-- The timeline -->
                                        <ul class="timeline timeline-inverse">
                                            <li style="margin-bottom:30px;"><img
                                                        style="max-width: 50px; margin-left:8px;" src="img/rabbit2.jpg"
                                                        class="img-circle"></li>

                                            <!-- timeline item -->


                                            <li>
                                                <i class="fa fa-venus-mars bg-blue"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-check bg-maroon"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-birthday-cake bg-green"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-cutlery bg-red"></i>

                                            </li>
                                            <!-- END timeline item -->

                                        </ul>
                                    </div>
                                    <div class="col-xs-2 col-sm-1">
                                        <!-- The timeline -->
                                        <ul class="timeline timeline-inverse">
                                            <li style="margin-bottom:30px;"><img
                                                        style="max-width: 50px; margin-left:8px;" src="img/rabbit2.jpg"
                                                        class="img-circle"></li>

                                            <!-- timeline item -->


                                            <li>
                                                <i class="fa fa-venus-mars bg-blue"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-check bg-maroon"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-birthday-cake bg-green"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-cutlery bg-red"></i>

                                            </li>
                                            <!-- END timeline item -->

                                        </ul>
                                    </div>
                                    <div class="col-xs-2 col-sm-1">
                                        <!-- The timeline -->
                                        <ul class="timeline timeline-inverse">
                                            <li style="margin-bottom:30px;"><img
                                                        style="max-width: 50px; margin-left:8px;" src="img/rabbit2.jpg"
                                                        class="img-circle"></li>

                                            <!-- timeline item -->


                                            <li>
                                                <i class="fa fa-venus-mars bg-blue"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-check bg-maroon"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-birthday-cake bg-green"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-cutlery bg-red"></i>

                                            </li>
                                            <!-- END timeline item -->

                                        </ul>
                                    </div>
                                    <div class="col-xs-2 col-sm-1">
                                        <!-- The timeline -->
                                        <ul class="timeline timeline-inverse">
                                            <li style="margin-bottom:30px;"><img
                                                        style="max-width: 50px; margin-left:8px;" src="img/rabbit2.jpg"
                                                        class="img-circle"></li>

                                            <!-- timeline item -->


                                            <li>
                                                <i class="fa fa-venus-mars bg-blue"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-check bg-maroon"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-birthday-cake bg-green"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-cutlery bg-red"></i>

                                            </li>
                                            <!-- END timeline item -->

                                        </ul>
                                    </div>
                                    <div class="col-xs-2 col-sm-1">
                                        <!-- The timeline -->
                                        <ul class="timeline timeline-inverse">
                                            <li style="margin-bottom:30px;"><img
                                                        style="max-width: 50px; margin-left:8px;" src="img/rabbit2.jpg"
                                                        class="img-circle"></li>

                                            <!-- timeline item -->


                                            <li>
                                                <i class="fa fa-venus-mars bg-blue"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-check bg-maroon"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-birthday-cake bg-green"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-balance-scale bg-yellow"></i>

                                            </li>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-cutlery bg-red"></i>

                                            </li>
                                            <!-- END timeline item -->

                                        </ul>
                                    </div>

                                </div>
                                <!--The calendar -->

                            </div><!-- /.box-body -->

                        </div>
                    </section>


                </div>
                <!-- modal -->
                <div class="modal" id="breed">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-aqua">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Schedule Breed</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal">
                                    <div class="form-group col-sm-6">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Buck</label>
                                        <div class="col-sm-8">
                                            <select class="form-control">
                                                <option>Fred</option>
                                                <option>James</option>
                                                <option>King</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="inputPassword3" class="col-sm-4 control-label">Doe</label>
                                        <div class="col-sm-8">
                                            <select class="form-control">
                                                <option>Sara</option>
                                                <option>Wilma</option>
                                                <option>Queen</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">Date</label>
                                        <div class="col-sm-4" id="datepick">
                                            <div class="input-group date">
                                                <input type="text" class="form-control"><span class="input-group-addon"><i
                                                            class="glyphicon glyphicon-th"></i></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-8">

                                            <div id="timeline" class="tab-pane active">
                                                <!-- The timeline -->
                                                <ul class="timeline timeline-inverse">

                                                    <!-- timeline item -->
                                                    <li>
                                                        <i class="fa fa-venus-mars bg-blue"></i>
                                                        <div class="timeline-item">
                                                            <span class="time"><i
                                                                        class="fa fa-calendar"></i> Today</span>
                                                            <h3 class="timeline-header">Breed</h3>
                                                        </div>
                                                    </li>
                                                    <!-- END timeline item -->
                                                    <!-- timeline item -->
                                                    <li>
                                                        <i class="fa fa-check bg-maroon"></i>
                                                        <div class="timeline-item">
                                                            <span class="time"><i
                                                                        class="fa fa-calendar"></i> Sep 15 </span>
                                                            <h3 class="timeline-header">Pregnancy Check</h3>
                                                        </div>
                                                    </li>
                                                    <!-- END timeline item -->
                                                    <!-- timeline item -->
                                                    <li>
                                                        <i class="fa fa-birthday-cake bg-green"></i>
                                                        <div class="timeline-item">
                                                            <span class="time"><i
                                                                        class="fa fa-calendar"></i> Sep 30 </span>
                                                            <h3 class="timeline-header">Kindle/Birth</h3>
                                                        </div>
                                                    </li>
                                                    <!-- END timeline item -->
                                                    <!-- timeline item -->
                                                    <li>
                                                        <i class="fa fa-balance-scale bg-yellow"></i>
                                                        <div class="timeline-item">
                                                            <span class="time"><i
                                                                        class="fa fa-calendar"></i> Oct 1 </span>
                                                            <h3 class="timeline-header">Wean and Weigh 1</h3>
                                                        </div>
                                                    </li>
                                                    <!-- END timeline item -->
                                                    <!-- timeline item -->
                                                    <li>
                                                        <i class="fa fa-balance-scale bg-yellow"></i>
                                                        <div class="timeline-item">
                                                            <span class="time"><i
                                                                        class="fa fa-calendar"></i> Nov 1 </span>
                                                            <h3 class="timeline-header">Weigh 2</h3>
                                                        </div>
                                                    </li>
                                                    <!-- END timeline item -->
                                                    <!-- timeline item -->
                                                    <li>
                                                        <i class="fa fa-balance-scale bg-yellow"></i>
                                                        <div class="timeline-item">
                                                            <span class="time"><i
                                                                        class="fa fa-calendar"></i> Nov 15 </span>
                                                            <h3 class="timeline-header">Weigh 3</h3>
                                                        </div>
                                                    </li>
                                                    <!-- END timeline item -->
                                                    <!-- timeline item -->
                                                    <li>
                                                        <i class="fa fa-cutlery bg-red"></i>
                                                        <div class="timeline-item">
                                                            <span class="time"><i
                                                                        class="fa fa-calendar"></i> Nov 30 </span>
                                                            <h3 class="timeline-header">Butcher</h3>
                                                        </div>
                                                    </li>
                                                    <!-- END timeline item -->

                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- /.box-header -->
                                    <!-- form start -->

                                    <!-- /.box-body -->
                                    <!-- /.box-footer -->
                                </form>
                            </div>
                            <div class="modal-footer bg-info">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close
                                </button>
                                <button type="button" class="btn btn-info">Save changes</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>


                <!-- modal -->
                <div class="modal" id="birth">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-green">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Report Birth</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal">


                                    <div class="form-group col-sm-6">
                                        <label for="inputPassword3" class="col-sm-4 control-label">Breeders</label>
                                        <div class="col-sm-8">
                                            <select class="form-control">
                                                <option>Fred + Sara</option>
                                                <option>Fred + Wilma</option>
                                                <option>Fred + Queen</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-4 control-label" for="inputPassword3">Number</label>
                                        <div class="col-sm-8">
                                            <input type="text" placeholder="Number in Litter" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">Date</label>
                                        <div class="col-sm-4" id="datepick">
                                            <div class="input-group date">
                                                <input type="text" class="form-control"><span class="input-group-addon"><i
                                                            class="glyphicon glyphicon-th"></i></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="exampleInputFile">Photo</label>
                                        <div class="col-sm-10">
                                            <input type="file" id="exampleInputFile">
                                            <p class="help-block">Select photo of litter</p></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="exampleInputFile">Notes</label>
                                        <div class="col-sm-10"><textarea placeholder="Descriptions" rows="3"
                                                                         class="form-control"></textarea></div>
                                    </div>


                                </form>
                            </div>
                            <div class="modal-footer bg-success">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close
                                </button>
                                <button type="button" class="btn btn-success">Save changes</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>


                <div id="weigh" class="modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-yellow">
                                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                                            aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Record Litter Weights</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal">

                                    <div class="form-group col-sm-6">
                                        <label for="inputPassword3" class="col-sm-4 control-label">Doe/Litter</label>
                                        <div class="col-sm-8">
                                            <select class="form-control">
                                                <option>Sara - 101</option>
                                                <option>Wilma - 203</option>
                                                <option>Queen - 304</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group col-sm-6">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Date</label>
                                        <div id="datepick" class="col-sm-8">
                                            <div class="input-group date">
                                                <input type="text" class="form-control"><span class="input-group-addon"><i
                                                            class="glyphicon glyphicon-th"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="info-box bg-aqua disabled">
                                                <span class="info-box-icon"><img class="img-responsive img-circle"
                                                                                 src="img/rabbit1.jpg"
                                                                                 style="max-width: 80%; margin:10px auto; border: 3px solid"></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-number">453453 - brown</span>
                                                    <span class="small"><strong><i class="fa fa-balance-scale"></i> 1.28
                                                            - 2.3 - 4.68</strong></span>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control"
                                                               placeholder="Enter Weight" name="message">
                      <span class="input-group-btn">
                        <button data-widget="remove" type="button" class="btn btn-outline">Save</button>
                      </span>
                                                    </div>
                                                </div><!-- /.info-box-content -->
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="info-box bg-maroon">
                                                <span class="info-box-icon"><img class="img-responsive img-circle"
                                                                                 src="img/rabbit3.jpg"
                                                                                 style="max-width: 80%; margin:10px auto;"></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-number">453453 - brown</span>
                                                    <span class="small"><i class="fa fa-balance-scale"></i> 1.28 - 2.3 - 4.68</span>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" placeholder="Weight"
                                                               name="message">
                      <span class="input-group-btn">
                        <button data-widget="remove" type="button" class="btn btn-outline">Save</button>
                      </span>
                                                    </div>
                                                </div><!-- /.info-box-content -->
                                            </div>
                                        </div>


                                        <div class="col-sm-6">
                                            <div class="info-box bg-maroon">
                                                <span class="info-box-icon"><img class="img-responsive img-circle"
                                                                                 src="img/rabbit3.jpg"
                                                                                 style="max-width: 80%; margin:10px auto;"></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-number">453453 - brown</span>
                                                    <span class="small"><i class="fa fa-balance-scale"></i> 1.28 - 2.3 - 4.68</span>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" placeholder="Weight"
                                                               name="message">
                      <span class="input-group-btn">
                        <button data-widget="remove" type="button" class="btn btn-outline">Save</button>
                      </span>
                                                    </div>
                                                </div><!-- /.info-box-content -->
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="info-box bg-aqua">
                                                <span class="info-box-icon"><img class="img-responsive img-circle"
                                                                                 src="img/rabbit3.jpg"
                                                                                 style="max-width: 80%; margin:10px auto;"></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-number">453453 - brown</span>
                                                    <span class="small"><i class="fa fa-balance-scale"></i> 1.28 - 2.3 - 4.68</span>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" placeholder="Weight"
                                                               name="message">
                      <span class="input-group-btn">
                        <button data-widget="remove" type="button" class="btn btn-outline">Save</button>
                      </span>
                                                    </div>
                                                </div><!-- /.info-box-content -->
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="info-box bg-aqua">
                                                <span class="info-box-icon"><img class="img-responsive img-circle"
                                                                                 src="img/rabbit3.jpg"
                                                                                 style="max-width: 80%; margin:10px auto;"></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-number">453453 - brown</span>
                                                    <span class="small"><i class="fa fa-balance-scale"></i> 1.28 - 2.3 - 4.68</span>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" placeholder="Weight"
                                                               name="message">
                      <span class="input-group-btn">
                        <button data-widget="remove" type="button" class="btn btn-outline">Save</button>
                      </span>
                                                    </div>
                                                </div><!-- /.info-box-content -->
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="info-box bg-maroon">
                                                <span class="info-box-icon"><img class="img-responsive img-circle"
                                                                                 src="img/rabbit3.jpg"
                                                                                 style="max-width: 80%; margin:10px auto;"></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-number">453453 - brown</span>
                                                    <span class="small"><i class="fa fa-balance-scale"></i> 1.28 - 2.3 - 4.68</span>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" placeholder="Weight"
                                                               name="message">
                      <span class="input-group-btn">
                        <button data-widget="remove" type="button" class="btn btn-outline">Save</button>
                      </span>
                                                    </div>
                                                </div><!-- /.info-box-content -->
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="info-box bg-maroon">
                                                <span class="info-box-icon"><img class="img-responsive img-circle"
                                                                                 src="img/rabbit3.jpg"
                                                                                 style="max-width: 80%; margin:10px auto;"></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-number">453453 - brown</span>
                                                    <span class="small"><i class="fa fa-balance-scale"></i> 1.28 - 2.3 - 4.68</span>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" placeholder="Weight"
                                                               name="message">
                      <span class="input-group-btn">
                        <button data-widget="remove" type="button" class="btn btn-outline">Save</button>
                      </span>
                                                    </div>
                                                </div><!-- /.info-box-content -->
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="info-box bg-aqua">
                                                <span class="info-box-icon"><img class="img-responsive img-circle"
                                                                                 src="img/rabbit3.jpg"
                                                                                 style="max-width: 80%; margin:10px auto;"></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-number">453453 - brown</span>
                                                    <span class="small"><i class="fa fa-balance-scale"></i> 1.28 - 2.3 - 4.68</span>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" placeholder="Weight"
                                                               name="message">
                      <span class="input-group-btn">
                        <button data-widget="remove" type="button" class="btn btn-outline">Save</button>
                      </span>
                                                    </div>
                                                </div><!-- /.info-box-content -->
                                            </div>
                                        </div>


                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer bg-warning">
                                <button data-dismiss="modal" class="btn btn-default pull-left" type="button">Close
                                </button>
                                <button class="btn btn-warning" type="button">Save changes</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>


                <div class="modal" id="butcher">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-red">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Butcher Litter</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal">

                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-4 control-label" for="inputPassword3">Doe/Litter</label>
                                        <div class="col-sm-8">
                                            <select class="form-control">
                                                <option>Sara - 101</option>
                                                <option>Wilma - 203</option>
                                                <option>Queen - 304</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group col-sm-6">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Date</label>
                                        <div class="col-sm-8" id="datepick">
                                            <div class="input-group date">
                                                <input type="text" class="form-control"><span class="input-group-addon"><i
                                                            class="glyphicon glyphicon-th"></i></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-xs-12">
                                            Select the fryers to be butchered
                                            <table class="table table-hover">
                                                <tbody>
                                                <tr>
                                                    <th class="col-xs-1"><input type="checkbox"></th>
                                                    <th class="col-xs-2">#</th>
                                                    <th class="col-xs-2"></th>
                                                    <th class="col-xs-1">Color</th>
                                                    <th class="text-center">Weight</th>

                                                </tr>
                                                <tr>
                                                    <td><input type="checkbox"></td>
                                                    <td class="bg-aqua disabled">101</td>
                                                    <td class="">
                                                        <img style="max-width: 40px;" src="img/rabbit1.jpg"
                                                             class="img-responsive img-circle">
                                                    </td>

                                                    <td>White</td>
                                                    <td><input type="text" class="form-control" value="4.45"></td>


                                                </tr>
                                                <tr>
                                                    <td><input type="checkbox"></td>
                                                    <td class="bg-maroon disabled">101</td>
                                                    <td class="">
                                                        <img style="max-width: 40px;" src="img/rabbit2.jpg"
                                                             class="img-responsive img-circle">
                                                    </td>

                                                    <td>White</td>
                                                    <td><input type="text" class="form-control" value="4.45"></td>


                                                </tr>
                                                <tr>
                                                    <td><input type="checkbox"></td>
                                                    <td class="bg-maroon disabled">101</td>
                                                    <td class="">
                                                        <img style="max-width: 40px;" src="img/rabbit2.jpg"
                                                             class="img-responsive img-circle">
                                                    </td>

                                                    <td>White</td>
                                                    <td><input type="text" class="form-control" value="4.45"></td>


                                                </tr>
                                                <tr>
                                                    <td><input type="checkbox"></td>
                                                    <td class="bg-aqua disabled">101</td>
                                                    <td class="">
                                                        <img style="max-width: 40px;" src="img/rabbit3.jpg"
                                                             class="img-responsive img-circle">
                                                    </td>

                                                    <td>White</td>
                                                    <td><input type="text" class="form-control" value="4.45"></td>


                                                </tr>


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                </form>
                            </div>
                            <div class="modal-footer bg-danger">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close
                                </button>
                                <button type="button" class="btn btn-danger">Save changes</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>


            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        <!-- modal -->
        <div class="modal" id="new_breeder">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">New Breeder</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputEmail3">Name</label>
                                <div class="col-sm-10"><input type="text" placeholder="Enter ..." class="form-control">

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputEmail3">Cage</label>
                                <div class="col-sm-4"><input type="text" placeholder="Enter ..." class="form-control">

                                </div>

                                <label class="col-sm-2 control-label" for="inputEmail3">Tattoo</label>
                                <div class="col-sm-4"><input type="text" placeholder="Enter ..." class="form-control">

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputPassword3">Father</label>
                                <div class="col-sm-4">
                                    <select class="form-control">
                                        <option>Sara</option>
                                        <option>Wilma</option>
                                        <option>Queen</option>
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label" for="inputPassword3">Mother</label>
                                <div class="col-sm-4">
                                    <select class="form-control">
                                        <option>Sara</option>
                                        <option>Wilma</option>
                                        <option>Queen</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputEmail3">Color</label>
                                <div class="col-sm-4"><input type="text" placeholder="Enter ..." class="form-control">

                                </div>

                                <label class="col-sm-2 control-label" for="inputEmail3">Aquired</label>
                                <div class="col-sm-4">
                                    <div class="input-group date" id="datepick">
                                        <input type="text" class="form-control"><span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-th"></i></span>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="exampleInputFile">Photo</label>
                                <div class="col-sm-10">
                                    <input type="file" id="exampleInputFile">
                                    <p class="help-block">Select photo of litter</p></div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="exampleInputFile">Notes</label>
                                <div class="col-sm-10"><textarea placeholder="Descriptions" rows="3"
                                                                 class="form-control"></textarea></div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer bg-info">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-info">Save changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="pull-right hidden-xs">
                Animal Managment Systems
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2015 <a href="#">BarnTrax</a>.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a>
                </li>
                <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane active" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Recent Activity</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript::;">
                                <i class="menu-icon fa fa-birthday-cake bg-red"></i>
                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
                                    <p>Will be 23 on April 24th</p>
                                </div>
                            </a>
                        </li>
                    </ul><!-- /.control-sidebar-menu -->

                    <h3 class="control-sidebar-heading">Tasks Progress</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript::;">
                                <h4 class="control-sidebar-subheading">
                                    Custom Template Design
                                    <span class="label label-danger pull-right">70%</span>
                                </h4>
                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                </div>
                            </a>
                        </li>
                    </ul><!-- /.control-sidebar-menu -->

                </div><!-- /.tab-pane -->
                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane -->
                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                    <form method="post">
                        <h3 class="control-sidebar-heading">General Settings</h3>
                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Report panel usage
                                <input type="checkbox" class="pull-right" checked="">
                            </label>
                            <p>
                                Some information about this general settings option
                            </p>
                        </div><!-- /.form-group -->
                    </form>
                </div><!-- /.tab-pane -->
            </div>
        </aside><!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
             immediately after the control sidebar -->
        <div style="position: fixed; height: auto;" class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

@endsection